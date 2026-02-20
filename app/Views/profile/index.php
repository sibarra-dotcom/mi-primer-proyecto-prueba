<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>


  <title><?= esc($title) ?></title>
</head>

<body>
<div id="overlay"></div>  
  <div id="loadingOverlay"><div id="loadingSpinner"></div></div>  


<div class=" relative flex flex-col items-center justify-center font-titil ">
  <img src="<?= base_url('img/perfil.svg') ?>" class="flex absolute bottom-2 right-8 -z-10 opacity-90 w-5/6 h-3/4 ">  

  <?php echo view('dashboard/_partials/navbar'); ?>

	<div class=" relative flex flex-col gap-y-10 items-center justify-center font-titil ">

		<div class=" text-title w-full md:py-4 md:px-16 p-2 flex items-center ">
			<h2 class="text-center font-bold w-full text-3xl "><?= esc($title) ?></h2>
			<a href="<?= base_url('dashboard') ?>" class="self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
		</div>

		<div class=" flex flex-col items-center justify-center gap-y-8 ">
			<div class="w-48 h-48 rounded-full overflow-hidden border-8 border-title ">
				<button id="btn_open_camera" data-modal="modal_picture" class="btn_open_modal" type="button">
					<img src="<?= !empty($picture) ? base_url("/files/download?path=" . urlencode($picture)) : base_url('img/user_default.png') ?>" class="w-48 h-48 ">  
				<button>
			</div>
		</div>

		<div class="mb-4  flex flex-col items-center justify-center gap-y-2 ">
			<h2 class="text-3xl text-title font-bold "><?= session()->get('user')['name'] . " " . session()->get('user')['last_name']?></h2>
			<h2 class="text-xl text-gray font-bold "><?= session()->get('user')['email']?></h2>
		</div>

		<div class="mb-4 flex items-center justify-center ">
			<a href="<?= base_url('profile/change_password') ?>"  class="w-full flex items-center justify-center space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 uppercase">Cambiar Contraseña</a>
		</div>

		<div class="mb-12 w-full relative flex flex-col gap-y-6 items-center justify-center font-titil ">
			<h2 class=" text-4xl text-title font-bold ">Completa tu Perfil</h2>
			<div class=" w-full relative flex flex-col gap-y-4 items-center justify-center font-titil ">
				<h2 class="text-2xl text-gray font-bold "><?= $profile_complete ?> %</h2>
				<?= renderProgressBar($profile_complete) ?>
				<p class="w-2/3">Crea tu firma digital para completar tu perfil. Esta firma te servirá para firmar documentos bajo tu nombre con validez oficial.</p>
			</div>
		</div>

		<?php if (empty($signature)) :  ?>
		<div class="flex items-center justify-center mb-12 ">
			<a href="<?= base_url('profile/signature') ?>"  class="w-full flex items-center justify-center space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 uppercase">Crear Firma Digital</a>
		</div>
		<?php endif; ?>

  </div>

	<!-- Modal Picture -->
	<div id="modal_picture" class="hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
    <div class=" flex flex-col space-y-8 mx-4 bg-white border-2 border-icon p-8 w-full  lg:max-w-2xl h-[85%]">

      <div class="relative flex w-full justify-center text-center pt-16 lg:pt-8 ">
        <h3 class="text-gray text-xl uppercase"> Fotografía Seleccionada</h3>
				<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
      </div>
  
			<img id="photoPreview" src="" alt="Captured Photo" class="flex justify-center items-center mx-auto w-[75%] h-[75%] border-title border-2 rounded">

      <form id="form_picture" method="post" enctype='multipart/form-data' class="flex w-full md:w-3/4 mx-auto justify-between text-sm ">
        <button id="btn_retry" class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
          SELECCIONAR ARCHIVO
        </button>
        <button class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit">
          GUARDAR
        </button>
				<input type="file" id="cameraInput" name="archivo" accept="image/*"  class="hidden">
				<?= csrf_field() ?>

      </form>

    </div>
  </div>


</div>

<?php echo view('_partials/_modal_msg_tablet'); ?>

<script>
 Service.setLoading();
 
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



  const form_picture = document.getElementById('form_picture');
  form_picture?.addEventListener('submit', (e) => {
		e.preventDefault;
		e.target.submit();
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
			// form_ticket.reset();
			document.body.classList.remove('no-scroll');
			overlay.style.display = 'none';
		}
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

