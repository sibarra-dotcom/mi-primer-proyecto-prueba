<?php echo view('_partials/header'); ?>
	<link rel="stylesheet" href="<?= base_url('_partials/inspeccion.css') ?>">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/Modal.min.js') ?>"></script>

  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">

	<div class="relative flex flex-col gap-y-2 items-center justify-center font-titil ">

  	<?php echo view('produccion/_partials/navbar'); ?>

		<section class=" w-full pt-3 pb-6 px-4 flex flex-col gap-y-4">

			<!-- row search -->
			<div class="flex justify-between items-center text-gray text-sm">
				<div class="relative flex flex-1 justify-center items-start gap-4 text-title">
					<button id="fullscreen-btn" class="absolute top-0 left-0 text-lg focus:outline-none active:outline-none px-2 border border-icon hover:text-white hover:bg-title ">
						<i class="fas fa-expand"></i>
					</button>

					<div class="flex flex-col items-center justify-start">
					<div class=" leading-none text-2xl font-semibold">Dashboard Orden de Fabricación</div>

					<span id="desc_articulo" class="text-super leading-none text-xl font-semibold"></span>
					<div class="flex gap-4">
						<span id="num_orden_fab" class="text-dark leading-none text-base font-semibold"></span> - 
						<span id="omg" class="text-dark leading-none text-base font-semibold"></span>
					</div>

					</div>

				</div>

				<div class="flex gap-4 items-center ">

					<div class="relative flex flex-col w-44 text-base">
						<input type="text" id="n_orden" class="to_uppercase h-8 w-full text-center  bg-grayLight focus:outline-none border border-icon " placeholder="# Orden Fab." required>
						<ul id="lista_ordenfab" class="absolute z-40 top-8 bg-grayLight border-b border-l border-r border-icon w-full h-32 overflow-y-auto"></ul>
					</div>


					<button id="btn_search_orden" class="btn btn-sm btn--cta" type="button"><i class="fas fa-search"></i><span>BUSCAR</span></button>
				</div>
			</div>

			<!-- Row fechas resumen -->
			<div class="flex w-full gap-4 text-sm">
				<div class="w-1/2 flex items-center justify-between gap-4">
					<div class="flex-1 flex justify-center gap-2 bg-title text-white py-1 px-3 items-center">
						<p>Fecha Arranque:</p>
						<p id="fecha-arranque"></p>
					</div>

					<div class="flex-1 flex justify-center gap-2 bg-gray bg-opacity-70 text-white py-1 px-3 items-center">
						<p>Fecha Compromiso:</p>
						<p id="fecha-compromiso"></p>
					</div>

					<div id="fecha-termino-container" class="flex-1 flex justify-center gap-2 bg-gray bg-opacity-70 text-white py-1 px-3 items-center">
						<p>Fecha Termino:</p>
						<p id="fecha-termino"></p>
					</div>
				</div>

				<div class="w-1/2 flex items-center gap-4">
					<div id="diferencia-compromiso-container" class="flex-1 flex gap-2 justify-center bg-gray bg-opacity-70 text-white py-1 px-3 items-center">
						<p>Dif. Fecha Término y Compromiso:</p>
						<p id="diferencia-compromiso"></p>
					</div>

					<div id="status-orden-container" class="flex-1 flex gap-2 justify-center bg-gray bg-opacity-70 text-white py-1 px-3 items-center">
						<p>Estatus de la orden:</p>
						<p id="status-orden"></p>
					</div>
				</div>
			</div>

			<div class="relative w-full flex gap-4">
				<div id="dash_overlay" class="absolute top-0 left-0 z-40 w-full h-full bg-white flex items-center justify-center mx-auto ">
					<div class="mt-32 mx-auto w-16 h-16 border-8 border-icon border-t-transparent rounded-full animate-spin"></div>
				</div>

				<div class="w-1/2 flex flex-col gap-4 ">

					<div class="relative w-full flex flex-col px-4 pt-4 pb-12 bg-white shadow-bottom-right ">
						<div class="transform -translate-x-[90px] ">
							<div id="donutChart"></div>
						</div>
						
						<h1 class="absolute bottom-2 left-1/2 -translate-x-1/2 w-full text-xl text-center text-title">Cantidad de Piezas Producidas por Turno</h1>
					</div>
				
					<div class="relative w-full flex flex-col px-2 pt-2 pb-4 bg-white shadow-bottom-right ">
						<div id="lineChart"></div>
						<h1 class="absolute bottom-2 left-1/2 -translate-x-1/2 w-full text-xl text-center text-title">Meta de Produccion vs Produccion Real</h1>
					</div>
				</div>

				<div class=" w-1/2 flex gap-4">
					<div class="flex-1 flex flex-col gap-4 ">

						<div class="w-full flex flex-col pt-8 px-4 pb-2 bg-white shadow-bottom-right ">
							<div id="gaugeChart" class="w-[350px] mx-auto"></div>
							<h1 class="pt-8 text-xl text-center text-title">Porcentaje Terminado</h1>
						</div>
					
						<div class="relative w-full flex flex-col pt-8 pb-10 px-4 bg-white shadow-bottom-right ">
							<div class="">
								<div id="barChart"></div>
							</div>
							<h1 class="absolute bottom-2 left-1/2 -translate-x-1/2 w-full text-xl text-center text-title">Distribución de Incidentes</h1>

						</div>

					</div>


					<div class="w-1/3 flex">

						<div class="w-full flex flex-col gap-4">

							<div class="w-full flex gap-4">
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-title text-white shadow-bottom-right"> 
									<p class="text-xl font-bold " id="fabricadas">0</p>
									<p class="text-sm text-center">Total Piezas <br>fabricadas</p>
								</div>
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-primaryLight text-white shadow-bottom-right"> 
									<p class="text-2xl font-bold " id="operadores">0</p>
									<p class="text-sm text-center">Operadores requeridos</p>
								</div>
							</div>

							<div class="w-full flex gap-4">
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-gray text-white shadow-bottom-right"> 
									<p class="text-xl font-bold " id="faltantes">0</p>
									<p class="text-sm text-center">Piezas faltantes</p>
								</div>
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 items-center bg-warning text-white shadow-bottom-right"> 
									<div class="text-sm flex flex-col " id="duracion">0</div>
									<p class="text-sm text-center">Duración total</p>
								</div>
							</div>

							<div class="w-full flex gap-4">
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-gray bg-opacity-80 text-white shadow-bottom-right"> 
									<p class="text-lg font-bold " id="totales">0</p>
									<p class="text-sm text-center">Piezas totales O.F.</p>
								</div>
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-success text-white shadow-bottom-right"> 
									<p class="text-xl font-bold " id="tiempoEfectivo">0</p>
									<p class="text-sm text-center">Tiempo efectivo</p>
								</div>
							</div>

							<div class="w-full flex gap-4">
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-itg bg-opacity-80 text-white shadow-bottom-right"> 
									<p class="text-2xl font-bold " id="scrapt">0</p>
									<p class="text-sm text-center">TDB</p>
								</div>
								<div class="w-1/2 h-28 flex flex-col p-2 gap-2 justify-center items-center bg-twt text-white shadow-bottom-right"> 
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
									<p class="text-xl font-bold " id="tiempoMuerto"></p>
									<p class="text-sm text-center">Tiempo muerto</p>
								</div>
							</div>

						</div>

					</div>
		
				</div>
			</div>


		</section>

	</div>

<!-- Modal warning -->
<div id="modal_warning" class="modal modal-md ">
	<div class="modal-content">
		<div class="modal-body">
			
			<div class="flex flex-col text-warning text-4xl text-center py-32 ">
				<span><i class="fas fa-warning"></i></span>
				<span>No results found.</span>
			</div>

			<div class="flex w-full justify-center  ">
				<button data-dismiss="modal" class="modal-btn--cancel" type="button">
					ACEPTAR
				</button>
			</div>
		</div>

	</div>
</div>

<script>
Service.setLoading();

let donutChart;
let barChart;
let gaugeChart;
let lineChart;

const fullscreenBtn = document.querySelector('#fullscreen-btn');
const menu = document.querySelector('#menu')

const toggleFullscreen = () => {
	const isExpanded = fullscreenBtn.querySelector('i').classList.contains('fa-expand');

	if (isExpanded) {
		fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';
		menu.classList.add('hidden');
	} else {
		fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i>';
		menu.classList.remove('hidden');
	}
};

fullscreenBtn.addEventListener('click', toggleFullscreen);

const handleEscapeKey = (e) => {
	if (e.key === 'Escape' && !fullscreenBtn.querySelector('i').classList.contains('fa-expand')) {
		toggleFullscreen();
	}
};

document.addEventListener('keydown', handleEscapeKey);


const num_ordenes = <?= json_encode($ordenes) ?>;

const n_orden = document.getElementById('n_orden');
const lista_ordenfab = document.getElementById('lista_ordenfab');

n_orden.addEventListener('keyup', e => {
    const query = e.target.value.trim();

    if (query.length <= 2) {
        Service.hide('#lista_ordenfab');
        return;
    }

    const filteredOrdenes = num_ordenes.filter(orden => orden.toString().includes(query));

    lista_ordenfab.innerHTML = '';

    if (filteredOrdenes.length > 0) {
        filteredOrdenes.forEach(_orden => {
            const li = document.createElement('li');
            li.className = "text-center";
            li.textContent = _orden;
            li.style.cursor = 'pointer';
            li.addEventListener('click', () => {
                n_orden.value = _orden;
                Service.hide('#lista_ordenfab');
            });
            lista_ordenfab.appendChild(li);
        });
        Service.show('#lista_ordenfab');
    } else {
        Service.hide('#lista_ordenfab');
    }
});


document.addEventListener('click', (e) => {
	if (!lista_ordenfab.contains(e.target) && e.target !== n_orden) {
		Service.hide('#lista_ordenfab');
	}
});


const btn_search = document.querySelector('#btn_search_orden');
btn_search.addEventListener('click', e => {
	let orden_num = n_orden.value;

	Service.stopSubmit(e.target, true);
	// Service.show('.loading');
	Service.show('#dash_overlay');

	Service.exec('get', `ordenfab/get_resumen/${orden_num}`)
	.then( r => {
					
		Service.stopSubmit(e.target, false);
		// Service.hide('.loading');
		Service.hide('#dash_overlay');


		if(r.success){
			if (barChart) {
				barChart.destroy();
			}

			barChart = new ApexCharts(barChartEl, renderBar(r.orden));
			barChart.render();

			if (gaugeChart) {
				gaugeChart.updateSeries([r.orden.gauge.value]);
			}
			
    	donutChart.updateOptions(renderDonut(r.orden), true, true);
			// lineChart.updateOptions(renderLine(r.orden), true, true, true);

			if (lineChart) {
				lineChart.destroy();
			}

			lineChart = new ApexCharts(lineChartEl, renderLine(r.orden));
			lineChart.render();

			const num_orden_fab = document.querySelector("#num_orden_fab");
			num_orden_fab.innerHTML = r.orden.num_orden;

			renderOrdenBoxes(r.orden);
			renderFechasResumen(r.orden);

		} else {
			Modal.init("modal_warning").open();
		}

	});
});


const chart_bar_height = 284;
const chart_donut_height = 210;
const chart_gauge_height = 400;
const chart_line_height = 332;

const donutChartEl = document.querySelector("#donutChart");
const gaugeChartEl = document.querySelector("#gaugeChart");
const lineChartEl = document.querySelector("#lineChart");
const barChartEl = document.querySelector("#barChart");

const renderOrdenBoxes = (orden) => {
	document.getElementById("fabricadas").textContent = orden.total_piezas_fabricadas;
	document.getElementById("faltantes").textContent = orden.piezas_faltantes;
	document.getElementById("totales").textContent = orden.piezas_totales;
	document.getElementById("scrapt").textContent = orden.scrapt;
	document.getElementById("desc_articulo").textContent = orden.desc_articulo;
	document.getElementById("omg").textContent = orden.omg;
	document.getElementById("incidencias").textContent = orden.incidencias_total;

	document.getElementById("duracion").innerHTML = `<p>${orden.duracion_total.dias} dias </p><p>${orden.duracion_total.horas} horas </p><p>${orden.duracion_total.minutos} minutos</p>`;

	document.getElementById("tiempoEfectivo").textContent = orden.tiempo_efectivo.horas + " HRS";

	document.getElementById("operadores").textContent = orden.operadores_requeridos;

	document.getElementById("porcentajeEfectivo").textContent = orden.tiempo_efectivo.porcentaje + '%';

	document.getElementById("tiempoMuerto").textContent = `${orden.tiempo_muerto.horas}h ${orden.tiempo_muerto.minutos}m`;
};


const renderBar = (orden) => {
  const labels = Object.keys(orden.incidencias).map(toTitleCase);
  const values = Object.values(orden.incidencias);

  return {
    series: [{
      data: values
    }],
    chart: {
      type: 'bar',
      height: chart_bar_height,
      toolbar: { show: true }
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        borderRadiusApplication: 'end',
        horizontal: true,
				distributed: true 
      }
    },
    dataLabels: {
      enabled: true,
      style: { fontSize: '14px' }
    },
    xaxis: {
      categories: labels,
      min: 0,
      max: 5,
      tickAmount: 5,
    },
    tooltip: {
      enabled: false
    },
    colors: [
      "#003F2A",
			"#054C3A",
			"#0A664D",
			"#138C66", 
			"#1DA97D", 
			"#26B588", 
		],
    grid: { borderColor: '#eee' },
    legend: { show: false }
  };
};
 

const renderDonut = (orden) => {
	const _labels = orden.donut.labels;
	const _series = orden.donut.series;

	const total = _series.reduce((a, b) => a + b, 0);

	return {
		chart: { 
			type: "donut", 
			height: chart_donut_height,
			toolbar: {
      	show: true
    	}
		},
		series: _series,
		labels: _labels,
		colors: [
				"hsl(193,99.2%,33.2%)", 
				"hsl(193,99.2%,21.2%)", 
				"hsl(193,99.2%,11.2%)"
		],
		plotOptions: {
			pie: {
				donut: {
					size: "50%",
					labels: {
						show: true,
						name: { fontSize: "16px", offsetY: -10 },
						value: { 
							show: true,
							formatter: (val) => formatNumberMex(parseFloat(val),0) + " PZ",
							fontSize: "15px", 
							offsetY: -5
						},
						total: {
							show: true,
							label: "Total",
							formatter: (w) => {
									const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
									return `${formatNumberMex(total,0)} PZ`;
							}
						}
					}
				}
			}
		},
		dataLabels: {
			// enabled: false, 
			formatter: (val, opts) => opts.w.config.series[opts.seriesIndex]
		},
		tooltip: {
				enabled: false,
		},
		legend: { 
			position: "left",
			offsetX: 120,
			formatter: (seriesName, opts) => {
				const value = opts.w.globals.series[opts.seriesIndex];
				const percentage = ((value / total) * 100).toFixed(1);
				return `
					<div class="flex gap-x-1">
						<div class="w-16">${seriesName}</div>
						<span>${percentage}%</span>
						<span> [ ${formatNumberMex(value,0)} PZ ] </span>
					</div>

				`;
			}
		}
	};
};


const renderGauge = (orden) => {
  return {
		  	series: [orden.gauge.value],
				// labels: [orden.gauge.label],
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

const renderFechasResumen = (orden) => {
  // Fecha arranque
  document.querySelector("#fecha-arranque").textContent = orden.fecha_arranque;

  // Fecha compromiso
  document.querySelector("#fecha-compromiso").textContent = orden.fecha_compromiso;

  // Fecha termino
  const fechaTerminoContainer = document.querySelector("#fecha-termino-container");
	let bg_color = orden.fecha_termino.color == 'dark' ? `bg-${orden.fecha_termino.color}` : `bg-opacity-70 bg-${orden.fecha_termino.color}`
  fechaTerminoContainer.className = `flex-1 flex justify-center gap-2 text-white py-1 px-3 items-center ${bg_color}`;
  document.querySelector("#fecha-termino").textContent = orden.fecha_termino.fecha;

  // Diferencia compromiso vs termino
  const diffContainer = document.querySelector("#diferencia-compromiso-container");
  diffContainer.className = `flex-1 flex gap-2 justify-center text-white py-1 px-3 items-center bg-${orden.diferencia_compromiso.color}`;
  document.querySelector("#diferencia-compromiso").textContent = orden.diferencia_compromiso.time;

  // Estatus de la orden
  const statusContainer = document.querySelector("#status-orden-container");
  statusContainer.className = `flex-1 flex gap-2 justify-center text-white py-1 px-3 items-center bg-${orden.status_orden.color}`;
  document.querySelector("#status-orden").textContent = orden.status_orden.status;
};

const computeYAxis = (production) => {
  const values = production.flatMap(d => [d.goal, d.real]).filter(v => v !== null);

  if (!values.length) return { min: 0, max: 100, tickAmount: 5 };

  const minVal = Math.min(...values);
  const maxVal = Math.max(...values);

  // Range of values
  const range = maxVal - minVal;

  // ✅ Pick step size dynamically
  let step;
  if (range <= 1000) step = 100;
  else if (range <= 5000) step = 500;
  else if (range <= 20000) step = 1000;
  else if (range <= 50000) step = 5000;
  else step = 10000;

  // Add offset (10% of range, at least one step)
  const offset = Math.max(Math.round(range * 0.1), step);

  const newMin = Math.max(0, Math.floor((minVal - offset) / step) * step);
  const newMax = Math.ceil((maxVal + offset) / step) * step;

  // Tick amount → max 10 ticks
  const tickAmount = Math.min(10, Math.round((newMax - newMin) / step));

  return { min: newMin, max: newMax, tickAmount };
};



const renderLine = (orden) => {
  const allDays = generateDaysOfMonth();

  const productionGoal = allDays[1].map(day => {
    const data = orden.production.find(d => d.date === day);
    return data ? data.goal : null;
  });

  const realProduction = allDays[1].map(day => {
    const data = orden.production.find(d => d.date === day);
    return data ? data.real : null;
  });

  // ✅ Compute y-axis dynamically
  const yAxis = computeYAxis(orden.production);

  return {
    series: [
      { name: 'Meta de Producción', data: productionGoal },
      { name: 'Producción Real', data: realProduction }
    ],
    chart: { height: chart_line_height, type: 'line', zoom: { enabled: false } },
    xaxis: { categories: allDays[0] },
    yaxis: {
      min: yAxis.min,
      max: yAxis.max,
      tickAmount: yAxis.tickAmount,
      labels: {
        formatter: (val) => `${formatNumberMex(Math.round(val), 0)} PZ`
      }
    },
    stroke: { width: 2, curve: 'smooth' },
    markers: { size: 3, colors: ['hsl(44,100%,45.9%)', 'hsl(151,81.4%,27.8%)'] },
    tooltip: {
      shared: true,
      intersect: false,
      y: { formatter: (val) => `${formatNumberMex(Math.round(val), 0)} PZ` },
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
      fontSize: '16px'
    }
  };
};


document.addEventListener('DOMContentLoaded', () => {
	Service.show('#dash_overlay');
	Service.exec('get', `ordenfab/get_last`)
	.then( r => {

		if(r.success){
			Service.hide('#dash_overlay');

			barChart = new ApexCharts(barChartEl, renderBar(r.orden));
			barChart.render();

			donutChart = new ApexCharts(donutChartEl, renderDonut(r.orden));
			donutChart.render();

			gaugeChart = new ApexCharts(gaugeChartEl, renderGauge(r.orden));
			gaugeChart.render().then(() => {
				placeGaugeTicks(gaugeChart);
			});

			lineChart = new ApexCharts(lineChartEl, renderLine(r.orden));
			lineChart.render();

			const num_orden_fab = document.querySelector("#num_orden_fab");
			num_orden_fab.innerHTML = r.orden.num_orden;

			renderOrdenBoxes(r.orden);
			renderFechasResumen(r.orden);

		} else {
			Modal.init("modal_warning").open();
		}
	});
});


const generateDaysOfMonth = () => {
    const now = moment();
    const daysInMonth = now.daysInMonth();
    const days = [];
    const days_w_month = [];

    const year = now.format('YYYY');
    const month = now.format('MM');

    for (let i = 1; i <= daysInMonth; i++) {
        const day = String(i).padStart(2, '0');
        days.push(day);

        const day_month = `${year}-${month}-${day}`;
        days_w_month.push(day_month);
    }

    return [days, days_w_month, month];
}

const toTitleCase = str =>
  str
    .toLowerCase()
    .replace(/\b\w/g, char => char.toUpperCase());
		

/**
 * ticks 0–100% on gauge Chart
 */
const placeGaugeTicks = (chartInstance, ticks = [0,10,20,30,40,50,60,70,80,90,100]) => {
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

</script>
</body>
</html>
