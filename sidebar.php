<?php 
$url = $_SERVER['REQUEST_URI'];
?>
<div class="w-[300px] h-max mx-auto p-4 shadow-lg rounded-xl">
  <a
    <?php if (!str_contains($url, 'proyectos.php')) echo 'href="proyectos.php"'; ?>
    class="block w-full py-2 text-center font-semibold transition-colors border-b-2 border-b-gray-200 last:border-b-0 
          <?php if (str_contains($url, 'proyectos.php')) echo 'bg-cyan-600 text-white cursor-default';
          else echo 'hover:bg-gray-100'; ?>"
  >
    Proyectos
  </a>
  <a
    <?php if (!str_contains($url, 'actividades.php')) echo 'href="actividades.php"'; ?>
    class="block w-full py-2 text-center font-semibold transition-colors border-b-2 border-b-gray-200 last:border-b-0 
          <?php if (str_contains($url, 'actividades.php')) echo 'bg-purple-600 text-white cursor-default';
          else echo 'hover:bg-gray-100'; ?>"
  >
    Actividades
  </a>
  <?php if ($es_jefe) { ?>
    <a
      <?php if (!str_contains($url, 'empleados.php')) echo 'href="empleados.php"'; ?>
      class="block w-full py-2 text-center font-semibold transition-colors border-b-2 border-b-gray-200 last:border-b-0 
            <?php if (str_contains($url, 'empleados.php')) echo 'bg-green-600 text-white cursor-default';
            else echo 'hover:bg-gray-100'; ?>"
    >
      Empleados
    </a>
  <?php } ?>
</div>