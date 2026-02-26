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

  	<?php echo view('produccion/_partials/navbar_old'); ?>

		<div class="relative text-title w-full py-2 px-4 lg:px-10 flex items-center justify-between ">

			<div class="hidden lg:flex gap-4 items-center absolute left-6 lg:left-10 top-1/2 -translate-y-1/2">
				<a href="<?= base_url('produccion/productos') ?>" class="hover:scale-110 transition-transform duration-100 ">
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


			<!-- col right - table -->
			<div class="col-right w-full">
				
				<div class="relative w-full h-[70vh] overflow-y-scroll mx-auto">
					<table id="tabla-lista-orden-fab">
						<thead>
							<tr>
								<th>N. Orden</th>
								<th>Articulo</th>
								<th>Cod Art.</th>
								<th>Nombre Deudor</th>
								<th>Status</th>
								<th>Lote</th>
								<th>Fecha Creacion</th>
								<th>Fecha Inicio</th>
								<th>Ultimo Rep.</th>
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




<!-- modal create -->
<div id="modal_create" class="modal modal-md">
	<div class="modal-content">

		<div class="modal-header">
			<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>
			<h3>Registrar Nuevo Producto</h3>
		</div>

		<form id="form_create" method="post" class="modal-body">
			<div class="w-full flex flex-col gap-y-6 ">

				<div class="relative flex flex-col md:flex-row items-center gap-x-8 ">
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Codigo</h5>
						<input type="text" name="codigo" placeholder="Codigo" >
					</div>
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Linea</h5>
						<select name="linea" class="text-center to_uppercase" >
							<option value="" disabled selected>Seleccionar...</option>

						</select>
					</div>
				</div>

				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Descripcion</h5>
					<input type="text" name="descripcion" placeholder="Descripcion" >
				</div>

				<div class="relative flex flex-col md:flex-row items-center gap-x-8 ">
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Peso o Volumen</h5>
						<input type="text" name="peso_volumen" placeholder="Peso o Volumen" >
					</div>
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Unidad Medida</h5>
						<input type="text" name="unidad_medida" placeholder="Unidad Medida" >
					</div>
				</div>


			</div>

			<div class="form-row-submit ">
				<button class=" modal-btn--submit" type="submit" >
				GUARDAR
				</button>
			</div>

		</form>
	</div>
</div>

<!-- modal edit -->
<div id="modal_edit" class="modal modal-md">
	<div class="modal-content">

		<div class="modal-header">
			<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>
			<h3>Editar Producto</h3>
		</div>

		<form id="form_edit" method="post" class="modal-body">
			<div class="w-full flex flex-col gap-y-6 ">
				<div class="relative flex flex-col md:flex-row items-center gap-x-8 ">
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Codigo</h5>
						<input type="text" name="codigo" placeholder="Codigo" >
					</div>
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Linea</h5>
						<select id="linea"  name="linea" class="text-center to_uppercase" >
							<option value="" disabled selected>Seleccionar...</option>

						</select>
					</div>
				</div>

				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Descripcion</h5>
					<input type="text" name="descripcion" placeholder="Descripcion" >
				</div>

				<div class="relative flex flex-col md:flex-row items-center gap-x-8 ">
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Peso o Volumen</h5>
						<input type="text" name="peso_volumen" placeholder="Peso o Volumen" >
					</div>
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Unidad Medida</h5>
						<input type="text" name="unidad_medida" placeholder="Unidad Medida" >
					</div>
				</div>
			</div>

			<div class="form-row-submit ">
				<button class=" modal-btn--submit" type="submit" >
				Actualizar
				</button>
			</div>

		</form>
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


const tbody = document.querySelector('#tabla-lista-orden-fab tbody');

const renderRows = (data) => {  
	tbody.innerHTML = "";

	if (!data.length) {
		Service.show('.row--empty');
		return;
	}

	data.forEach(orden => {
		const row = document.createElement('tr');

		row.innerHTML =
			`
				<td>
					<span>${orden.num_orden}</span>
				</td>
				<td>
					<span>${orden.desc_articulo}</span>
				</td>
				<td>
					<span>${orden.num_articulo}</span>
				</td>
				<td>
					<span>${orden.nombre_deudor}</span>
				</td>
				<td>
					<span>${orden.status_pedido}</span>
				</td>
				<td>
					<span>${orden.lote}</span>
				</td>
				<td>
					<span>${dateToString(orden.created_at)}</span>
				</td>
				<td>
					<span>${dateToString(orden.fecha_primer_reporte)}</span>
				</td>
				<td>
					<span>${dateToString(orden.fecha_ultimo_reporte)}</span>
				</td>

				<td>
					<div class="flex items-center gap-2 ">
						<a href="${root}/liberaciones/create/${orden.num_orden}" class=" hover:text-link">
							<i class="fas fa-plus text-lg pr-1"></i>
							<span class=" text-base ">Liberacion</span>
						</a>
					</div>
				</td>
			`
		tbody.appendChild(row);
	});

	// initRowBtn();
}

const loadRows = () => {
	Service.hide('.row--empty');
	tbody.innerHTML = Service.loader();

	Service.exec('get', `produccion/all_ordenes`)
	.then(r => renderRows(r));  
}

loadRows();



</script>
</body>
</html>