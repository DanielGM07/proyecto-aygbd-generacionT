<?php
include('conexion.php');
include('recaptcha.php');

// Si existe "login" en el payload de POST, significa que se mando el form
if (isset($_POST['login'])) {
  if (!$recaptcha_resp->isSuccess()) {
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
        // Y redirecciono al menu
        header('Location: menu.php');
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
  <title>Inicio de sesión</title>
</head>
<body>
  <?php include('header.php'); ?>
  <form method="POST" class="w-[500px] mx-auto p-8 shadow-2xl rounded-xl">
    <h2 class="mb-8 text-2xl font-semibold text-center">Inicio de sesión</h2>
    <div class="mb-4">
      <label for="email" class="text-gray-500">Email</label>
      <input
        id="email"
        name="email"
        class="w-full px-2 py-1 border-2 border-black rounded-lg"
        required
      />
    </div>
    <div class="mb-6">
      <label for="contraseña" class="text-gray-500">Contraseña</label>
      <input
        id="contraseña"
        name="contraseña"
        type="password"
        class="w-full px-2 py-1 border-2 border-black rounded-lg"
        required
      />
    </div>
    <div class="g-recaptcha mb-4" data-sitekey="<?php echo $site_key; ?>"></div>
    <input
      type="submit"
      class="w-full py-2 mb-2 bg-cyan-600 hover:bg-cyan-500 text-white text-center font-semibold rounded-xl transition-colors cursor-pointer"
      value="Iniciar sesión"
      name="login"
    />
    <a href="index.php" class="block w-full py-2 bg-gray-100 text-center rounded-xl">Atrás</a>
    <p class="mt-2 text-red-600 text-center">
      <?php echo $error ?? ''; ?>
    </p>
  </form>
</body>
</html>