<?php
include('conexion.php');

// Si la cookie "usuario_logeado" esta vacia, lo devuelvo al index
if (empty($_COOKIE['usuario_logeado'])) {
  header('Location: index.php');
}

// Agarro el id de usuario de la cookie
$cookie = explode(";", $_COOKIE['usuario_logeado']);
$id_usuario = $cookie[1];

// Si esta el parametro "eliminar", borro el producto en base al id
if (isset($_GET['eliminar'])) {
  // Agarro el id_proyecto del parametro de la url
  $id_proyecto = $_GET["eliminar"];
  if (!is_numeric($id_proyecto)) {
      // Si el id_proyecto pasado por parametro no es un numero, lo llevo a los proyectos sin el parametro
      header('Location: proyectos.php');
  }
  // Ejecuto la query para borrar el producto en base al id pasado por parametro
  $query = "DELETE FROM proyectos WHERE id = {$id_proyecto} and id_jefe = $id_usuario";
  $conexion->query($query);
  
  $accion = 'Proyecto eliminado exitosamente.';
} else if (isset($_GET['accion'])) {
  switch ($_GET['accion']) {
    case 'agrego':
      $accion = 'Proyecto creado exitosamente.';
      break;
    case 'actualizo':
      $accion = 'Proyecto actualizado exitosamente.';
      break;
    case 'usuario_registrado':
      $accion = 'Tu cuenta fue creada existosamente.';
      break;
  }
}

$query = "SELECT es_jefe FROM usuarios WHERE id = '{$id_usuario}'";
$es_jefe = $conexion->query($query)->fetch_assoc()['es_jefe'];

if ($es_jefe) {
  $query = "SELECT p.*
            FROM proyectos p
            JOIN usuarios u ON p.id_jefe = u.id
            WHERE u.id = '{$id_usuario}'";
} else {
  $query = "SELECT p.*
            FROM proyectos p
            JOIN usuario_proyecto up ON p.id = up.id_proyecto
            JOIN usuarios u ON up.id_usuario = u.id
            WHERE u.id = '{$id_usuario}'";
}

$proyectos = $conexion->query($query);
$conexion->close();

$estados = [
  1 => "Pendiente",
  2 => "En progreso",
  3 => "Completado"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <title>Proyectos</title>
</head>
<body class="w-screen h-screen flex flex-col">
  <?php include('toast.php'); ?>
  <?php include('header.php'); ?>
  <div class="h-0 flex-1 w-full flex">
    <?php include('sidebar.php') ?>
    <div class="flex-1 overflow-y-auto">
      <div class="p-8">
        <?php include('proyectos_content.php'); ?>
      </div>
    </div>
  </div>
</body>
</html>