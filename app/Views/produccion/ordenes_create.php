<?php echo view('_partials/header'); ?>
	<link rel="stylesheet" href="<?= base_url('_partials/inspec.css') ?>">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>
  <script src="<?= load_asset('js/Modal.min.js') ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">
	<img src="<?= base_url('img/mantenimientoweb.svg') ?>" class="hidden lg:flex absolute bottom-0 left-0 ">
	<img src="<?= base_url('img/mantenimientomovil.svg') ?>" class="flex lg:hidden absolute bottom-0 left-0 ">

  <div class=" relative h-full flex flex-col items-center justify-center font-titil ">

  	<?php echo view('produccion/_partials/navbar'); ?>

		<div class="text-title w-full md:pt-4 md:pb-8 md:px-16 p-2 flex items-center ">
      <h2 class="text-center font-bold w-full text-2xl lg:text-3xl "><?= esc($title) ?></h2>
      <a href="<?= base_url('produccion/ordenes_lista') ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>

		<form id="form_orden" method="post" class="pdf-container pt-4 pb-20" enctype='multipart/form-data'>

			<div class=" page__container">


				<div class="flex items-center justify-between gap-4 py-10">
					<div class="flex items-center gap-x-4">
						<input id="orden-pdf" type="file" accept="application/pdf" class="hidden">

						<button id="custom-upload-btn" class="rounded text-icon border-2 border-icon hover:bg-icon hover:text-white py-1 px-3 w-fit uppercase" type="button">
							<span>Seleccionar PDF</span>
						</button>

						<p id="file-name" class=" text-gray hidden">
							No file selected
						</p>
					</div>

					<button id="get_pdf_data" class="rounded text-icon border-2 border-icon hover:bg-icon hover:text-white py-1 px-3 w-fit gap-x-4 uppercase" type="button">
						<i class="fa fa-gear text-xl"></i>
						<span>Leer PDF</span>
					</button>
				</div>


				<div class="bg-grayMid bg-opacity-50 p-1 w-full lg:w-1/2 mx-auto flex  justify-around items-center text-xl lg:text-2xl text-title font-bold uppercase ">
					<h2 class="w-1/2 lg:w-3/4 text-center">Orden de fabricación</h2>
					<input type="text" name="num_orden" id="num_orden" class="w-1/2 lg:w-1/4 text-center">
				</div>


				<div class="general__section">

					<div class="general__col--produccion header border-r border-t  ">
						<div class=" col w-full lg:w-[100%] border-l border-b">
							<span class="text-2xl">Detalles orden</span>
						</div>
					</div>

					<div class="w-full flex flex-col lg:flex-row border-gray border-r">

						<div class="flex flex-col p-2 lg:p-4 gap-y-2 lg:gap-y-3 border-l lg:border-b w-full lg:w-[50%]">

							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="fecha_venc">Fecha de vencimiento</label>
								<input type="text" class="flex-1" name="fecha_vencimiento" id="fecha_vencimiento" >
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="codigo_cliente">Código de cliente</label>
								<input type="text" class="flex-1" name="codigo_cliente" id="codigo_cliente">
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="pedido">Pedido</label>
								<input type="text" class="flex-1" name="pedido" id="pedido">
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="omg">OC del Cliente</label>
								<input type="text" class="flex-1" name="omg" id="omg">
							</div>
						</div>

						<div class="flex flex-col px-2 pb-2 lg:p-4 gap-y-2 lg:gap-y-3 border-l lg:border-l-0  border-b w-full lg:w-[50%]">
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="tipo_orden">Tipo</label>
								<input type="text" class="flex-1" name="tipo_orden" id="tipo_orden" >
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="nombre_deudor">Nombre de deudor</label>
								<input type="text" class="flex-1" name="nombre_deudor" id="nombre_deudor">
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="status_pedido">Status de pedido</label>
								<input type="text" class="flex-1" name="status_pedido" id="status_pedido">
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="origen">Origen</label>
								<input type="text" class="flex-1" name="origen" id="origen">
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="cantidad_plan">Cantidad planificada</label>
								<input type="text" class="flex-1" name="cantidad_plan" id="cantidad_plan">
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="fecha_compromiso">Fecha Compromiso</label>
								<input type="text" class="flex-1 to-date" data-name="fecha_compromiso" placeholder="dd-mm-yyyy" required>
							</div>

						</div>
					</div>


					<div class="general__col--produccion header border-r ">
						<div class=" col w-full lg:w-[100%] border-l border-b">
							<span class="text-2xl">Detalles Articulo</span>
						</div>
					</div>


					<div class="w-full flex flex-col lg:flex-row border-gray border-r">

						<div class="flex flex-col p-2 lg:p-4 gap-y-2 lg:gap-y-3 border-l lg:border-b w-full lg:w-[50%]">

							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="num_articulo">Número de artículo</label>
								<input type="text" class="flex-1" name="num_articulo" id="num_articulo">
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="desc_articulo">Descripción artículo</label>
								<input type="text" class="flex-1" name="desc_articulo" id="desc_articulo">
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="lote">Lote</label>
								<input type="text" class="flex-1" name="lote" id="lote">
							</div>


						</div>

						<div class="flex flex-col px-2 pb-2 lg:p-4 gap-y-2 lg:gap-y-3 border-l lg:border-l-0  border-b w-full lg:w-[50%]">
							<div class="flex flex-col gap-y-4 w-full bg-grayMid bg-opacity-50 p-1 items-center text-center">
								<p class="text-center" >Rangos de llenado</p>
								<div class="flex w-full gap-x-2 items-center justify-between">
									<div class="w-1/3 flex flex-col">
										<label for="rango_min">Rango Min</label>
										<input type="text" class="w-full" name="rango_min" id="rango_min" class="text-center">
									</div>
									
									<div class="w-1/3 flex flex-col">
										<label for="rango_ideal">Rango Ideal</label>
										<input type="text" class="w-full" name="rango_ideal" id="rango_ideal" class="text-center">
									</div>

									<div class="w-1/3 flex flex-col">
										<label for="rango_max">Rango Max</label>
										<input type="text" class="w-full" name="rango_max" id="rango_max" class="text-center">
									</div>
								</div>

							</div>

							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="caducidad">Caducidad</label>
								<input type="text" class="flex-1" name="caducidad" id="caducidad">
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="rfc_cliente">RFC Cliente</label>
								<input type="text" class="flex-1" name="rfc_cliente" id="rfc_cliente">
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="num_piezas">Piezas por caja</label>
								<input type="text" class="flex-1" name="num_piezas" id="num_piezas">
							</div>


						</div>


					</div>
				</div>


				<div class="form-row-submit ">
					<button class="modal-btn--submit" type="submit">AÑADIR</button>
				</div>



			</div>

		</form>

  </div>


<script src="<?= load_asset('js/FormHelper.min.js') ?>"></script>
<script>

  Service.setLoading();
	FormHelper.toDate();


	const fileInput = document.getElementById('orden-pdf');
  const customBtn = document.getElementById('custom-upload-btn');
  const fileNameDisplay = document.getElementById('file-name');

  customBtn.addEventListener('click', () => {
    fileInput.click();
  });

  fileInput.addEventListener('change', () => {
    const file = fileInput.files[0];
    if (file) {
      fileNameDisplay.innerHTML = `<span class="text-lg text-icon"> Seleccionado : ${file.name} </span>`;
      fileNameDisplay.classList.remove('hidden');
    } else {
      fileNameDisplay.innerHTML = 'No file selected';
      fileNameDisplay.classList.add('hidden');
    }
  });


	const readPdfText = async (file) => {
		const pdfjsLib = window['pdfjs-dist/build/pdf'];
		pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
		const reader = new FileReader();

		return new Promise((resolve) => {
			reader.onload = async function () {
					const typedarray = new Uint8Array(this.result);
					const pdf = await pdfjsLib.getDocument(typedarray).promise;
					let text = '';

					for (let i = 1; i <= pdf.numPages; i++) {
							const page = await pdf.getPage(i);
							const content = await page.getTextContent();
							const strings = content.items.map(item => item.str).join(' ');
							text += strings + '\n';
					}

					resolve(text);
			};
			reader.readAsArrayBuffer(file);
		});
	};


	const autofillForm = (data) => {
			if (!data) return;
			Object.entries(data).forEach(([key, value]) => {
					const input = document.getElementById(key);
					if (input) input.value = value;
			});
	};

	const getData = async (e) => {
		Service.stopSubmit(e.target, true);
		Service.show('.loading');

		const fileInput = document.querySelector('input[id="orden-pdf"]');

		if (!fileInput.files.length) {
			Service.hide('.loading');

			fileNameDisplay.innerHTML = `<span class="text-lg text-warning">Debe seleccionar un archivo PDF</span>`;
			fileNameDisplay.classList.remove('hidden');
			return;
		}

		const text = await readPdfText(fileInput.files[0]);
		// const data = await sendToOpenAI(text);

		const formData = new FormData();
		formData.append('pdf-text', text);

		Service.exec('post', `/openia/read_pdf`, formData_header, formData)
		.then(r => {
			Service.hide('.loading');
			Service.stopSubmit(e.target, false);

			const jsonString = r.answer.replace(/^```json|```$/g, '').trim();

			autofillForm(JSON.parse(jsonString));
		});  
		
	};

	const get_pdf_data = document.getElementById('get_pdf_data');
	get_pdf_data?.addEventListener('click', getData);

	const submitForm = (e) => {
		e.preventDefault();
		Service.stopSubmit(e.target, true);
		Service.show('.loading');

		const formData = new FormData(e.target);

    Service.exec('post', `produccion/ordenes_create`, formData_header, formData)
    .then( r => {

			if(r.success){
				Service.stopSubmit(e.target, false);
				// form_orden.reset();
				window.location.href = `${root}/produccion/ordenes_lista`;
			}

    });
	}

	const form_orden = document.querySelector('#form_orden');
	form_orden.addEventListener('submit', submitForm);


</script>
</body>
</html>