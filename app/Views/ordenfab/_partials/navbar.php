<nav id="menu" class=" z-40 w-full px-2 lg:px-14 py-2 border-b-2 border-grayMid drop-shadow flex items-center justify-between  ">
    <nav class=" mobile_menu lg:hidden " >
      
      <label for="mobile_checkbox" class="mobile_menu_btn ">
        <input type="checkbox"  id="mobile_checkbox" class="_checkbox" autocomplete="off">

        <div class="mobile_menu_hbr"></div>

        <ul class="mobile_menu_items flex flex-col divide-y divide-neutral ">
          <li>
            <div class="mx-auto w-1/2 ">
              <a href="<?= base_url('ordenfab/ordenes_lista') ?>" class=" w-full">Orden de Fabricación <?= $controller ?></a>
            </div>
          </li>
          <li>
            <div class="mx-auto w-1/2 ">
              <!-- <a href="<?= base_url('ordenfab/productos') ?>" class=" w-full">Reporte de Órdenes Fabric.</a> -->
              <a href="<?= base_url('ordenfab/reporte_ordenfab') ?>" class=" w-full">Reporte de Órdenes Fabric.</a>
            </div>
          </li>
          <li>
            <div class="mx-auto w-1/2 ">
              <!-- <a href="<?= base_url('ordenfab/procesos') ?>" class=" w-full">Reporte Meta Diaria</a> -->
              <a href="<?= base_url('ordenfab/reporte_meta') ?>" class=" w-full">Reporte Meta Diaria</a>
            </div>
          </li>
          <li>
            <div class="mx-auto w-1/2 ">
              <!-- <a href="<?= base_url('ordenfab/lista') ?>" class=" w-full">Dashboard Producción</a> -->
              <a href="<?= base_url('ordenfab/reporte_dashboard') ?>" class=" w-full">Dashboard Producción</a>
            </div>
          </li>
          <li>
            <div class="mx-auto w-1/2 ">
              <a href="<?= base_url('ordenfab/personal') ?>" class=" w-full">Personal</a>
            </div>
          </li>
        </ul>

      </label>

    </nav>

    <div class=" flex justify-center">
      <a href="<?= base_url('dashboard') ?>" > <img src="<?= base_url('img/logo.jpeg') ?>" alt="Logo" class="w-10 h-10 md:w-12 md:h-12 rounded-full "> </a>
    </div>

    <div class="hidden lg:flex items-center justify-end px-8 py-3 space-x-4 text-gray ">
			<a href="<?= base_url('ordenfab/ordenes_lista') ?>" class="px-4 py-2">Orden de Fabricación</a>
			<a href="<?= base_url('ordenfab/reporte_ordenfab') ?>" class="px-4 py-2">Reporte de Órdenes Fabric.</a>
			<a href="<?= base_url('ordenfab/reporte_meta') ?>" class="px-4 py-2">Reporte Meta Diaria</a>
			<a href="<?= base_url('ordenfab/reporte_dashboard') ?>" class="px-4 py-2">Dashboard Producción</a>
			<a href="<?= base_url('ordenfab/personal') ?>" class="px-4 py-2">Personal</a>
    </div>

    <?php echo view('_partials/_nav_user'); ?>

  </nav>

