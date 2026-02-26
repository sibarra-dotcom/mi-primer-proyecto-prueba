
<?php echo view('_partials/header'); ?>
  <link rel="stylesheet" href="<?= load_asset('_partials/cotizar.css') ?>">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/Modal.min.js') ?>"></script>

	<script src="https://unpkg.com/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>

  <title><?= esc($title) ?></title>
</head>
<body class="relative min-h-screen">

  <div class="relative flex flex-col gap-y-2 items-center justify-center font-titil mb-10 ">

  	<?php echo view('produccion/_partials/navbar'); ?>

		<div class="relative text-title w-full py-2 px-4 lg:px-10 flex items-center justify-between ">

			<div class="hidden lg:flex gap-4 items-center absolute right-6 lg:right-10 top-1/2 -translate-y-1/2">

			</div>

			<div class="w-full flex items-center justify-start xl:justify-center">
				<h2 class="text-center font-semibold w-fit text-2xl lg:text-3xl "><?= esc($title) ?></h2>
			</div>

			<div class=" flex gap-4 items-center absolute right-4 lg:right-32 top-1/2 -translate-y-1/2">
				<a href="<?= previous_url() ?>" class="hover:scale-110 transition-transform duration-100 ">
					<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
				</a>
			</div>
    </div>


		<form id="form_registro_diario" method="post" class="pdf-container" enctype='multipart/form-data'>
			<?= csrf_field() ?>

			<!-- Pagina 1 -->
			<div class=" page__container">
				<!-- Datos Formato Inspeccion -->
				<?php echo view("produccion/_partials/format-header"); ?>

				<!-- Datos Generales Inspeccion -->
				<div class="general__section">

					<div class="general__row--rep border-r mb-6 ">
						<div class="col header border-t border-l border-b w-[16%]">
							<span>SUPERVISOR / LIDER</span>
						</div>
						<div class="col border-t border-l border-b w-[24%] px-2">
							<input type="text" class="to_uppercase h-8 text-center" value="<?= session()->get('user')['name']; ?>" readonly>
						</div>

						<div class="col header border-t border-l border-b w-[10%] ">
							<span>TURNO</span>
						</div>
						<div class="col border-t border-l border-b w-[25%] px-2">
							<select class="w-full py-1 h-8 text-center" name="turnoId" required>
								<option value="" disabled selected>Seleccionar ...</option>
								<?php foreach ($turnos as $turno): ?>
										<option value="<?= esc($turno['id']) ?>" data-value="<?= esc($turno['label']) ?>">
												<?= esc($turno['label']) ?>
										</option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="col header border-t border-l border-b w-[10%] ">
							<span>FECHA</span>
						</div>
						<div class="col border-t border-l border-b w-[15%] px-2">
							<input type="text" class="to_uppercase h-8 text-center" value="<?= fechaEspanol(date('d-m-Y')); ?>" readonly>
						</div>
					</div>

					<div class="flex header items-center justify-center border-gray border w-full p-2 mb-2">
						<span><?= $secciones[1] ?></span>
					</div>
					
					<div class="general__row--rep header border-r border-t  ">
						<div class=" col w-[10%] border-l border-b">
							<span>LINEA</span>
						</div>
						<div class=" col w-[15%] border-l border-b">
							<span>CODIGO PT</span>
						</div>
						<div class=" col w-[40%] border-l border-b">
							<span>PRODUCTO</span>
						</div>
						<div class=" col w-[15%] border-l border-b">
							<span>ORDEN FABRIC.</span>
						</div>
						<div class=" col w-[10%] border-l border-b">
							<span>HORA INICIO</span>
						</div>
						<div class=" col w-[10%] border-l border-b">
							<span>HORA FIN</span>
						</div>
					</div>

					<div class="general__row--rep border-r">
						<div class="col border-l border-b w-[10%]">
							<select class="w-full py-1 h-8 text-center" name="linea" required>
								<option value="CAPSULAS">CAPSULAS</option>
								<option value="POLVOS">POLVOS</option>
								<option value="LIQUIDOS">LIQUIDOS</option>
							</select>

							<!-- <input type="text" name="linea" class="to_uppercase h-8 text-center" required> -->
						</div>
						<div class="col border-l border-b w-[15%]">
							<input type="text" class="to_uppercase h-8 text-center" value="<?= $ordenfab['num_articulo'] ?>" readonly>
						</div>
						<div class="col border-l border-b w-[40%]">
							<input type="text" class="to_uppercase h-8 text-center" value="<?= esc($ordenfab['desc_articulo']) ?>" readonly>
						</div>
						<div class="col border-l border-b w-[15%]">
							<input type="text" class="to_uppercase h-8 text-center" value="<?= esc($ordenfab['num_orden']) ?>" readonly>
						</div>
						<div class="col border-l border-b w-[10%]">
							<input type="text" name="hora_inicio_registro" class="to_uppercase h-8 text-center">
						</div>
						<div class="col border-l border-b w-[10%]">
							<input type="text" name="hora_fin_registro" class="to_uppercase h-8 text-center">
						</div>
					</div>

					<div class="general__row--rep header border-r">
						<div class="col border-l border-b w-[12%]">
							<span>CAJAS DEL TURNO</span>
						</div>
						<div class="col border-l border-b w-[12%]">
							<span>PIEZAS PRODUCIDAS</span>
						</div>
						<div class="col border-l border-b w-[12%]">
							<span>MUESTRAS RETENCION</span>
						</div>
						<div class="col border-l border-b w-[12%]">
							<span>PIEZAS ACUMULADAS</span>
						</div>
						<div class="col border-l border-b w-[12%]">
							<span>PIEZAS REQUERIDAS</span>
						</div>
						<div class="col border-l border-b w-[20%]">
							<span>LOTE DE PRODUCTO TERMINADO</span>
						</div>
						<div class="col border-l border-b w-[20%]">
							<span>CADUCIDAD DE PRODUCTO TERMINADO</span>
						</div>
					</div>

					<div class="general__row--rep border-r ">
						<div class="col border-l border-b w-[12%]">
							<input type="text" name="cajas_turno" class="to_uppercase h-8 text-center" readonly>
						</div>
						<div class="col border-l border-b w-[12%]">
							<input type="text" name="piezas_producidas" class="to_uppercase h-8 text-center">
						</div>
						<div class="col border-l border-b w-[12%]">
							<input type="text" name="muestras" class="to_uppercase h-8 text-center">
						</div>
						<div class="col border-l border-b w-[12%]">
							<input type="text" id="piezas_acumuladas"  class="to_uppercase h-8 text-center" value="<?= $acumulado ?>" readonly>
						</div>
						<div class="col border-l border-b w-[12%]">
							<input type="text" class="to_uppercase h-8 text-center" value="<?= esc($ordenfab['cantidad_plan']) ?>" readonly>
							<input type="hidden" id="num_piezas" value="<?= esc($ordenfab['num_piezas']) ?>">
							<input type="hidden" name="ordenId" value="<?= esc($ordenfab['id']) ?>">
						</div>
						<div class="col border-l border-b w-[20%]">
							<input type="text" class="to_uppercase h-8 text-center" value="<?= esc($ordenfab['lote']) ?>" readonly>
						</div>
						<div class="col border-l border-b w-[20%]">
							<input type="text" class="to_uppercase h-8 text-center" value="<?= esc($ordenfab['caducidad']) ?>" readonly>
						</div>
					</div>

					<div class="general__row--rep header border-r">
						<div class="col border-l border-b w-[8%]">
							<span>BATCH INICIAL</span>
						</div>
						<div class="col border-l border-b w-[8%]">
							<span>BATCH FINAL</span>
						</div>
						<div class="col border-l border-b w-[12%]">
							<span>CANTIDAD DE MEZCLA</span>
						</div>
						<div class="col border-l border-b w-[30%]">
							<span>LOTE DE MEZCLA ENVASADO</span>
						</div>
						<!-- <div class="col border-l border-b w-[8%]">
							<span>PESO TM</span>
						</div>
						<div class="col border-l border-b w-[8%]">
							<span>PESO TV</span>
						</div> -->
						<div class="col border-l border-b w-[20%]">
							<span># COLECTIVA DE CIERRE</span>
						</div>
						<div class="col border-l border-b w-[22%]">
							<span>ESTATUS DE LA FABRICACION</span>
						</div>
					</div>

					<div class="general__row--rep border-r mb-2">
						<div class="col border-l border-b w-[8%]">
							<input type="text" name="batch_inicial" class="to_uppercase h-8 text-center">
						</div>
						<div class="col border-l border-b w-[8%]">
							<input type="text" name="batch_final" class="to_uppercase h-8 text-center">
						</div>
						<div class="col border-l border-b w-[12%]">
							<input type="text" name="cantidad_mezcla" class="to_uppercase h-8 text-center">
						</div>
						<div class="col border-l border-b w-[30%]">
							<input type="text" name="lote_mezcla" class="to_uppercase h-8 text-center">
						</div>
						<!-- <div class="col border-l border-b w-[8%]">
							<input type="text" name="peso_tm" class="to_uppercase h-8 text-center">
						</div>
						<div class="col border-l border-b w-[8%]">
							<input type="text" name="peso_tv" class="to_uppercase h-8 text-center">
						</div> -->
						<div class="col border-l border-b w-[20%]">
							<input type="text" name="colectiva" class="to_uppercase h-8 text-center">
						</div>
						<div class="col border-l border-b w-[22%]">
							<select class="w-full py-1 h-8 text-center" name="status_fabricacion">
								<option value="EN PROCESO">EN PROCESO</option>
								<option value="FINALIZADO">FINALIZADO</option>
							</select>
						</div>
					</div>


					<div class="w-full text-white flex flex-col items-center justify-between gap-y-2 mb-6">
						<textarea name="observacion_produc" id="" rows="4" placeholder="Escribir observación ..." class="w-full border border-gray p-2 text-gray outline-none resize-none " ></textarea>
					</div>


					<div class="flex header items-center justify-center border-gray border w-full p-2 mb-2">
						<span><?= $secciones[2] ?></span>
					</div>

					<div class="flex w-full justify-end items-center mb-2">
						<button id="add-incidencia" class=" rounded text-icon border-2 border-icon hover:bg-icon hover:text-white py-1 px-3 w-fit uppercase" type="button">
							<i class="fa fa-plus text-xl"></i>
							<span>Agregar incidencia</span>
						</button>
					</div>
					
					<div id="incidencias-container" class="general__section mb-6">
						<div class="w-full flex border-gray header border-r border-t">
							<div class="col border-gray border-l border-b w-[55%] ">
								<p class="w-full text-center py-2">DESCRIPCION DEL PARO</p>
							</div>
							<div class="col border-gray border-l border-b w-[15%] ">
								<p class="w-full text-center py-2">HORA DE INICIO</p>
							</div>
							<div class="col border-gray border-l border-b w-[15%] ">
								<p class="w-full text-center py-2">HORA DE FIN</p>
							</div>
							<div class="col border-gray border-l border-b w-[15%] ">
								<p class="w-full text-center py-2">TIEMPO DE PARO</p>
							</div>
						</div>

						<!-- insert personal rows -->
					</div>


					<div class="flex header items-center justify-center border-gray border w-full p-2 mb-2">
						<span><?= $secciones[3] ?></span>
					</div>

					<div class="flex w-full justify-end items-center mb-2">
						<button id="add-operario" class=" rounded text-icon border-2 border-icon hover:bg-icon hover:text-white py-1 px-3 w-fit uppercase" type="button">
							<i class="fa fa-plus text-xl"></i>
							<span>Agregar operario</span>
						</button>
					</div>
					
					<div id="operarios-container" class="general__section mb-6">
						<div class="w-full flex border-gray header border-r border-t">
							<div class="col border-gray border-l border-b w-[70%] ">
								<p class="w-full text-center py-2">OPERADOR</p>
							</div>
							<div class="col border-gray border-l border-b w-[30%] ">
								<p class="w-full text-center py-2">POSICION</p>
							</div>
						</div>

						<!-- insert personal rows -->
					</div>
				</div>


			</div>


			<!-- Pagina 2 -->
			<div class=" page__container">
				<!-- Datos Formato Inspeccion -->
				<?php echo view("produccion/_partials/format-header"); ?>

				<div class="general__section">
					<!-- inspeccion materiales -->
					<div class="flex header items-center justify-center border-gray border w-full p-2 mb-2">
						<span><?= $secciones[4] ?></span>
					</div>

					<div id="componentes_wrapper" class="w-full grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
						<!-- Column 1 -->
						<div class="col-1  w-full flex flex-col border-gray border-l">
							<div class="flex items-center header border-b border-r border-gray justify-between w-full">
								<div class="col border-gray border-r w-[50%] ">
									<p class="w-full text-center py-2">COMPONENTE</p>
								</div>
								<div class="col border-gray border-r w-[10%] ">
									<p class="w-full text-center py-2">C</p>
								</div>
								<div class="col border-gray border-r w-[10%] ">
									<p class="w-full text-center py-2">N/A</p>
								</div>
								<div class="col border-gray border-r w-[15%] ">
									<p class="w-full text-center py-2">MERMA</p>
								</div>
								<div class="col border-gray w-[15%] ">
									<p class="w-full text-center py-2">UNIDAD</p>
								</div>
							</div>
							<div id="col-inspec-1" class="componentes-list"></div>
						</div>

						<!-- Column 2 -->
						<div class="col-2 w-full flex flex-col border-gray border-l">
							<div class="flex items-center header border-b border-r border-gray justify-between w-full">
								<div class="col border-gray border-r w-[50%] ">
									<p class="w-full text-center py-2">COMPONENTE</p>
								</div>
								<div class="col border-gray border-r w-[10%] ">
									<p class="w-full text-center py-2">C</p>
								</div>
								<div class="col border-gray border-r w-[10%] ">
									<p class="w-full text-center py-2">N/A</p>
								</div>
								<div class="col border-gray border-r w-[15%] ">
									<p class="w-full text-center py-2">MERMA</p>
								</div>
								<div class="col border-gray w-[15%] ">
									<p class="w-full text-center py-2">UNIDAD</p>
								</div>
							</div>
							<div id="col-inspec-2" class="componentes-list"></div>
						</div>

						<!-- Column 3 -->
						<div class="col-3 lg:col-span-2 w-full lg:w-1/2 mx-auto w-full flex flex-col border-gray border-l">
							<div class="flex items-center header border-b border-r border-gray justify-between w-full">
								<div class="col border-gray border-r w-[50%] ">
									<p class="w-full text-center py-2">COMPONENTE</p>
								</div>
								<div class="col border-gray border-r w-[10%] ">
									<p class="w-full text-center py-2">C</p>
								</div>
								<div class="col border-gray border-r w-[10%] ">
									<p class="w-full text-center py-2">N/A</p>
								</div>
								<div class="col border-gray border-r w-[15%] ">
									<p class="w-full text-center py-2">MERMA</p>
								</div>
								<div class="col border-gray w-[15%] ">
									<p class="w-full text-center py-2">UNIDAD</p>
								</div>
							</div>
							<div id="col-inspec-3" class="componentes-list"></div>
						</div>
					</div>




					<!-- orden y limpieza -->
					<div class="flex header items-center justify-center border-gray border w-full p-2 mb-2">
						<span><?= $secciones[5] ?></span>
					</div>

					<div id="limpieza_wrapper" class="w-full grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
						<!-- Column 1 -->
						<div class="col-1  w-full flex flex-col border-gray border-l">
							<div class="flex items-center header border-b border-r border-gray justify-between w-full">
								<div class="col border-gray border-r w-[70%] ">
									<p class="w-full text-center py-2">ITEM</p>
								</div>
								<div class="col border-gray border-r w-[15%] ">
									<p class="w-full text-center py-2">SI</p>
								</div>
								<div class="col border-gray w-[15%] ">
									<p class="w-full text-center py-2">NO</p>
								</div>
							</div>
							<div id="col-limpieza-1" class="limpieza-list"></div>
						</div>

						<!-- Column 2 -->
						<div class="col-2 w-full flex flex-col border-gray border-l">
							<div class="flex items-center header border-b border-r border-gray justify-between w-full">
								<div class="col border-gray border-r w-[70%] ">
									<p class="w-full text-center py-2">ITEM</p>
								</div>
								<div class="col border-gray border-r w-[15%] ">
									<p class="w-full text-center py-2">SI</p>
								</div>
								<div class="col border-gray  w-[15%] ">
									<p class="w-full text-center py-2">NO</p>
								</div>
							</div>
							<div id="col-limpieza-2" class="limpieza-list"></div>
						</div>

					</div>



					<div class="flex header items-center justify-center border-gray border w-full p-2 mb-2">
						<span><?= $secciones[6] ?></span>
					</div>

					<!-- observacion reporte -->
					<div class="w-full text-white flex flex-col items-center justify-between gap-y-2 mb-6">
						<textarea name="observacion_reporte" id="" rows="4" placeholder="Escribir observación ..." class="w-full border border-gray p-2 text-gray outline-none resize-none " ></textarea>
					</div>



					<div class="flex flex-col items-center gap-y-2 justify-center w-1/3 ">
						
						<img id="produccion_firma" class="h-44 w-full border-2 border-grayMid bg-grayLight">  

						<p id="produccion_nombre" class="font-bold"><?= session()->get('user')['name'] . ' ' . session()->get('user')['last_name'] ?></p>
						<!-- <p><?= session()->get('user')['puesto'] ?></p> -->
						<p>SUPERVISOR / LIDER DE PRODUCCION</p>

						<button data-area="produccionId" data-user-id="<?= session()->get('user')['id'] ?>" data-field="firma_produccion" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">FIRMAR</button>
		

					</div>


					<div class="form-row-submit ">
						<button class="modal-btn--submit" type="submit">GUARDAR</button>
					</div>



				</div>

			</div>


			<input type="hidden" id="produccionId" name="produccionId" required>
			<input type="hidden" id="firma_produccion" name="firma_produccion">
			<input type="hidden" id="fecha_firma_produccion" name="fecha_firma_produccion">

		</form>

	</div>


<!-- Modal success -->
<div id="modal_success" class="modal modal-md ">
	<div class="modal-content">
		<div class="modal-body">
			

			<h3 class="text-title text-4xl text-center py-32 ">¡Enviado con éxito!</h3>

			<div class="flex w-full justify-center  ">
				<a href="<?= base_url('produccion/ordenes_registros/') . $ordenfab['num_orden'] ?>" class="btn btn-md btn--primary">
					ACEPTAR					
				</a>

			</div>
		</div>

	</div>
</div>



<script>
Service.setLoading();

setInterval(() => {
	Service.exec('get', `/session_check`)
	.then( r => {
			if (!r.success) {
					window.location.href = `${root}`;
			}
	});
}, 2 * 60 * 1000); // Check every 3 minutes


	const submitFirma = (e) => {

    let field = e.target.getAttribute('data-field');
    let id = e.target.getAttribute('data-id');
    let userId = e.target.getAttribute('data-user-id');
    let area = e.target.getAttribute('data-area');

		// console.log(field, id); return;

		e.target.disabled = true;

		const formData = new FormData();
		formData.append('field', field);
		// formData.append('mantId', id);

		if(userId !== undefined) {
			formData.append('userId', userId);
			formData.append('area', area);
		}

		Service.show('.loading');

		Service.exec('post', `${root}/add_firma_reporte_diario`, formData_header, formData)
		.then( r => {
			// return
			if(r.success) {
				Service.hide('.loading');
				e.target.disabled = false;

				document.querySelector("#produccionId").value = r.produccionId;
				document.querySelector("#fecha_firma_produccion").value = r.fecha_firma;
				document.querySelector("#firma_produccion").value = r.firma;

				if ( r.signature ) {
					document.querySelector('#produccion_firma').src = `${root}/files/download?path=${r.signature}`; 
				} else {
					document.querySelector('#produccion_firma').src = `${root}/img/no_img_alt.png`; 
				}

			}
		});
  }

  const allBtnFirmar = document.querySelectorAll('.btn_firmar');
  allBtnFirmar?.forEach( btn => {
		btn.addEventListener('click', submitFirma);
	});


	const submitForm = (e) => {
		e.preventDefault();
		Service.stopSubmit(e.target, true);
		Service.show('.loading');

		const formData = new FormData(e.target);

		// let id = e.target.dataset.id;
		// let modalId = "modal_create";

		// if (id) {
		// 	formData.append('id', id);
		// 	modalId = "modal_edit";
		// }


		let acumulado;

		const acumuladas = document.querySelector('#piezas_acumuladas');
		const producidas = document.querySelector('input[name="piezas_producidas"]');
		const muestras = document.querySelector('input[name="muestras"]');

		acumulado = parseInt(producidas.value) + parseInt(muestras.value) + parseInt(acumuladas.value);

	
		formData.append('piezas_acumuladas', acumulado);

    Service.exec('post', `produccion/registro_diario`, formData_header, formData)
    .then( r => {
				
			if(r.success){
				
				Service.stopSubmit(e.target, false);

				setTimeout(() => {
					Service.hide('.loading');
					Modal.init("modal_success").open();
					// e.target.reset();
				}, 500)


			}


      // if(r.success){

			// 	Service.stopSubmit(e.target, false);

			// 	window.location.href = `${root}/produccion/ordenes_registros/<?= esc($ordenfab['num_orden'])?>`;

			// }
    });
	}

	const form_registro_diario = document.querySelector('#form_registro_diario');
	form_registro_diario.addEventListener('submit', submitForm);



const limpieza_items = <?= $limpieza ?>;


function splitIntoColumns1(itemsPerColumn) {
  const col1 = limpieza_items.slice(0, itemsPerColumn);  // First column with specified items
  const col2 = limpieza_items.slice(itemsPerColumn);  // Second column with remaining items
  return [col1, col2];
}

function renderLimpieza() {
  const [col1, col2] = splitIntoColumns1(4);  // Split into two columns, first with 4 items, second with the rest

  const col1Container = document.getElementById('col-limpieza-1');
  const col2Container = document.getElementById('col-limpieza-2');

  let globalIndex = 0;  // Initialize global index for limpieza items

  function renderColumn(column, container) {
    column.forEach((item) => {
      const row = document.createElement('div');
      row.classList.add('flex', 'items-center', 'border-b', 'border-r', 'border-gray', 'justify-between', 'w-full');

      // Use globalIndex to ensure unique names for each input field
      row.innerHTML = `
        <div class="col border-gray border-r w-[70%] py-1 px-2">
          <input type="text" value="${item.name}" readonly>
          <input type="hidden" name="limpieza[${globalIndex}][id]" value="${item.id}">
        </div>

        <!-- Hidden Input for 'cumple' Default Value -->
        <input type="hidden" name="limpieza[${globalIndex}][cumple]" value="no">

        <div class="col flex justify-center border-gray border-r w-[15%]">
          <div class="py-1">
            <label class="label--check">
              <input type="radio" name="limpieza[${globalIndex}][cumple]" value="si" class="checkbox_inspec hidden">
              <span class="checkbox-label-rep"><i class="fas fa-check"></i></span>
            </label>
          </div>
        </div>

        <div class="col flex justify-center border-gray w-[15%]">
          <div class="py-1">
            <label class="label--check">
              <input type="radio" name="limpieza[${globalIndex}][cumple]" value="no" class="checkbox_inspec hidden">
              <span class="checkbox-label-rep"><i class="fas fa-check"></i></span>
            </label>
          </div>
        </div>
      `;

      container.appendChild(row);

      globalIndex++; // Increment global index after each limpieza item
    });
  }

  col1Container.innerHTML = '';
  col2Container.innerHTML = '';

  renderColumn(col1, col1Container);
  renderColumn(col2, col2Container);
}

renderLimpieza();








const componentes = <?= $inspeccion ?>;

function splitIntoColumns(itemsPerColumn) {
  const col1 = componentes.slice(0, itemsPerColumn);
  const col2 = componentes.slice(itemsPerColumn, itemsPerColumn * 2);
  const col3 = componentes.slice(itemsPerColumn * 2);
  
  return [col1, col2, col3];
}

function renderComponentes() {
  const [col1, col2, col3] = splitIntoColumns(6); 

  const col1Container = document.getElementById('col-inspec-1');
  const col2Container = document.getElementById('col-inspec-2');
  const col3Container = document.getElementById('col-inspec-3');

  let globalIndex = 0;  // Initialize global index

  function renderColumn(column, container, option_units) {
    column.forEach((componente) => {
			let options = '';
			option_units.forEach(option => {
				options += `<option value="${option}">${option}</option>`
			});

      const row = document.createElement('div');
      row.classList.add('flex', 'items-center', 'border-b', 'border-r', 'border-gray', 'justify-between', 'w-full');

      // Use globalIndex to ensure unique names for each input field
      row.innerHTML = `
        <div class="col border-gray border-r w-[50%] py-1 px-2">
          <input type="text" class="text-center" value="${componente.name}" readonly>
          <input type="hidden" name="componentes[${globalIndex}][id]" value="${componente.id}">
        </div>

        <!-- Hidden Input for 'cumple' Default Value -->
        <input type="hidden" name="componentes[${globalIndex}][cumple]" value="no">

        <div class="col flex justify-center border-gray border-r w-[10%]">
          <div class="py-1">
            <label class="label--check">
              <input type="radio" name="componentes[${globalIndex}][cumple]" value="si" class="checkbox_inspec hidden">
              <span class="checkbox-label-rep"><i class="fas fa-check"></i></span>
            </label>
          </div>
        </div>

        <div class="col flex justify-center border-gray border-r w-[10%]">
          <div class="py-1">
            <label class="label--check">
              <input type="radio" name="componentes[${globalIndex}][cumple]" value="no" class="checkbox_inspec hidden">
              <span class="checkbox-label-rep"><i class="fas fa-check"></i></span>
            </label>
          </div>
        </div>

        <div class="col border-gray border-r w-[15%] py-1 px-2">
          <input type="number" class="to_uppercase text-center" name="componentes[${globalIndex}][merma]" value="" min="0">
        </div>

        <div class="col border-gray w-[15%] px-2">
          <select class="w-full text-sm" name="componentes[${globalIndex}][unidad]">
					${options}
          </select>
        </div>
      `;

      container.appendChild(row);

      globalIndex++; // Increment global index after each component
    });
  }

  col1Container.innerHTML = '';
  col2Container.innerHTML = '';
  col3Container.innerHTML = '';

  renderColumn(col1, col1Container, ['PZS']);
  renderColumn(col2, col2Container, ['PZS']);
  renderColumn(col3, col3Container, ['Gramos']);
}

renderComponentes();



const calculateCajasTurno = () => {
	const piezasProducidas = parseFloat(document.querySelector('input[name="piezas_producidas"]').value);
	const numPiezas = parseFloat(document.getElementById('num_piezas').value);
	
	if (!isNaN(piezasProducidas) && !isNaN(numPiezas) && numPiezas > 0) {
		const result = Math.ceil(piezasProducidas / numPiezas);
		document.querySelector('input[name="cajas_turno"]').value = result;
	} else {
		console.log("Invalid inputs");
	}
}
document.querySelector('input[name="piezas_producidas"]').addEventListener('input', calculateCajasTurno);




const incidencias = <?= json_encode($incidencias); ?>;

const incidenciasContainer = document.getElementById('incidencias-container');
const addInciBtn = document.getElementById('add-incidencia');

document.addEventListener("DOMContentLoaded", () => {
  addIncidenciaRow();
});

addInciBtn.addEventListener('click', () => {
  addIncidenciaRow();
});


const addIncidenciaRow = () => {

	let options = '<option value="">Seleccionar ... </option>';
		incidencias.forEach(option => {
		options += `<option value="${option.id}">${option.incidencia}</option>`
	});

  const row = document.createElement('div');
  row.className = 'operario-row flex w-full border-gray border-r relative';

  row.innerHTML = `
	  <div class="col border-l border-b w-[55%] relative">
      <div class="flex items-center p-2 relative">

				<select class="w-full text-sm py-1 px-2" name="incidencia_id[]">
				${options}
				</select>

      </div>
    </div>
    <div class="col border-l border-b w-[15%]">
      <div class="p-2">	
        <div class="flex items-center space-x-2">
          <select class="hora_inicio w-full py-1 h-8" name="hora_inicio[]">
            ${Array.from({ length: 24 }, (_, i) => `<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`).join('')}
          </select>
          <select class="minutos_inicio w-full py-1 h-8" name="minutos_inicio[]">
						${Array.from({ length: 60 }, (_, i) => {
								const min = i;
								return `<option value="${min.toString().padStart(2, '0')}">
														${min.toString().padStart(2, '0')}
												</option>`;
						}).join('')}
					</select>

        </div>
      </div>
    </div>
    <div class="col border-l border-b w-[15%]">
      <div class="p-2">
				<input type="text" class="hora_fin input-gray-light text-center w-full py-1 h-8" name="hora_fin[]" placeholder="HH:mm" readonly>
      </div>
    </div>
    <div class="col border-l border-b w-[15%]">
      <div class="flex gap-x-4 p-2">
				<div class="flex flex-1 items-center gap-x-2">
					<input type="hidden" class="tiempo_paro" name="tiempo_paro[]">
					<select class="tiempo_paro_hora w-full py-1 h-8">
						${Array.from({ length: 9 }, (_, i) => `<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`).join('')}
					</select>
					<select class="tiempo_paro_minuto w-full py-1 h-8">
						${Array.from({ length: 60 }, (_, i) => {
								const min = i;
								return `<option value="${min.toString().padStart(2, '0')}">
														${min.toString().padStart(2, '0')}
												</option>`;
						}).join('')}
					</select>
				</div>
				<button type="button" class="delete-row-btn text-red">
      		<i class="fas fa-trash"></i>
    		</button>
      </div>
    </div>
  `;

  incidenciasContainer.appendChild(row);


	const deleteBtn = row.querySelector('.delete-row-btn');
  deleteBtn.addEventListener('click', () => {
    row.remove();
  });

  const horaInicioSelect = row.querySelector('.hora_inicio');
  const minutosInicioSelect = row.querySelector('.minutos_inicio');

  const tiempoParoInput = row.querySelector('.tiempo_paro');
  const tiempoParoHoraSelect = row.querySelector('.tiempo_paro_hora');
  const tiempoParoMinutoSelect = row.querySelector('.tiempo_paro_minuto');

  const horaFinInput = row.querySelector('.hora_fin');

	tiempoParoHoraSelect.addEventListener('change', () => updateHoraFin(horaInicioSelect, minutosInicioSelect, tiempoParoHoraSelect, tiempoParoMinutoSelect, tiempoParoInput, horaFinInput));

  tiempoParoMinutoSelect.addEventListener('change', () => updateHoraFin(horaInicioSelect, minutosInicioSelect, tiempoParoHoraSelect, tiempoParoMinutoSelect, tiempoParoInput, horaFinInput));
};

const updateHoraFin = (
  horaInicioSelect,
  minutosInicioSelect,
  tiempoParoHoraSelect,
  tiempoParoMinutoSelect,
  tiempoParoInput,
  horaFinInput
) => {
  const horaInicio = moment(
    `${horaInicioSelect.value}:${minutosInicioSelect.value}`,
    'HH:mm'
  );

	const tiempo_paro = `${tiempoParoHoraSelect.value}:${tiempoParoMinutoSelect.value}`;

  const horasParo = parseInt(tiempoParoHoraSelect.value, 10) || 0;
  const minutosParo = parseInt(tiempoParoMinutoSelect.value, 10) || 0;

  const horaFin = horaInicio.clone().add(horasParo, 'hours').add(minutosParo, 'minutes');

  horaFinInput.value = horaFin.format('HH:mm');
  tiempoParoInput.value = tiempo_paro;
};





const operarios = <?= json_encode($operarios); ?>;
const operariosContainer = document.getElementById('operarios-container');
const addBtn = document.getElementById('add-operario');

document.addEventListener("DOMContentLoaded", () => {
  addOperarioRow(); // Add the first operario row when the page is loaded
});

addBtn.addEventListener('click', () => {
  addOperarioRow(); // Add new operario row when button is clicked
});

function addOperarioRow() {
  const row = document.createElement('div');
  row.className = 'operario-row flex w-full border-gray border-r relative';

  row.innerHTML = `
    <div class="col border-l border-b w-[70%] relative">
      <div class="flex items-center p-2 relative">
        <input type="text" class="operario-name-input input-gray-light w-full text-gray py-2 h-8" placeholder="Buscar operario...">
        <input type="hidden" name="operarios[id][]" class="operario-id input-gray-light">
        <ul class="autocomplete-list w-2/3 absolute z-50 bg-white border border-grayMid top-12 left-2 max-h-40 overflow-auto hidden"></ul>
      </div>
    </div>
    <div class="col border-l border-b w-[30%]">
      <div class="flex gap-x-4 p-2">
        <input type="text" class="puesto input-gray-light text-center w-full py-1 h-8" name="operarios[puesto][]" placeholder="Posición">
				<button type="button" class="delete-row-btn text-red">
      		<i class="fas fa-trash"></i>
    		</button>
      </div>
    </div>
  `;

  operariosContainer.appendChild(row);

  initializeAutocomplete(row.querySelector('.operario-name-input'), row.querySelector('.operario-id'), operarios);

  const deleteBtn = row.querySelector('.delete-row-btn');
  deleteBtn.addEventListener('click', () => {
    row.remove();
  });
}

const initializeAutocomplete = (inputEl, hiddenInput, operarios) => {
  const turnoSelect = document.querySelector('#turno');
  let filteredOperarios = operarios;

  if (turnoSelect && turnoSelect.value.trim() !== "") {
    const selectedTurno = turnoSelect.value.trim();
    filteredOperarios = operarios.filter(op => op.turno === selectedTurno);
  }

  const listEl = inputEl.parentElement.querySelector(".autocomplete-list");

  inputEl.addEventListener("input", () => {
    const search = inputEl.value.toLowerCase();
    listEl.innerHTML = "";

    const matches = filteredOperarios.filter(op => 
      (`${op.name} ${op.last_name}`).toLowerCase().includes(search)
    );

    if (matches.length === 0 || search.length < 2) {
      listEl.classList.add("hidden");
      return;
    }

    matches.forEach(op => {
      const li = document.createElement("li");
      li.className = "px-4 py-2 hover:bg-title-l hover:text-white cursor-pointer";
      li.textContent = `${op.name} ${op.last_name}`;
      li.addEventListener("click", () => {
        inputEl.value = `${op.name} ${op.last_name}`;
        hiddenInput.value = op.id;
        listEl.classList.add("hidden");
      });
      listEl.appendChild(li);
    });

    listEl.classList.remove("hidden");
  });

  // Hide list when clicking outside
  document.addEventListener("click", (e) => {
    if (!inputEl.parentElement.contains(e.target)) {
      listEl.classList.add("hidden");
    }
  });
};




document.querySelectorAll('input[type="text"]').forEach(input => {
  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault(); // Don't submit the form
    }
  });
});



const pages = document.querySelectorAll('.page__container');
// const totalPages = pages.length;
const totalPages = <?= esc($formato['paginas']) ?>

pages.forEach((page, index) => {
	const numElem = page.querySelector('.num-page');
	if (numElem) {
		numElem.textContent = `${index + 1} de ${totalPages}`;
	}
});



 

const allMomentDate = document.querySelectorAll('.moment-date');
allMomentDate?.forEach( p => {
	p.innerText = formatToMonthYear(p.innerText);
});
			


const allInputToUpper = document.querySelectorAll('.to_uppercase');
allInputToUpper?.forEach( input => {
	input.addEventListener('input', e => {
		input.value = e.target.value.toUpperCase();
	});
});







</script>
</body>
</html>
