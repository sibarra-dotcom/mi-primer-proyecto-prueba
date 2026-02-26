<?php echo view('_partials/header'); ?>

  <link rel="stylesheet" href="<?= base_url('_partials/mtickets.css') ?>">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
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

      <?php echo view('mantenimiento/_partials/navbar'); ?>

      <a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer">Cerrar Sesión</a>

      <div class="text-title w-full md:pt-4 md:px-16 p-2 flex items-center ">
        <h2 class="text-center font-bold w-full text-2xl lg:text-3xl "><?= esc($title) ?></h2>
        <a href="<?= base_url('apps') ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
      </div>

			<div class="text-title w-full md:pb-4  md:px-16 p-2 flex items-center ">
        <h2 id="filter-title" class="text-center w-full text-lg lg:text-xl ">Registro de tickets</h2>
      </div>

			<div class="text-title w-full px-4 pb-8 flex items-center justify-between">
				<button id="btn_pendientes" class="flex uppercase space-x-4 shadow-bottom-right justify-center text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white text-sm lg:text-base py-2 px-2 lg:px-6 w-44 lg:min-w-64 " type="button" >
          <span>Tickets Pendientes</span>
				</button>
				<?php if (hasRole('mantenimiento') || hasRole('jefe_mantenimiento')): ?>
				<button id="btn_tickets" class="flex uppercase space-x-4 shadow-bottom-right justify-center text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white text-sm lg:text-base py-2 px-2 lg:px-6 w-44 lg:min-w-64 " type="button" >
          <span>Mis Tickets</span>
				</button>
				<?php endif; ?>
				<button id="btn_registro" class="flex uppercase space-x-4 shadow-bottom-right justify-center text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white text-sm lg:text-base py-2 px-2 lg:px-6 w-44 lg:min-w-64 " type="button" >
          <span>Registro de Tickets</span>
				</button>

      </div>


      <div class="w-full text-sm text-gray  ">
        <div class="flex flex-col h-full" >

					<div class=" w-full py-2 px-4 gap-4 flex items-center justify-between ">

						<div class="flex w-fit items-center gap-x-4">
							<span>ORDENAR POR:</span>
							<select id="order_by" name="order_by" class="w-44">
								<option value="" disabled selected>Seleccionar...</option>
								<option value="asc-fecha">- a + Fecha</option>
								<option value="desc-fecha">+ a - Fecha</option>
								<option value="asc-prioridad">ALTA - BAJA</option>
								<option value="desc-prioridad">BAJA - ALTA</option>
								<option value="asc-estado-maq">NO FUNC. - FUNC</option>
								<option value="desc-estado-maq">FUNC - NO FUNC.</option>
								<option value="asc-estado-ticket">ABIERTO - CERRADO</option>
								<option value="desc-estado-ticket">CERRADO - ABIERTO</option>
							</select>
						</div>

						<div class="w-1/2 lg:w-1/3 flex gap-2 px-4 text-sm">
							<select id="monthSelect" class="py-1 px-4 text-center"></select>
							<select id="yearSelect" class="py-1 px-4 text-center"></select>
							<button id="btn_download" type="button" class="btn btn-sm btn--search uppercase">
								<i class="fas fa-download"></i>
								<span>Descargar</span>
							</button>
						</div>
					</div>


					<div class="w-full pl-4 pr-4 pb-8">
						<div class="relative text-xs w-full text-sm h-[45vh] overflow-y-scroll ">
              <table id="table-tickets">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Responsable</th>
                    <th class=" hidden lg:flex">Planta</th>
                    <th class=" hidden lg:flex">Linea</th>
                    <th>Maquina</th>
                    <th>Estado Ticket</th>
                    <th>Prioridad</th>
                    <th class=" hidden lg:flex">Estado</th>
                    <th>Asunto</th>
                    <th class=" hidden lg:flex">Descripcion</th>
                    <th>Fecha</th>
                    <th>SLA</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
							<div id="row__empty" class="row__empty">No hay datos.</div>
              
            </div>
          </div>

					<div class="text-title w-full px-4 md:pb-8 flex items-center justify-end">
						<button data-modal="modal_ticket_filter" class="btn_open_modal flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit" >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
              </svg>
              <span>BUSCAR</span>
            </button>
					</div>

        </div>
      </div>


    </div>

  <!-- Modal Filtro -->
  <div id="modal_ticket_filter" class="hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
		<form id="form_search"  method="post" class=" flex flex-col items-center justify-between space-y-8 bg-white p-8 m-4 w-full md:w-[80%] h-[85%] border-icon border-2">

      <div class="flex flex-col w-full">
				<div class=" relative flex w-full justify-center text-center  ">
					<h2 class="text-gray text-xl uppercase">Filtro Busqueda</h2>
					<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
				</div>

				<div class=" grid md:grid-cols-3 gap-x-4 gap-y-8 place-items-center w-full pt-8 lg:pt-2 ">

					<div class="flex flex-col w-48">
						<h5 class="text-center uppercase">Estado Ticket</h5>
						<select name="estado_ticket" >
							<option value="" disabled selected>Seleccionar...</option>
							<option value="1">ABIERTO</option>
							<option value="2">EN PROGRESO</option>
							<option value="3">RESUELTO</option>
							<option value="4">CERRADO</option>
						</select>
					</div>

					<div class="flex flex-col ">
						<h5 class="text-center uppercase">Id</h5>
						<input type="text" id="ticketId" placeholder="Id ...">
					</div>

					<div class="relative flex flex-col">
						<h5 class="text-center uppercase">Responsable</h5>
						<input type="text" id="solicitante" name="solicitante">
						<ul id="lista_users" class="absolute z-40 top-12 bg-grayLight border border-super w-full h-32 overflow-y-scroll"></ul> 
					</div>


					<div class="flex flex-col w-48">
						<h5 class="text-center uppercase">Planta</h5>
						<select id="planta" name="planta" >
							<option value="" disabled selected>Seleccionar...</option>
							<?php foreach ($plantas as $planta): ?>
								<option value="<?= $planta['planta'] ?>"><?= $planta['planta'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="flex flex-col w-48">
						<h5 class="text-center uppercase">Linea</h5>
						<select id="linea" name="linea" >
							<option value="" disabled selected>Seleccionar...</option>
							<option value="1">Linea 1</option>
							<option value="2">Linea 2</option>
							<option value="3">Linea 3</option>
							<option value="4">Linea 4</option>
						</select>
					</div>

					<div class="relative flex flex-col ">
						<h5 class="text-center uppercase">Maquina</h5>
						<input type="text" id="nombre" name="nombre" >
					</div>

					<div class="flex flex-col w-48">
						<h5 class="text-center uppercase">Prioridad</h5>
						<select name="prioridad" id="prioridad">
							<option value="" disabled selected>Seleccionar...</option>
							<option value="BAJA">BAJA</option>
						<option value="MEDIA">MEDIA</option>
							<option value="ALTA">ALTA</option>
						</select>
					</div>
					<div class="flex flex-col w-48">
						<h5 class="text-center uppercase">Estado</h5>
						<select name="estado_maq" id="estado_maq">
							<option value="" disabled selected>Seleccionar...</option>
							<option value="FUNCIONAL">FUNCIONAL</option>
							<option value="PARCIAL">PARCIAL</option>
							<option value="NO FUNCIONAL">NO FUNCIONAL</option>
						</select>
					</div>

					<div class="flex flex-col w-48">
						<h5 class="text-center uppercase">Fecha</h5>
						<input id="fecha" type="date" name="fecha" >
					</div>


				</div>
			</div>

      <div class="flex w-full justify-around px-10">

				<button id="btn_cancel" class=" btn_close_modal flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
          CANCELAR
        </button>
				<button class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit" >
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
						<path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
					</svg>
					<span>BUSCAR</span>
				</button>
      </div>

    </form>
  </div>

	<!-- Modal Details -->
	<div id="modal_ticket" class="hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil  ">
		<form id="form_ticket" method="post" class="relative flex flex-col gap-y-4  border-2 border-icon p-10 w-[90vw] lg:w-[65vw] bg-white">

			<div class="relative flex w-full justify-center text-center ">
				<h3 class="text-gray text-xl uppercase"> Actualizacion del Ticket </h3>
				<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
			</div>

			<div class="relative flex w-full justify-center text-center ">
				<p class="modal_title text-lg uppercase"></p>
				<a href="" class="btn_pdf absolute right-2 top-0 btn btn-sm btn--search uppercase">Version PDF<i class="fas fa-print mx-1"></i></a>
			</div>


			<div class="w-full px-1 lg:px-2 flex flex-col gap-y-4 max-h-[65vh] overflow-y-scroll ">
				<p class="text-title text-center text-lg">Información de creación</p>

				<div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Fecha de Creacion</h5>
						<input type="text" name="fecha_creacion" class="text-center to_uppercase bg-title text-white " >
					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Hora de Reporte</h5>
						<input type="text" name="hora_reporte" class="text-center to_uppercase" readonly>
					</div>

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">Solicitante</h5>
						<input type="text" name="solicitante" class="text-center to_uppercase" readonly>
					</div>

				</div>

				<p class="text-title text-center text-lg pt-4">Información de Seguimiento</p>

				<div class="flex w-full items-center justify-center gap-x-8 text-sm text-gray ">
					<div class="relative flex flex-col w-1/3 px-2 ">
						<h5 class="text-center uppercase">Estado del ticket</h5>
						<select id="estado_ticket1"  name="estado_ticket1" class="text-center to_uppercase" >
							<option value="" >Seleccionar ....</option>
							<option value="1">ABIERTO</option>
							<option value="2">EN PROGRESO</option>
							<option value="3">RESUELTO</option>
							<option value="4">CERRADO</option>
						</select>
					</div>
				</div>

				<div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Inicio de Reparacion</h5>
						<input type="text" id="fecha_reparacion" class="text-center to_uppercase" readonly>
					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Fin de Reparacion</h5>
						<input type="text" id="fin_reparacion" class="text-center to_uppercase" readonly>
					</div>

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">Tiempo total reparacion</h5>
						<input type="text" id="tiempo_total_reparacion" class="text-center to_uppercase" readonly>
					</div>

				</div>

				<!-- <div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Inicio de Limpieza y sanitizacion</h5>
						<input type="text" id="fecha_inicio_limpieza" class="text-center to_uppercase" readonly>
					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Fin de Limpieza y sanitizacion</h5>
						<input type="text" id="fecha_cierre_limpieza" class="text-center to_uppercase" readonly>
					</div>

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">Tiempo total Limpieza y sanitizacion</h5>
						<input type="text" id="tiempo_total_limpieza" class="text-center to_uppercase" readonly>
					</div>

				</div> -->


				<!-- <div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Notificacion de liberacion y calidad</h5>
						<input type="text" id="fecha_inicio_liberacion" class="text-center to_uppercase" readonly>
					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Fin de liberacion y calidad</h5>
						<input type="text" id="fecha_cierre_liberacion" class="text-center to_uppercase" readonly>
					</div>

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">Tiempo total de liberacion y calidad</h5>
						<input type="text" id="tiempo_total_liberacion" class="text-center to_uppercase" readonly>
					</div>

				</div> -->


				<!-- <div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Inicio de Reparacion</h5>
						<input type="text" id="fecha_reparacion" class="text-center to_uppercase" >
					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Hora de Arranque</h5>
						<input type="text" id="fecha_arranque" class="text-center to_uppercase" readonly>
					</div>

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">Fecha de Cierre</h5>
						<input type="text" id="fecha_cierre" class="text-center to_uppercase" readonly>
					</div>

				</div> -->


				<!-- <div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">
					
					<div class="relative flex flex-col w-full">
						<h5 class="text-center uppercase">Solicitud Compra</h5>
						<input type="text" name="solicitud_compra" class="text-center to_uppercase " readonly>
					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Costo</h5>
						<input type="text" name="costo" class="text-center to_uppercase" readonly>
					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Tiempo de reaccion</h5>
						<input type="text" name="tiempo_reaccion" class="text-center to_uppercase" readonly>
					</div>

				</div> -->

				<!-- <div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">SLA</h5>
						<input type="text" name="sla" class="text-center to_uppercase" readonly>
					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Fecha de Cierre</h5>
						<input type="text" id="fecha_cierre" class="text-center to_uppercase" readonly>
					</div>

					<div class="relative flex flex-col w-full">
						<h5 class="text-center uppercase">Tiempo muerto</h5>
						<input type="text" name="tiempo_muerto" class="text-center to_uppercase " readonly>
					</div>

				</div> -->

				<div class="flex w-full items-center justify-center gap-x-8 text-sm text-gray ">
					<!-- <div class="relative flex flex-col w-1/3 px-2">
						<h5 class="text-center uppercase" style="color:#ff6600; font-weight: bold;">Hora Arranque</h5>
						<input type="text" id="fecha_arranque" class="text-center to_uppercase " readonly>
					</div> -->

					<div class="relative flex flex-col  w-1/3 px-2">
						<h5 class="text-center uppercase">Fecha de Cierre</h5>
						<input type="text" id="fecha_cierre" class="text-center to_uppercase" readonly>
					</div>

					<div class="relative flex flex-col w-1/3 px-2">
						<h5 class="text-center uppercase">Imputable a</h5>

						<select id="imputable" name="imputable" class="text-center to_uppercase" >
							<option value="" >Seleccionar ....</option>
							<option value="Falla de equipo">Falla de equipo</option>
							<option value="Ajuste de Operación">Ajuste de Operación</option>
							<option value="Materia Prima">Materia Prima</option>
							<option value="Ajuste de Arranque">Ajuste de Arranque</option>
							<option value="Limpieza Sopleteo">Limpieza Sopleteo</option>
						</select>

						<!-- <input type="text" name="imputable" class="text-center to_uppercase " readonly> -->
					</div>
				</div>

				<p class="text-title text-center text-lg pt-4">Detalles</p>

				<div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">Responsable</h5>
						<select id="responsableId"  name="responsableId" class="text-center to_uppercase" required>
							<option value="" >Seleccionar ....</option>
							<?php foreach ($users_mt as $user): ?>
								<option value="<?= $user['id'] ?>"><?= $user['name'] . ' ' . $user['last_name']?></option>
							<?php endforeach; ?>
							<?php foreach ($jefes_mt as $user_jefe): ?>
								<option value="<?= $user_jefe['id'] ?>"><?= $user_jefe['name'] . ' ' . $user_jefe['last_name']?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">planta</h5>
						<input type="text" name="planta" class="text-center to_uppercase" readonly>
					</div>

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">linea</h5>
						<input type="text" name="linea" class="text-center to_uppercase" readonly>
					</div>

				</div>

				<div class="flex w-full items-center justify-between gap-x-8 text-sm text-gray ">

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">maquina</h5>
						<input type="text" name="maquina" class="text-center to_uppercase" >
					</div>

					<div class="relative flex flex-col w-full ">
						<h5 class="text-center uppercase">prioridad</h5>
						<input type="text" name="prioridad" class="text-center to_uppercase" readonly>
					</div>

					<div class="flex flex-col w-full ">
						<h5 class="text-center uppercase">estado</h5>
						<input type="text" name="estado_maq" class="text-center to_uppercase" readonly>
					</div>

				</div>

				<div class="flex flex-col w-full items-center justify-between gap-y-4 text-sm text-gray ">

					<div class="flex flex-col w-full items-start space-y-2  ">
						<h5 class="text-center uppercase">Asunto</h5>
						<input type="text" name="asunto" class="px-4 py-2 w-full border border-grayMid bg-grayLight outline-none text-gray drop-shadow  "  readonly>
					</div>

					<div class="flex flex-col w-full items-start space-y-2  ">
						<h5 class="text-center uppercase">Descripción</h5>
						<textarea name="descripcion" class="p-4 w-full border border-grayMid outline-none resize-none bg-grayLight text-gray drop-shadow " rows="4"  readonly></textarea>
					</div>
				</div>


				<p class="text-title text-center text-lg pt-4">Comentarios</p>

				
				<div class=" flex flex-col space-y-2 w-full text-sm  ">
					<div class="flex w-full text-center">
						<div class="w-44 bg-icon text-white p-2">Nombre</div>
						<div class="w-28 bg-icon text-white p-2">Fecha</div>
						<div class="flex-grow bg-icon text-white p-2">Comentario</div>
					</div>
					<div class="h-44 overflow-y-auto ">
						<div id="comentarios_container" class="flex flex-col space-y-2 w-full "></div> 
					</div>

					<div id="add_comment" class="hidden flex flex-col gap-y-4 w-full ">
						<div class="flex flex-col gap-y-4 ">
							<input type="hidden" name="mant_id">
							<textarea name="comentario" class="p-4 w-full border border-grayMid outline-none resize-none bg-grayLight text-gray drop-shadow " rows="2" placeholder="Escribe tu comentario..."></textarea>
							<button id="btn_submit_comment" class="self-end  flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >AGREGAR COMENTARIO</button>
						</div>
					</div>

					<div class=" pt-6 flex justify-end space-x-12 text-sm ">
						<button id="btn_add_comment" class=" rounded text-icon border-2 border-icon hover:bg-icon hover:text-white py-1 px-3 w-fit" type="button"><i class="fa fa-plus text-xl"></i></button>
					</div>
				</div> 
				

				<p class="text-title text-center text-lg pt-4">Evidencia del incidente</p>

				<img id="adjunto_mant" alt="Captured Photo" class="flex w-full object-contain justify-center items-center mx-auto  h-56 border-title border-2 rounded">

				<p class="text-title text-center text-2xl pt-4">Reparación</p>
				<p class="text-title text-center text-lg">Evidencia de Mantenimiento</p>

				<img id="adjunto_repar" alt="Captured Photo" class="flex w-full object-contain justify-center items-center mx-auto  h-56 border-title border-2 rounded">

				<div class="flex flex-col w-full items-center justify-between gap-y-4 text-sm text-gray pt-4 ">

					<button id="btn_open_camera" class=" w-64 lg:w-fit flex  items-center justify-center space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 " type="button" >
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
							<path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
							<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
						</svg>

						<span>TOMAR EVIDENCIA</span>
					</button>
				</div>


				<div class="flex flex-col w-full items-center justify-between gap-y-4 text-sm text-gray ">

					<div class="flex flex-col w-full items-start space-y-2  ">
						<h5 class="text-center uppercase">Diagnostico</h5>
						<input type="text" name="diagnostico" class="px-4 py-2 w-full border border-grayMid bg-grayLight outline-none text-gray drop-shadow  " placeholder="Escribe aqui el diagnostico alcanzado ..." >
					</div>

					<div class="flex flex-col w-full items-start space-y-2  ">
						<h5 class="text-center uppercase">Reparacion Realizada</h5>
						<textarea name="reparacion_detalle" class="p-4 w-full border border-grayMid outline-none resize-none bg-grayLight text-gray drop-shadow " rows="4" placeholder="Escribe aquí el detalle de la reparación ..." ></textarea>
					</div>
				</div>

				<!-- <p class="font-bold text-gray text-center text-xl pt-4">¿Se requiere algun cambio de pieza?</p>

				<div class="flex items-center justify-center gap-x-24 pt-4">
					<label class="flex flex-col justify-center items-center">
							<input type="radio" data-name="cambio_pieza" name="tog1" data-value="no" class="checkbox_pieza hidden">
							<span class="checkbox-label"><i class="fas fa-check "></i></span>
							<span>No</span>
					</label>

					<label class="flex flex-col justify-center items-center">
							<input type="radio" data-name="cambio_pieza" name="tog1" data-value="si" class="checkbox_pieza hidden" >	
							<span class="checkbox-label"><i class="fas fa-check "></i></span>
							<span>Si</span>
					</label>
				</div> -->

				<div id="pregunta_compra" class="hidden my-4 flex flex-col w-full items-center justify-between gap-y-4 ">
					<p class="font-bold text-gray text-center text-xl pt-4">¿El ticket require alguna compra?</p>

					<div class="flex items-center justify-center gap-x-24 pt-4">
						<label class="flex flex-col justify-center items-center">
								<input type="radio" data-name="compra_pieza" name="tog2" data-value="no" class="checkbox_compra hidden" >
								<span class="checkbox-label"><i class="fas fa-check "></i></span>
								<span>No</span>
						</label>

						<label class="flex flex-col justify-center items-center">
								<input type="radio" data-name="compra_pieza" name="tog2" data-value="si" class="checkbox_compra hidden" >
								<span class="checkbox-label"><i class="fas fa-check "></i></span>
								<span>Si</span>
						</label>
					</div>
				
				</div>

				<div id="inventario_section" class="hidden  my-4 flex flex-col w-full items-center justify-between gap-y-4 text-sm text-gray ">
					<div class=" flex flex-col w-full items-start space-y-2  ">
						<h5 class="text-center uppercase">Toma inventario</h5>
						<textarea name="nota_inventario" class="p-4 w-full border border-grayMid outline-none resize-none bg-grayLight text-gray drop-shadow " rows="4" placeholder="Por favor, escribe aquí lo que requeriste del inventario"></textarea>
					</div>
				</div>

				<p class="font-bold text-gray text-center text-xl pt-4">Firmas de Documento</p>

				<!-- firmas encargado -->
				<div class="my-10 flex items-start gap-x-12 w-full">
					
					<div class="flex flex-col w-full items-center gap-y-2 justify-center w-1/3 ">
						
						<img id="resp_firma" class="h-44 w-full border-2 border-grayMid bg-grayLight">  

						<p id="resp_nombre" class="font-bold"></p>
						<h5 class="text-center uppercase">Realiza Mantenimiento</h5>

						<button data-field="firma_responsable" class="btn_firmar hidden flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">FIRMAR</button>

					</div>
					<div class="flex flex-col w-full items-center gap-y-2 justify-center w-1/3 ">
						
						<img id="solic_firma" class="h-44 w-full border-2 border-grayMid bg-grayLight">  

						<p id="solic_nombre" class="font-bold"></p>
						<h5 class="text-center uppercase">Solicitante</h5>

					</div>

					<!-- <div class="flex flex-col w-full items-center gap-y-2 justify-center w-1/3 ">
						
						<img id="produccion_firma" class="h-44 w-full border-2 border-grayMid bg-grayLight">  

						<?php if (hasRole('lider_produccion')): ?>
							<p id="produccion_nombre" class="font-bold"><?= session()->get('user')['name'] . ' ' . session()->get('user')['last_name'] ?></p>
						<?php else:?>
							<p id="produccion_nombre" class="font-bold"></p>
						<?php endif; ?>

						<h5 class="text-center uppercase">Valida Solucion</h5>
						<?php if (hasRole('lider_produccion')): ?>
						<button data-area="produccionId" data-user-id="<?= session()->get('user')['id'] ?>" data-field="firma_produccion" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">FIRMAR</button>
						<?php endif; ?>

					</div> -->

				</div>

				<div id="pregunta_limpieza" class=" my-4 flex flex-col w-full items-center justify-between gap-y-4 ">
					<p class="font-bold text-gray text-center text-xl pt-4">¿Se requiere limpieza y sanitización?</p>

					<div class="flex items-center justify-center gap-x-24 pt-4">
						<label class="flex flex-col justify-center items-center">
								<input type="radio" data-name="requiere_limpieza" name="tog_limpieza" data-value="no" class="checkbox_limpieza hidden" >
								<span class="checkbox-label"><i class="fas fa-check "></i></span>
								<span>No</span>
						</label>

						<label class="flex flex-col justify-center items-center">
								<input type="radio" data-name="requiere_limpieza" name="tog_limpieza" data-value="si" class="checkbox_limpieza hidden" >
								<span class="checkbox-label"><i class="fas fa-check "></i></span>
								<span>Si</span>
						</label>
					</div>
				
					<div id="add_limpieza_section" class="hidden my-10 flex items-start justify-center gap-x-12 w-full">
						<div class="flex flex-col w-1/3 items-center gap-y-2 justify-center  ">

							<p>¿Esta seguro ?</p>
						
							<button data-area="limpieza" class="btn_inicio_limpieza flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">Aceptar</button>
						</div>

					</div>
					
				</div>

				<!-- firmas limpieza -->
				<div id="limpieza_section" class="hidden my-10 flex items-start justify-center gap-x-12 w-full">

					<div class="flex flex-col w-1/3 items-center gap-y-2 justify-center  ">
						
						<img id="limpieza_firma" class="h-44 w-full border-2 border-grayMid bg-grayLight">  

						<?php if (hasRole('limpieza')): ?>
							<p id="limpieza_nombre" class="font-bold"><?= session()->get('user')['name'] . ' ' . session()->get('user')['last_name'] ?></p>
						<?php else:?>
							<p id="limpieza_nombre" class="font-bold"></p>
						<?php endif; ?>

						<h5 class="text-center uppercase">Limpieza y Sanitización</h5>

						<?php if (hasRole('limpieza')): ?>
						<button data-area="limpiezaId" data-user-id="<?= session()->get('user')['id'] ?>" data-field="firma_limpieza" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">FIRMAR</button>
						<?php endif; ?>

					</div>
				</div>

				<!-- firmas calidad -->

				<div class="my-10 flex items-start justify-center gap-x-12 w-full">
					<div class="flex flex-col items-center gap-y-2 justify-center w-1/3 ">
						
						<img id="calidad_firma" class="h-44 w-full border-2 border-grayMid bg-grayLight">  

						<?php if (hasRole('calidad')): ?>
							<p id="calidad_nombre" class="font-bold"><?= session()->get('user')['name'] . ' ' . session()->get('user')['last_name'] ?></p>
						<?php else:?>
							<p id="calidad_nombre" class="font-bold"></p>
						<?php endif; ?>

						<h5 class="text-center uppercase">Libera calidad</h5>

						<?php if (hasRole('calidad')): ?>
						<button data-area="calidadId" data-user-id="<?= session()->get('user')['id'] ?>" data-field="firma_calidad" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">FIRMAR</button>
						<?php endif; ?>

					</div>

					<div class="flex flex-col items-center gap-y-2 justify-center w-1/3 ">
						
						<img id="encar_firma" class="h-44 w-full border-2 border-grayMid bg-grayLight">  

						<p id="encar_nombre" class="font-bold"></p>
						<h5 class="text-center uppercase">Encargado Mantenimiento</h5>
						<?php if (hasRole('jefe_mantenimiento')): ?>
						<button data-field="firma_encargado" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">FIRMAR</button>
						<?php endif; ?>


					</div>


				</div>

			</div>

			<div id="msg_error" class="hidden flex w-full items-center justify-center text-warning font-semibold"></div>

			<div class="flex w-full items-center justify-between text-sm  ">
				<?php if (hasRole('mantenimiento') || hasRole('jefe_mantenimiento')): ?>
				<button class="btn_close_modal flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">CANCELAR</button>

				<button class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit">ACEPTAR</button>
				<?php endif; ?>

				<input type="file" id="cameraInput" name="archivo" accept="image/*" capture="environment" class="hidden">
				<input type="hidden" id="mant_id" name="mant_id">
				<input type="hidden" name="cambio_pieza">
				<input type="hidden" name="compra_pieza">
				<input type="hidden" name="requiere_limpieza">

				<input type="hidden" name="fecha_reparacion">
				<input type="hidden" name="fecha_arranque">
				<input type="hidden" name="fecha_cierre">
				<input type="hidden" name="estado_ticket">

			</div>


		</form>

	</div>


<?php echo view('_partials/_modal_msg_tablet'); ?>

<script>



const monthSelect = document.getElementById('monthSelect');
const yearSelect = document.getElementById('yearSelect');

function generateYears(start = 2023, end = moment().year()) {
  for (let y = start; y <= end; y++) {
    const option = document.createElement('option');
    option.value = y;
    option.textContent = y;
    yearSelect.appendChild(option);
  }
  yearSelect.value = moment().year();
}

function generateMonths(year) {
  monthSelect.innerHTML = '<option value="">Seleccionar mes</option>';

  const currentYear = moment().year();
  const currentMonth = moment().month() + 1;  // moment().month() is 0-indexed, so we add 1

  let totalMonths = 12;

  // If the selected year is the current year, limit the months to the current month
  if (year === currentYear) {
    totalMonths = currentMonth;
  }

  for (let i = 1; i <= totalMonths; i++) {
    const option = document.createElement('option');
    option.value = i;
    option.textContent = moment().month(i - 1).format('MMMM'); // Use moment to get the month name
    monthSelect.appendChild(option);
  }

  // ✅ Auto-select current month AND auto-load data
  if (year === currentYear && totalMonths > 0) {
    monthSelect.value = currentMonth;
    monthSelect.dispatchEvent(new Event('change'));
  }
}

generateYears();
generateMonths(moment().year());



function getMonthRange(year, month) {
	return {
		start_date: moment(`${year}-${month}-01`)
			.startOf('month')
			.format('YYYY-MM-DD'),

		end_date: moment(`${year}-${month}-01`)
			.endOf('month')
			.format('YYYY-MM-DD')
	};
}


const getListaMensual = (start_date, end_date) => {
	const url = `mtickets/get_by_daterange/${start_date}/${end_date}`;
	window.location.href = url;
};

const btn_download = document.getElementById('btn_download');
btn_download.addEventListener('click', () => {
	const month = parseInt(monthSelect.value);
	const year = parseInt(yearSelect.value);

	if (!month || !year) return;

	const { start_date, end_date } = getMonthRange(year, month);
	getListaMensual(start_date, end_date);
});




	const previewPhoto = (e) => {
		const file = e.target.files[0];
		if (file) {
			const reader = new FileReader();
			reader.onload = function(event) {
				document.getElementById('adjunto_repar').src = event.target.result;
			};
			reader.readAsDataURL(file);
		}
	}

	const cameraInput = document.querySelector('#cameraInput');
	cameraInput?.addEventListener('change', previewPhoto);

	const btn_open_camera = document.querySelector('#btn_open_camera');
	btn_open_camera?.addEventListener('click', () => {

		cameraInput.value = ''; 
		cameraInput.click();
	});

	const btn_retry = document.querySelector('#btn_retry');
	btn_retry?.addEventListener('click', () => {
		cameraInput.value = ''; 
		cameraInput.click();
	});


	const notif = document.getElementById('notif');

	if(notif) {

		const get_notif = () =>  {
			notif.innerHTML = ''; 

			Service.exec('get', `/get_notif`)
			.then( r => {

				if (r) {
					notif.innerHTML = r.length; 
				} 
			});
		}

		setTimeout(get_notif, 30000);

		get_notif();
	}


 Service.setLoading();

	const addComment = document.querySelector('#add_comment');
	const btnAddComment = document.querySelector('#btn_add_comment');
	btnAddComment?.addEventListener('click', e => addComment.classList.toggle('hidden'));

	const submitComm = (e) => {
    // e.preventDefault();
		let form_add_comment = document.querySelector(`#modal_ticket #add_comment`);
		// console.log(form_add_comment)

    let comentario = form_add_comment.querySelector('textarea[name="comentario"]');
    let mant_id = form_add_comment.querySelector('input[name="mant_id"]');

    if (comentario.value.length > 5) {
			e.target.disabled = true;

      const formData = new FormData();
			formData.append('mant_id', mant_id.value);
			formData.append('comentario', comentario.value)

      Service.exec('post', `/add_comment_mt`, formData_header, formData)
      .then( r => {
        // console.log(r); return;
        addComment.classList.add('hidden');
				e.target.disabled = false;
				mant_id.value = '';
				comentario.value = '';

        let id = r.mant_id;
				initComment(id); 

      });
    }
  }

  const btn_submit_comment = document.querySelector('#btn_submit_comment');
  btn_submit_comment?.addEventListener('click', submitComm);

  const initComment = (id) => {
		let form_add_comment = document.querySelector(`#modal_ticket #add_comment`);

    let comentarios_container = document.querySelector('#comentarios_container');
    comentarios_container.innerHTML = Service.loader();

    let mantId = form_add_comment.querySelector('[name="mant_id"]');
    mantId.value = id;

    Service.exec('get', `/get_comentarios_mt/${mantId.value}`)
    .then( r => {
      // console.log(r); return;
      comentarios_container.innerHTML = "";

      if ( r.comments.length > 0 ) {
        r.comments.forEach(comm => {
          const div = document.createElement('div');
          div.className = 'row-comentario';
          div.innerHTML = `
            <div class="w-44 p-2">${comm.name} ${comm.last_name}</div>
            <div class="w-28 p-2 text-center">${dateToString(comm.created_at)}</div>
            <div class="p-2">${comm.comentario}</div>
          `;
          comentarios_container.appendChild(div);
        });

      } else {
        const div = document.createElement('div');
        div.className = 'row-comentario';
        div.innerHTML = Service.empty('No hay datos.');
        comentarios_container.appendChild(div);
      }
    });
  }

	const initAdjuntos = (id) => {
		// traer todos los adjuntos y mostrar el mas reciente como adjunto_repar

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

	const initFirmas = (id) => {
		// traer todos los adjuntos y mostrar el mas reciente como adjunto_repar

		Service.exec('get', `/get_firmas_mt/${id}`)
    .then( r => {

			let form = document.querySelector(`#form_ticket`);

			let btn_encargado = form.querySelector('button[data-field="firma_encargado"]');
			btn_encargado?.setAttribute('data-id', id)

			let btn_responsable = form.querySelector('button[data-field="firma_responsable"]');
			btn_responsable?.setAttribute('data-id', id)

			let btn_limpieza = form.querySelector('button[data-field="firma_limpieza"]');
			btn_limpieza?.setAttribute('data-id', id)

			let btn_produccion = form.querySelector('button[data-field="firma_produccion"]');
			btn_produccion?.setAttribute('data-id', id)

			let btn_calidad = form.querySelector('button[data-field="firma_calidad"]');
			btn_calidad?.setAttribute('data-id', id)

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

			if(r.resp.email == "<?= session()->get('user')['email'] ?>"){
				btn_responsable.classList.remove('hidden');
			} else {
				btn_responsable.classList.add('hidden');
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

			// if ( r.ticket.firma_produccion == "si" ) {
			// 	form.querySelector('#produccion_firma').src = `${root}/files/download?path=${r.produccion.signature}`; 
      // } else {
			// 	form.querySelector('#produccion_firma').src = `${root}/img/no_img_alt.png`; 
      // }

			if ( r.ticket.firma_limpieza == "si" ) {
				form.querySelector('#limpieza_firma').src = `${root}/files/download?path=${r.limpieza.signature}`; 
      } else {
				form.querySelector('#limpieza_firma').src = `${root}/img/no_img_alt.png`; 
      }

    });

	}

	const submitLimpieza = (e) => {
    let area = e.target.getAttribute('data-area');
		let id = document.querySelector('input[name="mant_id"]').value;
		let req_limpieza = document.querySelector('input[name="requiere_limpieza"]').value;

		e.target.disabled = true;

		const formData = new FormData();
		formData.append('area', area);
		formData.append('mantId', id);
		formData.append('requiere_limpieza', req_limpieza);

		Service.exec('post', `/add_limpieza_mt`, formData_header, formData)
		.then( r => {
			e.target.disabled = false;
			document.querySelector('#add_limpieza_section').classList.add('hidden');
		});
	}

	const allInitLimpieza = document.querySelectorAll('.btn_inicio_limpieza');
	allInitLimpieza?.forEach( btn => {
		btn.addEventListener('click', submitLimpieza);
	});



	const submitFirma = (e) => {

    let field = e.target.getAttribute('data-field');
    let id = e.target.getAttribute('data-id');
    let userId = e.target.getAttribute('data-user-id');
    let area = e.target.getAttribute('data-area');

		// console.log(field, id); return;

		e.target.disabled = true;

		const formData = new FormData();
		formData.append('field', field);
		formData.append('mantId', id);

		if(userId !== undefined) {
			formData.append('userId', userId);
			formData.append('area', area);
		}

		Service.exec('post', `/add_firma_mt`, formData_header, formData)
		.then( r => {
			e.target.disabled = false;
			initFirmas(id); 
		});
  }

  const allBtnFirmar = document.querySelectorAll('.btn_firmar');
  allBtnFirmar?.forEach( btn => {
		btn.addEventListener('click', submitFirma);
	});



	let overlay = document.querySelector('.backdrop-mobile-menu');
	let mobile_checkbox = document.querySelector('#mobile_checkbox');
	mobile_checkbox?.addEventListener('change', e => {
		if(e.target.checked) {
			document.body.classList.add('no-scroll');
			overlay.style.display = 'block';
		} else {
			document.body.classList.remove('no-scroll');
			overlay.style.display = 'none';
		}
	});

	window.addEventListener('click', e => {
		let mobile_checkbox = document.querySelector('#mobile_checkbox');
		if (e.target == overlay) {
			mobile_checkbox.checked = false;
			form_ticket.reset();
			document.body.classList.remove('no-scroll');
			overlay.style.display = 'none';
		}
	});



  const solicitante = document.getElementById('solicitante');
  const lista_users = document.getElementById('lista_users');

  solicitante?.addEventListener('keyup', e => {
    const nombre = e.target.value.trim();

    if (nombre.length >= 2) {

			Service.exec('get', `/get_empleados/${nombre}`)
			.then( r => {

				lista_users.innerHTML = ''; 
				if (r.length > 0) {
					r.forEach(user => {
						let name = `${user.name} ${user.last_name}`;
            const li = document.createElement('li');
            li.textContent = name;
            li.style.cursor = 'pointer'; 
            li.addEventListener('click', () => {
              solicitante.value = name; 
              lista_users.style.display = 'none';
            });
            lista_users.appendChild(li);
          });
          lista_users.style.display = 'block';
        } else {
          lista_users.style.display = 'none';
        }
			});

    } else {
      lista_users.style.display = 'none';
    }
  });

  document.addEventListener('click', (e) => {
    if (!lista_users.contains(e.target) && e.target !== solicitante) {
      lista_users.style.display = 'none';
    }
  });


const order_select = document.querySelector('#order_by');
order_select?.addEventListener('change', () => reorderTableRows(order_select.value));

const reorderTableRows = (order) => {
	const tbody = document.querySelector('#table-tickets tbody');
	const rows = Array.from(tbody.rows);

	rows.sort((a, b) => {
		let valA, valB;

		if (order.includes("prioridad")) {
				// Sort by Prioridad: "ALTA" > "MEDIA" > "BAJA"
				const prioridadOrder = { "ALTA": 1, "MEDIA": 2, "BAJA": 3 };
				valA = prioridadOrder[a.cells[6].querySelectorAll('div')[0].innerText.trim()] || 999;
				valB = prioridadOrder[b.cells[6].querySelectorAll('div')[0].innerText.trim()] || 999;
		} 
		else if (order.includes("estado-maq")) {
				// Sort by Status: "NO FUNCIONAL" > "PARCIAL" > "FUNCIONAL"
				const statusOrder = { "NO FUNCIONAL": 1, "PARCIAL": 2, "FUNCIONAL": 3 };
				valA = statusOrder[a.cells[7].querySelectorAll('div')[0].innerText.trim()] || 999;
				valB = statusOrder[b.cells[7].querySelectorAll('div')[0].innerText.trim()] || 999;
		} 
		else if (order.includes("estado-ticket")) {
				// Sort by Status Ticket: "ABIERTO" > "EN PROGRESO" > "RESUELTO" > "CERRADO"
				const ticketOrder = { "ABIERTO": 1, "EN PROGRESO": 2, "RESUELTO": 3, "CERRADO": 4 };
				valA = ticketOrder[a.cells[5].querySelectorAll('div')[0].innerText.trim()] || 999;
				valB = ticketOrder[b.cells[5].querySelectorAll('div')[0].innerText.trim()] || 999;
		} 
		else if (order.includes("fecha")) {
				// Sort by Date (DD-MM-YYYY) using moment.js
				const dateA = moment(a.cells[10].querySelectorAll('div')[0].innerText.trim(), "DD-MM-YYYY");
				const dateB = moment(b.cells[10].querySelectorAll('div')[0].innerText.trim(), "DD-MM-YYYY");
				valA = dateA.isValid() ? dateA.valueOf() : 0;
				valB = dateB.isValid() ? dateB.valueOf() : 0;
		}

		return order.includes("asc") ? valA - valB : valB - valA;
	});

	rows.forEach(row => tbody.appendChild(row));
};


const btnCancel = document.querySelector("#btn_cancel")
const table_tickets = document.querySelector("#table-tickets")

document.addEventListener('DOMContentLoaded', () => {
	if (window.innerWidth >= 1048) {
		table_tickets.className = "table-ticket-desktop";
		// console.log(table_tickets)
	} else {
		table_tickets.className = "table-ticket-tablet";
	}
})

const filterTitle = document.querySelector('#filter-title');

const btnPendientes = document.querySelector('#btn_pendientes');
btnPendientes?.addEventListener('click', e => {
	Service.exec('get', `/all_tickets_pendientes`)
	.then(r => {
		Service.hide('#row__empty');
		filterTitle.textContent = "Tickets pendientes";

		renderTickets(r);

	});  
});

const btnTickets = document.querySelector('#btn_tickets');
btnTickets?.addEventListener('click', e => {
	Service.exec('get', `/get_user_tickets`)
	.then(r => {
		//Service.hide('#row__empty');
		filterTitle.textContent = "Mis Tickets";

		renderTickets(r);
		// renderTickets([]);
	});  
});

const btnRegistro = document.querySelector('#btn_registro');
btnRegistro?.addEventListener('click', e => {
	Service.hide('#row__empty');
	filterTitle.textContent = "Registro de Tickets";
	loadAllTickets(); 
});


const results_container = document.querySelector('#table-tickets tbody');
const form_search = document.querySelector('#form_search');
form_search?.addEventListener('submit', e => {
	e.preventDefault();
	Service.stopSubmit(e.target, true);

	results_container.innerHTML = Service.loader();
	let ticketId = document.querySelector('#ticketId');

	const formData = new FormData(e.target);
	formData.append('ticketId', restore_format_id(ticketId.value, 'id'));

	Service.exec('post', `/search_ticket`, formData_header, formData)
	.then(r => {
		Service.hide('#row__empty');

		btnCancel.click();
		renderTickets(r);
		Service.stopSubmit(e.target, false);
	});  
});



	const estado_ticket = document.querySelector('#form_ticket #estado_ticket1');
	estado_ticket?.addEventListener('change', e => {
		let form = document.querySelector(`#form_ticket`);
		if(e.target.value == "2") {

			form.querySelector('input[name="fecha_reparacion"]').value = moment().format('YYYY-MM-DD H:mm:ss');
			form.querySelector('input[name="estado_ticket"]').value = e.target.value;

			form.querySelector('#fecha_reparacion').value = moment().format('DD-MM-YYYY');

		} else if (e.target.value == "3" || e.target.value == "4" ) {
			form.querySelector('input[name="estado_ticket"]').value = e.target.value;
			
			form.querySelector('input[name="fecha_arranque"]').value = moment().format('YYYY-MM-DD H:mm:ss');
			// form.querySelector('#fecha_arranque').value = moment().format('H:mm') + ' hrs.';
			// form.querySelector('input[name="fecha_cierre"]').value = moment().format('YYYY-MM-DD H:mm:ss');
		}
	});


	const toggleLimpieza = (e) => {
		let _val = e.target.getAttribute('data-value');
		let name = e.target.getAttribute('data-name');
		let limpieza_section = document.querySelector('#limpieza_section');
		let add_limpieza_section = document.querySelector('#add_limpieza_section');

		document.querySelector(`input[name="${name}"]`).value = _val;

		console.log(_val);

		if(name == "requiere_limpieza") {
			add_limpieza_section.classList.remove('hidden');

			if(_val == 'si') {
				limpieza_section.classList.remove('hidden');
			} else {
				limpieza_section.classList.add('hidden');
			}
		}

	};

	const togglePieza = (e) => {
		let _val = e.target.getAttribute('data-value');
		let name = e.target.getAttribute('data-name');
		let inventario_section = document.querySelector('#inventario_section');
		let pregunta_compra = document.querySelector('#pregunta_compra');

		document.querySelector(`input[name="${name}"]`).value = _val;

		console.log(_val);

		if(name == "cambio_pieza") {
			if(_val == 'si') {
				pregunta_compra.classList.remove('hidden');
			}

			if(_val == 'no') {
				pregunta_compra.classList.add('hidden');
				inventario_section.classList.add('hidden');
			}

		}

	};

	const toggleCompra = (e) => {
		let _val = e.target.getAttribute('data-value');
		let name = e.target.getAttribute('data-name');
		let inventario_section = document.querySelector('#inventario_section');

		document.querySelector(`input[name="${name}"]`).value = _val;

		console.log(_val);
		
		if(name == "compra_pieza") {
			if(_val == 'si') {
				inventario_section.classList.add('hidden');
			} 
			if(_val == 'no') {
				inventario_section.classList.remove('hidden');
			}
		}

	};


	const allCheckbox = document.querySelectorAll('.checkbox_compra');
	allCheckbox?.forEach(input => {
		input.addEventListener('change', toggleCompra);
	});

	const allCheckboxPieza = document.querySelectorAll('.checkbox_pieza');
	allCheckboxPieza?.forEach(input => {
		input.addEventListener('change', togglePieza);
	});

	const allCheckboxLimpieza = document.querySelectorAll('.checkbox_limpieza');
	allCheckboxLimpieza?.forEach(input => {
		input.addEventListener('change', toggleLimpieza);
	});



  const initModalTicket = (id) => {
    let modal_ticket = document.querySelector(`#modal_ticket`);
    let title = modal_ticket.querySelector('.modal_title');
    let btn_pdf = modal_ticket.querySelector('.btn_pdf');

		let inventario_section = document.querySelector('#inventario_section');
		let limpieza_section = document.querySelector('#limpieza_section');
		let pregunta_compra = document.querySelector('#pregunta_compra');


    Service.exec('get', `/get_ticket/${id}`)
    .then( r => {

      let form = document.querySelector(`#form_ticket`);

			title.innerHTML = `${format_id(r.id, 'id')}`;
			btn_pdf.href = `${root}/mtickets/print/${r.id}`;

			// form?.reset();
      // aprobaciones_container.innerHTML = "";

      if (r) {
			
				form.querySelector('#mant_id').value = r.id;
				form.querySelector('input[name="fecha_creacion"]').value = dateToString(r.created_at);
				form.querySelector('input[name="hora_reporte"]').value = `${fixedTimeMoment(r.created_at, 'HH:mm')}`;
				form.querySelector('input[name="solicitante"]').value = r.solicitante;

				form.querySelector('#fecha_reparacion').value = `${fixedTimeMoment(r.fecha_reparacion, 'HH:mm')} ${dateToString(r.fecha_reparacion)}`;
				// form.querySelector('#fecha_arranque').value = `${fixedTimeMoment(r.fecha_arranque, 'DD-MM-YYYY HH:mm')}`;
				// form.querySelector('#fecha_arranque').value = `${fixedTimeMoment(r.fecha_arranque, 'HH:mm')}`;

				form.querySelector('#fin_reparacion').value = `${fixedTimeMoment(r.fecha_arranque, 'HH:mm')} ${dateToString(r.fecha_arranque)}`

				// fecha cierre solo cuando encargado mantenimiento pone el ticket como firmado y cerrado
				form.querySelector('#fecha_cierre').value = dateToString(r.fecha_cierre);


				// form.querySelector('#fecha_inicio_liberacion').value = `${fixedTimeMoment(r.fecha_inicio_liberacion, 'HH:mm')} ${dateToString(r.fecha_inicio_liberacion)}`;

				// form.querySelector('#fecha_cierre_liberacion').value = `${fixedTimeMoment(r.fecha_cierre_liberacion, 'HH:mm')} ${dateToString(r.fecha_cierre_liberacion)}`;


				// form.querySelector('#fecha_inicio_limpieza').value = `${fixedTimeMoment(r.fecha_inicio_limpieza, 'HH:mm')} ${dateToString(r.fecha_inicio_limpieza)}`;

				// form.querySelector('#fecha_cierre_limpieza').value = `${fixedTimeMoment(r.fecha_cierre_limpieza, 'HH:mm')} ${dateToString(r.fecha_cierre_limpieza)}`;



				// form.querySelector('input[name="sla"]').value = r.days_remaining;

				form.querySelector('input[name="planta"]').value = r.planta;
				form.querySelector('input[name="linea"]').value = r.linea;
				form.querySelector('input[name="maquina"]').value = r.nombre;
				form.querySelector('input[name="estado_maq"]').value = r.estado_maq;
				form.querySelector('input[name="prioridad"]').value = r.prioridad;
				form.querySelector('input[name="asunto"]').value = r.asunto;
				form.querySelector('input[name="diagnostico"]').value = r.diagnostico;



				form.querySelector('textarea[name="descripcion"]').value = r.descripcion;
				form.querySelector('textarea[name="reparacion_detalle"]').value = r.reparacion_detalle;
				form.querySelector('textarea[name="nota_inventario"]').value = r.nota_inventario;



				// tiempo_reaccion =  fecha_inicio_liberacion - created_at

				// if(r.fecha_inicio_liberacion) {			
				// 	form.querySelector('input[name="tiempo_reaccion"]').value = getTimeDiff(r.created_at, r.fecha_reparacion);
				// }


				// tiempo_total_reparacion = fecha_reparacion - fecha_arranque

				if(r.fecha_reparacion) {			
					form.querySelector('#tiempo_total_reparacion').value = getTimeDiff(r.fecha_reparacion, r.fecha_arranque);
				}

				// tiempo_total_limpieza = fecha_cierre_limpieza - fecha_inicio_limpieza

				// if(r.fecha_inicio_limpieza) {			
				// 	form.querySelector('#tiempo_total_limpieza').value = getTimeDiff(r.fecha_inicio_limpieza, r.fecha_cierre_limpieza);
				// }
				

				// tiempo_total_liberacion = fecha_cierre_liberacion - fecha_inicio_liberacion

				// if(r.fecha_inicio_liberacion) {			
				// 	form.querySelector('#tiempo_total_liberacion').value = getTimeDiff(r.fecha_arranque, r.fecha_cierre_liberacion);
				// }





				if(r.fecha_reparacion) {			
					form.querySelector('input[name="fecha_reparacion"]').value = r.fecha_reparacion;
				}

				if(r.fecha_arranque) {			
					form.querySelector('input[name="fecha_arranque"]').value = r.fecha_arranque;
					form.querySelector('input[name="fecha_cierre"]').value = r.fecha_arranque;

				}
				
				if(r.cambio_pieza) {

					if(r.cambio_pieza == 'si') {
						pregunta_compra.classList.remove('hidden');
						inventario_section.classList.add('hidden');
					}
					
					if(r.cambio_pieza == 'no') {
						pregunta_compra.classList.add('hidden');
						inventario_section.classList.add('hidden');
					}

					// form.querySelector(`input[data-name="cambio_pieza"][data-value="${r.cambio_pieza}"]`).checked = true;
					// form.querySelector('input[name="cambio_pieza"]').value = r.cambio_pieza
				}

				if(r.compra_pieza) {

					if(r.compra_pieza == 'si') {
						inventario_section.classList.add('hidden');
					} 
					if(r.compra_pieza == 'no') {
						inventario_section.classList.remove('hidden');
					}

					form.querySelector(`input[data-name="compra_pieza"][data-value="${r.compra_pieza}"]`).checked = true;
					form.querySelector('input[name="compra_pieza"]').value = r.compra_pieza
				}

				if(r.requiere_limpieza) {

					if(r.requiere_limpieza == 'si') {
						limpieza_section.classList.remove('hidden');
					} 
					if(r.requiere_limpieza == 'no') {
						limpieza_section.classList.add('hidden');
					}

					form.querySelector(`input[data-name="requiere_limpieza"][data-value="${r.requiere_limpieza}"]`).checked = true;
					form.querySelector('input[name="requiere_limpieza"]').value = r.requiere_limpieza
				}


				setSelectedOption('#responsableId', r.responsableId, 'string')
				setSelectedOption('#imputable', r.imputable, 'string')

				// console.log(typeof r.estado_ticket)
				setSelectedOption('#form_ticket #estado_ticket1', r.estado_ticket, 'string');

				form.querySelector('input[name="estado_ticket"]').value = r.estado_ticket

				if(r.estado_ticket !== '1') {
					disableOptions(r.estado_ticket);
				} 
	
				let total_times = 				[
					[r.created_at, r.fecha_reparacion],
					[r.fecha_reparacion, r.fecha_arranque],
					[r.fecha_inicio_limpieza, r.fecha_cierre_limpieza],
					[r.fecha_arranque, r.fecha_cierre_liberacion]
				];

				let t_muerto = getTimeDiffArray(total_times);

				// form.querySelector('input[name="tiempo_muerto"]').value = t_muerto;


				initComment(r.id);
				initAdjuntos(r.id);
				initFirmas(r.id);
        
      } else {
        const div = document.createElement('div');
        div.className = 'row-comentario';
        div.innerHTML = Service.empty('No hay datos.');
        aprobaciones_container.appendChild(div);
      }
    });
  }


  const initRowBtn = () => {
    const allBtnOpen = document.querySelectorAll('.btn_open_modal');
    allBtnOpen?.forEach( (btn, index) => {

      btn.addEventListener('click', e => {
        // console.log(e.currentTarget)

        let modal_id = e.currentTarget.getAttribute('data-modal');
        let modal = document.querySelector(`#${modal_id}`);
        // console.log(modal_id)

        modal.classList.add('modal_active');
        modal.classList.remove('hidden');

        if (modal_id == 'modal_ticket') {
          let id = e.currentTarget.getAttribute('data-id');
          initModalTicket(id);
        }

      });
    });

    const allBtnClose = document.querySelectorAll('.btn_close_modal')
    allBtnClose?.forEach( btn => {
      btn.addEventListener('click', (e) => {
        let modal_active = document.querySelector('.modal_active');
        if (modal_active) {
					form_ticket.reset();
          modal_active.classList.add('hidden');
          modal_active.classList.remove('modal_active');
        }
      });
    });

  }

  initRowBtn();


  const form_ticket = document.querySelector('#form_ticket');
  form_ticket?.addEventListener('submit', e => {

		// const img = document.querySelector('#adjunto_repar');
		// const invalidSrc = '/img/no_img_alt.png';

		// const hasValidImage =
		// 	img &&
		// 	img.src &&
		// 	!img.src.endsWith(invalidSrc) &&
		// 	img.complete &&
		// 	img.naturalWidth > 0;

		// if (!hasValidImage) {
		// 	const fileInput = document.querySelector('#form_ticket #cameraInput');
		// 	const msg_error = document.querySelector('#msg_error');

		// 	if (!fileInput.files || fileInput.files.length === 0) {
		// 		e.preventDefault();
		// 		msg_error.classList.remove('hidden');
		// 		msg_error.innerHTML = '* Es requerido adjuntar Evidencia de Mantenimiento (Foto)';
		// 		return;
		// 	}
		// }

    e.preventDefault();
		Service.stopSubmit(e.target, false);
  
    const formData = new FormData(e.target);
    Service.show('.loading');

		// debugFormData(formData); return;

    Service.exec('post', `/edit_mant`, formData_header, formData)
    .then( r => {
      // console.log(r); return;

      if(r){

        form_ticket.reset();
				msg_error.classList.add('hidden');

        let modal_active = document.querySelector('.modal_active');
        if (modal_active) {
          modal_active.classList.add('hidden');
          modal_active.classList.remove('modal_active');
        }

        let modal_success = document.querySelector('#edit_success');
        if (modal_success) {
          modal_success.classList.add('modal_active');
          modal_success.classList.remove('hidden');
        }

				loadAllTickets();  // load all articles with filtros de busqueda

        Service.hide('.loading');
        // return;
      }
    });
  });



  const renderTickets = (data) => {  
    order_select.value = "";
    results_container.innerHTML = "";
    // console.log(data); return;

    if (data.length > 0) {

      data.forEach(ticket => {
        const row = document.createElement('tr');

				row.classList.add('btn_open_modal');
				row.setAttribute('data-modal', 'modal_ticket');
				row.setAttribute('data-id', ticket.id);
				// <td><div class="row__cell">${ticket.solicitante}</div></td>

        row.innerHTML =
          `		          
            <td><div class="row__cell">${format_id(ticket.id, 'id')}</div></td>
						<td><div class="row__cell">${ticket.name} ${ticket.last_name}</div></td>
						<td class="hidden lg:flex"><div class="row__cell">${ticket.planta}</div></td>
						<td class="hidden lg:flex"><div class="row__cell">${ticket.linea}</div></td>
						<td><div class="row__cell">${ticket.nombre}</div></td>
						<td><div class="row__cell ${setTicketStatus(ticket.estado_ticket).color}">${setTicketStatus(ticket.estado_ticket).text}</div></td>
						<td><div class="row__cell">${ticket.prioridad}</div></td>
						<td class="hidden lg:flex"><div class="row__cell">${ticket.estado_maq}</div></td>
						<td><div class="row__cell">${ellipsis(ticket.asunto, 12)}</div></td>
						<td class="hidden lg:flex"><div class="row__cell">${ticket.descripcion}</div></td>
						<td><div class="row__cell">${dateToString(ticket.created_at)}</div></td>
						<td><div class="row__cell ${ticket.days_remaining < 0 ? 'text-red' : 'text-gray' }">${ticket.days_remaining}</div></td>
          `
        results_container.appendChild(row);
      });

      initRowBtn();
    } else {
      initRowBtn();

      Service.show('#row__empty');
    }
  }

  const loadAllTickets = () => {
    Service.hide('#row__empty');
    results_container.innerHTML = Service.loader();

    Service.exec('get', `/all_tickets`)
    .then(r => renderTickets(r));  
  }

  loadAllTickets();


</script>
</body>
</html>