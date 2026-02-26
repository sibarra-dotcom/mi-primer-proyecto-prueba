<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">

  <img src="<?= base_url('img/mantenimientoweb.svg') ?>" class="hidden lg:flex absolute bottom-0 left-0 ">
  <img src="<?= base_url('img/mantenimientomovil.svg') ?>" class="flex lg:hidden absolute bottom-0 left-0 ">

  <div class=" relative h-full flex flex-col items-center justify-center font-titil ">

    <?php echo view('cotizar/_partials/navbar'); ?>

    <div class="text-title w-full md:pt-4 md:pb-8 md:px-16 p-2 flex items-center ">
      <h2 class="text-center font-bold w-full text-2xl lg:text-3xl "><?= esc($title) ?></h2>
      <a href="<?= base_url('apps') ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>

    <div class="w-full text-sm text-gray  ">
      <div class="flex flex-col h-full" >

        <form id="form_search" class="search-row pb-8 px-4 lg:px-10 flex w-full  items-center " method="post">

					<div class="flex items-center lg:w-3/4 gap-x-8 ">
						<div class="relative flex flex-col w-64">
							<h5 class="text-center uppercase">Proveedor</h5>
							<input type="text" id="razon_social" name="razon_social" placeholder="Razon social " >
						</div>

						<div class="relative flex flex-col w-64">
							<h5 class="text-center uppercase">Contacto</h5>
							<input type="text" id="contacto" name="contacto" placeholder="" >
						</div>

						<div class="relative flex flex-col w-64">
							<h5 class="text-center uppercase">Direccion</h5>
							<input type="text" id="direccion" name="direccion" placeholder="" >
						</div>

						<div class="relative flex flex-col ">
							<h5 class="text-center uppercase">Pais</h5>
							<input type="text" id="pais" name="pais" placeholder="" >
						</div>

					</div>

					<div class="flex items-center gap-x-12 justify-end lg:w-1/4 ">
						<div class="flex flex-col w-24 items-center">
							<button data-modal="modal_add" class="btn_open_modal rounded text-icon border-2 border-icon hover:bg-icon hover:text-white py-1 px-3 w-fit" type="button"><i class="fa fa-plus text-xl"></i></button>
						</div>

						<button id="btn_search" type="submit"><span>BUSCAR</span></button>
					</div>
				</form>


        <div class="w-full pl-10 pr-8 ">
					<div class="relative w-full text-sm h-[65vh] overflow-y-scroll ">
            <table id="tabla-proveedores">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Razon social</th>
                  <th>Direccion</th>
                  <th>Pais</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody id="results_container"></tbody>

            </table>
            <div id="row__empty" class="row__empty">No hay coincidencias.</div>
            
          </div>
        </div>


      </div>
    </div>
  </div>


<!-- Modal Add Contacto -->

<div id="modal_contacto" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
  <form id="form_create_contacto" data-type="form" method="post" class=" flex flex-col space-y-4 bg-white border-2 border-icon p-10 w-full md:max-w-4xl ">

		<input type="hidden" name="create_contacto">

    <div class="relative flex w-full justify-center text-center ">
      <h3 class="text-gray text-xl uppercase">Añadir nuevo contacto</h3>
      <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
    </div>

		<div class="relative flex w-full text-primary">
      <h3 class="text-gray text-2xl ">Proveedor: <span id="nombre_proveedor"></span></h3>
    </div>

		<div class="w-full  ">
			<div class="relative w-full text-sm h-72 overflow-y-scroll ">
				<table id="tabla-contactos" >
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Correo</th>
							<th>Telefono</th>
							<th>Puesto</th>
						</tr>
					</thead>
					<tbody id="contactos_add_container">
						<tr>
							<td><input name="nombre[]" type="text" class="w-full to_uppercase" required placeholder="Nombre"></td>
							<td><input name="correo[]" type="text" class="w-full" required placeholder="Correo"></td>               
							<td><input name="telefono[]" type="text" class="w-full to_uppercase" required placeholder="Telefono"></td>
							<td><input name="puesto[]" type="text" class="w-full to_uppercase" required placeholder="Puesto"></td>
							<td>
									<button class="btn-delete text-gray hover:text-red px-2 mx-auto" type="button"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg></button>
								</td>
						</tr>
					</tbody>
				</table>
				</div>
			</div>

		<input type="hidden" name="proveedorId" >

		<button id="agregarFilaContacto" class="self-end rounded text-icon border-2 border-icon hover:bg-icon hover:text-white px-4 py-2 w-fit" type="button"><i class="fa fa-plus text-lg"></i></button>

		<div class="flex justify-end space-x-12 text-sm ">
			<button class="btn_close_modal flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
				CANCELAR
			</button>
			<button class="flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit">
				GUARDAR
			</button>
		</div>
		
  </form>
</div>


<!-- modal add proveedor -->
<div id="modal_add" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
	<form id="form_create_prov" data-type="form" method="post" class=" flex flex-col space-y-8 bg-white border-2 border-icon p-10 w-full md:w-[70%]">
		
		<input type="hidden" name="create_prov">
		<div class="relative flex w-full justify-center text-center  ">
			<h3 class="text-gray text-xl uppercase"> Agregar Nuevo Proveedor</h3>
			<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
		</div>

		<div class="flex items-center justify-between w-full">
			<div class="relative flex flex-col w-72">
				<h5 class="text-center uppercase">Proveedor</h5>
				<input type="text" id="razon_social" name="razon_social" class="input_modal" placeholder="Razon social " >
			</div>

			<div class="relative flex flex-col w-72">
				<h5 class="text-center uppercase">Direccion</h5>
				<input type="text" id="direccion" name="direccion" class="input_modal" placeholder="" >
			</div>

			<div class="relative flex flex-col ">
				<h5 class="text-center uppercase">Pais</h5>
				<input type="text" id="pais" name="pais" class="input_modal" placeholder="" >
			</div>

		</div>


		<div class="relative flex w-full justify-center text-center  ">
			<h3 class="text-gray text-xl uppercase"> Contactos</h3>
		</div>

		<div class="w-full  ">
			<div class="relative w-full text-sm h-44 overflow-y-scroll ">
				<table id="tabla-contactos">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Correo</th>
							<th>Telefono</th>
							<th>Puesto</th>
						</tr>
					</thead>
					<tbody id="contactos_container">
						<tr>
							<td><input name="nombre[]" type="text" class="w-full to_uppercase" required placeholder="Nombre"></td>
							<td><input name="correo[]" type="text" class="w-full " required placeholder="Correo"></td>               
							<td><input name="telefono[]" type="text" class="w-full to_uppercase" required placeholder="Telefono"></td>
							<td><input name="puesto[]" type="text" class="w-full to_uppercase" required placeholder="Puesto"></td>
							<td>
								<button class="btn-delete text-gray hover:text-red px-2 mx-auto" type="button"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
								<path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
							</svg></button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>


		<button id="agregarFila" class="self-end rounded text-icon border-2 border-icon hover:bg-icon hover:text-white px-4 py-2 w-fit" type="button"><i class="fa fa-plus text-lg"></i></button>


		<div class="flex justify-end space-x-12 text-sm ">
			<button class="btn_close_modal flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
				CANCELAR
			</button>
			<button class="flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit">
				GUARDAR
			</button>
		</div>

	</form>
</div>


<!-- Modal Add Contacto -->
<div id="modal_contacto11" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
  <form id="form_create_contacto1" data-type="form" method="post" class=" flex flex-col space-y-4 bg-white border-2 border-icon p-10 w-full md:max-w-4xl ">
		
		<input type="hidden" name="create_contacto">

    <div class="relative flex w-full justify-center text-center ">
      <h3 class="text-gray text-xl uppercase">Añadir nuevo contacto</h3>
      <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
    </div>

		<div class="relative flex w-full text-primary">
      <h3 class="text-gray text-2xl ">Proveedor: <span id="nombre_proveedor"></span></h3>
    </div>

		<div class="w-full  ">
			<div class="relative w-full text-sm h-72 overflow-y-scroll ">
				<table id="tabla-contactos" >
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Correo</th>
							<th>Telefono</th>
							<th>Puesto</th>
						</tr>
					</thead>
					<tbody id="contactos_add_container">
						<tr>
							<td><input name="nombre[]" type="text" class="input_alt to_uppercase" required placeholder="Nombre"></td>
							<td><input name="correo[]" type="text" class="input_alt" required placeholder="Correo"></td>               
							<td><input name="telefono[]" type="text" class="input_alt to_uppercase" required placeholder="Telefono"></td>
							<td><input name="puesto[]" type="text" class="input_alt to_uppercase" required placeholder="Puesto"></td>
							<td>
									<button class="btn-delete text-gray hover:text-red px-2 mx-auto" type="button"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg></button>
								</td>
						</tr>
					</tbody>
				</table>
				</div>
			</div>

		<input type="hidden" name="proveedorId" >

		<button id="agregarFilaContacto" class="self-end rounded text-icon border-2 border-icon hover:bg-icon hover:text-white px-4 py-2 w-fit" type="button"><i class="fa fa-plus text-lg"></i></button>

		<div class="flex justify-end space-x-12 text-sm ">
			<button class="btn_close_modal flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
				CANCELAR
			</button>
			<button class="flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit">
				GUARDAR
			</button>
		</div>
		
  </form>
</div>


<!-- modal Edit -->
<div id="modal_edit" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
	<form id="form_edit_prov" method="post" class=" flex flex-col space-y-8 bg-white border-2 border-icon p-10 w-full md:w-[70%]">

		<input type="hidden" name="proveedorId" >

		<div class="relative flex w-full justify-center text-center  ">
			<h3 class="text-gray text-xl uppercase"> Editar Proveedor</h3>
			<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
		</div>

		<div class="flex items-center justify-between w-full">
			<div class="relative flex flex-col w-72">
				<h5 class="text-center uppercase">Proveedor</h5>
				<input type="text" id="razon_social" name="razon_social" class="input_modal" placeholder="Razon social " >
			</div>


			<div class="relative flex flex-col w-72">
				<h5 class="text-center uppercase">Direccion</h5>
				<input type="text" id="direccion" name="direccion" class="input_modal" placeholder="" >
			</div>

			<div class="relative flex flex-col ">
				<h5 class="text-center uppercase">Pais</h5>
				<input type="text" id="pais" name="pais" class="input_modal" placeholder="" >
			</div>

		</div>


		<div class="relative flex w-full justify-center text-center  ">
			<h3 class="text-gray text-xl uppercase"> Contactos</h3>
		</div>

		<div class="w-full  ">
			<div class="relative w-full text-sm h-44 overflow-y-scroll ">
				<table id="tabla-contactos" >
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Correo</th>
							<th>Telefono</th>
							<th>Puesto</th>
						</tr>
					</thead>
					<tbody id="contactos_list_container">
					</tbody>
				</table>
				</div>
			</div>


		<div class="flex justify-end space-x-12 text-sm ">
			<button class="btn_close_modal flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
				CANCELAR
			</button>
			<button name="edit_prov" class="flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit">
				ACTUALIZAR
			</button>
		</div>

	</form>
</div>


<!-- Modal Details -->
<div id="modal_details" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
	<form id="form_details_prov" method="post" class=" flex flex-col space-y-8 bg-white border-2 border-icon p-10 w-full md:w-[70%]">

		<input type="hidden" name="proveedorId" >

		<div class="relative flex w-full justify-center text-center  ">
			<h3 class="text-gray text-xl uppercase"> Detalles Proveedor</h3>
			<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
		</div>

		<div class="flex items-center justify-between w-full">
			<div class="relative flex flex-col w-72">
				<h5 class="text-center uppercase">Proveedor</h5>
				<input type="text" id="razon_social" name="razon_social" readonly placeholder="Razon social " >
			</div>


			<div class="relative flex flex-col w-72">
				<h5 class="text-center uppercase">Direccion</h5>
				<input type="text" id="direccion" name="direccion" readonly placeholder="" >
			</div>

			<div class="relative flex flex-col ">
				<h5 class="text-center uppercase">Pais</h5>
				<input type="text" id="pais" name="pais" readonly placeholder="" >
			</div>

		</div>

		<div class="relative flex w-full justify-center text-center  ">
			<h3 class="text-gray text-xl uppercase"> Contactos</h3>
		</div>

		<div class="w-full  ">
			<div class="relative w-full text-sm h-72 overflow-y-scroll ">
				<table id="tabla-contactos" >
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Correo</th>
							<th>Telefono</th>
							<th>Puesto</th>
						</tr>
					</thead>
					<tbody id="contactos_list_details">
					</tbody>
				</table>
				</div>
			</div>

	</form>
</div>

<!-- Modal Delete -->
<div id="modal_delete" class="hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
  <div class=" flex flex-col items-center justify-between space-y-8 bg-white border-2 border-icon p-10 w-full md:w-[700px] h-96 ">
    
    <div class=" relative flex w-full justify-center text-center  ">
      <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
    </div>

    <h3 class="text-title text-4xl ">¿Eliminar Permanentemente?</h3>

    <div class="flex justify-center space-x-12 text-sm ">
      <button class="btn_close_modal flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
        CANCELAR
      </button>
      <button id="btn_delete" class=" flex space-x-4 shadow-bottom-right text-error border-2 border-error hover:bg-error hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
        ELIMINAR
      </button>
    </div>

  </div>
</div>




<?php echo view('_partials/_modal_msg_js'); ?>

<script>
  Service.setLoading();

  document.addEventListener('DOMContentLoaded', () => {
    const btn_search = document.querySelector("#btn_search");
    const svgIcon = document.createElement("span");
    svgIcon.innerHTML = getIcon('search');
    btn_search.prepend(svgIcon);
  });

  const btn_delete = document.querySelector('#btn_delete');
  btn_delete?.addEventListener('click', (e) => {
		Service.stopSubmit(e.target, true);

    let provId = e.target.getAttribute('data-id');

    Service.exec('delete', `/delete_proveedor/${provId}`)
    .then( r => {

      let button = document.querySelector(`button[data-modal="modal_delete"][data-id="${r.provId}"]`);
      let row = button.parentElement.parentElement.closest('tr');
      row.remove();

			Service.stopSubmit(e.target, false);

      let modal = e.target.closest("#modal_delete");
      modal.classList.add('hidden');
      modal.classList.remove('modal_active');
    });
  });



window.addEventListener('click', (event) => {
  let modal_active = document.querySelector('.modal_active');
  if (event.target === modal_active) {
    modal_active.classList.remove('modal_active');
    modal_active.classList.add('hidden');
  }
});

const initBtnDelete = () => {
	const delBtn = document.querySelectorAll('.btn-delete');
    delBtn?.forEach( btn => {
      btn.addEventListener('click', (e) => {
        e.currentTarget.parentNode.parentNode.remove();
      });
    });
}

const initRowBtn = () => {
	const allBtnOpen = document.querySelectorAll('.btn_open_modal');
	allBtnOpen?.forEach( (btn, index) => {

		btn.addEventListener('click', e => {
			e.stopPropagation()
			// console.log(e.currentTarget)
			let modal_id = e.currentTarget.getAttribute('data-modal');
			let modal = document.querySelector(`#${modal_id}`);

			modal.classList.add('modal_active');
			modal.classList.remove('hidden');

			if (modal_id == 'modal_details') {
				let id = e.currentTarget.getAttribute('data-id');
				let form_details = document.querySelector('#form_details_prov');
				let contact_list_det = document.querySelector('#contactos_list_details')

				Service.exec('get', `/get_proveedor_contacto`, { params: { prov_id: id } })
				.then( r => {

					form_details.querySelector('[name="proveedorId"]').value = r.proveedor.id;
					form_details.querySelector('[name="razon_social"]').value = r.proveedor.razon_social;
					form_details.querySelector('[name="direccion"]').value = r.proveedor.direccion;
					form_details.querySelector('[name="pais"]').value = r.proveedor.pais;

					contact_list_det.innerHTML = "";

					if (r.contactos.length > 0) {

						r.contactos.forEach(contacto => {
							const row = document.createElement('tr');

							row.innerHTML =
								`
									<td>
										<span>${contacto.nombre}</span>
									</td>
									<td>
										<span>${contacto.correo}</span>
									</td>
									<td>
										<span>${contacto.telefono}</span>
									</td>
									<td>
										<span>${contacto.puesto}</span>
									</td>
								`
							contact_list_det.appendChild(row);
						});

					} 
				});

			} else if (modal_id == 'modal_edit') {
				let id = e.currentTarget.getAttribute('data-id');
				let form_edit = document.querySelector('#form_edit_prov');
				let contact_list = document.querySelector('#contactos_list_container')

				Service.exec('get', `/get_proveedor_contacto`, { params: { prov_id: id } })
				.then( r => {

					form_edit.querySelector('[name="proveedorId"]').value = r.proveedor.id;
					form_edit.querySelector('[name="razon_social"]').value = r.proveedor.razon_social;
					form_edit.querySelector('[name="direccion"]').value = r.proveedor.direccion;
					form_edit.querySelector('[name="pais"]').value = r.proveedor.pais;

					contact_list.innerHTML = "";

					if (r.contactos.length > 0) {

						r.contactos.forEach(contacto => {
							const row = document.createElement('tr');

							row.innerHTML =
								`
									<td>
										<span>${contacto.nombre}</span>
									</td>
									<td>
										<span>${contacto.correo}</span>
									</td>
									<td>
										<span>${contacto.telefono}</span>
									</td>
									<td>
										<span>${contacto.puesto}</span>
									</td>
								`
							contact_list.appendChild(row);
						});

					} 

				});

			} else if (modal_id == 'modal_delete') {
				let id = e.currentTarget.getAttribute('data-id');

				const btn_delete = document.querySelector('#btn_delete');
				btn_delete.setAttribute('data-id', id);
				// console.log(btn_delete)

			} else if (modal_id == 'modal_contacto1') {

				let id = e.currentTarget.getAttribute('data-id');
				let archivos_container = document.querySelector('#archivos_container');
				archivos_container.innerHTML = Service.loader();

				Service.exec('get', `/get_adjuntos`, { params: { cotiz_id: id } })
				.then( r => {
					// console.log(r); return;
					archivos_container.innerHTML = '';
					if (r.length > 0) {
						r.forEach(adj => {
							const div = document.createElement('div');
							div.className = 'row-adjunto';
							div.innerHTML = `
								<div class="w-44 p-2">${adj.name} ${adj.last_name}</div>
								<div class="w-28 p-2 text-gray ">${dateToString(adj.fecha)}</div>
								<div class="p-2 flex items-center space-x-2 text-link">
									<i class="fas fa-paperclip"></i>
									<a href="${root}/files/download?path=${adj.archivo}" target="_blank" class="underline ">${getEncodedFileName(adj.archivo)}</a>
								</div>
							`;
							archivos_container.appendChild(div);
						});

					} else {
						const div = document.createElement('div');
						div.className = 'row-adjunto';
						div.innerHTML = Service.empty('No se encontraron archivos.');
						archivos_container.appendChild(div);
					}
				});

			} else if (modal_id == 'modal_contacto') {
				
				let id = e.currentTarget.getAttribute('data-id');
				let razon = e.currentTarget.getAttribute('data-razon');
				let form = document.querySelector('#form_create_contacto');
				form.querySelector('input[name="proveedorId"]').value = id;
				form.querySelector("#nombre_proveedor").innerText = razon;

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
			}
		});
	});
}

	initRowBtn();

	const btnAddRow = document.getElementById('agregarFila');
  btnAddRow?.addEventListener('click', () => {
    const nuevaFila = 
    `
    <tr>
			<td><input name="nombre[]" type="text" class="w-full to_uppercase" required placeholder="Nombre"></td>
			<td><input name="correo[]" type="text" class="w-full" required placeholder="Correo"></td>               
			<td><input name="telefono[]" type="text" class="w-full to_uppercase" required placeholder="Telefono"></td>
			<td><input name="puesto[]" type="text" class="w-full to_uppercase" required placeholder="Puesto"></td>
      <td>
        <button class="btn-delete text-gray hover:text-red px-2 mx-auto" type="button">${getIcon('delete')}</button>
      </td>
    </tr>
    `;
    document.getElementById('contactos_container').insertAdjacentHTML('beforeend', nuevaFila);
		initBtnDelete();

  });

	const btnAddContacto = document.getElementById('agregarFilaContacto');
  btnAddContacto?.addEventListener('click', () => {
    const nuevaFila = 
    `
    <tr>
			<td><input name="nombre[]" type="text" class="w-full to_uppercase" required placeholder="Nombre"></td>
			<td><input name="correo[]" type="text" class="w-full" required placeholder="Correo"></td>               
			<td><input name="telefono[]" type="text" class="w-full to_uppercase" required placeholder="Telefono"></td>
			<td><input name="puesto[]" type="text" class="w-full to_uppercase" required placeholder="Puesto"></td>
      <td>
        <button class="btn-delete text-gray hover:text-red px-2 mx-auto" type="button">${getIcon('delete')}</button>
      </td>
    </tr>
    `;
    document.getElementById('contactos_add_container').insertAdjacentHTML('beforeend', nuevaFila);
		initBtnDelete();
  });

	initBtnDelete();



const results_container = document.querySelector('#results_container');

const form_search = document.querySelector('#form_search');
form_search?.addEventListener('submit', e => {
	e.preventDefault();
	// Service.show('.loading');

	Service.hide('#row__empty');
	Service.stopSubmit(e.target, true);

	results_container.innerHTML = Service.loader();

	const formData = new FormData(e.target);

	Service.exec('post', `/search_proveedor`, formData_header, formData)
	.then(r => {
		renderProv(r);
		Service.stopSubmit(e.target, false);
	});  
});



const allForms = document.querySelectorAll('form[data-type="form"]');
console.log(allForms)
allForms.forEach( form => {
	form.addEventListener('submit', e => {
		e.preventDefault();
		Service.show('.loading');
		Service.stopSubmit(e.target, true);

		const formData = new FormData(e.target);

		// const formDataObj = {};
		// formData.forEach((value, key) => {
		// 	formDataObj[key] = value;
		// });

		// console.log(formDataObj);
		// return;


		Service.exec('post', `${root}/proveedores`, formData_header, formData)
		.then(r => {
			if(r){

					Service.hide('.loading');

					let msg = document.querySelector(`#msg_${r.status}`);
					console.log(msg)
					msg.classList.add('modal_active');
					msg.classList.remove('hidden');

					let btnClose = e.target.querySelectorAll('.btn_close_modal')[0];
					btnClose.click();

					e.target.reset();
					Service.stopSubmit(e.target, false);

					loadAllProveedores();
			}

		});  

	});
});



const renderProv = (data) => {  

    results_container.innerHTML = "";
    // console.log(data); return;

    if (data.length > 0) {

      data.forEach(prov => {
        const row = document.createElement('tr');

        row.innerHTML =
          `
            <td>
              <span>${format_id(prov.id, 'id')}</span>
            </td>
            <td>
              <span>${prov.razon_social}</span>
            </td>
            <td>
              <span>${prov.direccion}</span>
            </td>
            <td>
              <span>${prov.pais}</span>
            </td>

            <td>
              <div class="row__actions ">
								<button data-modal="modal_contacto" data-razon="${prov.razon_social}" data-id="${prov.id}" class="btn_open_modal hover:text-icon pr-2" type="button">
									<i class="fas fa-plus text-lg"></i>
									<i class="fas fa-user text-lg"></i>
								</button>
								<button data-modal="modal_details" data-id="${prov.id}" class="btn_open_modal hover:text-icon pr-2" type="button"><i class="fas fa-eye text-lg"></i>
								</button>
                <button data-modal="modal_edit" data-id="${prov.id}" class="btn_open_modal hover:text-blue pr-2" type="button">${getIcon('edit')}</button>
								<button data-modal="modal_delete" data-id="${prov.id}" class="btn_open_modal hover:text-red " type="button">${getIcon('delete')}</button>

              </div>
            </td>
          `
        results_container.appendChild(row);
      });

      initRowBtn();
    } else {
      Service.show('#row__empty');
    }
  }

  const loadAllProveedores = () => {
    Service.hide('#row__empty');
    results_container.innerHTML = Service.loader();

    Service.exec('get', `/all_proveedores`)
    .then(r => renderProv(r));  
  }

  loadAllProveedores();

</script>
</body>
</html>