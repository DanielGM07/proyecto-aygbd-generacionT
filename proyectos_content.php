<!-- <a href="proyectos.php" class="py-2 px-3 text-sm rounded-xl border-2 border-gray-200 hover:bg-gray-100">Volver</a> -->
<div class="mb-4 flex">
  <h2 class="text-3xl font-bold">Proyectos</h2>
  <div class="flex-1 flex justify-end">
    <?php if ($es_jefe) { ?>
      <a
        href="abm/proyecto.php?id=add"
        class="w-max px-4 py-2 flex bg-cyan-600 hover:bg-cyan-500 text-white font-semibold rounded-md transition-colors"
      >
        <span class="material-symbols-outlined text-lg mr-4">add</span>
        <p class="pb-0.5">Crear proyecto</p>
      </a>
    <?php } ?>
  </div>
</div>
<?php if ($proyectos->num_rows == 0) { ?>
  <p class="w-full h-14 bg-gray-100 text-gray-600 rounded-xl flex justify-center items-center">No tenés proyectos.</p>
<?php } else { ?>
  <div class="w-full border border-gray-200 rounded-lg overflow-hidden">
    <table class="w-full">
      <thead>
        <tr>
          <th class="border-b border-gray-200 p-4 text-start">Nombre</th>
          <th class="border-b border-gray-200 p-4 text-start">Descripción</th>
          <th class="border-b border-gray-200 p-4 text-start">Fecha de creación</th>
          <th class="border-b border-gray-200 p-4 text-start">Estado</th>
          <th class="border-b border-gray-200 p-4 text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Inserto una fila por cada resultado que trajo la query
        // A la cuarta celda le agrego las acciones
        while ($row = $proyectos->fetch_assoc()) {
        ?>
          <tr class="border-b border-gray-200 last:border-b-0">
            <td class="p-4"><?php echo $row['nombre']; ?></td>
            <td class="p-4"><?php echo $row['descripcion']; ?></td>
            <td class="p-4 whitespace-nowrap"><?php echo $row['fecha_creacion']; ?></td>
            <td
              class="p-4 whitespace-nowrap 
                    <?php if ($row['fecha_completado']) echo 'font-semibold'; ?>"
              <?php if ($row['fecha_completado']) echo "title=\"{$row['fecha_completado']}\""; ?>
            >
              <span class="py-1 px-3 rounded-full text-sm font-semibold 
              <?php if ($row['estado'] == 1) echo 'bg-yellow-100'; 
                else if ($row['estado'] == 2) echo 'bg-cyan-100';
                else if ($row['estado'] == 3) echo 'bg-green-200'; ?>">
                <?php echo $estados[$row['estado']]; ?>
              </span>
            </td>
            <td class="p-4">
              <div class="flex gap-2 justify-end">
                <a
                  href="matriz.php?<?php echo "proyecto={$row['id']}&desde=proyectos.php"; ?>"
                  class="material-symbols-outlined text-white w-[1.75rem] h-[1.75rem] text-lg flex justify-center items-center rounded-md
                  bg-orange-600 hover:bg-orange-500 transition-colors"
                  title="Ver matriz"
                >
                  table
                </a>
                <?php if ($es_jefe) { ?>
                <a
                  href="abm/proyecto.php?id=<?php echo $row['id']; ?>"
                  class="material-symbols-outlined text-white w-[1.75rem] h-[1.75rem] text-lg flex justify-center items-center rounded-md
                  bg-sky-600 hover:bg-sky-500 transition-colors"
                  title="Editar"
                >
                  edit
                </a>
                <button
                  type="button"
                  class="material-symbols-outlined text-white w-[1.75rem] h-[1.75rem] text-lg flex justify-center items-center rounded-md
                  bg-red-600 hover:bg-red-500 transition-colors"
                  title="Eliminar"
                  onclick="if (confirm('¿Estás seguro que queres eliminar esta fila?')) window.location.href = 'proyectos.php?eliminar=<?php echo $row['id']; ?>'"
                >
                  delete
                </button>
                <?php } ?>
              </div>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
<?php } ?>