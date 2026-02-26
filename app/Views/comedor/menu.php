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
  	<?php echo view('comedor/_partials/navbar'); ?>

		<!-- title group - title page -->
		<div class="relative text-title w-full mt-2 py-2 px-6 lg:px-10 flex items-center justify-center">
			<h2 class="text-center font-semibold text-3xl "><?= esc($title_group) ?></h2>
			<a href="<?= base_url('comedor') ?>" class="px-1 absolute right-6 lg:right-10 top-1/2 -translate-y-1/2 hover:scale-110 transition-transform duration-100 ">
				<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
			</a>
		</div>

		<!-- Main content -->
		<div class="w-full flex gap-4 items-start justify-center text-sm text-gray pl-4 pr-2" >

			<div class="w-full lg:w-2/3 flex flex-col gap-4">
				<div class="flex gap-4 justify-end items-center ">
					<label for="weekSelector" class="font-semibold text-lg">Selecciona la semana:</label>
					<select id="weekSelector" class="select_order w-96">
						<!-- Options will be populated dynamically -->
					</select>
				</div>

				<form id="form_menu" class="flex flex-col gap-4">
					<div id="daysContainer" class="flex flex-col gap-4">
						<!-- Days rows will be populated dynamically -->
					</div>
					<button type="submit" class="mx-auto btn btn-lg btn--search">Guardar Menú</button>
				</form>
			</div>

		</div>

  </div>


<!-- Modal success -->
<div id="modal_success" class="modal modal-md ">
	<div class="modal-content">
		<div class="modal-body">
			
			<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>

			<h3 class="text-title text-4xl text-center py-32 ">¡Enviado con éxito!</h3>

			<div class="flex w-full justify-center  ">
				<button data-dismiss="modal" class="modal-btn--cancel" type="button">
					ACEPTAR
				</button>
			</div>
		</div>

	</div>
</div>


<script>
Service.setLoading();

moment.locale('es');
const today = moment();

const getWeekDates = (startDate) => {
	let weekDates = [];
	for (let i = 0; i < 7; i++) {
		weekDates.push(startDate.clone().add(i, 'days'));
	}
	return weekDates;
};

// Función para generar las opciones del <select>
const generateWeekOptions = () => {
	const selectElement = document.getElementById('weekSelector');
	for (let i = 0; i < 3; i++) {
		const startOfWeek = today.clone().add(i, 'weeks').startOf('isoWeek');
		const endOfWeek = startOfWeek.clone().endOf('isoWeek');
		const option = document.createElement('option');
		option.value = i;
		option.textContent = `${startOfWeek.format('dddd DD/MM')} - ${endOfWeek.format('dddd DD/MM')}`;
		selectElement.appendChild(option);
	}
};

const generateDayInputs = (weekDates) => {
	const daysContainer = document.getElementById('daysContainer');
	daysContainer.innerHTML = ''; 

	weekDates.forEach((date, index) => {
		const dayRow = document.createElement('div');
		dayRow.classList.add('w-full', 'flex', 'gap-4', 'items-center');

		const dayName = moment(date).format('dddd');
		const dayDate = moment(date).format('DD-MM-YYYY');

		dayRow.innerHTML = `
			<div class="w-full flex gap-4">
				<span class="bg-grayMid h-8 text-center py-2 px-8">${dayDate}</span>
				<input type="hidden" value="${dayDate}" class=" h-8 text-center py-2 px-8" />
				<textarea rows="1" class="w-full flex-1 p-2 bg-white border border-grayMid " placeholder="Menú para ${dayName}"></textarea>
			</div>
		`;

		daysContainer.appendChild(dayRow);
	});
};


document.getElementById('weekSelector').addEventListener('change', (event) => {
	const selectedWeekIndex = parseInt(event.target.value);
	const startOfWeek = today.clone().add(selectedWeekIndex, 'weeks').startOf('isoWeek');
	const weekDates = getWeekDates(startOfWeek);
	generateDayInputs(weekDates);
});




const form_menu = document.querySelector('#form_menu');
	form_menu.addEventListener('submit', e => {
	e.preventDefault();
	Service.stopSubmit(e.target, true);
	Service.show('.loading');

	const formData = new FormData();

	const dayInputs = document.querySelectorAll('#daysContainer > div');
	dayInputs.forEach((row, index) => {
		const date = row.querySelector('input').value;
		const menu = row.querySelector('textarea').value;
		formData.append('date_' + index, date);
		formData.append('menu_' + index, menu);
	});

	Service.exec('post', `/comedor/menu`, formData_header, formData)
	.then( r => {
		if(r.success){
			// return;
			Service.hide('.loading');
			Modal.init("modal_success").open();

			// window.location.href = `${root}/comedor`;
		} else {
			Service.hide('.loading');
			Service.stopSubmit(e.target, false);

			const message = document.querySelector('#message');
			message.innerHTML = r.message;
		}
	});
});


window.onload = () => {
	generateWeekOptions();
	const firstWeekDates = getWeekDates(today.startOf('isoWeek'));
	generateDayInputs(firstWeekDates);
};
</script>


</body>
</html>