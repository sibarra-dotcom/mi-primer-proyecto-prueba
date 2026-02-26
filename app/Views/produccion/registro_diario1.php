<?php echo view('_partials/header'); ?>

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

  <div class="relative flex flex-col gap-y-2 items-center justify-center font-titil mb-10">

  	<?php echo view('produccion/_partials/navbar_old'); ?>

    <div class="text-title w-full md:pt-4 md:px-16 p-2 flex items-center mb-2">
      <h2 class="text-center font-bold w-full text-2xl lg:text-3xl "><?= esc($title_group) ?></h2>
      <a href="<?= previous_url() ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
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
							<select id="produccionId_select" class="select_modal w-full text-gray" required>
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
							<!-- <select id="turno" name="turno" class="w-full py-1 text-gray text-center" required> -->
							<select id="turno" name="turnoId" class="w-full py-1 text-gray text-center" required>
								<option value="" disabled selected>Seleccionar ...</option>
								<?php foreach ($turnos as $turno): ?>
										<option value="<?= esc($turno['id']) ?>" data-value="<?= esc($turno['label']) ?>">
												<?= esc($turno['label']) ?>
										</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col border-l border-b w-[50%]">
							<input type="text" name="nro_orden" class="w-full input--code to_uppercase text-center h-8" required>
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
							<input type="text" name="linea" class="w-full to_uppercase text-center h-8" required>
						</div>
						<div class="relative col border-l border-b w-[64%]">
							<input type="text" id="productoInput" class="to_uppercase text-center h-8 w-full" placeholder="Buscar producto...">
							<input type="hidden" id="productoIdHidden" name="productoId" required>
						</div>
						<div class="col border-l border-b w-[18%]">
							<input type="text" name="codigo" required class="w-full to_uppercase text-center h-8">
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
							<select name="procesoId" class="w-full py-1 text-gray text-center " required>
							<?php foreach ($procesos as $proc): ?>
								<option value="<?= $proc['id']?>"><?= $proc['descripcion']?></option>
							<?php endforeach; ?>
							</select>
						</div>
						<div class="col border-l border-b w-[50%]">
							<select name="tipo_proceso" class="w-full w-full py-1 text-gray text-center " required>
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
							<input type="text" name="meta_piezas" class="w-full input--number to_uppercase text-center h-8" required>
						</div>
						<div class="col border-l border-b w-[64%]">
							<input type="text" name="meta_cantidad" class="input--number to_uppercase text-center h-8" readonly>
						</div>
						<div class="col border-l border-b w-[18%]">
							<input type="text" name="meta_medida" class="input--number to_uppercase text-center h-8" readonly>
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
							<input type="text" name="real_piezas" class="w-full input--number to_uppercase text-center h-8" required>
						</div>
						<div class="col border-l border-b w-[64%]">
							<input type="text" name="real_cantidad" class="input--number to_uppercase text-center h-8" readonly>
						</div>
						<div class="col border-l border-b w-[18%]">
							<input type="text" name="real_medida" class="input--number to_uppercase text-center h-8" readonly>
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
							<div class="w-full lg:w-1/2 flex items-center justify-center pt-2 mx-auto ">
								<div class="quantity-tiempo-muerto gap-4 lg:gap-6"></div>
							</div>
						</div>
						<div class="col border-l border-b w-[50%]">
							<div class="p-2">
								<input readonly name="total_tiempo_efectivo" id="total_tiempo_efectivo" class="w-full text-lg text-gray py-2 text-center">
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


			<input type="hidden" id="produccionId" name="produccionId" required>
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

<!-- Modal success Pin -->
<div id="modal_pin_success" class="modal  ">
	<div class=" flex flex-col space-y-8 bg-white p-10 w-full md:w-[600px]">

		<div class="modal-content">
			<div class="modal-body">
				
				<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>
				
				<div class="text-title flex flex-col justify-center items-center gap-y-6 py-24">
					<h3 class="text-4xl">¡Pin Correcto!</h3>
					<h2 class="text-center text-2xl"></h2>
				</div>

				<div class="flex w-full justify-center  ">
					<button data-dismiss="modal" class="modal-btn--cancel" type="button">
						ACEPTAR
					</button>
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

Service.setLoading();

const allInputCode = document.querySelectorAll('.input--code');

allInputCode?.forEach(input => {
  input.addEventListener('input', e => {
    let value = e.target.value.replace(/\D/g, '');

    if (value.length > 6) {
      value = value.slice(0, 6);
    }
    input.value = value;
  });
});

const allInputToNumber = document.querySelectorAll('.input--number');

allInputToNumber?.forEach(input => {
  input.addEventListener('input', e => {
    let value = e.target.value.replace(/[^0-9.]/g, '');

    const parts = value.split('.');
    if (parts.length > 2) {
      value = parts[0] + '.' + parts.slice(1).join('');
    }

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
      e.preventDefault();
    }
  });
});


let firma_produccion = document.querySelector('button[data-field="firma_produccion"]');
const produccionId_select = document.querySelector('#produccionId_select');
produccionId_select?.addEventListener('change', e => {
	firma_produccion.setAttribute('data-user-id', e.target.value.trim());
	firma_produccion.setAttribute('data-user-name', e.target.options[e.target.selectedIndex].text.trim());

	let btn_ingresar_pin = document.querySelector("#btn_ingresar_pin")
		if (!btn_ingresar_pin.classList.contains('modal-open-btn')) {
		btn_ingresar_pin.classList.add('modal-open-btn');
	}
});



const submitFirma = (e) => {
	let user_name = e.target.getAttribute('data-user-name');
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
		let modal_s = document.querySelector("#modal_pin_success");
		let subtitle = modal_s.querySelector('h2');
		subtitle.innerHTML = "";

		if(r.success) {

			subtitle.innerHTML = `${user_name}`;

			pin.value = '';
			document.querySelector("#pin-response").innerText = "";
			document.querySelector("#produccionId").value = r.produccionId;
			document.querySelector("#fecha_firma_produccion").value = r.fecha_firma;
			document.querySelector("#firma_produccion").value = r.firma;

			Modal.init('modal_pin').close();
			e.target.disabled = false;

			setTimeout(() => {
				Service.hide('.loading');
				Modal.init("modal_pin_success").open();
			}, 500);

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
    const response = await Service.exec('get', `${endpoint}/${search}`);
    return response || [];
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


function convertToKgOrLiters(value, unit) {
  const num = parseFloat(value);
  if (isNaN(num)) return null;

  switch (unit.toLowerCase()) {
    case "mg":
      return num / 1_000_000; // mg → kg
    case "g":
      return num / 1_000;     // g → kg
    case "ml":
      return num / 1_000;     // ml → L
    default:
      return null; // unknown unit
  }
}

function setupUnitCalculator(inputName, pesoVolumen, unidadMedida) {
  const pesoBase = convertToKgOrLiters(pesoVolumen, unidadMedida);

  const piezasInput = document.querySelector(`input[name="${inputName}_piezas"]`);
  const cantidadInput = document.querySelector(`input[name="${inputName}_cantidad"]`);
  const medidaInput = document.querySelector(`input[name="${inputName}_medida"]`);

  const medida = unidadMedida.toLowerCase() === "ml" ? "L" : "KG";
  medidaInput.value = medida;

	if (pesoBase === null) {
		cantidadInput.value = 0;
    console.warn("Invalid unit or peso value");
    return;
  }

  piezasInput.addEventListener("keyup", () => {
    const piezas = parseFloat(piezasInput.value);
    if (!isNaN(piezas)) {
      const totalCantidad = piezas * pesoBase;
      cantidadInput.value = totalCantidad.toFixed(4);
    } else {
      cantidadInput.value = "";
    }
  });
}



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
			let form = document.querySelector('#form_incidencia');

      inputEl.value = product.descripcion;
      form.querySelector('input[name="linea"]').value = product.linea;
      form.querySelector('input[name="codigo"]').value = product.codigo;

			setupUnitCalculator("meta", product.peso_volumen, product.unidad_medida)
			setupUnitCalculator("real", product.peso_volumen, product.unidad_medida)
		
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
  initializeProductSearch(productoInput, productoIdHidden, `${root}/search_productos`);
});


const operarios = <?= json_encode($operarios); ?>;

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
      <div class="relative flex items-center p-2 relative">
				<div class="is_invalid hidden absolute right-2 top-2 px-3 py-2 bg-error bg-opacity-80 text-white rounded-full"><i class="fas fa-x"></i></div>
				<div class="is_valid hidden absolute right-2 top-2 px-3 py-2 bg-success bg-opacity-80 text-white rounded-full"><i class="fas fa-check"></i></div>
        <input type="text" class="operario-name-input w-full text-gray py-2 h-10" placeholder="Buscar operario...">
        <input type="hidden" name="operario_id[]">
        <ul class="autocomplete-list w-2/3 absolute z-50 bg-white border border-grayMid  top-12 left-2 max-h-40 overflow-auto hidden"></ul>
      </div>
    </div>
    <div class="col border-l border-b w-[25%]">
      <div class="p-2">
        <div class="quantity-selector gap-4 lg:gap-6"></div>
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





// const initializeAutocomplete = (inputEl, hiddenInput, operarios) => {
// 	const turnoSelect = document.querySelector('#turno');
// 	console.log(turnoSelect)
// 	let filteredOperarios = operarios;

// 	if (turnoSelect && turnoSelect.value.trim() !== "") {
// 		const selectedTurno = turnoSelect.value.trim();
// 		filteredOperarios = operarios.filter(op => op.turno === selectedTurno);
// 	}

//   const listEl = inputEl.parentElement.querySelector(".autocomplete-list");

//   inputEl.addEventListener("input", () => {
//     const search = inputEl.value.toLowerCase();
//     listEl.innerHTML = "";

//     const matches = filteredOperarios.filter(op => 
//       (`${op.name} ${op.last_name}`).toLowerCase().includes(search)
//     );

//     if (matches.length === 0 || search.length < 2) {
//       listEl.classList.add("hidden");
//       return;
//     }

//     matches.forEach(op => {
//       const li = document.createElement("li");
//       li.className = "px-4 py-2 hover:bg-title-l hover:text-white cursor-pointer";
//       li.textContent = `${op.name} ${op.last_name}`;
//       li.addEventListener("click", () => {
//         inputEl.value = `${op.name} ${op.last_name}`;
//         hiddenInput.value = op.id;
//         listEl.classList.add("hidden");
//       });
//       listEl.appendChild(li);
//     });

//     listEl.classList.remove("hidden");
//   });

//   // Hide list when clicking outside
//   document.addEventListener("click", (e) => {
//     if (!inputEl.parentElement.contains(e.target)) {
//       listEl.classList.add("hidden");
//     }
//   });
// };




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
  input.max = "8";
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



const initializeAutocomplete = (inputEl, hiddenInput, operarios) => {
  const turnoSelect = document.querySelector('#turno');
  let filteredOperarios = operarios;

  if (turnoSelect && turnoSelect.value.trim() !== "") {
    const selectedTurno = turnoSelect.value.trim();

		const selectedOption = turnoSelect.querySelector(`option[value="${selectedTurno}"]`);
  	const selectedDataValue = selectedOption ? selectedOption.dataset.value : null;

    filteredOperarios = operarios.filter(op => op.turno === selectedDataValue);
  }

  const listEl = inputEl.parentElement.querySelector(".autocomplete-list");
  const validIcon = inputEl.parentElement.querySelector(".is_valid");
  const invalidIcon = inputEl.parentElement.querySelector(".is_invalid");

  // Helper function to toggle the validation icons
  const toggleValidationIcons = () => {
    if (hiddenInput.value) {
      validIcon.classList.remove("hidden");
      invalidIcon.classList.add("hidden");
    } else {
      validIcon.classList.add("hidden");
      invalidIcon.classList.remove("hidden");
    }
  };

  // Update validation state on input change
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
        hiddenInput.value = op.id; // Set the operario_id
        listEl.classList.add("hidden");

        toggleValidationIcons(); // Update the validation icons when an operario is selected
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

  // Initial validation state (to check if there is already a valid hidden input value)
  toggleValidationIcons();
};



const validateOperariosInputs = () => {
  let isValid = true; // Flag to track overall form validity

  // Loop through all input fields for operarios (those with the class `.operario-name-input`)
  const operarioInputs = document.querySelectorAll('.operario-name-input');
  operarioInputs.forEach(inputEl => {
    const hiddenInput = inputEl.parentElement.querySelector('input[type="hidden"]');
    
    // Check if the hidden input (operario_id) is empty (invalid)
    if (!hiddenInput.value) {
      isValid = false;
      
      // Add invalid class and show the invalid icon if not already done
      inputEl.classList.add('invalid'); // Optional: Add red border to the input itself
      const invalidIcon = inputEl.parentElement.querySelector('.is_invalid');
      const validIcon = inputEl.parentElement.querySelector('.is_valid');
      
      if (invalidIcon && validIcon) {
        invalidIcon.classList.remove('hidden');
        validIcon.classList.add('hidden');
      }
    } else {
      // Valid input: Ensure the valid icon is displayed and no invalid styles are applied
      inputEl.classList.remove('invalid');
      const invalidIcon = inputEl.parentElement.querySelector('.is_invalid');
      const validIcon = inputEl.parentElement.querySelector('.is_valid');
      
      if (invalidIcon && validIcon) {
        invalidIcon.classList.add('hidden');
        validIcon.classList.remove('hidden');
      }
    }
  });

  return isValid; // Return true if all inputs are valid, false otherwise
};



const form_incidencia = document.querySelector('#form_incidencia');
form_incidencia?.addEventListener('submit', e => {

	const isValid = validateOperariosInputs();

  // If validation fails, prevent form submission
  if (!isValid) {
    e.preventDefault();  // This stops the form from being submitted
    return;  // Optional: To stop any further code from running (cleaner code)
  }


	e.preventDefault();
	Service.stopSubmit(e.target, true);

	const formData = new FormData(e.target);
	let produccionId = e.target.querySelector('#produccionId');
	if(!produccionId.value) {
		e.target.querySelector('#produccionId_select').focus();
		window.scrollTo({top: 0, behavior: 'smooth'});
		Service.stopSubmit(e.target, false);
		return;
	}

	// const formObject = {};
	// formData.forEach((value, key) => {
	// 	formObject[key] = value;
	// });
	// console.log(formObject);
	// return;

	Service.show('.loading');

	Service.exec('post', `/produccion/registro_diario1	`, formData_header, formData)
	.then(r => {
		if(r.success){
			Service.stopSubmit(e.target, false);
			form_incidencia.reset();
			window.location.href = `${root}/produccion/lista`;
		}
	});  

});

let baseTime = 8.0;

document.addEventListener('DOMContentLoaded', () => {

  const turnoSelect = document.querySelector('#turno');
  const tiempoInputs = document.querySelectorAll('.input_to_number-tiempo');
  const totalTiempoInput = document.querySelector('#total_tiempo_efectivo');

  const turnoMaxMap = {
    'Matutino': 7.0,
    'Vespertino': 6.5,
    'Nocturno': 7.0
  };

  turnoSelect?.addEventListener('change', () => {

    // const turnoValue = turnoSelect.value;
    const selectedTurno = turnoSelect.value.trim();

		const selectedOption = turnoSelect.querySelector(`option[value="${selectedTurno}"]`);
  	const selectedDataValue = selectedOption ? selectedOption.dataset.value : null;

		for (const turnoKey in turnoMaxMap) {
			// if (turnoValue.includes(turnoKey)) {
			if (selectedDataValue.includes(turnoKey)) {
				baseTime = turnoMaxMap[turnoKey];

				tiempoInputs.forEach(input => {
					input.setAttribute('max', baseTime);
				});

				updateTiempoEfectivo();
				break;
			}
		}
  });
});

function updateTiempoEfectivo() {
  const muertoInput = document.querySelector('#total_tiempo_muerto');
  const efectivoInput = document.querySelector('#total_tiempo_efectivo');

  if (!muertoInput || !efectivoInput) return;

  const muerto = parseFloat(muertoInput.value);

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
