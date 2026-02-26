<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/Modal.min.js') ?>"></script>
  <script src="<?= load_asset('js/SearchOrder.min.js') ?>"></script>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">

  <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/locales/es.js"></script>

  <title><?= esc($title) ?></title>

</head>

<body class="relative min-h-screen">

	<div class="relative flex flex-col gap-y-2 items-center justify-center font-titil ">

  	<?php echo view('produccion/_partials/navbar'); ?>

		<!-- title -->
		<div class="relative text-title w-full py-2 px-4 lg:px-10 flex items-center justify-between ">

			<div class="hidden lg:flex gap-4 items-center absolute left-6 lg:left-10 top-1/2 -translate-y-1/2">
				<a href="<?= base_url('produccion/ordenes_lista') ?>" class="hover:scale-110 transition-transform duration-100 ">
					<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
				</a>
			</div>

			<div class="w-full flex items-center justify-start xl:justify-center">
				<h2 class="text-center font-semibold w-fit text-2xl lg:text-3xl "><?= esc($title) ?></h2>
			</div>

			<div class=" flex gap-4 items-center absolute right-4 lg:right-10 top-1/2 -translate-y-1/2">


			</div>
    </div>

		<!-- Main content -->
		<div class="w-full flex gap-4 items-start justify-center text-sm text-gray pl-4 pr-2" >
			<!-- col left - search -->
			<div class="col-left w-72 bg-grayMid rounded border border-grayMid p-2">
				<div class="flex flex-col gap-6">

					<div class="flex flex-col gap-2">
						<p>TURNO</p>
						<select id="turnoId" class="w-full bg-white text-center py-1">
							<!-- <option value="" disabled selected>Seleccionar ...</option> -->
							<?php foreach ($turnos as $turno): ?>
									<option value="<?= esc($turno['id']) ?>" data-value="<?= esc($turno['label']) ?>">
											<?= esc($turno['label']) ?>
									</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="flex flex-col gap-2">
						<p>FECHA</p>
						<div id="datepicker"></div>
						<input type="hidden" id="dia_selected">
					</div>
				  

				</div>
  			
			</div>

			<!-- col right - table -->
			<div class="col-right w-full">
				
				<div class="relative w-full h-[70vh] overflow-y-scroll mx-auto">
					<table id="tabla-reporte-orden-fab">
						<thead>
							<tr>
								<th>Linea</th>
								<th>Descripcion</th>
								<th>N° Or. Fab.</th>
								<th>Piezas</th>
								<th>Colectiva</th>
								<th>Acumulado</th>
								<th>Merma</th>
								<th>Observaciones</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody></tbody>

					</table>
					<div class="row--empty">No results found.</div>
					
				</div>
				

			</div>
		</div>


  </div>



<!-- Modal Observacion -->
<div id="modal_obs" class="modal modal-sm">
	<div class="modal-content">

		<div class="modal-header">
			<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>
			<h3>Editar Observación</h3>
		</div>

		<form id="form_obs" class="modal-body">
			<textarea name="obs_reporte" class="p-4 w-full border border-grayMid outline-none resize-none bg-grayLight text-gray drop-shadow " rows="4" placeholder="Escribe tu comentario..."></textarea>
	
			<div class="form-row-submit ">
				<button class=" modal-btn--submit" type="submit" >
				Actualizar
				</button>
			</div>
		</form>
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

const getReporte = (turnoId, date) => {
	Service.hide('.row--empty');
	tbody.innerHTML = Service.loader();

	Service.exec('get', `produccion/reporte_ordenfab/${turnoId}/${date}`)
	.then(r => renderRows(r));  
}

const dia_selected = document.querySelector('#dia_selected');
dia_selected.value = moment().format('YYYY-MM-DD');

const datepicker_el = document.querySelector('#datepicker');

const datepicker = new Datepicker(datepicker_el, {
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


datepicker.element.addEventListener('changeDate', (e) => {
	let turno_id = turnoId.value;
	if (!turno_id) return;

  dia_selected.value = moment(e.detail.date).format('YYYY-MM-DD');
	getReporte(turno_id, dia_selected.value)
});


const turnoId = document.querySelector('#turnoId');
turnoId?.addEventListener('change', e => {
	let turno_id = e.target.value;
	if (!turno_id) return;

	getReporte(turno_id, dia_selected.value)
});




const tbody = document.querySelector('#tabla-reporte-orden-fab tbody');

const renderRows = (data) => {  
	tbody.innerHTML = "";

	if (!data.length) {
		Service.show('.row--empty');
		return;
	}

	data.forEach(cell => {
		const row = document.createElement('tr');

		row.innerHTML =
			`
				<td>
					<span>${cell.linea}</span>
				</td>
				<td>
					<span>${cell.desc_articulo}</span>
				</td>
				<td>
					<span>${cell.num_orden}</span>
				</td>
				<td>
					<span>${cell.piezas_producidas}</span>
				</td>
				<td>
					<span>${cell.colectiva}</span>
				</td>
				<td>
					<span>${cell.piezas_acumuladas_sin_muestras}</span>
				</td>
				<td>
					<span>${cell.total_merma}</span>
				</td>
				<td>
					<span>${cell.obs_reporte}</span>
				</td>

				<td>
					<div class="flex items-center gap-2 ">
						<button data-id="${cell.reporte_prod_id}" data-modal="modal_obs" class="btn_open_modal btn_edit flex hover:text-link w-fit" type="button">
							<i class="fas fa-plus text-lg pr-1"></i>
							<i class="fas fa-comment text-lg"></i>
						</button>

						<a href="${root}/produccion/registro_diariop/${cell.num_orden}/${cell.reporte_prod_id}" class=" hover:text-icon">
							<i class="fas fa-eye text-lg"></i>
						</a>
					</div>
				</td>
			`
		tbody.appendChild(row);
	});

	initRowBtn();
}

const initRowBtn = () => {
	const allBtnEdit = document.querySelectorAll('#tabla-reporte-orden-fab .btn_edit');
	allBtnEdit?.forEach( (btn, index) => {

		btn.addEventListener('click', e => {
			e.stopPropagation()
			let modal_id = btn.dataset.modal;
			Modal.init(modal_id).open();

			// let msg_alerts = document.querySelectorAll('.msg_alert');
			// msg_alerts.forEach( span => {
			// 	span.classList.add('hidden');
			// });

			if (modal_id == 'modal_obs') {
				let form_obs = document.querySelector('#form_obs')
				form_obs.dataset.rep_id = btn.dataset.id;
				let rep_id = btn.dataset.id;

				Service.exec('get', `produccion/obs_reporte/${rep_id}`)
				.then( r => {
					form_obs.querySelector('textarea').value = r.obs_reporte;
				});

			}

		});
	});

}





const form_obs = document.querySelector('#form_obs');
form_obs.addEventListener('submit', (e) => {
	e.preventDefault();
	Service.stopSubmit(e.target, true);
	Service.show('.loading');

	let rep_id = e.target.dataset.rep_id;

	const formData = new FormData(e.target);
	formData.append('repId', rep_id);

	Service.exec('post', `produccion/obs_reporte`, formData_header, formData)
	.then( r => {
		if(r.success){
			
			Modal.init('modal_obs').close();
			Service.stopSubmit(e.target, false);

			getReporte(turnoId.value, dia_selected.value)

			setTimeout(() => {
				Service.hide('.loading');
				Modal.init("modal_success").open();
				e.target.reset();
			}, 500)


		} else {

			let msg = '';
			let validationErrors = r.message;

			for (const field in validationErrors) {
					if (validationErrors.hasOwnProperty(field)) {
							// msg += `${field} : ${validationErrors[field]} <br>`;
							msg += `${validationErrors[field]} <br>`;
					}
			}

			let ci_error = document.querySelector('#ci_error')
			ci_error.innerHTML = msg;

			Service.show('#ci_error');

			Service.hide('.loading');
			Service.stopSubmit(e.target, false);

		}
	});
});



const loadRows = () => {
	Service.hide('.row--empty');
	tbody.innerHTML = Service.loader();
	let turnoId = 1; // turno ARTES - Matutino 

	Service.exec('get', `produccion/reporte_ordenfab/${turnoId}/${dia_selected.value}`)
	.then(r => renderRows(r));  
}

loadRows();


</script>
</body>
</html>