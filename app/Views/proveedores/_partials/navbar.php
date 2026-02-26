<nav class="w-full px-2 md:px-14 py-2 border-b-2 border-grayMid drop-shadow bg-white flex items-center justify-between">
  <div class=" flex justify-center">
    <a href="<?= base_url('dashboard') ?>" > <img src="<?= base_url('img/logo.jpeg') ?>" alt="Logo" class="w-10 h-10 md:w-12 md:h-12 rounded-full "> </a>
  </div>

  <div class="flex items-center justify-end px-8 py-3 space-x-4 text-gray ">
    <a href="<?= base_url('cotizar') ?>" class=" px-4 py-2">Formulario de Cotizaciones</a>
    <a href="<?= base_url('busqueda') ?>" class=" px-4 py-2">Materias y Materiales</a>
    <a href="<?= base_url('inactive/link') ?>" class=" px-4 py-2">Aprobaciones</a>
    <a href="<?= base_url('inactive/link') ?>" class=" px-4 py-2">Generar Cotización</a>
    <a href="<?= base_url('inactive/link') ?>" class=" px-4 py-2">Cotizaciones Clientes</a>
  </div>

  <?php echo view('_partials/_nav_user'); ?>
</nav>