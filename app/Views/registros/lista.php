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

  <div class=" relative h-full pb-16 flex flex-col gap-y-8 items-center justify-center font-titil ">

  	<?php echo view('dashboard/_partials/navbar'); ?>

    <div class="text-title w-full md:pt-4 md:pb-8 md:px-16 p-2 flex items-center ">
      <h2 class="text-center font-bold w-full text-2xl lg:text-3xl "><?= esc($title) ?></h2>
      <a href="<?= base_url('apps') ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>

    <div class="w-full text-sm text-gray  ">
      <div class="flex flex-col h-full" >


        <div class="w-full pl-10 pr-8 ">
					<div class="relative w-full text-sm h-[55vh] overflow-y-scroll ">
            <table id="tabla-inspecciones">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Creado Por</th>
                  <th>Fecha Creacion</th>
                  <th>Ver detalles</th>
                </tr>
              </thead>
              <tbody id="results_container"></tbody>

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


const results_container = document.querySelector('#results_container');


const renderProv = (data) => {  

    results_container.innerHTML = "";
    // console.log(data); return;

    if (data.length > 0) {
      Service.hide('#row__empty');


      data.forEach(prov => {
        const row = document.createElement('tr');

        row.innerHTML =
          `
            <td>
              <span>${format_id(prov.id, 'id')}</span>
            </td>
						<td>
              <span>${prov.name} ${prov.last_name}</span>
            </td>
            <td>
              <span>${dateToString(prov.created_at)}</span>
            </td>

            <td>
              <div class="row__actions ">
								<a href="${root}/registros/details/${prov.id}" class=" hover:text-icon px-2" ><i class="fas fa-eye text-lg"></i>
								</a>
              </div>
            </td>
          `
        results_container.appendChild(row);
      });

      // initRowBtn();
    } else {
      Service.show('#row__empty');
    }
  }

  const loadAllProveedores = () => {
    Service.hide('#row__empty');
    results_container.innerHTML = Service.loader();

    Service.exec('get', `/all_reportes/`)
    .then(r => renderProv(r));  
  }

  loadAllProveedores();

</script>
</body>
</html>