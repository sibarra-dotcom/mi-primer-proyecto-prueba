<?php echo view('_partials/header'); ?>

  <link rel="stylesheet" href="<?= base_url('_partials/maqui.css') ?>">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
  <script src="<?= base_url('js/axios.js') ?>"></script>
  <script src="<?= base_url('js/helper.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">
  <div id="overlay"></div>  
  <div id="loadingOverlay"><div id="loadingSpinner"></div></div>  


  <img src="<?= base_url('img/mantenimientoweb.svg') ?>" class="hidden lg:flex absolute bottom-0 left-0 ">
  <img src="<?= base_url('img/mantenimientomovil.svg') ?>" class="flex lg:hidden absolute bottom-0 left-0 ">

  <div class=" relative h-full flex flex-col items-center justify-center font-titil ">

    <?php echo view('mantenimiento/_partials/navbar'); ?>

    <a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer">Cerrar Sesión</a>

    <div class="text-title w-full md:pt-4 md:pb-8 md:px-16 p-2 flex items-center ">
      <h2 class="text-center font-bold w-full text-2xl lg:text-3xl "><?= esc($title) ?></h2>
      <a href="<?= base_url('apps') ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>

    <div class="w-full text-sm text-gray  ">
      <div class="flex flex-col h-full" >

        <form id="form_search" class="pb-8 px-4 lg:px-10 flex w-full space-x-4 items-center justify-center ">

          <div class="relative flex flex-col w-24 ">
            <h5 class="text-center uppercase">Id</h5>
            <input type="text" id="maqId" name="maqId" placeholder="Id ..." >
          </div>

          <div class="relative flex flex-col w-44 ">
            <h5 class="text-center uppercase">Nombre</h5>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre ..." >
          </div>

          <div class="relative flex flex-col ">
            <h5 class="text-center uppercase">Marca</h5>
            <input type="text" id="marca" name="marca" placeholder="Marca ..." >
          </div>

          <div class="relative flex flex-col ">
            <h5 class="text-center uppercase">Modelo</h5>
            <input type="text" id="modelo" name="modelo" placeholder="Modelo ..." >
          </div>

          <div class="relative flex flex-col ">
            <h5 class="text-center uppercase">N° Serie</h5>
            <input type="text" id="serie" name="serie" placeholder="N° Serie ..." >
          </div>

          <div class="flex flex-col   ">
            <h5 class="text-center uppercase">Planta</h5>
            <select id="planta" name="planta" >
              <option value="" disabled selected>Seleccionar...</option>
              <option value="1 ARTES">1 ARTES</option>
              <option value="2 CEDIS">2 CEDIS</option>
            </select>
          </div>

          <div class="flex flex-col   ">
            <h5 class="text-center uppercase">Linea</h5>
            <select id="linea" name="linea" >
              <option value="" disabled selected>Seleccionar...</option>
              <option value="1">Linea 1</option>
              <option value="2">Linea 2</option>
              <option value="3">Linea 3</option>
              <option value="4">Linea 4</option>
            </select>
          </div>

          <button id="btn_search" class="flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit" >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <span>BUSCAR</span>
          </button>

        </form>


        <div class="pb-4 px-4 lg:px-10 flex w-fit items-center space-x-4">
          <span>ORDENAR POR:</span>
          <select id="order_by" name="order_by" class="w-44">
            <option value="" disabled selected>Seleccionar...</option>
            <option value="asc-fecha">- a + Fecha Adqui.</option>
            <option value="desc-fecha">+ a - Fecha Adqui.</option>
            <option value="asc-nombre">A - Z</option>
            <option value="desc-nombre">Z - A</option>
          </select>
        </div>

        <div class="w-full px-4 lg:px-10 flex items-start min-h-72 ">
          <div class="relative top-0 w-full " >
            <table class="relative top-0 w-full">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Marca</th>
                  <th>Modelo</th>
                  <th>N° Serie</th>
                  <th>Año</th>
                  <th>Planta</th>
                  <th>Linea</th>
                  <th>Fecha Adquisicion</th>
                  <!-- <th>Acciones</th> -->
                </tr>
              </thead>
              <tbody id="results_container">
                <?php foreach ($maquinas as $maq): ?>
                  <tr>
                    <td><div class="row__cell"><span><?= format_id($maq['id'], 'id') ?></span></div></td>
                    <td><div class="row__cell"><span><?= $maq['nombre'] ?></span></div> </td>
                    <td><div class="row__cell"><span><?= $maq['marca'] ?></span></div> </td>
                    <td><div class="row__cell"><span><?= $maq['modelo'] ?></span></div></td>
                    <td><div class="row__cell"><span><?= $maq['serie'] ?></span></div></td>
                    <td><div class="row__cell"><span><?= $maq['year'] ?></span></div></td>
                    <td><div class="row__cell"><span><?= $maq['planta'] ?></span></div></td>
                    <td><div class="row__cell"><span><?= $maq['linea'] ?></span></div></td>
                    <td><div class="row__cell"><span><?= dateToString($maq['fechaAdqui']) ?></span></div></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>

            </table>
            <div id="row__empty" class="row__empty">
              No hay coincidencias.
            </div>
            
          </div>
        </div>



        <!-- form_create -->
        <form id="form_create" class="pb-8 px-4 lg:px-10 flex w-full space-x-4 items-center justify-center" method="post">
          <?= csrf_field() ?>

          <div class="relative flex flex-col w-44 ">
            <h5 class="text-center uppercase">Nombre</h5>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre ..." >
          </div>

          <div class="relative flex flex-col ">
            <h5 class="text-center uppercase">Marca</h5>
            <input type="text" id="marca" name="marca" placeholder="Marca ..." >
          </div>

          <div class="relative flex flex-col ">
            <h5 class="text-center uppercase">Modelo</h5>
            <input type="text" id="modelo" name="modelo" placeholder="Modelo ..." >
          </div>

          <div class="relative flex flex-col ">
            <h5 class="text-center uppercase">N° Serie</h5>
            <input type="text" id="serie" name="serie" placeholder="N° Serie ..." >
          </div>

          <div class="relative flex flex-col ">
            <h5 class="text-center uppercase">Año</h5>
            <input type="text" id="year" name="year" placeholder="Año ..." >
          </div>

          <div class="flex flex-col   ">
            <h5 class="text-center uppercase">Planta</h5>
            <select name="planta" id="planta" required>
              <option value="" disabled selected>Seleccionar...</option>
              <option value="1 ARTES">1 ARTES</option>
              <option value="2 CEDIS">2 CEDIS</option>
            </select>
          </div>

          <div class="flex flex-col   ">
            <h5 class="text-center uppercase">Linea</h5>
            <select name="linea" id="linea" required>
              <option value="" disabled selected>Seleccionar...</option>
              <option value="1">Linea 1</option>
              <option value="2">Linea 2</option>
              <option value="3">Linea 3</option>
              <option value="4">Linea 4</option>
            </select>
          </div>

          <div class="flex flex-col ">
            <h5 class="text-center uppercase">Fecha Adq.</h5>
            <input id="fechaAdqui" type="date" name="fechaAdqui" required>
          </div>

          <button class="flex space-x-4 items-center shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit" >
            <span class="fas fa-plus"></span>
            <span>Añadir</span>
          </button>

        </form>

      </div>
    </div>
  </div>

<?php echo view('_partials/_modal_msg'); ?>


<script>
let overlay = document.querySelector('#overlay');
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
    document.body.classList.remove('no-scroll');
    overlay.style.display = 'none';
  }
});



const _alert = document.querySelector('#msg_alert');
  if (_alert) {
  setTimeout(()=> {
    _alert.remove();
  }, 14000);
}

const form_create = document.querySelector('#form_create');
  form_create.addEventListener('submit', e => {
  e.preventDefault();
  let btn = e.target.querySelector('button[type="submit"]');

  document.querySelector('#loadingOverlay').style.display = 'block';
  btn.disabled = true;

  e.target.submit();
});



window.addEventListener('click', (event) => {
  let modal_active = document.querySelector('.modal_active');
  if (event.target === modal_active) {
    modal_active.classList.remove('modal_active');
    modal_active.classList.add('hidden');
    // console.log(modal_active)
  }
});


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

        if (modal_id == 'modal_comment') {
          btn.setAttribute('data-index', index); 

          modal_textarea.value = '';
          btn_save_comment.id = index; 
          // console.log(modal)

          const row = e.target.closest('tr');
          currentCommentInput = row.querySelector('.commentInput');

          modal_textarea.value = currentCommentInput.value || '';
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


    const delBtn = document.querySelectorAll('.btn-delete');
    delBtn?.forEach( btn => {
      btn.addEventListener('click', (e) => {
        e.currentTarget.parentNode.parentNode.remove();
      });
    });
  }

  initRowBtn();



const results_container = document.querySelector('#results_container');

const reorderTableRows = (order) => {

  const rows = Array.from(results_container.rows);

  rows.sort((a, b) => {
    let valA, valB;

    if (order.includes('nombre')) {
      valA = a.cells[1].querySelectorAll('span')[0].innerText.trim().toLowerCase();
      valB = b.cells[1].querySelectorAll('span')[0].innerText.trim().toLowerCase();
    } else if (order.includes('costo')) {
      const sizeA = parseFloat(a.cells[2].querySelectorAll('span')[0].innerText);
      const sizeB = parseFloat(b.cells[2].querySelectorAll('span')[0].innerText);
      valA = sizeA;
      valB = sizeB;
    } else if (order.includes('dias')) {
      valA = parseInt(a.cells[5].querySelectorAll('span')[0].innerText.trim(), 10);
      valB = parseInt(b.cells[5].querySelectorAll('span')[0].innerText.trim(), 10);
    } else if (order.includes('fecha')) {
      const dateA = moment(a.cells[8].querySelectorAll('span')[0].innerText.trim(), 'DD-MM-YYYY');
      const dateB = moment(b.cells[8].querySelectorAll('span')[0].innerText.trim(), 'DD-MM-YYYY');
      valA = dateA;
      valB = dateB;
    }

    if (order.includes('asc')) {
      return valA > valB ? 1 : -1;
    } else {
      return valA < valB ? 1 : -1;
    }
  });

  rows.forEach(row => results_container.appendChild(row));
};

let order_select = document.getElementById('order_by');
order_select?.addEventListener('change', e => {
  reorderTableRows(e.target.value);
});

reorderTableRows(order_select.value);


const form_search = document.querySelector('#form_search');
form_search?.addEventListener('submit', e => {
  e.preventDefault();
  let btn = e.target.querySelector('button[type="submit"]');
  let empty = document.querySelector('#row__empty');
  empty.style.display = 'none';

  document.querySelector('#loadingOverlay').style.display = 'block';
  btn.disabled = true;

  const formData = new FormData(e.target);

  Cotizador.post('/maq_search', formData, formData_header)
  .then( response => {
    console.log(response.data)
    results_container.innerHTML = '';

    if (response.data.length > 0) {
      document.querySelector('#loadingOverlay').style.display = 'none';
      btn.disabled = false;

      response.data.forEach(maq => {
        const row = document.createElement('tr');
        row.innerHTML =
          `
            <td><div class="row__cell"><span>${format_id(maq.id, 'id')}</span></div></td>
            <td><div class="row__cell"><span>${maq.nombre}</span></div> </td>
            <td><div class="row__cell"><span>${maq.marca}</span></div> </td>
            <td><div class="row__cell"><span>${maq.modelo}</span></div></td>
            <td><div class="row__cell"><span>${maq.serie}</span></div></td>
            <td><div class="row__cell"><span>${maq.year}</span></div></td>
            <td><div class="row__cell"><span>${maq.planta}</span></div></td>
            <td><div class="row__cell"><span>${maq.linea}</span></div></td>
            <td><div class="row__cell"><span>${dateToString(maq.fechaAdqui)}</span></div></td>
          `

        // li.textContent = art.nombreDelArticulo;
        // li.style.cursor = 'pointer'; 
        // li.addEventListener('click', () => {
        //   articulo.value = art.nombreDelArticulo;
        //   lista_articulos.style.display = 'none'; 
        // });

        results_container.appendChild(row);

      });

    } else {
      document.querySelector('#loadingOverlay').style.display = 'none';
      btn.disabled = false;
      empty.style.display = 'block';
    }
  })
  .catch( err => console.log(err.message) );

});


</script>
</body>
</html>