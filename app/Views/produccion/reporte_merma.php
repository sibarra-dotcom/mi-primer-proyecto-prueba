<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/Modal.min.js') ?>"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">

  <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/locales/es.js"></script>
	<!-- <script src="https://cdn.jsdelivr.net/npm/moment-timezone@0.5.34/moment-timezone-with-data.min.js"></script> -->

  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <title><?= esc($title) ?></title>

</head>

<body class="relative min-h-screen">

  <div class="relative flex flex-col gap-y-2 items-center justify-center font-titil mb-10">

  	<?php echo view('produccion/_partials/navbar'); ?>


		<div class="relative text-title w-full py-2 px-4 lg:px-10 flex items-center justify-between ">

			<div class="hidden lg:flex gap-4 items-center absolute left-6 lg:left-10 top-1/2 -translate-y-1/2">
				<a href="<?= previous_url() ?>" class="hover:scale-110 transition-transform duration-100 ">
					<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
				</a>
			</div>

			<div class="w-full flex items-center justify-start xl:justify-center">
				<h2 class="text-center font-semibold w-fit text-2xl lg:text-3xl "><?= esc($title) ?></h2>
			</div>

			<div class="relative flex items-center gap-2 ">
				<span class="w-56 text-sm">Seleccionar Fecha :</span>
				<input type="text" id="datepicker" placeholder="Seleccionar ..." class="text-center" readonly>
			</div>

			</div>
    </div>




    <div class="w-full text-sm text-gray  ">
      <div class="flex flex-col h-full" >

        <div class="w-full pl-10 pr-8 ">
					<div class=" w-full text-sm h-[75vh] overflow-y-scroll ">

            <div class="text-sm flex w-full bg-title text-white">
							<div class="flex justify-center px-2 py-1 w-[10%]">N° Orden</div>
							<div class="flex justify-center px-2 py-1 w-[74%]">Detalles</div>
							<div class="flex justify-center px-2 py-1 w-[8%]">Total Merma</div>
							<div class="flex justify-center px-2 py-1 w-[8%]">Acciones</div>
						</div>

						<div id="results_container" class="flex flex-col my-2 gap-2 w-full">
							<div class="flex bg-grayMid text-gray">lorem10</div>
						</div>
						
						<div class="row--empty">No hay datos</div>
          </div>
        </div>

      </div>
    </div>
  </div>


<script>

Service.setLoading();


const datepicker_input = document.querySelector('#datepicker');
const datepicker = new Datepicker(datepicker_input, {
  language: 'es',
  maxView: 0,
  maxDate: moment().toDate(),
  datesDisabled: (date, viewId) => {
    if (viewId === 0) {
      const dateString = date.toISOString().split('T')[0];
      // return feriados.includes(dateString); // Disable holidays
    }
    return false;
  }
});

datepicker.element.addEventListener('changeDate', function (e) {
		const selectedDate = e.target.value;
		datepicker_input.value = selectedDate.replace(/\//g, '-');
		datepicker.hide();

		let dia_selected = moment(e.detail.date).format('YYYY-MM-DD');
		loadRows(dia_selected);
});


const toTitleCase = str => str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
		

const toggleRowDetails = (event) => {
	const row = event.target.closest('.row_incidencias');

	const rowDetails = row.querySelector('.row_details');
	const icon = event.currentTarget.querySelector('i');

	rowDetails.classList.toggle('hidden');
	rowDetails.classList.toggle('transition-all');
	rowDetails.classList.toggle('duration-500');
	rowDetails.classList.toggle('ease-in-out');

	if (rowDetails.classList.contains('hidden')) {
		icon.classList.remove('rotate-180');
	} else {
		icon.classList.add('rotate-180');
	}
};

const initRowBtn = () => {
	document.querySelectorAll('.btn_show_details').forEach((button) => {
		button.addEventListener('click', toggleRowDetails);
	});
}


const tbody = document.querySelector('#results_container');
const renderRows = (data) => {  

	tbody.innerHTML = "";

	if (!data.length) {
		Service.show('.row--empty');
		return;
	}

	data.forEach((orden, index) => {
		const row = document.createElement('div');
		row.className = 'row_incidencias w-full flex flex-col text-sm';

		row.innerHTML =
			`
				<div class="p-1 flex items-center  w-full bg-grayMid bg-opacity-60 hover:bg-opacity-90 text-gray">

					<div class="flex justify-center px-2 py-1 w-[10%]">
						O.F. ${orden.num_orden}
					</div>
					<div class="flex justify-center px-2 py-1 w-[74%]">
						<div class="w-full ">
							<span>${orden.producto}</span>
						</div>
					</div>
					<div class="flex items-center gap-2 justify-start px-2 py-1 w-[8%]">
						<i class="fas fa-calculator text-base"></i>
						<span>${orden.total_sum}</span>
					</div>
					<div class="flex justify-center px-2 py-1 w-[8%]">
						<div class="flex items-center gap-2">
							<button class="btn_show_details text-icon" type="button"><i class="fas fa-chevron-down text-xl font-bold transition-transform duration-300"></i>
							</button>
						</div>
					</div>

				</div>

				<div class="row_details hidden transition-all duration-500 ease-in-out px-4 py-2 w-full bg-grayMid flex items-center gap-4 text-sm bg-opacity-60">
					<div class="w-full grid grid-cols-2 gap-4">
					
						<div class="flex flex-col ">

							<div class="w-full px-2 py-1 gap-4 flex items-center bg-gray bg-opacity-80 text-white text-sm">
								<span class="w-full text-center">Merma Empaque</span>
							</div>

							<div class="bg-white p-2">
								<div id="empaque-${index}"></div>
							</div>

						</div>

						<div class="flex flex-col ">

							<div class="w-full px-2 py-1 gap-4 flex items-center bg-gray bg-opacity-80 text-white text-sm">
								<span class="w-full text-center">Merma Presentación</span>
							</div>

							<div class="bg-white p-2">
								<div id="presentacion-${index}"></div>
							</div>

						</div>


					</div>
				</div>

			`
		tbody.appendChild(row);
	});


	data.forEach((orden, index) => {
		renderMermaChart(`#empaque-${index}`, 'Empaque', orden.empaque.items);
		renderMermaChart(`#presentacion-${index}`, 'Presentación', orden.presentacion.items);
		// renderMermaChart(`#contenido-${index}`, 'Contenido', orden.contenido.items);
	});

	initRowBtn();
}


const renderMermaChart = (containerId, title, items) => {
	const chart_bar_height = 284;
  const categories = Object.keys(items);
  const series = Object.values(items)

  const options = {
    series: [{
      data: series
    }],
    chart: {
      type: 'bar',
      height: chart_bar_height,
      toolbar: { show: false }
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        borderRadiusApplication: 'end',
        horizontal: true,
				distributed: true 
      }
    },
    dataLabels: {
      enabled: true,
      style: { fontSize: '14px' }
    },
    xaxis: {
      categories: categories,
      // min: 0,
      // max: 5,
      // tickAmount: 5,
    },
    tooltip: {
      enabled: false
    },
    colors: [
      "#003F2A",
			"#054C3A",
			"#0A664D",
			"#138C66", 
			"#1DA97D", 
			"#26B588", 
		],
    grid: { borderColor: '#eee' },
    legend: { show: false }
  };

  new ApexCharts(document.querySelector(containerId), options).render();
}



const loadRows = (date) => {
	Service.hide('.row--empty');
	tbody.innerHTML = Service.loader();

	datepicker_input.value = moment(date).format('DD-MM-YYYY');

	Service.exec('get', `produccion/get_merma_by_date/${date}`)
	// Service.exec('get', `produccion/get_lista_paros/2025-09-22`)
	.then(r => renderRows(r));  
}

let now = moment();

// Set to yesterday's date if it's before 9 AM, else today's date
let today = now.hour() < 9 ? now.subtract(1, 'days').format("YYYY-MM-DD") : now.format("YYYY-MM-DD");


loadRows(today);


</script>
</body>
</html>