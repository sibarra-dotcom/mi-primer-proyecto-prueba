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
<div class=" relative flex flex-col items-center justify-center font-titil ">
	<img src="<?= base_url('img/laboratorio.svg') ?>" class="hidden lg:flex absolute -z-10 -bottom-16 left-0 opacity-80 ">

	<img src="<?= base_url('img/laboratorio.svg') ?>" class="flex lg:hidden absolute -z-10 -bottom-16 left-0 opacity-80 ">
	
	<?php echo view('dashboard/_partials/navbar'); ?>

	<a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer "> Cerrar Sesion </a>

	<div class=" mb-2 text-title w-full md:py-4 md:px-16 p-2 flex items-center ">
		<h2 class="text-center font-bold w-full text-3xl "><?= esc($title) ?></h2>
		<a href="<?= base_url('inspeccion') ?>" class="self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
	</div>

  <div class="min-h-[70vh] flex items-start justify-center z-30 p-8 lg:px-24 w-full lg:w-3/4 ">

		<div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-16 ">
			
			<a href="<?= base_url('inspeccion/create/materias-primas') ?>" class="dashboard_link w-72 lg:w-96 w-full h-24 cursor-pointer shadow-bottom-right  ">
				<div class=" flex space-x-4 w-full items-center justify-center lg:justify-start px-8 h-full hover:text-white text-title">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="h-16 w-16 stroke-title transition duration-200">
					<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
				</svg>

					<span class="text-xl uppercase ">Iniciar desde Formato</span>
				</div>
			</a>

			<a href="<?= base_url('inspeccion/lista/materias-primas') ?>" class="dashboard_link w-72 lg:w-96 w-full h-24 cursor-pointer shadow-bottom-right  ">
				<div class=" flex space-x-4 w-full items-center justify-center lg:justify-start px-8 h-full hover:text-white text-title">

					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="h-16 w-16 transition duration-100 ">
						<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
					</svg>
					<span class="text-xl uppercase ">Ver Lista Inspecciones</span>
				</div>
			</a>

			<!-- <button data-input="cameraInput" data-img="img_certif" data-modal="modal_files" class="btn_open_modal btn_open_camera dashboard_link w-72 lg:w-96 w-full h-24 cursor-pointer shadow-bottom-right  " type="button">
				<div class=" flex space-x-4 w-full items-center justify-center lg:justify-start px-8 h-full ">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="h-16 w-16 stroke-title text-title hover:fill-none transition duration-300">
						<path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
						<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
					</svg>
					<span class="text-xl uppercase ">Iniciar desde Certificado</span>
				</div>
			</button> -->


		</div>
	</div>

</div>



<!-- Modal Certificado -->
<div id="modal_files" class="hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
	<div class=" flex flex-col gap-y-6 mx-4 bg-white border-2 border-icon p-6 w-full lg:max-w-2xl h-[90%]">

		<div class="relative flex w-full justify-center text-center ">
			<h3 class="text-gray text-xl uppercase">Certificado</h3>
			<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
		</div>

		<img id="img_certif" alt="Captured Photo" class="flex justify-center items-center mx-auto w-full h-[82%] border-title border-2 rounded">

		<div class="flex w-full  mx-auto justify-between text-sm ">
			<button data-input="cameraInput" class=" btn_retry flex space-x-4 shadow-bottom-right text-error border-2 border-error hover:bg-error hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
				REINTENTAR
			</button>
			<button data-img="img_certif" class=" btn_save_file lex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
				GUARDAR
			</button>
		</div>

	</div>

	<input type="file" data-img="img_certif" id="cameraInput" accept="image/*" capture="environment" class="hidden">
</div>


<script>

const allIcons = document.querySelectorAll('.icon_svg');
allIcons?.forEach( icon => {
let file = icon.getAttribute('data-file');

icon.addEventListener('mouseover', () => {
		icon.src = `${root}/img/${file}hover.svg`;
		console.log(icon.src)
});

icon.addEventListener('mouseout', () => {
		icon.src = `${root}/img/${file}.svg`;
});
});


Service.setLoading();

const previewPhoto = (e) => {
	const file = e.target.files[0];
	if (file) {

		let img = e.target.getAttribute('data-img');
	
		const reader = new FileReader();
		reader.onload = function(event) {
			document.getElementById(img).src = event.target.result;
		};
		reader.readAsDataURL(file);
	}
}

const cameraInput = document.querySelector('#cameraInput');
cameraInput?.addEventListener('change', previewPhoto);



allBtnCamera = document.querySelectorAll('.btn_open_camera')
allBtnCamera.forEach( btn => {
	btn.addEventListener('click', (e) => {
		let img = e.currentTarget.getAttribute('data-img');
		let input = e.currentTarget.getAttribute('data-input');

		document.getElementById(img).src = "<?= base_url('img/no_img_alt.png') ?>";
		document.getElementById(input).value = ''; 
		document.getElementById(input).click();
	});
})

allBtnRetry = document.querySelectorAll('.btn_retry')
allBtnRetry.forEach( btn => {
	btn.addEventListener('click', (e) => {
		let input = e.currentTarget.getAttribute('data-input');

		document.getElementById(input).value = ''; 
		document.getElementById(input).click();
	});
})


allBtnFile = document.querySelectorAll('.btn_save_file')
allBtnFile.forEach( btn => {
	btn?.addEventListener('click', (e) => {
		let img = btn.getAttribute('data-img');
		let item = btn.getAttribute('data-item');

		let input_file = document.querySelector(`input[data-img="${img}"]`);

		// console.log(input_file.files[0]);
		// return;

		let modal_active = document.querySelector('.modal_active');
		if (modal_active) {
			modal_active.classList.add('hidden');
			modal_active.classList.remove('modal_active');
			document.body.classList.remove('no-scroll');
		}

		if (img === "img_certif") {

			const file = input_file.files[0];

			if (!file) return;

			const formData = new FormData();
			formData.append('etiqueta', file);
			formData.append('tipo', 'etiqueta');

			Service.show('.loading');
			Service.stopSubmit(e.target, true);


			Service.exec('post', `/upload/inspeccion`, formData_header, formData)
			.then(r => {

				const input_obs = document.querySelector(`input[name="items[${item}][observacion]"`);
				
				input_obs.value = r.url;
				document.querySelector('#img_etiqueta_insp').src = `${root}/files/download?path=${r.url}`;

				Service.hide('.loading');

				Service.stopSubmit(e.target, false);
			});  


		}


	});
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
			document.body.classList.add('no-scroll');

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
				document.body.classList.remove('no-scroll');
			}
		});
	});

}

initRowBtn();



</script>
</body>
</html>

