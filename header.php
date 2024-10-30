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
<header class="w-full h-16 mb-12 shadow-lg">
  <div class="w-[1120px] mx-auto h-full flex justify-center items-center">
    <h1 class="pb-1 text-3xl font-semibold">PROYECTAZO</h1>
    <?php if(isset($_COOKIE['usuario_logeado'])) { ?>
      <div class="flex-1 flex justify-end items-center">
        <p class="text-lg italic">
          Hola, <?php echo $usuario; ?> <?php if($es_jefe) { ?> <strong>( JEFE )</strong> <?php } ?>
        </p>
        <a
          href="logout.php"
          class="flex w-max px-8 py-2 ml-4 bg-red-600 hover:bg-red-500 text-white font-semibold rounded-xl transition-colors"
        >
          Cerrar Sesión
        </a>
      </div>
    <?php } ?>
  </div>
</header>