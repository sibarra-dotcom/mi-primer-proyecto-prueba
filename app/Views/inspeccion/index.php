<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
  <script src="<?= base_url('js/axios.js') ?>"></script>
  <script src="<?= base_url('js/helper.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body>
<div class=" relative flex flex-col items-center justify-center font-titil ">

	<img src="<?= base_url('img/laboratorio.svg') ?>" class="hidden lg:flex absolute -z-10 -bottom-16 left-0 opacity-80 ">

  <img src="<?= base_url('img/laboratorio.svg') ?>" class="flex lg:hidden absolute -z-10 -bottom-16 left-0 opacity-80 ">

  <?php echo view('dashboard/_partials/navbar'); ?>

  <a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer "> Cerrar Sesion </a>

  <div class=" mb-2 text-title w-full md:py-4 md:px-16 p-2 flex items-center ">
    <h2 class="text-center font-bold w-full text-3xl "><?= esc($title) ?></h2>
    <a href="<?= base_url('registros') ?>" class="self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
  </div>

  <div class="min-h-[70vh] flex items-start justify-center z-30 p-8 lg:px-24 w-full lg:w-3/4 ">

		<div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-16 ">
			
			<a href="<?= base_url('inspeccion/init/materias-primas') ?>" class="dashboard_link lg:w-96 w-full h-24 cursor-pointer shadow-bottom-right  ">
				<div class=" flex gap-x-4 w-full items-center justify-center lg:justify-start px-8 h-full ">
					<!-- <i class="fas fa-file-lines text-4xl text-title hover:text-white "></i> -->
					<span class="text-lg uppercase ">Inspección de Materias Primas</span>
				</div>
			</a>

			<a href="<?= base_url('inspeccion/init/materiales') ?>" class="dashboard_link lg:w-96 w-full h-24 cursor-pointer shadow-bottom-right  ">
				<div class=" flex gap-x-4 w-full items-center justify-center lg:justify-start px-8 h-full ">
					
					<span class="text-lg uppercase ">Inspección de Materiales</span>
				</div>
			</a>


			<a href="<?= base_url('inactive/link') ?>" class="dashboard_link lg:w-96 w-full h-24 cursor-pointer shadow-bottom-right  ">
				<div class=" flex gap-x-4 w-full items-center justify-center lg:justify-start px-8 h-full ">

					<span class="text-lg uppercase ">Inspección de Etiquetas para productos</span>
				</div>
			</a>

			<a href="<?= base_url('inactive/link') ?>" class="dashboard_link lg:w-96 w-full h-24 cursor-pointer shadow-bottom-right  ">
				<div class=" flex gap-x-4 w-full items-center justify-center lg:justify-start px-8 h-full ">

					<span class="text-lg uppercase ">Inspección de Agua Purificada - Osmosis Inversa</span>
				</div>
			</a>
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



</script>
</body>
</html>

