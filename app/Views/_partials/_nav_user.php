<div class="flex items-center gap-x-6 md:gap-x-8 ">
  <div id="vacantes-user-info" class="flex items-center" style="display:none;gap:8px;margin-right:4px;">
    <span id="user-name" style="font-size:13px;font-weight:700;color:#0f172a;white-space:nowrap;"></span>
    <span id="user-role" style="font-size:11px;font-weight:700;padding:3px 8px;border-radius:999px;background:#dcfce7;color:#166534;white-space:nowrap;"></span>
    <select id="select-rol-doble" style="display:none;font-size:12px;font-weight:600;padding:4px 8px;border:2px solid #007940;border-radius:6px;color:#007940;background:#fff;cursor:pointer;" onchange="if(typeof cambiarRol==='function')cambiarRol(this.value)"></select>
  </div>

  <a href="<?= base_url('apps') ?>" class="flex gap-x-1" title="Inicio">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.4" stroke="currentColor" class="home h-8 w-8 stroke-title hover:stroke-title hover:fill-title transition duration-300 ">
      <path stroke-linecap="round" stroke-linejoin="round" d="M 12.3,2.38 C 12.01,2.38 11.72,2.49 11.5,2.71 L 2.55,11.67 H 4.8 V 19.56 C 4.8,20.17 5.3,20.67 5.92,20.67 L 14.55,20.67 V 20.67 H 16.8 18.67 C 19.29,20.67 19.8,20.17 19.8,19.56 V 11.67 H 22.05 L 13.09,2.71 C 12.87,2.49 12.59,2.38 12.3,2.38 Z M 11.17,14.76 H 13.42 C 14.04,14.76 14.55,15.25 14.55,15.86 V 20.37 L 10.05,20.37 V 20.22 20.07 19.47 18.27 17.07 15.86 C 10.05,15.25 10.55,14.76 11.17,14.76 Z" />
    </svg>
  </a>

  <button type="button" onclick="if(typeof toggleNotificaciones==='function')toggleNotificaciones();" class="flex gap-x-1" style="background:none;border:none;cursor:pointer;padding:0;position:relative;" title="Notificaciones">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.4" stroke="currentColor" class="bell h-8 w-8 stroke-title hover:stroke-title hover:fill-title transition duration-300 ">
      <path stroke-linecap="round" stroke-linejoin="round" d="M 11.5 3.02 A 6 6 0 0 0 6 9 L 6 9.75 A 8.97 8.97 0 0 1 3.69 15.77 C 5.42 16.41 7.25 16.86 9.14 17.08 A 24.25 24.25 0 0 0 14.86 17.08 A 23.85 23.85 0 0 0 20.31 15.77 A 8.97 8.97 0 0 1 18 9.75 L 18 9 A 6 6 0 0 0 11.5 3.02 z M 9.14 17.62 A 3 2.59 0 1 0 14.86 17.62 L 9.14 17.62 z" />
    </svg>
    <?php if ((hasRole('mantenimiento') && $title == 'Tickets de Mantenimiento') || (hasRole('jefe_mantenimiento') && $title == 'Tickets de Mantenimiento')): ?>
    <span id="notif" class="text-white bg-error h-8 w-8 flex items-center justify-center rounded-full">3</span>
    <?php endif; ?>
    <span id="notif-badge" class="flex items-center justify-center" style="display:none;position:absolute;top:-4px;right:-4px;min-width:18px;height:18px;border-radius:999px;background:#dc2626;color:#fff;font-size:10px;font-weight:800;padding:0 4px;line-height:1;">0</span>
  </button>

  <a href="<?= base_url('profile') ?>" class="flex gap-x-1" title="Mi Perfil">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.4" stroke="currentColor" class="user h-8 w-8 stroke-title hover:stroke-title hover:fill-title transition duration-300 ">
      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
    </svg>
  </a>

  <a href="<?= base_url('auth/signout') ?>" class="flex gap-x-1" title="Cerrar Sesión">
    <i class="fas fa-sign-out text-3xl text-icon text-red"></i>
  </a>

</div>
