<?php 
$url = $_SERVER['REQUEST_URI'];
?>
<div class="w-[300px] h-full p-4 bg-zinc-50 border-r border-gray-200 flex flex-col gap-2">
  <a
    <?php if (!str_contains($url, 'home.php')) echo 'href="/proyecto-aygbd-generacionT/home.php"'; ?>
    class="w-full py-2 px-4 font-semibold transition-colors rounded-md flex items-center
          <?php if (str_contains($url, 'home.php')) echo 'bg-black text-white cursor-default';
          else echo 'hover:bg-gray-100'; ?>"
  >
    <div class="material-symbols-outlined text-xl mr-3">home</div>
    <p class="pb-0.5">Inicio</p>
  </a>
  <a
    <?php if (!str_contains($url, 'proyectos.php')) echo 'href="/proyecto-aygbd-generacionT/proyectos.php"'; ?>
    class="w-full py-2 px-4 font-semibold transition-colors rounded-md flex items-center
          <?php if (str_contains($url, 'proyectos.php')) echo 'bg-cyan-600 text-white cursor-default';
          else echo 'hover:bg-gray-100'; ?>"
  >
    <div class="material-symbols-outlined text-xl mr-3">tactic</div>
    <p class="pb-0.5">Proyectos</p>
  </a>
  <a
    <?php if (!str_contains($url, 'actividades.php')) echo 'href="/proyecto-aygbd-generacionT/actividades.php"'; ?>
    class="w-full py-2 px-4 font-semibold transition-colors rounded-md flex items-center
          <?php if (str_contains($url, 'actividades.php')) echo 'bg-purple-600 text-white cursor-default';
          else echo 'hover:bg-gray-100'; ?>"
  >
    <div class="material-symbols-outlined text-xl mr-3">checklist</div>
    <p class="pb-0.5">Actividades</p>
  </a>
  <?php if ($es_jefe) { ?>
    <a
      <?php if (!str_contains($url, 'empleados.php')) echo 'href="/proyecto-aygbd-generacionT/empleados.php"'; ?>
      class="w-full py-2 px-4 font-semibold transition-colors rounded-md flex items-center
            <?php if (str_contains($url, 'empleados.php')) echo 'bg-green-600 text-white cursor-default';
            else echo 'hover:bg-gray-100'; ?>"
    >
      <div class="material-symbols-outlined text-xl mr-3">groups</div>
      <p class="pb-0.5">Empleados</p>
    </a>
  <?php } ?>
</div>