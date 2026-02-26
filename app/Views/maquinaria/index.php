<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>
  <script src="<?= load_asset('js/Modal.min.js') ?>"></script>
  <script src="<?= load_asset('js/SearchOrder.min.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">
	<img src="<?= base_url('img/mantenimientoweb.svg') ?>" class="hidden lg:flex absolute bottom-0 left-0 ">
	<img src="<?= base_url('img/mantenimientomovil.svg') ?>" class="flex lg:hidden absolute bottom-0 left-0 ">

	<div class="relative flex flex-col gap-y-2 items-center justify-center font-titil ">
    <?php echo view('mantenimiento/_partials/navbar'); ?>

		<!-- title group - title page -->
		<!-- <div class="relative text-title w-full mt-2 py-2 px-6 lg:px-10 flex items-center justify-center ">
			<h2 class="text-center font-semibold text-3xl ">Maquinaria</h2>
			<a href="<?= base_url('apps') ?>" class="px-1 absolute right-6 lg:right-10 top-1/2 -translate-y-1/2 hover:scale-110 transition-transform duration-100 ">
				<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
			</a>
		</div> -->


    <div class="relative text-title w-full py-2 px-4 lg:px-10 flex items-center justify-between ">
			<div class="hidden lg:flex gap-4 items-center absolute left-6 lg:left-10 top-1/2 -translate-y-1/2">
				<a href="<?= base_url('produccion') ?>" class="hover:scale-110 transition-transform duration-100 ">
					<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
				</a>
			</div>

			
			<div class="w-full flex items-center justify-start xl:justify-center">
				<h2 class="text-center font-semibold w-fit text-2xl lg:text-3xl ">Lista de <?= esc($title) ?></h2>
			</div>

			<div class=" flex gap-4 items-center absolute right-4 lg:right-10 top-1/2 -translate-y-1/2">

				<div class="flex w-fit items-center text-gray gap-x-2 text-sm">
					<span>ORDENAR POR :</span>
					<select id="order_by" class="select_order w-44">
						<option value="" disabled selected>Seleccionar...</option>
						<option value="asc-modelo">+ modelo</option>
						<option value="desc-modelo">- modelo</option>
						<option value="asc-nombre">+ nombre</option>
						<option value="desc-nombre">- nombre</option>
					</select>
				</div>

				<button data-modal="modal_create" class="modal-open-btn btn btn-sm btn--primary" type="button">
					<i class="fa fa-plus text-base"></i>
					<span>Nueva Máquina</span>
				</button>

			</div>
    </div>


		<!-- Main content -->
		<div class="w-full flex gap-4 items-start justify-center text-sm text-gray pl-4 pr-2" >
			<div class="col-left w-48 bg-grayMid rounded border border-grayMid p-2">
				<form id="form_search" class="search-column flex flex-col gap-y-4 w-full items-center justify-center p-3" method="post" >
					<?= csrf_field() ?>

					<!-- <div class="relative flex flex-col  w-full">
						<h5 class="text-center text-xs uppercase">Id</h5>
						<input type="text" name="maqId">
					</div> -->
					<div class="relative flex flex-col  w-full">
						<h5 class="text-center text-xs uppercase">Clave</h5>
						<input type="text" name="clave">
					</div>

					<div class="relative flex flex-col  w-full">
						<h5 class="text-center text-xs uppercase">Tipo</h5>
						<select name="tipo">
							<option value="" selected>Seleccionar...</option>
							<option>CAPSULAS</option>
							<option>POLVOS</option>
							<option>LIQUIDOS</option>
							<option>STICK</option>
							<option>JARABES</option>
							<option>SERVICIOS GENERALES</option>
						</select>
					</div>

					<div class="relative flex flex-col  w-full">
						<h5 class="text-center text-xs uppercase">Nombre</h5>
						<input type="text" name="nombre">
					</div>
					<div class="relative flex flex-col  w-full">
						<h5 class="text-center text-xs uppercase">Marca</h5>
						<input type="text" name="marca">
					</div>
					<!-- <div class="relative flex flex-col  w-full">
						<h5 class="text-center text-xs uppercase">Modelo</h5>
						<input type="text" name="modelo">
					</div> -->


					<div class="relative flex flex-col  w-full">
						<h5 class="text-center text-xs uppercase">planta</h5>
						<select id="planta_search" name="planta" class="text-center to_uppercase" >
							<option value="" disabled selected>Seleccionar...</option>
							<?php foreach ($plantas as $planta): ?>
								<option value="<?= $planta['planta'] ?>"><?= $planta['planta'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>


					<div class="relative flex flex-col  w-full">
						<h5 class="text-center text-xs uppercase">linea</h5>
						<select id="linea_search" name="linea" class="text-center to_uppercase">
						</select>
					</div>


					<div class="flex flex-col  w-full">
						<h5 class="text-center text-xs uppercase">Fecha</h5>
						<input id="fechaAdqui" type="date" name="fechaAdqui" >
					</div>


					<div class="relative flex flex-col  w-full">
						<h5 class="text-center text-xs uppercase">Estado</h5>
						<select name="estado" >
							<option value="" selected>Seleccionar...</option>
							<option>FUNCIONAL</option>
							<option>NO FUNCIONAL</option>
							<option>PARCIALMENTE FUNCIONAL</option>
						</select>
					</div>


					<button type="submit" class="btn btn-md btn--search"><i class="fas fa-search"></i> <span>BUSCAR</span></button>
				</form>
			</div>

			<div class="col-right w-full">

				<div class="relative w-full text-sm h-[65vh] overflow-y-scroll mx-auto">
					<table id="tabla-lista-maquinaria">
						<thead>
							<tr>
								<th>Id</th>
								<th>Clave</th>
								<th>Tipo</th>
								<th>Nombre</th>
								<th>Marca</th>
								<th>Modelo</th>
								<th>Planta</th>
								<th>Linea</th>
								<th>Fecha Adq.</th>
								<th>Estado</th>
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




<!-- Modal create -->
<div id="modal_create" class="modal modal-lg">
	<div class="modal-content">

		<div class="modal-header">
			<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>
			<h3>Registrar Nueva Máquina</h3>
		</div>

		<form id="form_create" method="post" class="modal-body" enctype='multipart/form-data'>
			<?= csrf_field() ?>


			<div class="w-full px-1 lg:px-2 flex flex-col gap-y-8 max-h-[65vh] overflow-y-scroll  ">


				<div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Clave</h5>
						<input type="text" name="clave" class="input_modal text-center to_uppercase" >
					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Tipo</h5>
						<select id="tipo"  name="tipo" class="select_modal text-center to_uppercase" >
							<option value="" disabled selected>Seleccionar ....</option>
							<option>CAPSULAS</option>
							<option>POLVOS</option>
							<option>LIQUIDOS</option>
							<option>STICK</option>
							<option>JARABES</option>
							<option>SERVICIOS GENERALES</option>
						</select>
					</div>

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">Nombre</h5>
						<input type="text" name="nombre" class="input_modal text-center to_uppercase">
					</div>

				</div>

				<div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">Marca</h5>
						<input type="text" id="marca" class="input_modal" name="marca" placeholder="Marca ..." >
					</div>

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">Modelo</h5>
						<input type="text" id="modelo" class="input_modal" name="modelo" placeholder="Modelo ..." >
					</div>

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">N° Serie</h5>
						<input type="text" id="serie" class="input_modal" name="serie" placeholder="N° Serie ..." >
					</div>

				</div>


				<div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Planta</h5>
						<select id="planta"  name="planta" class="select_modal text-center to_uppercase" >
							<option value="" disabled selected>Seleccionar...</option>
							<?php foreach ($plantas as $planta): ?>
								<option value="<?= $planta['planta'] ?>"><?= $planta['planta'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Linea</h5>
						<select id="linea"  name="linea" class="select_modal text-center to_uppercase" >
							<option value="" disabled selected>Seleccionar...</option>
						</select>
					</div>

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">FECHA Adquisicion</h5>
						<input id="fechaAdqui" type="date" class="input_modal" name="fechaAdqui" required>
					</div>

				</div>

				<div class="flex w-full items-center justify-center gap-x-8 text-sm text-gray ">
					<div class="relative flex flex-col w-1/3 px-2 ">
						<h5 class="text-center uppercase">Estado</h5>
						<select id="estado"  name="estado" class="select_modal text-center to_uppercase" >
							<option value="" disabled selected>Seleccionar ....</option>
							<option value="FUNCIONAL">FUNCIONAL</option>
							<option value="NO FUNCIONAL">NO FUNCIONAL</option>
							<option value="PARCIALMENTE FUNCIONAL">PARCIALMENTE FUNCIONAL</option>
						</select>
					</div>
				</div>


				<div class="modal-toggle-wrapper ">
					<h3 class="text-title text-center uppercase font-bold pt-2"><i class="fas fa-plus"></i> Adjuntar Archivo</h3>
					<div class="flex flex-col space-y-2 w-full text-sm">
						<div class=" modal-drag-drop-wrapper">
							<div data-id="drop-crear" class="drop-area modal-drag-drop">
								<span>Arrastre y suelte sus archivos para agregarlos.</span>
								<input type="file" class="hidden" multiple>
								<ul></ul>
							</div>
							<div class="form-row-submit ">
								<button class="btn_file_click modal-btn--submit" type="button" >
								ABRIR CARPETA
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="form-row-submit ">
					<button class=" modal-btn--submit" type="submit" >
					AÑADIR
					</button>
				</div>

			</div>


		</form>
	</div>
</div>


<!-- Modal details -->
<div id="modal_details" class="modal modal-lg">
	<div class="modal-content">

		<div class="modal-header">
			<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>
			<h3>Detalles Máquina</h3>

		</div>
		
		<form id="form_details" method="post" class="modal-body" enctype='multipart/form-data'>

			<div class="w-full px-1 lg:px-2 flex flex-col gap-y-8 max-h-[65vh] overflow-y-scroll  ">

				<div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Clave</h5>
						<input readonly type="text" name="clave" class="text-center to_uppercase" value="gasfgadfgsdfgsdfg">
					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Tipo</h5>
						<input readonly type="text" name="tipo" class="text-center to_uppercase" >

					</div>

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">Nombre</h5>
						<input readonly type="text" name="nombre" class="text-center to_uppercase">
					</div>

				</div>

				<div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">Marca</h5>
						<input readonly type="text" id="marca" name="marca" placeholder="Marca ..." class="text-center to_uppercase" >
					</div>

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">Modelo</h5>
						<input readonly type="text" id="modelo" name="modelo" placeholder="Modelo ..." class="text-center to_uppercase">
					</div>

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">N° Serie</h5>
						<input readonly type="text" id="serie" name="serie" placeholder="N° Serie ..." class="text-center to_uppercase">
					</div>

				</div>


				<div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Planta</h5>
						<input readonly type="text" id="planta" name="planta" class="text-center to_uppercase">

					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Linea</h5>
						<input readonly type="text" id="linea" name="linea" class="text-center to_uppercase">

					</div>

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">FECHA Adquisicion</h5>
						<input readonly type="text" id="fechaAdqui" name="fechaAdqui" class="text-center to_uppercase">

					</div>

				</div>

				<div class="flex w-full items-center justify-center gap-x-8 text-sm text-gray ">
					<div class="relative flex flex-col w-1/3 px-2 ">
						<h5 class="text-center uppercase">Estado</h5>
						<input readonly type="text" id="estado" name="estado" class="text-center to_uppercase">

					</div>
				</div>

				<div class="modal-toggle-wrapper ">
					<h3 class="text-title text-center uppercase font-bold pt-2">Archivos Adjuntos</h3>

					<div class="flex flex-col space-y-2 w-full text-sm">
						<div class="flex w-full text-center">
							<div class="w-28 bg-icon text-white p-2">Fecha</div>
							<div class="flex-grow bg-icon text-white p-2">Archivo</div>
						</div>
						<div class="files_container  flex flex-col space-y-2 w-full "></div>
					</div>
				</div>


			</div>


		</form>
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

<!-- Modal Edit -->
<div id="modal-edit" class="modal modal-lg">
  <div class="modal-content">
		<div class="modal-loading">
			<div><span class="loader"></span></div>
		</div>

    <div class="modal-header">
      <button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>
      <h3>Editar Máquina</h3>
    </div>

    <form id="form_edit" method="post" class="modal-body" enctype='multipart/form-data'>

			<div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">
				<div class="relative flex flex-col w-full ">
					<h5 class="text-center uppercase">Clave</h5>
					<input type="text" name="clave" class="input_modal text-center to_uppercase" >
				</div>

				<div class="relative flex flex-col w-full ">
					<h5 class="text-center uppercase">Tipo</h5>
					<select id="tipo_edit" name="tipo" class="select_modal text-center to_uppercase">
						<option value="" selected>Seleccionar...</option>
						<option value="CAPSULAS">CAPSULAS</option>
						<option value="POLVOS">POLVOS</option>
						<option value="LIQUIDOS">LIQUIDOS</option>
						<option value="STICK">STICK</option>
						<option value="JARABES">JARABES</option>
						<option value="SERVICIOS GENERALES">SERVICIOS GENERALES</option>
					</select>
				</div>

				<div class="flex flex-col w-full ">
					<h5 class="text-center uppercase">Nombre</h5>
					<input type="text" name="nombre" class="input_modal text-center to_uppercase">
				</div>
			</div>

			<div class="flex flex-col gap-y-4 md:flex-row  w-full items-center justify-between gap-x-8 text-sm text-gray ">
				<div class="flex flex-col w-full ">
					<h5 class="text-center uppercase">Marca</h5>
					<input type="text" id="marca" name="marca" placeholder="Marca ..." class="input_modal text-center to_uppercase" >
				</div>

				<div class="flex flex-col w-full ">
					<h5 class="text-center uppercase">Modelo</h5>
					<input type="text" id="modelo" name="modelo" placeholder="Modelo ..." class="input_modal text-center to_uppercase">
				</div>

				<div class="flex flex-col w-full ">
					<h5 class="text-center uppercase">N° Serie</h5>
					<input type="text" id="serie" name="serie" placeholder="N° Serie ..." class="input_modal text-center to_uppercase">
				</div>
			</div>

			<div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">
				<div class="relative flex flex-col w-full ">
					<h5 class="text-center uppercase">Planta</h5>

					<select id="planta_edit" name="planta" class="select_modal text-center to_uppercase" >
						<?php foreach ($plantas as $planta): ?>
							<option value="<?= $planta['planta'] ?>"><?= $planta['planta'] ?></option>
						<?php endforeach; ?>
					</select>

				</div>

				<div class="relative flex flex-col w-full ">
					<h5 class="text-center uppercase">Linea</h5>
					<select id="linea_edit" name="linea" class="select_modal text-center to_uppercase">
						<option value="Línea de Envasado">Línea de Envasado</option>
						<option value="Línea de Acondicionado">Línea de Acondicionado</option>
						<option value="Servicios Generales">Servicios Generales</option>
					</select>
				</div>

				<div class="flex flex-col w-full ">
					<h5 class="text-center uppercase">FECHA Adquisicion</h5>
					<input id="fechaAdqui" type="date" name="fechaAdqui" class="input_modal text-center to_uppercase">
				</div>
			</div>

			<div class="flex w-full items-center justify-center gap-x-8 text-sm text-gray ">
				<div class="relative flex flex-col w-1/3 px-2 ">
					<h5 class="text-center uppercase">Estado</h5>
					<select id="estado_edit"  name="estado" class="select_modal text-center to_uppercase" >
						<option value="" disabled selected>Seleccionar ....</option>
						<option value="FUNCIONAL">FUNCIONAL</option>
						<option value="NO FUNCIONAL">NO FUNCIONAL</option>
						<option value="PARCIALMENTE FUNCIONAL">PARCIALMENTE FUNCIONAL</option>
					</select>
				</div>
			</div>

			<div class="modal-toggle-wrapper ">
				<h3 class="text-title text-center uppercase font-bold pt-2">Archivos Adjuntos</h3>

				<div class="flex flex-col space-y-2 w-full text-sm">
					<div class="flex w-full text-center">
						<div class="w-28 bg-icon text-white p-2">Fecha</div>
						<div class="flex-grow bg-icon text-white p-2">Archivo</div>
					</div>
					<div class="files_container  flex flex-col space-y-2 w-full "></div>
				</div>
			</div>


				<!-- <div class="modal-toggle-wrapper ">
					<h3 class="modal-toggle-title"><i class="fas fa-plus"></i> Adjuntar Archivo</h3>
					<div class="modal-toggle-content">
						<div class=" modal-drag-drop-wrapper">
							<div data-id="drop-editar" class="drop-area modal-drag-drop">
								<span>Arrastre y suelte sus archivos para agregarlos.</span>
								<input type="file" class="hidden" multiple>
								<ul></ul>
							</div>
							<div class="form-row-submit ">
								<button class="btn_file_click modal-btn--submit" type="button" >
								ABRIR CARPETA
								</button>
							</div>
						</div>
					</div>
				</div> -->


			<div class="modal-toggle-wrapper ">
				<h3 class="text-title text-center uppercase font-bold pt-2"><i class="fas fa-plus"></i> Adjuntar Archivo</h3>
				<div class="flex flex-col space-y-2 w-full text-sm">
					<div class=" modal-drag-drop-wrapper">
						<div data-id="drop-editar" class="drop-area modal-drag-drop">
							<span>Arrastre y suelte sus archivos para agregarlos.</span>
							<input type="file" class="hidden" multiple>
							<ul></ul>
						</div>
						<div class="form-row-submit ">
							<button class="btn_file_click modal-btn--submit" type="button" >
							ABRIR CARPETA
							</button>
						</div>
					</div>
				</div>
			</div>

      <div class="form-row-submit">
        <button type="submit" class="modal-btn--submit">Actualizar</button>
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


<script src="<?= load_asset('js/dragDrop.js') ?>"></script>
<script>
  Service.setLoading();
  
  // Modal.init('modal_success').open();


  const planta_search = document.getElementById('planta_search');
  const linea_search = document.getElementById('linea_search');


	planta_search?.addEventListener('change', e => {
    const planta_search = e.target.value.trim();

		Service.exec('get', `/get_lineas/${planta_search}`)
		// Service.exec('get', `/get_lineas_alt`)
		.then( r => {
			// console.log(r); return;
			resetSelect('linea_search'); 

			if (r.length > 0) {
				r.forEach(line => {
					let opt = document.createElement('option');
					opt.value = line.linea;
					opt.textContent = `${line.linea}`;
					linea_search.appendChild(opt);
				});

			}
		});
  });

  const planta = document.getElementById('planta');
  const linea = document.getElementById('linea');


	planta?.addEventListener('change', e => {
    const planta = e.target.value.trim();

		// Service.exec('get', `/get_lineas/${planta}`)
		Service.exec('get', `/get_lineas_alt`)
		.then( r => {
			// console.log(r); return;
			resetSelect('linea'); 

			if (r.length > 0) {
				r.forEach(line => {
					let opt = document.createElement('option');
					opt.value = line.linea;
					opt.textContent = `${line.linea}`;
					linea.appendChild(opt);
				});

			}
		});
  });




	const form_create = document.querySelector('#form_create');
		form_create.addEventListener('submit', e => {
		e.preventDefault();
		Service.stopSubmit(e.target, true);
		Service.show('.loading');

		const formData = new FormData(e.target);
		const files = fileStorage.get('drop-crear'); 

		if (files.length > 0) {
			files.forEach(file => {
				formData.append('archivo[]', file);
			});
		}

    Service.exec('post', `/maquinaria`, formData_header, formData)
    .then( r => {
      if(r.success){
				Modal.init("modal_create").close();
				Service.stopSubmit(e.target, false);

				setTimeout(() => {
					Service.hide('.loading');
					Modal.init("modal_success").open();
				}, 500)

				loadRows();

				clearFiles('drop-crear');
			}
    });
	});


	const form_edit = document.querySelector('#form_edit');
		form_edit.addEventListener('submit', e => {
		e.preventDefault();
		Service.stopSubmit(e.target, true);
		Service.show('.loading');

		const formData = new FormData(e.target);
		const files = fileStorage.get('drop-editar'); 

		if (files.length > 0) {
			files.forEach(file => {
				formData.append('archivo[]', file);
			});
		}

		let id = form_edit.dataset.id;
		if (id) {
			formData.append('id', id);
		}


    Service.exec('post', `/maquinaria`, formData_header, formData)
    .then( r => {
      if(r.success){
				Modal.init("modal-edit").close();
				Service.stopSubmit(e.target, false);

				setTimeout(() => {
					Service.hide('.loading');
					Modal.init("modal_success").open();
				}, 500)

				loadRows();

				clearFiles('drop-editar');
			}
    });
	});


	const clearFiles = (id) => {
		let archivo_files = fileStorage.get(id);
		if (archivo_files) archivo_files.length = 0;
	}




		const toggleContent = (titleElement) => {
      const content = titleElement.nextElementSibling; 
      content.classList.toggle('show');
    };

    document.querySelectorAll('.modal-toggle-title').forEach(title => {
      title.addEventListener('click', (event) => {
        event.stopPropagation();
        toggleContent(title);
      });
    });

    document.addEventListener('click', () => {
      document.querySelectorAll('.modal-toggle-content').forEach(content => {
        content.classList.remove('show');
      });
    });

    document.querySelectorAll('.modal-toggle-wrapper').forEach(answer => {
      answer.addEventListener('click', (event) => {
        event.stopPropagation();
      });
    });


	const form_delete = document.querySelector('#form_delete');
		form_delete.addEventListener('submit', e => {
		e.preventDefault();
		Service.stopSubmit(e.target, true);
		Service.show('.loading');

		let id = form_delete.dataset.id;

		Service.exec('get', `maquinaria/delete/${id}`)
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

	const initFiles = (id, files_container, edit = null) => {
		Service.exec('get', `/get_maq_files/${id}`)
		.then( r => {
			// console.log(r); return;
			files_container.innerHTML = '';
			if (r.length > 0) {
				r.forEach(adj => {
					const div = document.createElement('div');
					div.className = 'row-adjunto';

					let btnDel = "";
					
					if(edit) {
						btnDel = `
							<div class="px-4">
								<button data-id="${adj.id}" class="btn-delete hover:text-red" type="button">${getIcon('delete')}</button>
							</div>
						`;
					}

					div.innerHTML = `
						<div class="w-28 p-2 text-gray ">${dateToStringAlt(adj.created_at)}</div>
						<div class="w-full flex items-center justify-between">
							<div class="p-2 flex items-center gap-x-4 text-link">
								<i class="fas fa-paperclip"></i>
								<a href="${root}/files/download?path=maquinaria/${adj.archivo}" target="_blank" class="underline ">${getEncodedFileName(adj.archivo)}</a>
							</div>
							${btnDel}
						</div>
					`;


					files_container.appendChild(div);
				});


        files_container.querySelectorAll('.btn-delete').forEach(btn => {
          btn.addEventListener('click', () => {
						deleteFile(btn.dataset.id, id, files_container);
          });
        });

			} else {
				const div = document.createElement('div');
				div.className = 'row-adjunto';
				div.innerHTML = Service.empty('No se encontraron archivos.');
				files_container.appendChild(div);
			}

		});

	}


const deleteFile = (fileId, maqId, files_container) => {
	Service.exec('get', `maquinaria/delete/${fileId}/file`)
	.then( r => {
		if (r.success) {
			Service.show('#modal-edit .modal-loading');
			initFiles(maqId, files_container, 'edit');

			setTimeout(() => {
				Service.hide('#modal-edit .modal-loading');
			}, 500)
		}
	});
};


  const initRowBtn = () => {
    const allBtnEdit = document.querySelectorAll('#tabla-lista-maquinaria .btn_edit');
    allBtnEdit?.forEach( (btn, index) => {

      btn.addEventListener('click', e => {
				e.stopPropagation()
        let modal_id = btn.dataset.modal;
				Modal.init(modal_id).open();

				if (modal_id == 'modal_details') {
					let form_details = document.querySelector('#form_details')
          let id = btn.dataset.id;

          Service.exec('get', `/get_maq/${id}`)
          .then( r => {
            // console.log(r); return;
            form_details.querySelector('[name="clave"]').value = r.clave;
            form_details.querySelector('[name="tipo"]').value = r.tipo;
            form_details.querySelector('[name="nombre"]').value = r.nombre;
            form_details.querySelector('[name="marca"]').value = r.marca;
            form_details.querySelector('[name="modelo"]').value = r.modelo;
            form_details.querySelector('[name="serie"]').value = r.serie;
            form_details.querySelector('[name="planta"]').value = r.planta;
            form_details.querySelector('[name="linea"]').value = r.linea;
            form_details.querySelector('[name="fechaAdqui"]').value = dateToStringAlt(r.fechaAdqui);
            form_details.querySelector('[name="estado"]').value = r.estado;
  
          });

					let files_container = form_details.querySelector('.files_container');
          files_container.innerHTML = Service.loader();

					initFiles(id, files_container);

				}	else if (modal_id == 'modal-edit') {
					let form_edit = document.querySelector('#form_edit')
					form_edit.dataset.id = btn.dataset.id;
          let id = btn.dataset.id;

          Service.exec('get', `/get_maq/${id}`)
          .then( r => {
            // console.log(r); return;
            form_edit.querySelector('[name="clave"]').value = r.clave;
            form_edit.querySelector('[name="nombre"]').value = r.nombre;
            form_edit.querySelector('[name="marca"]').value = r.marca;
            form_edit.querySelector('[name="modelo"]').value = r.modelo;
            form_edit.querySelector('[name="serie"]').value = r.serie;
            form_edit.querySelector('[name="estado"]').value = r.estado;

						setSelectedOption('#form_edit #tipo_edit', r.tipo, 'string');
						setSelectedOption('#form_edit #linea_edit', r.linea, 'string');
						setSelectedOption('#form_edit #planta_edit', r.planta, 'string');
						setSelectedOption('#form_edit #estado_edit', r.estado, 'string');
  
						setDateToInput('#form_edit #fechaAdqui', r.fechaAdqui);
          });

					let files_container = form_edit.querySelector('.files_container');
          files_container.innerHTML = Service.loader();

					initFiles(id, files_container, 'edit');
				}
      });
    });

		loadDelBtn();
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
			

  const tbody = document.querySelector('#tabla-lista-maquinaria tbody');

  const renderRows = (data) => {  
		tbody.innerHTML = "";

		if (!data.length) {
			Service.show('.row--empty');
			return;
		}

		data.forEach(cell => {
			const row = document.createElement('tr');
			row.setAttribute('data-id', cell.id);
			row.innerHTML =
				`
					<td>
						<div class="row__center">
							<span>${format_id(cell.id, 'id')}</span>
						</div>
					</td>
					<td>
						<div class="row__center">
							<span>${cell.clave}</span>
						</div>
					</td>
					<td>
						<div class="row__center">
							<span>${cell.tipo}</span>
						</div>
					</td>
					<td>
						<div class="row__center">
							<span>${cell.nombre}</span>
						</div>
					</td>            
					<td>
						<div class="row__center">
							<span>${cell.marca}</span>
						</div>
					</td>
					<td>
						<div class="row__center">
							<span>${cell.modelo}</span>
						</div>
					</td>
					<td>
						<div class="row__center">
							<span>${cell.planta}</span>
						</div>
					</td>
					<td>
						<div class="row__center">
							<span>${cell.linea}</span>
						</div>
					</td>
					<td>
						<div class="row__fecha">
							<span>${dateToStringAlt(cell.fechaAdqui)}</span>
						</div>
					</td>
					<td>
						<div class="row__center text-xs">
							<span>${cell.estado}</span>
						</div>
					</td>
						<div class="flex items-center gap-2">
							<button data-id="${cell.id}" class="btn_edit hover:text-icon" data-modal="modal_details" type="button"><i class="fas fa-eye text-lg"></i>
							</button>
							<button data-id="${cell.id}" class="btn_edit  hover:text-blue" data-modal="modal-edit" type="button">${getIcon('edit')}</button>
							<button data-id="${cell.id}" class="btn_delete hover:text-red" data-modal="modal-delete" type="button">${getIcon('delete')}</button>
						</div>
					</td>

				`
			tbody.appendChild(row);
		});

		initRowBtn();
  }

  const loadRows = () => {
		Service.hide('.row--empty');
    tbody.innerHTML = Service.loader();

    Service.exec('get', `/all_maquinas`)
    .then(r => renderRows(r));  
  }


	SearchOrder.init(['tabla-lista-maquinaria', 'order_by', 'form_search', '/search_maquina'], [
		['int', 'id'],
		['string', 'clave'],
		['string', 'tipo'],
		['string', 'nombre'],
		['string', 'marca'],
		['string', 'modelo'],
		['string', 'planta'],
		['string', 'linea'],
		['date', 'fecha_compra'],
		['string', 'estado'],
	]);


</script>
</body>
</html>