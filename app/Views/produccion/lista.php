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

        <div class="w-full pl-10 pr-8 ">
					<div class="relative w-full text-sm h-[70vh] overflow-y-scroll ">
            <table id="tabla-produccion-lista-procesos">
              <thead>
                <tr>
                  <th>Turno</th>
                  <th>Nro. Orden</th>
                  <th>Articulo</th>
                  <th>Proceso</th>
                  <th>Creado Por</th>
                  <th>Fecha Creacion</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody></tbody>

            </table>
            <div id="row__empty" class="row__empty">No hay datos</div>
            
          </div>
        </div>


      </div>
    </div>
  </div>



<?php echo view('_partials/_modal_msg'); ?>

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

  loadAllProveedores();

</script>
</body>
</html>