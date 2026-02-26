<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
	<script src="<?= load_asset('js/Modal.min.js') ?>"></script>
	<script src="<?= load_asset('js/SearchOrder.min.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">

	<div class="relative flex flex-col gap-y-2 items-center justify-center font-titil ">

  	<?php echo view('dashboard/_partials/navbar'); ?>

		<div class="relative text-title w-full py-2 px-4 lg:px-10 flex items-center justify-between ">

			<div class="hidden lg:flex gap-4 items-center absolute left-6 lg:left-10 top-1/2 -translate-y-1/2">
				<a href="<?= base_url('inspeccion/init/materias-primas') ?>" class="hover:scale-110 transition-transform duration-100 ">
					<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
				</a>
			</div>

			<div class="w-full flex items-center justify-start xl:justify-center">
				<h2 class="text-center font-semibold w-fit text-2xl lg:text-3xl "><?= esc($title) ?></h2>
			</div>

			<div class=" flex gap-4 items-center absolute right-4 lg:right-10 top-1/2 -translate-y-1/2">

				<div class="flex w-fit items-center text-gray gap-x-2 text-sm">
					<span>ORDENAR POR :</span>
					<select id="order_by" class="select_order w-44">
						<option value="" disabled selected>Seleccionar...</option>
						<option value="asc-materia">Materia A-Z</option>
						<option value="desc-materia">Materia Z-A</option>
						<option value="asc-proveedor">Proveedor A-Z</option>
						<option value="desc-proveedor">Proveedor Z-A</option>
						<option value="asc-fecha_arribo">Fecha Arribo - a +</option>
						<option value="desc-fecha_arribo">Fecha Arribo + a -</option>
						<option value="asc-fecha_caducidad">Fecha Caducidad - a +</option>
						<option value="desc-fecha_caducidad">Fecha Caducidad + a -</option>
					</select>
				</div>

			</div>
    </div>

		<!-- Main content -->
    <div class="w-full flex gap-4 items-start justify-center text-sm text-gray pl-4 pr-2" >

			<!-- col left - search -->
			<div class="col-left w-56 bg-grayMid rounded border border-grayMid p-2">
				<form id="form_search" class="search-column flex flex-col gap-y-4 w-full items-center justify-center p-3" method="post" >

					<div class="flex flex-col  w-full">
						<h5 class="text-center text-xs uppercase">Fecha Arribo</h5>
						<input id="fecha_arribo" type="date" name="fecha_arribo" >
					</div>

					<div class="relative flex flex-col  w-full">
						<h5 class="text-center text-xs uppercase">Lote Interno</h5>
						<input type="text" name="lote_interno" placeholder="Lote Interno">
					</div>


					<div class="relative flex flex-col  w-full">
						<h5 class="text-center text-xs uppercase">Materia Prima</h5>
						<input type="text" name="materia_prima" placeholder="Materia Prima">
					</div>
					<div class="relative flex flex-col  w-full">
						<h5 class="text-center text-xs uppercase">Proveedor</h5>
						<input type="text" name="proveedor" placeholder="Proveedor">
					</div>
					<div class="flex flex-col  w-full">
						<h5 class="text-center text-xs uppercase">Fecha Caducidad</h5>
						<input id="fecha_caducidad" type="date" name="fecha_caducidad" >
					</div>

					<div class="flex w-full gap-4">
						<button id="search_reset" class="btn btn-sm btn--cancel" type="button">
							<i class="fas fa-refresh"></i>
						</button>
						<button class="btn btn-md btn--search" type="submit">
							<i class="fas fa-search"></i>
							<span>BUSCAR</span>
						</button>
					</div>
				</form>
			</div>

			<div class="col-right w-full">
				<div class="relative w-full h-[65vh] overflow-y-scroll mx-auto">
					<table id="tabla-insp-materias">
						<thead>
							<tr>
								<th>Lote Interno</th>
								<th>Materia Prima</th>
								<th>Nombre Proveedor</th>
								<th>Creado Por</th>
								<th>Fecha Arribo</th>
								<th>Fecha Caducidad</th>
								<th>Fecha Creacion</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody></tbody>

					</table>
					<div class="row--empty">No hay datos</div>
					
				</div>
			</div>

    </div>
  </div>


<!-- Modal Delete -->
<div id="modal-delete" class="modal modal-md">
	<div class="modal-content">
		<form id="form_delete" class="modal-body">
			<h3 class="text-title text-4xl text-center py-32 ">¿Eliminar Permanentemente?</h3>
			<div class="flex w-full md:w-2/3 justify-between mx-auto text-sm ">
				<button data-dismiss="modal" class="modal-btn--cancel" type="button">
					CANCELAR
				</button>
				<button class="modal-btn--delete" type="submit">
					ELIMINAR
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

const search_reset = document.querySelector('#search_reset');
search_reset?.addEventListener('click', () => {
	const form = document.querySelector('#form_search');

	Service.show('.loading');
	form.reset();
	loadRows();
	Service.hide('.loading');
});

const loadSapBtn = () => {
	const allBtnSAP = document.querySelectorAll('#tabla-insp-materias .btn_sap');
  allBtnSAP?.forEach( (btn, index) => {
      btn.addEventListener('click', e => {
				Service.show('.loading');

				e.stopPropagation()
        let inspeccion_id = btn.dataset.id;
				Service.exec('get', `/inspeccion/upload_sap/${inspeccion_id}`)
				.then(r => {
					SearchOrder.loadPreviousSearch();
					Service.hide('.loading');
				}); 
			});
	});
}

const loadDelBtn = () => {
	document.addEventListener('click', (e) => {
		const btn = e.target.closest('.btn_delete');
		if (btn) {
			const modal = btn.dataset.modal;
			Modal.init(modal).open();
			let form_delete = document.querySelector(`#form_delete`);
			form_delete.dataset.id = btn.dataset.id;
		}
	});
};

const tbody = document.querySelector('#tabla-insp-materias tbody');

const renderRows = (data) => {
    tbody.innerHTML = "";

    if (!data.length) {
        Service.show('.row--empty');
        return;
    }

    data.forEach(cell => {
        const row = document.createElement('tr');
				let upload_SAP = (cell.upload_SAP == 1) ? "text-success" : '';

				let disabled_button = '';
				
				<?php if (!hasRole('calidad')) : ?>
					disabled_button = 'disabled';
				<?php endif; ?>

        row.innerHTML =
            `
            <td>
                <span>${cell.observaciones.lote_interno || "N/A"}</span>
            </td>
            <td>
                <span>${cell.observaciones.materia_prima || "N/A"}</span>
            </td>
            <td>
                <span>${cell.observaciones.proveedor || "N/A"}</span>
            </td>
            <td>
                <span>${cell.name} ${cell.last_name}</span>
            </td>
            <td>
                <span>${cell.observaciones.fecha_arribo || "N/A"}</span>
            </td>
            <td>
                <span>${cell.observaciones.fecha_caducidad || "N/A"}</span>
            </td>
            <td>
                <span>${dateToString(cell.created_at)}</span>
            </td>
            <td>
                <div class="flex items-center gap-2">
										<button type="button" data-id="${cell.inspeccion_id}" class="btn_sap" ><i class="fas fa-circle-check text-lg ${upload_SAP}"></i></button>
                    <a href="${root}/inspeccion/details/materias-primas/${cell.inspeccion_id}" class=" hover:text-icon" ><i class="fas fa-eye text-lg"></i></a>
                    <a href="${root}/inspeccion/update/materias-primas/${cell.inspeccion_id}" class=" hover:text-warning" ><i class="fas fa-pencil text-lg"></i></a>
                    <a href="${root}/inspeccion/print/materias-primas/${cell.inspeccion_id}" class=" hover:text-link" ><i class="fas fa-print text-lg"></i></a>
                    <button data-id="${cell.inspeccion_id}" class="btn_delete hover:text-red" data-modal="modal-delete" type="button" ${disabled_button}><i class="fas fa-trash-alt text-lg"></i></button>
                </div>
            </td>
        `;

        tbody.appendChild(row);
    });

    loadSapBtn();
    loadDelBtn();
};




	const form_delete = document.querySelector('#form_delete');
		form_delete.addEventListener('submit', e => {
		e.preventDefault();
		Service.stopSubmit(e.target, true);
		Service.show('.loading');

		let id = form_delete.dataset.id;

		Service.exec('get', `inspeccion/mt_delete/${id}`)
		.then( r => {
			if (r.success) {
				Modal.init("modal-delete").close();
				Service.stopSubmit(e.target, false);

				setTimeout(() => {
					Service.hide('.loading');
					Modal.init("modal_success").open();
				}, 500)

				loadRows();
			}
		});
	});


  const loadRows = () => {
    Service.hide('.row--empty');
    tbody.innerHTML = Service.loader();

    Service.exec('get', `/inspeccion/get_lista_insp/` + "<?= $formatoSlug ?>")
    .then(r => renderRows(r));  
  }

  // loadRows();

	SearchOrder.init(['tabla-insp-materias', 'order_by', 'form_search', '/inspeccion/search_insp_materias/<?= $formatoId ?>'], [
		['string', 'lote'],
		['string', 'materia'],
		['string', 'proveedor'],
		['string', 'creado_por'],
		['date', 'fecha_arribo'],
		['date', 'fecha_caducidad'],
		['date', 'fecha_creacion'],
	]);


</script>
</body>
</html>