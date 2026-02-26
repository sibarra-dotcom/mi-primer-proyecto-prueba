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

  <div class=" relative h-full pb-16 flex flex-col gap-y-8 items-center justify-center font-titil ">

  	<?php echo view('produccion/_partials/navbar_old'); ?>

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
					<span>Agregar Proceso</span>
				</button>
			</div>
    </div>

	<!-- Main content -->
    <div class="w-full text-sm text-gray  ">
      <div class="flex flex-col h-full" >


        <div class="w-full pl-10 pr-8 ">
					<div class="relative w-2/3 text-sm h-[70vh] overflow-y-scroll mx-auto">
            <table id="tabla-produccion-procesos">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Descripcion</th>
                  <th>Planta</th>
                  <th>Acciones</th>
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




<!-- modal create -->
<div id="modal_create" class="modal modal-sm">
	<div class="modal-content">

		<div class="modal-header">
			<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>
			<h3>Registrar Nuevo Proceso</h3>
		</div>

		<form id="form_create" method="post" class="modal-body">
			<div class="w-full px-4 lg:px-6 flex flex-col gap-y-6 max-w-[65vh]">
				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Descripcion</h5>
					<input type="text" class="input_modal" name="descripcion" placeholder="Descripcion" >
				</div>

				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Turno</h5>
					<select class="select_modal" name="planta" class="w-full text-gray text-center" required>
						<option value="" disabled selected>Seleccionar...</option>
						<option value="ARTES">ARTES</option>
						<option value="TONALA">TONALA</option>
					</select>
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
			<h3>Editar Proceso</h3>
		</div>

		<form id="form_edit" method="post" class="modal-body">
			<div class="w-full   flex flex-col gap-y-6 max-w-[65vh]">
				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Descripcion</h5>
					<input type="text" class="input_modal" name="descripcion" placeholder="Descripcion" >
				</div>
				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Turno</h5>
					<select id="planta" class="select_modal" name="planta" class="w-full text-gray text-center" required>
						<option value="" disabled selected>Seleccionar...</option>
						<option value="ARTES">ARTES</option>
						<option value="TONALA">TONALA</option>
					</select>
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

  Service.setLoading();

	const submitForm = (e) => {
		e.preventDefault();
		Service.stopSubmit(e.target, true);
		Service.show('.loading');

		const formData = new FormData(e.target);

		let id = e.target.dataset.id;
		let modalId = "modal_create";

		if (id) {
			formData.append('id', id);
			modalId = "modal_edit";
		}

    Service.exec('post', `produccion/procesos`, formData_header, formData)
    .then( r => {
      if(r.success){
				Modal.init(modalId).close();
				Service.stopSubmit(e.target, false);

				setTimeout(() => {
					Service.hide('.loading');
					Modal.init("modal_success").open();
				}, 500)

				loadProcesos();

			}
    });
	}

	const form_create = document.querySelector('#form_create');
	form_create.addEventListener('submit', submitForm);

	const form_edit = document.querySelector('#form_edit');
	form_edit.addEventListener('submit', submitForm);


  const initRowBtn = () => {
    const allBtnEdit = document.querySelectorAll('#tabla-produccion-procesos .btn_edit');
    allBtnEdit?.forEach( (btn, index) => {

      btn.addEventListener('click', e => {
				e.stopPropagation()
        let modal_id = btn.dataset.modal;
				Modal.init(modal_id).open();

				if (modal_id == 'modal_edit') {
					let form_edit = document.querySelector('#form_edit')
					form_edit.dataset.id = btn.dataset.id;
          let id = btn.dataset.id;

          Service.exec('get', `produccion/all_procesos/${id}`)
          .then( r => {
            // console.log(r); return;
            form_edit.querySelector('[name="descripcion"]').value = r.descripcion;
						setSelectedOption('#form_edit #planta', r.planta, 'string');
          });


				}
      });
    });

  }



const tbody = document.querySelector('#tabla-produccion-procesos tbody');
const renderRows = (data) => {  

    tbody.innerHTML = "";
    // console.log(data); return;

    if (data.length > 0) {
      Service.hide('#row__empty');


      data.forEach(proc => {
        const row = document.createElement('tr');

        row.innerHTML =
          `
            <td>
              <span>${format_id(proc.id, 'id')}</span>
            </td>
						<td>
              <span>${proc.descripcion}</span>
            </td>
						<td>
              <span>${proc.planta}</span>
            </td>
            <td>
              <div class="row__actions ">
								<button data-id="${proc.id}" class="btn_edit hover:text-icon pr-2" data-modal="modal_edit" type="button"><i class="fas fa-pencil text-lg"></i>
								</button>
              </div>
            </td>
          `
        tbody.appendChild(row);
      });

      initRowBtn();
    } else {
      initRowBtn();

      Service.show('#row__empty');
    }
  }

  const loadProcesos = () => {
    Service.hide('#row__empty');
    tbody.innerHTML = Service.loader();

    Service.exec('get', `produccion/all_procesos`)
    .then(r => renderRows(r));  
  }

  loadProcesos();

</script>
</body>
</html>