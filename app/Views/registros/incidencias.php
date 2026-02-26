<?php echo view('_partials/header'); ?>
	<link rel="stylesheet" href="<?= base_url('_partials/inspeccion.css') ?>">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/Modal.min.js') ?>"></script>
  <link rel="stylesheet" href="<?= load_asset('_partials/inspeccion.css') ?>">
	<script src="https://unpkg.com/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>

  <title><?= esc($title) ?></title>
</head>
<body class="relative min-h-screen">

  <div class=" relative h-full pb-16 flex flex-col gap-y-8 items-center justify-center font-titil ">

  	<?php echo view('dashboard/_partials/navbar'); ?>

    <div class="text-title w-full md:p-4 md:px-16 p-2 flex items-center ">
      <h2 class="text-center font-bold w-full text-2xl lg:text-4xl "><?= esc($title1) ?></h2>
      <a href="<?= base_url('apps') ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>

		<form id="form_incidencia" method="post" class="pdf-container" enctype='multipart/form-data'>
			<?= csrf_field() ?>

			<!-- Pagina 1 -->
			<div class=" flex flex-col gap-y-8 w-full lg:w-9/12 p-3 lg:p-16 bg-white drop-shadow-card">


				<h2 class="text-center font-bold w-full text-3xl lg:text-3xl text-title pb-8 "><?= esc($title) ?></h2>

				<div class="text-title w-full flex items-center justify-between ">
					<div class="flex flex-col items-center "> 
						<span class="text-gray uppercase">Fecha</span>
						<div class="text-white py-1 px-4 bg-icon uppercase"><?php echo date('d-m-Y'); ?></div> 
					</div>

					<div class="flex gap-x-4  w-1/2 items-center justify-end ">
						<div class="flex flex-col w-2/3 items-center "> 
							<span class="text-gray uppercase">Selecciona tu nombre:</span>
							<select id="produccionId_select" class="w-full text-gray " required>
								<option value="" selected disabled>Seleccionar...</option>
							<?php foreach ($lider_pro as $lider): ?>
								<option value="<?= $lider['id']?>"><?= $lider['name'] . ' ' . $lider['last_name'] ?></option>
							<?php endforeach; ?>
							</select>
						</div>

						<button data-modal="modal_pin" id="btn_ingresar_pin" class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-4 w-fit " type="button">Ingresar PIN
						</button>
					</div>

				</div>


				<h2 class="uppercase text-center w-full text-xl lg:text-2xl text-gray ">Informacion General</h2>

				<!-- Datos Generales Inspeccion -->
				<div class="general__section">

					<div class="general__col--produccion header border-r border-t  ">
						<div class=" col w-full lg:w-[50%] border-l border-b">
							<span>TURNO</span>
						</div>
						<div class=" col w-full lg:w-[50%] border-l border-b">
							<span>NUMERO DE ORDEN DE FABRICACION</span>
						</div>
					</div>

					<div class="general__col--produccion border-gray border-r">
						<div class="col border-l border-b w-[50%]">
							<select name="turno" class="w-full text-gray text-center" required>
								<option value="GDL - Matutino">GDL - Matutino</option>
								<option value="GDL - Vespertino">GDL - Vespertino</option>
								<option value="GDL - Nocturno">GDL - Nocturno</option>
								<option value="TONALÁ - Matutino">TONALÁ - Matutino</option>
							</select>
						</div>
						<div class="col border-l border-b w-[50%]">
							<input type="text" name="nro_orden" class="to_uppercase text-center h-8">
						</div>
					</div>


					<div class="general__col--produccion header border-r">
						<div class="col border-l border-b w-[18%]">
							<span>LINEA</span>
						</div>
						<div class="col border-l border-b w-[64%]">
							<span>ARTICULO EN PRODUCCION</span>
						</div>
						<div class="col border-l border-b w-[18%]">
							<span>CODIGO</span>
						</div>
					</div>

					<div class="general__col--produccion border-r">
						<div class="col border-l border-b w-[18%]">
							<input type="text" name="linea" class="to_uppercase text-center h-8">
						</div>
						<div class="relative col border-l border-b w-[64%]">
							<input type="text" id="productoInput" class="to_uppercase text-center h-8 w-full" placeholder="Buscar producto...">
							<input type="hidden" id="productoIdHidden" name="productoId" required>
						</div>
						<div class="col border-l border-b w-[18%]">
							<input type="text" name="codigo" required class="to_uppercase text-center h-8">
						</div>
					</div>


					<div class="general__col--produccion header border-r  ">
						<div class=" col w-[50%] border-l border-b">
							<span>SELECCIONA PROCESO</span>
						</div>
						<div class=" col w-[50%] border-l border-b">
							<span>TIPO PROCESO</span>
						</div>
					</div>

					<div class="general__col--produccion border-r">
						<div class="col border-l border-b w-[50%]">
							<select name="procesoId" class="w-full text-gray text-center " required>
							<?php foreach ($procesos as $proc): ?>
								<option value="<?= $proc['id']?>"><?= $proc['descripcion']?></option>
							<?php endforeach; ?>
							</select>
						</div>
						<div class="col border-l border-b w-[50%]">
							<select name="tipo_proceso" class="w-full text-gray text-center " required>
								<option value="Proceso">Proceso</option>
								<option value="Re-Proceso">Re-Proceso</option>
							</select>
						</div>
					</div>

				</div>



				<h2 class="uppercase text-center w-full text-xl lg:text-2xl text-gray ">META vs REAL</h2>

				<!-- Datos Metas -->
				<div class="general__section">

					<div class="general__col--produccion header border-r border-t">
						<div class="col border-l border-b w-[18%]">
							<span>INGRESA META EN PIEZAS</span>
						</div>
						<div class="col border-l border-b w-[64%]">
							<span>META EN UNIDAD DE MEDIDA</span>
						</div>
						<div class="col border-l border-b w-[18%]">
							<span>UNIDAD DE MEDIDA</span>
						</div>
					</div>

					<div class="general__col--produccion border-r">
						<div class="col border-l border-b w-[18%]">
							<input type="text" name="meta_piezas" class="input--number to_uppercase text-center h-8">
						</div>
						<div class="col border-l border-b w-[64%]">
							<input type="text" name="meta_cantidad" class="input--number to_uppercase text-center h-8">
						</div>
						<div class="col border-l border-b w-[18%]">
							<select name="meta_medida" class="w-full text-gray text-center " required>
								<option value="KG">KG</option>
								<option value="L">L</option>
							</select>
						</div>
					</div>


					<div class="general__col--produccion header border-r">
						<div class="col border-l border-b w-[18%]">
							<span>CANTIDAD EN PIEZAS REALES</span>
						</div>
						<div class="col border-l border-b w-[64%]">
							<span>REAL EN UNIDAD DE MEDIDA</span>
						</div>
						<div class="col border-l border-b w-[18%]">
							<span>UNIDAD DE MEDIDA</span>
						</div>
					</div>

					<div class="general__col--produccion border-r">
						<div class="col border-l border-b w-[18%]">
							<input type="text" name="real_piezas" class="input--number to_uppercase text-center h-8">
						</div>
						<div class="col border-l border-b w-[64%]">
							<input type="text" name="real_cantidad" class="input--number to_uppercase text-center h-8">
						</div>
						<div class="col border-l border-b w-[18%]">
							<select name="real_medida" class="w-full text-gray text-center " required>
								<option value="KG">KG</option>
								<option value="L">L</option>
							</select>
						</div>
					</div>

				</div>

				<!-- Datos Tiempo muerto -->
				<div class="general__section">

					<div class="w-full flex border-gray header border-r border-t">
						<div class="col border-gray border-l border-b w-full lg:w-[50%] ">
							<p class="w-full text-center py-2">TIEMPO MUERTO TOTAL (HORAS)</p>
						</div>
						<div class="col border-gray border-l border-b w-full lg:w-[50%] ">
							<p class="w-full text-center py-2">TIEMPO EFECTIVO</p>
						</div>
					</div>

					<div class="flex w-full border-gray border-r">
						<div class="col border-l border-b w-[50%]">
							<div class="w-full flex items-center justify-center pt-2">
								<div class="quantity-tiempo-muerto"></div>
							</div>
						</div>
						<div class="col border-l border-b w-[50%]">
							<div class="p-2">
								<input readonly name="total_tiempo_efectivo" id="total_tiempo_efectivo" class="w-full text-lg text-gray py-2 text-center" value="8.0">
							</div>
						</div>
					</div>
				</div>



				<h2 class="uppercase text-center w-full text-xl lg:text-2xl text-gray ">REPORTE DE PERSONAL</h2>		

				<div class="flex w-full justify-end items-center">
					<button  id="add-operario" class=" rounded text-icon border-2 border-icon hover:bg-icon hover:text-white py-1 px-3 w-fit uppercase" type="button">
						<i class="fa fa-plus text-xl"></i>
						<span>Agregar operario</span>
					</button>
				</div>
				

				<!-- Datos Personal -->
				<div id="operarios-container" class="general__section">
					<div class="w-full flex border-gray header border-r border-t">
						<div class="col border-gray border-l border-b w-full lg:w-[75%] ">
							<p class="w-full text-center py-2">PERSONAL QUE TRABAJO LA LINEA</p>
						</div>
						<div class="col border-gray border-l border-b w-full lg:w-[25%] ">
							<p class="w-full text-center py-2">HORAS EXTRA</p>
						</div>
					</div>

					<!-- insert personal rows -->
				</div>



				<!-- Datos Personal horas -->
				<div class="general__section">

					<div class="w-full flex border-gray header border-r border-t">
						<div class="col border-gray border-l border-b w-full lg:w-[75%] ">
							<p class="w-full text-center py-2"> TOTAL DE PERSONAS EN EL PROCESO</p>
						</div>
						<div class="col border-gray border-l border-b w-full lg:w-[25%] ">
							<p class="w-full text-center py-2">TOTAL HORAS EXTRA</p>
						</div>
					</div>

					<div class="flex w-full border-gray border-r">
						<div class="col border-l border-b w-[75%]">
							<div class="flex items-center p-2">
								<input readonly value="0" name="total_personal" id="total_personal" class="w-full text-lg text-gray py-2 text-center" >
							</div>

						</div>
						<div class="col border-l border-b w-[25%]">
							<div class="p-2">
								<input readonly value="0.0" name="total_horas_extras" id="total_horas_extras" class="w-full text-lg text-gray py-2 text-center" >
							</div>
						</div>
					</div>
				</div>




				<h2 class="uppercase text-center w-full text-xl lg:text-2xl text-gray ">INCIDENCIAS</h2>

				<!-- Datos incidencias -->
				<div class="general__section">

					<div class="w-full flex border-gray header border-r border-t">
						<div class="col border-gray border-l border-b w-full lg:w-[50%] ">
							<p class="w-full text-center py-2">EN CASO DE INCIDENCIA SELECCIONE MOTIVO</p>
						</div>
						<div class="col border-gray border-l border-b w-full lg:w-[50%] ">
							<p class="w-full text-center py-2">HUBO DESVIACION DE CALIDAD, SELECCIONA CUAL(ES)</p>
						</div>
					</div>

					<div class="flex w-full border-gray ">

						<div class="col border-l w-[50%]">
							<div class="flex flex-col items-center justify-center ">
								<?php foreach ($incidencias as $in): ?>
									<div class="flex items-center border-b border-r py-2 border-gray justify-between w-full">
										<span class="px-4"><?= $in['incidencia'] ?></span>
										<div class="item--alt w-[10%]">
											<label class="label--check" for="incidencia_<?= $in['id'] ?>">
												<!-- Check if the current incidencia ID exists in the selectedIds array -->
												<input type="checkbox" name="incidencias[]" value="<?= $in['id'] ?>" 
															class="checkbox_incidencia hidden" 
															id="incidencia_<?= $in['id'] ?>"
												>
												<span class="checkbox-label-inc"><i class="fas fa-check"></i></span>
											</label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>


						<div class="col w-[50%]">
							<div class="flex flex-col  items-center justify-center ">
								<?php foreach ($desviaciones as $desv): ?>
									<div class="flex items-center border-b border-r py-2 border-gray justify-between w-full">
										<span class="px-4"><?= $desv['desviacion'] ?></span>
										<div class="item--alt w-[10%]">
											<label class="label--check" for="desviacion_<?= $desv['id'] ?>">
												<!-- Check if the current incidencia ID exists in the selectedIds array -->
												<input type="checkbox" name="desviaciones[]" value="<?= $desv['id'] ?>" 
															class="checkbox_incidencia hidden" 
															id="desviacion_<?= $desv['id'] ?>"
												>
												<span class="checkbox-label-inc"><i class="fas fa-check"></i></span>
											</label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>


					</div>
				</div>


				<h2 class="uppercase text-center w-full text-xl lg:text-2xl text-gray ">OBSERVACIONES</h2>

				<div class="w-full text-white flex flex-col items-center justify-between gap-y-2 ">
					<textarea name="observacion" id="" rows="4" placeholder="Escribe un comentario" class="w-full border border-gray p-2 text-gray outline-none resize-none " ></textarea>
				</div>

				<div class="w-full flex items-center justify-end pt-8 ">
					<button class=" text-2xl pdf-button " type="submit" >
						<span>Guardar</span>
					</button>
				</div>

			</div>


			<input type="hidden" id="produccionId" name="produccionId">
			<input type="hidden" id="firma_produccion" name="firma_produccion">
			<input type="hidden" id="fecha_firma_produccion" name="fecha_firma_produccion">
		</form>

	</div>


<!-- Modal Pin -->
<div id="modal_pin" class="modal  ">
	<div class=" flex flex-col space-y-8 bg-white p-10 w-full md:w-[600px]">

		<div class="modal-content">
			<div class="modal-loading">
				<div><span class="loader"></span></div>
			</div>

			<div class="modal-header">
				<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>
				<h3>Ingresar PIN</h3>
			</div>
			<div class="modal-body">
				<input id="pinInput" type="text" class="w-full p-3 border rounded text-center text-2xl" readonly>

				<div class="grid grid-cols-3 gap-4 ">
					<button type="button" class="pin-btn">1</button>
					<button type="button" class="pin-btn">2</button>
					<button type="button" class="pin-btn">3</button>
					<button type="button" class="pin-btn">4</button>
					<button type="button" class="pin-btn">5</button>
					<button type="button" class="pin-btn">6</button>
					<button type="button" class="pin-btn">7</button>
					<button type="button" class="pin-btn">8</button>
					<button type="button" class="pin-btn">9</button>
					<div></div>
					<button type="button" class="pin-btn col-span-1">0</button>
					<div></div>
				</div>

				<p id="pin-response" class="text-warning text-lg"></p>

				<div class="flex w-full justify-between text-sm">
					<button id="clearPin" class=" flex space-x-4 shadow-bottom-right text-error border-2 border-error hover:bg-error hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >Limpiar</button>

					<button data-field="firma_produccion" data-area="produccionId" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">	Firmar</button>

				</div>
			</div>
		</div>
			
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

const allInputToNumber = document.querySelectorAll('.input--number');

allInputToNumber?.forEach(input => {
  input.addEventListener('input', e => {
    let value = e.target.value.replace(/[^0-9.]/g, '');

    // Allow only one dot
    const parts = value.split('.');
    if (parts.length > 2) {
      value = parts[0] + '.' + parts.slice(1).join('');
    }

    // Limit to 6 decimal places
    if (parts.length === 2) {
      value = parts[0] + '.' + parts[1].slice(0, 6);
    }

    input.value = value;
  });
});

document.getElementById("clearPin").addEventListener("click", function() {
	document.getElementById("pinInput").value = "";
	document.querySelector("#pin-response").innerText = "";
});

document.querySelectorAll(".pin-btn").forEach(button => {
	button.addEventListener("click", function() {
		document.getElementById("pinInput").value += this.textContent;
	});
});


document.querySelectorAll('input[type="text"]').forEach(input => {
  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault(); // Don't submit the form
    }
  });
});


let firma_produccion = document.querySelector('button[data-field="firma_produccion"]');
const produccionId_select = document.querySelector('#produccionId_select');
produccionId_select?.addEventListener('change', e => {
	// document.querySelector('#produccionId').value = e.target.value.trim();
	firma_produccion.setAttribute('data-user-id', e.target.value.trim());
	// console.log(firma_produccion)
	let btn_ingresar_pin = document.querySelector("#btn_ingresar_pin")
		if (!btn_ingresar_pin.classList.contains('modal-open-btn')) {
		btn_ingresar_pin.classList.add('modal-open-btn');
	}

});



const submitFirma = (e) => {
	let field = e.target.getAttribute('data-field');
	// let id = e.target.getAttribute('data-id');
	let userId = e.target.getAttribute('data-user-id');
	let area = e.target.getAttribute('data-area');
	let pin = document.querySelector('#pinInput');

	// console.log(field, id); return;

	e.target.disabled = true;

	const formData = new FormData();
	formData.append('field', field);

	if(userId !== undefined) {
		formData.append('userId', userId);
		formData.append('area', area);
	}

	if(pin.value !== undefined) {
		formData.append('pin', pin.value);
	}

	Service.show('.loading');

	// const formObject = {};
	// formData.forEach((value, key) => {
	// 	formObject[key] = value;
	// });
	// console.log(formObject);

	// return;

	Service.exec('post', `${root}/add_firma_reporte`, formData_header, formData)
	.then( r => {
		if(r.success) {
			// return;
			// let modal_active = document.querySelector("#modal_pin");
			// modal_active.classList.remove('modal_active');
			// modal_active.classList.add('hidden');
			// document.body.classList.remove('no-scroll');

			pin.value = '';
			document.querySelector("#pin-response").innerText = "";
			document.querySelector("#produccionId").value = r.produccionId;
			document.querySelector("#fecha_firma_produccion").value = r.fecha_firma;
			document.querySelector("#firma_produccion").value = r.firma;

			Modal.init('modal_pin').close();
			e.target.disabled = false;
			// initFirmas(r.inspeccionId); 
			Service.hide('.loading');
		} else {
			document.querySelector("#pin-response").innerText = r.message;
			e.target.disabled = false;
			Service.hide('.loading');
		}

	});
}

const allBtnFirmar = document.querySelectorAll('.btn_firmar');
allBtnFirmar?.forEach( btn => {
	btn.addEventListener('click', submitFirma);
});



function updateTotalPersonal() {
  const totalInput = document.querySelector('#total_personal');
  const operariosContainer = document.querySelector('#operarios-container');

  if (!totalInput || !operariosContainer) return;

  const count = operariosContainer.querySelectorAll('.operario-row').length;
  totalInput.value = count;
}


const initializeProductSearch = (inputEl, hiddenInputEl, endpointUrl) => {
  const listEl = createSuggestionList(inputEl);

  inputEl.addEventListener("input", async () => {
    const query = inputEl.value.trim();

    // Hide and skip short input
    if (query.length <= 4) {
      listEl.classList.add("hidden");
      return;
    }

    const results = await fetchProductSuggestions(endpointUrl, query);
    renderSuggestions(results, inputEl, hiddenInputEl, listEl);
  });

  // Hide on outside click
  document.addEventListener("click", (e) => {
    if (!inputEl.parentElement.contains(e.target)) {
      listEl.classList.add("hidden");
    }
  });
};

const fetchProductSuggestions = async (endpoint, search) => {
  try {

    const response = await axios.get(`${endpoint}/${search}`);
    return response.data || [];
  } catch (err) {
    console.error("Error fetching product suggestions:", err);
    return [];
  }
};



const createSuggestionList = (inputEl) => {
  let listEl = inputEl.parentElement.querySelector(".autocomplete-list");

  if (!listEl) {
    listEl = document.createElement("ul");
    listEl.className = "autocomplete-list absolute z-10 bg-white shadow border top-10 left-0 w-full max-h-44 overflow-y-auto hidden";
    inputEl.parentElement.appendChild(listEl);
  }

  return listEl;
};




const renderSuggestions = (products, inputEl, hiddenInputEl, listEl) => {
  listEl.innerHTML = "";

  if (products.length === 0) {
    listEl.classList.add("hidden");
    return;
  }

  products.forEach(product => {
    const li = document.createElement("li");
    li.className = "px-4 py-2 hover:bg-title-l hover:text-white cursor-pointer";
    li.textContent = product.descripcion;

    li.addEventListener("click", () => {
      inputEl.value = product.descripcion;
      document.querySelector('#form_incidencia input[name="linea"]').value = product.linea;
      document.querySelector('#form_incidencia input[name="codigo"]').value = product.codigo;
      hiddenInputEl.value = product.id; // Store selected product id
      listEl.classList.add("hidden");
    });

    listEl.appendChild(li);
  });

  listEl.classList.remove("hidden");
};




document.addEventListener("DOMContentLoaded", () => {
  const productoInput = document.getElementById("productoInput");
  const productoIdHidden = document.getElementById("productoIdHidden");

	console.log(productoInput);

  initializeProductSearch(productoInput, productoIdHidden, `${root}/search_productos`);
});


const operarios = <?= json_encode($operarios); ?>;  // Pass PHP array to JS

const operariosContainer = document.getElementById('operarios-container');
const addBtn = document.getElementById('add-operario');

document.addEventListener("DOMContentLoaded", () => {
  // Add the first row automatically on load
  // addOperarioRow();
});

addBtn.addEventListener('click', () => {
  addOperarioRow();
});

function addOperarioRow() {
  const row = document.createElement('div');
  row.className = 'operario-row flex w-full border-gray border-r relative';

  row.innerHTML = `
    <div class="col border-l border-b w-[75%] relative">
      <div class="flex items-center p-2 relative">
        <input type="text" class="operario-name-input w-full text-gray py-2 h-10" placeholder="Buscar operario...">
        <input type="hidden" name="operario_id[]">
        <ul class="autocomplete-list w-2/3 absolute z-50 bg-white border border-grayMid  top-12 left-2 max-h-40 overflow-auto hidden"></ul>
      </div>
    </div>
    <div class="col border-l border-b w-[25%]">
      <div class="p-2">
        <div class="quantity-selector"></div>
      </div>
    </div>
  `;

  operariosContainer.appendChild(row);

  // Attach autocomplete behavior
  initializeAutocomplete(row.querySelector('.operario-name-input'), row.querySelector('input[name="operario_id[]"]'), operarios);

  // Quantity selector
  initializeQuantitySelectorsIn(row, "operario_hours[]", "input_to_number-alt");

	updateTotalPersonal();
}



const initializeAutocomplete = (inputEl, hiddenInput, operarios) => {
  const listEl = inputEl.parentElement.querySelector(".autocomplete-list");

  inputEl.addEventListener("input", () => {
    const search = inputEl.value.toLowerCase();
    listEl.innerHTML = "";

    const matches = operarios.filter(op => 
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




function initializeQuantitySelectorsIn(container, inputName, inputClass) {
  const target = container.querySelector(".quantity-selector");
  if (!target) return;

  target.innerHTML = "";

  const decrementBtn = document.createElement("button");
  decrementBtn.className = "btn-decrement-alt";
  decrementBtn.textContent = "–";
  decrementBtn.type = "button";

  const input = document.createElement("input");
  input.name = inputName;
  input.className = inputClass;
  input.type = "text";
  input.value = "0.0";
  input.min = "0";
  input.max = "10";
  input.step = "0.5";
  input.readOnly = true;
  input.style.backgroundColor = 'hsl(189,5.3%,81.7%)';

  const incrementBtn = document.createElement("button");
  incrementBtn.className = "btn-increment-alt";
  incrementBtn.textContent = "+";
  incrementBtn.type = "button";

	const deleteBtn = document.createElement("button");
  deleteBtn.className = "btn-delete-alt";
  deleteBtn.innerHTML = '<i class="text-lg text-red fas fa-trash"></i>';
  deleteBtn.type = "button";
  deleteBtn.style.color = "red";
  deleteBtn.style.marginLeft = "28px";

  target.appendChild(input);
  target.appendChild(decrementBtn);
  target.appendChild(incrementBtn);
	target.appendChild(deleteBtn);

	const updateTotalHoras = () => {
    const totalInput = document.querySelector('#total_horas_extras');
    if (!totalInput) return;

    // Collect all inputs by class (if multiple rows exist)
    const allHourInputs = document.querySelectorAll(`input.${inputClass}`);
    let total = 0;

    allHourInputs.forEach(i => {
      const val = parseFloat(i.value);
      if (!isNaN(val)) {
        total += val;
      }
    });

    totalInput.value = total.toFixed(1);
  };

  decrementBtn.addEventListener("click", () => {
    let currentValue = parseFloat(input.value);
    if (currentValue > parseFloat(input.min)) {
      currentValue -= 0.5;
      input.value = currentValue.toFixed(1);
			 updateTotalHoras();
    }
  });

  incrementBtn.addEventListener("click", () => {
    let currentValue = parseFloat(input.value);
    if (currentValue < parseFloat(input.max)) {
      currentValue += 0.5;
      input.value = currentValue.toFixed(1);
			 updateTotalHoras();
    }
  });

  deleteBtn.addEventListener("click", () => {
    // Find the top-level row and remove it
    const row = container.closest(".operario-row");
    if (row) {
      row.remove();
      updateTotalHoras();
			updateTotalPersonal();
    }
  });

	  // Initial update
		updateTotalHoras();


}





Service.setLoading();

const form_incidencia = document.querySelector('#form_incidencia');
form_incidencia?.addEventListener('submit', e => {
	e.preventDefault();
	Service.stopSubmit(e.target, true);
	Service.show('.loading');

	const formData = new FormData(e.target);

	Service.exec('post', `/registros/incidencias`, formData_header, formData)
	.then(r => {
		if(r.success){
			Service.stopSubmit(e.target, false);
			form_incidencia.reset();

			window.location.href = `${root}/registros/lista`;
			// setTimeout(() => {
			// 	Service.hide('.loading');
			// 	Modal.init("modal_success").open();
			// }, 500)
		}
	});  

});

function updateTiempoEfectivo() {
  const muertoInput = document.querySelector('#total_tiempo_muerto');
  const efectivoInput = document.querySelector('#total_tiempo_efectivo');

  if (!muertoInput || !efectivoInput) return;

  const muerto = parseFloat(muertoInput.value);
  const baseTime = 8.0;

  const efectivo = baseTime - (isNaN(muerto) ? 0 : muerto);
  efectivoInput.value = efectivo.toFixed(1);
}


const initializeQuantityTiempo = (containerClass, inputName, inputClass) => {
  const containers = document.querySelectorAll(`.${containerClass}`);

  if (!containers.length) {
    console.error(`No containers with class "${containerClass}" found.`);
    return;
  }

  containers.forEach(container => {
    container.innerHTML = "";

    const decrementBtn = document.createElement("button");
    decrementBtn.className = "btn-decrement-alt";
    decrementBtn.textContent = "–";
    decrementBtn.type = "button";

    const input = document.createElement("input");
    input.name = inputName;
    input.className = inputClass;
    input.id = inputName;
    input.type = "text";
    input.value = "0.0";
    input.min = "0";
    input.max = "8";
		input.step = "0.5";  // Set the step size to 0.5
		input.readOnly = true;
		input.style.backgroundColor = 'hsl(189,5.3%,81.7%)';

		const incrementBtn = document.createElement("button");
		incrementBtn.className = "btn-increment-alt";
		incrementBtn.textContent = "+";
		incrementBtn.type = "button";


		container.appendChild(input);
		container.appendChild(decrementBtn);
		container.appendChild(incrementBtn);

		decrementBtn.addEventListener("click", () => {
			let currentValue = parseFloat(input.value); 
			if (currentValue > parseFloat(input.min)) {
				currentValue -= 0.5; 
				input.value = currentValue.toFixed(1);  
				updateTiempoEfectivo();

			}
		});

		incrementBtn.addEventListener("click", () => {
			let currentValue = parseFloat(input.value); 
			if (currentValue < parseFloat(input.max)) {
				currentValue += 0.5;  
				input.value = currentValue.toFixed(1);
				updateTiempoEfectivo();

			}
		});

		updateTiempoEfectivo();


  });
};


initializeQuantityTiempo("quantity-tiempo-muerto", "total_tiempo_muerto", "input_to_number-tiempo");


</script>
</body>
</html>
