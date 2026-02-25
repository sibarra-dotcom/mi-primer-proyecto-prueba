<nav id="menu" class="z-40 w-full px-2 lg:px-14 py-2 border-b-2 border-grayMid drop-shadow flex items-center justify-between">
	<nav class="mobile_menu lg:hidden">
		<label for="mobile_checkbox" class="mobile_menu_btn">
			<input type="checkbox" id="mobile_checkbox" class="_checkbox" autocomplete="off">
			<div class="mobile_menu_hbr"></div>
			<ul class="mobile_menu_items flex flex-col divide-y divide-neutral">
				<li class="nav-rh">
					<div class="mx-auto w-1/2">
						<a href="javascript:void(0)" onclick="showView('gestion-dashboard')" class="w-full">Dashboard RH</a>
					</div>
				</li>
				<li class="nav-rh">
					<div class="mx-auto w-1/2">
						<a href="javascript:void(0)" onclick="showView('gestion-vacantes')" class="w-full">Vacantes RH</a>
					</div>
				</li>
				<li class="nav-rh">
					<div class="mx-auto w-1/2">
						<a href="javascript:void(0)" onclick="showView('gestion-candidatos')" class="w-full">Candidatos</a>
					</div>
				</li>
				<li class="nav-rh">
					<div class="mx-auto w-1/2">
						<a href="javascript:void(0)" onclick="showView('calendario-reclutadoras')" class="w-full">Calendario</a>
					</div>
				</li>
				<li class="nav-jefe" style="display:none;">
					<div class="mx-auto w-1/2">
						<a href="javascript:void(0)" onclick="showView('solicitudes-jefe')" class="w-full">Mis Solicitudes</a>
					</div>
				</li>
				<li class="nav-aprobacion" style="display:none;">
					<div class="mx-auto w-1/2">
						<a href="javascript:void(0)" onclick="showView('solicitudes-aprobacion')" class="w-full">Aprobar Solicitudes</a>
					</div>
				</li>
			</ul>
		</label>
	</nav>

	<div class="flex justify-center">
		<a href="<?= base_url('dashboard') ?>"><img src="<?= base_url('img/logo.jpeg') ?>" alt="Logo" class="w-10 h-10 md:w-12 md:h-12 rounded-full"></a>
	</div>

	<div class="hidden lg:flex items-center justify-end px-8 py-3 space-x-4 text-gray">
		<a href="javascript:void(0)" class="nav-rh nav-link-vac px-4 py-2" data-view="gestion-dashboard" onclick="showView('gestion-dashboard')">Dashboard RH</a>
		<a href="javascript:void(0)" class="nav-rh nav-link-vac px-4 py-2" data-view="gestion-vacantes" onclick="showView('gestion-vacantes')">Vacantes RH</a>
		<a href="javascript:void(0)" class="nav-rh nav-link-vac px-4 py-2" data-view="gestion-candidatos" onclick="showView('gestion-candidatos')">Candidatos</a>
		<a href="javascript:void(0)" class="nav-rh nav-link-vac px-4 py-2" data-view="calendario-reclutadoras" onclick="showView('calendario-reclutadoras')">Calendario</a>
		<a href="javascript:void(0)" class="nav-jefe nav-link-vac px-4 py-2" data-view="solicitudes-jefe" onclick="showView('solicitudes-jefe')" style="display:none;">Mis Solicitudes</a>
		<a href="javascript:void(0)" class="nav-aprobacion nav-link-vac px-4 py-2" data-view="solicitudes-aprobacion" onclick="showView('solicitudes-aprobacion')" style="display:none;">Aprobar Solicitudes</a>
	</div>

	<?php echo view('_partials/_nav_user'); ?>
</nav>
