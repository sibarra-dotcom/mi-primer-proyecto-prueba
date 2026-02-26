<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
  <script src="<?= base_url('js/axios.js') ?>"></script>
  <script src="<?= base_url('js/helper.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body>
<div class=" relative flex flex-col items-center justify-center font-titil ">

  <?php echo view('dashboard/_partials/navbar'); ?>

  <a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer "> Cerrar Sesion </a>

  <div class=" mb-2 text-title w-full md:py-4 md:px-16 p-2 flex items-center ">
    <h2 class="text-center font-bold w-full text-3xl "><?= esc($title) ?></h2>
    <a href="<?= base_url('apps') ?>" class="self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
  </div>

  <div class=" relative flex flex-col items-start min-h-[78vh] w-full  ">
		<div class=" z-30 md:py-4 md:px-24  grid grid-cols-2 gap-16 md:grid-cols-3 lg:grid-cols-7 lg:gap-x-16 lg:gap-y-8 md:gap-20 mx-auto ">

				<div class="card__app card__info">
					<a href="<?= base_url('inactive/link') ?>" class="w-full h-full flex flex-col items-center justify-center">
						<img src="<?= base_url('img/ti.svg') ?>" data-file="ti" alt="" class=" object-contain icon_svg ">
						<p class="text-xs ">TI</p>
					</a>
				</div>

			<?php //if (hasRole('calidad')): ?>
				<div class="card__app card__info">
					<a href="<?= base_url('inspeccion') ?>" class="w-full h-full flex flex-col items-center justify-center">
						<img src="<?= base_url('img/calidad1.svg') ?>" data-file="calidad1" alt="" class="object-contain icon_svg ">
						<p class="text-xs ">Calidad</p>
					</a>
				</div>

			<!-- <div class="card__app card__info">
				<a href="<?= base_url('inspecciones') ?>" class="w-full h-full flex flex-col items-center justify-center">

					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="h-20 w-20">
						<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
					</svg>
					<p class="text-xs ">Calidad</p>
				</a>
			</div> -->
			<?php //endif; ?>

				<div class="card__app card__info">
					<a href="<?= base_url('inactive/link') ?>" class="w-full h-full flex flex-col items-center justify-center">
						<img src="<?= base_url('img/RH.svg') ?>" data-file="RH" alt="" class="object-contain icon_svg ">
						<p class="text-xs ">RH</p>
					</a>
				</div>


			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>


			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>
			<div class="card__app"><i class="fas fa-question fa-2x "></i></div>

		</div>
		<img src="<?= base_url('img/laboratorio.svg') ?>" class="hidden md:flex absolute bottom-0 left-0 -z-10 opacity-80 ">  
		</div>
	</div>

</div>

<script>

const allIcons = document.querySelectorAll('.icon_svg');
allIcons?.forEach( icon => {
	let parent = icon.parentElement;
  let file = icon.getAttribute('data-file');

  parent.addEventListener('mouseover', () => {
      icon.src = `${root}/img/${file}hover.svg`;
      console.log(icon.src)
  });

  parent.addEventListener('mouseout', () => {
      icon.src = `${root}/img/${file}.svg`;
  });
});



</script>
</body>
</html>

