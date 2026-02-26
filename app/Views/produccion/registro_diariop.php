<?php echo view('_partials/header'); ?>
  <link rel="stylesheet" href="<?= load_asset('_partials/cotizar.css') ?>">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/FormHelper.min.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>

  <link rel="stylesheet" href="<?= load_asset('_partials/inspeccion.css') ?>">
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
							<input type="text" class="to_uppercase h-8 text-center" value="<?= $reporte['name'] . ' ' . $reporte['last_name']?>" readonly>
						</div>

						<div class="col header border-t border-l border-b w-[10%] ">
							<span>TURNO</span>
						</div>
						<div class="col border-t border-l border-b w-[25%] px-2">
							<select class="w-full py-1 h-8 text-center" readonly>
								<?php foreach ($turnos as $turno): ?>
										<option value="<?= esc($turno['id']) ?>" data-value="<?= esc($turno['label']) ?>" <?= $reporte['turnoId'] == $turno['id'] ? 'selected' : 'hidden' ?> >
												<?= esc($turno['label']) ?>
										</option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="col header border-t border-l border-b w-[10%] ">
							<span>FECHA</span>
						</div>
						<div class="col border-t border-l border-b w-[15%] px-2">
							<input type="text" id="fecha_creacion" class="to-date text-gray py-1 px-4 uppercase text-center" data-name="created_at" placeholder="dd-mm-yyyy" inputmode="numeric">

							<!-- <div class="text-gray py-1 px-4 uppercase"><?= dateToString($reporte['created_at']); ?></div>  -->
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
								<option value="CAPSULAS" <?= $reporte['linea'] == "CAPSULAS" ? 'selected' : ''; ?>>CAPSULAS</option>
								<option value="POLVOS" <?= $reporte['linea'] == "POLVOS" ? 'selected' : ''; ?>>POLVOS</option>
								<option value="LIQUIDOS" <?= $reporte['linea'] == "LIQUIDOS" ? 'selected' : ''; ?>>LIQUIDOS</option>
							</select>

							<!-- <input type="text" name="linea" class="to_uppercase h-8 text-center" required value="<?= $reporte['linea'] ?>"> -->
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
							<input type="text" name="hora_inicio_registro" class="to_uppercase h-8 text-center" value="<?= $reporte['hora_inicio_registro']; ?>" readonly>
						</div>
						<div class="col border-l border-b w-[10%]">
							<input type="text" name="hora_fin_registro" class="to_uppercase h-8 text-center" value="<?= $reporte['hora_fin_registro']; ?>">
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
							<input type="text" name="cajas_turno" class="to_uppercase h-8 text-center" readonly value="<?= $reporte['cajas_turno'] ?>">
						</div>
						<div class="col border-l border-b w-[12%]">
							<input type="text" name="piezas_producidas" class="to_uppercase h-8 text-center" value="<?= $reporte['piezas_producidas'] ?>">
						</div>
						<div class="col border-l border-b w-[12%]">
							<input type="text" name="muestras" class="to_uppercase h-8 text-center" value="<?= $reporte['muestras'] ?>">
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
						<div class="col border-l border-b w-[20%]">
							<span># COLECTIVA DE CIERRE</span>
						</div>
						<div class="col border-l border-b w-[22%]">
							<span>ESTATUS DE LA FABRICACION</span>
						</div>
					</div>

					<div class="general__row--rep border-r mb-2">
						<div class="col border-l border-b w-[8%]">
							<input type="text" name="batch_inicial" class="to_uppercase h-8 text-center" value="<?= esc($reporte['batch_inicial'])?>">
						</div>
						<div class="col border-l border-b w-[8%]">
							<input type="text" name="batch_final" class="to_uppercase h-8 text-center" value="<?= esc($reporte['batch_final'])?>">
						</div>
						<div class="col border-l border-b w-[12%]">
							<input type="text" name="cantidad_mezcla" class="to_uppercase h-8 text-center" value="<?= esc($reporte['cantidad_mezcla'])?>">
						</div>
						<div class="col border-l border-b w-[30%]">
							<input type="text" name="lote_mezcla" class="to_uppercase h-8 text-center" value="<?= esc($reporte['lote_mezcla'])?>">
						</div>

						<div class="col border-l border-b w-[20%]">
							<input type="text" name="colectiva" class="to_uppercase h-8 text-center" value="<?= esc($reporte['colectiva'])?>">
						</div>
						<div class="col border-l border-b w-[22%]">
							<select class="w-full py-1 h-8 text-center" name="status_fabricacion">
								<option value="" disabled>Seleccionar...</option>
									<option value="EN PROCESO" <?= ($reporte['status_fabricacion'] == 'EN PROCESO') ? 'selected' : ''; ?>>EN PROCESO</option>
									<option value="FINALIZADO" <?= ($reporte['status_fabricacion'] == 'FINALIZADO') ? 'selected' : ''; ?>>FINALIZADO</option>
							</select>
						</div>
					</div>


					<div class="w-full text-white flex flex-col items-center justify-between gap-y-2 mb-6">
						<textarea name="observacion_produc" id="" rows="4" placeholder="Escribir observación ..." class="w-full border border-gray p-2 text-gray outline-none resize-none " ><?= esc($reporte['observacion_produc'])?></textarea>
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
							<div class="col border-gray border-l border-b w-[40%] ">
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
							<div class="col border-gray border-l border-b w-[15%] ">
								<p class="w-full text-center py-2">HH : MM</p>
							</div>
						</div>

						<!-- insert incidencias rows -->
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
						<textarea name="observacion_reporte" id="" rows="4" placeholder="Escribir observación ..." class="w-full border border-gray p-2 text-gray outline-none resize-none " ><?= esc($reporte['observacion_reporte']) ?></textarea>
					</div>



					<div class="flex flex-col items-center gap-y-2 justify-center w-1/3 ">
						
						<img id="produccion_firma" src="<?= base_url() . 'files/download?path=' . $reporte['signature']?>" class="h-44 w-full border-2 border-grayMid bg-grayLight">  

						
						<p id="produccion_nombre" class="font-bold"><?= $reporte['name'] . ' ' . $reporte['last_name']?></p>
						<p><?= session()->get('user')['puesto'] ?></p>

<!-- 
						<button data-area="produccionId" data-user-id="<?= $reporte['produccionId']?>" data-field="firma_produccion" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">FIRMAR</button> -->
		

					</div>


					<div class="form-row-submit ">
						<button class="modal-btn--submit" type="submit">ACTUALIZAR</button>
					</div>



				</div>

			</div>


			<input type="hidden" name="reporteId" value="<?= $registroId ?>" required>
			<input type="hidden" id="produccionId" name="produccionId" required>
			<input type="hidden" id="firma_produccion" name="firma_produccion">
			<input type="hidden" id="fecha_firma_produccion" name="fecha_firma_produccion">

		</form>

	</div>



<script>
Service.setLoading();
FormHelper.toDate();
 
  const loadFecha = () => {
		const fechaInput = document.querySelector('input[data-name="created_at"]');
		fechaInput.value = dateToStringAlt("<?= $reporte['created_at']; ?>");

		const dateInputs = document.querySelectorAll('input.to-date');
		dateInputs.forEach(input => {
				const event = new Event('input', { bubbles: true });
				input.dispatchEvent(event);
		});
	
	}

	loadFecha();



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

		// console.log(Object.fromEntries(formData));

		// return;


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

    Service.exec('post', `produccion/registro_update`, formData_header, formData)
    .then( r => {

			Service.hide('.loading');

			// return;

      if(r.success){
				Service.stopSubmit(e.target, false);

				window.location.href = `${root}/produccion/ordenes_registros/<?= esc($ordenfab['num_orden'])?>`;
			}
    });
	}

	const form_registro_diario = document.querySelector('#form_registro_diario');
	form_registro_diario.addEventListener('submit', submitForm);






const limpieza_items = <?= $limpieza ?>;
const limpData = <?= json_encode($limp_data) ?>;  // Assuming limp_data is passed as JSON from PHP

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

      // Find matching limp_data item for the current limpieza item by limpId
      const limpItem = limpData.find(limp => limp.limpId === item.id);

      // Default values for radio buttons (assume "no" is unchecked)
      let cumpleSiChecked = '';
      let cumpleNoChecked = '';

      if (limpItem) {
        // Set values based on limpItem data
        cumpleSiChecked = limpItem.cumple === 'si' ? 'checked' : '';
        cumpleNoChecked = limpItem.cumple === 'no' ? 'checked' : '';
      }

      // Use globalIndex to ensure unique names for each input field
      // Instead of item.id, use limpItem.id from limp_data to get the database ID
      row.innerHTML = `
        <div class="col border-gray border-r w-[70%] py-1 px-2">
          <input type="text" value="${item.name}" readonly>
          <!-- Use limpItem.id to set the correct ID from the reportes_prod_limp table -->
          <input type="hidden" name="limpieza[${globalIndex}][id]" value="${limpItem ? limpItem.id : ''}">
        </div>

        <!-- Hidden Input for 'cumple' Default Value -->
        <input type="hidden" name="limpieza[${globalIndex}][cumple]" value="no" class="cumple-value">

        <div class="col flex justify-center border-gray border-r w-[15%]">
          <div class="py-1">
            <label class="label--check">
              <input type="radio" name="limpieza[${globalIndex}][cumple]" value="si" class="checkbox_inspec hidden" ${cumpleSiChecked}>
              <span class="checkbox-label-rep"><i class="fas fa-check"></i></span>
            </label>
          </div>
        </div>

        <div class="col flex justify-center border-gray w-[15%]">
          <div class="py-1">
            <label class="label--check">
              <input type="radio" name="limpieza[${globalIndex}][cumple]" value="no" class="checkbox_inspec hidden" ${cumpleNoChecked}>
              <span class="checkbox-label-rep"><i class="fas fa-check"></i></span>
            </label>
          </div>
        </div>
      `;

      // Add the event listeners to update the hidden 'cumple' field when radio buttons are clicked
      const radios = row.querySelectorAll('input[name="limpieza[' + globalIndex + '][cumple]"]');
      radios.forEach(radio => {
        radio.addEventListener('change', function() {
          const hiddenCumple = row.querySelector('.cumple-value');
          hiddenCumple.value = this.value;  // Update the hidden input based on radio selection
        });
      });

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
const inspData = <?= json_encode($insp_data) ?>;  // Assuming insp_data is passed as JSON from PHP

function splitIntoColumns(itemsPerColumn) {
  const col1 = componentes.slice(0, itemsPerColumn);  // First column with specified items
  const col2 = componentes.slice(itemsPerColumn, itemsPerColumn * 2);  // Second column
  const col3 = componentes.slice(itemsPerColumn * 2);  // Third column

  return [col1, col2, col3];
}

function renderComponentes() {
  const [col1, col2, col3] = splitIntoColumns(6);  // Split into three columns

  const col1Container = document.getElementById('col-inspec-1');
  const col2Container = document.getElementById('col-inspec-2');
  const col3Container = document.getElementById('col-inspec-3');

  let globalIndex = 0;  // Initialize global index

  function renderColumn(column, container, option_units) {
    column.forEach((componente) => {

      const row = document.createElement('div');
      row.classList.add('flex', 'items-center', 'border-b', 'border-r', 'border-gray', 'justify-between', 'w-full');

      // Find matching insp_data item for the current componente
      const inspItem = inspData.find(item => item.itemId === componente.id); // Matching based on itemId

      // Default values for radio buttons and input fields
      let cumpleSiChecked = '';
      let cumpleNoChecked = '';
      let mermaValue = '';
      let unidadValue = "PZS";  // Default to "PZS"

      if (inspItem) {
        // Set values based on inspItem data
        cumpleSiChecked = inspItem.cumple === 'si' ? 'checked' : '';
        cumpleNoChecked = inspItem.cumple === 'no' ? 'checked' : '';
        mermaValue = inspItem.merma;
        unidadValue = inspItem.unidad;
      }

			let options = '';
			option_units.forEach(option => {
				options += `<option value="${option}" ${unidadValue === option ? 'selected' : ''} >${option}</option>`
			});


      // Use globalIndex to ensure unique names for each input field
      row.innerHTML = `
        <div class="col border-gray border-r w-[50%] py-1 px-2">
          <input type="text" class="text-center" value="${componente.name}" readonly>
          <!-- Hidden Input for correct 'id' from insp_data -->
          <input type="hidden" name="componentes[${globalIndex}][id]" value="${inspItem ? inspItem.id : ''}">
        </div>

        <!-- Hidden Input for 'cumple' Default Value -->
        <input type="hidden" name="componentes[${globalIndex}][cumple]" value="no">

        <div class="col flex justify-center border-gray border-r w-[10%]">
          <div class="py-1">
            <label class="label--check">
              <input type="radio" name="componentes[${globalIndex}][cumple]" value="si" class="checkbox_inspec hidden" ${cumpleSiChecked}>
              <span class="checkbox-label-rep"><i class="fas fa-check"></i></span>
            </label>
          </div>
        </div>

        <div class="col flex justify-center border-gray border-r w-[10%]">
          <div class="py-1">
            <label class="label--check">
              <input type="radio" name="componentes[${globalIndex}][cumple]" value="no" class="checkbox_inspec hidden" ${cumpleNoChecked}>
              <span class="checkbox-label-rep"><i class="fas fa-check"></i></span>
            </label>
          </div>
        </div>

        <div class="col border-gray border-r w-[15%] py-1 px-2">
          <input type="number" class="to_uppercase text-center" name="componentes[${globalIndex}][merma]" value="${mermaValue}" min="0">
        </div>

        <div class="col border-gray w-[15%] px-2">
          <select class="w-full text-sm" name="componentes[${globalIndex}][unidad]">
						${options}

          </select>
        </div>
      `;

      container.appendChild(row);

      globalIndex++; // Increment global index after each componente
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
const tiempoData = <?= json_encode($tiempo_data); ?>; // Assuming this array is passed from the backend
const incidenciasContainer = document.getElementById('incidencias-container');
const addInciBtn = document.getElementById('add-incidencia');

document.addEventListener("DOMContentLoaded", () => {
  // Render existing 'tiempo' data
  tiempoData.forEach(data => {
    renderIncidenciaRow(data);
  });
});

addInciBtn.addEventListener('click', () => {
  addIncidenciaRow(); // Add new incidencia row when button is clicked
});

const addIncidenciaRow = () => {
	let options = '<option value="">Seleccionar ... </option>';
		incidencias.forEach(option => {
		options += `<option value="${option.id}">${option.incidencia}</option>`
	});

  const row = document.createElement('div');
  row.className = 'operario-row flex w-full border-gray border-r relative';

  row.innerHTML = `
		<div class="col border-l border-b w-[40%] relative">
      <div class="flex items-center p-2 relative">

				<select class="w-full text-sm py-1 px-2" name="incidencia_id[]">
				${options}
				</select>
				<input type="hidden" name="incidente_record[]" value="0"> 

      </div>
    </div>

    <div class="col border-l border-b w-[15%]">
      <div class="p-2">	
        <div class="flex items-center space-x-2">
          <select class="hora_inicio w-full py-1 h-8" name="hora_inicio[]">
            ${Array.from({ length: 24 }, (_, i) => `<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`).join('')}
          </select>
          
          <select class="minutos_inicio w-full py-1 h-8" name="minutos_inicio[]">
            <option value="00">00</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
            <option value="50">50</option>
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
      <div class="p-2">
				<input type="text" class="tiempo_paro input-gray-light text-center w-full py-1 h-8" name="tiempo_paro[]" placeholder="HH:mm" readonly>
      </div>
    </div>
    <div class="col border-l border-b w-[15%]">
      <div class="flex gap-x-4 p-2">
				<div class="flex flex-1 items-center gap-x-2">
					
					<select class="tiempo_paro_hora w-full py-1 h-8">
						${Array.from({ length: 9 }, (_, i) => `<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`).join('')}
					</select>
					<select class="tiempo_paro_minuto w-full py-1 h-8">
						<option value="00">00</option>
						<option value="15">15</option>
						<option value="30">30</option>
						<option value="45">45</option>
					</select>
				</div>
				<button type="button" class="delete-row-btn text-red">
      		<i class="fas fa-trash"></i>
    		</button>
      </div>
    </div>
  `;

  incidenciasContainer.appendChild(row);

  // initAutocompleteIncid(row.querySelector('.incidencia-input'), row.querySelector('input[name="incidencia_id[]"]'), incidencias);

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

const renderIncidenciaRow = (data = {}) => {

  const row = document.createElement('div');
  row.className = 'operario-row flex w-full border-gray border-r relative';

  row.innerHTML = `
    <div class="col border-l border-b w-[40%] relative">
      <div class="flex items-center p-2 relative">
        <input type="text" class="incidencia-input input-gray-light w-full text-gray py-2 h-8" placeholder="Escribir incidencia ..." value="${data.incidId ? getIncidenciaById(data.incidId) : ''}">
        <input type="hidden" name="incidencia_id[]" class="incidencia-id" value="${data.id || ''}">
        <input type="hidden" name="incidente_record[]" class="incidente-record-id" value="${data.id || ''}"> <!-- New hidden field for record ID -->
        <ul class="autocomplete-list w-2/3 absolute z-50 bg-white border border-grayMid top-12 left-2 max-h-40 overflow-auto hidden"></ul>
      </div>
    </div>

    <div class="col border-l border-b w-[15%]">
      <div class="p-2">  
        <div class="flex items-center space-x-2">
          <select class="hora_inicio w-full py-1 h-8" name="hora_inicio[]">
            ${Array.from({ length: 24 }, (_, i) => `<option value="${i < 10 ? '0' + i : i}" ${data.hora_inicio && data.hora_inicio.startsWith(i < 10 ? '0' + i : i) ? 'selected' : ''}>${i < 10 ? '0' + i : i}</option>`).join('')}
          </select>
          
          <select class="minutos_inicio w-full py-1 h-8" name="minutos_inicio[]">
            ${['00', '10', '20', '30', '40', '50'].map(min => 
              `<option value="${min}" ${data.hora_inicio && data.hora_inicio.endsWith(`${min}:00`) ? 'selected' : ''}>${min}</option>`
            ).join('')}
          </select>
        </div>
      </div>
    </div>
    <div class="col border-l border-b w-[15%]">
      <div class="p-2">
        <input type="text" class="hora_fin input-gray-light text-center w-full py-1 h-8" name="hora_fin[]" placeholder="HH:mm" readonly value="${data.hora_fin.slice(0, 5) || ''}">
      </div>
    </div>
		<div class="col border-l border-b w-[15%]">
      <div class="p-2">
        <input type="text" class="tiempo_paro input-gray-light text-center w-full py-1 h-8" name="tiempo_paro[]" placeholder="HH:mm" readonly value="${data.tiempo_paro.slice(0, 5) || ''}">
      </div>
    </div>
    <div class="col border-l border-b w-[15%]">
      <div class="flex gap-x-4 p-2">
				<div class="flex flex-1 items-center gap-x-2">
					<select class="tiempo_paro_hora w-full py-1 h-8">
						${Array.from({ length: 9 }, (_, i) => `<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`).join('')}
					</select>
					<select class="tiempo_paro_minuto w-full py-1 h-8">
						<option value="00">00</option>
						<option value="15">15</option>
						<option value="30">30</option>
						<option value="45">45</option>
					</select>
				</div>
				<button type="button" class="delete-row-btn text-red">
      		<i class="fas fa-trash"></i>
    		</button>
      </div>
    </div>
  `;

  incidenciasContainer.appendChild(row);

  // initAutocompleteIncid(row.querySelector('.incidencia-input'), row.querySelector('.incidencia-id'), incidencias);

  const deleteBtn = row.querySelector('.delete-row-btn');
  deleteBtn.addEventListener('click', () => {
    const incidenciaId = row.querySelector('.incidencia-id').value;
    if (incidenciaId) {
      deleteIncidencia(incidenciaId, row); // Delete incidencia via Axios
    } else {
      row.remove(); // If no ID, just remove the row
    }
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

// Get the incidencia name by its ID
const getIncidenciaById = (id) => {
  const incidencia = incidencias.find(incid => incid.id === id);
  return incidencia ? incidencia.incidencia : ''; // Return the name if found, otherwise return an empty string
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




function deleteIncidencia(incidenciaId, row) {
	Service.show('.loading');
	Service.exec('delete', `/produccion/delete_incidencia/${incidenciaId}`)
	.then(r => {
		row.remove();
		Service.hide('.loading');
	});
}








const operarios = <?= json_encode($operarios); ?>; // Assuming you are passing this array from the backend
const personalData = <?= json_encode($personal_data); ?>; // Assuming this array is passed from the backend
const operariosContainer = document.getElementById('operarios-container');
const addBtn = document.getElementById('add-operario');

document.addEventListener("DOMContentLoaded", () => {
  // Render existing personal data (populate the fields)
  personalData.forEach(data => {
    addOperarioRow(data); // Pass the personal data to populate
  });

  // Ensure that all operarios are populated properly after the page reload
  operarios.forEach(operario => {

		const operarioElement = document.querySelector(`.operario-id[value="${operario.id}"]`);
    
    if (operarioElement) {
			const row = operarioElement.closest('.operario-row');
			if (row) {
				const nameInput = row.querySelector('.operario-name-input');
				nameInput.value = `${operario.name} ${operario.last_name}`;
				row.querySelector('.operario-id').value = operario.id;
				row.querySelector('.operario-record-id').value = operario.recordId || ''; // Update record ID if exists
			}
		}
  });
});

addBtn.addEventListener('click', () => {
  addOperarioRow(); // Add new operario row when button is clicked
});

function addOperarioRow(data = {}) {
  const row = document.createElement('div');
  row.className = 'operario-row flex w-full border-gray border-r relative';

  row.innerHTML = `
    <div class="col border-l border-b w-[70%] relative">
      <div class="flex items-center p-2 relative">
        <input type="text" class="operario-name-input input-gray-light w-full text-gray py-2 h-8" placeholder="Buscar operario..." value="${data.personalId ? getOperarioNameById(data.personalId) : ''}">
        <input type="hidden" name="operarios[id][]" class="operario-id input-gray-light" value="${data.id || ''}">
        <input type="hidden" name="operarios[record][]" class="operario-record-id" value="${data.id || ''}"> <!-- New hidden field for record ID -->
        <ul class="autocomplete-list w-2/3 absolute z-50 bg-white border border-grayMid top-12 left-2 max-h-40 overflow-auto hidden"></ul>
      </div>
    </div>
    <div class="col border-l border-b w-[30%]">
      <div class="flex gap-x-4 p-2">
        <input type="text" class="puesto input-gray-light text-center w-full py-1 h-8" name="operarios[puesto][]" placeholder="Posición" value="${data.puesto || ''}">
        <button type="button" class="delete-row-btn text-red">
          <i class="fas fa-trash"></i>
        </button>
      </div>
    </div>
  `;

  operariosContainer.appendChild(row);

  // Initialize autocomplete for the row
  initializeAutocomplete(row.querySelector('.operario-name-input'), row.querySelector('.operario-id'), operarios);

  // Event listener for delete button
  const deleteBtn = row.querySelector('.delete-row-btn');
  deleteBtn.addEventListener('click', () => {
    const operarioId = row.querySelector('.operario-id').value;
    if (operarioId) {
      deleteOperario(operarioId, row); // Delete operario via Axios
    } else {
      row.remove(); // If no ID, just remove the row
    }
  });
}

// Function to get the operario's name by their ID
function getOperarioNameById(id) {
  const operario = operarios.find(op => op.id === id);
  return operario ? `${operario.name} ${operario.last_name}` : '';
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

// Function to delete an operario from the backend
function deleteOperario(operarioId, row) {
  Service.show('.loading');
  Service.exec('delete', `/produccion/delete_operario/${operarioId}`)
    .then(r => {
      row.remove();
      Service.hide('.loading');
    });
}

















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



const renderRows = (data) => {  

}

  const loadRows = () => {
    // Service.hide('#row__empty');
    // tbody.innerHTML = Service.loader();

    Service.exec('get', `all_reporte/<?= esc($registroId) ?>`)
    .then(r => renderRows(r));  
  }

  loadRows();


</script>
</body>
</html>
