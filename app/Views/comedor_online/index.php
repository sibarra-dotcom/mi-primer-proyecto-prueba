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

<body>

  <div class="relative flex flex-col gap-y-2 items-center justify-center font-titil ">

  	<?php echo view('comedor_online/_partials/navbar'); ?>

		<div class="flex flex-col gap-y-6 h-[60vh] items-center justify-center my-4 w-full lg:w-1/2">

			<p class="text-2xl font-bold text-title" id="currentDayText"></p>

			<p class="text-xl font-bold text-gray">Bienvenid@</p>

			<form id="form_comedor" class="flex flex-col gap-y-4 items-center justify-center">
				<?= csrf_field() ?>

				<p class="text-xl font-bold text-gray">Ingresa tu ID para registrarte</p>

				<input type="text" name="pin" id="pin" class="bg-grayMid bg-opacity-50 w-full focus:outline-none focus:border-icon py-4 px-4 text-xl text-center" placeholder="Ingresa tu ID" required>

				<div id="message" class=" mb-2 text-xl text-warning"></div>

				<button class="btn btn-lg btn--primary" type="submit">
					<span class="text-2xl">Buscar</span>
				</button>
			</form>
		</div>

		<div class="text-gray w-full md:pt-4 md:px-16 p-2 flex items-center mb-6">
			<img src="<?= base_url('img/gibanibb_logo.png') ?>" class="mx-auto w-1/3 lg:w-1/6" alt="">
    </div>

  </div>

	
<script>
Service.setLoading();


const currentDate = moment();
const formattedDate = currentDate.locale('es').format('dddd DD-MMMM-YYYY');

const currentDayText = document.getElementById('currentDayText');
currentDayText.innerHTML = capitalize(formattedDate);

const form_comedor = document.querySelector('#form_comedor');
	form_comedor.addEventListener('submit', e => {
	e.preventDefault();
	Service.stopSubmit(e.target, true);
	Service.show('.loading');

	const formData = new FormData(e.target);

	Service.exec('post', `/comedor_online`, formData_header, formData)
	.then( r => {
		if(r.success){
			// return;
			Service.hide('.loading');
			window.location.href = `${root}/comedor_online/pedido`;
		} else {

			if (r.csrf) {
				form_comedor.querySelector(`input[name="${r.csrf.name}"]`).value = r.csrf.hash;
			}

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

