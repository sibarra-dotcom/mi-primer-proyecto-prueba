<nav id="menu" class="w-full px-2 lg:px-14 py-2 border-b-2 border-grayMid drop-shadow flex items-center justify-between" style="position:relative;z-index:60;">
	<nav class="mobile_menu lg:hidden">
		<label for="mobile_checkbox" class="mobile_menu_btn">
			<input type="checkbox" id="mobile_checkbox" class="_checkbox" autocomplete="off">
			<div class="mobile_menu_hbr"></div>
			<ul class="mobile_menu_items flex flex-col divide-y divide-neutral">
				<!-- Sección Vacantes (mobile) -->
				<li class="nav-rh mobile-section-header">
					<div class="mx-auto w-1/2">
						<button class="w-full mobile-section-toggle" onclick="toggleMobileSection(this)">
							Vacantes <svg class="mobile-chevron" width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</button>
					</div>
				</li>
				<li class="nav-rh mobile-section-item mobile-section-vacantes">
					<div class="mx-auto w-1/2">
						<a href="javascript:void(0)" onclick="showView('gestion-vacantes')" class="w-full">Gestión de vacantes</a>
					</div>
				</li>
				<li class="nav-rh mobile-section-item mobile-section-vacantes">
					<div class="mx-auto w-1/2">
						<a href="javascript:void(0)" onclick="showView('gestion-candidatos')" class="w-full">Candidatos</a>
					</div>
				</li>
				<li class="nav-rh mobile-section-item mobile-section-vacantes">
					<div class="mx-auto w-1/2">
						<a href="javascript:void(0)" onclick="showView('calendario-reclutadoras')" class="w-full">Calendario</a>
					</div>
				</li>
				<li class="nav-rh mobile-section-item mobile-section-vacantes">
					<div class="mx-auto w-1/2">
						<a href="javascript:void(0)" onclick="showView('gestion-dashboard')" class="w-full">Dashboard</a>
					</div>
				</li>
				<!-- Sección Personal (mobile) -->
				<li class="nav-rh mobile-section-header">
					<div class="mx-auto w-1/2">
						<button class="w-full mobile-section-toggle" onclick="toggleMobileSection(this)" disabled>
							Personal <span class="mobile-badge-soon">Próximamente</span>
						</button>
					</div>
				</li>
				<!-- Jefe / Aprobación -->
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

	<div class="hidden lg:flex items-center justify-end px-8 py-3 space-x-6 text-gray">
		<!-- Dropdown Vacantes -->
		<div class="nav-rh nav-dropdown" id="dropdown-vacantes">
			<button class="nav-dropdown-toggle nav-link-vac px-4 py-2" onclick="toggleDropdown('dropdown-vacantes')">
				Vacantes <svg class="nav-dropdown-chevron" width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</button>
			<div class="nav-dropdown-menu">
				<a href="javascript:void(0)" class="nav-link-vac nav-dropdown-item" data-view="gestion-vacantes" onclick="showView('gestion-vacantes')">Gestión de vacantes</a>
				<a href="javascript:void(0)" class="nav-link-vac nav-dropdown-item" data-view="gestion-candidatos" onclick="showView('gestion-candidatos')">Candidatos</a>
				<a href="javascript:void(0)" class="nav-link-vac nav-dropdown-item" data-view="calendario-reclutadoras" onclick="showView('calendario-reclutadoras')">Calendario</a>
				<a href="javascript:void(0)" class="nav-link-vac nav-dropdown-item" data-view="gestion-dashboard" onclick="showView('gestion-dashboard')">Dashboard</a>
			</div>
		</div>
		<!-- Dropdown Personal -->
		<div class="nav-rh nav-dropdown" id="dropdown-personal">
			<button class="nav-dropdown-toggle nav-link-vac px-4 py-2 nav-dropdown-disabled" disabled>
				Personal <span class="nav-badge-soon">Próximamente</span>
			</button>
		</div>
		<!-- Jefe / Aprobación (sin cambios) -->
		<a href="javascript:void(0)" class="nav-jefe nav-link-vac px-4 py-2" data-view="solicitudes-jefe" onclick="showView('solicitudes-jefe')" style="display:none;">Mis Solicitudes</a>
		<a href="javascript:void(0)" class="nav-aprobacion nav-link-vac px-4 py-2" data-view="solicitudes-aprobacion" onclick="showView('solicitudes-aprobacion')" style="display:none;">Aprobar Solicitudes</a>
	</div>

	<?php echo view('_partials/_nav_user'); ?>
</nav>
