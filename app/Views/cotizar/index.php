<?php echo view('_partials/header'); ?>

  <link rel="stylesheet" href="<?= load_asset('_partials/cotizar.css') ?>">

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

		<div id="overlayOrientation" class=" absolute top-0 left-0 w-full z-30 h-full bg-white text-title pt-48 px-8 ">
			<h1 class="text-4xl text-center pb-10 ">Gira el dispositivo.</h1>
			<img src="<?= base_url('img/rotating-phone.svg') ?>" class="h-44 w-44 mx-auto text-title">  
		</div>  


    <?php echo view('cotizar/_partials/navbar'); ?>

    <a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer">Cerrar Sesión</a>


    <div class="text-title w-full md:pt-4 md:pb-8 md:px-12 p-2 flex items-center justify-between ">
      <div class="flex flex-col w-40 items-center "> 
        <span class="text-gray uppercase">Fecha</span>
        <div class="text-white py-1 px-4 bg-icon uppercase"><?php echo date('d-m-Y'); ?></div> 
      </div>

      <h2 class="text-center font-bold w-full text-3xl "><?= esc($title) ?></h2>
      <a href="<?= base_url('apps') ?>" class="self-end hover:scale-105 transition-transform duration-300"><i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>

    <div class="w-full text-sm text-gray  ">
      <form id="form_cotizar" class="flex flex-col h-full" method="post" enctype='multipart/form-data'>
        <?= csrf_field() ?>

        <div class="pb-8 px-10 flex w-full items-center justify-between  ">
          <div class="flex w-full items-center justify-start ">
            <div class="flex flex-col pr-4  w-44">
              <h5 class="text-center uppercase">Cotizador</h5>
              <input type="text" id="cotizador" name="cotizador" value="<?= session()->get('user')['name'] . ' ' . session()->get('user')['last_name'] ?>" readonly>
            </div>

            <div class="relative flex flex-col pr-4  w-72">
              <h5 class="text-center uppercase">Proveedor</h5>
              <input type="hidden" id="proveedorId" name="proveedorId">
              <input type="text" id="proveedor" name="proveedor" class="to_uppercase" placeholder="Razón social de proveedor" required>
              <ul id="lista_prov" class="absolute z-40 top-12 bg-grayLight border border-super w-full h-32 overflow-y-scroll"></ul>
            </div>

            <div class="flex flex-col pr-4  w-56">
              <h5 class="text-center uppercase">Contacto Proveedor</h5>

              <select id="contactoId" name="contactoId" required>
                <option value="" disabled selected>---</option>

              </select>
            </div>

            <div class="flex flex-col pr-4 w-32 ">
              <h5 class="text-center uppercase">Tel. Contacto</h5>
              <input id="telefonoContacto" type="text" minlength="10" maxlength="17" readonly>
            </div>

            <div class="flex flex-col pr-4  ">
              <h5 class="text-center uppercase">Fecha Cotización</h5>
              <input id="vigencia" type="date" name="fecha" required>
            </div>

            <div class="flex flex-col pr-4  w-28">
              <h5 class="text-center uppercase">Vigencia</h5>
              <select name="vigencia" id="vigencia" required>
                <option value="" disabled selected>Seleccionar...</option>
                <option value="3 Meses">3 Meses</option>
                <option value="6 Meses">6 Meses</option>
                <option value="1 Año">1 Año</option>
              </select>
            </div>

            <div class="flex flex-col pr-4  w-32">
              <h5 class="text-center uppercase">Origen</h5>
              <select name="origen" id="origen" required>
                <option value="" disabled selected>Seleccionar...</option>
                <option value="NACIONAL">NACIONAL</option>
                <option value="IMPORTADO">IMPORTADO</option>
              </select>
            </div>

            <div class="hidden flex flex-col pr-4  ">
              <h5 class="text-center uppercase">IncorTerms</h5>
              <select name="incoterm" id="incoterm" >
                <option value="" disabled selected>Seleccionar...</option>
                <option value="EXW">EXW: EX Works</option>
                <option value="FCA">FCA: Free CArrier</option>
                <option value="FAS">FAS: Free Alongside Ship</option>
                <option value="FOB">FOB: Free On Board</option>
                <option value="CFR">CFR: Cost and Freight</option>
                <option value="CIF">CIF: Cost, Insurance and Freight</option>
                <option value="CPT">CPT: Carriage Paid To</option>
                <option value="CIP">CIP: Carriage and Insurance Paid to</option>
                <option value="DPU">DPU: Delivery at Place Unloaded</option>
                <option value="DAP">DAP: Delivery At Place</option>
                <option value="DDP">DDP: Delivery Duty Paid</option>
              </select>
            </div>
          </div>

          <div class="flex flex-col w-24 items-center">
            <h5 class="text-center uppercase">Agregar Fila</h5>
            <button id="agregarFila" class=" rounded text-icon border-2 border-icon hover:bg-icon hover:text-white px-1 w-fit" type="button"><i class="fa fa-plus"></i></button>
          </div>
        </div>

        <div class="w-full px-10 flex items-start min-h-80 ">
          <div class="relative w-full overflow-y-scroll h-80">
            <table class="relative top-0 w-full">
              <thead>
                <tr>
                  <th>Nombre del artículo</th>
                  <th>Costo unitario</th>
                  <th>Divisa</th>
                  <th>Impuesto</th>
                  <th>Unidad de Medida</th>
                  <th>Mínimo de compra</th>
                  <th>Importe</th>
                  <th>Tiempo de entrega</th>
                  <th>Coment</th>
                  <th>Eliminar</th>
                </tr>
              </thead>
              <tbody id="items_container">
                <tr>
                  <td><input name="nombreDelArticulo[]" type="text" class="to_uppercase" required placeholder="Nombre del artículo"></td>
                  <td><input name="costoPorUnidad[]" type="number" required step="0.000001" min="0.000001" placeholder="Cantidad" oninput="calcularImporte(this)"></td>
                  <td>
                    <select name="divisa[]" required>
                      <option value="" disabled selected >Seleccionar...</option>
                      <option>USD</option>
                      <option>MXN</option>
                      <option>EUR</option>
                      <option>JPY</option>
                      <option>GBP</option>
                    </select>
                  </td>
                  <td>
                    <select name="impuesto[]" required>
                      <option value="" disabled selected>Seleccionar...</option>
                      <option>16%</option>
                      <option>0%</option>
                      <option>Ex.I</option>
                    </select>
                  </td>
                  <td>
                    <select name="medicion[]" required>
                      <option value="" disabled selected>Seleccionar...</option>
                      <option>KG</option>
                      <option>PZ</option>
                      <option>LT</option>
                      <option>LB</option>
                      <option>GL</option>
                      <option>OZ</option>
                    </select>
                  </td>
                  <td><input name="minimo[]" type="number" required step="0.001" min="0.001" placeholder="Cantidad" oninput="calcularImporte(this)"></td>
                  <td><input name="importe[]" type="number" required step="0.001" min="0.001" placeholder="0.000" readonly></td>

                  <td>
                    <div class="flex space-x-2 items-center">
                      <input type="hidden" name="diasDeEnvio[]" >
                      <input name="cantidadPer[]" type="number" required step="1" min="1" placeholder="Cantidad" >
                      <select name="periodo[]" required>
                        <option value="" disabled selected>Periodo</option>
                        <option value="DIA">Dia(s)</option>
                        <option value="SEMANA">Semana(s)</option>
                        <option value="MES">Mes(es)</option>
                      </select>
                      <select name="tipoDia[]" required onchange="calcularDias(this)">
                        <option value="" disabled selected>Tipo Dias</option>
                        <option value="CALENDARIO">Calendario</option>
                        <option value="HABILES">Habiles</option>
                      </select>
                    </div>
                  </td>

                  <td>
                    <div class="flex items-center justify-center mx-auto ">
                      <button data-modal="modal_comment" class="btn_open_modal rounded text-icon border-2 border-icon hover:bg-icon hover:text-white w-fit" type="button">
                        <i class="fa fa-plus px-1"></i>
                      </button>
                      <input type="hidden" name="comentario[]" class="commentInput">
                    </div>
                  </td>

                  <td>
                    <button class="btn-delete hover:text-red px-2 mx-auto " type="button">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                      </svg>
                    </button>
                  </td>

                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class=" pt-20 pb-12 px-10 w-full flex items-center space-x-12 justify-end  ">
          <p id="files_loaded" class=" text-title text-lg "></p>
          <p id="files_empty" class=" hidden p-2 rounded bg-warning text-white text-lg ">Debe adjuntar minimo 1 archivo.</p>

          <button data-modal="modal_files" class="btn_open_modal flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6 ">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            <span>ADJUNTAR</span>
          </button>

          <button class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6  rotate-180">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            <span>ENVIAR</span>
          </button>
        </div>

        <input type="hidden" name="cotiz_num" value="<?= esc($cotiz_num) ?>">
        <input id="file_input" class="hidden" type="file" name="archivo[]" multiple>

      </form>
    </div>
  </div>

  <!-- Modal Comment -->
  <div id="modal_comment" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
    <div class=" flex flex-col space-y-8 bg-white border-2 border-icon p-10 w-full md:w-[700px]">

      <div class="relative flex w-full justify-center text-center ">
        <h3 class="text-gray text-xl uppercase"> Agregar Comentario</h3>
        <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
      </div>

      <textarea id="modal_textarea" class="p-4 w-full border border-grayMid outline-none resize-none bg-grayLight text-gray drop-shadow " rows="8" placeholder="Escribe tu comentario..."></textarea>

      <div class="flex justify-end space-x-12 text-sm ">
        <button id="btn_save" class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
          GUARDAR
        </button>
      </div>

    </div>
  </div>

  <!-- Modal Files -->
  <div id="modal_files" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
    <div class=" flex flex-col space-y-8 bg-white border-2 border-icon p-10 w-full md:w-[700px]">

      <div class="relative flex w-full justify-center text-center  ">
        <h3 class="text-gray text-xl uppercase"> Adjuntar Archivos</h3>
        <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
      </div>
  
      <div id="dragDropContainer" class="drag-drop-container relative flex flex-col items-center justify-start p-4 w-full border-2 border-dashed border-super bg-grayLight h-72 overflow-y-auto text-super ">
        <span class="absolute top-32 left-0 right-0 text-center">Arrastre y suelte sus archivos para agregarlos.</span>
        <input type="file" name="archivo[]" id="modal_file_input" class="hidden" multiple>
        <ul class="file-list w-full grid grid-cols-5 gap-4 py-4 " id="fileList"></ul>
      </div>

      <div class="flex justify-end space-x-12 text-sm ">
        <button id="btn_open_files" class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
          ABRIR CARPETA
        </button>
        <button id="btn_save_files" class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
          GUARDAR
        </button>
      </div>

    </div>
  </div>

<?php echo view('_partials/_modal_msg'); ?>

<script>
  Service.setLoading();

	function checkOrientation() {
		const overlayOrientation = document.querySelector('#overlayOrientation');

		const isTablet = window.innerWidth >= 600 && window.innerWidth <= 1024; 
		const isPortrait = window.matchMedia("(orientation: portrait)").matches;

		const modal = document.getElementById("orientationModal");

		if (isTablet && isPortrait) {
			console.log('portrait')
			overlayOrientation.classList.remove('hidden');
		} else {
			overlayOrientation.classList.add('hidden');
		}
	}

	// Run the check on page load
	checkOrientation();

	// Listen for orientation changes
	window.addEventListener("resize", checkOrientation);
	window.addEventListener("orientationchange", checkOrientation);



  const modalFileInput = document.getElementById('modal_file_input');
  const fileInput = document.getElementById('file_input');
  const files_loaded = document.getElementById('files_loaded');
  const dragDropContainer = document.getElementById('dragDropContainer');
  const fileList = document.getElementById('fileList');
  const cachedFiles = new DataTransfer();

  const transferFilesToForm = () => {
    if (cachedFiles.files.length === 0) {
      console.log('No files to transfer.');
      return;
    }

    fileInput.files = cachedFiles.files;
    // console.log('Files transferred:', fileInput.files);
  };


  dragDropContainer.addEventListener('dragover', (event) => {
    event.preventDefault();
    dragDropContainer.classList.add('dragover');
  });

  dragDropContainer.addEventListener('dragleave', () => {
    dragDropContainer.classList.remove('dragover');
  });

  dragDropContainer.addEventListener('drop', (event) => {
    event.preventDefault();
    dragDropContainer.classList.remove('dragover');
    handleFiles(event.dataTransfer.files);
  });

  // dragDropContainer.addEventListener('click', () => modalFileInput.click());

  modalFileInput.addEventListener('change', () => handleFiles(modalFileInput.files));

  const btn_open_files = document.getElementById('btn_open_files');
  btn_open_files?.addEventListener('click', () => modalFileInput.click());

  const btn_save_files = document.getElementById('btn_save_files');
  btn_save_files?.addEventListener('click', () => {
    let modal_active = document.querySelector('.modal_active');
    if (modal_active) {
      modal_active.classList.add('hidden');
      modal_active.classList.remove('modal_active');
    }

    const totalFiles = fileList.children.length;
    let files_loaded = document.getElementById('files_loaded');
    files_loaded.textContent = `Archivos adjuntos: ${totalFiles}`;
    let files_empty = document.getElementById('files_empty');
    files_empty.classList.add('hidden');

    transferFilesToForm();
  });

  const trimFileName = (fileName, maxLength = 18) => {
    if (fileName.length <= maxLength) {
        return fileName;
    }
    // const extension = fileName.substring(fileName.lastIndexOf('.'));
    // const baseName = fileName.substring(0, fileName.lastIndexOf('.'));
    // const trimmedBase = baseName.substring(0, maxLength - extension.length - 3);
    // return `${trimmedBase}...${extension}`;

    const extension = fileName.substring(fileName.lastIndexOf('.'));
    const baseName = fileName.substring(0, maxLength - extension.length);
    return `${baseName}...`;
  };

  const removeFileFromCache = (fileName) => {
    const updatedDataTransfer = new DataTransfer();

    // Re-add all files except the one to be deleted
    Array.from(cachedFiles.files).forEach((file) => {
      if (file.name !== fileName) {
        updatedDataTransfer.items.add(file);
      }
    });

    // Replace the cachedFiles object with the updated one
    cachedFiles.items.clear();
    Array.from(updatedDataTransfer.files).forEach((file) => {
      cachedFiles.items.add(file);
    });

    // console.log('File removed from cache:', fileName);
  };


  // Display files in the modal
  const handleFiles = (files) => {
    if (cachedFiles.files.length > 10) {
      alert('You can upload a maximum of 10 files.');
      return;
    }

    dragDropContainer.querySelector('span').innerHTML = '';
    fileList.innerHTML = '';
    Array.from(files).forEach(file => {
      // Add files to cached DataTransfer
      cachedFiles.items.add(file);

      const fileItem = document.createElement('li');
      fileItem.innerHTML = `<span>${(file.size / 1024).toFixed(2)} KB<span> <span>${trimFileName(file.name)}</span>`;

      // Create delete button
      const deleteBtn = document.createElement('button');
      deleteBtn.className = 'delete-btn';
      deleteBtn.innerHTML = '&times;';

      deleteBtn.addEventListener('click', () => {
        removeFileFromCache(file.name);
        fileItem.remove();
      });

      fileItem.appendChild(deleteBtn);
      fileList.appendChild(fileItem);
    });

  };



const contactoId = document.getElementById('contactoId');
console.log(contactoId)
contactoId?.addEventListener('change', e => {
  let selectedOption =  e.target.options[contactoId.selectedIndex];
  telefonoContacto.value = selectedOption.getAttribute('data-telefono');
});


  const getContactos = (proveedorId) => {

    Service.exec('get', `/get_contactos`, { params: { q: proveedorId } })
    .then( r => {

      contactoId.innerHTML = '';
      if (r.length > 0) {

        let opt_sel = document.createElement('option');
        opt_sel.textContent = 'Selec. contacto'; 
        opt_sel.value = '';
        contactoId.appendChild(opt_sel);

        r.forEach(contacto => {
          const opt = document.createElement('option');
          opt.textContent = contacto.nombre; 
          opt.value = contacto.id;
          opt.setAttribute('data-telefono', contacto.telefono)
          contactoId.appendChild(opt);
        });
      } else {
          const opt = document.createElement('option');
          opt.textContent = 'no hay contactos'; 
          opt.value = '';
          contactoId.appendChild(opt);
      }
    });

  }

  const proveedor = document.getElementById('proveedor');
  const proveedorId = document.getElementById('proveedorId');
  const lista_prov = document.getElementById('lista_prov');

  proveedor.addEventListener('keyup', event => {
    const query = event.target.value.trim();

    if (query.length >= 3) {
      Service.exec('get', `/get_proveedor`, { params: { q: query } })
      .then( r => {
        console.log(r)

        // Populate the user list
        lista_prov.innerHTML = ''; // Clear previous results
        if (r.length > 0) {
          r.forEach(prov => {
            const li = document.createElement('li');
            li.textContent = prov.razon_social; 
            li.style.cursor = 'pointer';
            li.addEventListener('click', () => {
              proveedor.value = prov.razon_social; 
              proveedorId.value = prov.id;
              getContactos(prov.id);
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


  const form = document.querySelector('#form_cotizar');
  const files_empty =  document.querySelector('#files_empty');

  form.addEventListener('submit', e => {
    e.preventDefault();
    let btn = e.target.querySelector('button[type="submit"]');

    if(fileInput.files.length > 0) {
      Service.show('.loading');
      btn.disabled = true;
      e.target.submit();
    } else {
      files_empty.classList.remove('hidden');
    }
  });


  const modal_textarea = document.getElementById('modal_textarea');
  const btn_save_comment = document.getElementById('btn_save');
  let currentCommentInput = null; 

  btn_save_comment.addEventListener('click', (e) => {
    if (currentCommentInput) {
      currentCommentInput.value = modal_textarea.value;
      let button = document.querySelector(`.btn_open_modal[data-index="${e.currentTarget.id}"]`);
      if (currentCommentInput.value !== '') {
        button.innerHTML = `<i class="fa px-2">1</i>`;
      } else {
        button.innerHTML = `<i class="fa fa-plus px-1"></i>`;
      }
      
    }
    modal_comment.classList.add('hidden');
    modal_comment.classList.remove('modal_active');
  });


  window.addEventListener('click', (event) => {
    let modal_active = document.querySelector('.modal_active');
    if (event.target === modal_active) {
      modal_active.classList.remove('modal_active');
      modal_active.classList.add('hidden');
      // console.log(modal_active)
    }
  });


  const incoterm = document.querySelector('#incoterm');
  const origen = document.querySelector('#origen');
  origen?.addEventListener('change', e => {
    if (e.target.value == 'IMPORTADO') {
      incoterm.required = true;
      incoterm.parentNode.classList.remove('hidden');
    } else {
      incoterm.required = false;
      incoterm.parentNode.classList.add('hidden');
    }
  });


  const initRowBtn = () => {
    const allInputToUpper = document.querySelectorAll('.to_uppercase');
    allInputToUpper?.forEach( input => {
      input.addEventListener('input', e => {
        input.value = e.target.value.toUpperCase();
      });
    });

    const allBtnOpen = document.querySelectorAll('.btn_open_modal');
    allBtnOpen?.forEach( (btn, index) => {

      btn.addEventListener('click', e => {
				e.stopPropagation()
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


  const btnAddRow = document.getElementById('agregarFila');
  btnAddRow?.addEventListener('click', () => {
    const nuevaFila = 
    `
    <tr>
      <td><input name="nombreDelArticulo[]" type="text" class="to_uppercase" required placeholder="Nombre del artículo"></td>
      <td><input name="costoPorUnidad[]" type="number" required step="0.000001" min="0.000001" placeholder="Cantidad" oninput="calcularImporte(this)"></td>
      <td>
        <select name="divisa[]" required>
          <option value="" disabled selected >Seleccionar...</option>
          <option>USD</option>
          <option>MXN</option>
          <option>EUR</option>
          <option>JPY</option>
          <option>GBP</option>
        </select>
      </td>

      <td>
        <select name="impuesto[]" required>
          <option value="" disabled selected>Seleccionar...</option>
          <option>16%</option>
          <option>0%</option>
          <option>Ex.I</option>
        </select>
      </td>
      <td>
        <select name="medicion[]" required>
          <option value="" disabled selected>Seleccionar...</option>
          <option>KG</option>
          <option>PZ</option>
          <option>LT</option>
          <option>LB</option>
          <option>GL</option>
          <option>OZ</option>
        </select>
      </td>
      <td><input name="minimo[]" type="number" required step="0.001" min="0.001" placeholder="Cantidad" oninput="calcularImporte(this)"></td>
      <td><input name="importe[]" type="number" required step="0.001" min="0.001" placeholder="0.00" readonly></td>

      <td>
        <div class="flex space-x-2 items-center">
          <input type="hidden" name="diasDeEnvio[]" >
          <input name="cantidadPer[]" type="number" required step="1" min="1" placeholder="Cantidad" >
          <select name="periodo[]" required>
            <option value="" disabled selected>Periodo</option>
            <option value="DIA">Dia(s)</option>
            <option value="SEMANA">Semana(s)</option>
            <option value="MES">Mes(es)</option>
          </select>

          <select name="tipoDia[]" required onchange="calcularDias(this)">
            <option value="" disabled selected>Tipo Dias</option>
            <option value="CALENDARIO">Calendario</option>
            <option value="HABILES">Habiles</option>
          </select>

        </div>
      </td>

      <td>
        <div class="flex items-center justify-center mx-auto">
          <button data-modal="modal_comment" class="btn_open_modal rounded text-icon border-2 border-icon hover:bg-icon hover:text-white w-fit " type="button"><i class="fa fa-plus px-1"></i></button>
          <input type="hidden" name="comentario[]" class="commentInput">
        </div>
      </td>

      <td>
        <button class="btn-delete text-gray hover:text-red px-2 mx-auto" type="button">${getIcon('delete')}</button>
      </td>

    </tr>
    `;
    document.getElementById('items_container').insertAdjacentHTML('beforeend', nuevaFila);
    initRowBtn();
  });

  function calcularImporte(input) {
    const fila = input.closest('tr');
    const costoPorUnidad = fila.querySelector('[name="costoPorUnidad[]"]').value;
    const minimo = fila.querySelector('[name="minimo[]"]').value;
    const importe = fila.querySelector('[name="importe[]"]');
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

</script>
</body>
</html>