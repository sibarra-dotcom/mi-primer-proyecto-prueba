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
			<a href="<?= base_url('apps') ?>" class="px-1 absolute right-6 lg:right-10 top-1/2 -translate-y-1/2 hover:scale-110 transition-transform duration-100 ">
				<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
			</a>
		</div>

		<div class="relative text-title w-full py-2 px-6 lg:px-10 flex items-center justify-between ">
			<div class="w-full flex items-center justify-start xl:justify-center">
				<h2 class="text-center font-semibold w-fit text-2xl lg:text-3xl "><?= esc($title) ?></h2>
			</div>

			<div class=" flex gap-4 items-center absolute right-6 lg:right-10 top-1/2 -translate-y-1/2">
				<a href="<?= base_url('export/comedor_entregas?date=' . date('Y-m-d')) ?>" class="btn btn-md btn--search uppercase">
					<i class="fas fa-download"></i>
					<span>Descargar Registros del Dia</span>
				</a>
			</div>
    </div>


    <div class="w-full text-sm text-gray  ">
      <div class="flex flex-col h-full" >

        <div class="w-full pl-6 pr-6 ">
					<div class="relative w-full text-sm h-[65vh] overflow-y-scroll mx-auto">
            <table id="tabla-comedor-lista">
              <thead>
                <tr>
                  <th># Pedido</th>
                  <th>Fecha</th>
                  <th>Nombre</th>
                  <th>Menu Dia</th>
                  <th>Menu Base</th>
                  <th>Especificaciones</th>
                  <th>Horario</th>
                </tr>
              </thead>
              <tbody></tbody>

            </table>
            <div id="row__empty" class="row__empty">No hay datos</div>
            
          </div>
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


const tbody = document.querySelector('#tabla-comedor-lista tbody');
const renderRows = (data) => {  
	tbody.innerHTML = "";

	if (data.length > 0) {
		Service.hide('#row__empty');
		let count = 1;
		
		data.forEach(orden => {
			const row = document.createElement('tr');

			row.innerHTML =
				`
					<td>
						<span>${count++}</span>
					</td>
					<td>
						<span>${dateToStringAlt(orden.fecha)}</span>
					</td>
					<td>
						<span>${orden.nombre}</span>
					</td>
					<td>
						<span>${orden.menu_dia}</span>
					</td>
					<td>
						<span>${orden.menu_base}</span>
					</td>
					<td>
						<span>${orden.observacion}</span>
					</td>
					<td>
						<span>${orden.horario}</span>
					</td>
				`
			tbody.appendChild(row);
		});

		// initRowBtn();
	} else {
		// initRowBtn();
		Service.show('#row__empty');
	}
}

const loadRows = () => {
	Service.hide('#row__empty');
	tbody.innerHTML = Service.loader();

	Service.exec('get', `comedor/lista`)
	.then(r => renderRows(r));  
}

loadRows();

</script>
</body>
</html>