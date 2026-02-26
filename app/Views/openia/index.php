<?php echo view('_partials/header'); ?>

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

		<?php echo view('dashboard/_partials/navbar'); ?>


    <a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer">Cerrar Sesión</a>

    <div class="text-title w-full md:pt-4 md:pb-8 md:px-16 p-2 flex items-center ">
      <h2 class="text-center font-bold w-full text-2xl lg:text-3xl "><?= esc($title) ?></h2>
      <a href="<?= base_url('apps') ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>

    <div class="w-full text-sm text-gray  ">
      <div class="flex flex-col h-full" >

        <form id="form_openia" class="pb-8 px-10 flex flex-col gap-y-8 w-full xl:w-1/2  mx-auto  items-center " method="post">

					<textarea name="question" class="p-4 w-full border border-grayMid outline-none  bg-grayLight text-gray drop-shadow " rows="4" placeholder="Escribe tu consulta"></textarea>

					<div id="respuesta" class="whitespace-pre-line h-72 overflow-y-scroll p-4 w-full focus:border focus:border-grayMid outline-none bg-grayLight text-gray " ></div>

					<button id="btn_search" type="submit"><span>Consultar</span></button>

				</form>

      </div>
    </div>
  </div>


<script>
				
	const typeResponse = (containerId, text, speed = 30) => {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = '';
    let index = 0;

    // Replace escaped \\n to real \n first
    text = text.replace(/\\n/g, '\n');

    const typeNextChar = () => {
        if (index < text.length) {
            const char = text[index++];

            if (char === '\n') {
                container.innerHTML += '<br>';
            } else {
                container.innerHTML += char;
            }

            container.scrollTop = container.scrollHeight;
            setTimeout(typeNextChar, speed);
        }
    };

    typeNextChar();
};



	const form_openia = document.querySelector('#form_openia');
  form_openia?.addEventListener('submit', e => {
    e.preventDefault();
    Service.stopSubmit(e.target, true);

    document.getElementById('respuesta').innerHTML = Service.loader();

    const formData = new FormData(e.target);

    Service.exec('post', `/openia`, formData_header, formData)
    .then(r => {

			typeResponse('respuesta', r.answer, 24);

      Service.stopSubmit(e.target, false);
    });  
  });



</script>
</body>
</html>

