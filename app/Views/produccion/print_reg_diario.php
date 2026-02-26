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

		<?php echo view("produccion/_partials/print_navbar"); ?>

		<div class="header">
			<h2 class="title"><?= esc($title) ?></h2>
			<a href="<?= base_url('produccion/ordenes_registros/' . $ordenfab['num_orden']) ?>" class="arrow-link">
				<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
			</a>
		</div>

		<button id="generate-pdf" class="pdf-button">Descargar PDF</button>


		<div class=" pdf-container--print web-padding web-gap ">
			<!-- pagina 1 -->
			<div class=" page__container">

				<!-- Datos Formato Inspeccion -->
				<?php echo view("produccion/_partials/format-header"); ?>

				<!-- Datos Generales Inspeccion -->
				<div class="reg__diario__section">


					<div class="row-1">
						<div class="bg-table-title">
							<span>SUPERVISOR / LIDER</span>
						</div>

						<div>
							<span><?= $reporte['name'] . ' ' . $reporte['last_name']?></span>
						</div>

						<div class="bg-table-title">
							<span>TURNO</span>
						</div>

						<div>
							<input type="text" value="<?= esc($reporte['turno']) ?>" readonly>
						</div>

						<div class="bg-table-title">
							<span>FECHA</span>
						</div>

						<div>
							<input type="text" value="<?= fechaEspanol(dateToString($reporte['created_at'])); ?>" readonly>
						</div>
					</div>

					<div class="section__title bg-table-title">
						<span><?= $secciones[1] ?></span>
					</div>

					<div class="row-2">
						<div class="bg-table-title">
							<span>LINEA</span>
						</div>
						<div class="bg-table-title">
							<span>CODIGO PT</span>
						</div>
						<div class="bg-table-title">
							<span>PRODUCTO</span>
						</div>
						<div class="bg-table-title">
							<span>ORDEN FABRIC.</span>
						</div>
						<div class="bg-table-title">
							<span>HORA INICIO</span>
						</div>
						<div class="bg-table-title">
							<span>HORA FIN</span>
						</div>					
					</div>

					<div class="row-3">
						<div>
							<input type="text" value="<?= esc($reporte['linea']) ?>" readonly>
						</div>
						<div>
							<input type="text" value="<?= esc($ordenfab['num_articulo']) ?>" readonly>
						</div>
						<div>
							<span><?= esc($ordenfab['desc_articulo']) ?></span>
						</div>
						<div>
							<input type="text" value="<?= esc($ordenfab['num_orden']) ?>" readonly>
						</div>
						<div>
							<input type="text" value="<?= esc(substr($reporte['hora_inicio_registro'], 0, -3)) ?>" readonly>
						</div>
						<div>
							<input type="text" value="<?= esc(substr($reporte['hora_fin_registro'], 0, -3)) ?>" readonly>
						</div>					
					</div>

					<div class="row-4">
						<div class="bg-table-title text-xs">
							<span>CAJAS DEL TURNO</span>
						</div>
						<div class="bg-table-title text-xs">
							<span>PIEZAS PRODUCIDAS</span>
						</div>
						<div class="bg-table-title text-xs">
							<span>MUESTRAS RETENCION</span>
						</div>
						<div class="bg-table-title text-xs">
							<span>PIEZAS ACUMULADAS</span>
						</div>
						<div class="bg-table-title text-xs">
							<span>PIEZAS REQUERIDAS</span>
						</div>
						<div class="bg-table-title text-xs">
							<span>LOTE DE PRODUCTO TERMINADO</span>
						</div>
						<div class="bg-table-title text-xs">
							<span>CADUCIDAD DE PRODUCTO TERMINADO</span>
						</div>				
					</div>

					<div class="row-4">
						<div>
							<input type="text" value="<?= esc($reporte['cajas_turno']) ?>" readonly>
						</div>
						<div>
							<input type="text" value="<?= esc($reporte['piezas_producidas']) ?>" readonly>
						</div>
						<div>
							<input type="text" value="<?= esc($reporte['muestras']) ?>" readonly>
						</div>
						<div>
							<input type="text" value="<?= esc($acumulado) ?>" readonly>
						</div>
						<div>
							<input type="text" value="<?= esc($ordenfab['cantidad_plan']) ?>" readonly>
						</div>
						<div>
							<input type="text" value="<?= esc($ordenfab['lote']) ?>" readonly>
						</div>		
						<div>
							<input type="text" value="<?= esc($ordenfab['caducidad']) ?>" readonly>
						</div>				
					</div>

					<div class="row-5">
						<div class="bg-table-title">
							<span>BATCH INICIAL</span>
						</div>
						<div class="bg-table-title">
							<span>BATCH FINAL</span>
						</div>
						<div class="bg-table-title">
							<span>CANTIDAD DE MEZCLA</span>
						</div>
						<div class="bg-table-title">
							<span>LOTE DE MEZCLA ENVASADO</span>
						</div>
						<div class="bg-table-title">
							<span># COLECTIVA DE CIERRE</span>
						</div>
						<div class="bg-table-title">
							<span>ESTATUS DE LA FABRICACION</span>
						</div>			
					</div>

					<div class="row-5">
						<div>
							<input type="text" value="<?= esc($reporte['batch_inicial']) ?>" readonly>
						</div>
						<div>
							<input type="text" value="<?= esc($reporte['batch_final']) ?>" readonly>
						</div>
						<div>
							<input type="text" value="<?= esc($reporte['cantidad_mezcla']) ?>" readonly>
						</div>
						<div>
							<input type="text" value="<?= esc($reporte['lote_mezcla']) ?>" readonly>
						</div>
						<div>
							<input type="text" value="<?= esc($reporte['colectiva']) ?>" readonly>
						</div>
						<div>
							<input type="text" value="<?= esc($reporte['status_fabricacion']) ?>" readonly>
						</div>						
					</div>

					<div class="section__comment">
						<span><?= esc($reporte['observacion_produc'])?></span>
					</div>

					<div class="section__title bg-table-title">
						<span><?= $secciones[2] ?></span>
					</div>

					<div id="incidencias-container">
						<div class="row-6">
							<div class="bg-table-title">
								<span>DESCRIPCION DEL PARO</span>
							</div>
							<div class="bg-table-title">
								<span>HORA DE INICIO</span>
							</div>
							<div class="bg-table-title">
								<span>HORA DE INICIO</span>
							</div>
							<div class="bg-table-title">
								<span>TIEMPO DE PARO</span>
							</div>				
						</div>
					</div>

					<div class="section__title bg-table-title">
						<span><?= $secciones[3] ?></span>
					</div>

					<div id="operarios-container">
						<div class="row-8">
							<div class="bg-table-title">
								<span>OPERADOR</span>
							</div>
							<div class="bg-table-title">
								<span>POSICION</span>
							</div>			
						</div>

					</div>

				</div>

				<!-- Datos Secciones Inspeccion -->


			</div>
			
			<div class="page-break"></div>

			<!-- Pagina 2 -->
			<div class=" page__container">
				<!-- Datos Formato Inspeccion -->
				<?php echo view("produccion/_partials/format-header"); ?>

				<div class="reg__diario__section">
					<!-- inspeccion materiales -->
					<div class="section__title bg-table-title">
						<span><?= $secciones[4] ?></span>
					</div>

					<div id="componentes_wrapper">
						<!-- Column 1 -->
						<div class="col">

							<div class="row-first">
								<div class="bg-table-title">
									<p>COMPONENTE</p>
								</div>
								<div class="bg-table-title">
									<p>C</p>
								</div>
								<div class="bg-table-title">
									<p>N/A</p>
								</div>
								<div class="bg-table-title">
									<p>MERMA</p>
								</div>
								<div class="bg-table-title">
									<p>UNIDAD</p>
								</div>
							</div>

							<div id="col-inspec-1"></div>
						</div>

						<!-- Column 2 -->
						<div class="col">
							<div class="row-first">
								<div class="bg-table-title">
									<p>COMPONENTE</p>
								</div>
								<div class="bg-table-title">
									<p>C</p>
								</div>
								<div class="bg-table-title">
									<p>N/A</p>
								</div>
								<div class="bg-table-title">
									<p>MERMA</p>
								</div>
								<div class="bg-table-title">
									<p>UNIDAD</p>
								</div>
							</div>
							<div id="col-inspec-2"></div>
						</div>

						<!-- Column 3 -->
						<div class="col">
							<div class="row-first">
								<div class="bg-table-title">
									<p>COMPONENTE</p>
								</div>
								<div class="bg-table-title">
									<p>C</p>
								</div>
								<div class="bg-table-title">
									<p>N/A</p>
								</div>
								<div class="bg-table-title">
									<p>MERMA</p>
								</div>
								<div class="bg-table-title">
									<p>UNIDAD</p>
								</div>
							</div>
							<div id="col-inspec-3"></div>
						</div>
					</div>


					<!-- orden y limpieza -->
					<div class="section__title bg-table-title">
						<span><?= $secciones[5] ?></span>
					</div>

					<div id="limpieza_wrapper">
						<!-- Column 1 -->
						<div class="col">
							<div class="row-first">
								<div class="bg-table-title">
									<p>ITEM</p>
								</div>
								<div class="bg-table-title">
									<p>SI</p>
								</div>
								<div class="bg-table-title">
									<p>NO</p>
								</div>
							</div>
							<div id="col-limpieza-1"></div>
						</div>

						<!-- Column 2 -->
						<div class="col">
							<div class="row-first">
								<div class="bg-table-title">
									<p>ITEM</p>
								</div>
								<div class="bg-table-title">
									<p>SI</p>
								</div>
								<div class="bg-table-title">
									<p>NO</p>
								</div>
							</div>
							<div id="col-limpieza-2"></div>
						</div>

					</div>

					<div class="section__title bg-table-title">
						<span><?= $secciones[6] ?></span>
					</div>

					<!-- observacion reporte -->
					<div class="section__comment">
						<span><?= esc($reporte['observacion_reporte'])?></span>
					</div>


					<div class="section__firma">
						<img id="produccion_firma" src="<?= base_url() . 'files/download?path=' . $reporte['signature']?>">  

						<span><?= $reporte['name'] . ' ' . $reporte['last_name']?></span>
						<!-- <span><?= session()->get('user')['puesto'] ?></span> -->
						<span>SUPERVISOR / LIDER DE PRODUCCION</span>
					</div>


				</div>


		</div>

	</div>

  <script>
Service.setLoading();

const incidencias = <?= json_encode($incidencias); ?>;
const tiempoData = <?= json_encode($tiempo_data); ?>;
const incidenciasContainer = document.getElementById('incidencias-container');

console.log(tiempoData);
document.addEventListener("DOMContentLoaded", () => {
  tiempoData.forEach(data => {
    renderIncidenciaRow(data);
  });
});

const renderIncidenciaRow = (data = {}) => {

  const row = document.createElement('div');
  row.className = 'row-7';

  row.innerHTML = `
		<div>
			<input type="text" value="${getIncidenciaById(data.incidId)}" readonly>
		</div>
		<div>
			<input type="text" value="${data.hora_inicio.slice(0, 5) || ''}" readonly>
		</div>
		<div>
			<input type="text" value="${data.hora_fin.slice(0, 5) || ''}" readonly>
		</div>
		<div>
			<input type="text" value="${data.tiempo_paro.slice(0, 5) || ''}" readonly>
		</div>	
  `;

  incidenciasContainer.appendChild(row);
};

const getIncidenciaById = (id) => {
  const incidencia = incidencias.find(incid => incid.id === id);
  return incidencia ? incidencia.incidencia : '';
};


const operarios = <?= json_encode($operarios); ?>; 
const personalData = <?= json_encode($personal_data); ?>; 
const operariosContainer = document.getElementById('operarios-container');

document.addEventListener("DOMContentLoaded", () => {
  personalData.forEach(data => {
    addOperarioRow(data);
  });
});


function addOperarioRow(data = {}) {
  const row = document.createElement('div');
  row.className = 'row-9';

  row.innerHTML = `
		<div>
			<input type="text" value="${getOperarioNameById(data.personalId)}" readonly>
		</div>
		<div>
			<input type="text" value="${data.puesto || ''}" readonly>
		</div>
  `;

  operariosContainer.appendChild(row);
}

// Function to get the operario's name by their ID
function getOperarioNameById(id) {
  const operario = operarios.find(op => op.id === id);
  return operario ? `${operario.name} ${operario.last_name}` : '';
}




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
      row.classList.add('row-last');

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

      // Use globalIndex to ensure unique names for each input field
      row.innerHTML = `
        <div>
          <input type="text" value="${componente.name}" readonly>
        </div>

				<div>
					<label class="label--check">
						<input type="radio" name="componentes[${globalIndex}][cumple]" value="si" class="checkbox_inspec--print hidden" ${cumpleSiChecked}>
						<span class="checkbox-label-si--print"><i class="fas fa-check"></i></span>
					</label>
				</div>

				<div>
					<label class="label--check">
						<input type="radio" name="componentes[${globalIndex}][cumple]" value="no" class="checkbox_inspec--print hidden" ${cumpleNoChecked}>
						<span class="checkbox-label-no--print"><i class="fas fa-x"></i></span>
					</label>
				</div>

        <div>
          <input type="text" class="to_uppercase text-center" value="${mermaValue}">
        </div>

        <div>
					<input type="text" class="to_uppercase text-center" value="${unidadValue}">
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
      row.classList.add('row-last');

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
        <div>
					<span>${item.name}</span>
        </div>

				<div>
					<label class="label--check">
						<input type="radio" name="limpieza[${globalIndex}][cumple]" value="si" class="checkbox_inspec--print hidden" ${cumpleSiChecked}>
						<span class="checkbox-label-si--print"><i class="fas fa-check"></i></span>
					</label>
				</div>

				<div>
					<label class="label--check">
						<input type="radio" name="limpieza[${globalIndex}][cumple]" value="no" class="checkbox_inspec--print hidden" ${cumpleNoChecked}>
						<span class="checkbox-label-no--print"><i class="fas fa-x"></i></span>
					</label>
				</div>
      `;

      container.appendChild(row);

      globalIndex++;
    });
  }

  col1Container.innerHTML = '';
  col2Container.innerHTML = '';

  renderColumn(col1, col1Container);
  renderColumn(col2, col2Container);
}

renderLimpieza();










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

		}

		loadGeneralData();


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

			let articulo = "<?= esc($ordenfab['num_articulo']) ?>"
			let orden_fab = "<?= esc($ordenfab['num_orden']) ?>";

      const opt = {
        margin:       6,
        filename:     `${formatTextPdf(articulo)}_${formatTextPdf(orden_fab)}.pdf`,
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
