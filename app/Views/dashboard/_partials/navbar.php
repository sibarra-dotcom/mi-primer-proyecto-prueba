<nav id="menu" class=" z-40 w-full px-2 lg:px-14 py-2 border-b-2 border-grayMid drop-shadow flex items-center justify-between  ">
    <nav class=" mobile_menu lg:hidden " >
      
      <label for="mobile_checkbox" class="mobile_menu_btn ">
        <input type="checkbox"  id="mobile_checkbox" class="_checkbox" autocomplete="off">

        <div class="mobile_menu_hbr"></div>

        <ul class="mobile_menu_items flex flex-col divide-y divide-neutral ">
					<li>
            <div class="mx-auto w-1/2 ">
              <a href="<?= base_url('apps') ?>" class=" w-full">Aplicaciones</a>
            </div>
          </li>
          <li>
            <div class="mx-auto w-1/2 ">
              <a href="<?= base_url('apps') ?>" class=" w-full">Recursos</a>
            </div>
          </li>
          <li>
            <div class="mx-auto w-1/2 ">
              <a href="<?= base_url('apps') ?>" class=" w-full">Tickets</a>
            </div>
          </li>

        </ul>

      </label>

    </nav>

    <div class=" flex justify-center py-3">
      <a href="<?= base_url('dashboard') ?>" > <img src="<?= base_url('img/logo.jpeg') ?>" alt="Logo" class="w-10 h-10 md:w-12 md:h-12 rounded-full "> </a>
    </div>



    <?php echo view('_partials/_nav_user'); ?>

  </nav>