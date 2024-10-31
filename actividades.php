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
  
  $accion = 'Actividad eliminada exitosamente.';
} else if (isset($_GET['accion'])) {
  $get_accion = $_GET['accion'];
  if ($get_accion == 'agrego') {
    $accion = 'Actividad creada exitosamente.';
  } else if ($get_accion == 'actualizo') {
    $accion = 'Actividad actualizada exitosamente.';
  }
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
<body class="w-screen h-screen flex flex-col">
  <?php include('toast.php'); ?>
  <?php include('header.php'); ?>
  <div class="h-0 flex-1 w-full flex">
    <?php include('sidebar.php') ?>
    <div class="flex-1 overflow-y-auto">
      <div class="p-8">
        <!-- <a href="proyectos.php" class="py-2 px-3 text-sm rounded-xl border-2 border-gray-200 hover:bg-gray-100">Volver</a> -->
        <div class="mb-4 flex">
          <h2 class="text-3xl font-bold">Actividades</h2>
          <div class="flex-1 flex justify-end">
            <?php if ($es_jefe) { ?>
              <a
                href="abm/actividad.php?id=add"
                class="w-max px-4 py-2 flex bg-purple-600 hover:bg-purple-500 text-white font-semibold rounded-md transition-colors"
              >
                <span class="material-symbols-outlined text-lg mr-4">add</span>
                <p class="pb-0.5">Crear actividad</p>
              </a>
            <?php } ?>
          </div>
        </div>
        <?php if ($result->num_rows == 0) { ?>
          <p class="w-full h-14 bg-gray-100 text-gray-600 rounded-xl flex justify-center items-center">No tenés actividades.</p>
        <?php } else { ?>
          <div class="w-full border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full">
              <thead>
                <tr>
                  <th class="border-b border-gray-200 p-4 text-start">Nombre</th>
                  <th class="border-b border-gray-200 p-4 text-start">Proyecto asociado</th>
                  <th class="border-b border-gray-200 p-4 text-start">Fecha de asociado</th>
                  <th class="border-b border-gray-200 p-4 text-start">Estado</th>
                  <th class="border-b border-gray-200 p-4 text-end">Acciones</th>
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
                    <td class="p-4">
                      <?php if ($row['nombre_proyecto']) { ?>
                        <a 
                          href="<?php echo "matriz.php?proyecto={$row['id_proyecto']}&desde=actividades.php" ?>"
                          class="font-semibold text-cyan-600 hover:text-cyan-500 transition-colors"  
                        >
                          <?php echo $row['nombre_proyecto']; ?>
                        </a>
                      <?php } else { echo 'Sin asociar'; } ?>
                    </td>
                    <td class="p-4 whitespace-nowrap"><?php echo $row['fecha_asociado']; ?></td>
                    <td
                      class="p-4 whitespace-nowrap 
                            <?php if ($row['fecha_completado']) echo 'font-semibold'; ?>"
                      <?php if ($row['fecha_completado']) echo "title=\"{$row['fecha_completado']}\""; ?>
                    >
                      <span class="py-1 px-3 rounded-full text-sm font-semibold 
                      <?php if ($row['estado'] == 1) echo 'bg-yellow-100'; 
                        else if ($row['estado'] == 2) echo 'bg-cyan-100';
                        else if ($row['estado'] == 3) echo 'bg-green-200'; ?>">
                        <?php echo $estados[$row['estado']]; ?>
                      </span>
                    </td>
                    <td class="p-4">
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
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</body>
</html>