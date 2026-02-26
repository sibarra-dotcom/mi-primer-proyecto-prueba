<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">

  <div class="relative flex flex-col gap-y-2 items-center justify-center font-titil mb-10">

  	<?php echo view('produccion/_partials/navbar_old'); ?>

		<div class="relative text-title w-full py-2 px-4 lg:px-10 flex items-center justify-between ">

			<div class="hidden lg:flex gap-4 items-center absolute left-6 lg:left-10 top-1/2 -translate-y-1/2">
				<a href="<?= base_url('produccion/registro_diario1') ?>" class="hover:scale-110 transition-transform duration-100 ">
					<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
				</a>
			</div>

			<div class="w-full flex items-center justify-start xl:justify-center">
				<h2 class="text-center font-semibold w-fit text-2xl lg:text-3xl "><?= esc($title) ?></h2>
			</div>

			<div class=" flex gap-4 items-center absolute right-4 lg:right-10 top-1/2 -translate-y-1/2">

				<a href=<?= base_url('produccion/ordenes_create') ?> class="btn btn-sm btn--primary">
					<i class="fa fa-plus text-base"></i>
					<span>Agregar Registro</span>
				</a>

			</div>

    </div>


		<!-- main content -->
    <div id="liberacion-wrapper"  class="w-full flex flex-col text-sm text-gray gap-8 ">


      <div id="liberacion_1" class="flex flex-col h-full" >

				<div class="w-full flex flex-col lg:w-2/3 mx-auto pl-10 pr-8 py-12 border border-grayMid gap-4 ">

					<!-- header -->
					<div class="flex items-center justify-center border border-grayMid ">
						<div class="flex w-[20%]">
							<img class="w-44 p-2" src="<?= base_url('img/gibanibb_logo.png') ?>">
						</div>

						<div class="flex flex-col w-[55%] text-xs">
							<div class="cell flex p-1 border-grayMid border-l border-b ">
								<span>TÍTULO: </span>
								<p class="text-center w-full px-4">LIBERACION DE MEZCLAS DE PRODUCCION</p>
							</div>	
							<div class="cell flex p-1 border-grayMid border-l border-b ">
								<span>CLAVE: </span>
								<p class="text-center w-full px-4">LMI-REG-04</p>
							</div>	
							<div class="cell flex p-1 border-grayMid border-l ">
								<span>VERSIÓN: </span>
								<p class="text-center w-full px-4">08</p>
							</div>						
						</div>

						<div class="flex flex-col w-[25%] text-xs">
							<div class="cell--alt flex p-1 justify-between border-grayMid border-l border-b">
								<span>PÁGINA: </span>
								<p class="num-page">1 de 2</p>
							</div>						
							<div class="cell--alt cell--mobile flex p-1 justify-between border-grayMid border-l border-b  ">
								<span>ÚLTIMA REVISIÓN: </span>
								<p class="moment-date">SEP.-2024</p>
							</div>						
							<div class="cell--alt cell--mobile flex p-1 justify-between border-grayMid border-l  ">
								<span>FECHA DE VIGENCIA: </span>
								<p class="moment-date">ENE.-2026</p>
							</div>						
						</div>
					</div>

					<!-- product data -->
					<div class="w-full flex flex-col border border-title ">

						<div class="w-full flex justify-between  text-sm border-b border-title">
							<div class="w-[30%] font-bold p-1 bg-title bg-opacity-20 text-center border-r border-title">PRODUCTO</div>
							<div class="flex-1 p-1 text-left"></div>
						</div>

						<div class="w-full flex justify-between text-sm">
							<div class="w-[30%] font-bold p-1 bg-title bg-opacity-20 text-center border-r border-title">LOTE DE MEZCLA</div>
							<div class="w-[25%] font-bold p-1 text-center border-r border-title"></div>
							<div class="w-[25%] font-bold p-1 bg-title bg-opacity-20 text-center border-r border-title">DILUSION PARA LIBERACION</div>
							<div class="flex-1 p-1 text-left"></div>
						</div>

					</div>

					<!-- organo -->
					<div class=" border-b">
						<div class="w-full flex justify-between bg-title text-white text-base">
							<div class="w-full p-1 text-center">LIBERACION DE PROPIEDAD ORGANOLEPTICAS</div>
						</div>

						<!-- especificacion -->
						<div class="w-full flex items-center border-l border-t border-gray ">
							<div class="h-6 w-72 flex items-center bg-title bg-opacity-20 border-r  font-bold">
								<span class="w-full px-8 text-right">ESPECIFICACIÓN</span>
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="especificacion" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="especificacion" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="especificacion" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="especificacion" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="especificacion" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
						</div>

						<!-- hora inicio -->
						<div class="w-full flex items-center border-l border-t border-gray ">
							<div class="h-6 w-72 flex items-center bg-title bg-opacity-20 border-r  font-bold">
								<span class="w-full px-8 text-right">Hora Inicio</span>
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="hora_inicio" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="hora_inicio" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="hora_inicio" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="hora_inicio" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="hora_inicio" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>

						</div>

						<!-- hora fin -->
						<div class="w-full flex items-center border-l border-t border-gray ">
							<div class="h-6 w-72 flex items-center bg-title bg-opacity-20 border-r  font-bold">
								<span class="w-full px-8 text-right">Hora Fin</span>
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="hora_fin" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="hora_fin" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="hora_fin" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="hora_fin" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="hora_fin" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
						</div>

						<!-- fecha -->
						<div class="w-full flex items-center border-l border-t border-gray ">
							<div class="h-6 w-72 flex items-center bg-title bg-opacity-20 border-r  font-bold">
								<span class="w-full px-8 text-right">FECHA</span>
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="especificacion" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="especificacion" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="especificacion" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="especificacion" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="especificacion" class="text-xs h-6 text-center w-full focus:outline-none read-only:bg-white">
							</div>

						</div>


						<!-- descripcion visual -->
						<div class="w-full flex items-center border-l border-t border-gray ">

							<div class="h-10 w-72 flex items-center border-r text-sm">
								<div class="h-10 w-1/2 flex items-center bg-title bg-opacity-20 border-r text-xs uppercase font-semibold">
									<span class="w-full text-center">Descrip. Visual</span>
								</div>
								<div class="h-10 w-1/2 flex items-center">
									<span class="w-full text-center">lorem lorem lorem Lorem, ipsum.</span>
								</div>
							</div>

							<div class=" border-r text-center">
								<input type="text" readonly data-field="descripcion_visual" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="descripcion_visual" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="descripcion_visual" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="descripcion_visual" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="descripcion_visual" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>

						</div>

						<!-- color -->
						<div class="w-full flex items-center border-l border-t border-gray ">
							<div class="h-10 w-72 flex items-center border-r text-sm">
								<div class="h-10 w-1/2 flex items-center bg-title bg-opacity-20 border-r text-xs uppercase font-semibold">
									<span class="w-full text-center">Color</span>
								</div>
								<div class="h-10 w-1/2 flex items-center">
									<span class="w-full text-center">lorem lorem lorem Lorem, ipsum.</span>
								</div>
							</div>

							<div class=" border-r text-center">
								<input type="text" readonly data-field="color" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="color" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="color" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="color" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="color" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>

						</div>

						<!-- aroma -->
						<div class="w-full flex items-center border-l border-t border-gray ">

							<div class="h-10 w-72 flex items-center border-r text-sm">
								<div class="h-10 w-1/2 flex items-center bg-title bg-opacity-20 border-r text-xs uppercase font-semibold">
									<span class="w-full text-center">Aroma</span>
								</div>
								<div class="h-10 w-1/2 flex items-center">
									<span class="w-full text-center">lorem lorem lorem Lorem, ipsum.</span>
								</div>
							</div>

							<div class=" border-r text-center">
								<input type="text" readonly data-field="aroma" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="aroma" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="aroma" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="aroma" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="aroma" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
						</div>

						<!-- sabor -->
						<div class="w-full flex items-center border-l border-t border-gray ">

							<div class="h-10 w-72 flex items-center border-r text-sm">
								<div class="h-10 w-1/2 flex items-center bg-title bg-opacity-20 border-r text-xs uppercase font-semibold">
									<span class="w-full text-center">Sabor</span>
								</div>
								<div class="h-10 w-1/2 flex items-center">
									<span class="w-full text-center">lorem lorem lorem Lorem, ipsum.</span>
								</div>
							</div>

							<div class=" border-r text-center">
								<input type="text" readonly data-field="sabor" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="sabor" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="sabor" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="sabor" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="sabor" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>

						</div>
					</div>


					<!-- fisicoquimicas -->
					<div class=" border-b">
						<div class="w-full flex justify-between bg-title text-white text-base">
							<div class="w-full p-1 text-center">LIBERACION DE PROPIEDADES FISICOQUIMICAS</div>
						</div>

						<!-- Humedad -->
						<div class="w-full flex items-center border-l border-t border-gray ">

							<div class="h-10 w-72 flex items-center border-r text-sm">
								<div class="h-10 w-1/2 flex items-center bg-title bg-opacity-20 border-r text-xs uppercase font-semibold">
									<span class="w-full text-center">Humedad</span>
								</div>
								<div class="h-10 w-1/2 flex items-center">
									<span class="w-full text-center">lorem lorem lorem Lorem, ipsum.</span>
								</div>
							</div>

							<div class=" border-r text-center">
								<input type="text" readonly data-field="humedad" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="humedad" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="humedad" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="humedad" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="humedad" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>

						</div>

						<!-- densidad -->
						<div class="w-full flex items-center border-l border-t border-gray ">

							<div class="h-10 w-72 flex items-center border-r text-sm">
								<div class="h-10 w-1/2 flex items-center bg-title bg-opacity-20 border-r text-xs uppercase font-semibold">
									<span class="w-full text-center">Densidad</span>
								</div>
								<div class="h-10 w-1/2 flex items-center">
									<span class="w-full text-center">lorem lorem lorem Lorem, ipsum.</span>
								</div>
							</div>

							<div class=" border-r text-center">
								<input type="text" readonly data-field="densidad" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="densidad" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="densidad" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="densidad" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="densidad" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>

						</div>

						<!-- ph -->
						<div class="w-full flex items-center border-l border-t border-gray ">

							<div class="h-10 w-72 flex items-center border-r text-sm">
								<div class="h-10 w-1/2 flex items-center bg-title bg-opacity-20 border-r text-xs uppercase font-semibold">
									<span class="w-full text-center">pH</span>
								</div>
								<div class="h-10 w-1/2 flex items-center">
									<span class="w-full text-center">lorem lorem lorem Lorem, ipsum.</span>
								</div>
							</div>

							<div class=" border-r text-center">
								<input type="text" readonly data-field="ph" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="ph" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="ph" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="ph" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="ph" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>

						</div>

						<!-- brix -->
						<div class="w-full flex items-center border-l border-t border-gray ">

							<div class="h-10 w-72 flex items-center border-r text-sm">
								<div class="h-10 w-1/2 flex items-center bg-title bg-opacity-20 border-r text-xs uppercase font-semibold">
									<span class="w-full text-center">BRIX</span>
								</div>
								<div class="h-10 w-1/2 flex items-center">
									<span class="w-full text-center">lorem lorem lorem Lorem, ipsum.</span>
								</div>
							</div>

							<div class=" border-r text-center">
								<input type="text" readonly data-field="brix" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="brix" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="brix" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="brix" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="brix" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>

						</div>

						<!-- acidez -->
						<div class="w-full flex items-center border-l border-t border-gray ">

							<div class="h-10 w-72 flex items-center border-r text-sm">
								<div class="h-10 w-1/2 flex items-center bg-title bg-opacity-20 border-r text-xs uppercase font-semibold">
									<span class="w-full text-center">Acidez</span>
								</div>
								<div class="h-10 w-1/2 flex items-center">
									<span class="w-full text-center">lorem lorem lorem Lorem, ipsum.</span>
								</div>
							</div>

							<div class=" border-r text-center">
								<input type="text" readonly data-field="acidez" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="acidez" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="acidez" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="acidez" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="acidez" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>

						</div>

						<!-- tiempo desintegracion -->
						<div class="w-full flex items-center border-l border-t border-gray ">

							<div class="h-10 w-72 flex items-center border-r text-sm">
								<div class="h-10 w-1/2 flex items-center bg-title bg-opacity-20 border-r text-xs uppercase font-semibold">
									<span class="w-full text-center">Tiempo Desintegracion</span>
								</div>
								<div class="h-10 w-1/2 flex items-center">
									<span class="w-full text-center">lorem lorem lorem Lorem, ipsum.</span>
								</div>
							</div>

							<div class=" border-r text-center">
								<input type="text" readonly data-field="tiempo_desintegracion" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="tiempo_desintegracion" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="tiempo_desintegracion" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="tiempo_desintegracion" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="tiempo_desintegracion" class="text-xs h-10 text-center w-full focus:outline-none read-only:bg-white">
							</div>

						</div>

						<!-- lotes producto terminado -->
						<div class="w-full flex items-center border-l border-t border-gray ">
							<div class="h-8 w-72 flex items-center bg-title bg-opacity-20 border-r  font-bold">
								<span class="w-full px-4 text-center text-sm">LOTES DE PRODUCTO TERMINADO</span>
						</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="lote_producto" class="text-xs h-8 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="lote_producto" class="text-xs h-8 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="lote_producto" class="text-xs h-8 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="lote_producto" class="text-xs h-8 text-center w-full focus:outline-none read-only:bg-white">
							</div>
							<div class=" border-r text-center">
								<input type="text" readonly data-field="lote_producto" class="text-xs h-8 text-center w-full focus:outline-none read-only:bg-white">
							</div>

						</div>

						<div class="w-full flex items-center border-l border-t border-gray ">
							<div class="h-14 w-72 flex items-center bg-title bg-opacity-20 border-r  font-bold">
								<span class="w-full px-4 text-center text-sm uppercase">Analizado laboratorio de pruebas Calidad</span>
							</div>

							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
						</div>

					</div>


					<!-- firmas aprobacion -->
					<div class=" border-b">
						<div class="w-full flex justify-between bg-title text-white text-base">
							<div class="w-full p-1 text-center">Aprobacion</div>
						</div>


						<div class="w-full flex items-center border-l border-t border-gray ">
							<div class="h-14 w-72 flex items-center bg-title bg-opacity-20 border-r  font-bold">
								<span class="w-full px-4 text-center text-sm uppercase">Calidad</span>
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>

						</div>

						<div class="w-full flex items-center border-l border-t border-gray ">
							<div class="h-14 w-72 flex items-center bg-title bg-opacity-20 border-r  font-bold">
								<span class="w-full px-4 text-center text-sm uppercase">Produccion</span>
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
						</div>

						<div class="w-full flex items-center border-l border-t border-gray ">
							<div class="h-14 w-72 flex items-center bg-title bg-opacity-20 border-r  font-bold">
								<span class="w-full px-4 text-center text-sm uppercase">Desarrollo</span>
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>
							<div class="border-r text-center relative">
								<input type="text" class="text-xs h-14 w-full focus:outline-none read-only:bg-white" readonly>

								<img src="<?= base_url('img/leaves.png') ?>" class="absolute inset-0 m-auto max-h-10 pointer-events-none">
							</div>

						</div>

					</div>

				</div>

      </div>


    </div>




  </div>





<script>
const MAX_COLS = 5;

function renderLiberacion(data) {
  const wrapper = document.getElementById('liberacion-wrapper');
  const template = document.getElementById('liberacion_1');

  wrapper.innerHTML = '';
  wrapper.appendChild(template);

  let page = 0;

  data.forEach((row, i) => {
    const col = i % MAX_COLS;

    if (col === 0 && i !== 0) {
      page++;
      const clone = template.cloneNode(true);
      clone.id = `liberacion_${page + 1}`;

      clone.querySelectorAll('input').forEach(inp => inp.value = '');
      wrapper.appendChild(clone);
    }

    const current = wrapper.children[page];

    Object.entries(row).forEach(([field, value]) => {
      const inputs = current.querySelectorAll(`input[data-field="${field}"]`);
      if (inputs[col]) inputs[col].value = value;
    });
  });
}

// AXIOS
axios.get('/api/liberacion')
  .then(res => renderLiberacion(res.data))
  .catch(console.error);
</script>


</body>
</html>