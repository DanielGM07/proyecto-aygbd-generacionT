<?php
include('conexion.php');
include('recaptcha.php');

// Si existe "register" en el payload de POST, significa que se mando el form
if (isset($_POST['register'])) {
  $error = null;
  if (!$recaptcha_resp->isSuccess()) {
    $error = "Tenés que completar el reCaptcha";
  } else {
    // Agarro las variables que necesito
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $contraseña = $_POST["contraseña"];
    $email = $_POST["email"];
    $es_jefe = 0;

    if (isset($_POST['registrar_como_jefe']) && $_POST['registrar_como_jefe'] == '1') {
      $codigo_jefe = $_POST['codigo_jefe'];
      $codigo_hash = hash("sha256", $codigo_jefe);

      $query = "SELECT 1 FROM config WHERE id = 1 AND valor = '{$codigo_hash}'";
      if ($conexion->query($query)->num_rows > 0) {
        $es_jefe = 1;
      } else {
        $error = 'El Código Jefe es incorrecto.';
      }
    }

    if (!$error) {
      // Ejecuto una query para encontrar algun usuario con el email ingresado
      $query = "SELECT * FROM usuarios WHERE email = '{$email}'";
      $result = $conexion->query($query);

      if ($result->num_rows > 0) {
        $conexion->close();
        // Si hay algun usuario con este email, muestro error
        $error = 'Ya existe un usuario con ese email';
      } else {   
        // Sino, inserto un nuevo usuario en la tabla "usuarios"
        $query = "INSERT INTO usuarios(nombre, apellido, contraseña, email, es_jefe) 
        VALUES ('{$nombre}', '{$apellido}', '{$contraseña}', '{$email}', {$es_jefe})";

        if ($conexion->query($query)) {
          // Agarro el id del usuario recien creado para la cookie
          $id = $conexion->insert_id;
          $conexion->close();
          // Guardo la cookie "usuario_logeado" con el usuario y el id separado por un ";"
          setcookie('usuario_logeado', "{$nombre};{$id}");
          // Y redirecciono al menu
          header('Location: menu.php');
        } else {
          // Hubo un error al crear el usuario, muestro error
          $conexion->close();
          $error = 'Error al crear el usuario';
        }
      }
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
  <title>Registro</title>
</head>
<body>
  <?php include('header.php'); ?>
  <form action="registro.php" method="POST" class="w-[500px] mx-auto p-8 shadow-2xl rounded-xl">
    <h2 class="mb-8 text-2xl font-semibold text-center">Registro</h2>
    <div class="mb-4">
      <label for="nombre" class="mb-1 text-gray-500">Nombre</label>
      <input
        id="nombre"
        name="nombre"
        class="w-full px-2 py-1 border-2 border-black rounded-lg"
        maxlength="50"
        required
      />
    </div>
    <div class="mb-4">
      <label for="apellido" class="mb-1 text-gray-500">Apellido</label>
      <input
        id="apellido"
        name="apellido"
        class="w-full px-2 py-1 border-2 border-black rounded-lg"
        maxlength="50"
        required
      />
    </div>
    <div class="mb-4">
      <label for="email" class="mb-1 text-gray-500">Email</label>
      <input
        id="email"
        name="email"
        type="email" class="w-full px-2 py-1 border-2 border-black rounded-lg"
        maxlength="50"
        required
      />
    </div>
    <div class="mb-6">
      <label for="contraseña" class="mb-1 text-gray-500">Contraseña</label>
      <input
        id="contraseña"
        name="contraseña"
        type="password" class="w-full px-2 py-1 border-2 border-black rounded-lg"
        maxlength="50"
        required
      />
    </div>
    <label class="mb-4 inline-flex items-center cursor-pointer">
      <input 
        type="checkbox"
        id="registrar_como_jefe"
        name="registrar_como_jefe"
        value="1"
        class="sr-only peer"
        onclick="handleEsJefeCheckbox(this)"
      >
      <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:cyan-300 dark:peer-focus:cyan-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-cyan-600"></div>
      <span class="ms-3 text-sm font-medium">Registrar como Jefe/a</span>
    </label>
    <div id="codigo_jefe_container" class="mb-4" hidden>
      <label for="codigo_jefe" class="mb-1 text-gray-500">Código Jefe</label>
      <input
        id="codigo_jefe"
        name="codigo_jefe"
        class="w-full px-2 py-1 border-2 border-black rounded-lg"
        maxlength="50"
      />
    </div>
    <div class="g-recaptcha mb-4" data-sitekey="<?php echo $site_key; ?>"></div>
    <input
      type="submit"
      class="w-full py-2 mb-2 bg-cyan-600 hover:bg-cyan-500 text-white text-center font-semibold rounded-xl transition-colors cursor-pointer"
      value="Registrarme"
      name="register"
    />
    <a href="index.php" class="block w-full py-2 bg-gray-100 text-center rounded-xl">Atrás</a>
    <p class="mt-2 text-red-600 text-center">
      <?php echo $error ?? ''; ?>
    </p>
  </form>
</body>
<script>
document.getElementById('registrar_como_jefe').checked = false
function handleEsJefeCheckbox(checkBox) {
  const checked = checkBox.checked
  document.getElementById('codigo_jefe_container').hidden = !checked ? 'hidden' : undefined
  document.getElementById('codigo_jefe').required = checked ? 'true' : undefined
}
</script>
</html>