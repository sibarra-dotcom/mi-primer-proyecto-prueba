<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/signature_pad@5.0.7/dist/signature_pad.umd.min.js"></script>

  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body>
	<div id="overlay"></div>  
  <div id="loadingOverlay"><div id="loadingSpinner"></div></div>  

<div class="relative flex flex-col items-center justify-center font-titil ">

	<img src="<?= base_url('img/perfil.svg') ?>" class="flex absolute bottom-2 right-8 -z-10 opacity-90 w-5/6 h-3/4 ">  

  <?php echo view('dashboard/_partials/navbar'); ?>

  <a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer "> Cerrar Sesion </a>

  <div class=" my-6 text-title w-full md:py-4 md:px-16 p-2 flex items-center ">
    <h2 class="text-center font-bold w-full text-3xl "><?= esc($title) ?></h2>
    <a href="<?= base_url('profile') ?>" class="self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
  </div>


	<div class="mb-10  flex flex-col items-center justify-center gap-y-2 ">
		<h2 class="text-3xl text-title font-bold "><?= session()->get('user')['name'] . " " . session()->get('user')['last_name']?></h2>
		<h2 class="text-xl text-gray font-bold "><?= session()->get('user')['email']?></h2>
	</div>


	<form id="form_pass" method="post" class="mb-4 w-full md:w-2/3 p-6 flex flex-col items-center justify-center gap-y-6  ">
		<h2 class="text-xl text-gray font-bold ">Ingresa Nueva Contraseña</h2>
		<?= csrf_field() ?>
    
		<div class="mb-6 relative w-full">
			<input type="password" id="password" name="password" placeholder="Contraseña" required class=" h-10 px-4 py-2  w-full  bg-super bg-opacity-10 focus:bg-opacity-20 focus:outline-none ">
			<div class="btn_eye absolute top-2 right-4  text-ctaDark text-xl cursor-pointer"><i class="fas fa-eye"></i></div>
		</div>

		<div class="flex w-full items-center justify-center text-sm ">

			<button  type="submit" class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-2/3 items-center text-center justify-center " type="button">
				GUARDAR
			</button>
		</div>

	</form>


</div>

<?php echo view('_partials/_modal_msg_tablet'); ?>


<script>

window.addEventListener('DOMContentLoaded', ()=> {
    let password = document.querySelector('input[name="password"]')

    let btn_show = document.querySelector('.btn_eye')
    btn_show?.addEventListener('click', e => {

      let icon = e.currentTarget.querySelector('i');

      if (icon.classList.contains('fa-eye')) {
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }

      if (password.type === "password") {
        password.type = "text";
      } else {
        password.type = "password";
      }
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
			// form_ticket.reset();
			document.body.classList.remove('no-scroll');
			overlay.style.display = 'none';
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

          modal_active.classList.add('hidden');
          modal_active.classList.remove('modal_active');
        }
      });
    });

  }

  initRowBtn();



const form_pass = document.querySelector('#form_pass');
form_pass?.addEventListener('submit', e => {
	e.preventDefault();
	let btn = e.target.querySelector('button[type="submit"]');
	let pass = e.target.querySelector('#password');

	document.querySelector('#loadingOverlay').style.display = 'block';
	btn.disabled = true;

	if (pass.value.length > 5) {

		e.target.submit();
	} else {
		btn.disabled = false;
	}
});



</script>
</body>
</html>

