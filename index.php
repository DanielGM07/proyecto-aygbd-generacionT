<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Inicio</title>
</head>
<body>
  <?php include('header.php'); ?>
  <div class="flex items-center">
    <form method="POST" class="w-[500px] mx-auto p-8 shadow-2xl rounded-xl">
      <h2 class="mb-4 text-2xl font-semibold text-center">Página principal</h2>
      <p class="text-lg">Bienvenido al proyecto de Daniel Guibarra Mendoza.</p>
      <p class="text-lg mb-6">Al ingresar, vas a poder manejar proyectos y administrar una Matriz de Responsabilidad por cada uno.</p>
      <div class="mb-4">
        <p class="pb-1 text-center">¿Ya tenés una cuenta?</p>
        <a
          href="iniciar_sesion.php"
          class="block w-full py-2 bg-cyan-600 hover:bg-cyan-500 text-white text-center font-semibold rounded-xl transition-colors"
        >
          Iniciar sesión
        </a>
      </div>
      <div class="mb-4">
        <p class="pb-1 text-center">¿Todavía no tenes una cuenta?</p>
        <a
          href="registro.php"
          class="block w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-center font-semibold rounded-xl transition-colors"
        >
          Registrate
        </a>
      </div>
    </div>
  </div>
</body>
</html>