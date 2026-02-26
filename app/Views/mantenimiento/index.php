<?php echo view('_partials/header'); ?>

  <link rel="stylesheet" href="<?= base_url('_partials/mant.css') ?>">

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

      <?php echo view('mantenimiento/_partials/navbar'); ?>

      <a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer">Cerrar Sesión</a>

      <div class="text-title w-full md:pt-4 md:pb-8 md:px-16 p-2 flex items-center ">
        <h2 class="text-center font-bold w-full text-2xl lg:text-3xl "><?= esc($title) ?></h2>
        <a href="<?= base_url('apps') ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
      </div>

    <?php  
      echo "<pre >";
      // print_r($user_id);
      // print_r($_SESSION);
      echo "</pre>";
     ?>

      <div class="w-full text-sm text-gray p-4 md:py-4 md:px-6 xl:px-8 xl:py-0  ">
        <form id="form_mant" class="flex flex-col h-full " method="post" enctype='multipart/form-data'>
          <?= csrf_field() ?>

          <div class="flex flex-col lg:border-icon lg:border-2 lg:shadow-bottom-right ">
            <div class="p-4 lg:px-10 lg:py-6 flex flex-col space-y-4 lg:flex-row lg:space-y-0 w-full items-center justify-center ">

              <div class="flex w-full items-center text-lg lg:text-xl text-title justify-center text-center  ">
                <h3>El equipo de mantenimiento recibirá tu solicitud para atenderla a la brevedad.</h3>
              </div>

              <div class="flex w-full lg:w-64 items-center justify-center lg:justify-end ">
                <div class="w-fit items-center flex flex-col "> 
                  <span class=" text-gray uppercase">Fecha</span>
                  <span class="text-white  py-1 px-4 bg-icon uppercase"><?php echo date('d-m-Y'); ?></span> 
                </div>
              </div>

            </div>

            <div class=" grid md:grid-cols-3 gap-x-4 gap-y-8 place-items-center lg:flex lg:justify-center w-full pt-8 lg:pt-2 ">
              
              <div class="relative flex flex-col w-3/4 md:w-56 ">
                <h5 class="text-center uppercase">Solicitante</h5>
								<input type="text" class="input_mant" id="solicitante" name="solicitante" value="<?= session()->get('user')['name'] . ' ' . session()->get('user')['last_name'] ?>" readonly>
                <!-- <input type="text" id="solicitante" name="solicitante" class="to_uppercase" placeholder="Nombre de solicitante" required>
								<ul id="lista_users" class="absolute z-40 top-12 bg-grayLight border border-super w-full h-32 overflow-y-scroll"></ul> -->
              </div>

							<div class="flex flex-col w-3/4 md:w-56 ">
								<h5 class="text-center uppercase">Planta</h5>
								<select id="planta" name="planta" >
									<option value="" disabled selected>Seleccionar...</option>
									<?php foreach ($plantas as $planta): ?>
										<option value="<?= $planta['planta'] ?>"><?= $planta['planta'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>


              <div class="flex flex-col w-3/4 md:w-56">
                <h5 class="text-center uppercase">Linea</h5>
								<select id="linea" name="linea" >
									<option value="" disabled selected>Seleccionar...</option>
								</select>
              </div>

              <!-- <div class="relative flex flex-col w-3/4 md:w-56">
                <h5 class="text-center uppercase">Máquina</h5>
                <input type="hidden" id="maqId" name="maqId">
                <input type="text" id="maquina" name="maquina" placeholder="Nombre de máquina" required>
                <ul id="lista_maq" class="absolute z-40 top-12 bg-grayLight border border-super w-full h-32 overflow-y-scroll"></ul>
              </div> -->

							<div class="flex flex-col w-3/4 md:w-56">
                <h5 class="text-center uppercase">Máquina</h5>
								<select id="maquina" name="maqId" >
									<option value="" disabled selected>Seleccionar...</option>
								</select>
              </div>

              <div class="flex flex-col w-3/4 md:w-56">
                <h5 class="text-center uppercase">Prioridad</h5>
                <select name="prioridad" id="prioridad" required>
                  <option value="" disabled selected>Seleccionar...</option>
                  <option value="BAJA">BAJA (OTM)</option>
                  <!-- <option value="MEDIA">MEDIA</option> -->
                  <option value="ALTA">ALTA (FIS)</option>
                </select>
              </div>

              <div class="flex flex-col w-3/4 md:w-56">
                <h5 class="text-center uppercase">Estado</h5>
                <select name="estado_maq" id="estado_maq" required>
                  <option value="" disabled selected>Seleccionar...</option>
                  <option value="FUNCIONAL">FUNCIONAL</option>
                  <option value="PARCIAL">PARCIAL</option>
                  <option value="NO FUNCIONAL">NO FUNCIONAL</option>
                </select>
              </div>

            </div>

            <div class="  py-6 md:px-10 md:py-6 flex flex-col space-y-4 w-full items-center justify-center ">

              <div class="flex flex-col w-full items-start space-y-2  ">
                <h5 class="text-center uppercase">Asunto</h5>
                <input type="text" name="asunto" class="px-4 py-2 w-full border border-grayMid bg-grayLight outline-none text-gray drop-shadow  " required placeholder="Escribe aquí un breve asunto...">
              </div>

              <div class="flex flex-col w-full items-start space-y-2  ">
                <h5 class="text-center uppercase">Descripción</h5>
                <textarea name="descripcion" class="p-4 w-full border border-grayMid outline-none resize-none bg-grayLight text-gray drop-shadow " rows="4" required placeholder="Escribe una descripción más detallada..."></textarea>
              </div>

            </div>

          </div>


          <div class="md:px-10 lg:pt-8 w-full flex items-center justify-end ">
            <p>Por favor anexar evidencia fotográfica siempre que sea posible. La evidencia es importante para que el equipo de mantenimiento tenga mas conocimiento sobre el problema.</p>
          </div>


          <div class=" py-10 w-full flex flex-col space-y-8 lg:flex-row items-center lg:space-x-12 lg:space-y-0 justify-end  ">

            <p id="files_empty" class=" hidden p-2 rounded bg-warning text-white ">Debe adjuntar minimo 1 archivo.</p>


            <button id="btn_open_camera" data-modal="modal_files" class="btn_open_modal w-64 lg:w-fit flex  items-center justify-center space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 " type="button" >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
              </svg>

              <span>ADJUNTAR EVIDENCIA</span>
            </button>

            <button class=" w-64 lg:w-fit  flex justify-center items-center  space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 " type="submit">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6  rotate-180">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
              </svg>
              <span>ENVIAR</span>
            </button>

						<p id="files_loaded" class="hidden w-3/4 md:w-1/2 lg:w-72 p-2 bg-title text-white text-center text-lg "></p>

          </div>

					<input type="file" id="cameraInput" name="archivo" accept="image/*" capture="environment" class="hidden">

        </form>
      </div>


    </div>


  <!-- Modal Files -->
  <div id="modal_files" class="hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
    <div class=" flex flex-col space-y-8 mx-4 bg-white border-2 border-icon p-8 w-full  lg:max-w-2xl h-[85%]">

      <div class="relative flex w-full justify-center text-center pt-16 lg:pt-8 ">
        <h3 class="text-gray text-xl uppercase"> Fotografía Seleccionada</h3>
      </div>
  
			<img id="photoPreview" src="" alt="Captured Photo" class="flex justify-center items-center mx-auto w-[75%] h-[75%] border-title border-2 rounded">

      <div class="flex w-full md:w-3/4 mx-auto justify-between text-sm ">
        <button id="btn_retry" class=" flex space-x-4 shadow-bottom-right text-error border-2 border-error hover:bg-error hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
          REINTENTAR
        </button>
        <button id="btn_save_files" class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
          GUARDAR
        </button>
      </div>

    </div>
  </div>


<?php echo view('_partials/_modal_msg_tablet'); ?>

<script>
  Service.setLoading();

const files_loaded = document.querySelector('#files_loaded');
const files_empty =  document.querySelector('#files_empty');

	const previewPhoto = (e) => {
		const file = e.target.files[0];
		if (file) {
			const reader = new FileReader();
			reader.onload = function(event) {
				document.getElementById('photoPreview').src = event.target.result;
			};
			reader.readAsDataURL(file);
		}
	}

	const cameraInput = document.querySelector('#cameraInput');
	cameraInput?.addEventListener('change', previewPhoto);

	const btn_open_camera = document.querySelector('#btn_open_camera');
	btn_open_camera?.addEventListener('click', () => {
		document.getElementById('photoPreview').src = "<?= base_url('img/no_img_alt.png') ?>"
		cameraInput.value = ''; 
		cameraInput.click();
	});

	const btn_retry = document.querySelector('#btn_retry');
	btn_retry?.addEventListener('click', () => {
		cameraInput.value = ''; 
		cameraInput.click();
	});



  const btn_save_files = document.getElementById('btn_save_files');
  btn_save_files?.addEventListener('click', () => {
    let modal_active = document.querySelector('.modal_active');
    if (modal_active) {
      modal_active.classList.add('hidden');
      modal_active.classList.remove('modal_active');
    }

    let totalFiles = cameraInput.files.length;
    files_loaded.textContent = `${totalFiles} Archivos adjuntos`;
    files_loaded.classList.remove('hidden');
    files_empty.classList.add('hidden');
  });


const allInputToUpper = document.querySelectorAll('.to_uppercase');
allInputToUpper?.forEach( input => {
  input.addEventListener('input', e => {
    input.value = e.target.value.toUpperCase();
  });
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
    document.body.classList.remove('no-scroll');
    overlay.style.display = 'none';
  }
});




  const maquina = document.getElementById('maquina');
  const planta = document.getElementById('planta');
  const linea = document.getElementById('linea');



	planta?.addEventListener('change', e => {
    const planta = e.target.value.trim();

		Service.exec('get', `/get_lineas/${planta}`)
		// Service.exec('get', `/get_lineas_alt`)
		.then( r => {
			// console.log(r); return;
			resetSelect('linea'); 
			resetSelect('maquina'); 
			if (r.length > 0) {
				r.forEach(line => {
					let opt = document.createElement('option');
					opt.value = line.linea;
					opt.textContent = `${line.linea}`;
					linea.appendChild(opt);
				});

			}
		});

  });

	linea?.addEventListener('change', e => {
    const _linea = e.target.value.trim();
		const _planta = planta.value.trim();

		Service.exec('get', `/get_maq_linea/${_planta}/${_linea}`)
		.then( r => {
			// console.log(r); return;
			resetSelect('maquina'); 
			if (r.length > 0) {
				r.forEach(maq => {
					let opt = document.createElement('option');
					opt.value = maq.id;
					opt.textContent = `${maq.nombre}`;
					maquina.appendChild(opt);
				});

			}
		});

  });

  // const solicitante = document.getElementById('solicitante');
  // const lista_users = document.getElementById('lista_users');

  // solicitante?.addEventListener('keyup', e => {
  //   const nombre = e.target.value.trim();

  //   if (nombre.length >= 2) {

	// 		Service.exec('get', `/get_empleados/${nombre}`)
	// 		.then( r => {

	// 			lista_users.innerHTML = ''; 
	// 			if (r.length > 0) {
	// 				r.forEach(user => {
	// 					let name = `${user.name} ${user.last_name}`;
  //           const li = document.createElement('li');
  //           li.textContent = name;
  //           li.style.cursor = 'pointer'; 
  //           li.addEventListener('click', () => {
  //             solicitante.value = name; 
  //             lista_users.style.display = 'none';
  //           });
  //           lista_users.appendChild(li);
  //         });
  //         lista_users.style.display = 'block';
  //       } else {
  //         lista_users.style.display = 'none';
  //       }
	// 		});

  //   } else {
  //     lista_users.style.display = 'none';
  //   }
  // });

  // document.addEventListener('click', (e) => {
  //   if (!lista_users.contains(e.target) && e.target !== solicitante) {
  //     lista_users.style.display = 'none';
  //   }
  // });

  const form_mant = document.querySelector('#form_mant');
  form_mant.addEventListener('submit', e => {
    e.preventDefault();
		Service.stopSubmit(e.target, true);

    if(cameraInput.files.length > 0) {
			Service.show('.loading');
			Service.stopSubmit(e.target, false);
      e.target.submit();
    } else {
      files_empty.classList.remove('hidden');
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

  }

  initRowBtn();


</script>
</body>
</html>