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

  <div class="relative flex flex-col gap-y-2 items-center justify-center font-titil ">

  	<?php echo view('sorteo/_partials/navbar'); ?>

		<!-- title group - title page -->
		<div class="relative text-title w-full mt-2 py-2 px-6 lg:px-10 flex items-center justify-center ">
			<h2 class="text-center font-semibold text-3xl "><?= esc($title_group) ?></h2>
			<a href="<?= base_url('sorteo') ?>" class="px-1 absolute right-6 lg:right-10 top-1/2 -translate-y-1/2 hover:scale-110 transition-transform duration-100 ">
				<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
			</a>
		</div>

		<div class="relative text-title w-full py-2 px-4 lg:px-10 flex items-center justify-between ">

			<div class="w-full flex items-center justify-start xl:justify-center">
				<h2 class="text-center font-semibold w-fit text-2xl lg:text-3xl "><?= esc($title) ?></h2>
			</div>
			<div class=" flex gap-4 items-center absolute right-6 lg:right-10 top-1/2 -translate-y-1/2">
				<!-- <a href="<?= base_url('export/sorteo_entregas/all') ?>" class="btn btn-md btn--search uppercase"> -->
				<a href="<?= base_url('export/sorteo_entregas') ?>" class="btn btn-md btn--search uppercase">
					<i class="fas fa-download"></i>
					<span>Descargar Lista</span>
				</a>
			</div>

    </div>

		<!-- main content -->
    <div class="w-full text-sm text-gray  ">
      <div class="flex flex-col h-full" >

        <div class="w-full pl-10 pr-8 ">
					<div class="relative w-full text-sm h-[65vh] overflow-y-scroll ">
            <table id="tabla-sorteo-entregas">
              <thead>
                <tr>
                  <th># Entrega</th>
                  <th>Nombre</th>
                  <th>Cargo</th>
                  <th>Sede - Turno</th>
                  <th>Producto</th>
                  <th>Cod. Producto - Lote</th>
                </tr>
              </thead>
              <tbody></tbody>

            </table>
            <div class="row--empty">No results found.</div>
            
          </div>
        </div>


      </div>
    </div>
  </div>



<?php echo view('_partials/_modal_msg'); ?>

<script>
  Service.setLoading();


const tbody = document.querySelector('#tabla-sorteo-entregas tbody');


const renderRows = (data) => {  
  tbody.innerHTML = "";

	if (!data.length) {
		Service.show('.row--empty');
		return;
	}

	let count = 1;

	data.forEach(cell => {
		const row = document.createElement('tr');
		row.innerHTML =
			`
				<td>
					<span>${count++}</span>
				</td>
				<td>
					<span>${cell.name} ${cell.last_name}</span>
				</td>
				<td>
					<span>${(cell.rol) ? cell.rol : ""}</span>
				</td>
				<td>
					<span>${(cell.turno) ? cell.turno : ""}</span>
				</td>
				<td>
					<span>${cell.descripcion}</span>
				</td>
				<td>
					<span>${cell.codigo} - ${cell.lote}</span>
				</td>
			`
		tbody.appendChild(row);
	});

}

const loadRows = () => {
	Service.hide('.row--empty');
	tbody.innerHTML = Service.loader();

	Service.exec('get', `${root}/sorteo/lista_entregado`)
	.then(r => renderRows(r));  
}

loadRows();

</script>
</body>
</html>