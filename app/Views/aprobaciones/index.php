<?php echo view('_partials/header'); ?>
  <link rel="stylesheet" href="<?= load_asset('_partials/aprobaciones.css') ?>">

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

    <a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer">Cerrar Sesión</a>

    <div class="text-title w-full md:pt-4 md:pb-2 md:px-16 p-2 flex items-center ">
      <h2 class="text-center font-bold w-full text-3xl "><?= esc($title) ?></h2>
      <a href="<?= base_url('apps') ?>" class="self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>

    <div class="w-full text-sm text-gray flex p-4 ">
      <div class="flex space-x-4 w-full h-full justify-center" >

        <div class="col-l bg-grayMid rounded border border-grayMid p-2">
          <form id="form_search" class="flex flex-col gap-y-4 w-full items-center justify-center px-4 " method="post" >
            <?= csrf_field() ?>
            <!-- <h3 class="text-gray text-lg">Filtro Aprobaciones</h3> -->

            <div class="relative flex flex-col  w-full">
              <h5 class="text-center text-xs uppercase">COSTOS</h5>
              <select name="status_costos" >
                <option value="" selected>Seleccionar...</option>
                <option value="PENDIENTE">PENDIENTE</option>
                <option value="NO APROBADO">NO APROBADO</option>
                <option value="APROBADO">APROBADO</option>
              </select>
            </div>

            <div class="relative flex flex-col  w-full">
              <h5 class="text-center text-xs uppercase">Desarrollo</h5>
              <select name="status_desarrollo" >
                <option value="" selected>Seleccionar...</option>
                <option value="PENDIENTE">PENDIENTE</option>
                <option value="NO APROBADO">NO APROBADO</option>
                <option value="APROBADO">APROBADO</option>
              </select>
            </div>

            <div class="relative flex flex-col  w-full">
              <h5 class="text-center text-xs uppercase">Calidad</h5>
              <select name="status_calidad" >
                <option value="" selected>Seleccionar...</option>
                <option value="PENDIENTE">PENDIENTE</option>
                <option value="NO APROBADO">NO APROBADO</option>
                <option value="APROBADO">APROBADO</option>
              </select>
            </div>

            <div class="relative flex flex-col  w-full">
              <h5 class="text-center text-xs uppercase">Proveedor</h5>
              <input type="text" id="proveedor" name="proveedor" placeholder="Razón social de proveedor" >
              <ul id="lista_prov" class="absolute z-40 top-12 bg-grayLight border border-super w-full h-32 overflow-y-scroll"></ul>
            </div>

            <div class="relative flex flex-col  w-full">
              <h5 class="text-center text-xs uppercase">Articulo</h5>
              <input type="text" id="articulo" name="articulo" placeholder="Nombre de artículo" >
              <ul id="lista_articulos" class="absolute z-40 top-12 bg-grayLight border border-super w-full h-32 overflow-y-scroll"></ul>
            </div>

            <div class="flex flex-col   w-full">
              <h5 class="text-center text-xs uppercase">Fecha</h5>
              <input id="fecha" type="date" name="fecha" >
            </div>

            <div class="flex flex-col w-full ">
              <h5 class="text-center text-xs uppercase">Origen</h5>
              <select name="origen" id="origen" >
                <option value="" selected>Seleccionar...</option>
                <option value="nacional">Nacional</option>
                <option value="importado">Importado</option>
              </select>
            </div>

            <div class="flex flex-col w-full ">
              <h5 class="text-center text-xs uppercase">Divisa</h5>
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
              <h5 class="text-center text-xs uppercase">Impuesto</h5>
              <select name="impuesto" >
                <option value="" selected>Seleccionar...</option>
                <option>16%</option>
                <option>0%</option>
                <option>Ex.I</option>
              </select>
            </div>

            <button id="btn_search" type="submit"><span>BUSCAR</span></button>
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

          <div class="w-full ">
						<div class="relative w-full text-sm h-[55vh] overflow-y-scroll ">
							<table id="table_results">
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
                        <span>Incoterm</span>
                      </div>
                    </th>
                    <th>
                      <div class="row__aprobacion">
                        <span>Cos</span>
                        <span>Des</span>
                        <span>Cal</span>
                      </div>
                    </th>
                    <th>Adj.</th>
                    <th>Coment.</th>
                    <!-- <th>Acciones</th> -->
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
      <input type="hidden" name="art_id" >

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
			<input type="hidden" name="art_id" >

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
			<input type="hidden" name="art_id" >

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
            <select id="status_calidad" name="status" class="input_alt w-32" required>
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

<!-- Modal Comment -->
<div id="modal_comment" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil">
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
<div id="modal_files" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil">
  <div class="flex flex-col space-y-8 bg-white border-2 border-icon p-10 w-full md:max-w-4xl">

    <div class="relative flex w-full justify-center text-center  ">
      <h3 class="text-gray text-xl uppercase">Archivos Adjuntos</h3>
      <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
    </div>

    <div class=" flex flex-col space-y-2 w-full text-sm">
      <div class="flex w-full text-center">
        <div class="w-44 bg-icon text-white p-2">Nombre</div>
        <div class="w-28 bg-icon text-white p-2">Fecha</div>
        <div class="flex-grow bg-icon text-white p-2">Archivo</div>
      </div>
      <div id="archivos_container" class="flex flex-col space-y-2 w-full "></div>
    </div>

  </div>
</div>

<?php echo view('_partials/_modal_msg'); ?>

<script>

  document.addEventListener('DOMContentLoaded', () => {
    const btn_search = document.querySelector("#btn_search");
    const svgIcon = document.createElement("span");
    svgIcon.innerHTML = getIcon('search');
    // console.log(svgIcon)
    btn_search.prepend(svgIcon);
  });


  Service.setLoading();

  const addComment = document.querySelector('#add_comment');
  const btnAddComment = document.querySelector('#btn_add_comment');
  btnAddComment?.addEventListener('click', e => addComment.classList.remove('hidden'));

  const submitAprob = (e) => {
    e.preventDefault();
 
    let comentario = e.target.querySelector('textarea[name="comentario"]');

    if (comentario.value.length > 5) {
      Service.stopSubmit(e.target, true);

      const formData = new FormData(e.target);

      Service.exec('post', `/add_aprobacion`, formData_header, formData)
      .then( r => {
        // return;
        e.target.reset();
        initModalAprob(r.art_id, r.area);
        loadAllArticles();
        Service.stopSubmit(e.target, false);
      });
    }
  }


  const form_aprobacion_desarrollo = document.querySelector('#form_aprobacion_desarrollo');
  form_aprobacion_desarrollo?.addEventListener('submit', submitAprob);

  const form_aprobacion_calidad = document.querySelector('#form_aprobacion_calidad');
  form_aprobacion_calidad?.addEventListener('submit', submitAprob);

  const form_aprobacion_costos = document.querySelector('#form_aprobacion_costos');
  form_aprobacion_costos?.addEventListener('submit', submitAprob);

  const submitComm = (e) => {
    e.preventDefault();
    let comentario = form_add_comment.querySelector('textarea[name="comentario"]');

    if (comentario.value.length > 5) {
      Service.stopSubmit(e.target, true);
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

  // reorderTableRows(order_select.value);

  const results_container = document.querySelector('#results_container');

  const form_search = document.querySelector('#form_search');
  form_search?.addEventListener('submit', e => {
    e.preventDefault();
    Service.hide('#row__empty');
    Service.stopSubmit(e.target, true);

    results_container.innerHTML = Service.loader();

    const formData = new FormData(e.target);

    Service.exec('post', `/search`, formData_header, formData)
    .then(r => {
      renderArticles(r);
      Service.stopSubmit(e.target, false);
    });  
  });


  const initModalComment = (id, icon) => {
    let comentarios_container = document.querySelector('#comentarios_container');
    comentarios_container.innerHTML = Service.loader();

    let artId = form_add_comment.querySelector('[name="art_id"]');
    artId.value = id;

    Service.exec('get', `/get_comentarios`, { params: { art_id: id } })
    .then( r => {
      // console.log(r); return;
      comentarios_container.innerHTML = "";
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

    Service.exec('get', `/get_aprobacion`, {params: { art_id: id, area: aprob_type }})
    .then( r => {

      let form = document.querySelector(`#form_aprobacion_${aprob_type}`);

			if(form) {
				let art_id = form.querySelector('input[name="art_id"]');
				let comentario = form.querySelector('textarea[name="comentario"]');
				let aprobId = form.querySelector('input[name="aprobId"]');
			}

			// form?.reset();

      aprobaciones_container.innerHTML = "";

      if (r) {

				const div = document.createElement('div');
				div.className = 'row-comentario';
				div.innerHTML = `
					<div class="w-64 p-2">${r.name} ${r.last_name}</div>
					<div class="w-28 p-2 text-center">${dateToString(r.created_at)}</div>
					<div class="w-28 p-2 text-center font-bold ${setArticleStatusText(r.status)}">${r.status}</div>
					<div class="p-2">${r.comentario}</div>
				`;
				aprobaciones_container.appendChild(div);		
				
				if(form) {
					art_id.value = r.articuloId;
					aprobId.value = r.id;
					comentario.value = r.comentario;
					setSelectedOption(`#status_${aprob_type}`, r.status, 'string');
					console.log(aprobId)
				}

        
      } else {
				if(form) {
					form?.reset();
					art_id.value = id;
					aprobId.value = "";
					// console.log(aprobId)
				}

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
				e.stopPropagation()
        // console.log(e.currentTarget)
        let modal_id = e.currentTarget.getAttribute('data-modal');
        let modal = document.querySelector(`#${modal_id}`);
        modal.classList.add('modal_active');
        modal.classList.remove('hidden');

        if (modal_id == 'modal_files') {

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

        } else if (modal_id == 'modal_comment') {
          let id = e.currentTarget.getAttribute('data-id');
          let icon = e.currentTarget.querySelector('i');
          initModalComment(id, icon);

        } else if (modal_id == 'modal_aprobacion_costos' || modal_id == 'modal_aprobacion_desarrollo' || modal_id == 'modal_aprobacion_calidad') {
          let id = e.currentTarget.getAttribute('data-id');
          let aprob_type = e.currentTarget.getAttribute('data-aprob');
          // modal.setAttribute('data-artId', id);
					// console.log(modal)
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
          form_add_comment.reset();
        }
      });
    });
  }

  const renderArticles = (data) => {  
    order_select.value = "";
    results_container.innerHTML = "";
    // console.log(data); return;

    if (data.length > 0) {

      data.forEach(art => {
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
              <div class="row__comentario ">
                <button data-modal="modal_comment" data-id="${art.art_id}" class="btn_open_modal rounded text-icon text-lg w-fit" type="button"><i class="fa px-2">${art.num_comm}</i></button>
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
    results_container.innerHTML = Service.loader();

    Service.exec('get', `/all_articles`)
    .then(r => renderArticles(r));  
  }

  loadAllArticles();

</script>
</body>
</html>