<?php echo view('home/_partials/header'); ?>

<body class="h-screen p-4 md:p-0 bg-user flex items-center justify-center relative font-noto">

  <!-- Container with two columns -->
  <div class="flex flex-col md:flex-row bg-white w-full h-3/4 md:w-10/12 lg:w-1/2 shadow-lg ">
    <!-- Left Column for Image -->
    <div class="hidden md:flex p-2 w-full md:w-1/2 items-center justify-center">
      <img id="img_left" src="<?= base_url('img/login_user.svg') ?>" alt="Login Image" class=" max-w-full h-64 rounded-md">
    </div>

    <!-- Right Column for Login Form -->
    <div class=" flex flex-col p-2 w-full md:w-1/2  justify-center ">

      <form id="form_login" action="<?= base_url('auth/signin') ?>" class=" h-5/6 p-4 md:px-8 md:border-l md:border-grayMid " method="post">
        <?= csrf_field() ?>

        <img src="<?= base_url('img/logo.jpeg') ?>" alt="Login Image" class="mx-auto max-w-16 h-16 rounded-full mb-8">

        <?php if (session()->getFlashdata('error')) : ?>
        <div class="text-red mb-2 text-sm mx-auto text-center">
          <?= session()->getFlashdata('error') ?>
        </div>
        <?php endif; ?>

        <div id="form_title" class="flex items-center justify-center mb-6">
          <p class="text-sm text-gray">Inicia sesión para acceder a portal Gibanibb</p>
        </div>

        <div class="mb-6">
          <input type="text" id="email" name="email" placeholder="Correo" required class="input_email">
        </div>

        <div class="mb-6 relative">
          <input type="password" id="password" name="password" placeholder="Contraseña" required class="input_text">
          <div class="btn_eye absolute top-1 right-4 text-ctaDark text-xl cursor-pointer"><i class="fas fa-eye"></i></div>
        </div>

				<div class="mb-3 flex flex-col justify-center items-center ">
					<span class="text-title text-sm">Ingresa tu PIN</span>
					<button data-modal="modal_pin" class="btn_open_modal" type="button">
						<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="0.55" stroke="currentColor" class="h-20 w-20 stroke-title text-title   transition duration-300  ">
							<path stroke-linecap="round" stroke-linejoin="round" d="M 5.07,16.93 V 15.13 H 6.92 8.77 V 16.93 18.74 H 6.92 5.07 Z M 10,16.93 V 15.13 H 11.85 13.7 V 16.93 18.74 H 11.85 10 Z M 14.93,16.93 V 15.13 H 16.78 18.63 V 16.93 18.74 H 16.78 14.93 Z M 5.07,11.92 V 10.11 H 6.92 8.77 V 11.92 13.72 H 6.92 5.07 Z M 10,11.92 V 10.11 H 11.85 13.7 V 11.92 13.72 H 11.85 10 Z M 14.93,11.92 V 10.11 H 16.78 18.63 V 11.92 13.72 H 16.78 14.93 Z M 5.07,6.9 V 5.1 H 6.92 8.77 V 6.9 8.71 H 6.92 5.07 Z M 10,6.9 V 5.1 H 11.85 13.7 V 6.9 8.71 H 11.85 10 Z M 14.93,6.9 V 5.1 H 16.78 18.63 V 6.9 8.71 H 16.78 14.93 Z" />
						</svg>
					</button>
				</div>

        <div class="flex items-center justify-center mb-12">
          <button type="submit" class="w-full flex items-center justify-center space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 uppercase">Ingresar</button>
        </div>

        <div id="form_forgot" class="flex items-center justify-center">
          <a href="<?= base_url('auth/forgot') ?>" class="text-xs text-user">¿Olvidaste tu contraseña?</a>
        </div>

      </form>
    </div>
  </div>

<!-- Modal Pin -->
<div id="modal_pin" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
	<div class=" flex flex-col space-y-8 bg-white border-2 border-icon p-10 w-full md:w-[600px]">

		<div class="relative flex w-full justify-center text-center ">
			<h3 class="text-gray text-xl uppercase">Ingresar PIN</h3>
			<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
		</div>

		<form id="form_login_pin" action="<?= base_url('auth/signin') ?>" method="post" class="bg-white p-6 flex flex-col gap-y-8 text-center w-full">

			<input id="pinInput" type="text" name="pin" class="w-full p-3 border rounded text-center text-2xl" readonly>
			<input id="emailInput" type="hidden" name="email">

			<div class="grid grid-cols-3 gap-4 ">
				<button type="button" class="pin-btn">1</button>
				<button type="button" class="pin-btn">2</button>
				<button type="button" class="pin-btn">3</button>
				<button type="button" class="pin-btn">4</button>
				<button type="button" class="pin-btn">5</button>
				<button type="button" class="pin-btn">6</button>
				<button type="button" class="pin-btn">7</button>
				<button type="button" class="pin-btn">8</button>
				<button type="button" class="pin-btn">9</button>
				<div></div>
				<button type="button" class="pin-btn col-span-1">0</button>
				<div></div>
			</div>

			<div class="flex w-full justify-between text-sm">
				<button id="clearPin" class=" flex space-x-4 shadow-bottom-right text-error border-2 border-error hover:bg-error hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >Limpiar</button>
				<button class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="submit">Ingresar</button>
			</div>
			<?= csrf_field() ?>
		</div>

	</form>
</div>

<!-- Button to change background color -->
<button id="btn_bg" class="absolute outline-none top-4 right-4 p-4 text-white hover:text-neutral text-2xl"> <i class="fas fa-gear"></i> </button>

<script>

	document.getElementById("form_login_pin").addEventListener("submit", function() {
		document.getElementById("emailInput").value += document.querySelector("#form_login #email").value;
	});

	document.getElementById("clearPin").addEventListener("click", function() {
		document.getElementById("pinInput").value = "";
	});

	document.querySelectorAll(".pin-btn").forEach(button => {
		button.addEventListener("click", function() {
			document.getElementById("pinInput").value += this.textContent;
		});
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


  const btn_bg = document.querySelector('#btn_bg');
  btn_bg?.addEventListener('click', (e) => {
    const i = e.currentTarget.querySelector('i');

    const body = document.querySelector('body');
    const form_title = document.querySelector('#form_title');
    const form_forgot = document.querySelector('#form_forgot');
    const img_left = document.querySelector('#img_left');

    if (body.classList.contains('bg-user')) {

      form_title.classList.add('hidden');
      form_forgot.classList.add('hidden');
      body.classList.remove('bg-user');
      body.classList.add('bg-super');
      i.classList.remove('fa-gear');
      i.classList.add('fa-arrow-turn-up');
      i.classList.add('fa-rotate-270');
      img_left.src = "<?= base_url('img/login_admin.svg') ?>";

    } else {
      form_title.classList.remove('hidden');
      form_forgot.classList.remove('hidden');

      body.classList.remove('bg-super');
      body.classList.add('bg-user');
      i.classList.remove('fa-arrow-turn-up');
      i.classList.remove('fa-rotate-270');
      i.classList.add('fa-gear');
      img_left.src = "<?= base_url('img/login_user.svg') ?>";
    }

  });

</script>
</body>
</html>
