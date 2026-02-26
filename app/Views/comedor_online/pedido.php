<?php echo view('_partials/header'); ?>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
	<script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/Modal.min.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">
  <div class="relative flex flex-col gap-y-2 items-center justify-center font-titil ">

  	<?php echo view('comedor_online/_partials/navbar'); ?>

		<div class="flex flex-col gap-y-6 items-center justify-center ">

			<form id="pedidoForm" class="max-w-xl mx-auto bg-white shadow-lg border border-grayMid rounded p-6 space-y-6 my-4 text-gray ">
				<h2 class="text-gray text-center font-semibold text-3xl "><?= session()->get('user_pedido')['name'] . ' ' . session()->get('user_pedido')['last_name'] ?></h2>

				<!-- Menú del día -->
				<input type="hidden" name="nombre" value="<?= session()->get('user_pedido')['name'] . ' ' . session()->get('user_pedido')['last_name'] ?>" />
				<input type="hidden" name="userId" value="<?= session()->get('user_pedido')['user_id'] ?>" />
				<input type="hidden" name="fecha" value="<?= date('Y-m-d') ?>" />


				<div class="flex flex-col gap-2">
					<p class=" text-title font-semibold uppercase">Importante</p>
					<p class="text-warning ">
						* Selecciona solo una opción ya sea del <strong>menú del día</strong> o del <strong>menú base</strong> *
					</p>
				</div>

				<div class="flex flex-col gap-2">
					<div class="flex items-center justify-between text-xl font-semibold border-grayMid border-b pb-1"> 
						<span>Menú del día</span>	
						<p id="currentDayText" class="text-center font-semibold text-title"></p>
					</div>

					<label class="flex items-center space-x-2 cursor-pointer">
						<input type="radio" name="menu_dia" value="<?= $menu_dia ?>" class="radio_fit h-4 w-4" />
						<span><?= $menu_dia ?></span>
					</label>
					
				</div>

				<!-- Guarniciones -->
				<div class="flex flex-col gap-2">
					<div class="flex items-center justify-between text-xl font-semibold border-grayMid border-b pb-1"> 
						<span>Guarniciones</span>	
					</div>

					<div class="grid grid-cols-2 gap-2 text-sm">
						<li>Arroz Blanco</li>
						<li>Arroz Rojo</li>
						<li>Frijoles</li>
						<li>Spaguetti</li>
					</div>
					<span class="text-warning text-sm">* Escoger dos opciones de los anteriores y anotarlas en especificaciones</span>	
				</div>

				<!-- Menú base -->
				<div class="flex flex-col gap-2">

					<div class="flex items-center justify-between text-xl font-semibold border-grayMid border-b pb-1"> 
						<span>Menú Base</span>	
					</div>

					<div class="w-full flex flex-col gap-4 text-sm">
						<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="menu_base" value="Lonches de guisado (lengua en salsa verde, costilla en salsa roja, bistec con papas, chicharrón guisado)" class="radio_fit h-4 w-4" />
							<span>
								Lonches de guisado (escoger guiso: lengua en salsa verde, costilla en salsa roja,
								bistec con papas, chicharrón guisado)
							</span>
						</label>

						<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="menu_base" value="3 tacos de guisado (lengua en salsa verde, costilla en salsa roja, bistec con papas, chicharrón guisado)" class="radio_fit h-4 w-4" />
							<span>
								3 tacos de guisado (escoger guiso: lengua en salsa verde, costilla en salsa roja,
								bistec con papas, chicharrón guisado)
							</span>
						</label>

							<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="menu_base" value="2 quesadillas de guiso (escoger guiso: lengua en salsa verde, costilla en salsa roja,
								bistec con papas, chicharrón guisado) y una pellizcala de frijol con queso" class="radio_fit h-4 w-4" />
							<span>
								2 quesadillas de guiso (escoger guiso: lengua en salsa verde, costilla en salsa roja,
								bistec con papas, chicharrón guisado) y una pellizcala de frijol con queso
							</span>
						</label>

					</div>

					<div class="grid grid-cols-2 gap-4 pt-4 text-sm">

						<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="menu_base" value="Costillas en salsa roja" class="radio_fit h-4 w-4" />
							<span>Costillas en salsa roja</span>
						</label>

						<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="menu_base" value="Chicharrón en salsa roja" class="radio_fit h-4 w-4" />
							<span>Chicharrón en salsa roja</span>
						</label>

						<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="menu_base" value="Lengua en salsa verde" class="radio_fit h-4 w-4" />
							<span>Lengua en salsa verde</span>
						</label>


						<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="menu_base" value="2 huaraches" class="radio_fit h-4 w-4" />
							<span>2 huaraches</span>
						</label>

						<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="menu_base" value="3 quesadillas" class="radio_fit h-4 w-4" />
							<span>3 quesadillas</span>
						</label>
												<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="menu_base" value="Chilaquiles" class="radio_fit h-4 w-4" />
							<span>Chilaquiles</span>
						</label>
												<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="menu_base" value="Huevos al gusto" class="radio_fit h-4 w-4" />
							<span>Huevos al gusto</span>
						</label>
												<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="menu_base" value="Omelette" class="radio_fit h-4 w-4" />
							<span>Omelette</span>
						</label>
												<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="menu_base" value="Torta Cubana" class="radio_fit h-4 w-4" />
							<span>Torta Cubana</span>
						</label>
												<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="menu_base" value="Torta Americana" class="radio_fit h-4 w-4" />
							<span>Torta Americana</span>
						</label>
												<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="menu_base" value="Torta Hawaiana" class="radio_fit h-4 w-4" />
							<span>Torta Hawaiana</span>
						</label>
					</div>
				</div>

				<!-- Especificaciones -->
				<div>
					<label for="observacion" class="block text-sm font-medium ">Especificaciones</label>
					<textarea id="observacion" name="observacion" rows="2" class="flex w-full border border-grayMid shadow-sm focus:border-title"></textarea>
				</div>


				<!-- Horario -->
				<div class="flex flex-col gap-2">

					<div class="flex items-center justify-between text-xl font-semibold border-grayMid border-b pb-1"> 
						<span>Horario</span>	
					</div>

					<div class="w-full flex flex-col gap-4 ">
						<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="horario" value="02:00 PM" class="radio_fit h-4 w-4" />
							<span>
								02:00 PM
							</span>
						</label>
												<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="horario" value="09:30 PM (TURNO NOCTURNO)" class="radio_fit h-4 w-4" />
							<span>
								09:30 PM (TURNO NOCTURNO)
							</span>
						</label>
												<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="horario" value="01:30 PM (OFICINAS AV. VALLARTA)" class="radio_fit h-4 w-4" />
							<span>
								01:30 PM (OFICINAS AV. VALLARTA)
							</span>
						</label>
												<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="horario" value="10:30 AM" class="radio_fit h-4 w-4" />
							<span>
								10:30 AM
							</span>
						</label>
												<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="horario" value="01:00 PM (PLANTA TONALÁ)" class="radio_fit h-4 w-4" />
							<span>
								01:00 PM (PLANTA TONALÁ)
							</span>
						</label>
					</div>
				</div>

					<!-- Confirmación -->
				<div class="flex items-center gap-8 text-base border-b border-grayMid pb-2">
					<label for="confirm">Confirmar mi pedido </label>
					<span id="currentDayText1" class="text-title "></span>
					<input id="confirm" type="checkbox" class="checkbox_fit h-4 w-4">
				</div>

				<div class="flex flex-col gap-2">
					<p class=" text-title font-semibold uppercase">Importante</p>
					<p class="text-warning ">
						* Si quieres pedir un segundo plato debes llenar de nuevo el formulario.
					</p>
					<p class="text-warning ">
						** Sólo se permite máximo 2 platos por persona al dia.
					</p>
				</div>

				<div class="w-full mx-auto pt-8">
					<button type="submit" class=" mx-auto btn btn-lg btn--cta">
						Enviar pedido
					</button>
				</div>
				<?= csrf_field() ?>
			</form>

  	</div>
  </div>


<!-- Modal success -->
<div id="modal_success" class="modal modal-md ">
	<div class="modal-content">
		<div class="modal-body">
			
			<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>

			<div class="flex flex-col gap-12 py-32">
				<h2 class="text-gray text-center font-semibold text-3xl "><?= session()->get('user_pedido')['name'] . ' ' . session()->get('user_pedido')['last_name'] ?></h2>

				<h3 class="text-title text-4xl text-center ">¡Registrado con éxito!</h3>

				<a href="<?= base_url('comedor_online') ?>" class="mx-auto btn btn-md btn--primary" type="button">
					<span class="text-2xl">ACEPTAR</span>
				</a>
			</div>


		</div>

	</div>
</div>

<script>
Service.setLoading();

const currentDate = moment();
const formattedDate = currentDate.locale('es').format('dddd DD-MMMM-YYYY');
const currentDayText = document.getElementById('currentDayText');
const currentDayText1 = document.getElementById('currentDayText1');
currentDayText.innerHTML = capitalize(formattedDate);
currentDayText1.innerHTML = capitalize(formattedDate);


const form_pedido = document.querySelector('#pedidoForm');
form_pedido.addEventListener('submit', e => {
	e.preventDefault();
	const formData = new FormData(e.target);

	const menu_dia = formData.get("menu_dia");
	const menu_base = formData.get("menu_base");
	const observacion = formData.get("observacion");
	const confirm = document.getElementById("confirm").checked;

	if (menu_dia && menu_base) {
		alert("Por favor selecciona SOLO menú del día o menú base, no ambos.");

		document.querySelectorAll('input[type="radio"][name="menu_dia"], input[type="radio"][name="menu_base"]').forEach(r => {
				r.checked = false;
			});
		return;
	}

	if (!menu_dia && !menu_base) {
		alert("Debes seleccionar al menos una opción de menú.");
		return;
	}

	if (!confirm) {
		alert("Debes confirmar tu pedido antes de enviarlo.");
		return;
	}

	Service.show('.loading'); 

	Service.exec('post', `/comedor_online/pedido`, formData_header, formData)
	.then( r => {
		if(r.success){
			Service.hide('.loading');
			Modal.init("modal_success").open();

			setTimeout(() => {
				window.location.href = `${root}/comedor_online`;
			}, 3000);
		}  else {
			if (r.csrf) {
				form_pedido.querySelector(`input[name="${r.csrf.name}"]`).value = r.csrf.hash;
			}

		}
	});
});


</script>
</body>
</html>