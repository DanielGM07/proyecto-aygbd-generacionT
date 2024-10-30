<?php
include('conexion.php');

// Si la cookie "usuario_logeado" esta vacia, lo devuelvo al index
if (empty($_COOKIE['usuario_logeado'])) {
  header('Location: index.php');
}

// Agarro el id de usuario de la cookie
$cookie = explode(";", $_COOKIE['usuario_logeado']);
$id_usuario = $cookie[1];

$query = "SELECT es_jefe FROM usuarios WHERE id = '{$id_usuario}'";
$es_jefe = $conexion->query($query)->fetch_assoc()['es_jefe'];

$query = "SELECT * FROM usuarios WHERE es_jefe = 0";
$result = $conexion->query($query);
$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <title>Empleados</title>
</head>
<body>
  <?php include('header.php'); ?>
  <div class="w-max flex gap-4 mx-auto">
    <?php include('sidebar.php') ?>
    <div class="w-[800px] p-8 shadow-lg rounded-lg">
      <!-- <a href="proyectos.php" class="py-2 px-3 text-sm rounded-xl border-2 border-black hover:bg-gray-100">Volver</a> -->
      <h2 class="text-2xl w-max mx-auto mb-4">Empleados</h2>
      <?php if ($result->num_rows == 0) { ?>
        <p class="w-full h-12 bg-gray-100 rounded-xl flex justify-center items-center">No hay empleados.</p>
      <?php } else { ?>
        <table class="w-full border-2 border-black">
          <thead>
            <tr>
              <th class="border-b border-r border-black p-2">Nombre</th>
              <th class="border-b border-r border-black p-2">Apellido</th>
              <th class="border-b border-r border-black p-2">E-mail</th>
              <th class="border-b border-r border-black p-2">Fecha creación</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Inserto una fila por cada resultado que trajo la query
            // A la cuarta celda le agrego las acciones
            while ($row = $result->fetch_assoc()) {
            ?>
              <tr class="odd:bg-slate-100">
                <td class="border-b border-r border-black px-2 py-1"><?php echo $row['nombre']; ?></td>
                <td class="border-b border-r border-black px-2 py-1"><?php echo $row['apellido']; ?></td>
                <td class="border-b border-r border-black px-2 py-1"><?php echo $row['email']; ?></td>
                <td class="border-b border-r border-black px-2 py-1"><?php echo $row['fecha_creacion']; ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      <?php } ?>
    </div>
  </div>
</body>
</html>