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
  // Agarro el id_actividad del parametro de la url
  $id_actividad = $_GET["eliminar"];
  if (!is_numeric($id_actividad)) {
      // Si el id_actividad pasado por parametro no es un numero, lo llevo a las actividades sin el parametro
      header('Location: actividades.php');
  }
  // Ejecuto la query para borrar el producto en base al id pasado por parametro
  $query = "DELETE FROM actividades WHERE id = {$id_actividad}";
  $conexion->query($query);
  $conexion->close();
  // Lo llevo a las actividades sin el parametro
  header('Location: actividades.php');
  // Ya hice lo que queria hacer, no ejecuto el resto del codigo
  exit();
}

$query = "SELECT es_jefe FROM usuarios WHERE id = '{$id_usuario}'";
$es_jefe = $conexion->query($query)->fetch_assoc()['es_jefe'];

if ($es_jefe) {
  $query = "SELECT  p.id id_proyecto,
                    p.nombre nombre_proyecto,
                    a.id,
                    a.nombre,
                    COALESCE(pa.estado, 0) estado,
                    pa.fecha_asociado,
                    pa.fecha_completado
            FROM actividades a
            LEFT JOIN proyecto_actividad pa ON a.id = pa.id_actividad
            LEFT JOIN proyectos p ON pa.id_proyecto = p.id
            ORDER BY a.id, pa.id_proyecto";
} else {
  $query = "SELECT  ua.id_proyecto,
                    p.nombre nombre_proyecto,
                    a.id,
                    a.nombre,
                    pa.estado,
                    pa.fecha_asociado,
                    pa.fecha_completado
            FROM usuario_actividad ua
            JOIN proyecto_actividad pa ON ua.id_actividad = pa.id_actividad AND ua.id_proyecto = pa.id_proyecto
            JOIN proyectos p ON pa.id_proyecto = p.id
            JOIN actividades a ON ua.id_actividad = a.id
            WHERE ua.id_usuario = {$id_usuario}
            ORDER BY a.id, p.id";
}
$result = $conexion->query($query);
$conexion->close();

$estados = [
  0 => "",
  1 => "Pendiente",
  2 => "En progreso",
  3 => "Completada"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <title>Actividades</title>
</head>
<body>
  <?php include('header.php'); ?>
  <div class="w-max flex gap-4 mx-auto">
    <?php include('sidebar.php') ?>
    <div class="w-[800px] p-8 shadow-lg rounded-lg">
      <!-- <a href="proyectos.php" class="py-2 px-3 text-sm rounded-xl border-2 border-black hover:bg-gray-100">Volver</a> -->
      <h2 class="text-2xl w-max mx-auto mb-4">Actividades</h2>
      <?php if ($es_jefe) { ?>
        <a
          href="abm/actividad.php?id=add"
          class="flex w-max px-8 py-2 mb-4 bg-purple-600 hover:bg-purple-500 text-white font-semibold rounded-xl transition-colors"
        >
          <span class="material-symbols-outlined">add</span>Crear actividad
        </a>
      <?php }
      if ($result->num_rows == 0) { ?>
        <p class="w-full h-12 bg-gray-100 rounded-xl flex justify-center items-center">No tenés actividades.</p>
      <?php } else { ?>
        <table class="w-full border-2 border-black">
          <thead>
            <tr>
              <th class="border-b border-r border-black p-2">Nombre</th>
              <th class="border-b border-r border-black p-2">Proyecto asociado</th>
              <th class="border-b border-r border-black p-2">Fecha asociado</th>
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
                <td class="border-b border-r border-black px-2 py-1">
                  <?php if ($row['nombre_proyecto']) { ?>
                    <a 
                      href="<?php echo "matriz.php?proyecto={$row['id_proyecto']}&desde=actividades.php" ?>"
                      class="font-semibold text-cyan-600 hover:text-cyan-500 transition-colors"  
                    >
                      <?php echo $row['nombre_proyecto']; ?>
                    </a>
                  <?php } else { echo 'Sin asociar'; } ?>
                </td>
                <td class="border-b border-r border-black px-2 py-1 whitespace-nowrap"><?php echo $row['fecha_asociado']; ?></td>
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
                      href="abm/actividad.php?<?php echo "id={$row['id']}&proyecto={$row['id_proyecto']}"; ?>"
                      class="material-symbols-outlined text-white w-[1.75rem] h-[1.75rem] text-lg flex justify-center items-center rounded-md
                      bg-sky-600 hover:bg-sky-500 transition-colors"
                      title="Editar"
                    >
                      edit
                    </a>
                    <?php if ($es_jefe && $row['estado'] == 0) { ?>
                    <button
                      type="button"
                      class="material-symbols-outlined text-white w-[1.75rem] h-[1.75rem] text-lg flex justify-center items-center rounded-md
                      bg-red-600 hover:bg-red-500 transition-colors"
                      title="Eliminar"
                      onclick="if (confirm('¿Estás seguro que queres eliminar esta fila?')) window.location.href = 'actividades.php?eliminar=<?php echo $row['id']; ?>'"
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