<?php
include('conexion.php');
include('recaptcha.php');

// Si existe "login" en el payload de POST, significa que se mando el form
if (isset($_POST['login'])) {
  if (!isset($recaptcha_resp) || !$recaptcha_resp->isSuccess()) {
    $error = "Tenés que completar el reCaptcha";
  } else {
    // Agarro las variables que necesito
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];

    // Ejecuto la query para encontrar al usuario en la base de datos
    $query = "SELECT * FROM usuarios WHERE email = '{$email}' AND contraseña = '{$contraseña}'";
    $result = $conexion->query($query);

    $conexion->close();
    if ($result->num_rows == 0) {
        // Si la query no me devolvio nada, el usuario no existe
        $error = 'No existe el usuario ingresado';
    } else {
        // Pero si devolvió, agarro el primer resultado
        $primer_resultado = $result->fetch_assoc();
        // Agarro su id y nombre
        $id = $primer_resultado["id"];
        $nombre = $primer_resultado["nombre"];
        // Guardo la cookie "usuario_logeado" con el nombre y el id separado por un ";"
        setcookie('usuario_logeado', "{$nombre};{$id}");
        // Y redirecciono al home
        header('Location: home.php');
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <title>Inicio</title>
</head>
<body>
  <header class="w-full h-16 mb-12 border-b border-b-gray-200 flex justify-center items-center">
    <div class="material-symbols-outlined text-2xl pt-0.5">group</div>
    <h1 class="ml-2 text-2xl font-semibold">PROYECTAZO</h1>
  </header>
  <div class="w-[1120px] mx-auto flex">
    <div class="flex-1 pt-28">
      <h2 class="text-6xl font-bold mb-4">Bienvenido a Proyectazo</h2>
      <p class="text-gray-600 text-lg">Bienvenido al proyecto de Daniel&nbsp;Guibarra&nbsp;Mendoza, Santiago&nbsp;Burd, Agustin&nbsp;Pancrazi, Tomas&nbsp;Garcia y Juan&nbsp;Fernandez.</p>
      <p class="text-gray-600 text-lg mb-6">Al ingresar, vas a poder manejar proyectos y administrar una Matriz de Responsabilidad por cada uno.</p>
    </div>
    <form method="POST" class="w-[500px] p-6 rounded-lg border border-gray-200">
      <h2 class="mb-2 text-2xl font-semibold">Acceso a la plataforma</h2>
      <p class="text-sm">Iniciá sesión para acceder a tu cuenta o registrate si aún no tenés una.</p>
      <div class="mt-6 mb-4">
        <label for="email" class="block mb-2 text-sm text-gray-500">Email</label>
        <input
          id="email"
          name="email"
          class="w-full px-2 py-1 border-2 border-gray-200 rounded-md"
          placeholder="tu@email.com"
          required
        />
      </div>
      <div class="mb-4">
        <label for="contraseña" class="block mb-2 text-sm text-gray-500">Contraseña</label>
        <input
          id="contraseña"
          name="contraseña"
          type="password"
          class="w-full px-2 py-1 border-2 border-gray-200 rounded-md"
          required
        />
      </div>
      <div class="g-recaptcha mb-4" data-sitekey="<?php echo $site_key; ?>"></div>
      <p class="my-2 text-red-600 text-center">
        <?php echo $error ?? ''; ?>
      </p>
      <button
        type="submit"
        class="w-full py-2 bg-cyan-600 hover:bg-cyan-500 text-white text-center rounded-md transition-colors cursor-pointer
              flex justify-center items-center"
        name="login"
      >
        <span class="pb-0.5">Iniciar sesión</span>
        <div class="material-symbols-outlined text-base ml-4">arrow_forward</div>
      </button>
      <div class="my-4 flex items-center">
        <div class="flex-1 h-0.5 bg-gray-200"></div>
        <p class="px-2 uppercase text-gray-600 text-xs">¿Todavía no tenes una cuenta?</p>
        <div class="flex-1 h-0.5 bg-gray-200"></div>
      </div>
      <div class="mb-4">
        <a
          href="registro.php"
          class="block w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-center rounded-md transition-colors"
        >
          Registrate
        </a>
      </div>
    </div>
  </div>
</body>
</html>