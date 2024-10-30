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

$result = $conexion->query($query);
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
<body>
  <?php include('toast.php'); ?>
  <?php include('header.php'); ?>
  <div class="w-max flex gap-4 mx-auto">
    <?php include('sidebar.php') ?>
    <div class="w-[800px] p-8 shadow-lg rounded-lg">
      <!-- <a href="proyectos.php" class="py-2 px-3 text-sm rounded-xl border-2 border-black hover:bg-gray-100">Volver</a> -->
      <h2 class="text-2xl w-max mx-auto mb-4">Proyectos</h2>
      <?php if ($es_jefe) { ?>
        <a
          href="abm/proyecto.php?id=add"
          class="flex w-max px-8 py-2 mb-4 bg-cyan-600 hover:bg-cyan-500 text-white font-semibold rounded-xl transition-colors"
        >
          <span class="material-symbols-outlined">add</span>Crear proyecto
        </a>
      <?php }
      if ($result->num_rows == 0) { ?>
        <p class="w-full h-12 bg-gray-100 rounded-xl flex justify-center items-center">No tenés proyectos.</p>
      <?php } else { ?>
        <table class="w-full border-2 border-black">
          <thead>
            <tr>
              <th class="border-b border-r border-black p-2">Nombre</th>
              <th class="border-b border-r border-black p-2">Descripción</th>
              <th class="border-b border-r border-black p-2">Fecha creación</th>
              <th class="border-b border-r border-black p-2">Estado</th>
              <th class="border-b border-r border-black p-2">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Inserto una fila por cada resultado que trajo la query
            // A la cuarta celda le agrego las acciones
            while ($row = $result->fetch_assoc()) {
            ?>
              <tr class="<?php if ($row['estado'] == 1) echo 'bg-yellow-100'; 
                else if ($row['estado'] == 2) echo 'bg-cyan-100';
                else if ($row['estado'] == 3) echo 'bg-green-200'; ?>">
                <td class="border-b border-r border-black px-2 py-1"><?php echo $row['nombre']; ?></td>
                <td class="border-b border-r border-black px-2 py-1"><?php echo $row['descripcion']; ?></td>
                <td class="border-b border-r border-black px-2 py-1 whitespace-nowrap"><?php echo $row['fecha_creacion']; ?></td>
                <td
                  class="border-b border-r border-black px-2 py-1 whitespace-nowrap 
                         <?php if ($row['fecha_completado']) echo 'font-semibold'; ?>"
                  <?php if ($row['fecha_completado']) echo "title=\"{$row['fecha_completado']}\""; ?>
                >
                  <?php echo $estados[$row['estado']]; ?>
                </td>
                <td class="border-b border-r border-black px-2 py-1">
                  <div class="flex gap-2 justify-end">
                    <a
                      href="matriz.php?<?php echo "proyecto={$row['id']}&desde=proyectos.php"; ?>"
                      class="material-symbols-outlined text-white w-[1.75rem] h-[1.75rem] text-lg flex justify-center items-center rounded-md
                      bg-orange-600 hover:bg-orange-500 transition-colors"
                      title="Ver matriz"
                    >
                      table
                    </a>
                    <?php if ($es_jefe) { ?>
                    <a
                      href="abm/proyecto.php?id=<?php echo $row['id']; ?>"
                      class="material-symbols-outlined text-white w-[1.75rem] h-[1.75rem] text-lg flex justify-center items-center rounded-md
                      bg-sky-600 hover:bg-sky-500 transition-colors"
                      title="Editar"
                    >
                      edit
                    </a>
                    <button
                      type="button"
                      class="material-symbols-outlined text-white w-[1.75rem] h-[1.75rem] text-lg flex justify-center items-center rounded-md
                      bg-red-600 hover:bg-red-500 transition-colors"
                      title="Eliminar"
                      onclick="if (confirm('¿Estás seguro que queres eliminar esta fila?')) window.location.href = 'proyectos.php?eliminar=<?php echo $row['id']; ?>'"
                    >
                      delete
                    </button>
                    <?php } ?>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      <?php } ?>
    </div>
  </div>
</body>
</html>