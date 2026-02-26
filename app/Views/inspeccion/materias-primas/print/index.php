<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<link rel="icon" href="<?= base_url('img/logo.ico') ?>" type="image/x-icon">
	<link rel="stylesheet" href="<?= load_asset('_partials/print_header.css') ?>">
	<link rel="stylesheet" href="<?= load_asset('_partials/print_inspeccion.css') ?>">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>

  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
	<title><?= esc($title) ?></title>
</head>
<body>

	<div class="wrapper">

		<?php echo view("inspeccion/$slug/print/_partials/navbar"); ?>

		<div class="header">
			<h2 class="title"><?= esc($title) ?></h2>
			<a href="<?= base_url('inspeccion/lista/materias-primas') ?>" class="arrow-link">
				<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
			</a>
		</div>

		<button id="generate-pdf" class="pdf-button">Descargar PDF</button>


		<div class=" pdf-container--print web-padding web-gap ">
			<!-- pagina 1 -->
			<div class=" page__container">

				<!-- Datos Formato Inspeccion -->
				<?php echo view("inspeccion/$slug/print/_partials/format-header"); ?>

				<!-- Datos Generales Inspeccion -->
				<div class="general__section">

					<div class="row-1 bg-table-title">
						<div>
							<span>LOTE INTERNO</span>
						</div>
						<div>
							<span>MATERIA PRIMA</span>
						</div>
						<div>
							<span>CANTIDAD <br>(Piezas o contenedores).</span>
						</div>
						<div>
							<span>CANTIDAD TOTAL <br>(Kg o L)</span>
						</div>
					</div>

					<div class="row-2 ">
						<div>
							<input type="text" data-field="LOTE INTERNO" >
						</div>
						<div>
							<input type="text" data-field="MATERIA PRIMA">
						</div>
						<div>
							<input type="text" data-field="CANTIDAD (Piezas o contenedores)">
						</div>
						<div>
							<input type="text" data-field="CANTIDAD TOTAL (Kg o L)">
						</div>
					</div>


					<div class="row-3 bg-table-title">
						<div>
							<span>FECHA DE ARRIBO</span>
						</div>
						<div>
							<span>LOTE EXTERNO</span>
						</div>
						<div>
							<span>PROVEEDOR</span>
						</div>
						<div>
							<span>CADUCIDAD</span>
						</div>
					</div>

					<div class="row-4">
						<div>
							<input type="text" data-field="FECHA DE ARRIBO">
						</div>
						<div>
							<input type="text" data-field="LOTE EXTERNO">
						</div>
						<div>
							<input type="text" data-field="PROVEEDOR">
						</div>
						<div>
							<input type="text" data-field="CADUCIDAD">
						</div>
					</div>

					<div class="row-5 ">
						<div class="bg-table-title">
							<span>NOMBRE DEL TRANSPORTISTA</span>
						</div>

						<div>
							<input type="text" data-field="NOMBRE DE TRANSPORTISTA" class="text-xs">
						</div>

						<div class="bg-table-title">
							<span>NÚMERO DE PLACA</span>
						</div>

						<div>
							<input type="text" data-field="NUMERO DE PLACA">
						</div>
					</div>

				</div>

				<!-- Datos Secciones Inspeccion -->
				<div class="list__section">
					<div class="list__section__wrapper">
						<?php foreach($secciones as $title => $groupedItems): ?>
						<div class="section__title bg-table-title">
							<div><?= esc($title) ?></div>
							<div>SI CUMPLE</div>
							<div>NO CUMPLE</div>
							<div>
								<span>OBSERVACIONES</span>
							</div>
						</div>

							<?php foreach($groupedItems as $item): ?>
							<div class="section__items">
								<div><?= esc($item['item_number']) ?> <?= esc($item['description']) ?></div>

								<div class="item--alt ">
									<label class="label--check">
										<input type="radio" data-id="<?= esc($item['id']) ?>" name="items[<?= esc($item['id']) ?>][check]" data-value="si" class="checkbox_inspec--print hidden">
										<span class="checkbox-label-si--print"><i class="fas fa-check "></i></span>
									</label>
								</div>

								<div class="item--alt ">
									<label class="label--check">
										<input type="radio" data-id="<?= esc($item['id']) ?>" name="items[<?= esc($item['id']) ?>][check]" data-value="no" class="checkbox_inspec--print hidden" >	
										<span class="checkbox-label-no--print"><i class="fas fa-x "></i></span>
									</label>
								</div>

								<div class="item--alt">
									<input type="text" data-section-title="<?= esc($title) ?>" name="items[<?= esc($item['id']) ?>][observacion]" >
								</div>

							</div>
							<?php endforeach; ?>
						<?php endforeach; ?>
					</div>
				</div>

			</div>
			
			<div class="page-break"></div>

			<!-- pagina 2 -->
			<div class=" page__container">
				<!-- Datos Formato Inspeccion -->
				<?php echo view("inspeccion/$slug/print/_partials/format-header"); ?>

				<!-- Datos Etiqueta Inspeccion -->
				<div class="etiqueta__section">

					<div class="file__section ">
						<div class="file__section__title bg-table-title ">
							<?= esc($etiqueta['description']) ?>
						</div>

						<div class="file__section__image ">
							<div class="etiqueta">
								<img id="img_etiqueta_insp">  
							</div>
						</div>

						<div class="file__section__check ">
							<label class="label--check">
								<span>Aprobado</span>
								<input type="radio" data-id="<?= esc($etiqueta['id']) ?>" name="items[<?= esc($etiqueta['id']) ?>][check]" data-value="si" class="checkbox_inspec--print hidden">
								<span class="checkbox-label-si--print checkbox-border"><i class="fas fa-check "></i></span>
							</label>

							<label class="label--check">
								<span>Rechazado</span>
								<input type="radio" data-id="<?= esc($etiqueta['id']) ?>" name="items[<?= esc($etiqueta['id']) ?>][check]" data-value="no" class="checkbox_inspec--print hidden" >	
								<span class="checkbox-label-no--print checkbox-border"><i class="fas fa-x "></i></span>
							</label>
						</div>
					</div>

					<div class="firmas__section">
						<div>
							<div class="firma__title bg-table-title">ALMACEN DE MATERIALES Y MATERIAS PRIMAS</div>
							<div class="firma__content">
								<img id="almacen_firma">  
								<p id="almacen_nombre"></p>
							</div>
							<div class="firma__footer bg-table-title">Nombre y Firma</div>
						</div>

						<div>
							<div class="firma__title bg-table-title">Control de calidad</div>
							<div class="firma__content">
								<img id="calidad_firma">  
								<p id="calidad_nombre"></p>
							</div>
							<div class="firma__footer bg-table-title">Nombre y Firma</div>
						</div>
					</div>

				</div>


			</div>


		</div>

	</div>

  <script>
Service.setLoading();


		let inspeccionId = <?= esc($inspId) ?>;


		const lockChecklistRadios = () => {
			document.querySelectorAll('.checkbox_inspec--print').forEach(radio => {
				if (radio.checked) {
					// Lock the selected radio by disabling all radios in the same group
					const name = radio.name;
					document.querySelectorAll(`input[name="${name}"]`).forEach(input => {
						input.disabled = true;
					});
				}
			});
		};

		const loadItems = () => {
			const registros = <?= json_encode($registros) ?>;

			registros.forEach(reg => {
				const itemId = reg.itemId;

				const radio = document.querySelector(`input[data-id="${itemId}"][data-value="${reg.aprobado}"]`);
				if (radio) {
					radio.checked = true;
				}

				const observInput = document.querySelector(`input[name="items[${itemId}][observacion]"]`);
				if (observInput) {
					observInput.value = reg.observacion;
				}

				if (reg.observacion && reg.observacion.match(/\.(jpg|jpeg|png|gif|webp)$/i)) {
					const img = document.querySelector(`#img_etiqueta_insp`);
					if (img) {
						img.src = `${root}/files/download?path=${reg.observacion}`; 
					} else {
					}
				}
			});

			lockChecklistRadios();
		}

		loadItems();



		const loadGeneralData = () => {
			document.querySelectorAll('input[type="text"]').forEach(input => {
				if (!input.classList.contains('text-xs')) {
					input.classList.add('text-sm');
				}
				input.setAttribute('readonly', true);
			});

			const allMomentDate = document.querySelectorAll('.moment-date');
			allMomentDate?.forEach( p => {
				p.innerText = formatToMonthYear(p.innerText);
			});

			const pages = document.querySelectorAll('.page__container');
			const totalPages = <?= esc($formato['paginas']) ?>

			pages.forEach((page, index) => {
				const numElem = page.querySelector('.num-page');
				if (numElem) {
					numElem.textContent = `${index + 1} de ${totalPages}`;
				}
			});

			const generalItems = <?= json_encode($generalItems) ?>;
			const inputs = document.querySelectorAll("input[data-field]");

			inputs.forEach(input => {
				const label = input.dataset.field?.trim().toUpperCase();
				const item = generalItems.find(i => i.description.trim().toUpperCase() === label);

				if (item) {
					input.value = item.observacion;
				}
			});

		}

		loadGeneralData();

		const loadFirmas = (inspeccionId) => {
			Service.exec('get', `/get_firmas_insp/${inspeccionId}`)
			.then( r => {

				if ( r.almacen && r.almacen.name ) {
					document.querySelector('#almacen_nombre').textContent = `${r.almacen.name} ${r.almacen.last_name}`; 
				}

				if ( r.calidad && r.calidad.name ) {
					document.querySelector('#calidad_nombre').textContent = `${r.calidad.name} ${r.calidad.last_name}`; 
				}

				// inspecciones/3/vista_previa_tab_ventanas.png

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

		loadFirmas(inspeccionId);
		

		const loadFiles = (inspeccionId) => {
			Service.exec('get', `/get_files_insp/${inspeccionId}`)
			.then( r => {

				const container = document.querySelector('.pdf-container--print');

				if (!container || !Array.isArray(r)) return;

				r.forEach( file => {

					const pageBreak = document.createElement('div');
					pageBreak.className = 'page-break';

					const pageContainer = document.createElement('div');
					pageContainer.className = 'page__container';

					const fileWrapper = document.createElement('div');
					fileWrapper.className = 'file__wrapper';

					const img = document.createElement('img');
					img.src = `${root}/files/download?path=${file.archivo}`;

					fileWrapper.appendChild(img);
					pageContainer.appendChild(fileWrapper);

					container.appendChild(pageBreak);
					container.appendChild(pageContainer);
				});


			});
		}

		loadFiles(inspeccionId);

		const formatTextPdf = (text) => {
			return text
				.toUpperCase()        // Convert text to uppercase
				.replace(/\s+/g, '_') // Replace spaces with '_'
				.replace(/-/g, '_');  // Replace dash '-' with '_'
		};

    document.getElementById('generate-pdf').addEventListener('click', () => {

			Service.show('.loading');
			document.body.classList.add('no-scroll');

      const element = document.querySelector('.pdf-container--print');
			element.classList.remove('web-padding');
			element.classList.remove('web-gap');

			let lote_interno = document.querySelector('input[data-field="LOTE INTERNO"]');
			let materia_prima = document.querySelector('input[data-field="MATERIA PRIMA"]');

      const opt = {
        margin:       6,
        filename:     `${formatTextPdf(lote_interno.value)}_${formatTextPdf(materia_prima.value)}.pdf`,
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
      };

			html2pdf().set(opt).from(element).save().then(() => {
				element.classList.add('web-padding');
				element.classList.add('web-gap');

				document.body.classList.remove('no-scroll');
				Service.hide('.loading');

				loadItems();
			});
    });


  </script>
</body>
</html>
