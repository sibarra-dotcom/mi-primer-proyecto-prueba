<nav id="menu" class="w-full px-2 lg:px-14 py-2 border-b-2 border-grayMid drop-shadow flex items-center justify-between" style="position:relative;z-index:60;">
	<nav class="mobile_menu lg:hidden">
		<label for="mobile_checkbox" class="mobile_menu_btn">
			<input type="checkbox" id="mobile_checkbox" class="_checkbox" autocomplete="off">
			<div class="mobile_menu_hbr"></div>
			<ul class="mobile_menu_items flex flex-col divide-y divide-neutral">
				<li>
					<div class="mx-auto w-1/2">
						<a href="javascript:void(0)" onclick="showView('expedientes')" class="w-full">Expedientes</a>
					</div>
				</li>
			</ul>
		</label>
	</nav>

	<div class="flex justify-center">
		<a href="<?= base_url('dashboard') ?>"><img src="<?= base_url('img/logo.jpeg') ?>" alt="Logo" class="w-10 h-10 md:w-12 md:h-12 rounded-full"></a>
	</div>

	<div class="hidden lg:flex items-center justify-center flex-1 px-8 py-3 space-x-6 text-gray">
		<a href="javascript:void(0)" class="nav-link-salud nav-active" data-view="expedientes" onclick="showView('expedientes')">
			<i class="fas fa-clipboard-list" style="margin-right:4px;"></i> Expedientes
		</a>
	</div>

	<?php echo view('_partials/_nav_user'); ?>
</nav>
