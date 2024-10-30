<?php
include('../conexion.php');

// Si la cookie "usuario_logeado" esta vacia, lo devuelvo al index
if(empty($_COOKIE['usuario_logeado'])) {
  header('Location: index.php');
}

// Si no se paso un "id" en la url, lo devuelvo a los proyectos
if (!isset($_GET['id']) || $_GET['id'] == '') {
  header('Location: proyectos.php');
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
  <title>Proyecto</title>
</head>
<body>
  <?php include('../header.php'); ?>
  <div class="w-max flex gap-4 mx-auto">
    <?php include('../sidebar.php') ?>
    <form method="POST" class="w-[800px] p-8 shadow-2xl rounded-xl">
      <h2 class="mb-8 text-2xl font-semibold text-center">
        <?php if ($agregando) echo 'Crear proyecto'; else echo 'Editar proyecto' ?>
      </h2>
      <div class="mb-4">
        <label for="nombre" class="text-gray-500">Nombre</label>
        <input
          id="nombre"
          name="nombre"
          class="w-full px-2 py-1 border-2 border-black rounded-lg"
          value="<?php echo $item['nombre'] ?? ''; ?>"
          maxlength="1000"
          required
        />
      </div>
      <div class="mb-4">
        <label for="descripcion" class="text-gray-500">Descripción</label>
        <input
          id="descripcion"
          name="descripcion"
          class="w-full px-2 py-1 border-2 border-black rounded-lg"
          value="<?php echo $item['descripcion'] ?? ''; ?>"
          maxlength="50"
          required
        />
      </div>
      <div class="mb-4" <?php if ($agregando) echo 'hidden'; ?>>
        <label for="estado" class="text-gray-500">Estado</label>
        <select
          id="estado"
          name="estado"
          class="w-full px-2 py-1 border-2 border-black rounded-lg bg-transparent"
        >
          <option value="1" <?php if ($item['estado'] == 1) echo 'selected'; ?> >Pendiente</option>
          <option value="2" <?php if ($item['estado'] == 2) echo 'selected'; ?> >En progreso</option>
          <option value="3" <?php if ($item['estado'] == 3) echo 'selected'; ?> >Completado</option>
        </select>
      </div>
      <input
        type="submit"
        class="w-full py-2 mb-2
        <?php if ($agregando) echo "bg-cyan-600 hover:bg-cyan-500"; else echo "bg-sky-600 hover:bg-sky-500"; ?>
        text-white text-center font-semibold rounded-xl transition-colors cursor-pointer"
        value="Guardar"
        name="save"
      />
      <a href="../<?php echo $desde; ?>" class="block w-full py-2 bg-gray-100 text-center rounded-xl">Atrás</a>
      <p class="mt-2 text-red-600 text-center">
        <?php echo $error ?? ''; ?>
      </p>
    </form>
  </div>
</body>
</html>