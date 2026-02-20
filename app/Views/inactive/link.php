<?php echo view('_partials/header'); ?>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">
    <div class=" relative h-full flex flex-col items-center justify-center font-titil ">

			<nav id="menu" class=" z-40 w-full px-2 lg:px-14 py-2 border-b-2 border-grayMid drop-shadow flex items-center justify-between bg-white ">

				<div class=" flex justify-center">
					<a href="<?= base_url('dashboard') ?>" > <img src="<?= base_url('img/logo.jpeg') ?>" alt="Logo" class="w-10 h-10 md:w-12 md:h-12 rounded-full "> </a>
				</div>


				<?php echo view('_partials/_nav_user'); ?>

			</nav>

			<div class=" mb-2 text-title w-full md:py-4 md:px-16 p-2 flex items-center ">
				<h2 class="text-center font-bold w-full text-3xl text-white"><?= esc($title) ?></h2>

				<a href="<?= base_url('apps') ?>" class="self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
			</div>

			<div class="py-16 px-4 md:p-0 font-titil neutralDark flex flex-col items-center justify-center gap-y-12">
				<h3 class="lg:text-6xl font-semibold text-4xl text-title "><?= esc($title) ?></h3>
				<img src="<?= base_url('img/en_construccion.svg') ?>" alt="Page in Progress" class="w-full lg:w-3/4 rounded-md">
			</div>
    </div>
</body>
</html>
