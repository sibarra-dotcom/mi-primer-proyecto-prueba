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

		<?php echo view("mtickets/_partials/print_navbar"); ?>

		<div class="header">
			<h2 class="title"><?= esc($title) ?></h2>
			<a href="<?= base_url('mtickets') ?>" class="arrow-link">
				<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
			</a>
		</div>

		<button id="generate-pdf" class="pdf-button">Descargar PDF</button>


		<div id="form_ticket" class=" pdf-container--print web-padding web-gap ">
			<!-- pagina 1 -->
			<div class="page__container">

				<!-- header formato -->
				<?php echo view("mtickets/_partials/print_header"); ?>

				<div class="mticket__section">

					<!-- Información de creación -->
					<div class="mticket-group">

						<div class="file__section ">
							<div class="file__section__title bg-table-title ">
								Información de creación
							</div>
						</div>

						<div class="group-col">
							<div>
								<div class="input_group_label">FECHA DE CREACIÓN</div>
								<div id="fecha_creacion" class="input_group_input bg-table-title">Nombre y Firma</div>
							</div>
							<div>
								<div class="input_group_label">HORA DE REPORTE</div>
								<div id="hora_reporte" class="input_group_input bg-table-title">Nombre y Firma</div>
							</div>
							<div>
								<div class="input_group_label">SOLICITANTE</div>
								<div id="solicitante" class="input_group_input bg-table-title">Nombre y Firma</div>
							</div>

						</div>

					</div>


					<!-- Información de Seguimiento -->
					<div class="mticket-group">

						<div class="file__section ">
							<div class="file__section__title bg-table-title ">
								Información de Seguimiento
							</div>
						</div>

						<div class="group-col">
							<div>
								<div class="input_group_label">ESTADO DEL TICKET</div>
								<div id="estado_ticket" class="input_group_input bg-table-title"></div>
							</div>
							<div>
								<div class="input_group_label">INICIO DE REPARACION</div>
								<div id="fecha_reparacion" class="input_group_input bg-table-title"></div>
							</div>
							<div>
								<div class="input_group_label">FIN DE REPARACION</div>
								<div id="fin_reparacion" class="input_group_input bg-table-title"></div>
							</div>

						</div>
						<div class="group-col">
							<div>
								<div class="input_group_label">TIEMPO TOTAL REPARACION</div>
								<div id="tiempo_total_reparacion" class="input_group_input bg-table-title"></div>
							</div>
							<div>
								<div class="input_group_label">FECHA DE CIERRE</div>
								<div id="fecha_cierre" class="input_group_input bg-table-title"></div>
							</div>
							<div>
								<div class="input_group_label">Imputable</div>
								<div id="imputable" class="input_group_input bg-table-title"></div>
							</div>

						</div>
					</div>

					<!-- Detalles -->
					<div class="mticket-group">

						<div class="file__section ">
							<div class="file__section__title bg-table-title ">
								Detalles
							</div>
						</div>

						<div class="group-col">
							<div>
								<div class="input_group_label">RESPONSABLE</div>
								<div id="responsable_text" class="input_group_input bg-table-title"></div>
							</div>
							<div>
								<div class="input_group_label">PLANTA</div>
								<div id="planta" class="input_group_input bg-table-title"></div>
							</div>
							<div>
								<div class="input_group_label">LINEA</div>
								<div id="linea" class="input_group_input bg-table-title"></div>
							</div>

						</div>
						<div class="group-col">
							<div>
								<div class="input_group_label">MAQUINA</div>
								<div id="maquina" class="input_group_input bg-table-title"></div>
							</div>
							<div>
								<div class="input_group_label">PRIORIDAD</div>
								<div id="prioridad" class="input_group_input bg-table-title"></div>
							</div>
							<div>
								<div class="input_group_label">ESTADO</div>
								<div id="estado_maq" class="input_group_input bg-table-title"></div>
							</div>

						</div>

						<div class="group-col">
							<div>
								<div class="input_group_label">ASUNTO</div>
								<div id="asunto" class="input_group_input bg-table-title"></div>
							</div>
						</div>

						<div class="group-col">
							<div>
								<div class="input_group_label">Descripción</div>
								<div id="descripcion" class="input_group_input bg-table-title"></div>
							</div>
						</div>

					</div>

					<!-- Comentarios -->
					<div class="mticket-group">

						<div class="file__section ">
							<div class="file__section__title bg-table-title ">
								Comentarios
							</div>
						</div>

						<div class="group-col">
							<div>
								<div class="input_group_label">Nombre</div>
							</div>
							<div>
								<div class="input_group_label">Fecha</div>
							</div>
							<div>
								<div class="input_group_label">Comentario</div>
							</div>
						</div>

						<div id="comentarios_container"></div>


					</div>

				</div>
			</div>
			
			<div class="page-break"></div>

			<!-- pagina 2 -->
			<div class=" page__container">
				<!-- header formato -->
				<?php echo view("mtickets/_partials/print_header"); ?>

				<div class="mticket__section">

					<!-- Evidencia del Incidente -->
					<div class="mticket-group">

						<div class="file__section ">
							<div class="file__section__title bg-table-title ">
								Evidencia del Incidente
							</div>
						</div>

						<div class="group-col">
							<div>
								<div class="input_group_label">
									<img id="adjunto_mant" class="image-md">
								</div>
								
							</div>

						</div>
					</div>

					<!-- Evidencia de Reparación -->
					<div class="mticket-group">

						<div class="file__section ">
							<div class="file__section__title bg-table-title ">
								Evidencia de Reparación
							</div>
						</div>

						<div class="group-col">
							<div>
								<div class="input_group_label">
									<img id="adjunto_repar" class="image-md">
								</div>
								
							</div>
						</div>

						<div class="group-col">
							<div>
								<div class="input_group_label">Diagnostico</div>
								<div id="diagnostico" class="input_group_input bg-table-title"></div>
							</div>
						</div>
						<div class="group-col">
							<div>
								<div class="input_group_label">Reparacion Realizada</div>
								<div id="reparacion_detalle" class="input_group_input bg-table-title"></div>
							</div>
						</div>

					</div>

					<!-- Firmas de Aprobación -->
					<div class="mticket-group">
						<div class="section__firma">
							<div>
								<img id="resp_firma">  
								<span id="resp_nombre"></span>
								<h5 class="puesto">Realiza Mantenimiento</h5>
							</div>
							<div>
								<img id="solic_firma">  
								<span id="solic_nombre"></span>
								<h5 class="puesto">Solicitante</h5>
							</div>
						</div>
		
					</div>

					<div class="mticket-group">

						<div class="file__section ">
							<div class="file__section__title bg-table-title ">
								¿Se requiere limpieza y sanitización?
							</div>
						</div>

						<div class="group-col">
							<div class="checkbox-group">
								<span>SI</span>
								<label class="label--check">
									
									<input type="radio"  data-name="requiere_limpieza" data-value="si" class="checkbox_inspec--print hidden">
									<span class="checkbox-label-si--print checkbox-border"><i class="fas fa-check "></i></span>
								</label>
							</div>

							<div class="checkbox-group">
								<span>NO</span>
								<label class="label--check">
									<input type="radio" data-name="requiere_limpieza" data-value="no" class="checkbox_inspec--print hidden" >	
									<span class="checkbox-label-no--print checkbox-border"><i class="fas fa-x "></i></span>
								</label>
							</div>
						</div>

					</div>

					<!-- Firmas de Aprobación -->
					<div class="mticket-group">
						<div class="section__firma">
							<div id="limpieza_section">
								<img id="limpieza_firma">  
								<span id="limpieza_nombre"></span>
								<h5 class="puesto">Limpieza y Sanitización</h5>
							</div>
							<div>
								<img id="calidad_firma">  
								<span id="calidad_nombre"></span>
								<h5 class="puesto">Libera calidad</h5>
							</div>
							<div>
								<img id="encar_firma">  
								<span id="encar_nombre"></span>
								<h5 class="puesto">Encargado Mantenimiento</h5>
							</div>
						</div>
		
					</div>


				</div>
			</div>

		</div>

	</div>

  <script>
Service.setLoading();

	const ticketStates = {
			1: 'ABIERTO',
			2: 'EN PROGRESO',
			3: 'RESUELTO',
			4: 'CERRADO'
	};

	const responsables = <?= json_encode($responsables, JSON_UNESCAPED_UNICODE) ?>;


  const initModalTicket = (id) => {
    let modal_ticket = document.querySelector(`#modal_ticket`);

		let inventario_section = document.querySelector('#inventario_section');
		let limpieza_section = document.querySelector('#limpieza_section');
		let pregunta_compra = document.querySelector('#pregunta_compra');


    Service.exec('get', `/get_ticket/${id}`)
    .then( r => {

      let form = document.querySelector(`#form_ticket`);

      if (r) {

				const radio = form.querySelector(`input[data-name="requiere_limpieza"][data-value="${r.requiere_limpieza}"]`);
				if (radio) {
					radio.checked = true;
				}

				form.querySelector('#fecha_creacion').innerHTML = dateToString(r.created_at);
				form.querySelector('#hora_reporte').innerHTML = `${fixedTimeMoment(r.created_at, 'HH:mm')}`;
				form.querySelector('#solicitante').innerHTML = r.solicitante;

				form.querySelector('#estado_ticket').innerHTML = ticketStates[r.estado_ticket] ?? '-';

				form.querySelector('#responsable_text').innerHTML = responsables[r.responsableId] ?? '-';

				form.querySelector('#fecha_reparacion').innerHTML = `${fixedTimeMoment(r.fecha_reparacion, 'HH:mm')} ${dateToString(r.fecha_reparacion)}`;

				form.querySelector('#fin_reparacion').innerHTML = `${fixedTimeMoment(r.fecha_arranque, 'HH:mm')} ${dateToString(r.fecha_arranque)}`

				form.querySelector('#fecha_cierre').innerHTML = dateToString(r.fecha_cierre) || '-';
				form.querySelector('#imputable').innerHTML = r.imputable || '-';


				form.querySelector('#planta').innerHTML = r.planta;
				form.querySelector('#linea').innerHTML = r.linea;
				form.querySelector('#maquina').innerHTML = r.nombre;
				form.querySelector('#estado_maq').innerHTML = r.estado_maq;
				form.querySelector('#prioridad').innerHTML = r.prioridad;
				form.querySelector('#asunto').innerHTML = r.asunto;
				form.querySelector('#descripcion').innerHTML = r.descripcion;

				form.querySelector('#diagnostico').innerHTML = r.diagnostico;
				form.querySelector('#reparacion_detalle').innerHTML = r.reparacion_detalle;


				if(r.fecha_reparacion) {			
					form.querySelector('#tiempo_total_reparacion').innerHTML = getTimeDiff(r.fecha_reparacion, r.fecha_arranque);
				}


				initComment(r.id);
				initAdjuntos(r.id);
				initFirmas(r.id);
        
      }

    });
  }


	initModalTicket(<?= esc($ticketId) ?>);


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

	lockChecklistRadios();

	const loadPagesData = () => {

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
	}

	loadPagesData();

	const setInputFontSize = () => {
		document.querySelectorAll('input[type="text"]').forEach(input => {
			if (!input.classList.contains('text-xs')) {
				input.classList.add('text-sm');
			}
			input.setAttribute('readonly', true);
		});
	}
	
	setInputFontSize();

  const initComment = (id) => {

    let comentarios_container = document.querySelector('#comentarios_container');

    Service.exec('get', `/get_comentarios_mt/${id}`)
    .then( r => {
      // console.log(r); return;
      comentarios_container.innerHTML = "";

      if ( r.comments.length > 0 ) {
        r.comments.forEach(comm => {


          const div = document.createElement('div');
          div.className = 'group-col';
          div.innerHTML = `
							<div>
								<div class="input_group_label">${comm.name} ${comm.last_name}</div>
							</div>
							<div>
								<div class="input_group_label">${dateToString(comm.created_at)}</div>
							</div>
							<div>
								<div class="input_group_label">${comm.comentario}</div>
							</div>
          `;
          comentarios_container.appendChild(div);
        });

      } else {
        const div = document.createElement('div');
        div.className = 'group-col';
				 div.innerHTML = `
							<div>
								<div class="input_group_label">No hay comentarios.</div>
							</div>
         `;
        comentarios_container.appendChild(div);
      }
    });
  }


	const initFirmas = (id) => {

		Service.exec('get', `/get_firmas_mt/${id}`)
    .then( r => {

			let form = document.querySelector(`#form_ticket`);
			let limpieza_section = document.querySelector(`#limpieza_section`);
			limpieza_section.style.display = 'none';

			form.querySelector('#solic_nombre').textContent = `${r.solic.name} ${r.solic.last_name}`; 
			form.querySelector('#encar_nombre').textContent = `${r.encar.name} ${r.encar.last_name}`; 
			form.querySelector('#resp_nombre').textContent = `${r.resp.name} ${r.resp.last_name}`; 

      if ( r.produccion && r.produccion.name ) {
				form.querySelector('#produccion_nombre').textContent = `${r.produccion.name} ${r.produccion.last_name}`; 
			}

			if ( r.limpieza && r.limpieza.name ) {
				form.querySelector('#limpieza_nombre').textContent = `${r.limpieza.name} ${r.limpieza.last_name}`; 
			}

			if ( r.calidad && r.calidad.name ) {
				form.querySelector('#calidad_nombre').textContent = `${r.calidad.name} ${r.calidad.last_name}`; 
			}


      if ( r.solic.signature ) {
				form.querySelector('#solic_firma').src = `${root}/files/download?path=${r.solic.signature}`; 
      } else {
				form.querySelector('#solic_firma').src = `${root}/img/no_img_alt.png`; 
      }

			if ( r.ticket.firma_encargado == "si" ) {
				form.querySelector('#encar_firma').src = `${root}/files/download?path=${r.encar.signature}`; 
      } else {
				form.querySelector('#encar_firma').src = `${root}/img/no_img_alt.png`; 
      }

			if ( r.ticket.firma_responsable == "si" ) {
				form.querySelector('#resp_firma').src = `${root}/files/download?path=${r.resp.signature}`; 
      } else {
				form.querySelector('#resp_firma').src = `${root}/img/no_img_alt.png`; 
      }

			if ( r.ticket.firma_calidad == "si" ) {
				form.querySelector('#calidad_firma').src = `${root}/files/download?path=${r.calidad.signature}`; 
      } else {
				form.querySelector('#calidad_firma').src = `${root}/img/no_img_alt.png`; 
      }


			if ( r.ticket.firma_limpieza == "si" ) {
				limpieza_section.style.display = 'block';
				form.querySelector('#limpieza_firma').src = `${root}/files/download?path=${r.limpieza.signature}`; 
      } else {
				form.querySelector('#limpieza_firma').src = `${root}/img/no_img_alt.png`; 
      }

    });

	}


	const initAdjuntos = (id) => {

		Service.exec('get', `/get_adjuntos_mt/${id}`)
    .then( r => {
      // console.log(r.adjuntos[0]); return;
			
			let form = document.querySelector(`#form_ticket`);

			form.querySelector('#adjunto_mant').src = `${root}/files/download?path=${r.adjuntos[0].archivo}`; 

      if ( r.adjuntos.length > 1 ) {
				let repar = r.adjuntos[r.adjuntos.length - 1]; 
				form.querySelector('#adjunto_repar').src = `${root}/files/download?path=${repar.archivo}`; 
      } else {
				form.querySelector('#adjunto_repar').src = `${root}/img/no_img_alt.png`; 
      }
    });

	}


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

	// loadFiles(inspeccionId);

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
			filename:     `hoja_mantenimiento_<?= $ticketId ?>.pdf`,
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
