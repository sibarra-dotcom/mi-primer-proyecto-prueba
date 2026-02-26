<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/Modal.min.js') ?>"></script>
  <script src="<?= load_asset('js/SearchOrder.min.js') ?>"></script>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">

  <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/locales/es.js"></script>

  <title><?= esc($title) ?></title>

</head>

<body class="relative min-h-screen">

	<div class="relative flex flex-col gap-y-2 items-center justify-center font-titil ">

  	<?php echo view('produccion/_partials/navbar'); ?>

		<!-- title -->
		<div class="relative text-title w-full py-2 px-4 lg:px-10 flex items-center justify-between ">

			<div class="hidden lg:flex gap-4 items-center absolute left-6 lg:left-10 top-1/2 -translate-y-1/2">
				<a href="<?= base_url('produccion/ordenes_lista') ?>" class="hover:scale-110 transition-transform duration-100 ">
					<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
				</a>
			</div>

			<div class="w-full flex items-center justify-start xl:justify-center">
				<h2 class="text-center font-semibold w-fit text-2xl lg:text-3xl "><?= esc($title) ?></h2>
			</div>

			<div class=" flex gap-4 items-center absolute right-4 lg:right-10 top-1/2 -translate-y-1/2">


			</div>
    </div>

		<!-- Main content -->
		<div class="w-full flex gap-4 items-start justify-center text-sm text-gray pl-4 pr-2" >
			<!-- col left - search -->
			<div class="col-left w-72 bg-grayMid rounded border border-grayMid p-2">
				<div class="flex flex-col gap-6">

					<div class="flex flex-col gap-2">
						<p>FECHA</p>
						<div id="datepicker"></div>
						<input type="hidden" id="dia_selected">
					</div>
				  

				</div>
  			
			</div>

			<!-- col right - table -->
			<div class="col-right w-full flex flex-col gap-4">
				<div class="col-table w-full flex flex-col gap-4">
					
					<div class="relative w-full h-[45vh] overflow-y-scroll mx-auto">
						<table id="tabla-reporte-meta-dia">
							<thead>
								<tr>
									<th>Planta</th>
									<th>Producto</th>
									<th>N° Or. Fab.</th>
									<th>Cant. Orden</th>
									<th>% Avanzado</th>
									<th>Meta de dia</th>
									<th>Total Prod.</th>
									<th>Total Merma</th>
									<th>% Cumplimiento</th>
								</tr>
							</thead>
							<tbody></tbody>

						</table>
						<div class="row--empty">No results found.</div>
					</div>
				</div>
				
				<form id="form_meta" class="modal-body">
					<div class="form-row-submit p-4">
						<button class=" modal-btn--submit" type="submit" >
						Guardar Cambios
						</button>
					</div>
				</form>

			</div>
		</div>


  </div>





<!-- Modal success -->
<div id="modal_success" class="modal modal-md ">
	<div class="modal-content">
		<div class="modal-body">
			
			<button type="button" data-dismiss="modal" class="modal-btn--close">&times;</button>

			<h3 class="text-title text-4xl text-center py-32 ">¡Enviado con éxito!</h3>

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

const getReporteMeta = (date) => {
	Service.hide('.row--empty');
	tbody.innerHTML = Service.loader();

	Service.exec('get', `produccion/reporte_meta/${date}`)
	.then(r => renderRows(r));  
}

const dia_selected = document.querySelector('#dia_selected');
dia_selected.value = moment().format('YYYY-MM-DD');

const datepicker_el = document.querySelector('#datepicker');

const datepicker = new Datepicker(datepicker_el, {
  language: 'es',
  maxView: 0,
  maxDate: moment().toDate(),

  datesDisabled: (date, viewId) => {

    if (viewId === 0) {
      const dateString = date.toISOString().split('T')[0];
      // return feriados.includes(dateString); // Disable holidays
    }
    return false;
  }
});


datepicker.element.addEventListener('changeDate', (e) => {
	// let turno_id = turnoId.value;
	// if (!turno_id) return;

  dia_selected.value = moment(e.detail.date).format('YYYY-MM-DD');
	getReporteMeta(dia_selected.value)
});


// const turnoId = document.querySelector('#turnoId');
// turnoId?.addEventListener('change', e => {
// 	let turno_id = e.target.value;
// 	if (!turno_id) return;

// 	getReporteMeta(turno_id, dia_selected.value)
// });




const initInputNumber = () => {
	const allInputToNumber = document.querySelectorAll('.input--number');

	allInputToNumber?.forEach(input => {
		input.addEventListener('input', e => {
			let value = e.target.value.replace(/[^0-9.]/g, '');

			const parts = value.split('.');
			if (parts.length > 2) {
				value = parts[0] + '.' + parts.slice(1).join('');
			}

			if (parts.length === 2) {
				value = parts[0] + '.' + parts[1].slice(0, 6);
			}

			input.value = value;
		});
	});

}

const tbody = document.querySelector('#tabla-reporte-meta-dia tbody');

const renderRows = (data) => {  
	tbody.innerHTML = "";

	if (!data.length) {
		Service.show('.row--empty');
		return;
	}

	data.forEach(cell => {
		const row = document.createElement('tr');

		row.innerHTML =
			`
				<td>
					<span>${cell.planta}</span>
				</td>
				<td>
					<span>${cell.producto}</span>
				</td>
				<td>
					<span>${cell.num_orden}</span>
				</td>
				<td>
					<span>${cell.cantidad_plan}</span>
				</td>
        <td>
					<input name="porcentaje_avanzado[]" type="text" class="text-center" readonly value="${getAvance(cell.cantidad_plan, cell.total_producido)}">
				</td>
        <td>
					<input name="meta_dia[]" value="${cell.meta_del_dia}" type="text" class="w-24 text-center input--number" required>
				</td>
				<td>
					<span>${cell.total_producido}</span>
				</td>
				<td>
					<span>${cell.total_merma}</span>
				</td>
        <td>
					<input name="cumplimiento[]" type="text" class="text-center" readonly>
				</td>
				<input type="hidden" name="ordenId[]" value="${cell.orden_fab_id}">
				<input type="hidden" name="fecha[]" value="${cell.fecha}">
			`
		tbody.appendChild(row);
	});


  addSummaryDiv();
	initRowEvents();
  updateTotalsSummary();
	initInputNumber();

}



// // Attach events to each row input
// const initRowEvents = () => {
//   const metaInputs = document.querySelectorAll('input[name="meta_dia[]"]');

//   metaInputs.forEach((input) => {
//     input.addEventListener("input", () => {
//       const row = input.closest("tr");
//       const cumplimientoInput = row.querySelector('input[name="cumplimiento[]"]');
//       const totalProd = parseInt(
//         row.querySelector("td:nth-child(7) span").textContent.replace(/[^\d]/g, ""),
//         10
//       ) || 0;
//       const meta = parseInt(input.value.replace(/[^\d]/g, ""), 10) || 0;

//       let cumplimiento = 0;
//       if (meta > 0) cumplimiento = Math.round((totalProd / meta) * 100);

//       cumplimientoInput.value = `${cumplimiento}%`;

//       // update totals whenever row changes
//       updateTotalsSummary();
//     });
//   });
// };

// Attach events to each row input
// const initRowEvents = () => {
//   const metaInputs = document.querySelectorAll('input[name="meta_dia[]"]');

//   metaInputs.forEach((input) => {
//     input.addEventListener("input", () => {
//       const row = input.closest("tr");
//       const cumplimientoInput = row.querySelector('input[name="cumplimiento[]"]');
//       const totalProd = parseInt(
//         row.querySelector("td:nth-child(7) span").textContent.replace(/[^\d]/g, ""),
//         10
//       ) || 0;

// 			// 
			
//       const meta = parseInt(input.value.replace(/[^\d]/g, ""), 10) || 0;

//       let cumplimiento = 0;
//       if (meta > 0) {
//         cumplimiento = (totalProd / meta) * 100;
//       }

//       // clamp between 0–100 and format with 1 decimal place
//       cumplimientoInput.value = `${Math.min(cumplimiento, 100).toFixed(1)}%`;

//       // update totals whenever row changes
//       updateTotalsSummary();
//     });
//   });
// };


const initRowEvents = () => {
  const metaInputs = document.querySelectorAll('input[name="meta_dia[]"]');

  metaInputs.forEach((input) => {
    input.addEventListener("input", () => {
      const row = input.closest("tr");
      const cumplimientoInput = row.querySelector('input[name="cumplimiento[]"]');
      const totalProd = parseInt(
        row.querySelector("td:nth-child(7) span").textContent.replace(/[^\d]/g, ""),
        10
      ) || 0;

      const meta = parseInt(input.value.replace(/[^\d]/g, ""), 10) || 0;

      let cumplimiento = 0;
      if (meta > 0) {
        cumplimiento = (totalProd / meta) * 100;
      }

      // ✅ clamp between 0–100 and format with 1 decimal place
      const porcentaje = Math.min(cumplimiento, 100).toFixed(1) + "%";

      // ✅ set directly in the cumplimiento[] input
      cumplimientoInput.value = porcentaje;

      // update totals whenever row changes
      updateTotalsSummary();
    });

    // trigger calculation once on load (pre-fill cumplimiento if meta exists)
    input.dispatchEvent(new Event("input"));
  });
};



// Add summary container once
const addSummaryDiv = () => {
  const colTable = document.querySelector(".col-table");
  let summary_container = colTable.querySelector(".summary-container");
  let summary = colTable.querySelector(".summary-totals");

  if (!summary_container) {
    summary_container = document.createElement("div");
		summary_container.className = "summary-container w-full flex justify-end p-4";

    summary = document.createElement("div");
    summary.className = "summary-totals w-fit flex gap-x-4 p-2 bg-grayMid bg-opacity-50";

    summary.innerHTML = `
      <div class="w-fit text-center text-lg font-semibold ">DE ACUERDO AL PLAN</div>
      <div class="w-28 text-center border p-1 font-semibold sum-meta-dia">0</div>
      <div class="w-28 text-center border p-1 font-semibold sum-total-prod">0</div>
      <div class="w-28 text-center border p-1 font-semibold sum-total-merma">0</div>
      <div class="w-28 text-center border p-1 font-semibold sum-cumplimiento">0%</div>
    `;

		summary_container.appendChild(summary);
		colTable.appendChild(summary_container);
  }
};

// Update totals in the summary div
// const updateTotalsSummary = () => {
//   const metaInputs = document.querySelectorAll('input[name="meta_dia[]"]');
//   const prodCells = document.querySelectorAll("#tabla-reporte-meta-dia tbody tr td:nth-child(7) span");
//   const mermaCells = document.querySelectorAll("#tabla-reporte-meta-dia tbody tr td:nth-child(8) span");
//   const cumplimientoInputs = document.querySelectorAll('input[name="cumplimiento[]"]');

//   let sumMeta = 0;
//   let sumProd = 0;
//   let sumMerma = 0;
//   let sumCumplimiento = 0;
//   let validCumplimiento = 0;

//   metaInputs.forEach(inp => {
//     sumMeta += parseInt(inp.value.replace(/[^\d]/g, ""), 10) || 0;
//   });

//   prodCells.forEach(cell => {
//     sumProd += parseInt(cell.textContent.replace(/[^\d]/g, ""), 10) || 0;
//   });

//   mermaCells.forEach(cell => {
//     sumMerma += parseInt(cell.textContent.replace(/[^\d]/g, ""), 10) || 0;
//   });

//   cumplimientoInputs.forEach(inp => {
//     const val = parseInt(inp.value.replace(/[^\d]/g, ""), 10);
//     if (!isNaN(val)) {
//       sumCumplimiento += val;
//       validCumplimiento++;
//     }
//   });

//   // Global cumplimiento as percentage of sums
//   const globalCumplimiento = sumMeta > 0 ? Math.round((sumProd / sumMeta) * 100) : 0;

//   document.querySelector(".sum-meta-dia").textContent = sumMeta;
//   document.querySelector(".sum-total-prod").textContent = sumProd;
//   document.querySelector(".sum-total-merma").textContent = sumMerma;
//   document.querySelector(".sum-cumplimiento").textContent = `${globalCumplimiento}%`;
// };

// const updateTotalsSummary = () => {
//   const rows = document.querySelectorAll("#tabla-reporte-meta-dia tbody tr");

//   let totalMeta = 0;
//   let totalProd = 0;
//   let totalMerma = 0;
//   let cumplimientoSum = 0;
//   let rowCount = 0;

//   rows.forEach((row) => {
//     const meta = parseInt(row.querySelector('input[name="meta_dia[]"]').value.replace(/[^\d]/g, ""), 10) || 0;
//     const prod = parseInt(row.querySelector("td:nth-child(7) span").textContent.replace(/[^\d]/g, ""), 10) || 0;
//     const merma = parseInt(row.querySelector("td:nth-child(8) span").textContent.replace(/[^\d]/g, ""), 10) || 0;
//     const cumplimientoStr = row.querySelector('input[name="cumplimiento[]"]').value.replace("%", "").trim();
//     let cumplimiento = parseFloat(cumplimientoStr) || 0;

//     // clamp cumplimiento per row
//     cumplimiento = Math.min(cumplimiento, 100);

//     totalMeta += meta;
//     totalProd += prod;
//     totalMerma += merma;
//     cumplimientoSum += cumplimiento;
//     rowCount++;
//   });

//   // average cumplimiento across rows (already capped)
//   const cumplimientoTotal = rowCount > 0 ? (cumplimientoSum / rowCount).toFixed(1) : 0;

//   document.querySelector(".sum-meta-dia").textContent = totalMeta.toLocaleString();
//   document.querySelector(".sum-total-prod").textContent = totalProd.toLocaleString();
//   document.querySelector(".sum-total-merma").textContent = totalMerma.toLocaleString();
//   document.querySelector(".sum-cumplimiento").textContent = `${cumplimientoTotal}%`;
// };

const updateTotalsSummary = () => {
  const rows = document.querySelectorAll("#tabla-reporte-meta-dia tbody tr");

  let totalMeta = 0;
  let totalProd = 0;
  let totalMerma = 0;
  let cumplimientoSum = 0;
  let rowCount = 0;

  rows.forEach((row) => {
    const meta = parseInt(
      row.querySelector('input[name="meta_dia[]"]').value.replace(/[^\d]/g, ""),
      10
    ) || 0;

    const prod = parseInt(
      row.querySelector("td:nth-child(7) span").textContent.replace(/[^\d]/g, ""),
      10
    ) || 0;

    const merma = parseInt(
      row.querySelector("td:nth-child(8) span").textContent.replace(/[^\d]/g, ""),
      10
    ) || 0;

    const cumplimientoInput = row.querySelector('input[name="cumplimiento[]"]');
    let cumplimiento = parseFloat(cumplimientoInput.value.replace("%", "").trim()) || 0;

    // clamp cumplimiento per row
    cumplimiento = Math.min(cumplimiento, 100);

    totalMeta += meta;
    totalProd += prod;
    totalMerma += merma;
    cumplimientoSum += cumplimiento;
    rowCount++;
  });

  // ✅ promedio de % cumplimiento (ya calculado por fila)
  const cumplimientoTotal = rowCount > 0 ? (cumplimientoSum / rowCount).toFixed(1) : "0.0";

  document.querySelector(".sum-meta-dia").textContent = totalMeta.toLocaleString();
  document.querySelector(".sum-total-prod").textContent = totalProd.toLocaleString();
  document.querySelector(".sum-total-merma").textContent = totalMerma.toLocaleString();
  document.querySelector(".sum-cumplimiento").textContent = `${cumplimientoTotal}%`;
};






/**
 * Calcula el porcentaje de avance.
 * @param {string|number} plan - Meta planificada (ej: "1,000 PIEZAS" o número).
 * @param {string|number} producido - Total producido (ej: "344").
 * @returns {string} - Porcentaje (ej: "34%").
 */
const getAvance = (plan, producido) => {
  if (!plan || !producido) return "0%";

  // Limpia todo lo que no sea dígito en el plan
  const planNum = parseInt(String(plan).replace(/[^\d]/g, ""), 10) || 0;
  const prodNum = parseInt(String(producido).replace(/[^\d]/g, ""), 10) || 0;

  if (planNum <= 0) return "0%";

  const porcentaje = Math.round((prodNum / planNum) * 100);
  return `${porcentaje}%`;
};





const form_meta = document.querySelector('#form_meta');
form_meta.addEventListener('submit', (e) => {
	e.preventDefault();
	Service.stopSubmit(e.target, true);
	Service.show('.loading');

	const rows = document.querySelectorAll("#tabla-reporte-meta-dia tbody tr");
	const formData = new FormData();

	rows.forEach((row, i) => {
		formData.append(`rows[${i}][ordenId]`, row.querySelector('input[name="ordenId[]"]').value);
		formData.append(`rows[${i}][fecha]`, row.querySelector('input[name="fecha[]"]').value);
		formData.append(`rows[${i}][porcentaje_avanzado]`, row.querySelector('input[name="porcentaje_avanzado[]"]').value);
		formData.append(`rows[${i}][meta_dia]`, row.querySelector('input[name="meta_dia[]"]').value);
		formData.append(`rows[${i}][total_producido]`, row.querySelector("td:nth-child(7) span").textContent);
		formData.append(`rows[${i}][cumplimiento]`, row.querySelector('input[name="cumplimiento[]"]').value);
	});


	Service.exec('post', `produccion/reporte_meta`, formData_header, formData)
	.then( r => {
		if(r.success){
			
			Service.stopSubmit(e.target, false);

			setTimeout(() => {
				Service.hide('.loading');
				Modal.init("modal_success").open();
				e.target.reset();
			}, 500)


		} else {

			let msg = '';
			let validationErrors = r.message;

			for (const field in validationErrors) {
					if (validationErrors.hasOwnProperty(field)) {
							// msg += `${field} : ${validationErrors[field]} <br>`;
							msg += `${validationErrors[field]} <br>`;
					}
			}

			let ci_error = document.querySelector('#ci_error')
			ci_error.innerHTML = msg;

			Service.show('#ci_error');

			Service.hide('.loading');
			Service.stopSubmit(e.target, false);

		}
	});
});



const loadRows = () => {
	Service.hide('.row--empty');
	tbody.innerHTML = Service.loader();

	Service.exec('get', `produccion/reporte_meta/${dia_selected.value}`)
	.then(r => renderRows(r));  
}

loadRows();


</script>
</body>
</html>
