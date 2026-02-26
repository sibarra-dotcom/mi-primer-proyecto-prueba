
<?php echo view('_partials/header'); ?>
	<link rel="stylesheet" href="<?= base_url('_partials/inspeccion.css') ?>">


  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>
  <link rel="stylesheet" href="<?= load_asset('_partials/inspeccion.css') ?>">
	<script src="https://unpkg.com/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>


  <title><?= esc($title) ?></title>
</head>
<body class="relative min-h-screen">
  <div id="overlay"></div>  
  <div id="loadingOverlay"><div id="loadingSpinner"></div></div>  


  <div class=" relative h-full pb-16 flex flex-col gap-y-8 items-center justify-center font-titil ">

  	<?php echo view('dashboard/_partials/navbar'); ?>

    <div class="text-title w-full md:p-4 md:px-16 p-2 flex items-center ">
      <h2 class="text-center font-bold w-full text-2xl lg:text-3xl "><?= esc($title) ?></h2>
      <a href="<?= base_url('inspeccion/init/materias-primas') ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>


		<form id="form_inspeccion" method="post" class="pdf-container" enctype='multipart/form-data'>
			<?= csrf_field() ?>

			<!-- Pagina 1 -->
			<div class=" page__container">

				<!-- Datos Formato Inspeccion -->
				<div class="formato__section ">
					<div class="formato__col-1 ">
						<img src="<?= base_url('img/gibanibb_logo.png') ?>">
					</div>

					<div class="formato__col-2">
						<div class="cell border-l border-b ">
							<span>TÍTULO: </span>
							<p><?= esc($formato['titulo']) ?></p>
						</div>	
						<div class="cell border-l border-b ">
							<span>CLAVE: </span>
							<p><?= esc($formato['clave']) ?></p>
						</div>	
						<div class="cell border-l ">
							<span>VERSIÓN: </span>
							<p><?= esc($formato['version']) ?></p>
						</div>						
					</div>

					<div class="formato__col-3">
						<div class="cell--alt border-l border-b">
							<span>PÁGINA: </span>
							<p class="num-page"><?= esc($formato['paginas']) ?></p>
						</div>						
						<div class="cell--alt cell--mobile border-l border-b  ">
							<span>ÚLTIMA REVISIÓN: </span>
							<p class="moment-date"><?= esc($formato['revision']) ?></p>
						</div>						
						<div class="cell--alt cell--mobile border-l  ">
							<span>FECHA DE VIGENCIA: </span>
							<p class="moment-date"><?= esc($formato['vigencia']) ?></p>
						</div>						
					</div>
				</div>

				<!-- Datos Generales Inspeccion -->
				<div class="general__section">

					<div class="general__col header border-r border-t  ">
						<div class=" col w-full lg:w-[15%] border-l border-b">
							<span>LOTE INTERNO</span>
							<input type="text" data-field="LOTE INTERNO" class="text-gray h-8 lg:hidden ">
						</div>
						<div class=" col w-full lg:w-[52%] border-l border-b">
							<span>MATERIA PRIMA</span>
							<input type="text" data-field="MATERIA PRIMA" class="text-gray h-8 lg:hidden ">
						</div>
						<div class=" col w-full lg:w-[18%] border-l border-b">
							<span>CANTIDAD (Piezas o contenedores).</span>
							<input type="text" data-field="CANTIDAD (Piezas o contenedores)" class="text-gray h-8 lg:hidden ">
						</div>
						<div class=" col w-full lg:w-[15%] border-l border-b">
							<span>CANTIDAD TOTAL (Kg o L)</span>
							<input type="text" data-field="CANTIDAD TOTAL (Kg o L)" class="text-gray h-8 lg:hidden ">
						</div>
					</div>

					<div class="general__row border-r">
						<div class="col border-l border-b w-[15%]">
							<input type="text" data-field="LOTE INTERNO" class="h-8" >
						</div>
						<div class="col border-l border-b w-[52%]">
							<input type="text" data-field="MATERIA PRIMA" class="h-8">
						</div>
						<div class="col border-l border-b w-[18%]">
							<input type="text" data-field="CANTIDAD (Piezas o contenedores)" class="h-8">
						</div>
						<div class="col border-l border-b w-[15%]">
							<input type="text" data-field="CANTIDAD TOTAL (Kg o L)" class="h-8">
						</div>
					</div>


					<div class="general__col header border-r">
						<div class="col border-l border-b w-full lg:w-[15%]">
							<span>FECHA DE ARRIBO</span>
							<input type="text" data-field="FECHA DE ARRIBO" class="text-gray h-8 lg:hidden" value="<?= date('d-m-Y'); ?>">
						</div>
						<div class="col border-l border-b w-full lg:w-[28%]">
							<span>LOTE EXTERNO</span>
							<input type="text" data-field="LOTE EXTERNO" class="text-gray h-8 lg:hidden">
						</div>
						<div class="col border-l border-b w-full lg:w-[42%]">
							<span>PROVEEDOR</span>
							<input type="text" data-field="PROVEEDOR" class="text-gray h-8 lg:hidden">
						</div>
						<div class="col border-l border-b w-full lg:w-[15%]">
							<span>CADUCIDAD</span>
							<input type="text" data-field="CADUCIDAD" class="text-gray h-8 lg:hidden">
						</div>
					</div>

					<div class="general__row border-r">
						<div class="col border-l border-b w-[15%]">
							<input type="text" data-field="FECHA DE ARRIBO" class="h-8" value="<?= date('d-m-Y'); ?>">
						</div>
						<div class="col border-l border-b w-[28%]">
							<input type="text" data-field="LOTE EXTERNO" class="h-8">
						</div>
						<div class="col border-l border-b w-[42%]">
							<input type="text" data-field="PROVEEDOR" class="h-8">
						</div>
						<div class="col border-l border-b w-[15%]">
							<input type="text" data-field="CADUCIDAD" class="h-8">
						</div>
					</div>


					<div class="general__col border-r ">
						<div class="col header border-l border-b w-full lg:w-[25%]">
							<span class="cell--mobile">NOMBRE DEL TRANSPORTISTA</span>
							<input type="text" data-field="NUMERO DE PLACA" class="mobile-input lg:hidden h-8 text-gray">
						</div>

						<div class="col-hidden border-l border-b w-[40%] px-2">
							<input type="text" data-field="NOMBRE DE TRANSPORTISTA" class="desktop-input h-8">
						</div>

						<div class="col header border-l border-b w-full lg:w-[20%] ">
							<span class="cell--mobile">NÚMERO DE PLACA</span>
							<input type="text" data-field="NUMERO DE PLACA" class="mobile-input lg:hidden h-8 text-gray">
						</div>

						<div class="col-hidden border-l border-b w-[15%] px-2">
							<input type="text" data-field="NUMERO DE PLACA" class="desktop-input h-8">
						</div>
					</div>

				</div>

				<!-- Datos Secciones Inspeccion -->
				<div class="list__section">

					<div class="w-full flex flex-col border-t  border-gray ">

						<?php foreach($secciones as $title => $groupedItems): ?>
						<div class="list__row header-b border-r">
							<div class="col border-l border-b w-[65%] lg:w-[50%]"><?= esc($title) ?></div>
							<div class="col border-l border-b w-[10%]">SI CUMPLE</div>
							<div class="col border-l border-b w-[10%]">NO CUMPLE</div>
							<div class="col border-l border-b w-[15%] lg:w-[30%]">
								<span>OBSERVACIONES</span>
								<button type="button" data-section-title="<?= esc($title) ?>" class=" btn_na absolute -bottom-10 right-4 bg-icon text-white rounded-lg py-1 px-3 text-lg">N.A.</button>
								<!-- <button type="button" data-id="<?= esc($groupedItems[0]['id']) ?>" data-section="<?= esc($title) ?>" data-modal="modal_comm_unico" class="btn_open_modal absolute -bottom-12 right-4 border-gray text-icon p-1 text-4xl"><i class="fas fa-comment"></i></button> -->
							</div>

						</div>

					
							<?php foreach($groupedItems as $item): ?>
								<div class="list__row border-r">
									<div class="item border-l border-b w-[65%] lg:w-[50%]"><?= esc($item['item_number']) ?> <?= esc($item['description']) ?></div>

									<div class="item--alt border-l border-b w-[10%]">
										<label class="label--check">
											<input type="radio" data-id="<?= esc($item['id']) ?>" name="items[<?= esc($item['id']) ?>][check]" data-value="si" class="checkbox_inspec hidden">
											<span class="checkbox-label-si"><i class="fas fa-check "></i></span>
										</label>
									</div>

									<div class="item--alt border-l border-b w-[10%]">
										<label class="label--check">
											<input type="radio" data-id="<?= esc($item['id']) ?>" name="items[<?= esc($item['id']) ?>][check]" data-value="no" class="checkbox_inspec hidden" >	
											<span class="checkbox-label-no"><i class="fas fa-x "></i></span>
										</label>
									</div>

									<div class="item--alt border-l border-b py-1 px-3 w-[15%] lg:w-[30%]">
										<input type="text" data-section-title="<?= esc($title) ?>" name="items[<?= esc($item['id']) ?>][observacion]" class="bg-white focus:bg-grayLight " >
										<input type="hidden" name="items[<?= esc($item['id']) ?>][aprobado]" >
										<input type="hidden" name="items[<?= esc($item['id']) ?>][itemId]" value="<?= esc($item['id']) ?>">
									</div>

								</div>
							<?php endforeach; ?>

						<?php endforeach; ?>

					</div>
				</div>

			</div>


			<!-- Pagina 2 -->
			<div class=" page__container">

				<!-- Datos Formato Inspeccion -->
				<div class="formato__section ">
					<div class="formato__col-1 ">
						<img src="<?= base_url('img/gibanibb_logo.png') ?>">
					</div>

					<div class="formato__col-2">
						<div class="cell border-l border-b ">
							<span>TÍTULO: </span>
							<p><?= esc($formato['titulo']) ?></p>
						</div>	
						<div class="cell border-l border-b ">
							<span>CLAVE: </span>
							<p><?= esc($formato['clave']) ?></p>
						</div>	
						<div class="cell border-l ">
							<span>VERSIÓN: </span>
							<p><?= esc($formato['version']) ?></p>
						</div>						
					</div>

					<div class="formato__col-3">
						<div class="cell--alt border-l border-b">
							<span>PÁGINA: </span>
							<p class="num-page"><?= esc($formato['paginas']) ?></p>
						</div>						
						<div class="cell--alt cell--mobile border-l border-b  ">
							<span>ÚLTIMA REVISIÓN: </span>
							<p class="moment-date"><?= esc($formato['revision']) ?></p>
						</div>						
						<div class="cell--alt cell--mobile border-l  ">
							<span>FECHA DE VIGENCIA: </span>
							<p class="moment-date"><?= esc($formato['vigencia']) ?></p>
						</div>						
					</div>
				</div>

				<!-- Datos Etiqueta Inspeccion -->
				<div class="etiqueta__section">

					<div class="file__section border-t border-l  ">

						<div class="file__section--title header-b border-b border-r  ">
							<?= esc($etiqueta['description']) ?>
							<input type="hidden" name="items[<?= esc($etiqueta['id']) ?>][itemId]" value="<?= esc($etiqueta['id']) ?>">
							<input type="hidden" name="items[<?= esc($etiqueta['id']) ?>][aprobado]" >
							<input type="hidden" name="items[<?= esc($etiqueta['id']) ?>][observacion]" class="url_etiqueta">
						</div>

						<div class="flex flex-col items-center justify-center gap-y-6 w-full h-96 border-r  border-gray ">

							<div class="flex flex-col items-center justify-center gap-y-6 mx-auto w-1/2 ">

								<img id="img_etiqueta_insp" class="h-64 w-full border-2 border-grayMid bg-grayLight rounded ">  

								<button data-input="cameraInput" data-img="img_etiqueta" data-modal="modal_etiqueta" class="btn_open_modal btn_open_camera pdf-button " type="button">
										<span class="text-lg ">Cargar imagen</span>
								</button>

							</div>
						</div>

						<div class="file__section--value border-r border-t border-b">
							<label class="label--check">
								<span>Aprobado</span>
								<input type="radio" data-id="<?= esc($etiqueta['id']) ?>" name="items[<?= esc($etiqueta['id']) ?>][check]" data-value="si" class="checkbox_inspec hidden">
								<span class="checkbox-label-si"><i class="fas fa-check "></i></span>
							</label>

							<label class="label--check">
								<span>Rechazado</span>
								<input type="radio" data-id="<?= esc($etiqueta['id']) ?>" name="items[<?= esc($etiqueta['id']) ?>][check]" data-value="no" class="checkbox_inspec hidden" >	
								<span class="checkbox-label-no"><i class="fas fa-x "></i></span>
							</label>
						</div>

					</div>

					<div class="w-full flex justify-center pt-12 ">
						<button data-modal="modal_confirm" class="btn_open_modal text-2xl pdf-button " type="button" >
							<i class="fas fa-save "></i>
							<span>Guardar</span>
						</button>
					</div>


					<div class=" hidden firmas__section ">

						<div class="firmas__firma  border-t border-l  ">

							<div class="firma__header header-b border-b border-r ">
								ALMACEN DE MATERIALES Y MATERIAS PRIMAS
							</div>

							<div class="flex flex-col text-center justify-between w-full h-64  text-gray  border-r  border-gray p-4 ">
								<img id="almacen_firma" class="h-44 w-full ">  
								<p id="almacen_nombre"></p>

								<select id="almacenId" class="my-2 text-center to_uppercase" required>
									<option value="" >Seleccionar ....</option>
									<?php foreach ($almacen as $user): ?>
										<option value="<?= $user['id'] ?>"><?= $user['name'] . ' ' . $user['last_name']?></option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="flex firma__footer header-b border-b border-r border-t ">
								<span>Nombre y Firma</span>
							</div>

							<div class="flex firma__footer border-r border-b ">
								<button data-field="firma_almacen" data-area="almacenId" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
									Firmar
								</button>
							</div>


						</div>

						<div class="firmas__firma  border-t border-l  ">

							<div class="firma__header header-b border-b border-r ">
								Control de calidad
							</div>

							<div class="flex flex-col text-center justify-between w-full h-64  text-gray  border-r  border-gray p-4 ">
								<img id="calidad_firma" class="h-44 w-full ">  
								<!-- <p id="calidad_nombre"><?= session()->get('user')['name'] . ' ' . session()->get('user')['last_name'] ?></p> -->
								<p id="calidad_nombre"></p>
							</div>

							<div class="flex firma__footer header-b border-b border-r border-t ">
								<span>Nombre y Firma</span>
							</div>

							<div class="flex firma__footer border-r border-b ">
								<button data-field="firma_calidad" data-id="" data-user-id="<?= session()->get('user')['id'] ?>" data-area="calidadId" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
									Firmar
								</button>
							</div>


						</div>


					</div>
				</div>


			</div>

			<?php
			// echo "<pre>";
			// print_r($adjunto);
			// echo "</pre>";

			?>

			<!-- Pagina 3 -->
			<div class=" page__container">

				<!-- Datos Formato Inspeccion -->
				<div class="formato__section ">
					<div class="formato__col-1 ">
						<img src="<?= base_url('img/gibanibb_logo.png') ?>">
					</div>

					<div class="formato__col-2">
						<div class="cell border-l border-b ">
							<span>TÍTULO: </span>
							<p><?= esc($formato['titulo']) ?></p>
						</div>	
						<div class="cell border-l border-b ">
							<span>CLAVE: </span>
							<p><?= esc($formato['clave']) ?></p>
						</div>	
						<div class="cell border-l ">
							<span>VERSIÓN: </span>
							<p><?= esc($formato['version']) ?></p>
						</div>						
					</div>

					<div class="formato__col-3">
						<div class="cell--alt border-l border-b">
							<span>PÁGINA: </span>
							<p class="num-page"><?= esc($formato['paginas']) ?></p>
						</div>						
						<div class="cell--alt cell--mobile border-l border-b  ">
							<span>ÚLTIMA REVISIÓN: </span>
							<p class="moment-date"><?= esc($formato['revision']) ?></p>
						</div>						
						<div class="cell--alt cell--mobile border-l  ">
							<span>FECHA DE VIGENCIA: </span>
							<p class="moment-date"><?= esc($formato['vigencia']) ?></p>
						</div>						
					</div>
				</div>

				<!-- Datos Adjunto Inspeccion -->
				<div class="adjunto__section border-t border-l border-b">

					<div class="file__section--title header-b border-b border-r">
						<?= esc($adjunto['description']) ?>
					</div>

					<div class="flex flex-col items-center justify-center gap-y-6 w-full h-[1300px] border-r  border-gray p-4">

						<div class="flex flex-col items-center justify-center gap-y-6 mx-auto w-full ">

							<img id="img_certif_insp" class="h-[1280px] w-full border-2 border-grayMid bg-grayLight rounded ">  

							<button data-input="fileInput" data-id="" data-img="img_certif" data-modal="modal_files" class="hidden btn_open_modal btn_open_camera items-center gap-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit uppercase; " type="button">
									<span class="text-lg ">Cargar imagen</span>
							</button>

						</div>
					</div>

				</div>


			</div>

			<input type="hidden" id="inspId" value="<?= esc($inspId ?? '') ?>">
		</form>

	</div>

<!-- Modal Certificado -->
<div id="modal_files" class="hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
	<div class=" flex flex-col gap-y-6 mx-4 bg-white border-2 border-icon p-6 w-full lg:max-w-2xl h-[90%]">

		<div class="relative flex w-full justify-center text-center ">
			<h3 class="text-gray text-xl uppercase">Certificado</h3>
			<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
		</div>

		<img id="img_certif" alt="Captured Photo" class="flex justify-center items-center mx-auto w-full h-[82%] border-title border-2 rounded">

		<div class="flex w-full  mx-auto justify-between text-sm ">
			<button data-input="fileInput" class=" btn_retry flex space-x-4 shadow-bottom-right text-error border-2 border-error hover:bg-error hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
				REINTENTAR
			</button>
			<button data-id="" data-file="img_certif" data-img="img_certif" class=" btn_save_file lex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
				GUARDAR
			</button>
		</div>

	</div>

	<input type="file" data-img="img_certif" id="fileInput" accept="image/*" capture="environment" class="hidden">
</div>

<!-- Modal etiqueta -->
<div id="modal_etiqueta" class="hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
	<div class=" flex flex-col gap-y-6 mx-4 bg-white border-2 border-icon p-6 w-full lg:max-w-2xl h-[60%]">

		<div class="relative flex w-full justify-center text-center ">
			<h3 class="text-gray text-xl uppercase">Etiqueta</h3>
			<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
		</div>

		<img id="img_etiqueta" alt="Captured Photo" class="flex justify-center items-center mx-auto w-full h-[70%] border-title border-2 rounded">

		<div class="flex w-full  mx-auto justify-between text-sm ">
			<button data-input="cameraInput" class="btn_retry flex space-x-4 shadow-bottom-right text-error border-2 border-error hover:bg-error hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
				REINTENTAR
			</button>
			<button data-item="<?= esc($etiqueta['id']) ?>" data-img="img_etiqueta" class=" btn_save_file flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
				GUARDAR
			</button>
		</div>

	</div>

	<input type="file" data-img="img_etiqueta" id="cameraInput" accept="image/*" capture="environment" class="hidden">
</div>

<!-- Modal confirm -->
<div id="modal_confirm" class="hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
	<div class=" flex flex-col  items-center gap-y-6 mx-4 bg-white border-2 border-icon p-6 w-full lg:max-w-2xl h-[50%]">

		<div class="relative flex w-full justify-center text-center ">
			<h3 class="text-gray text-xl uppercase">Confirmar</h3>
			<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
		</div>

		<div class="relative flex w-full justify-center text-center  py-20 ">
			<h3 class="text-gray text-3xl">¿Seguro de guardar cambios?</h3>
		</div>

		<div class="flex w-full items-center justify-center text-sm ">
			<button id="btn_submit" class="  flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
				CONFIRMAR
			</button>
		</div>

	</div>

</div>


<!-- Modal Comentario unico -->
<div id="modal_comm_unico" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
	<div class=" flex flex-col gap-y-8 bg-white mx-4 border-2 border-icon p-8 w-full md:w-[500px]">

		<div class="relative flex w-full justify-center text-center ">
			<h3 class="text-gray text-xl uppercase">Comentario único</h3>
			<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
		</div>

		<div class="relative flex w-full justify-center text-center ">
			<p id="section_title" class="text-gray"></p>
		</div>

		<textarea id="modal_textarea" class="p-4 w-full border border-grayMid outline-none resize-none bg-grayLight text-gray drop-shadow " rows="3" placeholder="Escribe tu comentario..."></textarea>

		<div class="flex justify-end space-x-12 text-sm ">
			<button id="btn_save_comment" class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
				GUARDAR
			</button>
		</div>

	</div>
</div>



<script>

Service.setLoading();

<?php if ($inspId) : ?>
let inspId = document.querySelector("#inspId");

const registros = <?= json_encode($registros) ?>;

document.addEventListener('DOMContentLoaded', () => {
	registros.forEach(reg => {
		const itemId = reg.itemId;

		// Check the radio for aprobado value
		const radio = document.querySelector(`input[data-id="${itemId}"][data-value="${reg.aprobado}"]`);
		if (radio) {
			radio.checked = true;
		}

		// Set observacion text
		const observInput = document.querySelector(`input[name="items[${itemId}][observacion]"]`);
		if (observInput) {
			observInput.value = reg.observacion;
		}

		// Set hidden aprobado field, if you use it
		const hiddenInput = document.querySelector(`input[name="items[${itemId}][aprobado]"]`);
		if (hiddenInput) {
			hiddenInput.value = reg.aprobado;
		}

		if (reg.observacion && reg.observacion.match(/\.(jpg|jpeg|png|gif|webp)$/i)) {
			const img = document.querySelector(`#img_etiqueta_insp`);
			if (img) {
				img.src = `${root}/files/download?path=${reg.observacion}`; 
      } else {
			}
		}

	});

	initFirmas(inspId.value);

	lockChecklistRadios();
});

const generalItemsEdit = <?= json_encode($generalItems) ?>;

document.addEventListener("DOMContentLoaded", () => {
  const inputs = document.querySelectorAll("input[data-field]");

  inputs.forEach(input => {
    const label = input.dataset.field?.trim().toUpperCase();

    // Match item by description
    const item = generalItemsEdit.find(i => i.description.trim().toUpperCase() === label);

    if (item) {
      // Set value
      input.value = item.observacion;

      // Add hidden input with itemId and observacion
      const hiddenName = `general_items[${item.itemId}][observacion]`;

      // Prevent duplicate hidden
      if (!input.parentElement.querySelector(`input[type="hidden"][name="${hiddenName}"]`)) {
        const hidden = document.createElement("input");
        hidden.type = "hidden";
        hidden.name = hiddenName;
        hidden.value = item.observacion;

        input.parentElement.appendChild(hidden);
      }
    }
  });
});



<?php endif; ?>



document.querySelectorAll('input[type="text"]').forEach(input => {
  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault(); // Don't submit the form
    }
  });
});

const lockChecklistRadios = () => {
  document.querySelectorAll('.checkbox_inspec').forEach(radio => {
    if (radio.checked) {
      // Lock the selected radio by disabling all radios in the same group
      const name = radio.name;
      document.querySelectorAll(`input[name="${name}"]`).forEach(input => {
        input.disabled = true;
      });
    }
  });
};




	const previewPhoto = (e) => {
		const file = e.target.files[0];
		if (file) {

			let img = e.target.getAttribute('data-img');
		
			const reader = new FileReader();
			reader.onload = function(event) {
				document.getElementById(img).src = event.target.result;
			};
			reader.readAsDataURL(file);
		}
	}

	const cameraInput = document.querySelector('#cameraInput');
	cameraInput?.addEventListener('change', previewPhoto);

	const fileInput = document.querySelector('#fileInput');
	fileInput?.addEventListener('change', previewPhoto);

	allBtnCamera = document.querySelectorAll('.btn_open_camera')
	allBtnCamera.forEach( btn => {
		btn.addEventListener('click', (e) => {
			let img = e.currentTarget.getAttribute('data-img');
			let input = e.currentTarget.getAttribute('data-input');

			document.getElementById(img).src = "<?= base_url('img/no_img_alt.png') ?>";
			document.getElementById(input).value = ''; 
			document.getElementById(input).click();
		});
	})

	allBtnRetry = document.querySelectorAll('.btn_retry')
	allBtnRetry.forEach( btn => {
		btn.addEventListener('click', (e) => {
			let input = e.currentTarget.getAttribute('data-input');

			document.getElementById(input).value = ''; 
			document.getElementById(input).click();
		});
	})


	allBtnFile = document.querySelectorAll('.btn_save_file')
	allBtnFile.forEach( btn => {
		btn?.addEventListener('click', (e) => {
			let img = btn.getAttribute('data-img');
			let file_btn = btn.getAttribute('data-file') ?? '';

			let item = btn.getAttribute('data-item');

			let input_file = document.querySelector(`input[data-img="${img}"]`);

			// console.log(input_file.files[0]);
			// return;

			let modal_active = document.querySelector('.modal_active');
			if (modal_active) {
				modal_active.classList.add('hidden');
				modal_active.classList.remove('modal_active');
				document.body.classList.remove('no-scroll');
			}

			if (img === "img_etiqueta") {

				const file = input_file.files[0];

				if (!file) return;

				const formData = new FormData();
				formData.append('etiqueta', file);
				formData.append('tipo', 'etiqueta');

				Service.show('.loading');
				Service.stopSubmit(e.target, true);


				Service.exec('post', `/upload/inspeccion`, formData_header, formData)
				.then(r => {

					const input_obs = document.querySelector(`input[name="items[${item}][observacion]"`);
					
					input_obs.value = r.url;
					document.querySelector('#img_etiqueta_insp').src = `${root}/files/download?path=${r.url}`;

					Service.hide('.loading');

					Service.stopSubmit(e.target, false);
				});  


			} 
			
			if (file_btn === 'img_certif') {
				let inspeccionId = btn.getAttribute('data-id');
				if (!inspeccionId) return;

				const file = input_file.files[0];

				if (!file) return;

				const formData = new FormData();
				formData.append('certif', file);
				formData.append('inspeccionId', inspeccionId);
				formData.append('tipo', 'certif');

				Service.show('.loading');
				Service.stopSubmit(e.target, true);


				Service.exec('post', `/upload/inspeccion`, formData_header, formData)
				.then(r => {

					document.querySelector('#img_certif_insp').src = `${root}/files/download?path=${r.url}`;

					Service.hide('.loading');

					Service.stopSubmit(e.target, false);
				});  

			}




		});
	});








function updatePageNumbers() {
  const pages = document.querySelectorAll('.page__container');
  const totalPages = pages.length;

  pages.forEach((page, index) => {
    const numElem = page.querySelector('.num-page');
    if (numElem) {
      numElem.textContent = `${index + 1} de ${totalPages}`;
    }
  });
}

document.addEventListener('DOMContentLoaded', updatePageNumbers);



window.addEventListener('load', () => {
  const isMobile = window.innerWidth < 1024;
  document.querySelectorAll('.desktop-input').forEach(el => el.disabled = isMobile);
  document.querySelectorAll('.mobile-input').forEach(el => el.disabled = !isMobile);
});

const generalItems = <?= json_encode($general) ?>;

document.addEventListener("DOMContentLoaded", () => {
  const inputs = document.querySelectorAll("input[data-field]");

  inputs.forEach(input => {
    const label = input.dataset.field?.trim().toUpperCase();

    const item = generalItems.find(i => i.description.trim().toUpperCase() === label);

    if (item) {
      input.name = `items[${item.id}][observacion]`;

      // Optional: Add hidden inputs for aprobado + itemId
      const parent = input.parentElement;
      
      const aprobadoInput = document.createElement("input");
      aprobadoInput.type = "hidden";
      aprobadoInput.name = `items[${item.id}][aprobado]`;
      aprobadoInput.value = "si";

      const itemIdInput = document.createElement("input");
      itemIdInput.type = "hidden";
      itemIdInput.name = `items[${item.id}][itemId]`;
      itemIdInput.value = item.id;

      parent.appendChild(aprobadoInput);
      parent.appendChild(itemIdInput);
    }
  });
});


const toggleCheckbox = (e) => {
	let _val = e.target.getAttribute('data-value');
	let id = e.target.getAttribute('data-id');

	document.querySelector(`input[name="items[${id}][aprobado]"]`).value = _val;

	console.log(_val);


};

const allCheckboxToggle = document.querySelectorAll('.checkbox_inspec');
allCheckboxToggle?.forEach(input => {
	input.addEventListener('change', toggleCheckbox);
});


	const allMomentDate = document.querySelectorAll('.moment-date');
	allMomentDate?.forEach( p => {
		p.innerText = formatToMonthYear(p.innerText);
	});
				

	const form_openia = document.querySelector('#form_openia');
  form_openia?.addEventListener('submit', e => {
    e.preventDefault();
    Service.stopSubmit(e.target, true);

    document.getElementById('respuesta').innerHTML = Service.loader();

    const formData = new FormData(e.target);
// actualizar search proveedores 
    Service.exec('post', `/test/openia`, formData_header, formData)
    .then(r => {
      // renderProv(r);
			document.getElementById('respuesta').innerText = r.answer;
      Service.stopSubmit(e.target, false);
    });  
  });


const modal_comm_unico = document.querySelector('#modal_comm_unico');
const modal_textarea = document.querySelector('#modal_textarea');
const section_title = document.querySelector('#section_title');

const btn_save_comment = document.querySelector('#btn_save_comment');
btn_save_comment.addEventListener('click', (e) => {

	let title = e.currentTarget.getAttribute('data-section-title');
			// console.log(e.currentTarget)

	
		// let comm_container = 	document.querySelector(`div[data-com-id="${id}"]`);	
		let comm_input = 	document.querySelectorAll(`input[data-section-title="${title}"]`);	
		// console.log(comm_input)

	if (modal_textarea.value) {
		comm_input.forEach( input => {
			input.value = modal_textarea.value;	
		})
			
		// comm_container.innerHTML = modal_textarea.value;	
		// comm_container.classList.remove('hidden');

	}

	modal_comm_unico.classList.add('hidden');
	modal_comm_unico.classList.remove('modal_active');
	document.body.classList.remove('no-scroll');

});


const allBtnNa = document.querySelectorAll('.btn_na');
allBtnNa?.forEach( btn => {
	btn.addEventListener('click', (e) => {
		let title = e.currentTarget.getAttribute('data-section-title');
		let comm_input = 	document.querySelectorAll(`input[data-section-title="${title}"]`);	

		comm_input.forEach( input => {
			input.value = "N.A.";	
		})

	});
});


const initRowBtn = () => {
	const allBtnOpen = document.querySelectorAll('.btn_open_modal');
	allBtnOpen?.forEach( (btn, index) => {

		btn.addEventListener('click', e => {
			// console.log(e.currentTarget)

			let modal_id = e.currentTarget.getAttribute('data-modal');
			let modal = document.querySelector(`#${modal_id}`);
			// console.log(modal_id)

			modal.classList.add('modal_active');
			modal.classList.remove('hidden');
			document.body.classList.add('no-scroll');


			if (modal_id == 'modal_comm_unico') {

				modal_textarea.value = '';

				let section = e.currentTarget.getAttribute('data-section');

				let id = e.currentTarget.getAttribute('data-id');
				btn_save_comment.setAttribute('data-section-title', section);

				section_title.innerHTML = section;

				currentCommentInput = document.querySelector(`input[name="items[${id}][observacion]"]`);
				// console.log(currentCommentInput)

				modal_textarea.value = currentCommentInput.value || '';
			}

		});
	});

	const allBtnClose = document.querySelectorAll('.btn_close_modal')
	allBtnClose?.forEach( btn => {
		btn.addEventListener('click', (e) => {
			let modal_active = document.querySelector('.modal_active');
			if (modal_active) {
				modal_active.classList.add('hidden');
				modal_active.classList.remove('modal_active');
				document.body.classList.remove('no-scroll');
			}
		});
	});

}

initRowBtn();




const modal_confirm = document.querySelector('#modal_confirm');
const almacenId = document.querySelector('#almacenId');

almacenId?.addEventListener('change', e => {
	firma_almacen.setAttribute('data-user-id', e.target.value.trim());
	console.log(firma_almacen)
});

let firma_almacen = document.querySelector('button[data-field="firma_almacen"]');
let firma_calidad = document.querySelector('button[data-field="firma_calidad"]');
let btn_certif = document.querySelector('button[data-file="img_certif"]');
let btn_modal_certif = document.querySelector('button[data-modal="modal_files"]');

const btn_submit = document.querySelector('#btn_submit');
btn_submit?.addEventListener('click', e => {

	let etiqueta = document.querySelector('.url_etiqueta');
	let firmas__section = document.querySelector('.firmas__section');

	if(etiqueta.value !== undefined) {
		Service.stopSubmit(e.target, true);
		Service.show('.loading');

		const form_inspeccion = document.querySelector("#form_inspeccion");
  	const formData = new FormData(form_inspeccion);
		formData.append('slug', 'materias-primas')

		if(inspId.value !== "") {
			formData.append('inspeccionId', inspId.value);
		}
		
		// const formDataObj = {};
		// formData.forEach((value, key) => {
		// 	formDataObj[key] = value;
		// });

		// console.log(formDataObj);

		// 	return;

		// Service.exec('post', `${root}/inspeccion/materias_primas`, formData_header, formData)
		Service.exec('post', `inspeccion/materias_primas`, formData_header, formData)
		.then(r => {
			if(r.inspeccionId){

				Service.hide('.loading');
				Service.hide('#btn_submit');
				lockChecklistRadios();

				firma_almacen.setAttribute('data-id', r.inspeccionId);
				firma_calidad.setAttribute('data-id', r.inspeccionId);
				btn_certif.setAttribute('data-id', r.inspeccionId);

				btn_modal_certif.classList.add('flex');
				btn_modal_certif.classList.remove('hidden');

						// hide the modal and initFirmas

				modal_confirm.classList.remove('modal_active');
				modal_confirm.classList.add('hidden');
				document.body.classList.remove('no-scroll');

				firmas__section.classList.add('flex');
				firmas__section.classList.remove('hidden');

				Service.stopSubmit(e.target, false);
			}
		});

	} else {
		console.log('not ready')
		// mostrar mensaje es obligatorio subir etiqueta

	}
});






const initFirmas = (inspeccionId) => {
	// traer todos los adjuntos y mostrar el mas reciente como adjunto_repar

	Service.exec('get', `/get_firmas_insp/${inspeccionId}`)
	.then( r => {

		firma_almacen.setAttribute('data-id', inspeccionId);
		firma_calidad.setAttribute('data-id', inspeccionId);


		if ( r.almacen && r.almacen.name ) {
			document.querySelector('#almacen_nombre').textContent = `${r.almacen.name} ${r.almacen.last_name}`; 
		}

		if ( r.calidad && r.calidad.name ) {
			document.querySelector('#calidad_nombre').textContent = `${r.calidad.name} ${r.calidad.last_name}`; 
		}


		if ( r.inspeccion.firma_calidad == "si" ) {
			document.querySelector('#calidad_firma').src = `${root}/files/download?path=${r.calidad.signature}`; 
		} else {
			document.querySelector('#calidad_firma').src = `${root}/img/no_img_alt.png`; 
		}

		if ( r.inspeccion.firma_almacen == "si" ) {
			document.querySelector('#almacen_firma').src = `${root}/files/download?path=${r.almacen.signature}`; 
		} else {
			document.querySelector('#almacen_firma').src = `${root}/img/no_img_alt.png`; 
		}

	});

}


const initFiles = (inspeccionId) => {
	// traer todos los adjuntos y mostrar el mas reciente como adjunto_repar

	Service.exec('get', `/get_files_insp/${inspeccionId}`)
	.then( r => {

		if ( r.file.archivo ) {
			document.querySelector('#img_certif_insp').src = `${root}/files/download?path=${r.file.archivo}`; 
		} else {
			document.querySelector('#img_certif_insp').src = `${root}/img/no_img_alt.png`; 
		}

	});

}




const submitFirma = (e) => {

	let field = e.target.getAttribute('data-field');
	let id = e.target.getAttribute('data-id');
	let userId = e.target.getAttribute('data-user-id');
	let area = e.target.getAttribute('data-area');

	// console.log(field, id); return;

	e.target.disabled = true;

	const formData = new FormData();
	formData.append('field', field);
	formData.append('inspeccionId', id);

	if(userId !== undefined) {
		formData.append('userId', userId);
		formData.append('area', area);
	}

	Service.exec('post', `${root}/add_firma_insp`, formData_header, formData)
	.then( r => {
		e.target.disabled = false;
		initFirmas(r.inspeccionId); 
	});
}

const allBtnFirmar = document.querySelectorAll('.btn_firmar');
	allBtnFirmar?.forEach( btn => {
	btn.addEventListener('click', submitFirma);
});



</script>
</body>
</html>
