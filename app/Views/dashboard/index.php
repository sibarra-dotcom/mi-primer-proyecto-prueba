<?php echo view('_partials/header'); ?>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body>
<div class=" relative flex flex-col items-center justify-center font-titil ">
  <img src="<?= base_url('img/laboratorio.svg') ?>" class="hidden md:flex absolute -bottom-36 left-0 -z-10 opacity-90 ">  


	<?php echo view('dashboard/_partials/navbar'); ?>

  <a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer">Cerrar Sesión</a>

  <!-- Container with two columns -->
  <div class=" w-full p-4 md:px-14 md:py-6 flex flex-col lg:flex-row">

    <!-- Left Column for Image -->
    <div class=" text-title w-full p-2 md:w-1/2 relative flex flex-col gap-y-8 overflow-hidden ">
      <h2 class="text-5xl lg:text-6xl font-bold ">Bienvenido,</h2>
      <h2 class="text-5xl lg:text-6xl font-bold "><?= session()->get('user')['name'] ?></h2>
    </div>

    <!-- Right Column for Login Form -->
    <div class=" w-full p-2 lg:w-1/2 flex flex-col items-center lg:items-start gap-y-16 justify-center lg:justify-start md:px-14 md:pt-24">

      <a href="<?= base_url('apps') ?>" class="dashboard_link md:w-2/3 w-full h-24 cursor-pointer shadow-bottom-right ">
				<div class=" flex space-x-4 w-full items-center justify-center lg:justify-start px-16 h-full ">
          <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="0.55" stroke="currentColor" class="h-24 w-24 stroke-title text-title hover:fill-none transition duration-300  ">
            <path stroke-linecap="round" stroke-linejoin="round" d="M 5.07,16.93 V 15.13 H 6.92 8.77 V 16.93 18.74 H 6.92 5.07 Z M 10,16.93 V 15.13 H 11.85 13.7 V 16.93 18.74 H 11.85 10 Z M 14.93,16.93 V 15.13 H 16.78 18.63 V 16.93 18.74 H 16.78 14.93 Z M 5.07,11.92 V 10.11 H 6.92 8.77 V 11.92 13.72 H 6.92 5.07 Z M 10,11.92 V 10.11 H 11.85 13.7 V 11.92 13.72 H 11.85 10 Z M 14.93,11.92 V 10.11 H 16.78 18.63 V 11.92 13.72 H 16.78 14.93 Z M 5.07,6.9 V 5.1 H 6.92 8.77 V 6.9 8.71 H 6.92 5.07 Z M 10,6.9 V 5.1 H 11.85 13.7 V 6.9 8.71 H 11.85 10 Z M 14.93,6.9 V 5.1 H 16.78 18.63 V 6.9 8.71 H 16.78 14.93 Z" />
          </svg>
          <span class="text-2xl uppercase ">Aplicaciones </span>
        </div>
      </a>

      <a href="<?= base_url('apps') ?>" class="dashboard_link md:w-2/3 w-full h-24 cursor-pointer shadow-bottom-right  ">
				<div class=" flex space-x-4 w-full items-center justify-center lg:justify-start px-16 h-full ">
          <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="0.55" stroke="currentColor" class=" h-24 w-24 stroke-title text-title hover:fill-none transition duration-300 ">
            <path stroke-linecap="round" stroke-linejoin="round" d="M 5.68 5.31 L 5.68 6.85 L 5.68 8.39 L 11.56 8.39 C 16.94 8.39 17.62 8.43 17.62 8.43 L 19.95 16.65 C 19.95 16.65 19.95 9.39 19.95 6.42 L 15.71 6.36 C 11.5 6.3 11.46 6.29 11.39 5.8 C 11.32 5.32 11.25 5.31 8.5 5.31 L 5.68 5.31 z M 10.16 9.59 L 3.47 9.6 C 3.97 11.49 5.53 17.62 5.64 18.07 L 5.86 18.87 L 12.77 18.87 L 19.69 18.87 L 19.18 17.09 C 18.9 16.11 18.28 14.02 17.82 12.45 L 16.97 9.59 L 10.16 9.59 z" />
          </svg>
          <span class="text-2xl uppercase ">Recursos </span>
        </div>
      </a>

      <a href="<?= base_url('apps') ?>" class="dashboard_link md:w-2/3 w-full h-24 cursor-pointer shadow-bottom-right  ">
        <div class=" flex space-x-4 w-full items-center justify-center lg:justify-start px-16 h-full ">
          <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="0.24" stroke="currentColor" class=" h-24 w-24 stroke-title text-title hover:fill-none transition duration-300 ">
            <path stroke-linecap="round" stroke-linejoin="round" d="M 8.47,3.9 8.1,4.31 C 8.11,4.32 8.12,4.31 8.12,4.33 8.45,4.61 8.47,5.11 8.18,5.44 7.9,5.76 7.4,5.78 7.07,5.49 7.07,5.48 7.05,5.47 7.05,5.46 L 6.7,5.84 C 6.72,5.85 6.72,5.87 6.73,5.87 7.05,6.16 7.08,6.66 6.78,6.98 6.49,7.3 5.99,7.32 5.67,7.03 5.66,7.02 5.66,7.02 5.65,7.01 L 5.26,7.44 C 5.27,7.44 5.28,7.46 5.29,7.45 5.6,7.75 5.63,8.25 5.33,8.57 5.05,8.89 4.55,8.91 4.23,8.62 4.22,8.61 4.21,8.6 4.2,8.6 L 3.87,8.98 C 3.87,8.98 3.87,9 3.89,9 4.18,9.27 4.22,9.72 3.99,10.05 L 11.12,16.53 8.54,7.99 C 8.92,7.84 9.11,7.42 9,7.05 9,7.03 8.99,7.02 8.99,7.01 L 9.48,6.86 C 9.48,6.88 9.74,6.78 9.75,6.79 9.87,7.21 10.17,7.49 10.47,7.35 10.81,7.28 10.9,6.9 10.78,6.48 10.78,6.47 10.99,6.42 10.98,6.41 L 11.53,6.24 C 11.53,6.25 11.54,6.27 11.54,6.28 11.67,6.69 12.11,6.92 12.52,6.79 12.54,6.79 12.58,6.77 12.6,6.75 L 9.51,3.96 C 9.21,4.2 8.78,4.18 8.49,3.92 8.49,3.91 8.48,3.91 8.47,3.9 Z M 18.21,12.32 18.98,14.87 A 0.79,0.79 0 0 1 19.4,15.02 L 19.77,14.62 A 0.79,0.79 0 0 1 19.68,13.64 Z M 9.27,7.3 A 0.72,0.71 73.18 0 1 9.28,7.32 0.72,0.71 73.18 0 1 8.88,8.18 L 12.57,20.4 A 0.72,0.71 73.18 0 1 13.38,20.86 L 13.83,20.73 A 0.72,0.71 73.18 0 1 14.31,19.88 0.72,0.71 73.18 0 1 15.19,20.32 L 15.7,20.16 A 0.72,0.71 73.18 0 1 16.18,19.31 0.72,0.71 73.18 0 1 17.06,19.75 L 17.51,19.62 A 0.72,0.71 73.18 0 1 18,18.77 0.72,0.71 73.18 0 1 18.87,19.2 L 19.35,19.06 A 0.72,0.71 73.18 0 1 19.73,18.24 L 16.04,6.02 A 0.72,0.71 73.18 0 1 15.25,5.52 0.72,0.71 73.18 0 1 15.24,5.49 L 14.77,5.63 A 0.72,0.71 73.18 0 1 14.78,5.66 0.72,0.71 73.18 0 1 14.31,6.56 0.72,0.71 73.18 0 1 13.42,6.07 0.72,0.71 73.18 0 1 13.41,6.05 L 12.96,6.18 A 0.72,0.71 73.18 0 1 12.97,6.21 0.72,0.71 73.18 0 1 12.49,7.11 0.72,0.71 73.18 0 1 11.59,6.62 0.72,0.71 73.18 0 1 11.6,6.59 L 11.09,6.75 A 0.72,0.71 73.18 0 1 11.09,6.77 0.72,0.71 73.18 0 1 10.62,7.67 0.72,0.71 73.18 0 1 9.73,7.18 0.72,0.71 73.18 0 1 9.72,7.16 Z" />
          </svg>
          <span class="text-2xl uppercase ">Tickets </span>
        </div>
      </a>


<!-- 
      <a href="<?= base_url('user/link') ?>" class="md:w-2/3 w-full h-24 text-gray border-4 border-cta hover:bg-cta hover:text-white bg-white cursor-pointer shadow-bottom-right hover:scale-105 transition-transform duration-300 ">
        <div class=" flex space-x-8 w-full items-center justify-start px-16 h-full ">
          <i class="fas fa-user fa-3x"></i>
          <span class="text-2xl uppercase ">Recursos </span>
        </div>
      </a> -->


    </div>
  </div>

</div>
<script>
	  Service.setLoading();
		let overlay = document.querySelector('.backdrop-mobile-menu');
let mobile_checkbox = document.querySelector('#mobile_checkbox');
mobile_checkbox?.addEventListener('change', e => {
  if(e.target.checked) {
    document.body.classList.add('no-scroll');
    overlay.style.display = 'block';
  } else {
    document.body.classList.remove('no-scroll');
    overlay.style.display = 'none';
  }
});

window.addEventListener('click', e => {
  let mobile_checkbox = document.querySelector('#mobile_checkbox');
  if (e.target == overlay) {
    mobile_checkbox.checked = false;
    document.body.classList.remove('no-scroll');
    overlay.style.display = 'none';
  }
});

</script>
</body>
</html>

