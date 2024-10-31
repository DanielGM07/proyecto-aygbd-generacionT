<?php
include('conexion.php');
$es_jefe = false;
if (isset($_COOKIE['usuario_logeado'])) {
  // Agarro la cookie, la separo por ";" y agarro el primer valor, asi muestro el usuario
  $cookie = explode(";", $_COOKIE['usuario_logeado']);
  $usuario = $cookie[0];
  $id_usuario = $cookie[1];
  $query = "SELECT es_jefe FROM usuarios WHERE id = '{$id_usuario}'";
  $es_jefe = $conexion->query($query)->fetch_assoc()['es_jefe'] == 1;
}
?>
<header class="w-full h-16 bg-zinc-50 border-b border-b-gray-200 flex">
  <div class="w-[300px] h-full pl-4 flex items-center border-r border-gray-200">
    <div class="material-symbols-outlined text-2xl pt-0.5">group</div>
    <h1 class="ml-2 text-2xl font-semibold">PROYECTAZO</h1>
  </div>
  <?php if(isset($_COOKIE['usuario_logeado'])) { ?>
    <div class="flex-1 px-8 flex items-center">
      <p class="text-lg font-semibold">
        Hola, <?php echo $usuario; ?> <?php if($es_jefe) { echo '( JEFE )'; } ?>
      </p>
      <div class="flex-1 flex justify-end items-center">
        <a
          href="/proyecto-aygbd-generacionT/logout.php"
          class="flex w-max py-2 px-4 ml-4 bg-red-400 hover:bg-red-300 text-white font-semibold rounded-md transition-colors"
        >
        <div class="material-symbols-outlined text-base mr-4">logout</div>
          Cerrar sesión
        </a>
      </div>
    </div>
  <?php } ?>
</header>