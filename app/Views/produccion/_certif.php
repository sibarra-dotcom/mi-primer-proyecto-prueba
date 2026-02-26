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

					<div class="flex items-center justify-center border border-grayMid ">
						<div class="flex w-[20%]">
							<img class="w-44 p-2" src="<?= base_url('img/gibanibb_logo.png') ?>">
						</div>

						<div class="flex flex-col w-[55%] text-xs">
							<div class="cell flex p-1 border-grayMid border-l border-b ">
								<span>TÍTULO: </span>
								<p class="text-center w-full px-4">SISTEMA DE GESTIÓN DE CALIDAD</p>
							</div>	
							<div class="cell flex p-1 border-grayMid border-l border-b ">
								<span>CLAVE: </span>
								<p class="text-center w-full px-4">CCA-REG-10</p>
							</div>	
							<div class="cell flex p-1 border-grayMid border-l ">
								<span>VERSIÓN: </span>
								<p class="text-center w-full px-4">07</p>
							</div>						
						</div>

						<div class="flex flex-col w-[25%] text-xs">
							<div class="cell--alt flex p-1 justify-between border-grayMid border-l border-b">
								<span>PÁGINA: </span>
								<p class="num-page">1 de 1</p>
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


					<h2 class="text-center text-3xl text-title ">CERTIFICADO DE ANÁLISIS</h2>
					<h2 class="text-center font-bold text-xl ">INFORMACIÓN DESCRIPTIVA</h2>

					<div class="w-full flex flex-col border border-grayMid">
						<div class="w-full flex justify-between text-sm">
							<div class="w-[25%] font-bold p-1 text-center">PRODUCTO</div>
							<div class="w-[40%] p-1 text-left">VITFINITY NAD 60 CAPSULAS DE 650 MG</div>
							<div class="w-[20%] font-bold p-1 text-center">CÓDIGO</div>
							<div class="w-[15%] p-1 text-left">PT500303</div>
						</div>
						<div class="w-full flex justify-between text-sm">
							<div class="w-[25%] font-bold p-1 text-center">CLIENTE</div>
							<div class="w-[40%] p-1 text-left">THE 3 LETTERS COMPANY</div>
							<div class="w-[20%] font-bold p-1 text-center">RFC</div>
							<div class="w-[15%] p-1 text-left">TLE210804B58</div>
						</div>
												<div class="w-full flex justify-between text-sm">
							<div class="w-[25%] font-bold p-1 text-center">PAÍS</div>
							<div class="w-[40%] p-1 text-left">MÉXICO</div>
							<div class="w-[20%] font-bold p-1 text-center">PERIODO DE FABRICACIÓN</div>
							<div class="w-[15%] p-1 text-left">Sep-25</div>
						</div>

						<div class="w-full flex justify-between text-sm">
							<div class="w-[25%] font-bold p-1 text-center">LOTE</div>
							<div class="w-[40%] p-1 text-left">VND250095</div>
							<div class="w-[20%] font-bold p-1 text-center">FECHA DE CADUCIDAD</div>
							<div class="w-[15%] p-1 text-left">Oct-27</div>
						</div>

						<div class="w-full flex justify-between text-sm">
							<div class="w-[25%] font-bold p-1 text-center">CANTIDAD LIBERADA</div>
							<div class="flex-1 p-1 text-left">1,047 PIEZAS</div>

						</div>

					</div>


					<h2 class="text-center font-bold text-xl ">ANÁLISIS</h2>

					<div id="container_analisis" class="w-full flex flex-col gap-2"></div>

					<div class="flex flex-col mx-auto items-center justify-center text-sm text-center">
						<p>NOTA: Los resultados corresponden a la muestra descrita arriba.</p>
						<p class="font-bold mt-2">CONSIDERACIONES IMPORTANTES</p>
						<p>El Producto debe de mantenerse condiciones de temperatura entre 19-22°C y Humedad Relativa ( HR ) no mayor a 80%.</p>
						<p>La estabilidad del producto es inversamente proporcional a la temperatura y humedad relativa, si esta condición no se cumple el producto se verá afectado.</p>
						<p>El color y del producto terminado pueden variar por el origen natural de sus ingredientes.</p>
					</div> 

					<div class="w-full flex flex-col border border-grayMid">
						<div class="w-full flex justify-between text-base">
							<div class="w-[25%] font-bold p-1 text-center">DICTAMEN</div>
							<div class="w-[25%] p-1 text-left">
								<select name="" id="">
									<option value="aprobado">APROBADO</option>
									<option value="no_aprobado">NO APROBADO</option>
								</select>
							</div>
							<div class="w-[20%] font-bold p-1 text-center">FECHA DE LIBERACIÓN</div>
							<div class="w-[25%] p-1 text-left">07 DE NOVIEMBRE DEL 2025</div>
						</div>
					</div>

					<div class="flex flex-col mx-auto items-center gap-y-2 justify-center w-1/3 mt-2 ">
						
						<img id="produccion_firma" class="h-44 w-full border-2 border-grayMid bg-grayLight">  

						<p id="produccion_nombre" class="font-bold">T.L.Q. LUZ ADRIANA ALVARADO MANRIQUEZ</p>
						<p>ENCARGADO DE CONTROL DE CALIDAD</p>

						<button data-area="produccionId" data-user-id="<?= session()->get('user')['id'] ?>" data-field="firma_produccion" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">FIRMAR</button>
		
					</div>

				</div>


      </div>
    </div>
  </div>



<?php echo view('_partials/_modal_msg'); ?>


<script>


const analisisData = {
  sensorial: {
    "title": "SENSORIAL",
    "columns": ["Parámetro", "Especificación", "Resultado", "Método"],
    "rows": [
      {
        "parametro": "Apariencia",
        "especificacion": "Polvo de textura fina",
        "resultado": "CUMPLE",
        "metodo": "INV-MET-01"
      },
      {
        "parametro": "Color**",
        "especificacion": "Anaranjado amarillo",
        "resultado": "CUMPLE <br> **MET",
        "metodo": "INV-MET-02  <br> Método Interno de Evaluación sensorial"
      }
    ]
  },
  fisicoquimico: {
    "title": "FISICOQUÍMICO",
    "columns": ["Parámetro", "Especificación", "Resultado", "Método"],
    "rows": [
      {
        "parametro": "Densidad",
        "especificacion": "0.40 - 0.60 g/ cm³",
        "resultado": "0.48 g / cm³",
        "metodo": "LMI-INS-08"
      },
      {
        "parametro": "Humedad",
        "especificacion": "<4%",
        "resultado": "0.85%",
        "metodo": "NMX-F-428-1982"
      },
      {
        "parametro": "Tiempo de desintegración",
        "especificacion": ">30 min",
        "resultado": "CUMPLE",
        "metodo": "FEUM MGA 0261 DESINTEGRACION"
      }
    ]
  },
  cuantitativo: {
    "title": "CUANTITATIVO",
    "columns": ["Parámetro", "Especificación", "Resultado", "Método"],
    "rows": [
      {
        "parametro": "Contenido de la cápsula",
        "especificacion": "645 mg - 705 mg",
        "resultado": "CONFORME",
        "metodo": "MET-CUA-01"
      }
    ]
  },
  microbiologico: {
    title: "MICROBIOLÓGICO",
    rows: [
      {
        parametro: "Mesofílicos Aerobios",
        especificacion: "< 3,000 UFC / g",
        resultado: "< 10 UFC / g",
        metodo: "AOAC MÉTODO OFFICIAL 990.12"
      },
      {
        parametro: "Coliformes Totales",
        especificacion: "< 10 UFC / g",
        resultado: "< 10 UFC / g",
        metodo: "AOAC MÉTODO OFFICIAL 991.14"
      },
      {
        parametro: "Escherichia coli",
        especificacion: "< 10 UFC / g",
        resultado: "< 10 UFC / g",
        metodo: "AOAC MÉTODO OFFICIAL 991.14"
      },
      {
        parametro: "Mohos",
        especificacion: "< 10 UFC / g",
        resultado: "< 10 UFC / g",
        metodo: "AOAC MÉTODO OFFICIAL 997.02"
      },
      {
        parametro: "Levaduras",
        especificacion: "< 10 UFC / g",
        resultado: "< 10 UFC / g",
        metodo: "AOAC MÉTODO OFFICIAL 997.02"
      }
    ]
  }
}


const container = document.getElementById("container_analisis");

Object.values(analisisData).forEach(section => {
  container.innerHTML += `
    <h2 class="text-center font-bold text-base mt-2 ">${section.title}</h2>

    <div class="flex flex-col w-full border border-icon">

      <div class="w-full flex justify-between text-sm">
        <div class="w-full bg-title text-white font-bold p-2 text-center">PARÁMETRO</div>
        <div class="w-full bg-title text-white font-bold p-2 text-center">ESPECIFICACIÓN</div>
        <div class="w-full bg-title text-white font-bold p-2 text-center">RESULTADO</div>
        <div class="w-full bg-title text-white font-bold p-2 text-center">MÉTODO</div>
      </div>

      <div class="flex flex-col">
        ${section.rows.map(row => `
          <div class="w-full flex justify-between text-sm border-t">
            <div class="w-full px-2 py-1 text-center">${row.parametro}</div>
            <div class="w-full px-2 py-1 text-center">${row.especificacion}</div>
            <div class="w-full px-2 py-1 text-center font-bold">${row.resultado}</div>
            <div class="w-full px-2 py-1 text-center">${row.metodo}</div>
          </div>
        `).join("")}
      </div>

    </div>
  `;
});


</script>


<script>
  Service.setLoading();

const tbody = document.querySelector('#tabla-produccion-lista-procesos tbody');

const renderProv = (data) => {  
	tbody.innerHTML = "";
	// console.log(data); return;

	if (data.length > 0) {
		Service.hide('#row__empty');


		data.forEach(prov => {
			const row = document.createElement('tr');

			row.innerHTML =
				`
					<td>
						<span>${prov.turno}</span>
					</td>
					<td>
						<span>${prov.nro_orden}</span>
					</td>
					<td>
						<span>${prov.producto}</span>
					</td>

					<td>
						<span>${prov.proceso}</span>
					</td>
					<td>
						<span>${prov.name} ${prov.last_name}</span>
					</td>
					<td>
						<span>${dateToString(prov.created_at)}</span>
					</td>
					<td>
						<div class="flex items-center gap-4">
							<a href="${root}/produccion/details/${prov.id}" class="hover:text-icon">
								<i class="fas fa-eye text-lg"></i>
							</a>
						</div>
					</td>
				`
			tbody.appendChild(row);
		});

		// initRowBtn();
	} else {
		Service.show('#row__empty');
	}
}

const loadAllProveedores = () => {
	Service.hide('#row__empty');
	tbody.innerHTML = Service.loader();

	Service.exec('get', `${root}/all_reportes_old`)
	.then(r => renderProv(r));  
}

// loadAllProveedores();

</script>
</body>
</html>