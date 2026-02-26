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

    <?php echo view('mantenimiento/_partials/navbar'); ?>


		<form id="form_inspeccion" method="post" class="pdf-container py-20" enctype='multipart/form-data'>
			<?= csrf_field() ?>

			<div class=" page__container">

				<div class="bg-grayMid bg-opacity-50 p-1 w-full lg:w-1/2 mx-auto flex  justify-around items-center text-xl lg:text-2xl text-title font-bold uppercase ">
					<h2 class="w-1/2 lg:w-3/4 text-center">Orden de fabricación</h2>
					<input type="text" id="num_orden" value="25" class=" w-1/2 lg:w-1/4 text-center">
				</div>


				<div class="general__section">

					<div class="general__col--produccion header border-r border-t  ">
						<div class=" col w-full lg:w-[100%] border-l border-b">
							<span>Detalles orden</span>
						</div>
					</div>

					<div class="w-full flex flex-col lg:flex-row border-gray border-r">

						<div class="flex flex-col p-2 lg:p-4 gap-y-2 lg:gap-y-3 border-l lg:border-b w-full lg:w-[50%]">

							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="fecha_venc">Fecha de vencimiento</label>
								<input type="text" id="fecha_venc" value="0025">
							</div>
										<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="codigo_cliente">Código de cliente</label>
								<input type="text" id="codigo_cliente" value="027">
							</div>
										<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="pedido">Pedido</label>
								<input type="text" id="pedido" value="87">
							</div>


						</div>

						<div class="flex flex-col px-2 pb-2 lg:p-4 gap-y-2 lg:gap-y-3 border-l lg:border-l-0  border-b w-full lg:w-[50%]">
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="tipo_orden">Tipo</label>
								<input type="text" id="tipo_orden" value="Ear">
							</div>
														<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="nombre_deudor">Nombre de deudor</label>
								<input type="text" id="nombre_deudor" value="Nore">
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="status_pedido">Status de pedido</label>
								<input type="text" id="status_pedido" value="Lido">
							</div>
										<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="origen">Origen</label>
								<input type="text" id="origen" value="Mal">
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="cantidad_plan">Cantidad planificada</label>
								<input type="text" id="cantidad_plan" >
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="fecha_compromiso">Fecha Compromiso planificada</label>
								<input type="date" id="fecha_compromiso">
							</div>

						</div>
					</div>


					<div class="general__col--produccion header border-r ">
						<div class=" col w-full lg:w-[100%] border-l border-b">
							<span>Detalles Articulo</span>
						</div>
					</div>


					<div class="w-full flex flex-col lg:flex-row border-gray border-r">

						<div class="flex flex-col p-2 lg:p-4 gap-y-2 lg:gap-y-3 border-l lg:border-b w-full lg:w-[50%]">

							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="num_articulo">Número de artículo</label>
								<input type="text" id="num_articulo" value="P324">
							</div>
										<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="desc_articulo">Descripción artículo</label>
								<input type="text" id="desc_articulo" value="C07">
							</div>
										<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="lote">Lote</label>
								<input type="text" id="lote" value="JOHXICO">
							</div>


						</div>

						<div class="flex flex-col px-2 pb-2 lg:p-4 gap-y-2 lg:gap-y-3 border-l lg:border-l-0  border-b w-full lg:w-[50%]">
							<div class="flex flex-col gap-y-4 w-full bg-grayMid bg-opacity-50 p-1 items-center text-center">
								<p class="text-center" >Rangos de llenado</p>
								<div class="flex w-full gap-x-2 items-center justify-between">
									<div class="w-1/3 flex flex-col">
										<label for="rango_min">Rango Min</label>
										<input type="text" id="rango_min" class="text-center" value="4g">
									</div>
									
									<div class="w-1/3 flex flex-col">
										<label for="rango_ideal">Rango Ideal</label>
										<input type="text" id="rango_ideal" class="text-center" value="51mg">
									</div>

									<div class="w-1/3 flex flex-col">
										<label for="rango_max">Rango Max</label>
										<input type="text" id="rango_max" class="text-center" value="50mg">
									</div>
								</div>

							</div>

							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="caducidad">Caducidad</label>
								<input type="text" id="caducidad" value="Nombre">
							</div>
							<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="rfc_cliente">RFC Cliente</label>
								<input type="text" id="rfc_cliente" value="MAVS3">
							</div>
										<div class="flex w-full bg-grayMid bg-opacity-50 p-1 items-center">
								<label class="w-1/3" for="num_piezas">Piezas por caja</label>
								<input type="text" id="num_piezas" value="100">
							</div>


						</div>


					</div>
				</div>

				<div class="flex flex-col py-10">
					<input type="file">
					<button class="load-pdf" type="button">Load Pdf file</button>
				</div>


			</div>

		</form>

  </div>





<script>
  Service.setLoading();
  
  // Service.show('#modal_create .modal-loading');
  // Modal.init('modal_success').open();

// 									Mín: 480 mg
// Rangos de llenado: Ideal: 510 mg
// 									Máx: 540 mg

        const apiKey = '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'; // Replace with your actual API key



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

        const sendToOpenAI = async (text) => {
            const prompt = `				
						You will extract information from a manufacturing order document.\n\n
						Only extract information from sections “Orden de fabricación”, “Detalles orden”, “Detalles artículo”, and “Rangos de llenado”.\n
						Skip any section titled “Componentes” or data below that title.\n\n
						For “Piezas por caja”, if there are two numbers (e.g., "18.0000" and "0.0000"), only return the **first valid number with decimals**.\n\n
						For “Cantidad planificada”, include both the number and its **unit** (e.g., "1,000 PIEZAS").\n\n

						Return JSON in the following format:\n
						{\n
							"num_orden": "",\n
							"fecha_venc": "",\n
							"codigo_cliente": "",\n
							"pedido": "",\n
							"tipo_orden": "",\n
							"nombre_deudor": "",\n
							"status_pedido": "",\n
							"origen": "",\n
							"cantidad_plan": "",\n
							"num_articulo": "",\n
							"desc_articulo": "",\n
							"lote": "",\n
							"caducidad": "",\n
							"rfc_cliente": "",\n
							"num_piezas": "",\n
							"rango_min": "",\n
							"rango_ideal": "",\n
							"rango_max": ""\n
						}\n
						If any field is missing, fill with “-”.\n\n	
						Document content:\n\n${text}
					`;

            const response = await axios.post('https://api.openai.com/v1/chat/completions', {
                model: "gpt-4o",
                messages: [
                    { "role": "system", "content": "You are a helpful assistant that extracts structured data from text." },
                    { "role": "user", "content": prompt }
                ]
            }, {
                headers: {
                    Authorization: `Bearer ${apiKey}`,
                    'Content-Type': 'application/json'
                }
            });

            const aiContent = response.data.choices[0].message.content;
            console.log('AI raw content:', aiContent);
            try {
                // Fix for OpenAI returning JSON in a markdown block
                const jsonString = aiContent.replace(/^```json|```$/g, '').trim();
                return JSON.parse(jsonString);
            } catch (e) {
                alert('Error parsing AI response');
                console.error('AI response:', aiContent);
                return null;
            }
        };

        const autofillForm = (data) => {
            if (!data) return;
            Object.entries(data).forEach(([key, value]) => {
                const input = document.getElementById(key);
                if (input) input.value = value;
            });
        };

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('.load-pdf').addEventListener('click', async () => {
                const fileInput = document.querySelector('input[type="file"]');
                if (!fileInput.files.length) return alert("Selecciona un archivo PDF");

                const text = await readPdfText(fileInput.files[0]);
                const data = await sendToOpenAI(text);
                autofillForm(data);
            });
        });




</script>
</body>
</html>