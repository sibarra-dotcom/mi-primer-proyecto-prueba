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


  <title><?= esc($title) ?></title>

</head>

<body class="relative min-h-screen">

  <div class="relative flex flex-col gap-y-2 items-center justify-center font-titil mb-10">

  	<?php echo view('produccion/_partials/navbar'); ?>

		<div class=" w-full py-2 px-4 lg:px-10 gap-4 flex items-center justify-between ">

			<div class="w-fit flex flex-col gap-2 text-sm">
				<select id="weekSelect" class="py-1 px-4 text-center"></select>
				<select id="yearSelect" class="py-1 px-4 text-center"></select>
			</div>

			<div id="cards" class="w-full grid md:grid-cols-4  gap-4 text-sm">

				<div class="flex-1 flex gap-2 justify-center bg-gray bg-opacity-70 text-white py-1 px-3 items-center">
					<p>Dif. Fecha Término y Compromiso:</p>
					<p id="diferencia-compromiso"></p>
				</div>

			</div>

    </div>



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
							<div class="flex justify-center px-2 py-1 w-[8%]">N° Orden</div>
							<div class="flex justify-center px-2 py-1 w-[75%]">Horas</div>
							<div class="flex justify-center px-2 py-1 w-[12%]">Tiempo Paro</div>
							<div class="flex justify-center px-2 py-1 w-[5%]">Acciones</div>
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

  const weekSelect = document.getElementById('weekSelect');
  const yearSelect = document.getElementById('yearSelect');

  function generateYears(start = 2023, end = moment().year()) {
    for (let y = start; y <= end; y++) {
      const option = document.createElement('option');
      option.value = y;
      option.textContent = y;
      yearSelect.appendChild(option);
    }
    yearSelect.value = moment().year();
  }

	function generateWeeks(year) {
		weekSelect.innerHTML = '<option value="">Seleccione una semana</option>';

		const currentYear = moment().year();
		const currentWeek = moment().isoWeek();

		let totalWeeks = 0;

		if (year < currentYear) {
			totalWeeks = moment(`${year}-12-31`).isoWeeksInYear();
		} else if (year === currentYear) {
			totalWeeks = currentWeek;
		}

		for (let i = 1; i <= totalWeeks; i++) {
			const option = document.createElement('option');
			option.value = i;
			option.textContent = `Semana ${i}`;
			weekSelect.appendChild(option);
		}

		// ✅ Auto-select current week AND auto-load data
		if (year === currentYear && totalWeeks > 0) {
			weekSelect.value = currentWeek;
			weekSelect.dispatchEvent(new Event('change'));
		}
	}

  generateYears();
  generateWeeks(moment().year());


	function getWeekRange(year, week) {
		return {
			start_date: moment()
				.year(year)
				.isoWeek(week)
				.startOf('isoWeek')
				.format('YYYY-MM-DD'),

			end_date: moment()
				.year(year)
				.isoWeek(week)
				.endOf('isoWeek')
				.format('YYYY-MM-DD')
		};
	}

	weekSelect.addEventListener('change', () => {
		const week = parseInt(weekSelect.value);
		const year = parseInt(yearSelect.value);

		if (!week || !year) return;

		const { start_date, end_date } = getWeekRange(year, week);
		getListaSemanal(start_date, end_date);
	});


	yearSelect.addEventListener('change', () => {
		generateWeeks(parseInt(yearSelect.value));
	});




	const cards_container = document.getElementById('cards');

  const renderCards = (data) => {
    cards_container.innerHTML = "";

    data.forEach(item => {
      const card = document.createElement('div');
      card.className = 'card flex-1 flex gap-2 justify-center bg-title bg-opacity-90 text-white py-1 px-3 items-center';

      card.innerHTML = `
        <h4>${item.incidencia} <strong>(${item.total}) : </strong></h4>
        <p><strong>${item.total_tiempo_paro.substring(0, 5)}</strong></p>
      `;

      cards_container.appendChild(card);
    });
  }


	const getListaSemanal = (start_date, end_date) => {
    cards_container.innerHTML = Service.loader();

		Service.exec('get', `produccion/get_incidencias_daterange/${start_date}/${end_date}`)
		.then(r => renderCards(r));  
	}

	getListaSemanal();



	document.addEventListener('DOMContentLoaded', () => {
		const currentYear = moment().year();

		yearSelect.value = currentYear;
		generateWeeks(currentYear); // ← auto-selects week + loads data
	});






const getListaParos = (date) => {
	Service.hide('.row--empty');
	tbody.innerHTML = Service.loader();

	Service.exec('get', `produccion/get_lista_paros/${date}`)
	.then(r => renderRows(r));  
}

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

		console.log('Selected date:', selectedDate);
		let dia_selected = moment(e.detail.date).format('YYYY-MM-DD');
		getListaParos(dia_selected);
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
		let today = moment().format("YYYY-MM-DD");

	tbody.innerHTML = "";

	if (!data.ordenes.length) {
		Service.show('.row--empty');
		return;
	}

	data.ordenes.forEach(orden => {
		const row = document.createElement('div');
		row.className = 'row_incidencias w-full flex flex-col text-sm';

		row.innerHTML =
			`
				<div class="p-1 flex items-center  w-full bg-grayMid bg-opacity-60 hover:bg-opacity-90 text-gray">

					<div class="flex justify-center px-2 py-1 w-[8%]">
						O.F. ${orden.orden_num}
					</div>
					<div class="flex justify-center px-2 py-1 w-[75%]">
						<div class="w-full ">
							<div class=" timeline relative w-full rounded-sm overflow-visible"></div>
							<div class="timeline-ruler relative w-full flex justify-between text-[10px] text-gray"></div>
						</div>
					</div>
					<div class="flex items-center gap-2 justify-center px-2 py-1 w-[12%]">
						<i class="fas fa-clock text-base"></i>
						<span>${orden.tiempo_total}</span>
					</div>
					<div class="flex justify-center px-2 py-1 w-[5%]">
						<div class="flex items-center gap-2">
							<button class="btn_show_details text-icon" type="button"><i class="fas fa-chevron-down text-xl font-bold transition-transform duration-300"></i>
							</button>
						</div>
					</div>

				</div>

				<div class="row_details hidden transition-all duration-500 ease-in-out px-4 py-2 w-full bg-grayMid flex items-center gap-4 text-sm bg-opacity-60">
					<div class="w-full flex items-start justify-center gap-4">
					
						<div class="w-[60%] flex flex-col ">

							<div class="w-full px-2 py-1 gap-4 flex items-center bg-gray bg-opacity-80 text-white text-xs">
								<div  class="w-[10%] text-center">Fecha</div>
								<div class="flex-1 text-center">Tipo de paro</div>
								<div class="w-[20%] text-center">Turno de paro</div>
								<div class="w-[10%] text-center">Inicio de paro</div>
								<div class="w-[10%] text-center">Fin de paro</div>
								<div class="w-[10%] text-center">Tiempo Total</div>
							</div>

							<div class="flex flex-col border-l border-super">
								${renderDetalles(orden.incidencias_detalles_historial)}
							</div>
						</div>

						<div class="w-[40%] flex justify-between items-start gap-4 p-4 border border-super divide-x divide-super">
							<div class="w-[40%] gap-4 flex flex-col items-start">
								<div class="w-full flex gap-4 items-center text-xl">
									<span class="flex-1">Tiempo Muerto</span>
									<span class="border-b-4 border-warning">
										${orden.tiempo_muerto}
										</span>
								</div>
								<div class="w-full flex gap-4 items-center text-xl">
									<span class="flex-1">Tiempo Efectivo</span>
									<span class="border-b-4 border-success">
										${orden.tiempo_efectivo}
									</span>
								</div>
							</div>
						
							<div class="w-[60%] pl-4 gap-1 flex flex-col items-start">
								${renderResumen(orden.incidencias_resumen_historial)}
							</div>

						</div>

					</div>
				</div>

			`
		tbody.appendChild(row);

		// createTimelineWithRuler(row, orden.last_incidencias, '2025-09-22');
		createTimelineWithRuler(row, orden.last_incidencias, today);
	});

	initRowBtn();
}

const renderResumen = (resumen) => 
  resumen.map(item => `
    <div class="w-full flex items-center">
      <span class="flex-1">${toTitleCase(item.tipo_incid)}</span>
      <span>${item.tiempo}</span>
    </div>
  `).join('');

const renderDetalles = (detalles) => 
detalles.map(item => `
	<div class="w-full px-2 py-1 gap-4 flex items-center border-b border-r  border-super">
		<div  class="w-[10%] text-center">${item.fecha.slice(0,10)}</div>
		<div class="flex-1 text-center">${toTitleCase(item.tipo_incid)}</div>
		<div class="w-[20%] text-center">${item.turno}</div>
		<div class="w-[10%] text-center">${moment(item.hora_inicio).format("HH:mm")}</div>
		<div class="w-[10%] text-center">${moment(item.hora_fin).format("HH:mm")}</div>
		<div class="w-[10%] text-center">${item.tiempo_paro} hrs</div>
	</div>
`).join('');

const loadRows = () => {
	Service.hide('.row--empty');
	tbody.innerHTML = Service.loader();

	let now = moment();

	// Set to yesterday's date if it's before 9 AM, else today's date
	let today = now.hour() < 9 ? now.subtract(1, 'days').format("YYYY-MM-DD") : now.format("YYYY-MM-DD");

	datepicker_input.value = moment(today).format('DD-MM-YYYY');

	Service.exec('get', `produccion/get_lista_paros/${today}`)
	// Service.exec('get', `produccion/get_lista_paros/2025-09-22`)
	.then(r => renderRows(r));  
}

loadRows();

const createTimelineWithRuler = (row, incidencias, dateStr = null) => {
  const timeline = row.querySelector('.timeline');
  const ruler = row.querySelector('.timeline-ruler');
  timeline.innerHTML = '';
  ruler.innerHTML = '';

  const date = dateStr ? moment(dateStr) : moment();
  const timelineStart = date.clone().startOf('day').set({ hour: 6, minute: 0, second: 0, millisecond: 0 });
  const totalMinutes = 24 * 60;

  // ---- Render incidencias ----
  incidencias.forEach(inc => {
		const start = moment(inc.start, "YYYY-MM-DD HH:mm:ss");
		const end   = moment(inc.end, "YYYY-MM-DD HH:mm:ss");


    if (!start.isValid() || !end.isValid()) return;
    if (end.isSameOrBefore(timelineStart) || start.diff(timelineStart, 'minutes') >= totalMinutes) return;

    const clampedStartMin = Math.max(0, start.diff(timelineStart, 'minutes'));
    const clampedEndMin = Math.min(totalMinutes, end.diff(timelineStart, 'minutes'));
    const durationMin = clampedEndMin - clampedStartMin;
    if (durationMin <= 0) return;

    const leftPercent = (clampedStartMin / totalMinutes) * 100;
    const widthPercent = (durationMin / totalMinutes) * 100;

    const el = document.createElement('div');
    el.className = 'absolute top-1 h-1';
    el.style.left = `${leftPercent}%`;
    el.style.width = `${widthPercent}%`;
    el.style.backgroundColor = inc.color || '#FF5733';

    timeline.appendChild(el);

    // Labels above block
    const startLabel = document.createElement('div');
    startLabel.className = 'absolute text-[10px] text-gray -top-3';
    startLabel.style.left = `${leftPercent}%`;
    startLabel.style.transform = 'translateX(-10%)';
    startLabel.textContent = start.format('H:mm');
    timeline.appendChild(startLabel);

    const endLabel = document.createElement('div');
    endLabel.className = 'absolute text-[10px] text-gray -top-6';
    endLabel.style.left = `${leftPercent + widthPercent}%`;
    endLabel.style.transform = 'translateX(-100%)';
    endLabel.textContent = end.format('H:mm');
    timeline.appendChild(endLabel);

    // Duration below block
    // const durLabel = document.createElement('div');
    // durLabel.className = 'absolute text-[10px] text-gray-800 -top-4';
    // durLabel.style.left = `${leftPercent + widthPercent / 2}%`;
    // durLabel.style.transform = 'translateX(-50%)';
    // durLabel.textContent = durationMin >= 60
    //   ? `${Math.floor(durationMin / 60)} hr${durationMin >= 120 ? 's' : ''}`
    //   : `${durationMin} min`;
    // timeline.appendChild(durLabel);
  });

  // ---- Render hour ruler ----
  for (let h = 0; h <= 24; h++) {
    const hourMoment = timelineStart.clone().add(h, 'hours');
    const pct = (h * 60 / totalMinutes) * 100;

    // tick line
    const tick = document.createElement('div');
    tick.className = 'absolute top-2 h-2 border-l border-super';
    tick.style.left = `${pct}%`;
    timeline.appendChild(tick);

    // label below
    // const label = document.createElement('div');
    // label.className = 'absolute text-[10px] text-gray top-2';
    // label.style.left = `${pct}%`;
    // label.style.transform = 'translateX(-50%)';
    // label.textContent = hourMoment.format('H');
    // timeline.appendChild(label);
  }
}

</script>
</body>
</html>