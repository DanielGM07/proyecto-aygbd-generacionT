<?php
include('conexion.php');

// Si la cookie "usuario_logeado" esta vacia, lo devuelvo al index
if(empty($_COOKIE['usuario_logeado'])) {
  header('Location: index.php');
}

// Si no se paso un "id" en la url, lo devuelvo a la matriz
if (!isset($_GET['id']) || $_GET['id'] == '') {
  header('Location: matriz.php');
}

// Agarro el id_matriz del parametro "id"
$id_matriz = $_GET['id'];
// Si el id_matriz es "add", significa que esta agregando un item
$agregando = $id_matriz == 'add';
// Agarro la cookie, la separo por ";" y agarro el primer valor, asi muestro el usuario
$cookie = explode(";", $_COOKIE['usuario_logeado']);
$id_usuario = $cookie[1];

// Si no esta agregando, traigo el item que esta queriendo editar y lo guardo en $item
if (!$agregando) {
  $query = "SELECT * FROM matriz WHERE id = {$id_matriz} AND id_usuario = {$id_usuario}";
  $result = $conexion->query($query);

  if ($result->num_rows > 0) {
    $item = $result->fetch_assoc();
  }
}

// Si existe "save" en el payload de POST, significa que se mando el form
if (isset($_POST['save'])) {
  // Agarro las variables que necesito
  $actividades = $_POST["actividades"];
  $encargado = $_POST["encargado"];
  $nombre_proyecto = $_POST["nombre_proyecto"];
  
  // Si esta agregando, la query va a ser un INSERT
  if ($agregando) {
    $query = "INSERT INTO matriz(id_usuario, actividades, encargado, nombre_proyecto) 
    VALUES ('{$id_usuario}', '{$actividades}', '{$encargado}', '{$nombre_proyecto}')";
  } else {
    // Sino, va a ser un UPDATE
    $query = "UPDATE matriz 
              SET actividades = '{$actividades}', 
                  encargado = '{$encargado}', 
                  nombre_proyecto = '{$nombre_proyecto}' 
              WHERE id = '{$id_matriz}' AND id_usuario = '{$id_usuario}'";
  }

  // Ejecuto la query
  if ($conexion->query($query)) {
    $conexion->close();
    // Y redirecciono a la matriz
    header('Location: matriz.php');
  } else {
    // Hubo un error al guardar, muestro error
    $conexion->close();
    $error = 'Error al guardar los cambios';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Item</title>
</head>
<body>
  <?php include('header.php'); ?>
  <!-- action="item.php?id=<?php echo $id_matriz ?>" -->
  <form method="POST" class="w-[500px] mx-auto p-8 shadow-2xl rounded-xl">
    <h2 class="mb-8 text-2xl font-semibold text-center">
      <?php if ($agregando) echo 'Agregar item'; else echo 'Editar item' ?>
    </h2>
    <div class="mb-4">
      <label for="actividades" class="text-gray-500">Actividades</label>
      <input
        id="actividades"
        name="actividades"
        class="w-full px-2 py-1 border-2 border-black rounded-lg"
        value="<?php echo $item['actividades'] ?? ''; ?>"
        maxlength="1000"
        required
      />
    </div>
    <div class="mb-4">
      <label for="encargado" class="text-gray-500">Encargado</label>
      <input
        id="encargado"
        name="encargado"
        class="w-full px-2 py-1 border-2 border-black rounded-lg"
        value="<?php echo $item['encargado'] ?? ''; ?>"
        maxlength="50"
        required
      />
    </div>
    <div class="mb-6">
      <label for="nombre_proyecto" class="text-gray-500">Proyecto asignado</label>
      <input
        id="nombre_proyecto"
        name="nombre_proyecto"
        class="w-full px-2 py-1 border-2 border-black rounded-lg"
        value="<?php echo $item['nombre_proyecto'] ?? ''; ?>"
        maxlength="50"
        required
      />
    </div>
    <input
      type="submit"
      class="w-full py-2 mb-2
      <?php if ($agregando) echo "bg-emerald-600 hover:bg-emerald-500"; else echo "bg-sky-600 hover:bg-sky-500"; ?>
      text-white text-center font-semibold rounded-xl transition-colors cursor-pointer"
      value="Guardar"
      name="save"
    />
    <a href="matriz.php" class="block w-full py-2 bg-gray-100 text-center rounded-xl">Atrás</a>
    <p class="mt-2 text-red-600 text-center">
      <?php echo $error ?? ''; ?>
    </p>
  </form>
</body>
</html>