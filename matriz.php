<?php
include('conexion.php');

// Si la cookie "usuario_logeado" esta vacia, lo devuelvo al index
if (empty($_COOKIE['usuario_logeado'])) {
  header('Location: index.php');
}

// Agarro el id de usuario de la cookie
$cookie = explode(";", $_COOKIE['usuario_logeado']);
$id_usuario = $cookie[1];

$desde = $_GET['desde'] ?? 'proyectos.php';

if (isset($_GET['proyecto'])) {
  $id_proyecto = $_GET['proyecto'];
  if (!is_numeric($id_proyecto)) {
    header("Location: {$desde}");
  }
} else {
  header("Location: {$desde}");
}

$location_matriz = "Location: matriz.php?proyecto={$id_proyecto}&desde={$desde}";

// Averiguo si el usuario es_jefe
$query = "SELECT es_jefe FROM usuarios WHERE id = '{$id_usuario}'";
$es_jefe = $conexion->query($query)->fetch_assoc()['es_jefe'];

if (isset($_GET['accion'])) {
  if ($_GET['accion'] == 'actualizo') {
    $accion = 'Actividad actualizada exitosamente.';
  }
}

if (isset($_POST['asociar'])) {
  $asociar = $_POST['asociar'];

  if ($asociar == 'empleado') {
    $id_empleado = $_POST['empleado'];
    if (!is_numeric($id_empleado)) {
      header($location_matriz);
    }
    $query = "INSERT INTO usuario_proyecto (id_proyecto, id_usuario)
              VALUES ({$id_proyecto}, {$id_empleado})";

    $accion = 'Usuario asociado exitosamente.';
  } else if ($asociar == 'actividad') {
    $id_actividad = $_POST['actividad'];
    if (!is_numeric($id_actividad)) {
      header($location_matriz);  
    }
    $fecha_actual = date('Y-m-d H:i:s');
    $query = "INSERT INTO proyecto_actividad(id_proyecto, id_actividad, estado, fecha_asociado)
              VALUES ({$id_proyecto}, {$id_actividad}, 1, '$fecha_actual')";
              
    $accion = 'Actividad asociada exitosamente.';
  }

  $conexion->query($query);
}

if (isset($_POST['asignar'])) {
  $id_actividad = explode(',', $_POST['asignar'])[0];
  $id_empleado = explode(',', $_POST['asignar'])[1];
  
  $fecha_actual = date('Y-m-d H:i:s');
  $query = "INSERT INTO usuario_actividad (id_usuario, id_actividad, id_proyecto, fecha_asignacion)
            VALUES({$id_empleado}, {$id_actividad}, {$id_proyecto}, '{$fecha_actual}')";
  $conexion->query($query);
  
  $accion = 'Actividad asignada exitosamente.';
} else if (isset($_POST['desasignar'])) {
  $id_actividad = explode(',', $_POST['desasignar'])[0];
  $id_empleado = explode(',', $_POST['desasignar'])[1];

  $query = "DELETE FROM usuario_actividad
            WHERE id_usuario = {$id_empleado} AND id_actividad = {$id_actividad} AND id_proyecto = {$id_proyecto}";
  $conexion->query($query);

  $accion = 'Actividad desasignada exitosamente.';
}

if (isset($_POST['eliminar'])) {
  $eliminar = $_POST['eliminar'];

  if ($eliminar == 'actividad') {
    $id_actividad = $_POST['actividad'];
    if (!is_numeric($id_actividad)) {
      header($location_matriz);
    }
    // Limpiamos las asignaciones de la actividad
    $query="DELETE FROM usuario_actividad
            WHERE id_proyecto = {$id_proyecto} AND id_actividad = {$id_actividad}";
    $conexion->query($query);

    $query = "DELETE FROM proyecto_actividad
              WHERE id_proyecto = {$id_proyecto} AND id_actividad = {$id_actividad}";
              
    $accion = 'Actividad eliminada exitosamente.';
  } else if ($eliminar == 'empleado') {
    $id_empleado = $_POST['empleado'];
    if (!is_numeric($id_empleado)) {
      header($location_matriz);
    }
    // Limpiamos las asignaciones del empleado
    $query="DELETE FROM usuario_actividad
            WHERE id_proyecto = {$id_proyecto} AND id_usuario = {$id_empleado}";
    $conexion->query($query);

    $query = "DELETE FROM usuario_proyecto
              WHERE id_proyecto = {$id_proyecto} AND id_usuario = {$id_empleado}";
              
    $accion = 'Empleado eliminado exitosamente.';
  }

  $conexion->query($query);
}

// Traigo la info del proyecto
$query = "SELECT * FROM proyectos WHERE id = {$id_proyecto}";
$proyecto = $conexion->query($query)->fetch_assoc();

// Traigo los empleados disponibles
$query = "SELECT u.id id, CONCAT(u.nombre, ' ', u.apellido) nombre
          FROM usuarios u
          LEFT JOIN usuario_proyecto up ON u.id = up.id_usuario AND up.id_proyecto = {$id_proyecto}
          WHERE es_jefe = 0 AND up.id_proyecto IS NULL";
$empleados_disponibles = $conexion->query($query);

// Traigo los empleados asociados
$query = "SELECT u.id id, CONCAT(u.nombre, ' ', u.apellido) nombre
          FROM usuarios u
          LEFT JOIN usuario_proyecto up ON u.id = up.id_usuario AND up.id_proyecto = {$id_proyecto}
          WHERE es_jefe = 0 AND up.id_proyecto IS NOT NULL";
$empleados_asociados = $conexion->query($query);

// Traigo las actividades disponibles
$query = "SELECT a.id id, a.nombre nombre
          FROM actividades a
          LEFT JOIN proyecto_actividad pa ON a.id = pa.id_actividad AND pa.id_proyecto = {$id_proyecto}
          WHERE pa.id_proyecto IS NULL";
$actividades_disponibles = $conexion->query($query);

// Traigo las actividades asociadas
$query = "SELECT a.id id, a.nombre nombre
          FROM actividades a
          LEFT JOIN proyecto_actividad pa ON a.id = pa.id_actividad AND pa.id_proyecto = {$id_proyecto}
          WHERE pa.id_proyecto IS NOT NULL";
$actividades_asociadas = $conexion->query($query);

// Traigo la info necesaria para la matriz
$query = "SELECT 
            a.id id_actividad,
            a.nombre actividad,
            pa.estado,
            u.id id_usuario,
            CONCAT(u.nombre, ' ', u.apellido) empleado,
            IF(ua.id_actividad IS NOT NULL, 'Asignado', '') asignado,
            ua.fecha_asignacion
          FROM proyecto_actividad pa
          JOIN actividades a ON pa.id_actividad = a.id
          JOIN usuario_proyecto up ON pa.id_proyecto = up.id_proyecto
          JOIN usuarios u ON up.id_usuario = u.id AND u.es_jefe = 0
          LEFT JOIN usuario_actividad ua ON a.id = ua.id_actividad AND u.id = ua.id_usuario AND ua.id_proyecto = pa.id_proyecto
          WHERE pa.id_proyecto = {$id_proyecto}
          ORDER BY a.nombre, u.apellido";
$result = $conexion->query($query);

// Creo la matriz
$matriz = [];
$lista_actividades = [];
$lista_empleados = [];

while ($fila = $result->fetch_assoc()) {
  $actividad = [ "id_actividad" => $fila['id_actividad'], "nombre" => $fila['actividad'], "estado" => $fila['estado'] ];
  $empleado = [ "id_empleado" => $fila['id_usuario'], "nombre" => $fila['empleado'] ];
  $asignado = [ "asignado" => $fila['asignado'], "fecha_asignacion" => $fila['fecha_asignacion'] ];

  if (!isset($lista_actividades[$actividad['id_actividad']])) {
    $lista_actividades[$actividad['id_actividad']] = $actividad;
  }

  if (!isset($lista_empleados[$empleado['id_empleado']])) {
    $lista_empleados[$empleado['id_empleado']] = $empleado;
  }
  
  // Crear la fila para la actividad si no existe
  if (!isset($matriz[$actividad['id_actividad']])) {
      $matriz[$actividad['id_actividad']] = [];
  }

  // Asignar "X" o vacío en la intersección
  $matriz[$actividad['id_actividad']][$empleado['id_empleado']] = $asignado;
}

// Hago la consulta para el gráfico de actividades
$query = "SELECT 
            SUM(CASE WHEN estado = '1' THEN 1 ELSE 0 END) '1',
            SUM(CASE WHEN estado = '2' THEN 1 ELSE 0 END) '2',
            SUM(CASE WHEN estado = '3' THEN 1 ELSE 0 END) '3'
          FROM proyecto_actividad
          WHERE id_proyecto = {$id_proyecto}";
$datos_grafico = $conexion->query($query)->fetch_assoc();

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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js" integrity="sha512-ZwR1/gSZM3ai6vCdI+LVF1zSq/5HznD3ZSTk7kajkaj4D292NLuduDCO1c/NT8Id+jE58KYLKT7hXnbtryGmMg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <title>Matriz</title>
</head>
<body class="w-screen h-screen flex flex-col">
  <?php include('toast.php'); ?>
  <?php include('header.php'); ?>
  <div class="h-0 flex-1 w-full flex">
    <?php include('sidebar.php') ?>
    <div class="flex-1 overflow-y-auto">
      <div class="p-8">
        <div class="flex items-center mb-8">
          <a href="<?php echo $desde; ?>" class="w-max py-1 px-3 text-sm flex items-center rounded-md border border-gray-200 hover:bg-gray-100 transition-colors">
            <span class="material-symbols-outlined text-lg mr-2">chevron_backward</span>  
            <p class="pb-0.5">Volver</p>
          </a>
          <h2 class="text-3xl font-bold ml-4 pb-1">Dashboard de <?php echo $proyecto['nombre']; ?></h2>
        </div>
        <?php if ($es_jefe) { ?>
          <div class="flex gap-8">
            <div class="flex-1">
              <p class="text-2xl mb-4 font-semibold text-center">Empleados</p>
              <div class="w-full border border-gray-200 rounded-lg overflow-hidden">
                <div class="flex border-b border-gray-200">
                  <div class="flex-1 p-4 font-bold text-start border-r border-gray-200">Disponibles</div>
                  <div class="flex-1 p-4 font-bold text-end">Asociados</div>
                </div>
                <div class="h-[16.75rem] overflow-y-auto">
                  <div class="flex">
                    <div class="h-max flex-1 flex-col">
                      <?php if ($empleados_disponibles->num_rows == 0) { ?>
                        <p class="p-4 text-center text-gray-400">No quedan empleados</p>
                      <?php } else while($empleado = $empleados_disponibles->fetch_assoc()) { ?>
                        <div class="p-4 border-b border-gray-200 flex items-center">
                          <p class="flex-1"><?php echo $empleado['nombre']; ?></p>
                          <form method="POST" class="w-max h-max">
                            <input type="hidden" name="proyecto" value="<?php echo $id_proyecto; ?>" hidden />
                            <input type="hidden" name="empleado" value="<?php echo $empleado['id']; ?>" hidden />
                            <button
                              type="submit"
                              class="py-0.5 px-2 border border-gray-200 rounded-md hover:bg-gray-100 transition-colors"
                              title="Asociar empleado"
                              name="asociar"
                              value="empleado"
                            >
                              <span class="material-symbols-outlined text-lg">chevron_right</span>  
                            </button>
                          </form>
                        </div>
                      <?php }?>
                    </div>
                    <div class="w-[1px] bg-gray-200"></div>
                    <div class="flex-1 flex-col">
                      <?php if ($empleados_asociados->num_rows == 0) { ?>
                        <p class="p-4 text-center text-gray-400">No hay empleados asociados</p>
                      <?php } else while($empleado = $empleados_asociados->fetch_assoc()) { ?>
                        <div class="p-4 border-b border-gray-200 flex items-center">
                          <form method="POST" class="w-max h-max">
                            <input type="hidden" name="proyecto" value="<?php echo $id_proyecto; ?>" hidden />
                            <input type="hidden" name="empleado" value="<?php echo $empleado['id']; ?>" hidden />
                            <button
                              type="submit"
                              class="py-0.5 px-2 border border-gray-200 rounded-md hover:bg-gray-100 transition-colors"
                              title="Eliminar empleado"
                              name="eliminar"
                              value="empleado"
                            >
                              <span class="material-symbols-outlined text-lg">chevron_left</span>  
                            </button>
                          </form>
                          <p class="flex-1 text-end"><?php echo $empleado['nombre']; ?></p>
                        </div>
                      <?php }?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex-1">
              <p class="text-2xl mb-4 font-semibold text-center">Actividades</p>
              <div class="w-full border border-gray-200 rounded-lg overflow-hidden">
                <div class="flex border-b border-gray-200">
                  <div class="flex-1 p-4 font-bold text-start border-r border-gray-200">Disponibles</div>
                  <div class="flex-1 p-4 font-bold text-end">Asociadas</div>
                </div>
                <div class="h-[16.75rem] overflow-y-auto">
                  <div class="flex">
                    <div class="h-max flex-1 flex-col">
                      <?php if ($actividades_disponibles->num_rows == 0) { ?>
                        <p class="p-4 text-center text-gray-400">No quedan actividades</p>
                      <?php } else while($actividad = $actividades_disponibles->fetch_assoc()) { ?>
                        <div class="p-4 border-b border-gray-200 flex items-center">
                          <p class="flex-1"><?php echo $actividad['nombre']; ?></p>
                          <form method="POST" class="w-max h-max">
                            <input type="hidden" name="proyecto" value="<?php echo $id_proyecto; ?>" hidden />
                            <input type="hidden" name="actividad" value="<?php echo $actividad['id']; ?>" hidden />
                            <button
                              type="submit"
                              class="py-0.5 px-2 border border-gray-200 rounded-md hover:bg-gray-100 transition-colors"
                              title="Asociar actividad"
                              name="asociar"
                              value="actividad"
                            >
                              <span class="material-symbols-outlined text-lg">chevron_right</span>  
                            </button>
                          </form>
                        </div>
                      <?php }?>
                    </div>
                    <div class="w-[1px] bg-gray-200"></div>
                    <div class="flex-1 flex-col">
                      <?php if ($actividades_asociadas->num_rows == 0) { ?>
                        <p class="p-4 text-center text-gray-400">No hay actividades asociadas</p>
                      <?php } else while($actividad = $actividades_asociadas->fetch_assoc()) { ?>
                        <div class="p-4 border-b border-gray-200 flex items-center">
                          <form method="POST" class="w-max h-max">
                            <input type="hidden" name="proyecto" value="<?php echo $id_proyecto; ?>" hidden />
                            <input type="hidden" name="actividad" value="<?php echo $actividad['id']; ?>" hidden />
                            <button
                              type="submit"
                              class="py-0.5 px-2 border border-gray-200 rounded-md hover:bg-gray-100 transition-colors"
                              title="Eliminar actividad"
                              name="eliminar"
                              value="actividad"
                            >
                              <span class="material-symbols-outlined text-lg">chevron_left</span>  
                            </button>
                          </form>
                          <p class="flex-1 text-end"><?php echo $actividad['nombre']; ?></p>
                        </div>
                      <?php }?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
        <h2 class="text-2xl font-semibold w-max mx-auto mt-8 mb-4">Matriz de responsabilidades</h2>
        <?php if (empty($matriz)) { ?>
          <span class="w-full py-2 bg-gray-50 text-gray-600 rounded-xl flex flex-col justify-center items-center">
            <p>
              Tenés que cumplir los siguientes requisitos para mostrar la matriz:
            </p>
            <div class="flex items-center">
              <?php if ($empleados_asociados->num_rows == 0) { ?>
                <div
                  class="mr-1 material-symbols-outlined text-red-600 text-xl"
                >
                  close
                </div>
                Asociar al menos 1 empleado
              <?php } else { ?>
                <div
                  class="mr-1 material-symbols-outlined text-green-600 text-xl"
                >
                  check
                </div>
                Ya tenés <?php echo $empleados_asociados->num_rows; ?> empleado/s asociados.
              <?php } ?>
            </div>
            <div class="flex items-center">
              <?php if ($actividades_asociadas->num_rows == 0) { ?>
                <div
                  class="mr-1 material-symbols-outlined text-red-600 text-xl"
                >
                  close
                </div>
                Asociar al menos 1 actividad
              <?php } else { ?>
                <div
                  class="mr-1 material-symbols-outlined text-green-600 text-xl"
                >
                  check
                </div>
                Ya tenés <?php echo $actividades_asociadas->num_rows; ?> actividad/es asociadas
              <?php } ?>
            </div>
          </span>
        <?php } else { ?>
          <div class="w-full border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full">
              <thead>
                <tr class="bg-gray-50">
                  <th class="w-28 border-b border-gray-200 p-4 text-start">Actividades</th>
                  <?php foreach(array_keys(reset($matriz)) as $id_empleado) { ?>
                    <th
                      class="w-28 border-b border-gray-200 p-4 group <?php if ($id_empleado == $id_usuario) echo 'text-orange-600'; ?>"
                    >
                      <?php echo $lista_empleados[$id_empleado]['nombre']; ?>
                    </th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <?php
                // Inserto una fila por cada resultado que trajo la query
                // A la cuarta celda le agrego las acciones
                foreach($matriz as $id_actividad => $empleados) {
                  $estado_actividad = $lista_actividades[$id_actividad]['estado'];
                ?>
                  <tr class="border-b border-gray-200 last:border-b-0">
                    <td class="bg-gray-50 p-4 font-bold group"> 
                        <form method="POST">
                          <input type="hidden" name="proyecto" value="<?php echo $id_proyecto; ?>" hidden />
                          <input type="hidden" name="id_actividad" value="<?php echo $id_actividad; ?>" hidden />
                          <a
                            class="hover:text-purple-500 transition-colors"
                            href="abm/actividad.php?id=<?php echo $id_actividad; ?>&proyecto=<?php echo $id_proyecto; ?>&desde=matriz.php?proyecto=<?php echo $id_proyecto; ?>"
                          >
                            <?php echo $lista_actividades[$id_actividad]['nombre']; ?>
                          </a>
                          <p class="<?php if ($estado_actividad == 1) echo 'text-yellow-600';
                            else if ($estado_actividad == 2) echo 'text-cyan-600';
                            else if ($estado_actividad == 3) echo 'text-green-600'; ?>">
                            <?php echo $estados[$estado_actividad]; ?>
                          </p>
                        </form>
                    </td>
                    <?php
                    // Inserto una fila por cada resultado que trajo la query
                    // A la cuarta celda le agrego las acciones
                    foreach($empleados as $id_empleado => $asignado) {
                    ?>
                      <td class="<?php if ($asignado['asignado']) echo 'bg-orange-400 font-semibold'; ?>">
                        <?php if ($es_jefe) { ?>
                        <form method="POST" class="flex justify-center">
                          <input type="hidden" name="proyecto" value="<?php echo $id_proyecto; ?>" hidden />
                          <button
                            type="submit"
                            name="<?php echo $asignado['asignado'] ? 'desasignar' : 'asignar';  ?>"
                            value="<?php echo "{$lista_actividades[$id_actividad]['id_actividad']},{$id_empleado}"; ?>"
                            class="w-full h-max min-h-8"
                          >
                            <div class="flex flex-col">
                              <p><?php echo $asignado['asignado']; ?></p>
                              <p class="text-xs"><?php echo $asignado['fecha_asignacion'] ?></p>
                            </div>
                          </button>
                        </form>
                        <?php } else { ?>
                          <div class="flex flex-col justify-center items-center">
                            <p><?php echo $asignado['asignado']; ?></p>
                            <p class="text-xs"><?php echo $asignado['fecha_asignacion'] ?></p>
                          </div>
                        <?php } ?>
                      </td>
                    <?php } ?>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        <?php } ?>
        <div class="w-full mt-8 b-4">
          <h2 class="text-2xl font-semibold w-max mx-auto mb-4">Resúmen de actividades</h2>
          <div class="w-1/2 mx-auto">
            <canvas id="grafico"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<script>
const data = [
  { estado: 'Pendientes', count: <?php echo $datos_grafico['1'] ?? 0; ?> },
  { estado: 'En progreso', count: <?php echo $datos_grafico['2'] ?? 0; ?> },
  { estado: 'Completadas', count: <?php echo $datos_grafico['3'] ?? 0; ?> },
];

const chart = new Chart(document.getElementById('grafico'), {
  type: 'bar',
  data: {
    labels: data.map(row => row.estado),
    datasets: [
      {
        label: 'Actividades',
        data: data.map(row => row.count),
        backgroundColor: '#fb923c'
      }
    ]
  },
  options: {
    animation: false,
    plugins: {
      legend: {
        display: false
      }
    },
    scales: {
      y: {
        ticks: {
          stepSize: 1
        }
      }
    }
  }
});

function onSelectChange(id) {
  const value = document.getElementById(id).value;
  console.log(value, value === 'null')
  document.getElementById(`submit_asociar_${id}`).disabled = value === 'null' ? 'true' : '';
}
</script>
</html>