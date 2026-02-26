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
					<span>Agregar Producto</span>
				</button>
			</div>
    </div>

    <div class="w-full text-sm text-gray  ">
      <div class="flex flex-col h-full" >

        <div class="w-full pl-10 pr-8 ">
					<div class="relative w-full text-sm h-[70vh] overflow-y-scroll mx-auto">
            <table id="tabla-produccion-productos">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Codigo</th>
                  <th>Descripcion</th>
                  <th>Linea</th>
                  <th>Peso o Volumen</th>
                  <th>Unidad Medida</th>
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

				<div class="w-full mx-auto relative grid grid-cols-1 lg:grid-cols-4 gap-4 ">
					<div class="w-full relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Codigo</h5>
						<input type="text" class="input_modal" name="codigo" placeholder="Codigo" >
					</div>
					<div class="w-full relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Linea</h5>
						<select id="linea_create" class="select_modal" name="linea" class="text-center to_uppercase" >
							<option value="" disabled selected>Seleccionar...</option>
							<?php foreach ($lineas as $linea): ?>
								<option value="<?= $linea['linea'] ?>"><?= $linea['linea'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="w-full relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Unidad Medida</h5>
						<input type="text" class="input_modal" name="unidad_medida" placeholder="Unidad Medida" >
					</div>
					<div class="w-full relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Peso o Volumen</h5>
						<input type="text" class="input_modal" name="peso_volumen" placeholder="Peso o Volumen" >
					</div>
				</div>

				<div class="w-full relative flex flex-col md:flex-row items-center gap-x-4 ">

					<div class=" relative flex flex-col w-full">
						<h5 class="text-center uppercase">Descripcion</h5>
						<input type="text" class="input_modal" name="descripcion" placeholder="Descripcion" >
					</div>
				</div>


				<div id="items_general" class="relative w-full mx-auto border border-grayMid p-4 grid grid-cols-1 lg:grid-cols-3 items-center justify-center gap-4">

					<!-- Mesofilos -->
					<div id="mesofilos_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">MESOFILOS UFC /g</h5>
						<input type="text" class="input_modal" name="mesofilos" id="mesofilos" placeholder="MESOFILOS UFC /g">
					</div>

					<!-- Coliformes -->
					<div id="coliformes_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">COLIFORMES UFC /gE.</h5>
						<input type="text" class="input_modal" name="coliformes" id="coliformes" placeholder="COLIFORMES UFC /gE.">
					</div>

					<!-- E. Coli -->
					<div id="coli_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">COLI UFC /g</h5>
						<input type="text" class="input_modal" name="coli" id="coli" placeholder="COLI UFC /g">
					</div>

					<!-- Hongos y Levaduras -->
					<div id="hongos_levaduras_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">HONGOS Y LEVADURAS UFC /g</h5>
						<input type="text" class="input_modal" name="hongos_levaduras" id="hongos_levaduras" placeholder="HONGOS Y LEVADURAS UFC /g">
					</div>

					<!-- Color -->
					<div id="color_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">COLOR</h5>
						<input type="text" class="input_modal" name="color" id="color" placeholder="COLOR">
					</div>

					<!-- Sabor -->
					<div id="sabor_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">SABOR</h5>
						<input type="text" class="input_modal" name="sabor" id="sabor" placeholder="SABOR">
					</div>

					<!-- Olor -->
					<div id="olor_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">OLOR</h5>
						<input type="text" class="input_modal" name="olor" id="olor" placeholder="OLOR">
					</div>

					<!-- Descripción -->
					<div id="descripcion_visual_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">DESCRIPCIÓN VISUAL</h5>
						<input type="text" class="input_modal" name="descripcion_visual" id="descripcion_visual" placeholder="DESCRIPCIÓN VISUAL">
					</div>
					<!-- MF -->
					<div id="mf_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">MF</h5>
						<input type="text" class="input_modal" name="mf" id="mf" placeholder="MF">
					</div>

					<!-- Tiempo Desintegracion -->
					<div id="tiempo_desintegracion_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">Tiempo Desintegracion</h5>
						<input type="text" class="input_modal" name="tiempo_desintegracion" id="tiempo_desintegracion" placeholder="Tiempo Desintegracion">
					</div>
				</div>

				<div id="items_polvos" class="hidden relative w-full mx-auto border border-grayMid p-4 grid grid-cols-1 lg:grid-cols-3 items-center justify-center gap-4">

					<!-- P. Especifico Min -->
					<div id="p_especifico_min_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">P. ESPECIFICO MIN</h5>
						<input type="text" class="input_modal" name="p_especifico_min" id="p_especifico_min" placeholder="P. ESPECIFICO MIN">
					</div>

					<!-- P. Especifico Max -->
					<div id="p_especifico_max_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">P. ESPECIFICO MAX</h5>
						<input type="text" class="input_modal" name="p_especifico_max" id="p_especifico_max" placeholder="P. ESPECIFICO MAX">
					</div>

					<!-- % Humedad Polvos -->
					<div id="humedad_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">% HUMEDAD</h5>
						<input type="text" class="input_modal" name="humedad" id="humedad" placeholder="% HUMEDAD">
					</div>

					<!-- Volumen -->
					<div id="volumen_polvo_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">Volumen</h5>
						<input type="text" class="input_modal" name="volumen_polvo" id="volumen_polvo" placeholder="Volumen">
					</div>

				</div>

				<div id="items_liquidos_geles" class="hidden relative w-full mx-auto border border-grayMid p-4 grid grid-cols-1 lg:grid-cols-4 items-center justify-center gap-4">

					<!-- BRIX MIN -->
					<div id="brix_min_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">BRIX MIN</h5>
						<input type="text" class="input_modal" name="brix_min" id="brix_min" placeholder="BRIX MIN">
					</div>

					<!-- BRIX MAX -->
					<div id="brix_max_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">BRIX MAX</h5>
						<input type="text" class="input_modal" name="brix_max" id="brix_max" placeholder="BRIX MAX">
					</div>

					<!-- PH MIN -->
					<div id="ph_min_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">PH MIN</h5>
						<input type="text" class="input_modal" name="ph_min" id="ph_min" placeholder="PH MIN">
					</div>

					<!-- PH MAX -->
					<div id="ph_max_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">PH MAX</h5>
						<input type="text" class="input_modal" name="ph_max" id="ph_max" placeholder="PH MAX">
					</div>

					<!-- ACIDEZ MIN -->
					<div id="acidez_min_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">ACIDEZ MIN</h5>
						<input type="text" class="input_modal" name="acidez_min" id="acidez_min" placeholder="ACIDEZ MIN">
					</div>

					<!-- ACIDEZ MAX -->
					<div id="acidez_max_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">ACIDEZ MAX</h5>
						<input type="text" class="input_modal" name="acidez_max" id="acidez_max" placeholder="ACIDEZ MAX">
					</div>

					<!-- DENSIDAD MIN -->
					<div id="densidad_min_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">DENSIDAD MIN</h5>
						<input type="text" class="input_modal" name="densidad_min" id="densidad_min" placeholder="DENSIDAD MIN">
					</div>

					<!-- DENSIDAD MAX -->
					<div id="densidad_max_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">DENSIDAD MAX</h5>
						<input type="text" class="input_modal" name="densidad_max" id="densidad_max" placeholder="DENSIDAD MAX">
					</div>

					<!-- Volumen -->
					<div id="volumen_liquido_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">Volumen</h5>
						<input type="text" class="input_modal" name="volumen_liquido" id="volumen_liquido" placeholder="Volumen">
					</div>

				</div>


				<div id="items_capsulas" class="hidden relative w-full mx-auto border border-grayMid p-4 grid grid-cols-1 lg:grid-cols-3 items-center justify-center gap-4">

					

					<!-- Densidad -->
					<div id="densidad_capsula_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">Densidad</h5>
						<input type="text" class="input_modal" name="densidad_capsula" id="densidad_capsula" placeholder="Densidad">
					</div>

					<!-- % Humedad -->
					<div id="humedad_capsula_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">% HUMEDAD</h5>
						<input type="text" class="input_modal" name="humedad_capsula" id="humedad_capsula" placeholder="% HUMEDAD">
					</div>

					<!-- Contenido de capsula -->
					<div id="contenido_capsula_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">Contenido de capsula</h5>
						<input type="text" class="input_modal" name="contenido_capsula" id="contenido_capsula" placeholder="Contenido de capsula">
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
				
				<div class="w-full mx-auto relative grid grid-cols-1 lg:grid-cols-4 gap-4 ">
					<div class="w-full relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Codigo</h5>
						<input type="text" class="input_modal" name="codigo" placeholder="Codigo" >
					</div>
					<div class="w-full relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Linea</h5>
						<select id="linea_edit"  class="select_modal" name="linea" class="text-center to_uppercase" >
							<option value="" disabled selected>Seleccionar...</option>
							<?php foreach ($lineas as $linea): ?>
								<option value="<?= $linea['linea'] ?>"><?= $linea['linea'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="w-full relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Unidad Medida</h5>
						<input type="text" class="input_modal" name="unidad_medida" placeholder="Unidad Medida" >
					</div>
					<div class="w-full relative flex flex-col w-1/2 ">
						<h5 class="text-center uppercase">Peso o Volumen</h5>
						<input type="text" class="input_modal" name="peso_volumen" placeholder="Peso o Volumen" >
					</div>
				</div>



				<div class="w-full relative flex flex-col md:flex-row items-center gap-x-4 ">

					<div class=" relative flex flex-col w-full">
						<h5 class="text-center uppercase">Descripcion</h5>
						<input type="text" class="input_modal" name="descripcion" placeholder="Descripcion" >
					</div>
				</div>


				<div id="items_general" class="relative w-full mx-auto border border-grayMid p-4 grid grid-cols-1 lg:grid-cols-3 items-center justify-center gap-4">

					<!-- Mesofilos -->
					<div id="mesofilos_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">MESOFILOS UFC /g</h5>
						<input type="text" class="input_modal" name="mesofilos" id="mesofilos" placeholder="MESOFILOS UFC /g">
					</div>

					<!-- Coliformes -->
					<div id="coliformes_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">COLIFORMES UFC /gE.</h5>
						<input type="text" class="input_modal" name="coliformes" id="coliformes" placeholder="COLIFORMES UFC /gE.">
					</div>

					<!-- E. Coli -->
					<div id="coli_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">COLI UFC /g</h5>
						<input type="text" class="input_modal" name="coli" id="coli" placeholder="COLI UFC /g">
					</div>

					<!-- Hongos y Levaduras -->
					<div id="hongos_levaduras_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">HONGOS Y LEVADURAS UFC /g</h5>
						<input type="text" class="input_modal" name="hongos_levaduras" id="hongos_levaduras" placeholder="HONGOS Y LEVADURAS UFC /g">
					</div>

					<!-- Color -->
					<div id="color_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">COLOR</h5>
						<input type="text" class="input_modal" name="color" id="color" placeholder="COLOR">
					</div>

					<!-- Sabor -->
					<div id="sabor_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">SABOR</h5>
						<input type="text" class="input_modal" name="sabor" id="sabor" placeholder="SABOR">
					</div>

					<!-- Olor -->
					<div id="olor_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">OLOR</h5>
						<input type="text" class="input_modal" name="olor" id="olor" placeholder="OLOR">
					</div>

					<!-- Descripción -->
					<div id="descripcion_visual_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">DESCRIPCIÓN VISUAL</h5>
						<input type="text" class="input_modal" name="descripcion_visual" id="descripcion_visual" placeholder="DESCRIPCIÓN">
					</div>
					<!-- MF -->
					<div id="mf_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">MF</h5>
						<input type="text" class="input_modal" name="mf" id="mf" placeholder="MF">
					</div>

					<!-- Tiempo Desintegracion -->
					<div id="tiempo_desintegracion_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">Tiempo Desintegracion</h5>
						<input type="text" class="input_modal" name="tiempo_desintegracion" id="tiempo_desintegracion" placeholder="Tiempo Desintegracion">
					</div>
				</div>

				<div id="items_polvos" class="hidden relative w-full mx-auto border border-grayMid p-4 grid grid-cols-1 lg:grid-cols-3 items-center justify-center gap-4">

					<!-- P. Especifico Min -->
					<div id="p_especifico_min_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">P. ESPECIFICO MIN</h5>
						<input type="text" class="input_modal" name="p_especifico_min" id="p_especifico_min" placeholder="P. ESPECIFICO MIN">
					</div>

					<!-- P. Especifico Max -->
					<div id="p_especifico_max_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">P. ESPECIFICO MAX</h5>
						<input type="text" class="input_modal" name="p_especifico_max" id="p_especifico_max" placeholder="P. ESPECIFICO MAX">
					</div>

					<!-- % Humedad -->
					<div id="humedad_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">% HUMEDAD</h5>
						<input type="text" class="input_modal" name="humedad" id="humedad" placeholder="% HUMEDAD">
					</div>

					<!-- Volumen -->
					<div id="volumen_polvo_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">Volumen</h5>
						<input type="text" class="input_modal" name="volumen_polvo" id="volumen_polvo" placeholder="Volumen">
					</div>
				</div>

				<div id="items_liquidos_geles" class="hidden relative w-full mx-auto border border-grayMid p-4 grid grid-cols-1 lg:grid-cols-4 items-center justify-center gap-4">

					<!-- BRIX MIN -->
					<div id="brix_min_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">BRIX MIN</h5>
						<input type="text" class="input_modal" name="brix_min" id="brix_min" placeholder="BRIX MIN">
					</div>

					<!-- BRIX MAX -->
					<div id="brix_max_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">BRIX MAX</h5>
						<input type="text" class="input_modal" name="brix_max" id="brix_max" placeholder="BRIX MAX">
					</div>

					<!-- PH MIN -->
					<div id="ph_min_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">PH MIN</h5>
						<input type="text" class="input_modal" name="ph_min" id="ph_min" placeholder="PH MIN">
					</div>

					<!-- PH MAX -->
					<div id="ph_max_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">PH MAX</h5>
						<input type="text" class="input_modal" name="ph_max" id="ph_max" placeholder="PH MAX">
					</div>

					<!-- ACIDEZ MIN -->
					<div id="acidez_min_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">ACIDEZ MIN</h5>
						<input type="text" class="input_modal" name="acidez_min" id="acidez_min" placeholder="ACIDEZ MIN">
					</div>

					<!-- ACIDEZ MAX -->
					<div id="acidez_max_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">ACIDEZ MAX</h5>
						<input type="text" class="input_modal" name="acidez_max" id="acidez_max" placeholder="ACIDEZ MAX">
					</div>

					<!-- DENSIDAD MIN -->
					<div id="densidad_min_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">DENSIDAD MIN</h5>
						<input type="text" class="input_modal" name="densidad_min" id="densidad_min" placeholder="DENSIDAD MIN">
					</div>

					<!-- DENSIDAD MAX -->
					<div id="densidad_max_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">DENSIDAD MAX</h5>
						<input type="text" class="input_modal" name="densidad_max" id="densidad_max" placeholder="DENSIDAD MAX">
					</div>

					<!-- Volumen -->
					<div id="volumen_liquido_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">Volumen</h5>
						<input type="text" class="input_modal" name="volumen_liquido" id="volumen_liquido" placeholder="Volumen">
					</div>
				</div>

				<div id="items_capsulas" class="hidden relative w-full mx-auto border border-grayMid p-4 grid grid-cols-1 lg:grid-cols-3 items-center justify-center gap-4">

					
					<!-- Densidad -->
					<div id="densidad_capsula_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">Densidad</h5>
						<input type="text" class="input_modal" name="densidad_capsula" id="densidad_capsula" placeholder="Densidad">
					</div>

					<!-- % Humedad -->
					<div id="humedad_capsula_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">% HUMEDAD</h5>
						<input type="text" class="input_modal" name="humedad_capsula" id="humedad_capsula" placeholder="% HUMEDAD">
					</div>

					<!-- Contenido de capsula -->
					<div id="contenido_capsula_container" class="w-full flex flex-col w-1/2 ">
						<h5 class="text-center uppercase text-xs">Contenido de capsula</h5>
						<input type="text" class="input_modal" name="contenido_capsula" id="contenido_capsula" placeholder="Contenido de capsula">
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

  Service.setLoading();

	const select_linea_create= document.querySelector("#linea_create");

	select_linea_create.addEventListener("change", (e) => {
		const selectedValue = e.target.value;
		
		// Get the containers
		const itemsLiquidosGeles = form_create.querySelector("#items_liquidos_geles");
		const itemsPolvos = form_create.querySelector("#items_polvos");
		const itemsCapsulas = form_create.querySelector("#items_capsulas");

		itemsLiquidosGeles.classList.add("hidden");
		itemsPolvos.classList.add("hidden");
		itemsCapsulas.classList.add("hidden");

		// Show the appropriate container based on selected value
		if (selectedValue === "LIQUIDOS" || selectedValue === "GELES") {
			itemsLiquidosGeles.classList.remove("hidden");
		} else if (selectedValue === "POLVOS") {
			itemsPolvos.classList.remove("hidden");
		} else if (selectedValue === "CAPSULAS") {
			itemsCapsulas.classList.remove("hidden");
		}
	});

	const select_linea_edit= document.querySelector("#linea_edit");

	select_linea_edit.addEventListener("change", (e) => {
		const selectedValue = e.target.value;
		
		// Get the containers
		const itemsLiquidosGeles = form_edit.querySelector("#items_liquidos_geles");
		const itemsPolvos = form_edit.querySelector("#items_polvos");
		const itemsCapsulas = form_edit.querySelector("#items_capsulas");

		itemsLiquidosGeles.classList.add("hidden");
		itemsPolvos.classList.add("hidden");
		itemsCapsulas.classList.add("hidden");

		// Show the appropriate container based on selected value
		if (selectedValue === "LIQUIDOS" || selectedValue === "GELES") {
			itemsLiquidosGeles.classList.remove("hidden");
		} else if (selectedValue === "POLVOS") {
			itemsPolvos.classList.remove("hidden");
		} else if (selectedValue === "CAPSULAS") {
			itemsCapsulas.classList.remove("hidden");
		}
	});


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
						setSelectedOption('#form_edit #linea_edit', r.linea, 'string');

						handleLineaChangeEdit(r.linea);

						form_edit.querySelector('[name="mesofilos"]').value = r.mesofilos ?? '';
						form_edit.querySelector('[name="coliformes"]').value = r.coliformes ?? '';
						form_edit.querySelector('[name="coli"]').value = r.coli ?? '';
						form_edit.querySelector('[name="hongos_levaduras"]').value = r.hongos_levaduras ?? '';
						form_edit.querySelector('[name="color"]').value = r.color ?? '';
						form_edit.querySelector('[name="sabor"]').value = r.sabor ?? '';
						form_edit.querySelector('[name="olor"]').value = r.olor ?? '';
						form_edit.querySelector('[name="descripcion_visual"]').value = r.descripcion_visual ?? '';
						form_edit.querySelector('[name="p_especifico_min"]').value = r.p_especifico_min ?? '';
						form_edit.querySelector('[name="p_especifico_max"]').value = r.p_especifico_max ?? '';
						form_edit.querySelector('[name="humedad"]').value = r.humedad ?? '';
						form_edit.querySelector('[name="brix_min"]').value = r.brix_min ?? '';
						form_edit.querySelector('[name="brix_max"]').value = r.brix_max ?? '';
						form_edit.querySelector('[name="ph_min"]').value = r.ph_min ?? '';
						form_edit.querySelector('[name="ph_max"]').value = r.ph_max ?? '';
						form_edit.querySelector('[name="acidez_min"]').value = r.acidez_min ?? '';
						form_edit.querySelector('[name="acidez_max"]').value = r.acidez_max ?? '';
						form_edit.querySelector('[name="densidad_min"]').value = r.densidad_min ?? '';
						form_edit.querySelector('[name="densidad_max"]').value = r.densidad_max ?? '';

						form_edit.querySelector('[name="tiempo_desintegracion"]').value = r.tiempo_desintegracion ?? '';
						form_edit.querySelector('[name="desintegracion_capsula"]').value = r.desintegracion_capsula ?? '';
						form_edit.querySelector('[name="densidad_capsula"]').value = r.densidad_capsula ?? '';
						form_edit.querySelector('[name="contenido_capsula"]').value = r.contenido_capsula ?? '';
						form_edit.querySelector('[name="humedad_capsula"]').value = r.humedad_capsula ?? '';
						form_edit.querySelector('[name="mf"]').value = r.mf ?? '';
						form_edit.querySelector('[name="volumen_liquido"]').value = r.volumen_liquido ?? '';
						form_edit.querySelector('[name="volumen_polvo"]').value = r.volumen_polvo ?? '';

          });


				}
      });
    });

  }


const handleLineaChangeEdit = (selectedLinea) => {
  // Get the containers
  const itemsLiquidosGeles = form_edit.querySelector("#items_liquidos_geles");
  const itemsPolvos = form_edit.querySelector("#items_polvos");
  const itemsCapsulas = form_edit.querySelector("#items_capsulas");

  // Hide both containers initially
  itemsLiquidosGeles.classList.add("hidden");
  itemsPolvos.classList.add("hidden");
  itemsCapsulas.classList.add("hidden");

  // Show the appropriate container based on the selected "linea"
  if (selectedLinea === "LIQUIDOS" || selectedLinea === "GELES") {
    itemsLiquidosGeles.classList.remove("hidden");
  } else if (selectedLinea === "POLVOS") {
    itemsPolvos.classList.remove("hidden");
  } else if (selectedLinea === "CAPSULAS") {
    itemsCapsulas.classList.remove("hidden");
  }
};


const tbody = document.querySelector('#tabla-produccion-productos tbody');
const renderRows = (data) => {  

    tbody.innerHTML = "";
    // console.log(data); return;

    if (data.length > 0) {
      Service.hide('#row__empty');


      data.forEach(prod => {
        const row = document.createElement('tr');

        row.innerHTML =
          `
            <td>
              <span>${format_id(prod.id, 'id')}</span>
            </td>
						<td>
              <span>${prod.codigo}</span>
            </td>
						<td>
              <span>${prod.descripcion}</span>
            </td>
						<td>
              <span>${prod.linea}</span>
            </td>
						<td>
              <span>${prod.peso_volumen}</span>
            </td>
						<td>
              <span>${prod.unidad_medida}</span>
            </td>
            <td>
              <div class="row__actions ">
								<button data-id="${prod.id}" class="btn_edit hover:text-icon pr-2" data-modal="modal_edit" type="button"><i class="fas fa-pencil text-lg"></i>
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

  const loadProductos = () => {
    Service.hide('#row__empty');
    tbody.innerHTML = Service.loader();

    Service.exec('get', `produccion/all_productos`)
    .then(r => renderRows(r));  
  }

  loadProductos();

</script>
</body>
</html>