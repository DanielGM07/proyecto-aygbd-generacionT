<?php
include('../conexion.php');

// Si la cookie "usuario_logeado" esta vacia, lo devuelvo al index
if(empty($_COOKIE['usuario_logeado'])) {
  header('Location: ../index.php');
}

// Si no se paso un "id" en la url, lo devuelvo a los proyectos
if (!isset($_GET['id']) || $_GET['id'] == '') {
  header('Location: ../proyectos.php');
}

// Agarro el id_proyecto del parametro "id"
$id_proyecto = $_GET['id'];
// Si el id_proyecto es "add", significa que esta agregando un item
$agregando = $id_proyecto == 'add';
// Agarro la cookie, la separo por ";" y agarro el primer valor, asi muestro el usuario
$cookie = explode(";", $_COOKIE['usuario_logeado']);
$id_usuario = $cookie[1];

$desde = $_GET['desde'] ?? 'proyectos.php';

// Si no esta agregando, traigo el item que esta queriendo editar y lo guardo en $item
if (!$agregando) {
  $query = "SELECT * FROM proyectos WHERE id = {$id_proyecto} AND id_jefe = {$id_usuario}";
  $result = $conexion->query($query);

  if ($result->num_rows > 0) {
    $item = $result->fetch_assoc();
  }
}

// Si existe "save" en el payload de POST, significa que se mando el form
if (isset($_POST['save'])) {
  // Agarro las variables que necesito
  $nombre = $_POST["nombre"];
  $descripcion = $_POST["descripcion"];
  $estado = isset($_POST['estado']) ? $_POST["estado"] : 1;
  
  // Si esta agregando, la query va a ser un INSERT
  if ($agregando) {
    $fecha_actual = date('Y-m-d H:i:s');
    $query = "INSERT INTO proyectos(nombre, descripcion, estado, id_jefe, fecha_creacion) 
    VALUES ('{$nombre}', '{$descripcion}', {$estado}, {$id_usuario}, '{$fecha_actual}')";
  } else {
    // Sino, va a ser un UPDATE
    $fecha_actual = date('Y-m-d H:i:s');
    $update_fecha_completado = 'fecha_completado = ' . ($estado == 3 ? "'{$fecha_actual}'" : 'NULL ');
    $query = "UPDATE proyectos 
              SET nombre = '{$nombre}', 
                  descripcion = '{$descripcion}', 
                  estado = {$estado},
                  {$update_fecha_completado}
              WHERE id = {$id_proyecto} AND id_jefe = {$id_usuario}";
  }

  // Ejecuto la query
  if ($conexion->query($query)) {
    $conexion->close();
    // Y redirecciono a los proyectos
    $desde = $desde . (str_contains($desde, '?') ? '&' : '?') . "accion=" . ($agregando ? 'agrego' : 'actualizo');
    header("Location: ../{$desde}");
  } else {
    // Hubo un error al guardar, muestro error
    $conexion->close();
    $error = 'Error al guardar los cambios';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <title>Proyecto</title>
</head>
<body class="w-screen h-screen flex flex-col">
  <?php include('../header.php'); ?>
  <div class="h-0 flex-1 w-full flex">
    <?php include('../sidebar.php') ?>
    <div class="flex-1 overflow-y-auto">
      <form method="POST" class="p-8">
        <h2 class="mb-4 text-3xl font-bold">
          <?php if ($agregando) echo 'Crear proyecto'; else echo 'Editar proyecto' ?>
        </h2>
        <div class="mb-4">
          <label for="nombre" class="block mb-2 text-sm text-gray-500">Nombre</label>
          <input
            id="nombre"
            name="nombre"
            class="w-full p-2 border-2 border-gray-200 rounded-md"
            value="<?php echo $item['nombre'] ?? ''; ?>"
            maxlength="1000"
            required
          />
        </div>
        <div class="mb-4">
          <label for="descripcion" class="block mb-2 text-sm text-gray-500">Descripción</label>
          <input
            id="descripcion"
            name="descripcion"
            class="w-full p-2 border-2 border-gray-200 rounded-md"
            value="<?php echo $item['descripcion'] ?? ''; ?>"
            maxlength="50"
            required
          />
        </div>
        <div class="mb-4" <?php if ($agregando) echo 'hidden'; ?>>
          <label for="estado" class="block mb-2 text-sm text-gray-500">Estado</label>
          <select
            id="estado"
            name="estado"
            class="w-full p-2 border-2 border-gray-200 rounded-md bg-transparent"
          >
            <option value="1" <?php if ($item['estado'] == 1) echo 'selected'; ?> >Pendiente</option>
            <option value="2" <?php if ($item['estado'] == 2) echo 'selected'; ?> >En progreso</option>
            <option value="3" <?php if ($item['estado'] == 3) echo 'selected'; ?> >Completado</option>
          </select>
        </div>
        <button
          type="submit"
          class="w-full py-2 mb-2 bg-cyan-600 hover:bg-cyan-500 text-white text-center rounded-md transition-colors cursor-pointer
                flex justify-center items-center"
          name="save"
        >
          <div class="material-symbols-outlined text-base mr-4">save</div>
          <span class="pb-0.5">Guardar</span>
        </button>
        <a href="../<?php echo $desde; ?>" class="block w-full py-2 bg-gray-100 text-center rounded-xl">Atrás</a>
        <p class="mt-2 text-red-600 text-center">
          <?php echo $error ?? ''; ?>
        </p>
      </form>
    </div>
  </div>
</body>
</html>