<nav class="w-full px-2 md:px-14 py-2 border-b-2 border-grayMid drop-shadow bg-white flex items-center justify-between">
  <div class=" flex justify-center">
    <a href="<?= base_url('dashboard') ?>" > <img src="<?= base_url('img/logo.jpeg') ?>" alt="Logo" class="w-10 h-10 md:w-12 md:h-12 rounded-full "> </a>
  </div>

  <div class="flex items-center justify-end px-8 py-3 space-x-4 text-gray ">
    <a href="<?= base_url('sorteo/lista') ?>" class=" px-4 py-2">Lista entregados</a>
    <a href="<?= base_url('sorteo/inventario') ?>" class=" px-4 py-2">Inventario GIBB SHOP</a>
  </div>

  <?php echo view('_partials/_nav_user'); ?>
</nav>