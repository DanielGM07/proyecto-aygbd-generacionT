<?php
include('../conexion.php');

// Si la cookie "usuario_logeado" esta vacia, lo devuelvo al index
if(empty($_COOKIE['usuario_logeado'])) {
  header('Location: index.php');
}

// Si no se paso un "id" en la url, lo devuelvo a las actividades
if (!isset($_GET['id']) || $_GET['id'] == '') {
  header('Location: actividades.php');
}

// Agarro el id_actividad del parametro "id"
$id_actividad = $_GET['id'];
$id_proyecto = isset($_GET['proyecto']) ? $_GET['proyecto'] : 0;
// Si el id_actividad es "add", significa que esta agregando un item
$agregando = $id_actividad == 'add';
// Agarro la cookie, la separo por ";" y agarro el primer valor, asi muestro el usuario
$cookie = explode(";", $_COOKIE['usuario_logeado']);
$id_usuario = $cookie[1];

$query = "SELECT es_jefe FROM usuarios WHERE id = '{$id_usuario}'";
$es_jefe = $conexion->query($query)->fetch_assoc()['es_jefe'];

$desde = $_GET['desde'] ?? 'actividades.php';

// Si no esta agregando, traigo el item que esta queriendo editar y lo guardo en $item
if (!$agregando) {
  if ($id_proyecto) {
    $query = "SELECT a.id, a.nombre, pa.estado
              FROM actividades a
              LEFT JOIN proyecto_actividad pa ON a.id = pa.id_actividad
              WHERE a.id = {$id_actividad} AND pa.id_proyecto = {$id_proyecto}";
  } else {
    $query = "SELECT * FROM actividades WHERE id = {$id_actividad}";
  }
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
    $query = "INSERT INTO actividades(nombre) 
              VALUES ('{$nombre}')";
  } else {
    // Sino, va a ser un UPDATE
    $query = "UPDATE actividades
              SET nombre = '{$nombre}'
              WHERE id = {$id_actividad}";
    if ($id_proyecto) {
      $conexion->query($query);
      $fecha_actual = date('Y-m-d H:i:s');
      $update_fecha_completado = $estado == 3 ? ", fecha_completado = '{$fecha_actual}'" : "";
      $query = "UPDATE proyecto_actividad
                SET estado = {$estado}{$update_fecha_completado}
                WHERE id_proyecto = {$id_proyecto} AND id_actividad = {$id_actividad}";
    }
  }

  // Ejecuto la query
  if ($conexion->query($query)) {
    $conexion->close();
    // Y redirecciono a las actividades
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
  <title>Actividad</title>
</head>
<body>
  <?php include('../header.php'); ?>
  <div class="w-max flex gap-4 mx-auto">
    <?php include('../sidebar.php') ?>
    <form method="POST" class="w-[800px] p-8 shadow-2xl rounded-xl">
      <h2 class="mb-8 text-2xl font-semibold text-center">
        <?php if ($agregando) echo 'Crear actividad'; else echo 'Editar actividad' ?>
      </h2>
      <div class="mb-4">
        <label for="nombre" class="text-gray-500">Nombre</label>
        <input
          id="nombre"
          name="nombre"
          class="w-full px-2 py-1 border-2 border-black rounded-lg read-only:bg-gray-100 read-only:text-gray-600"
          value="<?php echo $item['nombre'] ?? ''; ?>"
          maxlength="1000"
          <?php if (!$es_jefe) echo 'readonly'; ?>
          required
        />
      </div>
      <div class="mb-4" <?php if ($agregando || !$id_proyecto) echo 'hidden'; ?>>
        <label for="estado" class="text-gray-500">Estado</label>
        <select
          id="estado"
          name="estado"
          class="w-full px-2 py-1 border-2 border-black rounded-lg bg-transparent"
        >
          <option value="1" <?php if ($item['estado'] == 1) echo 'selected'; ?> >Pendiente</option>
          <option value="2" <?php if ($item['estado'] == 2) echo 'selected'; ?> >En progreso</option>
          <option value="3" <?php if ($item['estado'] == 3) echo 'selected'; ?> >Completada</option>
        </select>
      </div>
      <input
        type="submit"
        class="w-full py-2 mb-2 bg-purple-600 hover:bg-purple-500 text-white text-center font-semibold rounded-xl transition-colors cursor-pointer"
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