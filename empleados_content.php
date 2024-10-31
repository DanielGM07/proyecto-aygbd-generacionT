<!-- <a href="proyectos.php" class="py-2 px-3 text-sm rounded-xl border-2 border-black hover:bg-gray-100">Volver</a> -->
<div class="mb-4 flex">
  <h2 class="text-3xl font-bold">Empleados</h2>
</div>
<?php if ($empleados->num_rows == 0) { ?>
  <p class="w-full h-12 bg-gray-100 rounded-xl flex justify-center items-center">No hay empleados.</p>
<?php } else { ?>
  <div class="w-full border border-gray-200 rounded-lg overflow-hidden">
    <table class="w-full">
      <thead>
        <tr>
          <th class="border-b border-gray-200 p-4 text-start">Nombre</th>
          <th class="border-b border-gray-200 p-4 text-start">Apellido</th>
          <th class="border-b border-gray-200 p-4 text-start">Correo electrónico</th>
          <th class="border-b border-gray-200 p-4 text-start">Fecha de creación</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Inserto una fila por cada resultado que trajo la query
        // A la cuarta celda le agrego las acciones
        while ($row = $empleados->fetch_assoc()) {
        ?>
          <tr class="border-b border-gray-200 last:border-b-0">
            <td class="p-4"><?php echo $row['nombre']; ?></td>
            <td class="p-4"><?php echo $row['apellido']; ?></td>
            <td class="p-4"><?php echo $row['email']; ?></td>
            <td class="p-4"><?php echo $row['fecha_creacion']; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
<?php } ?>