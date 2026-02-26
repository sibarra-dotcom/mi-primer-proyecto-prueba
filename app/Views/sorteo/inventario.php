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

  	<?php echo view('sorteo/_partials/navbar'); ?>

    <div class="text-title w-full md:pt-4 md:px-16 p-2 flex items-center mb-2">
      <h2 class="text-center font-bold w-full text-2xl lg:text-3xl "><?= esc($title_group) ?></h2>
      <a href="<?= base_url('sorteo') ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>

		<div class="text-title w-full px-10 flex items-center justify-between mb-2">
			<div class="w-full lg:w-3/4 flex items-center justify-start lg:justify-center ">
				<!-- <h2 class="text-center font-bold w-fit text-2xl lg:text-3xl "><?= esc($title) ?></h2> -->
			</div>


				<button data-modal="modal_create" class="modal-open-btn rounded text-icon border-2 border-icon hover:bg-icon hover:text-white py-1 px-3 w-fit uppercase" type="button">
					<i class="fa fa-plus text-xl"></i>
					<span>Agregar Producto</span>
				</button>

    </div>



		<div class="w-full px-10 flex items-center gap-x-24 mb-2 ">

			<div class="flex gap-x-6 items-center w-fit lg:w-1/5 text-xl lg:text-2xl ">
				<span class="font-bold  text-title">Periodo</span>
				<select class="w-fit text-sm" id="periodo">
					<?php foreach ($periodos as $per): ?>
						<option value="<?= $per['id'] ?>" <?= ($periodo['id'] == $per['id']) ? 'selected' : '' ?>><?= $per['name'] ?></option>
					<?php endforeach; ?>
				</select>
			</div>


			<div class="font-bold flex gap-x-6 items-center w-fit text-xl lg:text-2xl border-grayMid border-b-2">
				<span class="text-title">Fecha Ingreso</span>
				<span class="moment-date text-gray text-lg"><?= esc($periodo['fecha_ingreso']) ?></span>
			</div>

			<div class="font-bold flex gap-x-6 items-center w-fit text-xl lg:text-2xl border-grayMid border-b-2">
				<span class="text-title">Fecha Corte</span>
				<span class="moment-date text-gray text-lg"><?= esc($periodo['fecha_corte']) ?></span>
			</div>
    </div>


    <div class="w-full text-sm text-gray  ">
      <div class="flex flex-col h-full" >


        <div class="w-full pl-10 pr-8 ">
					<div class="relative w-full text-sm h-[55vh] overflow-y-scroll ">
            <table id="tabla-sorteo-invent">
              <thead>
                <tr>
                  <th>Activo</th>
                  <th>Nombre</th>
                  <th>Codigo Producto</th>
                  <th>Descripcion</th>
                  <th>Lote</th>
                  <th>Stock Total</th>
                  <th>Entregado</th>
                  <th>Restante</th>
                  <th>Actions</th>
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


<!-- Modal create -->
<div id="modal_create" class="modal modal-lg">
	<div class="modal-content">

		<div class="modal-header">
			<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>
			<h3>Registrar Producto</h3>
		</div>

		<form id="form_create" method="post" class="modal-body" enctype='multipart/form-data'>
			<?= csrf_field() ?>


			<div class="w-full px-1 lg:px-2 flex flex-col gap-y-8 max-h-[65vh] overflow-y-scroll  ">


				<div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Nombre</h5>
						<input type="text" name="nombre" class="input_modal text-center to_uppercase" required >
					</div>

					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Codigo</h5>
						<input type="text" name="codigo" class="input_modal text-center to_uppercase" required >
					</div>

					<div class="relative flex flex-col w-full">
						<h5 class="text-center uppercase">Descripcion</h5>
						<input type="text" name="descripcion" class="input_modal text-center to_uppercase" required >
					</div>
					<div class="relative flex flex-col w-1/2">
						<h5 class="text-center uppercase">Stock</h5>
						<input type="text" name="stock" class="input_modal text-center to_uppercase" required >
					</div>
				</div>

				<div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Fecha ingreso</h5>
						<input type="text" class="input_modal to-date" data-name="fecha_ingreso" placeholder="dd-mm-yyyy">
					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Fecha vencimiento</h5>
						<input type="text" class="input_modal to-date" data-name="fecha_vencimiento" placeholder="dd-mm-yyyy" required >
					</div>


					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Lote</h5>
						<input type="text" name="lote" class="input_modal text-center to_uppercase" required >
					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Color</h5>

						<span class="color-picker">
							<label for="colorPicker">
								<input type="color" value="#1DB8CE" data-name="color" class="color_picker" required>
							</label>
						</span>

					</div>

				</div>


				<div class="flex flex-col p-2 w-full justify-center items-center">
					<img id="img_image" src="<?= base_url('img/no_img_alt.png')?>" class="hidden">
					<label for="image" class=" relative flex justify-center items-center bg-white w-44 h-44 cursor-pointer">
						<canvas class="h-44 w-44 rounded bg-white border-title border-2 drop-shadow "></canvas>
						<input name="archivo[]" id="image" type="file" class="input_preview hidden">    
					</label>
					<p>Imagen</p>
				</div>

				<input type="hidden" name="periodoId" value="<?= esc($periodo['id']) ?>">
				
				<div class="flex items-center justify-between">
				
					<div id="ci_error" class="hidden px-4 py-3 bg-error bg-opacity-10 rounded w-2/3 text-error"></div>


					<div class="flex w-full justify-end">
						<button class="btn btn-md btn--cta" type="submit" >
						AÑADIR
						</button>
					</div>

				</div>

			</div>


		</form>
	</div>
</div>

<!-- modal edit -->
<div id="modal_edit" class="modal modal-sm">
	<div class="modal-content">

		<div class="modal-header">
			<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>
			<h3>Editar Producto</h3>
		</div>

		<form id="form_edit" method="post" class="modal-body">
			<div class="w-full   flex flex-col gap-y-6 max-w-[65vh]">

				<div class="relative flex flex-col w-full ">
					<h5 class="text-center uppercase">Nombre</h5>
					<input type="text" name="nombre" class="input_modal text-center to_uppercase" required >
				</div>

				<div class="relative flex flex-col w-full ">
					<h5 class="text-center uppercase">Lote</h5>
					<input type="text" name="lote" class="input_modal text-center to_uppercase" required >
				</div>

				<div class="relative flex flex-col w-full">
					<h5 class="text-center uppercase">Stock</h5>
					<input type="text" name="stock" class="input_modal text-center to_uppercase" required >
				</div>

				<div class="relative flex flex-col w-full ">
					<h5 class="text-center uppercase">Fecha ingreso</h5>
					<input type="text" class="input_modal to-date" data-name="fecha_ingreso" placeholder="dd-mm-yyyy">
				</div>

				<div class="relative flex flex-col w-full ">
					<h5 class="text-center uppercase">Fecha vencimiento</h5>
					<input type="text" class="input_modal to-date" data-name="fecha_vencimiento" placeholder="dd-mm-yyyy" required >
				</div>

				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Color</h5>
					<span class="color-picker">
						<label for="colorPicker">
							<input type="color" value="#1DB8CE" data-name="color" class="color_picker" required>
						</label>
					</span>
				</div>

				<input type="hidden" name="periodoId">

			</div>

			<div class="form-row-submit ">
				<button class=" modal-btn--submit" type="submit" >
				Actualizar
				</button>
			</div>

		</form>
	</div>
</div>


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

const periodoSelect = document.querySelector('#periodo');
periodoSelect?.addEventListener('change', (e) => {
	let periodoId = document.querySelector('#form_create input[name="periodoId"]');
	// console.log(periodoId)
	periodoId.value = e.target.value;
	Service.show('.loading');
	loadInvent(e.target.value);
});



const handleColorInput = (inputElement) => {
    const hiddenInputElement = document.createElement('input');
    hiddenInputElement.type = 'hidden';
    hiddenInputElement.name = inputElement.getAttribute('data-name');
		
    inputElement.parentElement.appendChild(hiddenInputElement);

		inputElement.addEventListener('change', () => {
			hiddenInputElement.value = inputElement.value;
		});
}


const handleDateInput = (inputElement) => {
    const hiddenInputElement = document.createElement('input'); // Create the hidden input element
    hiddenInputElement.type = 'hidden';
    hiddenInputElement.name = inputElement.getAttribute('data-name'); // Use the data-name attribute for hidden input name
    inputElement.parentElement.appendChild(hiddenInputElement); // Append hidden input as a sibling of the visible input

    inputElement.addEventListener('input', (e) => {
        let userInput = e.target.value;  // Get the user's input

        // Remove any non-numeric characters (just in case)
        userInput = userInput.replace(/\D/g, '');

        // Limit the input to 8 characters (ddmmyyyy)
        if (userInput.length > 8) {
            userInput = userInput.substring(0, 8); // Truncate to 8 characters
        }

        // Add dashes after the day (dd) and month (mm)
        if (userInput.length >= 3) {
            userInput = userInput.replace(/(\d{2})(\d{1,2})/, '$1-$2'); // Insert dash after day
        }
        if (userInput.length >= 6) {
            userInput = userInput.replace(/(\d{2})-(\d{2})(\d{1,4})/, '$1-$2-$3'); // Insert dash after month
        }

        // Update the visible input field with dd-mm-yyyy format
        inputElement.value = userInput;

        // Validate the input date format (dd-mm-yyyy)
        if (/^\d{2}-\d{2}-\d{4}$/.test(userInput)) {
            const [day, month, year] = userInput.split('-');

            // Validate if the date is a valid calendar date (dd-mm-yyyy)
            const isValidDate = isValidDayMonthYear(day, month, year);

            // If it's a valid date, format and set the hidden input value as yyyy-mm-dd
            if (isValidDate) {
                const formattedDate = `${year}-${month}-${day}`;  // Convert to yyyy-mm-dd format
                hiddenInputElement.value = formattedDate;  // Set the hidden input with correct format
            } else {
                hiddenInputElement.value = '';  // Clear hidden input if date is invalid
            }
        } else {
            hiddenInputElement.value = '';  // Clear hidden input if format is incorrect
        }
    });
};

// Helper function to check if the date is valid (dd-mm-yyyy)
const isValidDayMonthYear = (day, month, year) => {
    // Ensure that day, month, and year are numbers
    const d = parseInt(day);
    const m = parseInt(month);
    const y = parseInt(year);

    // Check if the date is in a valid range (1–31 for day, 1–12 for month)
    if (isNaN(d) || isNaN(m) || isNaN(y)) return false;

    // Check if the month is between 1 and 12
    if (m < 1 || m > 12) return false;

    // Check if the day is within the valid range for the given month/year
    const daysInMonth = new Date(y, m, 0).getDate();
    if (d < 1 || d > daysInMonth) return false;

    return true; // Valid date
};

// Initialize the function for all visible input fields with class "to-date"
document.addEventListener('DOMContentLoaded', () => {
    const dateInputs = document.querySelectorAll('.to-date'); // Select all visible inputs with class "to-date"
    
    dateInputs.forEach((inputElement) => {
        handleDateInput(inputElement); // Initialize the date input handler for each element
    });

		    const colorInputs = document.querySelectorAll('.color_picker'); // Select all visible inputs with class "to-date"
    
    colorInputs.forEach((inputElement) => {
        handleColorInput(inputElement); // Initialize the date input handler for each element
    });

});




	const allMomentDate = document.querySelectorAll('.moment-date');
	allMomentDate?.forEach( p => {
		p.innerText = dateToStringAlt(p.innerText);
	});
				

  allInputPreview = document.querySelectorAll('input[type="file"].input_preview');
  allInputPreview?.forEach( input => {
    input.type = 'file'
    input.accept = 'image/*'

    let canvas = input.previousElementSibling;
    let ctx = canvas.getContext('2d');

    let img = document.querySelector(`#img_${input.id}`);
    ctx.drawImage(img, 0, 0, canvas.width, canvas.height)

    // let 'input[data-url="specific_value"]'
    input.addEventListener('change', event => {
      let file = event.target.files[0]
      let reader = new FileReader()

      reader.addEventListener('load', e => {
        let image = new Image()
        image.addEventListener('load', function() {
          ctx.drawImage(this, 0, 0, canvas.width, canvas.height);
        });

        image.src = e.target.result;
      })

      reader.readAsDataURL(file, "UTF-8")
    })

  }); 




	const submitForm = (e) => {
		e.preventDefault();
		Service.stopSubmit(e.target, true);
		Service.show('.loading');

		const formData = new FormData(e.target);

		let id = e.target.dataset.prod_id;
		let inv_id = e.target.dataset.inv_id;
		let modalId = "modal_create";

		if (id) {
			formData.append('id', id);
			formData.append('action', 'update');
			formData.append('inv_id', inv_id);
			modalId = "modal_edit";
		}

    Service.exec('post', `/sorteo/inventario`, formData_header, formData)
    .then( r => {
      if(r.success){
				

				Modal.init(modalId).close();
				Service.stopSubmit(e.target, false);
				loadInvent(r.periodoId);
				

				setTimeout(() => {
					Service.hide('.loading');
					Modal.init("modal_success").open();
					e.target.reset();
				}, 500)


			} else {

				let msg = '';
				let validationErrors = r.message;

        for (const field in validationErrors) {
            if (validationErrors.hasOwnProperty(field)) {
               	// msg += `${field} : ${validationErrors[field]} <br>`;
               	msg += `${validationErrors[field]} <br>`;
            }
        }

				let ci_error = document.querySelector('#ci_error')
				ci_error.innerHTML = msg;

				Service.show('#ci_error');

				Service.hide('.loading');
				Service.stopSubmit(e.target, false);

			}
    });
	}

	const form_create = document.querySelector('#form_create');
	form_create.addEventListener('submit', submitForm);

	const form_edit = document.querySelector('#form_edit');
	form_edit.addEventListener('submit', submitForm);



  const initRowBtn = () => {
    const allBtnEdit = document.querySelectorAll('#tabla-sorteo-invent .btn_edit');
    allBtnEdit?.forEach( (btn, index) => {

      btn.addEventListener('click', e => {
				e.stopPropagation()
        let modal_id = btn.dataset.modal;
				Modal.init(modal_id).open();
				let msg_alerts = document.querySelectorAll('.msg_alert');
				msg_alerts.forEach( span => {
					span.classList.add('hidden');
				});

				if (modal_id == 'modal_edit') {
					let form_edit = document.querySelector('#form_edit')
					form_edit.dataset.inv_id = btn.dataset.inv;
					form_edit.dataset.prod_id = btn.dataset.prod;
          let prod_id = btn.dataset.prod;

          Service.exec('get', `sorteo/all_inventario/${prod_id}`)
          .then( r => {
            // console.log(r); return;

            form_edit.querySelector('input[data-name="fecha_ingreso"]').value = dateToStringAlt(r.fecha_ingreso);
            form_edit.querySelector('input[data-name="fecha_vencimiento"]').value = dateToStringAlt(r.fecha_vencimiento);
            form_edit.querySelector('input[data-name="color"]').value = r.color;
            form_edit.querySelector('[name="periodoId"]').value = r.periodoId;
            form_edit.querySelector('[name="nombre"]').value = r.nombre;
            form_edit.querySelector('[name="lote"]').value = r.lote;
            form_edit.querySelector('[name="stock"]').value = r.stock;

          });


				}
      });
    });

  }


const setCheckUpdate = (e) => {
	const isChecked = e.target.checked;
	const inventarioId = e.target.getAttribute('data-id');

	Service.show('.loading');

	const formData = new FormData();
	formData.append('inventarioId', inventarioId);
	formData.append('activo', isChecked ? 1 : 0);

	Service.exec('post', `/sorteo/activar_producto`, formData_header, formData)
	.then( r => {
		if(r.success){
			Service.hide('.loading');
		}
	});
}

const results_container = document.querySelector('#tabla-sorteo-invent tbody');

const renderRows = (data) => {  
    results_container.innerHTML = "";

    if (data.length > 0) {
      Service.hide('#row__empty');


      data.forEach(prov => {
        const row = document.createElement('tr');

        row.innerHTML =
          `
            <td>
							<div class="col flex justify-center ">
								<div class="py-1">
									<label>
										<input type="checkbox" value="si" class="checkbox_sorteo hidden" ${(prov.activo == 1) ? "checked" : ''} data-id="${prov.inv_id}">
										<span class="checkbox-label-sorteo"><i class="fas fa-check"></i></span>
									</label>
								</div>
							</div>
            </td>
						<td>
              <span>${prov.nombre}</span>
            </td>
						<td>
              <span>${prov.codigo}</span>
            </td>
						<td>
              <span>${prov.descripcion}</span>
            </td>
						<td>
              <span>${prov.lote}</span>
            </td>
						<td>
              <span>${prov.stock_total}</span>
            </td>
						<td>
              <span>${prov.stock_entregado}</span>
            </td>
						<td>
              <span>${prov.stock_restante}</span>
            </td>
						</td>
              <div class="flex justify-center items-center gap-x-2">
								<button data-inv="${prov.inv_id}" data-prod="${prov.prod_id}" class="btn_edit hover:text-icon pr-2" data-modal="modal_edit" type="button"><i class="fas fa-pencil text-lg"></i>
								</button>
              </div>
            </td>
          `
        results_container.appendChild(row);


				const checkbox = row.querySelector('.checkbox_sorteo');
				checkbox.addEventListener('change', setCheckUpdate);

      });

			Service.hide('.loading');
      initRowBtn();
    } else {
			Service.hide('.loading');
      initRowBtn();

      Service.show('#row__empty');
    }
  }

  const loadInvent = (periodoId) => {
    Service.hide('#row__empty');
    results_container.innerHTML = Service.loader();

    Service.exec('get', `${root}/sorteo/lista_inventario/${periodoId}`)
    // Service.exec('get', `${root}/sorteo/lista_inventario/7`)
    .then(r => renderRows(r));  
  }

  loadInvent(<?= esc($periodo['id']) ?>);

</script>
</body>
</html>