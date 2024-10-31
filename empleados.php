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
<body class="w-screen h-screen flex flex-col">
  <?php include('header.php'); ?>
  <div class="h-0 flex-1 w-full flex">
    <?php include('sidebar.php') ?>
    <div class="flex-1 overflow-y-auto">
      <div class="p-8">
        <!-- <a href="proyectos.php" class="py-2 px-3 text-sm rounded-xl border-2 border-black hover:bg-gray-100">Volver</a> -->
        <div class="mb-4 flex">
          <h2 class="text-3xl font-bold">Empleados</h2>
        </div>
        <?php if ($result->num_rows == 0) { ?>
          <p class="w-full h-12 bg-gray-100 rounded-xl flex justify-center items-center">No hay empleados.</p>
        <?php } else { ?>
          <div class="w-full border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full">
              <thead>
                <tr>
                  <th class="border-b border-gray-200 p-4 text-start">Nombre</th>
                  <th class="border-b border-gray-200 p-4 text-start">Apellido</th>
                  <th class="border-b border-gray-200 p-4 text-start">Correo electrónico</th>
                  <th class="border-b border-gray-200 p-4 text-start">Fecha de creación</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Inserto una fila por cada resultado que trajo la query
                // A la cuarta celda le agrego las acciones
                while ($row = $result->fetch_assoc()) {
                ?>
                  <tr class="border-b border-gray-200 last:border-b-0">
                    <td class="p-4"><?php echo $row['nombre']; ?></td>
                    <td class="p-4"><?php echo $row['apellido']; ?></td>
                    <td class="p-4"><?php echo $row['email']; ?></td>
                    <td class="p-4"><?php echo $row['fecha_creacion']; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</body>
</html>