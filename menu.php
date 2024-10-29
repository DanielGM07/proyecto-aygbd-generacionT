<?php
include('conexion.php');

// Agarro el id de usuario de la cookie
$cookie = explode(";", $_COOKIE['usuario_logeado']);
$id_usuario = $cookie[1];

$query = "SELECT es_jefe FROM usuarios WHERE id = '{$id_usuario}'";
$es_jefe = $conexion->query($query)->fetch_assoc()['es_jefe'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Menu</title>
</head>
<body>
  <?php include('header.php'); ?>
  <div class="flex items-center">
    <form method="POST" class="w-[500px] mx-auto p-8 shadow-2xl rounded-xl">
      <h2 class="mb-4 text-2xl font-semibold text-center">Menú</h2>
      <a
        href="proyectos.php"
        class="block w-full py-2 mb-4 bg-cyan-600 hover:bg-cyan-500 text-white text-center font-semibold rounded-xl transition-colors"
      >
        Proyectos
      </a>
      <a
        href="actividades.php"
        class="block w-full py-2 mb-4 bg-purple-600 hover:bg-purple-500 text-white text-center font-semibold rounded-xl transition-colors"
      >
        Actividades
      </a>
      <?php if ($es_jefe) { ?>
        <a
          href="empleados.php"
          class="block w-full py-2 mb-4 bg-green-600 hover:bg-green-500 text-white text-center font-semibold rounded-xl transition-colors"
        >
          Empleados
        </a>
      <?php } ?>
    </div>
  </div>
</body>
</html>