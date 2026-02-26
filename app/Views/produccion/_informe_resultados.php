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
			</div>

    </div>


		<!-- main content -->
    <div class="w-full text-sm text-gray  ">
      <div class="flex flex-col h-full" >

				<div class="w-full flex flex-col gap-4 lg:w-2/3 mx-auto pl-10 pr-8 py-12 border border-grayMid ">

					<!-- header -->
					<div class="flex items-center justify-center border border-grayMid ">
						<div class="flex w-[20%]">
							<img class="w-44 p-2" src="<?= base_url('img/gibanibb_logo.png') ?>">
						</div>

						<div class="flex flex-col w-[55%] text-xs">
							<div class="cell flex p-1 border-grayMid border-l border-b ">
								<span>TÍTULO: </span>
								<p class="text-center w-full px-4">INFORME DE RESULTADOS MATERIAS PRIMAS Y PRODUCTOS TERMINADOS</p>
							</div>	
							<div class="cell flex p-1 border-grayMid border-l border-b ">
								<span>CLAVE: </span>
								<p class="text-center w-full px-4">LMI-REG-09</p>
							</div>	
							<div class="cell flex p-1 border-grayMid border-l ">
								<span>VERSIÓN: </span>
								<p class="text-center w-full px-4">03</p>
							</div>						
						</div>

						<div class="flex flex-col w-[25%] text-xs">
							<div class="cell--alt flex p-1 justify-between border-grayMid border-l border-b">
								<span>PÁGINA: </span>
								<p class="num-page">1 de 1</p>
							</div>						
							<div class="cell--alt cell--mobile flex p-1 justify-between border-grayMid border-l border-b  ">
								<span>ÚLTIMA REVISIÓN: </span>
								<p class="moment-date">ENE.-2024</p>
							</div>						
							<div class="cell--alt cell--mobile flex p-1 justify-between border-grayMid border-l  ">
								<span>FECHA DE VIGENCIA: </span>
								<p class="moment-date">ENE.-2026</p>
							</div>						
						</div>
					</div>


					<div class="w-full flex flex-col border border-grayMid">
						<div class="w-full flex items-center justify-between">
							<div class="w-[25%] font-bold p-1 text-center">Fecha Recepción Muestra :</div>
							<div class="flex-1 p-1 text-left"></div>
						</div>
						<div class="w-full flex items-center justify-between">
							<div class="w-[25%] font-bold p-1 text-center">Fecha Emisión Informe :</div>
							<div class="flex-1 p-1 text-left"></div>
						</div>
						<div class="w-full flex items-center justify-between">
							<div class="w-[25%] font-bold p-1 text-center">Clave de Rastreabilidad :</div>
							<div class="flex-1 p-1 text-left"></div>
						</div>
					</div>

					<div class="w-full flex flex-col border border-title">

						<div class="w-full flex justify-between bg-title text-white text-lg">
							<div class="w-full p-2 text-center">Información de la Muestra</div>

						</div>
						<div class="w-full flex justify-between  text-lg border-b border-title">
							<div class="w-[30%] font-bold p-2 bg-title bg-opacity-20 text-center border-r border-title">PRODUCTO</div>
							<div class="flex-1 p-1 text-left"></div>
						</div>
						<div class="w-full flex justify-between text-lg">
							<div class="w-[30%] font-bold p-2 bg-title bg-opacity-20 text-center border-r border-title">LOTE / BATCH</div>
							<div class="w-[25%] font-bold p-2 text-center border-r border-title"></div>
							<div class="w-[25%] font-bold p-2 bg-title bg-opacity-20 text-center border-r border-title">CADUCIDAD</div>
							<div class="flex-1 p-2 text-left"></div>
						</div>
					</div>


					<div class="flex flex-col mx-auto items-center justify-center text-lg text-left">
						<p>Se presenta a continuación los resultados obtenidos de la muestra tomada a las <input class="to-time h-6 text-sm w-full text-center" placeholder="HH:mm" data-name="hora_muestra"> hrs por el personal autorizado del laboratorio de microbiología ubicado en las instalaciones de la empresa GIBANIBB S.A. DE C.V</p>
					</div> 


					<h2 class="text-center font-bold text-2xl mt-4 ">ANÁLISIS DE RESULTADOS</h2>



					<div class="flex flex-col w-full border border-icon">

						<div class="w-full flex justify-between text-sm">
							<div class="w-[25%] bg-title text-white p-2 text-center">DETERMINACION</div>
							<div class="w-[15%] bg-title text-white p-2 text-center">RESULTADO</div>
							<div class="w-[15%] bg-title text-white p-2 text-center">UNIDADES</div>
							<div class="w-[15%] bg-title text-white p-2 text-center">FECHA ESTUDIO</div>
							<div class="w-[30%] bg-title text-white p-2 text-center">METODOLOGIA</div>
						</div>

						<div id="container_analisis" class="flex flex-col">
						</div>

					</div>


					<div class="w-full text-base flex flex-col mx-auto items-start justify-center text-left">
						<p class="font-bold mt-2">Observaciones:</p>
						<p class="font-bold mt-2">Menor de (100, 10 o 1 según sea el caso) UFC/g: No detectado</p>
    				<p>Bacterias Mesofílicas Aerobias: placas petrifilm, incubadas 48h (±3h) a 35°C (±1°C)</p>
    				<p>Hongos y Levaduras: placas petrifilm, incubadas a 25±1°C durante 5 días</p>
    				<p>Escherichia coli y Organismos Coliformes Totales: placas petrifilm; coliformes 24h (±2h) a 35°C (±1°C); E. coli 48h (±2h) a 35°C (±1°C)</p>
    				<p>Coliformes Totales se obtiene mediante la suma de organismos coliformes y E. coli</p>
					</div> 


					<!-- seccion firmas -->
					<div class="w-full flex gap-10 pt-10">

						<div class="flex flex-col mx-auto items-center gap-y-2 justify-center w-1/3 mt-2 ">
							
							<img id="produccion_firma" class="h-44 w-full border-2 border-grayMid bg-grayLight">  

							<p id="produccion_nombre" class="font-bold">NOMBRE DE ANALISTA</p>
							<p>ANALISTA DE MICROBIOLOGIA</p>

							<button data-area="produccionId" data-user-id="<?= session()->get('user')['id'] ?>" data-field="firma_produccion" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">FIRMAR</button>
			
						</div>

						<div class="flex flex-col mx-auto items-center gap-y-2 justify-center w-1/3 mt-2 ">
							
							<img id="produccion_firma" class="h-44 w-full border-2 border-grayMid bg-grayLight">  

							<p id="produccion_nombre" class="font-bold">NOMBRE DE ANALISTA</p>
							<p>ANALISTA DE GESTION DE CALIDAD</p>

							<button data-area="produccionId" data-user-id="<?= session()->get('user')['id'] ?>" data-field="firma_produccion" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">FIRMAR</button>
			
						</div>
					</div>

				</div>


      </div>
    </div>
  </div>




<script>



const analisisData = [
    {
      determinacion: "Coliformes Totales",
      resultado: "",
      unidades: "",
      fechaEstudio: "",
      metodologia: "AOAC método oficial 991.14"
    },
    {
      determinacion: "Escherichia coli",
      resultado: "",
      unidades: "",
      fechaEstudio: "",
      metodologia: "AOAC método oficial 991.14"
    },
    {
      determinacion: "Organismos Coliformes",
      resultado: "",
      unidades: "",
      fechaEstudio: "",
      metodologia: "AOAC método oficial 991.14"
    },
    {
      determinacion: "Bacterias Mesofílicas Aerobias",
      resultado: "",
      unidades: "",
      fechaEstudio: "",
      metodologia: "AOAC método oficial 990.12"
    },
    {
      determinacion: "Levaduras",
      resultado: "",
      unidades: "",
      fechaEstudio: "",
      metodologia: "AOAC método oficial 997.02"
    },
    {
      determinacion: "Hongos",
      resultado: "",
      unidades: "",
      fechaEstudio: "",
      metodologia: "AOAC método oficial 997.02"
    }
	]


const container = document.getElementById("container_analisis");

analisisData.forEach(row => {
  container.innerHTML += `


          <div class="w-full flex justify-between text-sm border-t">
            <div class="w-[25%] border-r border-title p-2 text-center bg-title bg-opacity-20 font-bold">${row.determinacion}</div>
            <div class="w-[15%] border-r border-title p-2 text-center font-bold">${row.resultado}</div>
            <div class="w-[15%] border-r border-title p-2 text-center font-bold">${row.unidades}</div>
            <div class="w-[15%] border-r border-title p-2 text-center">${row.fechaEstudio}</div>
            <div class="w-[30%] p-2 text-center">${row.metodologia}</div>
          </div>

  `;
});


</script>

</body>
</html>