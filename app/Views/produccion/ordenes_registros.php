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

  	<?php echo view('produccion/_partials/navbar'); ?>

		<!-- title group - title page -->
		<div class="relative text-title w-full mt-2 py-2 px-6 lg:px-10 flex items-center justify-center ">
			<h2 class="text-center font-semibold text-3xl "><?= esc($title_group) ?></h2>
			<a href="<?= base_url('produccion/ordenes_lista') ?>" class="px-1 absolute right-6 lg:right-10 top-1/2 -translate-y-1/2 hover:scale-110 transition-transform duration-100 ">
				<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
			</a>
		</div>

    <div class="relative text-title w-full py-2 px-6 lg:px-10 flex items-center justify-between ">
			<div class="w-full flex items-center justify-start xl:justify-center">
				<h2 class="text-center font-semibold w-fit text-2xl lg:text-3xl "><?= esc($title) ?></h2>
			</div>

			<div class=" flex gap-4 items-center absolute right-6 lg:right-10 top-1/2 -translate-y-1/2">

				<a href=<?= base_url('produccion/registro_diario/') . $num_orden ?> class="rounded flex items-center py-1 px-3 gap-x-1 text-icon border-2 border-icon hover:bg-icon hover:text-white   w-fit uppercase">
					<i class="fa fa-plus text-base"></i>
					<span>Nuevo Registro</span>
				</a>

			</div>
    </div>


    <div class="w-full text-sm text-gray  ">
      <div class="flex flex-col h-full" >


        <div class="w-full pl-10 pr-8 ">
					<div class="relative w-full text-sm h-[55vh] overflow-y-scroll mx-auto">
            <table id="tabla-produccion-registros">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Piezas Producidas</th>
                  <th>Articulo</th>
                  <th>Cod Articulo</th>
                  <th>Cajas Turno</th>
                  <th>Lote</th>
                  <th>Fecha Reporte</th>
                  <th>Acumulado</th>
                  <th>PZS Requeridas</th>
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
<div id="modal_create" class="modal modal-md">
	<div class="modal-content">

		<div class="modal-header">
			<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>
			<h3>Registrar Nuevo Producto</h3>
		</div>

		<form id="form_create" method="post" class="modal-body">
			<div class="w-full flex flex-col gap-y-6 ">

				<div class="relative flex flex-col md:flex-row items-center gap-x-8 ">
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Codigo</h5>
						<input type="text" name="codigo" placeholder="Codigo" >
					</div>
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Linea</h5>
						<select name="linea" class="text-center to_uppercase" >
							<option value="" disabled selected>Seleccionar...</option>

						</select>
					</div>
				</div>

				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Descripcion</h5>
					<input type="text" name="descripcion" placeholder="Descripcion" >
				</div>

				<div class="relative flex flex-col md:flex-row items-center gap-x-8 ">
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Peso o Volumen</h5>
						<input type="text" name="peso_volumen" placeholder="Peso o Volumen" >
					</div>
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Unidad Medida</h5>
						<input type="text" name="unidad_medida" placeholder="Unidad Medida" >
					</div>
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
<div id="modal_edit" class="modal modal-md">
	<div class="modal-content">

		<div class="modal-header">
			<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>
			<h3>Editar Producto</h3>
		</div>

		<form id="form_edit" method="post" class="modal-body">
			<div class="w-full flex flex-col gap-y-6 ">
				<div class="relative flex flex-col md:flex-row items-center gap-x-8 ">
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Codigo</h5>
						<input type="text" name="codigo" placeholder="Codigo" >
					</div>
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Linea</h5>
						<select id="linea"  name="linea" class="text-center to_uppercase" >
							<option value="" disabled selected>Seleccionar...</option>

						</select>
					</div>
				</div>

				<div class="relative flex flex-col ">
					<h5 class="text-center uppercase">Descripcion</h5>
					<input type="text" name="descripcion" placeholder="Descripcion" >
				</div>

				<div class="relative flex flex-col md:flex-row items-center gap-x-8 ">
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Peso o Volumen</h5>
						<input type="text" name="peso_volumen" placeholder="Peso o Volumen" >
					</div>
					<div class="relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Unidad Medida</h5>
						<input type="text" name="unidad_medida" placeholder="Unidad Medida" >
					</div>
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

		const formData = new FormData(e.target);

		let id = e.target.dataset.id;
		let modalId = "modal_create";

		if (id) {
			formData.append('id', id);
			modalId = "modal_edit";
		}

    Service.exec('post', `produccion/productos`, formData_header, formData)
    .then( r => {
      if(r.success){
				Modal.init(modalId).close();
				Service.stopSubmit(e.target, false);

				setTimeout(() => {
					Service.hide('.loading');
					Modal.init("modal_success").open();
				}, 500)

				loadProductos();

			}
    });
	}

	const form_create = document.querySelector('#form_create');
	form_create.addEventListener('submit', submitForm);

	const form_edit = document.querySelector('#form_edit');
	form_edit.addEventListener('submit', submitForm);


  const initRowBtn = () => {
    const allBtnEdit = document.querySelectorAll('#tabla-produccion-productos .btn_edit');
    allBtnEdit?.forEach( (btn, index) => {

      btn.addEventListener('click', e => {
				e.stopPropagation()
        let modal_id = btn.dataset.modal;
				Modal.init(modal_id).open();

				if (modal_id == 'modal_edit') {
					let form_edit = document.querySelector('#form_edit')
					form_edit.dataset.id = btn.dataset.id;
          let id = btn.dataset.id;

          Service.exec('get', `produccion/all_productos/${id}`)
          .then( r => {
            // console.log(r); return;
            form_edit.querySelector('[name="codigo"]').value = r.codigo;
            form_edit.querySelector('[name="descripcion"]').value = r.descripcion;
            form_edit.querySelector('[name="peso_volumen"]').value = r.peso_volumen;
            form_edit.querySelector('[name="unidad_medida"]').value = r.unidad_medida;
						setSelectedOption('#form_edit #linea', r.linea, 'string');

          });


				}
      });
    });

  }



const tbody = document.querySelector('#tabla-produccion-registros tbody');
const renderRows = (data) => {  

    tbody.innerHTML = "";
    // console.log(data); return;

    if (data.length > 0) {
      Service.hide('#row__empty');

      data.forEach(orden => {
        const row = document.createElement('tr');

        row.innerHTML =
          `
            <td>
              <span>${format_id(orden.id, 'id')}</span>
            </td>
						<td>
              <span>${orden.piezas_producidas}</span>
            </td>
						<td>
              <span>${orden.desc_articulo}</span>
            </td>
						<td>
              <span>${orden.num_articulo}</span>
            </td>
						<td>
              <span>${orden.cajas_turno}</span>
            </td>
						<td>
              <span>${orden.lote}</span>
            </td>
						<td>
              <span>${dateToStringAlt(orden.created_at)}</span>
            </td>
						<td>
              <span>${formatNumberMex(orden.piezas_acumuladas)}</span>
            </td>
						<td>
              <span>${orden.cantidad_plan}</span>
            </td>
            <td>
              <div class="row__actions ">
								<a href="${root}/produccion/registro_diariop/${orden.num_orden}/${orden.id}" class="btn_edit hover:text-icon pr-2" type="button"><i class="fas fa-pencil text-lg"></i>
								</a>
								<a href="${root}/produccion/print_reg_diario/${orden.num_orden}/${orden.id}" class=" hover:text-link" ><i class="fas fa-print text-lg"></i></a>
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

  const loadRows = () => {
    Service.hide('#row__empty');
    tbody.innerHTML = Service.loader();

    Service.exec('get', `all_reportes/<?= esc($num_orden) ?>`)
    .then(r => renderRows(r));  
  }

  loadRows();

</script>
</body>
</html>