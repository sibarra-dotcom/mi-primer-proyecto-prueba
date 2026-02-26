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
<div class="relative flex flex-col gap-y-2 items-center justify-center font-titil ">
	<img src="<?= base_url('img/laboratorio.svg') ?>" class="hidden lg:flex absolute -z-10 -bottom-16 left-0 opacity-80 ">

	<img src="<?= base_url('img/laboratorio.svg') ?>" class="flex lg:hidden absolute -z-10 -bottom-16 left-0 opacity-80 ">
	
	<?php echo view('dashboard/_partials/navbar'); ?>

	<div class="text-title w-full md:py-4 md:px-16 p-2 flex items-center ">
		<h2 class="text-center font-bold w-full text-3xl "><?= esc($title_group) ?></h2>
		<a href="<?= base_url('apps') ?>" class="self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
	</div>

  <div class="min-h-[70vh] flex items-start justify-center z-30 p-8 lg:px-24 w-full lg:w-3/4 ">

		<div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-16 ">
			
			<a href="<?= base_url('produccion/ordenes_lista') ?>" class="dashboard_link w-72 lg:w-96 w-full h-24 cursor-pointer shadow-bottom-right  ">
				<div class=" flex space-x-4 w-full items-center justify-center lg:justify-start px-8 h-full hover:text-white text-title">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="h-16 w-16 transition duration-200">
					<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
				</svg>

					<span class="text-xl uppercase ">LISTA ORDENES DE FABRICACION</span>
				</div>
			</a>

			<a href="<?= base_url('produccion/lista') ?>" class="dashboard_link w-72 lg:w-96 w-full h-24 cursor-pointer shadow-bottom-right  ">
				<div class=" flex space-x-4 w-full items-center justify-center lg:justify-start px-8 h-full hover:text-white text-title">

					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="h-16 w-16 transition duration-100 ">
						<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
					</svg>
					<span class="text-xl uppercase ">LISTA PROCESOS REPORTADOS</span>
				</div>
			</a>

			<!-- <button data-input="cameraInput" data-img="img_certif" data-modal="modal_files" class="btn_open_modal btn_open_camera dashboard_link w-72 lg:w-96 w-full h-24 cursor-pointer shadow-bottom-right  " type="button">
				<div class=" flex space-x-4 w-full items-center justify-center lg:justify-start px-8 h-full ">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="h-16 w-16 stroke-title text-title hover:fill-none transition duration-300">
						<path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
						<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
					</svg>
					<span class="text-xl uppercase ">Iniciar desde Certificado</span>
				</div>
			</button> -->


		</div>
	</div>

</div>




<script>

const allIcons = document.querySelectorAll('.icon_svg');
allIcons?.forEach( icon => {
let file = icon.getAttribute('data-file');

icon.addEventListener('mouseover', () => {
		icon.src = `${root}/img/${file}hover.svg`;
		console.log(icon.src)
});

icon.addEventListener('mouseout', () => {
		icon.src = `${root}/img/${file}.svg`;
});
});


Service.setLoading();

</script>
</body>
</html>

