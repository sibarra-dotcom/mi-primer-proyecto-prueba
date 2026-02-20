<?php echo view('_partials/header'); ?>
  <link rel="stylesheet" href="<?= base_url('_partials/aprobaciones.css') ?>">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
  <script src="<?= base_url('js/axios.js') ?>"></script>
  <script src="<?= base_url('js/helper.js') ?>"></script>
  <script src="<?= base_url('js/modal.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">
  <div id="loadingOverlay"><div id="loadingSpinner"></div></div>  

  <img src="<?= base_url('img/laboratorio.svg') ?>" class="hidden md:flex absolute bottom-0 -left-2 ">

  <div class=" relative h-full flex flex-col items-center justify-center font-titil ">

    <?php echo view('cotizar/_partials/navbar'); ?>

    <a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer">Cerrar Sesión</a>

    <div class="text-title w-full md:pt-4 md:pb-8 md:px-16 p-2 flex items-center ">
      <h2 class="text-center font-bold w-full text-3xl "><?= esc($title) ?></h2>
      <a href="<?= base_url('apps') ?>" class="self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>

    <div class="w-full text-sm text-gray flex p-4 ">
      <div class="flex space-x-4 w-full h-full justify-center" >

        <div class="col-l bg-grayMid rounded border border-grayMid p-2">
          <form id="form_search" class="flex flex-col space-y-4 w-full items-center justify-center px-6 " method="post" >
            <?= csrf_field() ?>
            <h3 class="text-gray text-lg">Filtro Aprobaciones</h3>

            <div class="relative flex flex-col  w-full">
              <h5 class="text-center uppercase">Proveedor</h5>
              <input type="text" id="proveedor" name="proveedor" placeholder="Razón social de proveedor" >
              <ul id="lista_prov" class="absolute z-40 top-12 bg-grayLight border border-super w-full h-32 overflow-y-scroll"></ul>
            </div>

            <div class="relative flex flex-col  w-full">
              <h5 class="text-center uppercase">Articulo</h5>
              <input type="text" id="articulo" name="articulo" placeholder="Nombre de artículo" >
              <ul id="lista_articulos" class="absolute z-40 top-12 bg-grayLight border border-super w-full h-32 overflow-y-scroll"></ul>
            </div>

            <div class="flex flex-col   w-full">
              <h5 class="text-center uppercase">Fecha</h5>
              <input id="fecha" type="date" name="fecha" >
            </div>

            <div class="flex flex-col w-full">
              <h5 class="text-center uppercase">Vigencia</h5>
              <select name="vigencia" id="vigencia">
                <option value="" disabled selected>Seleccionar...</option>
                <option value="3 Meses">3 Meses</option>
                <option value="6 Meses">6 Meses</option>
                <option value="1 Año">1 Año</option>
              </select>
            </div>

            <div class="flex flex-col w-full ">
              <h5 class="text-center uppercase">Origen</h5>
              <select name="origen" id="origen" >
                <option value="" selected>Seleccionar...</option>
                <option value="nacional">Nacional</option>
                <option value="importado">Importado</option>
              </select>
            </div>

            <div class="flex flex-col w-full ">
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

            <div class="flex flex-col w-full ">
              <h5 class="text-center uppercase">Impuesto</h5>
              <select name="impuesto" >
                <option value="" selected>Seleccionar...</option>
                <option>16%</option>
                <option>0%</option>
              </select>
            </div>

            <button id="btn_search" class="flex space-x-4 shadow-bottom-right bg-white text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit" >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
              </svg>
              <span>BUSCAR</span>
            </button>
          </form>
        </div>

        <div class="col-r w-full">

          <div class="pb-4  flex w-fit items-center space-x-4">
            <span>ORDENAR POR:</span>
            <select id="order_by" name="order_by" class="input_alt w-44">
              <option value="" disabled selected>Seleccionar...</option>
              <option value="asc-costo">$ - $$$</option>
              <option value="desc-costo">$$$ - $</option>
              <option value="asc-dias">- Tiempo Entrega</option>
              <option value="desc-dias">+ Tiempo Entrega</option>
              <option value="asc-nombre">A - Z</option>
              <option value="desc-nombre">Z - A</option>
            </select>
          </div>

          <div class="w-full flex items-start min-h-72 ">
            <div class="relative top-0 w-full " >
              <table class="relative top-0 w-full">
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
                      </div>
                    </th>
                    <th>Fecha</th>
                    <th>Tiempo de Entrega</th>
                    <th>
                      <div class="row__origen">
                        <span>Origen</span>
                        <span>Incoterm</span>
                      </div>
                    </th>
                    <th>
                      <div class="row__aprobacion">
                        <span>Costos</span>
                        <span>Desarrollo</span>
                        <span>Calidad</span>
                      </div>
                    </th>
                    <th>Adj.</th>
                    <th>Coment.</th>
                    <!-- <th>Acciones</th> -->
                  </tr>
                </thead>
                <tbody id="results_container">
                  <?php foreach ($results as $prod): ?>
                    <tr>
                      <td>
                        <div class="row__articulo">
                          <span><?= $prod['articulo'] ?></span>
                        </div>
                      </td>
                      <td>
                        <div class="row__proveedor">
                          <span><?= $prod['proveedor'] ?></span>
                        </div>
                      </td>
                      <td>
                        <div class="row__costo">
                          <span><?= $prod['costo'] ?></span>
                          <span><?= $prod['divisa'] ?></span>
                          <span><?= $prod['impuesto'] ?></span>
                        </div>
                      </td>
                      <td>
                        <div class="row__minimo">
                          <span><?= $prod['minimo'] ?></span>
                          <span><?= $prod['medicion'] ?></span>
                        </div>
                      </td>

                      <td>
                        <div class="row__fecha">
                          <span><?= dateToString($prod['fecha']) ?></span>
                        </div>
                      </td>

                      <td>
                        <div class="row__entrega ">
                          <span class="hidden"><?= $prod['diasDeEnvio'] ?></span>
                          <span><?= $prod['cantidadPer'] ?></span>
                          <span><?= ellipsis($prod['periodo'], 5) ?></span>
                          <span><?= ellipsis($prod['tipoDia'], 3) ?></span>
                        </div>
                      </td>

                      <td>
                        <div class="row__origen">
                          <span><?= ellipsis($prod['origen'], 5) ?></span>
                          <span><?= $prod['incoterm'] ?></span>
                        </div>
                      </td>

                      <td>
                        <div class="row__aprobacion">
                          <button data-modal="modal_aprobacion" data-aprob="costos" data-id="<?= $prod['art_id'] ?>" 
                            class="<?= hasRole('costos') ? 'btn_open_modal' : 'cursor-text' ?> w-fit rounded border-2 <?= setArticleStatus($prod['status_costos'])['color'] ?>" 
                            type="button">
                            <?= setArticleStatus($prod['status_costos'])['icon'] ?>
                          </button>
                          <button data-modal="modal_aprobacion" data-aprob="desarrollo" data-id="<?= $prod['art_id'] ?>" 
                            class="<?= hasRole('desarrollo') ? 'btn_open_modal' : 'cursor-text' ?> w-fit rounded border-2 <?= setArticleStatus($prod['status_desarrollo'])['color'] ?>" 
                            type="button">
                            <?= setArticleStatus($prod['status_desarrollo'])['icon'] ?>
                          </button>
                          <button data-modal="modal_aprobacion" data-aprob="calidad" data-id="<?= $prod['art_id'] ?>" 
                            class="<?= hasRole('calidad') ? 'btn_open_modal' : 'cursor-text' ?> w-fit rounded border-2 <?= setArticleStatus($prod['status_calidad'])['color'] ?>" 
                            type="button">
                            <?= setArticleStatus($prod['status_calidad'])['icon'] ?>
                          </button>
                          <!-- <div class="rounded text-red border-2 border-red w-fit " ><i class="fa fa-minus px-1"></i></div> -->
                          <!-- <div class="rounded text-grayLight border-2 border-gray w-fit " ><i class="fa fa-minus px-1"></i></div> -->
                        </div>
                      </td>

                      <td>
                        <div class="row__adjunto ">
                          <button data-modal="modal_files" data-id="<?= $prod['cotiz_id'] ?>" class="btn_open_modal rounded text-icon w-fit " type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                              <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                            </svg>
                          </button>
                        </div>
                      </td>

                      <td>
                        <div class="row__comentario ">
                          <button data-modal="modal_comment" data-id="<?= $prod['art_id'] ?>" class="btn_open_modal rounded text-icon text-lg w-fit " type="button">
                            <i class="fa px-2"><?= $prod['num_comm'] ?></i>
                          </button>
                        </div>
                      </td>


                    </tr>
                  <?php endforeach; ?>
                </tbody>


              </table>
              
              <div id="row__empty" class="row__empty">
                No hay coincidencias.
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

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
          <input type="text" name="nombreDelArticulo" class="to_uppercase" required>
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
          <input name="costoPorUnidad" type="number" step="0.01" min="0.01" oninput="calcularImporte(this)" required >
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
          </select>
        </div>

        <div class="relative flex flex-col ">
          <h5 class="text-center uppercase">Unidad Medida</h5>
          <select id="medicion" name="medicion" required>
            <option value="KG">KG</option>
            <option value="PZ">PZ</option>
            <option value="LT">LT</option>
          </select>
        </div>

        <div class="relative flex flex-col ">
          <h5 class="text-center uppercase">Minimo de Compra</h5>
          <input name="minimo" type="number" step="0.01" min="0.01" required >
        </div>

        <div class="relative flex flex-col ">
          <h5 class="text-center uppercase">Tiempo de Entrega</h5>
          <div class="flex space-x-2 items-center">
            <input type="hidden" id="diasDeEnvio" name="diasDeEnvio" >
            <input id="cantidadPer" name="cantidadPer" type="number" step="1" min="1" required>
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


    <div id="add_files" class=" mx-auto w-2/3 flex flex-col space-y-4  items-center ">
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

    </div>

    <input id="file_input" class="hidden" type="file" name="archivo[]" multiple>
    <input type="hidden" id="importe" name="importe">
  </form>
</div>

<!-- Modal Delete -->
<div id="modal_delete" class="hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
  <form id="form_delete" method="post" class=" flex flex-col items-center justify-between space-y-8 bg-white border-2 border-icon p-10 w-full md:w-[700px] h-96 ">
    
    <div class=" relative flex w-full justify-center text-center  ">
      <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
    </div>

    <h3 class="text-title text-4xl ">¿Eliminar Permanentemente?</h3>

    <div class="flex justify-center space-x-12 text-sm ">
      <button class="btn_close_modal flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
        CANCELAR
      </button>
      <button class=" flex space-x-4 shadow-bottom-right text-error border-2 border-error hover:bg-error hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit">
        ELIMINAR
      </button>
    </div>

  </form>
</div>

<!-- Modal Aprobacion -->
<div id="modal_aprobacion" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
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
        <div id="aprobaciones_container" class="flex flex-col space-y-2 w-full "></div> 
      </div>
    </div>

    <?php // if (hasRole(['costos', 'desarrollo'])): ?>
    <?php if (hasAnyRole(['costos', 'desarrollo'])): ?>

      <?php
        // echo "<pre>";
        // print_r($_SESSION['user']) ;
        // echo "</pre>";
      ?>

    <form id="form_aprobacion" class=" w-full flex flex-col space-y-2 text-dark">
      <input type="hidden" name="art_id">
      <input type="hidden" name="area" value="<?= session()->get('user')['rol'] ?>">

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
            <select id="divisa" name="divisa" class="input_alt w-32" required>
                  <!-- setSelectedOption('#status', 'USD'); -->
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

    <?php else: ?>
      <p class="text-white">No es usuario cotizador</p>
    <?php endif; ?>

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

<!-- Modal Files -->
<div id="modal_files" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
  <div class=" flex flex-col space-y-8 bg-white border-2 border-icon p-10 w-full md:w-[700px]">

    <div class="relative flex w-full justify-center text-center  ">
      <h3 class="text-gray text-xl uppercase">Archivos Adjuntos</h3>
      <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
    </div>

    <div class=" flex flex-col space-y-2 w-full ">
      <div class="flex w-full text-center">
        <div class="w-28 bg-icon text-white p-2">Fecha</div>
        <div class="flex-grow bg-icon text-white p-2">Archivo</div>
      </div>

      <div id="archivos_container" class="flex flex-col space-y-2 w-full ">
      </div>
        
    </div>


  </div>
</div>

<?php echo view('_partials/_modal_msg'); ?>

<script>


const btnAddComment = document.querySelector('#btn_add_comment');
const addComment = document.querySelector('#add_comment');

btnAddComment?.addEventListener('click', e => {
  addComment.classList.remove('hidden');
});

const form_aprobacion = document.querySelector('#form_aprobacion');
form_aprobacion?.addEventListener('submit', e => {

  e.preventDefault();
  let btn = e.target.querySelector('button[type="submit"]');
  let comentario = e.target.querySelector('textarea[name="comentario"]');

  if (comentario.value.length > 5) {
    btn.disabled = true;

    const formData = new FormData(e.target);

    Cotizador.post('/add_aprobacion', formData, formData_header)
    .then( response => {
      console.log(response.data); 
      // return;
      e.target.reset();

      // let id = response.data.art_id;
      // let button = document.querySelector(`button[data-modal="modal_comment"][data-id="${id}"]`);
      // let icon = button.querySelector('i');

      initModalComment(id, icon);
      btn.disabled = false;
    })
    .catch( err => console.log(err.message) );
  }
});


const form_add_comment = document.querySelector('#form_add_comment');
form_add_comment?.addEventListener('submit', e => {

  e.preventDefault();
  let btn = e.target.querySelector('button[type="submit"]');
  let comentario = form_add_comment.querySelector('textarea[name="comentario"]');

  if (comentario.value.length > 5) {
    btn.disabled = true;

    const formData = new FormData(e.target);

    Cotizador.post('/add_comment', formData, formData_header)
    .then( response => {
      // console.log(response.data); return;
      addComment.classList.add('hidden');
      e.target.reset();

      let id = response.data.art_id;
      let button = document.querySelector(`button[data-modal="modal_comment"][data-id="${id}"]`);
      let icon = button.querySelector('i');

      initModalComment(id, icon);
      btn.disabled = false;
    })
    .catch( err => console.log(err.message) );
  }
});

const form_delete = document.querySelector('#form_delete');
form_delete?.addEventListener('submit', e => {

  e.preventDefault();
  let btn = e.target.querySelector('button[type="submit"]');

  btn.disabled = true;

  const formData = new FormData(e.target);
  e.target.innerHTML = '<div class="p-6 w-full flex justify-center"><span class="loader"></span></div>';

  Cotizador.post('/delete_art', formData, formData_header)
  .then( response => {
    console.log(response.data)
    e.target.reset();

    let id = response.data.art_id;
    let button = document.querySelector(`button[data-modal="modal_delete"][data-id="${id}"]`);

    console.log(button)
    let row = button.parentElement.parentElement.closest('tr');
    row.remove();

    btn.disabled = false;

    form_delete.parentElement.classList.add('hidden');
    form_delete.parentElement.classList.remove('modal_active');
  })
  .catch( err => console.log(err.message) );
  
});

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

let order_select = document.getElementById('order_by');
order_select?.addEventListener('change', e => {
  reorderTableRows(e.target.value);
});

reorderTableRows(order_select.value);



// const modalFileInput = document.querySelector('input[name="archivos[]"]');
const modalFileInput = document.querySelector('#modal_file_input');

const fileInput = document.getElementById('file_input');
const files_loaded = document.getElementById('files_loaded');

// Drag-and-drop container setup (same as earlier)
const dragDropContainer = document.getElementById('dragDropContainer');
const fileList = document.getElementById('fileList');


dragDropContainer?.addEventListener('dragover', (event) => {
  event.preventDefault();
  dragDropContainer.classList.add('dragover');
});

dragDropContainer?.addEventListener('dragleave', () => {
  dragDropContainer.classList.remove('dragover');
});

dragDropContainer?.addEventListener('drop', (event) => {
  event.preventDefault();
  dragDropContainer.classList.remove('dragover');
  handleFiles(event.dataTransfer.files);
});


let archivo_files = [];

function updateFileList() {
  dragDropContainer.querySelector('span').innerHTML = '';
  fileList.innerHTML = ''; 

  archivo_files.forEach((file, index) => {

    const fileItem = document.createElement('li');
    fileItem.innerHTML = `<span>${(file.size / 1024).toFixed(2)} KB<span> <span>${trimFileName(file.name)}</span>`;

    const deleteBtn = document.createElement('button');
    deleteBtn.className = 'delete-btn';
    deleteBtn.innerHTML = '&times;';

    deleteBtn.addEventListener('click', () => {
      archivo_files.splice(index, 1); 
      console.log(archivo_files)
      updateFileList();
    });

    fileItem.appendChild(deleteBtn);
    fileList.appendChild(fileItem);
  });
}


modalFileInput?.addEventListener('change', e => {
  if (e.target.files.length > 10) {
    alert('You can upload a maximum of 10 files.');
    return;
  }

  const _files = e.target.files;

  for (let i = 0; i < _files.length; i++) {
    archivo_files.push(_files[i]);
  }

  updateFileList();
});



const btn_open_files = document.getElementById('btn_open_files');
btn_open_files?.addEventListener('click', () => modalFileInput.click());

const btn_add_files = document.getElementById('btn_add_files');
btn_add_files?.addEventListener('click', () => {
  const totalFiles = fileList.children.length;
  let files_loaded = document.getElementById('files_loaded');
  files_loaded.textContent = `Archivos adjuntos: ${totalFiles}`;
});

const trimFileName = (fileName, maxLength = 18) => {
  if (fileName.length <= maxLength) {
    return fileName;
  }

  const extension = fileName.substring(fileName.lastIndexOf('.'));
  const baseName = fileName.substring(0, maxLength - extension.length);
  return `${baseName}...`;
};


  const results_container = document.querySelector('#results_container');
  const form_search = document.querySelector('#form_search');
  form_search?.addEventListener('submit', e => {
    e.preventDefault();
    let btn = e.target.querySelector('button[type="submit"]');
    let empty = document.querySelector('#row__empty');
    empty.style.display = 'none';

    document.querySelector('#loadingOverlay').style.display = 'block';
    btn.disabled = true;

    const formData = new FormData(e.target);

    Cotizador.post('/search', formData, formData_header)
    .then( response => {
      console.log(response.data)
      order_select.value = "";
      
      results_container.innerHTML = '';

      if (response.data.length > 0) {
        document.querySelector('#loadingOverlay').style.display = 'none';
        btn.disabled = false;

        response.data.forEach(art => {
          const row = document.createElement('tr');
          row.innerHTML =
            `
              <td>
                <div class="row__articulo">
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
                </div>
              </td>

              <td>
                <div class="row__fecha">
                  <span>${dateToString(art.fecha)}</span>
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
                  <div class="rounded text-icon border-2 border-icon w-fit " ><i class="fa fa-check px-1"></i></div>
                  <div class="rounded text-red border-2 border-red w-fit " ><i class="fa fa-minus px-1"></i></div>
                  <div class="rounded text-grayLight border-2 border-gray w-fit " ><i class="fa fa-minus px-1"></i></div>
                </div>
              </td>

              <td>
                <div class="row__adjunto ">
                  <button data-modal="modal_files" data-id="${art.cotiz_id}" class="btn_open_modal rounded text-icon w-fit " type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                    </svg>
                  </button>
                </div>
              </td>

              <td>
                <div class="row__comentario ">
                  <button data-modal="modal_comment" data-id="${art.art_id}" class="btn_open_modal rounded text-icon text-lg w-fit " type="button"><i class="fa px-2">${art.num_comm}</i></button>
                </div>
              </td>

              <td>
                <div class="row__actions ">
                  <button data-modal="modal_edit" data-id="${art.art_id}" data-cotiz="${art.cotiz_id}" class="btn_open_modal hover:text-blue px-2 " type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                    </svg>
                  </button>

                  <button data-modal="modal_delete" data-id="${art.art_id}" class="btn_open_modal hover:text-red px-2 " type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                  </button>
                </div>

              </td>
            `
          results_container.appendChild(row);

          initRowBtn();
        });

      } else {
        document.querySelector('#loadingOverlay').style.display = 'none';
        btn.disabled = false;
        empty.style.display = 'block';
      }
    })
    .catch( err => console.log(err.message) );
  });


  const form_edit = document.querySelector('#form_edit');
  form_edit?.addEventListener('submit', e => {

    e.preventDefault();
    let btn = e.target.querySelector('button[type="submit"]');
    let _overlay = e.target.querySelector('#_overlay');

    // btn.disabled = true;

    const formData = new FormData(e.target);

    archivo_files.forEach((file, index) => {
      formData.append(`archivo[${index}]`, file);
    });

    _overlay.classList.remove('hidden');

    Cotizador.post('/edit_art', formData, formData_header)
    .then( response => {
      console.log(response.data)

      if(response.data.success){
        files_loaded.textContent = '';
        dragDropContainer.querySelector('span').innerHTML = 'Arrastre y suelte sus archivos para agregarlos.';
        fileList.innerHTML = '';
        archivo_files = [];

        form_edit.reset();

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

        _overlay.classList.add('hidden');
        // return;
      }
    })
    .catch( err => console.log(err.message) );
    
  });


  const initModalComment = (id, icon) => {
    let comentarios_container = document.querySelector('#comentarios_container');
    comentarios_container.innerHTML = '<div class="p-6 w-full flex justify-center"><span class="loader"></span></div>';

    Cotizador.get('/get_comentarios', { params: { art_id: id } })
    .then( response => {
      // console.log(response.data); return;

      let _c = response.data;
      comentarios_container.innerHTML = '';
      icon.innerHTML = _c.num_comments;

      if ( _c.num_comments > 0 ) {

        _c.comments.forEach(comm => {
          const div = document.createElement('div');
          div.classList.add('row-comentario');
          div.innerHTML = `
            <div class="w-44 p-2">${comm.name} ${comm.last_name}</div>
            <div class="w-28 p-2 text-center">${dateToString(comm.created_at)}</div>
            <div class="p-2">${comm.comentario}</div>
          `;
          comentarios_container.appendChild(div);
        });

      } else {
        const div = document.createElement('div');
        div.classList.add('row-comentario');
        div.innerHTML = `
          <div class="p-2 w-full text-center text-gray">No se encontraron comentarios.</div>
        `;
        comentarios_container.appendChild(div);
      }

    })
    .catch( err => console.log(err.message) );
  }

  const initModalAprob = (id, icon, aprob_type) => {
    let modal_aprob = document.querySelector('#modal_aprobacion');
    let title = modal_aprob.querySelector('.modal_title');
    title.innerHTML = `Aprobación ${aprob_type}`
    return;

    let aprobaciones_container = document.querySelector('#aprobaciones_container');
    aprobaciones_container.innerHTML = '<div class="p-6 w-full flex justify-center"><span class="loader"></span></div>';

    Cotizador.get('/get_aprobacion', { params: { art_id: id } })
    .then( r => {
      console.log(r.data)

      aprobaciones_container.innerHTML = '';
      if (r.data.length > 0) {
        console.log("no hay data")
        r.data.forEach(aprob => {
          const div = document.createElement('div');
          div.classList.add('row-comentario');
          div.innerHTML = `
            <div class="w-64 p-2">${aprob.name} ${aprob.last_name}</div>
            <div class="w-28 p-2 text-center">${dateToString(aprob.created_at)}</div>
            <div class="w-28 p-2">${aprob.estado}</div>
            <div class="p-2">${aprob.comentario}</div>
          `;
          aprobaciones_container.appendChild(div);
        });

      } else {

        const div = document.createElement('div');
        div.classList.add('row-comentario');
        div.innerHTML = `
          <div class="p-2 w-full text-center text-gray">No se encontraron aprobaciones.</div>
        `;
        aprobaciones_container.appendChild(div);
      }
    })
    .catch( err => console.log(err.message) );
  }

  const initRowBtn = () => {
    const allBtnOpen = document.querySelectorAll('.btn_open_modal');
    allBtnOpen?.forEach( (btn, index) => {

      btn.addEventListener('click', e => {
        // console.log(e.currentTarget)
        let modal_id = e.currentTarget.getAttribute('data-modal');
        let modal = document.querySelector(`#${modal_id}`);

        modal.classList.add('modal_active');
        modal.classList.remove('hidden');

        if (modal_id == 'modal_edit') {
          let id = e.currentTarget.getAttribute('data-id');
          let cotiz_id = e.currentTarget.getAttribute('data-cotiz');

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

          Cotizador.get('/get_articulo_coti', { params: { cotiz_id: cotiz_id, art_id:id } })
          .then( response => {
            // console.log(response.data)
            // return;
            let art = response.data;

            form_edit.querySelector('[name="nombreDelArticulo"]').value = art.articulo;
            form_edit.querySelector('[name="proveedor"]').value = art.proveedor;
            form_edit.querySelector('[name="origen"]').value = art.origen;
            form_edit.querySelector('[name="incoterm"]').value = art.incoterm;
            form_edit.querySelector('[name="fecha"]').value = dateToString(art.fecha);
            form_edit.querySelector('[name="vigencia"]').value = art.vigencia;
            form_edit.querySelector('[name="costoPorUnidad"]').value = art.costo;
            form_edit.querySelector('[name="minimo"]').value = art.minimo;
            setSelectedOption('#form_edit #divisa', art.divisa);
            setSelectedOption('#form_edit #impuesto', art.impuesto);
            setSelectedOption('#form_edit #medicion', art.medicion);
            setSelectedOption('#form_edit #periodo', art.periodo);
            setSelectedOption('#form_edit #tipoDia', art.tipoDia);


            form_edit.querySelector('[name="cantidadPer"]').value = art.cantidadPer;
            form_edit.querySelector('#diasDeEnvio').value = art.diasDeEnvio;
            form_edit.querySelector('[name="importe"]').value = art.importe;


          })
          .catch( err => console.log(err.message) );


        } else if (modal_id == 'modal_delete') {
          let id = e.currentTarget.getAttribute('data-id');

          const artId= document.createElement('input');
          artId.type = 'hidden'
          artId.name = 'art_id';
          artId.value = id;
          form_delete.prepend(artId);

        } else if (modal_id == 'modal_files') {

          let id = e.currentTarget.getAttribute('data-id');
          let archivos_container = document.querySelector('#archivos_container');
          archivos_container.innerHTML = '<div class="p-6 w-full flex justify-center"><span class="loader"></span></div>';

          Cotizador.get('/get_adjuntos', { params: { cotiz_id: id } })
          .then( response => {
            // console.log(response.data)

            archivos_container.innerHTML = '';
            if (response.data.length > 0) {
              response.data.forEach(adj => {
                const div = document.createElement('div');
                div.classList.add('row-adjunto');
                div.innerHTML = `
                  <div class="w-28 text-center text-gray ">${dateToString(adj.fecha)}</div>
                  <div class="flex-grow space-x-2 w-full px-8 text-link">
                    <i class="fas fa-paperclip"></i>
                    <a href="${root}/files/download?path=${adj.archivo}" target="_blank" class="underline ">${getEncodedFileName(adj.archivo)}</a>
                  </div>
                `;
                archivos_container.appendChild(div);
              });

            } else {

              const div = document.createElement('div');
              div.classList.add('row-adjunto');
              div.innerHTML = `
                <div class="p-2 w-full text-center text-gray">No se encontraron archivos.</div>
              `;
              archivos_container.appendChild(div);
            }
          })
          .catch( err => console.log(err.message) );

        } else if (modal_id == 'modal_comment') {

          let id = e.currentTarget.getAttribute('data-id');
          let icon = e.currentTarget.querySelector('i');
          // console.log('id =', id)

          let artId = form_add_comment.querySelector('[name="art_id"]');
          artId.value = id;

          initModalComment(id, icon);

        } else if (modal_id == 'modal_aprobacion') {

          // console.log(e.currentTarget);
          let id = e.currentTarget.getAttribute('data-id');
          let aprob_type = e.currentTarget.getAttribute('data-aprob');

          let icon = e.currentTarget.querySelector('i');
          console.log('id =', id)

          let artId = form_aprobacion.querySelector('[name="art_id"]');
          artId.value = id;
          console.log(artId)
          initModalAprob(id, icon, aprob_type);
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

          files_loaded.textContent = '';
          dragDropContainer.querySelector('span').innerHTML = 'Arrastre y suelte sus archivos para agregarlos.';
          fileList.innerHTML = '';
          archivo_files = [];

          form_add_comment.reset();
          form_edit.reset();
        }
      });
    });

  }

  initRowBtn();


  function calcularImporte(input) {
    const costoPorUnidad = form_edit.querySelector('[name="costoPorUnidad"]').value;
    const minimo = form_edit.querySelector('[name="minimo"]').value;
    const importe = form_edit.querySelector('[name="importe"]');
    importe.value = (costoPorUnidad * minimo).toFixed(2);
  }


  function calcularDias(element) {
    const tipo_dia = element.value;

    const _fila = element.parentElement;
    const dias = _fila.querySelector('[name="cantidadPer[]"]').value;
    const periodo = _fila.querySelector('[name="periodo[]"]').value;
    const diasDeEnvio = _fila.querySelector('[name="diasDeEnvio[]"]');
    // console.log(diasDeEnvio)

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

  const loadAllArticles = () => {
    Cotizador.get('/aprobaciones', formData, formData_header)
    .then( response => {
      console.log(response.data)
      order_select.value = "";
      
      results_container.innerHTML = '';

      if (response.data.length > 0) {
        document.querySelector('#loadingOverlay').style.display = 'none';
        btn.disabled = false;

        response.data.forEach(art => {
          const row = document.createElement('tr');
          row.innerHTML =
            `
              <td>
                <div class="row__articulo">
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
                </div>
              </td>

              <td>
                <div class="row__fecha">
                  <span>${dateToString(art.fecha)}</span>
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
                  <div class="rounded text-icon border-2 border-icon w-fit " ><i class="fa fa-check px-1"></i></div>
                  <div class="rounded text-red border-2 border-red w-fit " ><i class="fa fa-minus px-1"></i></div>
                  <div class="rounded text-grayLight border-2 border-gray w-fit " ><i class="fa fa-minus px-1"></i></div>
                </div>
              </td>

              <td>
                <div class="row__adjunto ">
                  <button data-modal="modal_files" data-id="${art.cotiz_id}" class="btn_open_modal rounded text-icon w-fit " type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                    </svg>
                  </button>
                </div>
              </td>

              <td>
                <div class="row__comentario ">
                  <button data-modal="modal_comment" data-id="${art.art_id}" class="btn_open_modal rounded text-icon text-lg w-fit " type="button"><i class="fa px-2">${art.num_comm}</i></button>
                </div>
              </td>

              <td>
                <div class="row__actions ">
                  <button data-modal="modal_edit" data-id="${art.art_id}" data-cotiz="${art.cotiz_id}" class="btn_open_modal hover:text-blue px-2 " type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                    </svg>
                  </button>

                  <button data-modal="modal_delete" data-id="${art.art_id}" class="btn_open_modal hover:text-red px-2 " type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                  </button>
                </div>

              </td>
            `
          results_container.appendChild(row);

          initRowBtn();
        });

      } else {
        document.querySelector('#loadingOverlay').style.display = 'none';
        btn.disabled = false;
        empty.style.display = 'block';
      }
    })
    .catch( err => console.log(err.message) );

  }


loadAllArticles();
</script>
</body>
</html>