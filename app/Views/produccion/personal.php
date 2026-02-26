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

  <div class="relative flex flex-col gap-y-2 items-center justify-center font-titil mb-10">

  	<?php echo view('produccion/_partials/navbar'); ?>

		<div class="relative text-title w-full py-2 px-4 lg:px-10 flex items-center justify-between ">

			<div class="hidden lg:flex gap-4 items-center absolute left-6 lg:left-10 top-1/2 -translate-y-1/2">
				<a href="<?= previous_url() ?>" class="hover:scale-110 transition-transform duration-100 ">
					<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
				</a>
			</div>

			<div class="w-full flex items-center justify-start xl:justify-center">
				<h2 class="text-center font-semibold w-fit text-2xl lg:text-3xl "><?= esc($title) ?></h2>
			</div>

			<div class=" flex gap-4 items-center absolute right-4 lg:right-10 top-1/2 -translate-y-1/2">

				<button data-modal="modal_create" class="modal-open-btn btn btn-sm btn--primary" type="button">
					<i class="fa fa-plus text-xl"></i>
					<span>Agregar operario</span>
				</button>
			</div>
    </div>


    <div class="w-full text-sm text-gray  ">
      <div class="flex flex-col h-full" >


        <div class="w-full pl-10 pr-8 ">
					<div class="relative w-full text-sm h-[70vh] overflow-y-scroll ">
            <table id="tabla-personal-operarios">
              <thead>
                <tr>
                  <!-- <th>Id</th> -->
                  <th>Id Empleado</th>
                  <th>Nombres y Apellidos</th>
                  <th>Turno</th>
                  <th>Puesto</th>
                  <th>Fecha Registro</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody id="results_container"></tbody>

            </table>
            <div id="row__empty" class="row__empty">No hay datos</div>
            
          </div>
        </div>


      </div>
    </div>
  </div>




<!-- modal create -->
<div id="modal_create" class="modal modal-sm">
	<div class="modal-content">

		<div class="modal-header">
			<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>
			<h3>Registrar Nuevo Operario</h3>
		</div>

		<form id="form_create" method="post" class=" modal-body">
			<div class="w-full px-4 lg:px-6 flex flex-col gap-y-6 max-w-[65vh]">
				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Nombres</h5>
					<input type="text" name="name" class="input_modal" placeholder="Nombres" >
				</div>

				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Apellidos</h5>
					<input type="text" name="last_name" class="input_modal" placeholder="Apellidos" >
				</div>

				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Id Empleado</h5>
					<input type="text" name="empleadoId" class="input_modal" placeholder="Id Empleado" >
					<span class="msg_alert text-sm text-warning"></span>
				</div>

				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Turno</h5>
					<select name="turno" class="select_modal text-center" required>
						<option value="ARTES - Matutino">ARTES - Matutino</option>
						<option value="ARTES - Vespertino">ARTES - Vespertino</option>
						<option value="ARTES - Nocturno">ARTES - Nocturno</option>
						<option value="TONALÁ - Matutino">TONALÁ - Matutino</option>
					</select>
				</div>

				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Puesto</h5>
					<input type="text" name="puesto" class="input_modal" placeholder="Puesto" >
				</div>

			</div>

			<div class="form-row-submit ">
				<button class=" modal-btn--submit" type="submit" >
				GUARDAR
				</button>
			</div>

		</form>
	</div>
</div>

<!-- modal edit -->
<div id="modal_edit" class="modal modal-sm">
	<div class="modal-content">

		<div class="modal-header">
			<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>
			<h3>Editar Operario</h3>
		</div>

		<form id="form_edit" method="post" class="modal-body">
			<div class="w-full   flex flex-col gap-y-6 max-w-[65vh]">
				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Nombres</h5>
					<input type="text" class="input_modal" name="name" placeholder="Nombres" >
				</div>

				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Apellidos</h5>
					<input type="text" class="input_modal" name="last_name" placeholder="Apellidos" >
				</div>

				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Id Empleado</h5>
					<input type="text" class="input_modal" name="empleadoId" placeholder="Id Empleado" >
					<span class="msg_alert text-sm text-warning"></span>
				</div>

				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Turno</h5>
					<select id="turno" name="turno" class="select_modal text-center" required>
						<option value="" disabled selected>Seleccionar...</option>
						<option value="ARTES - Matutino">ARTES - Matutino</option>
						<option value="ARTES - Vespertino">ARTES - Vespertino</option>
						<option value="ARTES - Nocturno">ARTES - Nocturno</option>
						<option value="TONALÁ - Matutino">TONALÁ - Matutino</option>
					</select>
				</div>

				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Puesto</h5>
					<input type="text" class="input_modal" name="puesto" placeholder="Puesto" >
				</div>

			</div>

			<div class="form-row-submit ">
				<button class=" modal-btn--submit" type="submit" >
				Actualizar
				</button>
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


<script>
  // Modal.init('modal_create').open();

  Service.setLoading();

	const submitForm = (e) => {
		e.preventDefault();
		Service.stopSubmit(e.target, true);
		Service.show('.loading');

		let msg_alert = e.target.querySelector('.msg_alert');
		msg_alert.classList.add('hidden');

		const formData = new FormData(e.target);

		let id = e.target.dataset.id;
		let modalId = "modal_create";

		if (id) {
			formData.append('id', id);
			modalId = "modal_edit";
		}

    Service.exec('post', `produccion/personal`, formData_header, formData)
    .then( r => {
      if(r.success){
				e.target.reset();

				Modal.init(modalId).close();
				Service.stopSubmit(e.target, false);

				setTimeout(() => {
					Service.hide('.loading');
					Modal.init("modal_success").open();
				}, 500)

				loadPersonal();

			} else {

				Service.hide('.loading');
				Service.stopSubmit(e.target, false);


				const inputEmpleadoId = e.target.querySelector('[name="empleadoId"]');
				if (inputEmpleadoId && r.errors.empleadoId) {
					inputEmpleadoId.focus();
				}

				if (r.errors && r.errors.empleadoId) {
					msg_alert.classList.remove('hidden');
					msg_alert.textContent = r.errors.empleadoId;
				} 
			}
    });
	}

	const form_create = document.querySelector('#form_create');
	form_create.addEventListener('submit', submitForm);

	const form_edit = document.querySelector('#form_edit');
	form_edit.addEventListener('submit', submitForm);


  const initRowBtn = () => {
    const allBtnEdit = document.querySelectorAll('#tabla-personal-operarios .btn_edit');
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
					form_edit.dataset.id = btn.dataset.id;
          let id = btn.dataset.id;

          Service.exec('get', `produccion/all_personal/${id}`)
          .then( r => {
            // console.log(r); return;
            form_edit.querySelector('[name="name"]').value = r.name;
            form_edit.querySelector('[name="last_name"]').value = r.last_name;
            form_edit.querySelector('[name="puesto"]').value = r.puesto;
            form_edit.querySelector('[name="empleadoId"]').value = r.empleadoId;

						setSelectedOption('#form_edit #turno', r.turno, 'string');
          });


				}
      });
    });

  }



const results_container = document.querySelector('#results_container');
const renderRows = (data) => {  

    results_container.innerHTML = "";
    // console.log(data); return;

    if (data.length > 0) {
      Service.hide('#row__empty');


      data.forEach(user => {
        const row = document.createElement('tr');

        row.innerHTML =
          `
            <td>
              <span>${format_id(user.empleadoId, 'id')}</span>
            </td>
						<td>
              <span>${user.name} ${user.last_name}</span>
            </td>
						<td>
              <span>${user.turno}</span>
            </td>
						<td>
              <span>${user.puesto}</span>
            </td>

            <td>
              <span>${dateToString(user.created_at)}</span>
            </td>

            <td>
              <div class="row__actions ">
								<button data-id="${user.id}" class="btn_edit hover:text-icon pr-2" data-modal="modal_edit" type="button"><i class="fas fa-pencil text-lg"></i>
								</button>
              </div>
            </td>
          `
        results_container.appendChild(row);
      });

      initRowBtn();
    } else {
      initRowBtn();

      Service.show('#row__empty');
    }
  }

  const loadPersonal = () => {
    Service.hide('#row__empty');
    results_container.innerHTML = Service.loader();

    Service.exec('get', `produccion/all_personal`)
    .then(r => renderRows(r));  
  }

  loadPersonal();

</script>
</body>
</html>