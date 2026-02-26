<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">

  <div class="relative flex flex-col gap-y-2 items-center justify-center font-titil ">

  	<?php echo view('sorteo/_partials/navbar'); ?>

		<div class="flex flex-col gap-y-6 h-[60vh] items-center justify-center my-4 w-full lg:w-1/2" style="background-image: url('<?= base_url('img/cent1.png') ?>'); background-size: contain; background-position: center; background-repeat: no-repeat;">

			<p class="text-2xl font-bold text-title" id="monthText">Producto del Mes de Octubre</p>

			<div id="section_aviso" class="hidden relative py-12 px-10 flex flex-col items-center gap-4 rounded-2xl border-2 border-title text-white bg-title">
				<button id="btn_reload" type="button" class="absolute top-2 right-2 text-2xl">
					<span class="text-sm px-1">Tocar para cerrar</span>
					&times;
				</button>

				<h2 class="text-xl text-gold font-bold">Atención al cliente está contigo:</h2>
				<p class="text-lg text-center text-white font-bold mb-4">
					Comunícate con nosotros <br>
					+52 33 1584 2489
				</p>

			</div>

			<div id="section_confirm" class="hidden  p-6 flex flex-col items-center gap-4 rounded-2xl border-2 border-title bg-white ">
				<h2 id="username" class="text-xl font-bold text-gray"></h2>
				<p class="text-xl text-warning mb-4 font-bold">Verifica que eres tú</p>
				<div class="flex items-center gap-12">
					<button id="btn_si" class="bg-title text-white w-32 py-3 border border-title" type="button">
						<span class="text-2xl"> SI</span>
					</button>				
					<button id="btn_no" class=" bg-white text-title w-32 py-3 border border-title" type="button">
						<span class="text-2xl"> NO</span>
					</button>
				</div>

			</div>

			<div id="section_form" class="flex flex-col gap-y-6 items-center">
				<p class="text-xl font-bold text-gray">Bienvenid@</p>

				<form id="form_sorteo" class="flex flex-col gap-y-4 items-center justify-center">
					<p class="text-xl font-bold text-gray">Ingresa tu NIP para registrarte</p>

					<input type="text" name="pin" id="pin" class="input_modal w-full focus:outline-none focus:border-icon py-4 px-4 text-xl text-center" placeholder="Ingresa tu NIP" required>

					<div id="message" class=" mb-2 text-xl text-warning"></div>

					<button class="btn btn-lg btn--primary" type="submit">
						<span class="text-2xl"> Buscar</span>
					</button>
				</form>
			</div>


		</div>

		<div class="text-gray w-full md:pt-4 md:px-16 p-2 flex items-center mb-6">
			<img src="<?= base_url('img/gibanibb_logo.png') ?>" class="mx-auto w-1/3 lg:w-1/6" alt="">
    </div>

  </div>

<script>

	const currentMonth = moment().locale('es').format('MMMM'); 

	const capitalizedMonth = currentMonth.charAt(0).toUpperCase() + currentMonth.slice(1);
	const monthTextElement = document.getElementById('monthText');
	monthTextElement.innerHTML = `Producto del Mes de ${capitalizedMonth}`;

  Service.setLoading();

	const section_form = document.querySelector('#section_form');
	const section_aviso = document.querySelector('#section_aviso');
	const section_confirm = document.querySelector('#section_confirm');
	const btn_si = document.querySelector('#btn_si');
	const btn_no = document.querySelector('#btn_no');
	const btn_reload = document.querySelector('#btn_reload');
	const username = document.querySelector('#username');

	btn_reload.addEventListener('click', e => {
		window.location.reload();
	});

	btn_si.addEventListener('click', e => {
		window.location.href = `${root}/sorteo/ruleta`;
	});

	btn_no.addEventListener('click', e => {
		section_confirm.classList.add('hidden');
		section_aviso.classList.remove('hidden');
	});

	const form_sorteo = document.querySelector('#form_sorteo');
		form_sorteo.addEventListener('submit', e => {
		e.preventDefault();
		Service.stopSubmit(e.target, true);
		Service.show('.loading');

		const formData = new FormData(e.target);

    Service.exec('post', `/sorteo/index`, formData_header, formData)
    .then( r => {
      if(r.success){
				Service.hide('.loading');
				section_form.classList.add('hidden');
				section_confirm.classList.remove('hidden');
				username.innerHTML = r.username;

			} else {
				Service.hide('.loading');
				Service.stopSubmit(e.target, false);

				const message = document.querySelector('#message');
				message.innerHTML = r.message;

			}
    });
	});



</script>
</body>
</html>