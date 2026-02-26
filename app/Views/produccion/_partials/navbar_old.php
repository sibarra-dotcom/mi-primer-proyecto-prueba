<nav id="menu" class=" z-40 w-full px-2 lg:px-14 py-2 border-b-2 border-grayMid drop-shadow flex items-center justify-between  ">
    <nav class=" mobile_menu lg:hidden " >
      
      <label for="mobile_checkbox" class="mobile_menu_btn ">
        <input type="checkbox"  id="mobile_checkbox" class="_checkbox" autocomplete="off">

        <div class="mobile_menu_hbr"></div>

        <ul class="mobile_menu_items flex flex-col divide-y divide-neutral ">
          <!-- <li>
            <div class="mx-auto w-1/2 ">
              <a href="<?= base_url('produccion/registro_diario1') ?>" class=" w-full">Reporte de Procesos</a>
            </div>
          </li>
          <li>
            <div class="mx-auto w-1/2 ">
              <a href="<?= base_url('produccion/procesos') ?>" class=" w-full">Procesos</a>
            </div>
          </li> -->
          <li>
            <div class="mx-auto w-1/2 ">
							<a href="<?= base_url('produccion/productos') ?>" class=" w-full">Productos</a>
            </div>
          </li>
          <li>
            <div class="mx-auto w-1/2 ">
              <a href="<?= base_url('produccion/personal_old') ?>" class=" w-full">Personal</a>
            </div>
          </li>
					<!-- <li>
            <div class="mx-auto w-1/2 ">
              <a href="<?= base_url('produccion/lista') ?>" class=" w-full">Lista Procesos Reportados</a>
            </div>
          </li> -->
					<li>
            <div class="mx-auto w-1/2 ">
              <a href="<?= base_url('produccion/certif') ?>" class=" w-full">Certificado COA</a>
            </div>
          </li>
        </ul>

      </label>

    </nav>

    <div class=" flex justify-center">
      <a href="<?= base_url('dashboard') ?>" > <img src="<?= base_url('img/logo.jpeg') ?>" alt="Logo" class="w-10 h-10 md:w-12 md:h-12 rounded-full "> </a>
    </div>

    <div class="hidden lg:flex items-center justify-end px-8 py-3 space-x-4 text-gray ">
			<!-- <a href="<?= base_url('produccion/registro_diario1') ?>" class="px-4 py-2">Reporte de Procesos</a> -->
			<!-- <a href="<?= base_url('produccion/procesos') ?>" class="px-4 py-2">Procesos</a> -->
			<a href="<?= base_url('produccion/productos') ?>" class="px-4 py-2">Productos</a>
			<!-- <a href="<?= base_url('produccion/personal_old') ?>" class="px-4 py-2">Personal</a> -->
			<a href="<?= base_url('informeres/ordenes_informe') ?>" class="px-4 py-2">Informe Resultados</a>
			<!-- <a href="<?= base_url('produccion/liberacion') ?>" class="px-4 py-2">Liberación</a> -->
			<a href="<?= base_url('liberaciones/ordenes_liberacion') ?>" class="px-4 py-2">Liberación</a>
			<a href="<?= base_url('produccion/certif') ?>" class="px-4 py-2">Certificado COA</a>
			<!-- <a href="<?= base_url('produccion/lista') ?>" class="px-4 py-2">Lista Proc. Reportados</a> -->
    </div>

    <?php echo view('_partials/_nav_user'); ?>

  </nav>

