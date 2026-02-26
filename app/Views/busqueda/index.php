<?php echo view('_partials/header'); ?>
  <link rel="stylesheet" href="<?= load_asset('_partials/busqueda.css') ?>">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">
  <img src="<?= base_url('img/laboratorio.svg') ?>" class="hidden md:flex absolute bottom-0 -left-2 ">

  <div class=" relative h-full flex flex-col items-center justify-center font-titil ">

    <?php echo view('cotizar/_partials/navbar'); ?>

    <div class="text-title w-full md:pt-4 md:pb-8 md:px-16 p-2 flex items-center ">
      <h2 class="text-center font-bold w-full text-3xl "><?= esc($title) ?></h2>
      <a href="<?= base_url('apps') ?>" class="self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>

    <div class="w-full text-sm text-gray ">
      <div class="flex flex-col h-full" >

        <form id="form_search" class="search-row pb-6 px-10 flex w-full items-center justify-center " method="post">

          <?= csrf_field() ?>

          <div class="relative flex flex-col pr-4 w-64">
            <h5 class="text-center uppercase">Proveedor</h5>
            <input type="text" class=" to_uppercase" id="proveedor" placeholder="Razón social de proveedor" >
						<input type="hidden" id="proveedor_name" name="proveedor">
            <ul id="lista_prov" class="absolute z-40 top-12 bg-grayLight border border-super w-full h-32 overflow-y-scroll"></ul>
          </div>

          <div class="relative flex flex-col pr-4  w-64">
            <h5 class="text-center uppercase">Articulo</h5>
            <input type="text" class=" to_uppercase" id="articulo" name="articulo" placeholder="Nombre de artículo" >
            <ul id="lista_articulos" class="absolute z-40 top-12 bg-grayLight border border-super w-full h-32 overflow-y-scroll"></ul>
          </div>

          <div class="flex flex-col pr-4 w-32 ">
            <h5 class="text-center uppercase">Fecha</h5>
            <input id="fecha" type="date" name="fecha" >
          </div>

          <div class="flex flex-col pr-4  w-28">
            <h5 class="text-center uppercase">Vigencia</h5>
            <select name="vigencia" id="vigencia">
              <option value="" disabled selected>Seleccionar...</option>
              <option value="3 Meses">3 Meses</option>
              <option value="6 Meses">6 Meses</option>
              <option value="1 Año">1 Año</option>
            </select>
          </div>

          <div class="flex flex-col pr-4  w-32">
            <h5 class="text-center uppercase">Origen</h5>
            <select name="origen" id="origen" >
              <option value="" selected>Seleccionar...</option>
              <option value="nacional">Nacional</option>
              <option value="importado">Importado</option>
            </select>
          </div>

          <div class="flex flex-col pr-4  w-28">
            <h5 class="text-center uppercase">Divisa</h5>
            <select name="divisa" >
              <option value="" selected >Seleccionar...</option>
              <option>USD</option>
              <option>MXN</option>
              <option>EUR</option>
              <option>JPY</option>
              <option>GBP</option>
            </select>
          </div>

          <div class="flex flex-col pr-4  w-36">
            <h5 class="text-center uppercase">Impuesto</h5>
            <select name="impuesto" >
              <option value="" selected>Seleccionar...</option>
              <option>16%</option>
              <option>0%</option>
              <option>Ex.I</option>
            </select>
          </div>

          <button class="btn btn-md btn--cta" type="submit"><i class="fas fa-search"></i><span>BUSCAR</span></button>
        </form>

        <div class="search-cotiz-busqueda pb-4 px-10 flex w-fit items-center space-x-4">
          <span>ORDENAR POR:</span>
          <select id="order_by" name="order_by" class="w-44">
            <option value="" disabled selected>Seleccionar...</option>
            <option value="asc-costo">$ - $$$</option>
            <option value="desc-costo">$$$ - $</option>
            <option value="asc-dias">- Tiempo Entrega</option>
            <option value="desc-dias">+ Tiempo Entrega</option>
            <option value="asc-nombre">A - Z</option>
            <option value="desc-nombre">Z - A</option>
          </select>
        </div>

        <div class="w-full pl-8 pr-6 ">
					<div class="relative w-full text-sm h-[55vh] overflow-y-scroll ">
            <table id="table_results" class="tabla-cotiz-busqueda">
              <thead>
                <tr>
                  <th>Nombre del artículo</th>
                  <th>Proveedor</th>
                  <th>
                    <div class="row__costo">
                      <span>C. unit.</span>
                      <span>Divisa</span>
                      <span>Imp.</span>
                    </div>
                  </th>
                  <th>
                    <div class="row__minimo">
                      <span>Minimo</span>
                      <span>Und.</span>
                      <span>Importe</span>
                    </div>
                  </th>
                  <th>Fecha</th>
                  <th>Tiempo de Entrega</th>
                  <th>
                    <div class="row__origen">
                      <span>Origen</span>
                      <span>Incot.</span>
                    </div>
                  </th>
                  <th>
                    <div class="row__aprobacion">
                      <span>Cos</span>
                      <span>Des</span>
                      <span>Cal</span>
                    </div>
                  </th>
                  <th>Cot.</th>
                  <th>F.T.</th>
                  <th>Coment.</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody id="results_container"></tbody>
            </table>
            
            <div id="row__empty" class="row__empty">No hay datos.</div>
          </div>
        </div>

      </div>
    </div>

  </div>

<!-- Modal Details -->
<div id="modal_details" class="hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
  <form id="form_details" method="post" class="relative flex flex-col space-y-8 bg-white border-2 border-icon p-10 w-full md:max-w-6xl ">
    <div id="_overlay" class=" hidden absolute bg-white bg-opacity-70 z-40 -top-8 left-0 h-full w-full flex items-center justify-center"><span class="loader"></span></div>

    <div class="relative flex w-full justify-center text-center ">
      <h3 class="text-gray text-xl uppercase"> Detalles </h3>
      <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
    </div>

    <div class="w-full flex flex-col space-y-8 ">
      <div class="flex w-full items-center justify-between text-sm text-gray ">

        <div class="relative flex flex-col  w-72">
          <h5 class="text-center uppercase">Nombre Del Articulo</h5>
          <input type="text" name="nombreDelArticulo" class="required_gray to_uppercase" required>
        </div>

        <div class="relative flex flex-col  w-72">
          <h5 class="text-center uppercase">Proveedor</h5>
          <input type="text" name="proveedor" class="to_uppercase" readonly>
        </div>

        <div class="flex flex-col w-28 ">
          <h5 class="text-center uppercase">Fecha</h5>
          <input type="text" name="fecha" class="to_uppercase" readonly>
        </div>

        <div class="flex flex-col  w-28">
          <div class="relative flex flex-col  w-28">
            <h5 class="text-center uppercase">Origen</h5>
            <input type="text" name="origen" class="to_uppercase" readonly>
          </div>
        </div>

        <div class="flex flex-col w-24 ">
          <h5 class="text-center uppercase">Vigencia</h5>
          <input type="text" name="vigencia" class="to_uppercase" readonly>
        </div>

        <div class=" flex flex-col w-28 ">
          <h5 class="text-center uppercase">IncorTerms</h5>
          <input type="text" name="incoterm" class="to_uppercase" readonly>
        </div>

      </div>

      <div class="flex w-full items-center justify-between text-sm text-gray pb-8 ">

        <div class="relative flex flex-col w-28">
          <h5 class="text-center uppercase">Costo Unitario</h5>
          <input name="costoPorUnidad" type="number" step="0.000001" min="0.000001" oninput="calcularImporte(this)" required readonly>
        </div>

				<div class="relative flex flex-col w-28">
          <h5 class="text-center uppercase">Costo Unitario</h5>
          <input name="divisa" type="text" required readonly>
        </div>

				<div class="relative flex flex-col w-28">
          <h5 class="text-center uppercase">Impuesto</h5>
          <input name="impuesto" type="text" required readonly>
        </div>

				<div class="relative flex flex-col w-28">
          <h5 class="text-center uppercase">Unidad Medida</h5>
          <input name="medicion" type="text" required readonly>
        </div>

        <div class="relative flex flex-col w-32">
          <h5 class="text-center uppercase">Minimo de Compra</h5>
          <input name="minimo" type="number" step="0.001" min="0.001" required readonly >
        </div>

        <div class="relative flex flex-col ">
          <h5 class="text-center uppercase">Tiempo de Entrega</h5>
          <div class="flex space-x-2 items-center">
            <input type="hidden" id="diasDeEnvio" name="diasDeEnvio" >

						<div class="relative flex flex-col w-32">
							<input id="cantidadPer" name="cantidadPer" type="text" required readonly >
						</div>

						<div class="relative flex flex-col w-32">
							<input name="periodo" type="text" required readonly >
						</div>

						<div class="relative flex flex-col w-32">
							<input name="tipoDia" type="text" required readonly >
						</div>

          </div>
        </div>


      </div>

			<div id="condiciones_container"class="flex flex-col gap-y-2 w-full items-center justify-between text-sm text-gray ">
			</div>

    </div>


    <!-- <div id="add_files" class=" mx-auto w-2/3 flex flex-col space-y-4  items-center ">
      <div id="dragDropContainer" class="drag-drop-container relative flex flex-col items-center justify-start p-4 w-full border-2 border-dashed border-super bg-grayLight h-44 overflow-y-auto text-super ">
        <span class="absolute top-20 left-0 right-0 text-center">Arrastre y suelte sus archivos para agregarlos.</span>
        <input type="file" id="modal_file_input" class="hidden" multiple>
        <ul id="fileList" class="file-list w-full grid grid-cols-5 gap-4 py-4 "></ul>
      </div>

      <div class="flex justify-end space-x-12 text-sm ">
        <button id="btn_open_files" class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
          ABRIR CARPETA
        </button>
        <button id="btn_add_files" class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
          GUARDAR
        </button>
      </div>

    </div>

    <div class="flex justify-end space-x-12 text-sm ">
      <p id="files_loaded" class=" text-title text-lg "></p>

      <button class=" flex space-x-4 items-center shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit" >
        <span>ACTUALIZAR</span>
      </button>

    </div> -->

    <input id="file_input" class="hidden" type="file" name="archivo[]" multiple>
    <input type="hidden" id="importe" name="importe">
  </form>
</div>


<!-- Modal Edit -->
<div id="modal_edit" class="hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
  <form id="form_edit" method="post" class="relative flex flex-col space-y-8 bg-white border-2 border-icon p-10 w-full md:max-w-6xl ">
    <div id="_overlay" class=" hidden absolute bg-white bg-opacity-70 z-40 -top-8 left-0 h-full w-full flex items-center justify-center"><span class="loader"></span></div>

    <div class="relative flex w-full justify-center text-center ">
      <h3 class="text-gray text-xl uppercase"> Editar</h3>
      <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
    </div>

    <div class="w-full flex flex-col space-y-8 ">
      <div class="flex w-full items-center justify-between text-sm text-gray ">

        <div class="relative flex flex-col  w-72">
          <h5 class="text-center uppercase">Nombre Del Articulo</h5>
          <input type="text" name="nombreDelArticulo" class="input_modal to_uppercase" required>
        </div>

        <div class="relative flex flex-col  w-72">
          <h5 class="text-center uppercase">Proveedor</h5>
          <input type="text" name="proveedor" class="to_uppercase" readonly>
        </div>

        <div class="flex flex-col w-28 ">
          <h5 class="text-center uppercase">Fecha</h5>
          <input type="text" name="fecha" class="to_uppercase" readonly>
        </div>

        <div class="flex flex-col  w-28">
          <div class="relative flex flex-col  w-28">
            <h5 class="text-center uppercase">Origen</h5>
            <input type="text" name="origen" class="to_uppercase" readonly>
          </div>
        </div>

        <div class="flex flex-col w-24 ">
          <h5 class="text-center uppercase">Vigencia</h5>
          <input type="text" name="vigencia" class="to_uppercase" readonly>
        </div>

        <div class=" flex flex-col w-28 ">
          <h5 class="text-center uppercase">IncorTerms</h5>
          <input type="text" name="incoterm" class="to_uppercase" readonly>
        </div>

      </div>

      <div class="flex w-full items-center justify-between text-sm text-gray ">

        <div class="relative flex flex-col w-28">
          <h5 class="text-center uppercase">Costo Unitario</h5>
          <input name="costoPorUnidad" type="number" step="0.000001" min="0.000001" oninput="calcularImporte(this)" class="input_modal" required >
        </div>

        <div class="relative flex flex-col  ">
          <h5 class="text-center uppercase">Divisa</h5>
          <select id="divisa" name="divisa" required>
                <!-- setSelectedOption('#status', 'USD'); -->
            <option value="USD">USD</option>
            <option value="MXN">MXN</option>
            <option value="EUR">EUR</option>
            <option value="JPY">JPY</option>
            <option value="GBP">GBP</option>
          </select>
        </div>

        <div class="relative flex flex-col  ">
          <h5 class="text-center uppercase">Impuesto</h5>
          <select id="impuesto" name="impuesto" required>
            <option value="16%">16%</option>
            <option value="0%">0%</option>
            <option value="Ex.I">Ex.I</option>
          </select>
        </div>

        <div class="relative flex flex-col ">
          <h5 class="text-center uppercase">Unidad Medida</h5>
          <select id="medicion" name="medicion" required>
            <option value="KG">KG</option>
            <option value="PZ">PZ</option>
            <option value="LT">LT</option>
            <option value="LB">LB</option>
            <option value="GL">GL</option>
            <option value="OZ">OZ</option>
          </select>
        </div>

        <div class="relative flex flex-col ">
          <h5 class="text-center uppercase">Minimo de Compra</h5>
          <input name="minimo" type="number" class="input_modal" step="0.001" min="0.001" required >
        </div>

        <div class="relative flex flex-col ">
          <h5 class="text-center uppercase">Tiempo de Entrega</h5>
          <div class="flex space-x-2 items-center">
            <input type="hidden" id="diasDeEnvio" name="diasDeEnvio" >
            <input id="cantidadPer" name="cantidadPer" class="input_modal" type="number" step="1" min="1" required>
            <select id="periodo" name="periodo" required>
              <option value="DIA">Dia(s)</option>
              <option value="SEMANA">Semana(s)</option>
              <option value="MES">Mes(es)</option>
            </select>

            <select id="tipoDia" name="tipoDia" required onchange="calcularDias(this)">
              <option value="CALENDARIO">Calendario</option>
              <option value="HABILES">Habiles</option>
            </select>

          </div>
        </div>


      </div>

    </div>

		<!-- adjuntar archivos -->
		<div class="modal-toggle-wrapper ">
			<h3 class="text-title text-center uppercase font-bold pt-2"><i class="fas fa-plus"></i> Adjuntar Archivo</h3>
			<div class="flex flex-col space-y-2 w-full text-sm">
				<div class=" modal-drag-drop-wrapper">
					<div data-id="drop-editar" class="drop-area modal-drag-drop">
						<span>Arrastre y suelte sus archivos para agregarlos.</span>
						<input type="file" class="hidden" multiple>
						<ul></ul>
					</div>
					<div class="form-row-submit ">
						<button class="btn_file_click modal-btn--submit" type="button" >
						ABRIR CARPETA
						</button>
					</div>
				</div>
			</div>
		</div>

    <!-- <div id="add_files" class=" mx-auto w-2/3 flex flex-col space-y-4  items-center ">
      <div id="dragDropContainer" class="drag-drop-container relative flex flex-col items-center justify-start p-4 w-full border-2 border-dashed border-super bg-grayLight h-44 overflow-y-auto text-super ">
        <span class="absolute top-20 left-0 right-0 text-center">Arrastre y suelte sus archivos para agregarlos.</span>
        <input type="file" id="modal_file_input" class="hidden" multiple>
        <ul id="fileList" class="file-list w-full grid grid-cols-5 gap-4 py-4 "></ul>
      </div>

      <div class="flex justify-end space-x-12 text-sm ">
        <button id="btn_open_files" class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
          ABRIR CARPETA
        </button>
        <button id="btn_add_files" class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
          GUARDAR
        </button>
      </div>

    </div> -->

    <div class="flex justify-end space-x-12 text-sm ">
      <!-- <p id="files_loaded" class=" text-title text-lg "></p> -->

      <button class=" flex space-x-4 items-center shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit" >
        <span>ACTUALIZAR</span>
      </button>

    </div>

    <!-- <input id="file_input" class="hidden" type="file" name="archivo[]" multiple> -->
    <input type="hidden" id="importe" name="importe">
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


<!-- Modal Comment -->
<div id="modal_comment" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
  <div class=" flex flex-col space-y-4 bg-white border-2 border-icon p-10 w-full md:max-w-4xl h-[640px] ">

    <div class="relative flex w-full justify-center text-center ">
      <h3 class="text-gray text-xl uppercase">Comentarios</h3>
      <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
    </div>

    <div class=" flex flex-col space-y-2 w-full text-sm  ">
      <div class="flex w-full text-center">
        <div class="w-44 bg-icon text-white p-2">Nombre</div>
        <div class="w-28 bg-icon text-white p-2">Fecha</div>
        <div class="flex-grow bg-icon text-white p-2">Comentario</div>
      </div>
      <div class="h-72 overflow-y-auto ">
        <div id="comentarios_container" class="flex flex-col space-y-2 w-full "></div> 
      </div>
    </div>

    <form id="form_add_comment" class=" w-full flex flex-col space-y-2 ">
      <input type="hidden" name="art_id">

      <div id="add_comment" class="hidden flex w-full ">
        <textarea name="comentario" class="p-4 w-full border border-grayMid outline-none resize-none bg-grayLight text-gray drop-shadow " rows="2" placeholder="Escribe tu comentario..."></textarea>
      </div>

      <div class=" pt-6 flex justify-end space-x-12 text-sm ">
        <button id="btn_add_comment" class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
          AGREGAR COMENTARIO
        </button>
        <button class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit" >
          ACEPTAR
        </button>
      </div>
    </form>

  </div>
</div>

<!-- Modal Adjuntos -->
<div id="modal_files" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
  <div class=" flex flex-col space-y-8 bg-white border-2 border-icon p-10 w-full md:max-w-4xl ">

    <div class="relative flex w-full justify-center text-center  ">
      <h3 class="text-gray text-xl uppercase">Archivos Adjuntos</h3>
      <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
    </div>
    <div class=" flex flex-col space-y-2 w-full text-sm ">
      <div class="flex w-full text-center">
        <div class="w-44 bg-icon text-white p-2">Nombre</div>
        <div class="w-28 bg-icon text-white p-2">Fecha</div>
        <div class="flex-grow bg-icon text-white p-2">Archivo</div>
      </div>
      <div id="archivos_container" class="flex flex-col space-y-2 w-full "></div>
    </div>

  </div>
</div>

<!-- Modal Aprobacion Costos-->
<div id="modal_aprobacion_costos" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
  <div class=" flex flex-col space-y-4 bg-white border-2 border-icon p-10 w-full md:max-w-4xl ">

    <div class="relative flex w-full justify-center text-center ">
      <h3 class="modal_title text-gray text-xl uppercase"></h3>
      <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
    </div>

    <div class=" flex flex-col space-y-2 w-full text-sm  ">
      <div class="flex w-full text-center">
        <div class="w-64 bg-icon text-white p-2">Nombre</div>
        <div class="w-28 bg-icon text-white p-2">Fecha</div>
        <div class="w-28 bg-icon text-white p-2">Estado</div>
        <div class="flex-grow bg-icon text-white p-2">Comentario</div>
      </div>

      <div class="h-44 overflow-y-auto ">
        <div id="aprobaciones_container_costos" class="flex flex-col space-y-2 w-full "></div> 
      </div>
    </div>

    <?php if (hasRole('costos')): ?>
    <form id="form_aprobacion_costos" class=" w-full flex flex-col space-y-2 text-dark">
      <input type="hidden" name="area" value="<?= session()->get('user')['rol'] ?>">
      <input type="hidden" name="aprobId" >

      <div class=" flex flex-col gap-y-4 w-full ">
        <div class="flex w-full space-x-8 ">
          <div class="flex space-x-2 w-1/2 ">
            <p>Nombre: </p>
            <p><?= session()->get('user')['name'] . ' ' . session()->get('user')['last_name'] ?></p>
          </div>
          <div class="flex w-fit space-x-2 ">
            <p>Fecha: </p>
            <p><?= date('d-m-Y') ?></p>
          </div>
          <div class="flex w-fit space-x-2 ">
            <p>Estado: </p>
            <select id="status_costos" name="status" class="input_alt w-32" required>
              <option value="PENDIENTE">PENDIENTE</option>
              <option value="NO APROBADO">NO APROBADO</option>
              <option value="APROBADO">APROBADO</option>
            </select>
          </div>
        </div>

        <div class="flex flex-col w-full ">
          <p>Comentario: </p>
          <textarea name="comentario" required class="p-4 w-full border border-grayMid outline-none resize-none bg-grayLight text-gray drop-shadow " rows="2" placeholder="Escribe tu comentario..."></textarea>
        </div>

      </div>

      <div class=" pt-6 flex justify-end space-x-12 text-sm ">
        <button class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit" >
          ACTUALIZAR ESTADO
        </button>
      </div>
    </form>
    <?php endif; ?>

  </div>
</div>

<!-- Modal Aprobacion desarrollo-->
<div id="modal_aprobacion_desarrollo" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
  <div class=" flex flex-col space-y-4 bg-white border-2 border-icon p-10 w-full md:max-w-4xl ">

    <div class="relative flex w-full justify-center text-center ">
      <h3 class="modal_title text-gray text-xl uppercase"></h3>
      <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
    </div>

    <div class=" flex flex-col space-y-2 w-full text-sm  ">
      <div class="flex w-full text-center">
        <div class="w-64 bg-icon text-white p-2">Nombre</div>
        <div class="w-28 bg-icon text-white p-2">Fecha</div>
        <div class="w-28 bg-icon text-white p-2">Estado</div>
        <div class="flex-grow bg-icon text-white p-2">Comentario</div>
      </div>

      <div class="h-44 overflow-y-auto ">
        <div id="aprobaciones_container_desarrollo" class="flex flex-col space-y-2 w-full "></div> 
      </div>
    </div>

    <?php if (hasRole('desarrollo')): ?>
    <form id="form_aprobacion_desarrollo" class=" w-full flex flex-col space-y-2 text-dark">
      <input type="hidden" name="area" value="<?= session()->get('user')['rol'] ?>">
      <input type="hidden" name="aprobId" > 

      <div class=" flex flex-col gap-y-4 w-full ">
        <div class="flex w-full space-x-8 ">
          <div class="flex space-x-2 w-1/2 ">
            <p>Nombre: </p>
            <p><?= session()->get('user')['name'] . ' ' . session()->get('user')['last_name'] ?></p>
          </div>
          <div class="flex w-fit space-x-2 ">
            <p>Fecha: </p>
            <p><?= date('d-m-Y') ?></p>
          </div>
          <div class="flex w-fit space-x-2 ">
            <p>Estado: </p>
            <select id="status_desarrollo" name="status" class="input_alt w-32" required>
              <option value="PENDIENTE">PENDIENTE</option>
              <option value="NO APROBADO">NO APROBADO</option>
              <option value="APROBADO">APROBADO</option>
            </select>
          </div>
        </div>

        <div class="flex flex-col w-full ">
          <p>Comentario: </p>
          <textarea name="comentario" required class="p-4 w-full border border-grayMid outline-none resize-none bg-grayLight text-gray drop-shadow " rows="2" placeholder="Escribe tu comentario..."></textarea>
        </div>

      </div>

      <div class=" pt-6 flex justify-end space-x-12 text-sm ">
        <button class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit" >
          ACTUALIZAR ESTADO
        </button>
      </div>
    </form>
    <?php endif; ?>

  </div>
</div>

<!-- Modal Aprobacion calidad-->
<div id="modal_aprobacion_calidad" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
  <div class=" flex flex-col space-y-4 bg-white border-2 border-icon p-10 w-full md:max-w-4xl ">

    <div class="relative flex w-full justify-center text-center ">
      <h3 class="modal_title text-gray text-xl uppercase"></h3>
      <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
    </div>

    <div class=" flex flex-col space-y-2 w-full text-sm  ">
      <div class="flex w-full text-center">
        <div class="w-64 bg-icon text-white p-2">Nombre</div>
        <div class="w-28 bg-icon text-white p-2">Fecha</div>
        <div class="w-28 bg-icon text-white p-2">Estado</div>
        <div class="flex-grow bg-icon text-white p-2">Comentario</div>
      </div>

      <div class="h-44 overflow-y-auto ">
        <div id="aprobaciones_container_calidad" class="flex flex-col space-y-2 w-full "></div> 
      </div>
    </div>

    <?php if (hasRole('calidad')): ?>
    <form id="form_aprobacion_calidad" class=" w-full flex flex-col space-y-2 text-dark">
      <input type="hidden" name="area" value="<?= session()->get('user')['rol'] ?>">
      <input type="hidden" name="aprobId" >

      <div class=" flex flex-col gap-y-4 w-full ">
        <div class="flex w-full space-x-8 ">
          <div class="flex space-x-2 w-1/2 ">
            <p>Nombre: </p>
            <p><?= session()->get('user')['name'] . ' ' . session()->get('user')['last_name'] ?></p>
          </div>
          <div class="flex w-fit space-x-2 ">
            <p>Fecha: </p>
            <p><?= date('d-m-Y') ?></p>
          </div>
          <div class="flex w-fit space-x-2 ">
            <p>Estado: </p>
            <select id="status_calidad" name="status" class="select_filter w-32" required>
              <option value="PENDIENTE">PENDIENTE</option>
              <option value="NO APROBADO">NO APROBADO</option>
              <option value="APROBADO">APROBADO</option>
            </select>
          </div>
        </div>

        <div class="flex flex-col w-full ">
          <p>Comentario: </p>
          <textarea name="comentario" required class="p-4 w-full border border-grayMid outline-none resize-none bg-grayLight text-gray drop-shadow " rows="2" placeholder="Escribe tu comentario..."></textarea>
        </div>

      </div>

      <div class=" pt-6 flex justify-end space-x-12 text-sm ">
        <button class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit" >
          ACTUALIZAR ESTADO
        </button>
      </div>
    </form>
    <?php endif; ?>

  </div>
</div>

<!-- Modal Ficha -->
<div id="modal_ficha" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
  <div class=" flex flex-col space-y-8 bg-white border-2 border-icon p-10 w-full md:max-w-4xl  max-h-[85vh]">

    <div class="relative flex flex-col gap-y-2 w-full justify-center text-center  ">
      <h3 class="text-gray text-xl uppercase">Ficha Tecnica </h3>
      <h2 class="text-gray uppercase"></h2>
      <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
    </div>
    <div class=" flex flex-col space-y-2 w-full text-sm ">
      <div class="flex w-full text-center">
        <div class="w-44 bg-icon text-white p-2">Nombre</div>
        <div class="w-28 bg-icon text-white p-2">Fecha</div>
        <div class="flex-grow bg-icon text-white p-2">Archivo</div>
      </div>
      <div id="ficha_container" class="flex flex-col space-y-2 w-full "></div>
    </div>


		<form id="form_ficha" method="post" class="modal-body" enctype='multipart/form-data'>

			<div class="modal-toggle-wrapper ">
				<h3 class="text-title text-center uppercase font-bold pt-2"><i class="fas fa-plus"></i> Adjuntar Archivo</h3>
				<div class="flex flex-col space-y-2 w-full text-sm">
					<div class=" modal-drag-drop-wrapper">
						<div data-id="drop-ficha" class="drop-area modal-drag-drop">
							<span>Arrastre y suelte sus archivos para agregarlos.</span>
							<input type="file" class="hidden" multiple>
							<ul></ul>
						</div>
						<div class="form-row-submit ">
							<button class="btn_file_click modal-btn--submit" type="button" >
							ABRIR CARPETA
							</button>
						</div>
					</div>
				</div>
			</div>

      <div class="form-row-submit">
        <button type="submit" class="modal-btn--submit">Actualizar</button>
      </div>

    </form>

  </div>
</div>

<?php echo view('_partials/_modal_msg'); ?>
<script src="<?= load_asset('js/dragDrop.js') ?>"></script>

<script>
  Service.setLoading();

	const form_ficha = document.querySelector('#form_ficha');
	form_ficha.addEventListener('submit', e => {
		e.preventDefault();
		Service.stopSubmit(e.target, true);
		Service.show('.loading');

		let lista = form_ficha.querySelector(".drop-area  ul");
		let dragText = form_ficha.querySelector(".drop-area  span");

		const formData = new FormData(e.target);
		const files = fileStorage.get('drop-ficha'); 

		if (files.length > 0) {
			files.forEach(file => {
				formData.append('archivo[]', file);
			});
		}

		let artId = form_ficha.dataset.id;
		let cotizId = form_ficha.dataset.cotiz;
		if (artId) {
			formData.append('artId', artId);
			formData.append('cotizId', cotizId);
		}

    Service.exec('post', `/busqueda/upload_ficha`, formData_header, formData)
    .then( r => {
      if(r.success){

				Service.hide('.loading');

				form_ficha.reset();
				lista.innerHTML = "";
				dragText.style.display = 'block';

				Service.stopSubmit(e.target, false);

				initFicha(artId);

				clearFiles('drop-ficha');
			}
    });
	});


	const clearFiles = (id) => {
		let archivo_files = fileStorage.get(id);
		if (archivo_files) archivo_files.length = 0;
	}
	



const proveedor = document.getElementById('proveedor');
const proveedorName = document.getElementById('proveedor_name');
const lista_prov = document.getElementById('lista_prov');

proveedor.addEventListener('keyup', event => {
	const query = event.target.value.trim();

	if (query.length >= 3) {
		Service.exec('get', `/get_proveedor`, { params: { q: query } })
		.then( r => {
			// console.log(r)

			// Populate the user list
			lista_prov.innerHTML = ''; // Clear previous results
			if (r.length > 0) {
				r.forEach(prov => {
					const li = document.createElement('li');
					li.textContent = prov.razon_social; 
					li.style.cursor = 'pointer';
					li.addEventListener('click', () => {
						proveedor.value = prov.razon_social; 
						proveedorName.value = prov.razon_social;
						Service.hide('#lista_prov');
					});
					lista_prov.appendChild(li);
				});
				Service.show('#lista_prov');
			} else {
				Service.hide('#lista_prov');
			}
		});
	} else {
		Service.hide('#lista_prov');
	}
});

document.addEventListener('click', (e) => {
	if (!lista_prov.contains(e.target) && e.target !== proveedor) {
		Service.hide('#lista_prov');
	}
});



  const addComment = document.querySelector('#add_comment');
  const btnAddComment = document.querySelector('#btn_add_comment');
  btnAddComment?.addEventListener('click', e => addComment.classList.remove('hidden'));

  const submitComm = (e) => {
    e.preventDefault();
    let comentario = form_add_comment.querySelector('textarea[name="comentario"]');

    if (comentario.value.length > 5) {
      Service.stopSubmit(e.target, false);
      const formData = new FormData(e.target);

      Service.exec('post', `/add_comment`, formData_header, formData)
      .then( r => {
        // console.log(r); return;
        addComment.classList.add('hidden');
        e.target.reset();

        let id = r.art_id;
        let button = document.querySelector(`button[data-modal="modal_comment"][data-id="${id}"]`);
        let icon = button.querySelector('i');

        initModalComment(id, icon);
        Service.stopSubmit(e.target, false);
      });
    }
  }

  const form_add_comment = document.querySelector('#form_add_comment');
  form_add_comment?.addEventListener('submit', submitComm);


  const btn_delete = document.querySelector('#btn_delete');
  btn_delete?.addEventListener('click', () => {
    btn_delete.disabled = true;
    let art_id = btn_delete.getAttribute('data-id');

    Service.exec('delete', `/delete_article/${art_id}`)
    .then( r => {
      // console.log(r); return;

      let button = document.querySelector(`button[data-modal="modal_delete"][data-id="${r.art_id}"]`);
      let row = button.parentElement.parentElement.closest('tr');
      row.remove();

      btn_delete.disabled = false;

      let modal = btn_delete.closest("#modal_delete");
      modal.classList.add('hidden');
      modal.classList.remove('modal_active');
    });
  });


  const order_select = document.querySelector('#order_by');
  order_select?.addEventListener('change', () => reorderTableRows(order_select.value));

  const reorderTableRows = (order) => {
    const tbody = document.querySelector('#results_container');
    const rows = Array.from(tbody.rows);

    rows.sort((a, b) => {
      let valA, valB;

      if (order.includes('nombre')) {
        valA = a.cells[0].querySelectorAll('span')[0].innerText.trim().toLowerCase();
        valB = b.cells[0].querySelectorAll('span')[0].innerText.trim().toLowerCase();
      } else if (order.includes('costo')) {
        const sizeA = parseFloat(a.cells[2].querySelectorAll('span')[0].innerText);
        const sizeB = parseFloat(b.cells[2].querySelectorAll('span')[0].innerText);
        valA = sizeA;
        valB = sizeB;
      } else if (order.includes('dias')) {
        valA = parseInt(a.cells[5].querySelectorAll('span')[0].innerText.trim(), 10);
        valB = parseInt(b.cells[5].querySelectorAll('span')[0].innerText.trim(), 10);
      }

      if (order.includes('asc')) {
        return valA > valB ? 1 : -1;
      } else {
        return valA < valB ? 1 : -1;
      }
    });

    rows.forEach(row => tbody.appendChild(row));
  };


  const results_container = document.querySelector('#results_container');

  const form_search = document.querySelector('#form_search');
  form_search?.addEventListener('submit', e => {
    e.preventDefault();
		Service.hide('#row__empty');
    Service.stopSubmit(e.target, true);

    results_container.innerHTML = Service.loader();

    const formData = new FormData(e.target);

    Service.exec('post', `/search_articulos`, formData_header, formData)
    .then(r => {

      renderArticles(r);
      Service.stopSubmit(e.target, false);
    });  
  });


  const form_edit = document.querySelector('#form_edit');
  form_edit?.addEventListener('submit', e => {
    e.preventDefault();
		Service.stopSubmit(e.target, true);
		Service.show('.loading');

		let lista = form_edit.querySelector(".drop-area  ul");
		let dragText = form_edit.querySelector(".drop-area  span");

    const formData = new FormData(e.target);
		const files = fileStorage.get('drop-editar'); 

		if (files.length > 0) {
			files.forEach(file => {
				formData.append('archivo[]', file);
			});
		}

    Service.exec('post', `/edit_art`, formData_header, formData)
    .then( r => {

      if(r.success){
				Service.stopSubmit(e.target, false);
				Service.hide('.loading');

        form_edit.reset();
				lista.innerHTML = "";
				dragText.style.display = 'block';

				clearFiles('drop-editar');

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

        loadAllArticles();  // load all articles with filtros de busqueda
      }

    });
  });


	const initFicha = (artId) => {

		let ficha_container = document.querySelector('#ficha_container');
		ficha_container.innerHTML = Service.loader();

		let modal_ficha = document.querySelector('#modal_ficha');
		let subtitle = modal_ficha.querySelector('h2');
		let form = modal_ficha.querySelector('#form_ficha');

		form.style.display = 'block';

		Service.exec('get', `/get_art_ficha/${artId}`)
		.then( r => {
			ficha_container.innerHTML = '';
			subtitle.innerHTML = form.dataset.articulo;

			if (r.length > 0) {

				form.style.display = 'none';

				r.forEach(adj => {
					const div = document.createElement('div');
					div.className = 'row-adjunto';
					div.innerHTML = `
						<div class="w-44 p-2">${adj.name} ${adj.last_name}</div>
						<div class="w-28 p-2 text-gray ">${dateToStringAlt(adj.fecha)}</div>
						<div class="p-2 flex items-center space-x-2 text-link">
							<i class="fas fa-paperclip"></i>
							<a href="${root}/files/download?path=${adj.archivo}" target="_blank" class="underline ">${getEncodedFileName(adj.archivo)}</a>
						</div>
					`;
					ficha_container.appendChild(div);
				});

			} else {
				const div = document.createElement('div');
				div.className = 'row-adjunto';
				div.innerHTML = Service.empty('No se encontraron archivos.');
				ficha_container.appendChild(div);
			}
		});

	}

  const initModalComment = (id, icon) => {
    let comentarios_container = document.querySelector('#comentarios_container');
    comentarios_container.innerHTML = Service.loader();

    let artId = form_add_comment.querySelector('[name="art_id"]');
    artId.value = id;

    Service.exec('get', `/get_comentarios`, { params: { art_id: id } })
    .then( r => {
      // console.log(r.data); return;
      comentarios_container.innerHTML = '';
      icon.innerHTML = r.num_comments;

      if ( r.num_comments > 0 ) {
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


  const initModalAprob = (id, aprob_type) => {
		
    let modal_aprob = document.querySelector(`#modal_aprobacion_${aprob_type}`);
    let title = modal_aprob.querySelector('.modal_title');
    title.innerHTML = `Aprobación ${aprob_type}`

    let aprobaciones_container = document.querySelector(`#aprobaciones_container_${aprob_type}`);
    aprobaciones_container.innerHTML = Service.loader();

    Service.exec('get', `/get_aprobacion/${id}/${aprob_type}`)
    .then( r => {

      let form = document.querySelector(`#form_aprobacion_${aprob_type}`);
      form?.reset();
      aprobaciones_container.innerHTML = "";

      if (r) {
        // console.log(r); return;

        // r.data.forEach(aprob => {
          const div = document.createElement('div');
          div.className = 'row-comentario';
          div.innerHTML = `
            <div class="w-64 p-2">${r.name} ${r.last_name}</div>
            <div class="w-28 p-2 text-center">${dateToString(r.created_at)}</div>
            <div class="w-28 p-2 text-center font-bold ${setArticleStatusText(r.status)}">${r.status}</div>
            <div class="p-2">${r.comentario}</div>
          `;
          aprobaciones_container.appendChild(div);
        // });

        if(form) {
          // console.log(form)
          let comentario = form.querySelector('textarea[name="comentario"]');
          let aprobId = form.querySelector('input[name="aprobId"]');
          aprobId.value = r.id;

          comentario.value = r.comentario;
          setSelectedOption(`#status_${aprob_type}`, r.status, 'string');
        }

      } else {
        const div = document.createElement('div');
        div.className = 'row-comentario';
        div.innerHTML = Service.empty('No hay datos.');
        aprobaciones_container.appendChild(div);
      }
    });
  }

	const allInputToUpper = document.querySelectorAll('.to_uppercase');
	allInputToUpper?.forEach( input => {
		input.addEventListener('input', e => {
			input.value = e.target.value.toUpperCase();
		});
	});

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
					let form_details = document.querySelector('#form_details')
          let id = e.currentTarget.getAttribute('data-id');
          let cotiz_id = e.currentTarget.getAttribute('data-cotiz');

          Service.exec('get', `/get_articulo_coti`, { params: { cotiz_id: cotiz_id, art_id:id } })
          .then( r => {
            // console.log(r); return;
            form_details.querySelector('[name="nombreDelArticulo"]').value = r.articulo;
            form_details.querySelector('[name="proveedor"]').value = r.proveedor;
            form_details.querySelector('[name="origen"]').value = r.origen;
            form_details.querySelector('[name="incoterm"]').value = r.incoterm;
            form_details.querySelector('[name="fecha"]').value = dateToStringAlt(r.fecha);
            form_details.querySelector('[name="vigencia"]').value = r.vigencia;
            form_details.querySelector('[name="costoPorUnidad"]').value = r.costo;
            form_details.querySelector('[name="minimo"]').value = r.minimo;
            form_details.querySelector('[name="divisa"]').value = r.divisa;
            form_details.querySelector('[name="impuesto"]').value = r.impuesto;
            form_details.querySelector('[name="medicion"]').value = r.medicion;
            form_details.querySelector('[name="periodo"]').value = r.periodo;
            form_details.querySelector('[name="tipoDia"]').value = r.tipoDia;
            // setSelectedOption('#form_details #divisa', r.divisa, 'string');
            // setSelectedOption('#form_details #impuesto', r.impuesto, 'string');
            // setSelectedOption('#form_details #medicion', r.medicion, 'string');
            // setSelectedOption('#form_details #periodo', r.periodo, 'string');
            // setSelectedOption('#form_details #tipoDia', r.tipoDia, 'string');

            form_details.querySelector('[name="cantidadPer"]').value = r.cantidadPer;
            form_details.querySelector('#diasDeEnvio').value = r.diasDeEnvio;
            form_details.querySelector('[name="importe"]').value = r.importe;
          });

					let condiciones_container = form_details.querySelector("#condiciones_container")

					Service.exec('get', `/get_condiciones/${id}`)
					.then( r => {
						condiciones_container.innerHTML = "";

						if ( r.length > 0 ) {
							r.forEach(comm => {
								const div = document.createElement('div');
								div.className = 'flex w-full bg-warning text-dark bg-opacity-30 p-1 ';
								div.innerHTML = `
									<div class="w-28 p-2 text-center">${dateToString(comm.created_at)}</div>
									<i class="fas fa-triangle-exclamation text-warning text-2xl pr-2"></i>
									<div class="p-2">${comm.condicion}</div>
								`;
								condiciones_container.appendChild(div);
							});

						} else {
							const div = document.createElement('div');
							div.className = 'row-comentario';
							div.innerHTML = Service.empty('No hay datos.');
							condiciones_container.appendChild(div);
						}
					});



				} else if (modal_id == 'modal_edit') {
          let id = e.currentTarget.getAttribute('data-id');
          let cotiz_id = e.currentTarget.getAttribute('data-cotiz');

					let lastCotizId = form_edit.querySelector('[name="cotiz_id"]');
					lastCotizId?.remove();

					let lastArtId = form_edit.querySelector('[name="art_id"]');
					lastArtId?.remove();

          const cotizId= document.createElement('input');
          cotizId.type = 'hidden'
          cotizId.name = 'cotiz_id';
          cotizId.value = cotiz_id;
          form_edit.prepend(cotizId);

          const artId= document.createElement('input');
          artId.type = 'hidden'
          artId.name = 'art_id';
          artId.value = id;
          form_edit.prepend(artId);

          Service.exec('get', `/get_articulo_coti`, { params: { cotiz_id: cotiz_id, art_id:id } })
          .then( r => {
            // console.log(r); return;
            form_edit.querySelector('[name="nombreDelArticulo"]').value = r.articulo;
            form_edit.querySelector('[name="proveedor"]').value = r.proveedor;
            form_edit.querySelector('[name="origen"]').value = r.origen;
            form_edit.querySelector('[name="incoterm"]').value = r.incoterm;
            form_edit.querySelector('[name="fecha"]').value = dateToStringAlt(r.fecha);
            form_edit.querySelector('[name="vigencia"]').value = r.vigencia;
            form_edit.querySelector('[name="costoPorUnidad"]').value = r.costo;
            form_edit.querySelector('[name="minimo"]').value = r.minimo;
            setSelectedOption('#form_edit #divisa', r.divisa, 'string');
            setSelectedOption('#form_edit #impuesto', r.impuesto, 'string');
            setSelectedOption('#form_edit #medicion', r.medicion, 'string');
            setSelectedOption('#form_edit #periodo', r.periodo, 'string');
            setSelectedOption('#form_edit #tipoDia', r.tipoDia, 'string');

            form_edit.querySelector('[name="cantidadPer"]').value = r.cantidadPer;
            form_edit.querySelector('#diasDeEnvio').value = r.diasDeEnvio;
            form_edit.querySelector('[name="importe"]').value = r.importe;
          });

        } else if (modal_id == 'modal_delete') {
          let id = e.currentTarget.getAttribute('data-id');

          const btn_delete = document.querySelector('#btn_delete');
          btn_delete.setAttribute('data-id', id);
          // console.log(btn_delete)

        } else if (modal_id == 'modal_files') {

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
                  <div class="w-28 p-2 text-gray ">${dateToStringAlt(adj.fecha)}</div>
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

        } else if (modal_id == 'modal_ficha') {

					let id = e.currentTarget.getAttribute('data-id');
					form_ficha.dataset.id = btn.dataset.id;
					form_ficha.dataset.cotiz = btn.dataset.cotiz;
					form_ficha.dataset.articulo = btn.dataset.articulo;

					initFicha(id);

        } else if (modal_id == 'modal_comment') {
          let id = e.currentTarget.getAttribute('data-id');
          let icon = e.currentTarget.querySelector('i');
          initModalComment(id, icon);

        } else if (modal_id == 'modal_aprobacion_costos' || modal_id == 'modal_aprobacion_desarrollo' || modal_id == 'modal_aprobacion_calidad') {
          let id = e.currentTarget.getAttribute('data-id');
          let aprob_type = e.currentTarget.getAttribute('data-aprob');
					
          modal.setAttribute('data-artId', id);
          initModalAprob(id, aprob_type);
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
          addComment.classList.add('hidden');
				
					let lista = modal_active.querySelector(".drop-area  ul");
					let dragText = modal_active.querySelector(".drop-area  span");

					if(lista && dragText) {
						clearFiles('drop-ficha');
						clearFiles('drop-editar');
						
						lista.innerHTML = "";
						dragText.style.display = 'block';
					}

          // files_loaded.textContent = '';
          // dragDropContainer.querySelector('span').innerHTML = 'Arrastre y suelte sus archivos para agregarlos.';
          // fileList.innerHTML = '';
          // archivo_files = [];

          form_add_comment.reset();
          form_edit.reset();
        }
      });
    });
  }


  function calcularImporte(input) {
    const costoPorUnidad = form_edit.querySelector('[name="costoPorUnidad"]').value;
    const minimo = form_edit.querySelector('[name="minimo"]').value;
    const importe = form_edit.querySelector('[name="importe"]');
    importe.value = (costoPorUnidad * minimo).toFixed(2);
    // console.log(importe)
  }


  function calcularDias(element) {
    const tipo_dia = element.value;

    const _row = element.parentElement;
    const dias = _row.querySelector('[name="cantidadPer"]').value;
    const periodo = _row.querySelector('[name="periodo"]').value;
    const diasDeEnvio = _row.querySelector('[name="diasDeEnvio"]');
    console.log(diasDeEnvio)

    let startDate = moment();

    let daysToAdd;
    switch (periodo) {
      case 'DIA':
        daysToAdd = dias;
          break;
      case 'SEMANA':
        daysToAdd = dias * 7;
          break;
      case 'MES':
        daysToAdd = dias * 30;
          break;
    }

    let arrivalDate = startDate.clone(); // Clone to avoid mutating startDate
    let totalDays = 0;

    if (tipo_dia === 'HABILES') {
      // Add business days only
      let addedDays = 0;
      while (addedDays < daysToAdd) {
        arrivalDate.add(1, 'days');
        totalDays++;
        // Skip weekends (Saturday and Sunday)
        if (arrivalDate.isoWeekday() !== 6 && arrivalDate.isoWeekday() !== 7) {
          addedDays++;
        }
      }

      diasDeEnvio.value = totalDays;
    } else {
      totalDays = daysToAdd;
      diasDeEnvio.value = totalDays;
    }

    // console.log(diasDeEnvio)
  }

  const renderArticles = (data) => {  
    order_select.value = "";
    results_container.innerHTML = "";
    // console.log(data); return;

    if (data.length > 0) {

      data.forEach(art => {
        const row = document.createElement('tr');
				row.className = setRowValidation(art.status_costos, art.status_desarrollo, art.status_calidad);
				row.classList.add('btn_open_modal');
				row.setAttribute('data-modal', 'modal_details');
				row.setAttribute('data-id', art.art_id);
				row.setAttribute('data-cotiz', art.cotiz_id);

				let warning = "";

				if (art.num_cond > 0) {
					warning = `<i class="fas fa-triangle-exclamation text-warning text-lg pr-1"></i>`;
				} 

        row.innerHTML =
          `
            <td>
              <div class="row__articulo">
								${warning}
                <span>${art.articulo}</span>
              </div>
            </td>
            <td>
              <div class="row__proveedor">
                <span>${art.proveedor}</span>
              </div>
            </td>
            <td>
              <div class="row__costo">
                <span>${art.costo}</span>
                <span>${art.divisa}</span>
                <span>${art.impuesto}</span>
              </div>
            </td>
            <td>
              <div class="row__minimo">
                <span>${art.minimo}</span>
                <span>${art.medicion}</span>
                <span>${art.importe}</span>
              </div>
            </td>
            <td>
              <div class="row__fecha">
                <span>${dateToStringAlt(art.fecha)}</span>
              </div>
            </td>
            <td>
              <div class="row__entrega ">
                <span class="hidden">${art.diasDeEnvio}</span>
                <span>${art.cantidadPer}</span>
                <span>${ellipsis(art.periodo, 5)}</span>
                <span>${ellipsis(art.tipoDia, 3)}</span>
              </div>
            </td>
            <td>
              <div class="row__origen">
                <span>${ellipsis(art.origen, 5)}</span>
                <span>${art.incoterm}</span>
              </div>
            </td>
            <td>
              <div class="row__aprobacion">
                <button data-modal="modal_aprobacion_costos" data-aprob="costos" data-id="${art.art_id}" 
                  class="btn_open_modal w-fit rounded border-2 ${setArticleStatus(art.status_costos).color}" 
                  type="button">
                  ${setArticleStatus(art.status_costos).icon}
                </button>
                <button data-modal="modal_aprobacion_desarrollo" data-aprob="desarrollo" data-id="${art.art_id}" 
                  class="btn_open_modal w-fit rounded border-2 ${setArticleStatus(art.status_desarrollo).color}" 
                  type="button">
                  ${setArticleStatus(art.status_desarrollo).icon}
                </button>
                <button data-modal="modal_aprobacion_calidad" data-aprob="calidad" data-id="${art.art_id}" 
                  class="btn_open_modal w-fit rounded border-2 ${setArticleStatus(art.status_calidad).color}" 
                  type="button">
                  ${setArticleStatus(art.status_calidad).icon}
                </button>
              </div>
            </td>
            <td>
              <div class="row__adjunto ">
                <button data-modal="modal_files" data-id="${art.cotiz_id}" class="btn_open_modal rounded text-icon w-fit" type="button">${getIcon('clip')}</button>
              </div>
            </td>
						<td>
              <div class="row__adjunto ">
                <button data-modal="modal_ficha" data-id="${art.art_id}" data-cotiz="${art.cotiz_id}" data-articulo="${art.articulo}"  class="btn_open_modal rounded text-icon w-fit" type="button"><i class="fas fa-file-alt fa-2x"></i></button>
              </div>
            </td>
            <td>
              <div class="row__comentario ">
                <button data-modal="modal_comment" data-id="${art.art_id}" class="btn_open_modal rounded text-icon text-lg w-fit" type="button"><i class="fa px-2">${art.num_comm}</i></button>
              </div>
            </td>
            <td>
              <div class="row__actions ">
										
								<button data-modal="modal_details" data-id="${art.art_id}" data-cotiz="${art.cotiz_id}" class="btn_open_modal hover:text-icon pr-2" type="button"><i class="fas fa-eye text-lg"></i>
								</button>
                <button data-modal="modal_edit" data-id="${art.art_id}" data-cotiz="${art.cotiz_id}" class="btn_open_modal hover:text-blue pr-2" type="button">${getIcon('edit')}</button>
                <button data-modal="modal_delete" data-id="${art.art_id}" class="btn_open_modal hover:text-red " type="button">${getIcon('delete')}</button>
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

  const loadAllArticles = () => {
    Service.hide('#row__empty');
    results_container.innerHTML = Service.loader();

    Service.exec('get', `/all_articles`)
    .then(r => renderArticles(r));  
  }

  loadAllArticles();

</script>
</body>
</html>