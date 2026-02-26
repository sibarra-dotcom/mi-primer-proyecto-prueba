<?php echo view('_partials/header'); ?>
	<link rel="stylesheet" href="<?= base_url('_partials/inspeccion.css') ?>">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>
  <link rel="stylesheet" href="<?= load_asset('_partials/inspeccion.css') ?>">
	<script src="https://unpkg.com/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">

  <div class=" relative h-full pb-16 flex flex-col gap-y-4 items-center justify-center font-titil ">

  	<?php echo view('dashboard/_partials/navbar'); ?>

    <div class="text-title w-full md:p-4 md:px-16 p-2 flex items-center   ">
      <h2 class="text-center font-bold w-full text-xl lg:text-2xl "><?= esc($title) ?></h2>
      <a href="<?= base_url('apps') ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>

		<section class=" w-full px-8 flex flex-col gap-y-4">
			<div class="w-full flex justify-center gap-4 items-center text-3xl font-bold text-title">
				<p>Orden de Fabricación :</p>
				<span id="num_orden_fab">OF-2025-001</span>
			</div>

			<div class="flex gap-4 justify-end items-center text-gray text-sm">
				<label for="ordenSelect">Numero Orden de Fabricación:</label>
				<select id="ordenSelect" class="select_modal w-64"></select>
				<button class="btn btn-sm btn--cta" type="button"><i class="fas fa-search"></i><span>BUSCAR</span></button>
			</div>

			<div class="flex w-full gap-4 text-sm">
				<div class="flex-1 flex items-center justify-between gap-4">

					<div class=" w-full flex justify-center gap-2  bg-title text-white py-1 px-3 items-center ">
						<p>
							Fecha Arranque : 
						</p>
						<p>
							10-09-2025
						</p>
					</div>
					<div class=" w-full flex justify-center gap-2  bg-gray bg-opacity-60 text-white py-1 px-3 items-center ">
						<p>
							Fecha Compromiso : 
						</p>
						<p>
							22-09-2025
						</p>
					</div>

				</div>

				<div class="w-2/3 flex items-center gap-4">
					<!-- Fecha Termino -->
					<div class="flex-1 flex justify-center gap-2 bg-dark text-white py-1 px-3 items-center">
						<p>Fecha Termino:</p>
						<p>22-09-2025</p>
					</div>

					<!-- Diferencia Fecha Termino y compromiso -->
					<div class="flex-1 flex gap-2 justify-center bg-title bg-opacity-80 text-white py-1 px-3 items-center">
						<p>Diferencia Fecha Termino y compromiso:</p>
						<p>16:40:20</p>
					</div>

					<!-- Estatus de la orden -->
					<div class="flex-1 flex gap-2 justify-center bg-warning text-white py-1 px-3 items-center">
						<p>Estatus de la orden:</p>
						<p>EN PROGRESO</p>
					</div>
				</div>

			</div>

			<div class=" w-full flex gap-4">

				<div class="flex-1 flex flex-col gap-4 ">
					<div class="w-full flex flex-col p-4 bg-white shadow-bottom-right ">
						<div class="flex pt-12">
						</div>
						<div id="donutChart"></div>
						<h1 class="pt-6 text-xl text-center text-title">Cantidad de Piezas Producidas por Turno</h1>
					</div>
				
					<div class="w-full flex flex-col p-4 bg-white shadow-bottom-right ">
						<div class="flex pt-24">
						</div>
						<div id="gaugeChart" class="w-[350px] mx-auto"></div>
						<h1 class="pt-20 text-xl text-center text-title">Porcentaje Terminado</h1>
					</div>
				</div>

				<div class=" w-2/3 flex gap-4">
					<div class="w-3/4 flex flex-col gap-4 ">

						<div class="w-full flex flex-col p-4 bg-white shadow-bottom-right ">
							<div id="lineChart"></div>
							<!-- <h1 class="text-xl text-center text-title">Meta de produccion vs Produccion Real</h1> -->
						</div>

						<div class="w-full flex flex-col p-4 bg-white shadow-bottom-right ">
							<div class="flex pt-10">
							</div>
							<div class=" transform -translate-x-[90px] ">
								<div id="radialChart"></div>
							</div>
							<h1 class="text-xl text-center text-title">Distribución de Incidentes</h1>

						</div>

					</div>

					<div class="w-1/4 flex ">

						<div class="flex flex-col gap-4">

							<div class="w-full flex gap-4">
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-title text-white shadow-bottom-right"> 
									<p class="text-2xl font-bold " id="fabricadas">0</p>
									<p class="text-sm text-center">Total Piezas <br>fabricadas</p>
								</div>
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-white text-gray shadow-bottom-right"> 
									<p class="text-2xl font-bold " id="operadores">0</p>
									<p class="text-sm text-center">Operadores requeridos</p>
								</div>
							</div>

							<div class="w-full flex gap-4">
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-gray text-white shadow-bottom-right"> 
									<p class="text-2xl font-bold " id="faltantes">0</p>
									<p class="text-sm text-center">Piezas faltantes</p>
								</div>
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 items-center bg-white text-gray shadow-bottom-right"> 
									<div class="text-sm flex flex-col " id="duracion">0</div>
									<p class="text-sm text-center">Duración total</p>
								</div>
							</div>

							<div class="w-full flex gap-4">
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-white text-gray shadow-bottom-right"> 
									<p class="text-2xl font-bold " id="totales">0</p>
									<p class="text-sm text-center">Piezas totales O.F.</p>
								</div>
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-white text-gray shadow-bottom-right"> 
									<p class="text-2xl font-bold " id="tiempoEfectivo">0</p>
									<p class="text-sm text-center">Tiempo efectivo</p>
								</div>
							</div>

							<div class="w-full flex gap-4">
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-dark text-white shadow-bottom-right"> 
									<p class="text-2xl font-bold " id="scrap">0</p>
									<p class="text-sm text-center">SCRAP</p>
								</div>
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-white text-gray shadow-bottom-right"> 
									<p class="text-2xl font-bold " id="porcentajeEfectivo">0%</p>
									<p class="text-sm text-center">% Tiempo efectivo</p>
								</div>
							</div>

							<div class="w-full flex gap-4">
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-dark text-white shadow-bottom-right"> 
									<p class="text-2xl font-bold " id="incidencias">0</p>
									<p class="text-sm text-center">Incidencias</p>
								</div>
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-error bg-opacity-80 text-white shadow-bottom-right"> 
									<p class="text-2xl font-bold " id="tiempoMuerto">0%</p>
									<p class="text-sm text-center">Tiempo muerto</p>
								</div>
							</div>

						</div>

					</div>
		
				</div>
			</div>


		</section>



	</div>




  <script>

    const chart_radial_height = 310;
    const chart_donut_height = 310;
    const chart_gauge_height = 400;
    const chart_line_height = 440;


		const donutChartEl = document.querySelector("#donutChart");
		const radialChartEl = document.querySelector("#radialChart");	
		const gaugeChartEl = document.querySelector("#gaugeChart");
		const lineChartEl = document.querySelector("#lineChart");

    // Mock dataset
    const ordenes = [
      {
        id: "OF-2025-001",
        status: "EN PROGRESO",
        total_piezas_fabricadas: 17000,
        operadores_requeridos: 42,
        piezas_faltantes: 20000,
        duracion_total: { dias: 5, horas: 8, minutos: 20 },
        piezas_totales: 37000,
        tiempo_efectivo: { horas: 89, porcentaje: 95 },
        scrap: { porcentaje: 8 },
        incidencias_total: { total: 4 },
        tiempo_muerto: { horas: 3, minutos: 20 },
				incidencias: {
          "Falta de Personal": 10,
          "Falla de Maquina": 20,
          "Falta de Material": 30,
          "Falta de Espacio": 50,
          "Limpieza de Area": 60,
          "Cambio de Producto": 80
        },
				donut: {
          labels: ["Matutino", "Vespertino", "Nocturno"],
          series: [12000, 3000, 2000]
        },
				gauge: {
					label: "Avance",
					value: 67 // porcentaje de avance (0–100)
				},
				production: [
					{ date: "1-septiembre", goal: 500, real: 480 },
					{ date: "2-septiembre", goal: 510, real: 505 },
					{ date: "3-septiembre", goal: 520, real: 515 },
					{ date: "4-septiembre", goal: 530, real: 525 },
					{ date: "5-septiembre", goal: 540, real: 532 },
					{ date: "6-septiembre", goal: 550, real: 545 },
					{ date: "7-septiembre", goal: 560, real: 552 },
					{ date: "8-septiembre", goal: 570, real: 565 },
					{ date: "9-septiembre", goal: 580, real: 578 },
					{ date: "10-septiembre", goal: 590, real: 583 },
					{ date: "11-septiembre", goal: 600, real: 595 },
					{ date: "12-septiembre", goal: 610, real: 605 },
					{ date: "13-septiembre", goal: 620, real: 618 },
					{ date: "14-septiembre", goal: 630, real: 627 },
					{ date: "15-septiembre", goal: 640, real: 639 },
					{ date: "16-septiembre", goal: 650, real: 645 },
					{ date: "17-septiembre", goal: 660, real: 652 },
					{ date: "18-septiembre", goal: 670, real: 665 },
					{ date: "19-septiembre", goal: 680, real: 675 },
					{ date: "20-septiembre", goal: 690, real: 688 },
					{ date: "21-septiembre", goal: 700, real: 698 },
					{ date: "22-septiembre", goal: 710, real: 703 },
					{ date: "23-septiembre", goal: 720, real: 718 },
					{ date: "24-septiembre", goal: 730, real: 725 },
					{ date: "25-septiembre", goal: 740, real: 739 },
					{ date: "26-septiembre", goal: 750, real: 745 },
					{ date: "27-septiembre", goal: 760, real: 758 }
				]
      },
      {
        id: "OF-2025-002",
        status: "EN PROGRESO",
        total_piezas_fabricadas: 12000,
        operadores_requeridos: 35,
        piezas_faltantes: 15000,
        duracion_total: { dias: 4, horas: 5, minutos: 10 },
        piezas_totales: 27000,
        tiempo_efectivo: { horas: 70, porcentaje: 90 },
        scrap: { porcentaje: 5 },
        incidencias_total: { total: 2 },
        tiempo_muerto: { horas: 2, minutos: 10 },
				incidencias: {
          "Falta de Personal": 85,
          "Falla de Maquina": 60,
          "Falta de Material": 45,
          "Falta de Espacio": 30,
          "Limpieza de Area": 70,
          "Cambio de Producto": 90
        },
				donut: {
          labels: ["Producción", "Rechazo", "Retrabajo"],
          series: [6000, 2000, 1000]
        },
				gauge: {
					label: "Avance",
					value: 55 // porcentaje de avance (0–100)
				},
				production: [
					{ date: "1-septiembre", goal: 400, real: 480 },
					{ date: "2-septiembre", goal: 410, real: 404 },
					{ date: "3-septiembre", goal: 420, real: 414 },
					{ date: "4-septiembre", goal: 430, real: 424 },
					{ date: "5-septiembre", goal: 440, real: 432 },
					{ date: "6-septiembre", goal: 40, real: 444 },
					{ date: "7-septiembre", goal: 460, real: 42 },
					{ date: "8-septiembre", goal: 470, real: 464 },
					{ date: "9-septiembre", goal: 480, real: 478 },
					{ date: "10-septiembre", goal: 490, real: 483 },
					{ date: "11-septiembre", goal: 500, real: 494 },
					{ date: "12-septiembre", goal: 510, real: 504 },
					{ date: "13-septiembre", goal: 520, real: 518 },
					{ date: "14-septiembre", goal: 530, real: 527 },
					{ date: "15-septiembre", goal: 540, real: 539 },
					{ date: "16-septiembre", goal: 540, real: 544 },
					{ date: "17-septiembre", goal: 550, real: 542 },
					{ date: "18-septiembre", goal: 570, real: 554 },
					{ date: "19-septiembre", goal: 580, real: 574 },
					{ date: "20-septiembre", goal: 590, real: 588 },
					{ date: "21-septiembre", goal: 700, real: 698 },
					{ date: "22-septiembre", goal: 710, real: 703 },
					{ date: "23-septiembre", goal: 720, real: 718 },
					{ date: "24-septiembre", goal: 730, real: 724 },
					{ date: "25-septiembre", goal: 740, real: 739 },
					{ date: "26-septiembre", goal: 740, real: 745 },
					{ date: "27-septiembre", goal: 760, real: 758 }
				]
      },
      {
        id: "OF-2025-003",
        status: "FINALIZADO",
        total_piezas_fabricadas: 30000,
        operadores_requeridos: 50,
        piezas_faltantes: 0,
        duracion_total: { dias: 6, horas: 4, minutos: 45 },
        piezas_totales: 30000,
        tiempo_efectivo: { horas: 120, porcentaje: 97 },
        scrap: { porcentaje: 3 },
        incidencias_total: { total: 5 },
        tiempo_muerto: { horas: 4, minutos: 30 },
				incidencias: {
          "Falta de Personal": 40,
          "Falla de Maquina": 75,
          "Falta de Material": 60,
          "Falta de Espacio": 25,
          "Limpieza de Area": 50,
          "Cambio de Producto": 20
        },
				donut: {
          labels: ["Producción", "Rechazo", "Retrabajo"],
          series: [18000, 3000, 2000]
        },
				gauge: {
					label: "Avance",
					value: 37 // porcentaje de avance (0–100)
				},
				production: [
					{ date: "1-septiembre", goal: 300, real: 380 },
					{ date: "2-septiembre", goal: 310, real: 303 },
					{ date: "3-septiembre", goal: 320, real: 313 },
					{ date: "4-septiembre", goal: 330, real: 323 },
					{ date: "5-septiembre", goal: 330, real: 332 },
					{ date: "6-septiembre", goal: 30, real: 333 },
					{ date: "7-septiembre", goal: 360, real: 32 },
					{ date: "8-septiembre", goal: 370, real: 363 },
					{ date: "9-septiembre", goal: 380, real: 378 },
					{ date: "10-septiembre", goal: 390, real: 383 },
					{ date: "11-septiembre", goal: 400, real: 393 },
					{ date: "12-septiembre", goal: 410, real: 403 },
					{ date: "13-septiembre", goal: 420, real: 418 },
					{ date: "14-septiembre", goal: 430, real: 427 },
					{ date: "15-septiembre", goal: 430, real: 439 },
					{ date: "16-septiembre", goal: 430, real: 433 },
					{ date: "17-septiembre", goal: 40, real: 432 },
					{ date: "18-septiembre", goal: 470, real: 43 },
					{ date: "19-septiembre", goal: 480, real: 473 },
					{ date: "20-septiembre", goal: 490, real: 588 },
					{ date: "21-septiembre", goal: 600, real: 698 },
					{ date: "22-septiembre", goal: 610, real: 603 },
					{ date: "23-septiembre", goal: 620, real: 618 },
					{ date: "24-septiembre", goal: 630, real: 623 },
					{ date: "25-septiembre", goal: 630, real: 639 },
					{ date: "26-septiembre", goal: 630, real: 635 },
					{ date: "27-septiembre", goal: 760, real: 758 }
				]
      }
    ];

    // Populate select options
    const select = document.getElementById("ordenSelect");
    ordenes.forEach(o => {
      const opt = document.createElement("option");
      opt.value = o.id;
      opt.textContent = o.id + " (" + o.status + ")";
      select.appendChild(opt);
    });

    // Function to render data
    const renderOrdenBoxes = (orden) => {
      document.getElementById("fabricadas").textContent = orden.total_piezas_fabricadas;
      document.getElementById("operadores").textContent = orden.operadores_requeridos;
      document.getElementById("faltantes").textContent = orden.piezas_faltantes;
      document.getElementById("duracion").innerHTML = `<p>${orden.duracion_total.dias} dias </p><p>${orden.duracion_total.horas} horas </p><p>${orden.duracion_total.minutos} minutos</p>`;
      document.getElementById("totales").textContent = orden.piezas_totales;
      document.getElementById("tiempoEfectivo").textContent = orden.tiempo_efectivo.horas + " HRS";
      document.getElementById("porcentajeEfectivo").textContent = orden.tiempo_efectivo.porcentaje + "%";
      document.getElementById("scrap").textContent = orden.scrap.porcentaje + "%";
      document.getElementById("incidencias").textContent = orden.incidencias_total.total;
      document.getElementById("tiempoMuerto").textContent = `${orden.tiempo_muerto.horas}h ${orden.tiempo_muerto.minutos}m`;
    };

    // Handle change event
    select.addEventListener("change", (e) => {
      const orden = ordenes.find(o => o.id === e.target.value);

      if (!orden) return;

			if (radialChart) {
				radialChart.destroy();
			}

			radialChart = new ApexCharts(radialChartEl, renderRadial(orden));
			radialChart.render();

			if (gaugeChart) {
				gaugeChart.destroy();
			}

			gaugeChart = new ApexCharts(gaugeChartEl, renderGauge(orden));
			gaugeChart.render().then(() => {
				placeGaugeTicks(gaugeChart);
			});


    	donutChart.updateOptions(renderDonut(orden), true, true);
    	// radialChart.updateOptions(renderRadial(orden), true, true);
    	// gaugeChart.updateOptions(renderGauge(orden), true, true);
			lineChart.updateOptions(renderLine(orden), true, true, true);

			const num_orden_fab = document.querySelector("#num_orden_fab");
			num_orden_fab.innerHTML = orden.id;


			renderOrdenBoxes(orden);
    });

    // Load first by default
    renderOrdenBoxes(ordenes[0]);


  </script>

	  <script>



  const renderRadial = (orden) => {
		const labels = Object.keys(orden.incidencias);
    const values = Object.values(orden.incidencias);

    return {
      chart: {
        type: "radialBar",
        height: chart_radial_height,
        animations: { enabled: true }
      },
      series: values,
      labels: labels,
			colors: [
				"hsl(151,81.4%,44.5%)",
				"#1DA97D",
				"hsl(151,81.4%,34.5%)",
				"hsl(151,81.4%,27.5%)",
				"#138C66",
				"#0A664D"
			],
      plotOptions: {
        radialBar: {
          startAngle: 0,
          endAngle: 270,
          hollow: { size: "30%" },
          track: { background: "#ddd" },
          // dataLabels: { name: { show: true }, value: { show: true } },
					dataLabels: {
						name: { show: false }, // ❌ Hide label text
						value: {
							show: true,          // ✅ Only show the value
							formatter: (val) => `${val}%` // add % suffix
						}
					},
          endShape: "rounded"
        }
      },
      legend: {
        show: true,
        position: "left",
        offsetX: 260,
        offsetY: -30,
				fontSize: 11
      },
			stroke: {
    		lineCap: "round",
			},
      states: {
        hover: { filter: { type: "none" } },
        active: { filter: { type: "none" } }
      }
    };
  };



    let radialChart = new ApexCharts(radialChartEl, renderRadial(ordenes[0]));
    // Load first by default
    radialChart.render().then(() => {
      placeRadialTicks(radialChart);
    });




		    // -------------------------------
    // Función para colocar ticks 0–100%
    // -------------------------------
    function placeRadialTicks(chartInstance, ticks = [0,20,40,60,80,100]) {
      const root = chartInstance.el;
      const svg = root.querySelector("svg");
      if (!svg) return;

      const inner = svg.querySelector(".apexcharts-inner");
      const t = inner?.getAttribute("transform") || "translate(0,0)";
      const m = /translate\(([-\d.]+),\s*([-\d.]+)\)/.exec(t);
      const tx = m ? parseFloat(m[1]) : 0;
      const ty = m ? parseFloat(m[2]) : 0;

      const paths = [...svg.querySelectorAll(".apexcharts-radialbar-area")];
      if (!paths.length) return;

      let maxR = 0, cx = 0, cy = 0, maxStroke = 0;
      paths.forEach(p => {
        const d = p.getAttribute("d") || "";
        const m1 = /M\s*([-\d.]+)\s+([-\d.]+)/.exec(d);
        const a1 = /A\s*([-\d.]+)\s+([-\d.]+)/.exec(d);
        if (!m1 || !a1) return;
        const cxPath = parseFloat(m1[1]);
        const topY   = parseFloat(m1[2]);
        const r      = parseFloat(a1[1]);
        const cyPath = topY + r;
        const sw     = parseFloat(p.getAttribute("stroke-width")) || 0;
        if (r > maxR) { maxR = r; cx = cxPath; cy = cyPath; maxStroke = sw; }
      });

      const cfg = chartInstance.w.config.plotOptions.radialBar;
      const startAngle = cfg?.startAngle ?? 0;
      const endAngle   = cfg?.endAngle ?? 360;

      const gap = (svg.viewBox?.baseVal?.height || chart_radial_height) * 0.04;
      const labelRadius = maxR + (maxStroke / 2) + gap;

      const toRad = deg => (deg * Math.PI) / 180;
      const angleFor = (percent) => {
        const a = startAngle + (endAngle - startAngle) * (percent / 100);
        return toRad(a - 90);
      };

      const fontSize = Math.round((svg.viewBox?.baseVal?.height || chart_radial_height) * 0.025) + "px";

      const texts = ticks.map(t => {
        const a = angleFor(t);
        return {
          text: `${t}%`,
          x: cx + tx + labelRadius * Math.cos(a) -10, //update on height change
          y: cy + ty + labelRadius * Math.sin(a),
          style: { fontSize }
        };
      });

      chartInstance.updateOptions({ annotations: { texts } }, false, true, false);
    }




  const renderDonut = (orden) => {
		const _labels = orden.donut.labels;
    const _series = orden.donut.series;

    return {
      chart: { type: "donut", height: chart_donut_height },
      series: _series,
      labels: _labels,
			colors: ["hsl(193,99.2%,50.2%)", "hsl(193,99.2%,25.2%)", "hsl(193,99.2%,38.2%)"],
      plotOptions: {
        pie: {
          donut: {
            size: "65%",
            labels: {
              show: true,
              name: { fontSize: "18px", offsetY: -10 },
              value: { 
								show: true,
            		formatter: (val) => val,
								fontSize: "22px", 
								offsetY: 10
								
							},
              total: {
                show: true,
                label: "Total",
                formatter: (w) => w.globals.seriesTotals.reduce((a,b) => a+b, 0)
              }
            }
          }
        }
      },
			dataLabels: {
				formatter: (val, opts) => opts.w.config.series[opts.seriesIndex] // 👈 valores en las porciones
			},
      legend: { 
				position: "top",
				// offsetX: 10,
				formatter: (seriesName, opts) => {
					return seriesName + ": " + opts.w.globals.series[opts.seriesIndex];
				}
			}
    };
  };

	const donutChart = new ApexCharts(donutChartEl, renderDonut(ordenes[0]));
	// Load first by default
	donutChart.render();



const renderGauge = (orden) => {
  return {
		  	series: [orden.gauge.value],
				labels: [orden.gauge.label],
        chart: {
					height: chart_gauge_height,
          type: 'radialBar',
          offsetY: -20,
          sparkline: {
            enabled: true
          },
					animations: {
						enabled: true,
						easing: 'easeout',
						speed: 1200,       // duration of animation in ms
						animateGradually: {
							enabled: true,
							delay: 200       // delay between animations if multiple series
						},
						dynamicAnimation: {
							enabled: true,
							speed: 1000      // smooth animation when series updates
						}
					},
        },
        plotOptions: {
          radialBar: {
            startAngle: -90,
            endAngle: 90,
            track: {
              background: "#e7e7e7",
              strokeWidth: '97%',
              margin: 10, // margin is in pixels
              dropShadow: {
                enabled: true,
                top: 2,
                left: 0,
                color: '#555',
                opacity: 1,
                // blur: 2
              }
            },
            dataLabels: {
              name: {
                show: false
              },
              value: {
                offsetY: -2,
                fontSize: '22px'
              }
            }
          }
        },
        grid: {
          padding: {
            top: -10
          }
        },
        fill: {
					colors: ["hsl(151,81.4%,34.5%)"],
          // type: 'gradient',
          // gradient: {
          //   shade: 'light',
          //   shadeIntensity: 0.4,
          //   inverseColors: false,
          //   opacityFrom: 1,
          //   opacityTo: 1,
          //   stops: [0, 50, 53, 91]
          // },
        }

  };
};




function placeGaugeTicks(chartInstance, ticks = [0,10,20,30,40,50,60,70,80,90,100]) {
  const root = chartInstance.el;
  const svg = root.querySelector("svg");
  if (!svg) return;

  const inner = svg.querySelector(".apexcharts-inner");
  const t = inner?.getAttribute("transform") || "translate(0,0)";
  const m = /translate\(([-\d.]+),\s*([-\d.]+)\)/.exec(t);
  const tx = m ? parseFloat(m[1]) : 0;
  const ty = m ? parseFloat(m[2]) : 0;

  const path = svg.querySelector(".apexcharts-radialbar-area");
  if (!path) return;

  const d = path.getAttribute("d") || "";
  const m1 = /M\s*([-\d.]+)\s+([-\d.]+)/.exec(d);
  const a1 = /A\s*([-\d.]+)\s+([-\d.]+)/.exec(d);
  if (!m1 || !a1) return;

  const startX = parseFloat(m1[1]);
  const topY = parseFloat(m1[2]);
  const r = parseFloat(a1[1]);
  const sw = parseFloat(path.getAttribute("stroke-width")) || 0;

  // Real center of the semicircle: shift horizontally to chart middle
  const vbWidth = svg.viewBox?.baseVal?.width || chart_gauge_height;
  const cx = vbWidth / 2;      // center of viewBox
  const cy = topY + r - r;     // lifted center (top of semi)

  // Arc WEST(0%) → NORTH(50%) → EAST(100%)
  const startAngle = 180;
  const endAngle   = 0;

  const gap = (svg.viewBox?.baseVal?.height || chart_gauge_height) * 0.05;
  const labelRadius = r + (sw / 2) + gap;

  const toRad = deg => (deg * Math.PI) / 180;
  const angleFor = (percent) => {
    const a = startAngle + (endAngle - startAngle) * (percent / 100);
    return toRad(a);
  };

  const fontSize = Math.round((svg.viewBox?.baseVal?.height || chart_gauge_height) * 0.03) + "px";

  const texts = ticks.map(t => {
    const a = angleFor(t);
    return {
      text: `${t}%`,
      x: cx + tx + labelRadius * Math.cos(a) -35, // update on change height
      y: cy + ty - labelRadius * Math.sin(a),
      style: { fontSize }
    };
  });

  chartInstance.updateOptions({ annotations: { texts } }, false, true, false);
}




let gaugeChart = new ApexCharts(gaugeChartEl, renderGauge(ordenes[0]));
gaugeChart.render().then(() => {
  placeGaugeTicks(gaugeChart);
});







function generateDaysOfMonth() {
    const now = moment(); // Get current date using moment.js
    const daysInMonth = now.daysInMonth(); // Get number of days in current month
    const days = [];
    const days_w_month = [];

    const monthName = now.format('MMMM');

    for (let i = 1; i <= daysInMonth; i++) {
        const day = moment().date(i).format('DD');
        days.push(day);
				const day_month = moment().date(i); 
				days_w_month.push(`${i}-${day_month.format('MMMM')}`); 
    }

    return [days, days_w_month, monthName];
}

// function generateDaysOfMonth() {
// 		const now = moment(); // Get current date using moment.js
// 		const daysInMonth = now.daysInMonth(); // Get number of days in current month
// 		const days = [];

// 		// Loop through each day in the month
// 		for (let i = 1; i <= daysInMonth; i++) {
// 				const day = moment().date(i); // Set the day to the 'i' day of the current month
// 				// days.push(`${i}-${day.format('MMMM')}`); // Format month in full (e.g., June)
// 				days.push(`${i}`); // Format month in full (e.g., June)
// 		}

// 		return days;
// }



function renderLine(orden) {
  const allDays = generateDaysOfMonth();
	console.log(allDays)

  const productionGoal = allDays[1].map(day => {
    const data = orden.production.find(d => d.date === day);
    return data ? data.goal : null;
  });

  const realProduction = allDays[1].map(day => {
    const data = orden.production.find(d => d.date === day);
    return data ? data.real : null;
  });

  return {
    series: [
      {
        name: 'Meta de Producción',
        data: productionGoal,
      },
      {
        name: 'Producción Real',
        data: realProduction,
      }
    ],
    chart: {
      height: chart_line_height,
      type: 'line',
      zoom: { enabled: false },
    },
    xaxis: {
			// title: { text: 'Días del Mes' }
      // title: { text: allDays[2] }
      categories: allDays[0],

    },
    yaxis: {
      // title: { text: 'Kg' },
      labels: {
        formatter: (val) => `${Math.round(val)} Kg`
      }
    },
    stroke: {
      width: 2,
      curve: 'smooth'
    },
    markers: {
      size: 3,
      colors: ['hsl(44,100%,45.9%)', 'hsl(151,81.4%,27.8%)'],
    },
    tooltip: {
			
			// custom: function({series, seriesIndex, dataPointIndex, w}) {
			//     const customElement = document.createElement('div')
			//     customElement.style.padding = '10px'
			//     customElement.innerHTML = 'My custom Tooltip: ' + series[seriesIndex][dataPointIndex]

			//     return customElement
			// }


				shared: true,
				intersect: false,
				y: { 
						formatter: (val) => `${Math.round(val)} Kg`,
				},
				x: {
						show: true,
						formatter: (val) => `${Math.round(val)} - ${allDays[2]}` 
				},
    },
    colors: ['hsl(44,100%,45.9%)', 'hsl(151,81.4%,27.8%)'],
    legend: {
			show: true,
      position: 'top',
      horizontalAlign: 'center',
			fontSize: '16px',
      labels: {
          colors: undefined,
          useSeriesColors: false
      }
    }
  };
}

const lineChart = new ApexCharts(lineChartEl, renderLine(ordenes[0]));
lineChart.render();




  </script>
	




</body>
</html>
