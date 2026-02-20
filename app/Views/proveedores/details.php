<?php echo view('_partials/header'); ?>

  <link rel="stylesheet" href="<?= base_url('_partials/maqui.css') ?>">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">
  <div id="overlay"></div>  
  <div id="loadingOverlay"><div id="loadingSpinner"></div></div>  


  <img src="<?= base_url('img/mantenimientoweb.svg') ?>" class="hidden lg:flex absolute bottom-0 left-0 ">
  <img src="<?= base_url('img/mantenimientomovil.svg') ?>" class="flex lg:hidden absolute bottom-0 left-0 ">

  <div class=" relative h-full flex flex-col items-center justify-center font-titil ">

    <?php echo view('cotizar/_partials/navbar'); ?>

    <a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer">Cerrar Sesión</a>

    <div class="text-title w-full md:pt-4 md:pb-8 md:px-16 p-2 flex items-center ">
      <h2 class="text-center font-bold w-full text-2xl lg:text-3xl "><?= esc($title) ?></h2>
      <a href="<?= base_url("proveedores") ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>

    <div class="w-full text-sm text-gray  ">
      <div class="flex flex-col h-full" >

         <!-- form_create -->
        <form id="form_editar_prov" class="pb-8 px-4 lg:px-10 flex w-full space-x-4 items-center justify-center" method="post">
          <?= csrf_field() ?>

          <div class="relative flex flex-col w-72 ">
            <h5 class="text-center uppercase">Razon social</h5>
            <input type="text" id="razon_social" name="razon_social" required placeholder="Razon social " value="<?= $proveedor['razon_social'] ?>">
          </div>

          <div class="relative flex flex-col w-72">
            <h5 class="text-center uppercase">Direccion</h5>
            <input type="text" id="direccion" name="direccion" required placeholder="" value="<?= $proveedor['direccion'] ?>">
          </div>

          <div class="relative flex flex-col ">
            <h5 class="text-center uppercase">Pais</h5>
            <input type="text" id="pais" name="pais" required placeholder="" value="<?= $proveedor['pais'] ?>">
          </div>

          <div class="relative flex flex-col ">
            <h5 class="text-center uppercase">Tipo</h5>
            <input type="text" id="tipo_prov" name="tipo_prov" required placeholder="" value="<?= $proveedor['tipo_prov'] ?>">
          </div>

          <div class="relative flex flex-col w-72">
            <h5 class="text-center uppercase">Sitio Web</h5>
            <input type="text" id="sitio_web" name="sitio_web" required placeholder="" value="<?= $proveedor['sitio_web'] ?>">
          </div>

          <input type="hidden" name="proveedorId" value="<?= $proveedor['id'] ?>" >


          <button name="editar_prov" class="flex space-x-4 items-center shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit" >
            <span class="fas fa-plus"></span>
            <span>Guardar Cambios</span>
          </button>

        </form>

<!--         <div class="pb-4 px-4 lg:px-10 flex w-fit items-center space-x-4">
          <span>ORDENAR POR:</span>
          <select id="order_by" name="order_by" class="w-44">
            <option value="" disabled selected>Seleccionar...</option>
            <option value="asc-fecha">- a + Fecha Adqui.</option>
            <option value="desc-fecha">+ a - Fecha Adqui.</option>
            <option value="asc-nombre">A - Z</option>
            <option value="desc-nombre">Z - A</option>
          </select>
        </div> -->

        <div class="w-full px-4 lg:px-10 flex items-start min-h-72 ">
          <div class="relative top-0 w-full " >
            <table id="tabla-proveedores-cont" class="relative top-0 w-full">
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Puesto</th>
                  <th>Telefono</th>
                  <th>Correo</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody id="results_container">
                <?php foreach ($prov_cont as $cont): ?>
                  <tr>
                    <td><div class="row__cell"><span><?= $cont['nombre'] ?></span></div> </td>
                    <td><div class="row__cell"><span><?= $cont['puesto'] ?></span></div> </td>
                    <td><div class="row__cell"><span><?= $cont['telefono'] ?></span></div></td>
                    <td><div class="row__cell"><span><?= $cont['correo'] ?></span></div></td>
                    <td>
                      <div class="row__cell">
                        <a href="<?= base_url("proveedores/contacto/" . $cont['id']) ?>"><span class="text-link ">Editar</span></a>
                        <!-- <button data-modal="modal_delete" data-id="" class="btn_open_modal hover:text-red px-2" type="button">Eliminar</button> -->
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


         <!-- form_create contacto-->
        <form id="form_crear_cont" class="pb-8 px-4 lg:px-10 flex w-full space-x-4 items-center justify-center" method="post">
          <?= csrf_field() ?>

          <div class="relative flex flex-col w-72 ">
            <h5 class="text-center uppercase">Nombre</h5>
            <input type="text" id="nombre" name="nombre" placeholder="Razon social " required >
          </div>

          <div class="relative flex flex-col w-72">
            <h5 class="text-center uppercase">Puesto</h5>
            <input type="text" id="puesto" name="puesto" placeholder="" required>
          </div>

          <div class="relative flex flex-col ">
            <h5 class="text-center uppercase">Telefono</h5>
            <input type="text" id="telefono" name="telefono" placeholder="" required>
          </div>

          <div class="relative flex flex-col ">
            <h5 class="text-center uppercase">Correo</h5>
            <input type="email" id="correo" name="correo" placeholder="" required >
          </div>

          <input type="hidden" name="proveedorId" value="<?= $proveedor['id'] ?>" >

          <button name="crear_cont" class="flex space-x-4 items-center shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit" >
            <span class="fas fa-plus"></span>
            <span>Añadir Contacto</span>
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



const form_create = document.querySelector('#form_create');
  form_create?.addEventListener('submit', e => {
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

// reorderTableRows(order_select.value);


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