
<?php echo view('_partials/header'); ?>
	<link rel="stylesheet" href="<?= base_url('_partials/inspeccion.css') ?>">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/Modal.min.js') ?>"></script>
  <link rel="stylesheet" href="<?= load_asset('_partials/inspeccion.css') ?>">
	<script src="https://unpkg.com/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>

  <title><?= esc($title) ?></title>
</head>
<body class="relative min-h-screen">

  <div class=" relative h-full pb-16 flex flex-col gap-y-8 items-center justify-center font-titil ">

  	<?php echo view('dashboard/_partials/navbar'); ?>

    <div class="text-title w-full md:p-4 md:px-16 p-2 flex items-center ">
      <h2 class="text-center font-bold w-full text-2xl lg:text-4xl "><?= esc($title1) ?></h2>
      <a href="<?= base_url('registros/lista') ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>

		<form id="form_incidencia" method="post" class="pdf-container" enctype='multipart/form-data'>
			<?= csrf_field() ?>


			<!-- Pagina 1 -->
			<div class=" flex flex-col gap-y-8 w-full lg:w-9/12 p-3 lg:p-16 bg-white drop-shadow-card">


				<h2 class="text-center font-bold w-full text-3xl lg:text-3xl text-title pb-8 "><?= esc($title) ?></h2>

				<div class="text-title w-full flex items-center justify-between ">
					<div class="flex flex-col items-center "> 
						<span class="text-gray uppercase">Fecha</span>
						<div class="text-white py-1 px-4 bg-icon uppercase"><?= dateToString($reporte['created_at']); ?></div> 
					</div>

					<div class="flex gap-x-4  w-1/2 items-center justify-end ">
						<div class="flex flex-col w-2/3 items-center "> 
							<span class="text-gray uppercase">Registrado por:</span>
							<input readonly type="text" name="meta_piezas" class="to_uppercase text-center h-8" value="<?= $reporte['name'] . ' ' . $reporte['last_name']?>">

						</div>

					</div>

				</div>


				<h2 class="uppercase text-center w-full text-xl lg:text-2xl text-gray ">Informacion General</h2>

				<!-- Datos Generales Inspeccion -->
				<div class="general__section">

					<div class="general__col header border-r border-t  ">
						<div class=" col w-full lg:w-[50%] border-l border-b">
							<span>TURNO</span>
						</div>
						<div class=" col w-full lg:w-[50%] border-l border-b">
							<span>NUMERO DE ORDEN DE FABRICACION</span>
						</div>
					</div>

					<div class="general__row border-r">
						<div class="col border-l border-b w-[50%]">
						<input readonly type="text" name="meta_piezas" class="to_uppercase text-center h-8" value="<?= $registros['turno'] ?>">

						</div>
						<div class="col border-l border-b w-[50%]">
							<input readonly type="text" name="meta_piezas" class="to_uppercase text-center h-8" value="<?= $registros['nro_orden'] ?>">

						</div>
					</div>


					<div class="general__col header border-r">
						<div class="col border-l border-b w-full lg:w-[18%]">
							<span>LINEA</span>
						</div>
						<div class="col border-l border-b w-full lg:w-[64%]">
							<span>ARTICULO EN PRODUCCION</span>
						</div>
						<div class="col border-l border-b w-full lg:w-[18%]">
							<span>CODIGO</span>
						</div>
					</div>

					<div class="general__row border-r">
						<div class="col border-l border-b w-[18%]">
							<input type="text" name="linea" class="to_uppercase text-center h-8" value="<?= $registros['linea'] ?>">
						</div>
						<div class="relative col border-l border-b w-[64%]">
						<input readonly type="text" name="meta_piezas" class="to_uppercase text-center h-8" value="<?= $registros['nombre_producto'] ?>">

						</div>
						<div class="col border-l border-b w-[18%]">
						<input readonly type="text" name="meta_piezas" class="to_uppercase text-center h-8" value="<?= $registros['codigo'] ?>">

						</div>
					</div>


					<div class="general__col header border-r  ">
						<div class=" col w-full lg:w-[50%] border-l border-b">
							<span>SELECCIONA PROCESO</span>
						</div>
						<div class=" col w-full lg:w-[50%] border-l border-b">
							<span>TIPO PROCESO</span>
						</div>
					</div>

					<div class="general__row border-r">
						<div class="col border-l border-b w-[50%]">
							<input readonly type="text" name="meta_piezas" class="to_uppercase text-center h-8" value="<?= $registros['proceso'] ?>">

						</div>
						<div class="col border-l border-b w-[50%]">
							<input readonly type="text" name="meta_piezas" class="to_uppercase text-center h-8" value="<?= $registros['tipo_proceso'] ?>">

						</div>
					</div>

				</div>



				<h2 class="uppercase text-center w-full text-xl lg:text-2xl text-gray ">META vs REAL</h2>

				<!-- Datos Metas -->
				<div class="general__section">

					<div class="general__col header border-r border-t">
						<div class="col border-l border-b w-full lg:w-[18%]">
							<span>INGRESA META EN PIEZAS</span>
						</div>
						<div class="col border-l border-b w-full lg:w-[64%]">
							<span>META EN UNIDAD DE MEDIDA</span>
						</div>
						<div class="col border-l border-b w-full lg:w-[18%]">
							<span>UNIDAD DE MEDIDA</span>
						</div>
					</div>

					<div class="general__row border-r">
						<div class="col border-l border-b w-[18%]">
							<input readonly type="text" name="meta_piezas" class="to_uppercase text-center h-8" value="<?= $registros['meta_piezas'] ?>">
						</div>
						<div class="col border-l border-b w-[64%]">
							<input readonly type="text" name="meta_cantidad" class="to_uppercase text-center h-8" value="<?= $registros['meta_cantidad'] ?>">
						</div>
						<div class="col border-l border-b w-[18%]">
							<input readonly type="text" name="meta_medida" class="to_uppercase text-center h-8" value="<?= $registros['meta_medida'] ?>">
						</div>
					</div>


					<div class="general__col header border-r">
						<div class="col border-l border-b w-full lg:w-[18%]">
							<span>CANTIDAD EN PIEZAS REALES</span>
						</div>
						<div class="col border-l border-b w-full lg:w-[64%]">
							<span>REAL EN UNIDAD DE MEDIDA</span>
						</div>
						<div class="col border-l border-b w-full lg:w-[18%]">
							<span>UNIDAD DE MEDIDA</span>
						</div>
					</div>

					<div class="general__row border-r">
						<div class="col border-l border-b w-[18%]">
							<input readonly type="text" name="real_piezas" class="to_uppercase text-center h-8" value="<?= $registros['real_piezas'] ?>">
						</div>
						<div class="col border-l border-b w-[64%]">
							<input readonly type="text" name="real_cantidad" class="to_uppercase text-center h-8" value="<?= $registros['real_cantidad'] ?>" >
						</div>
						<div class="col border-l border-b w-[18%]">
							<input readonly type="text" name="real_medida" class="to_uppercase text-center h-8" value="<?= $registros['real_medida'] ?>">
						</div>
					</div>

				</div>



				<h2 class="uppercase text-center w-full text-xl lg:text-2xl text-gray ">REPORTE DE PERSONAL</h2>		

				<!-- <div class="flex w-full justify-end items-center">
					<button  id="add-operario" class=" rounded text-icon border-2 border-icon hover:bg-icon hover:text-white py-1 px-3 w-fit uppercase" type="button">
						<i class="fa fa-plus text-xl"></i>
						<span>Agregar operario</span>
					</button>
				</div> -->
				

				<!-- Datos Personal -->
				<div id="operarios-container" class="general__section">
					<div class="w-full flex border-gray header border-r border-t">
						<div class="col border-gray border-l border-b w-full lg:w-[75%] ">
							<p class="w-full text-center py-2">PERSONAL QUE TRABAJO LA LINEA</p>
						</div>
						<div class="col border-gray border-l border-b w-full lg:w-[25%] ">
							<p class="w-full text-center py-2">HORAS EXTRA</p>
						</div>
					</div>


					<?php foreach ($personal as $proc): ?>

					<div class="operario-row flex w-full border-gray border-r relative">
						<div class="col border-l border-b w-[75%] relative">
							<div class="flex items-center p-2 relative">
								<input readonly name="total_personal" id="total_personal" class="w-full text-gray py-2 text-center" value="<?= $proc['name'] . " " . $proc['last_name'] ?>">

							</div>
						</div>
						<div class="col border-l border-b w-[25%]">
							<div class="p-2">
							<input readonly name="total_personal" id="total_personal" class="w-full text-gray py-2 text-center" value="<?= $proc['horas'] ?>">

							</div>
						</div>
					</div>
					<?php endforeach; ?>

				</div>



				<!-- Datos Personal horas -->
				<div class="general__section">

					<div class="w-full flex border-gray header border-r border-t">
						<div class="col border-gray border-l border-b w-full lg:w-[75%] ">
							<p class="w-full text-center py-2"> TOTAL DE PERSONAS EN EL PROCESO</p>
						</div>
						<div class="col border-gray border-l border-b w-full lg:w-[25%] ">
							<p class="w-full text-center py-2">TOTAL HORAS EXTRA</p>
						</div>
					</div>

					<div class="flex w-full border-gray border-r">
						<div class="col border-l border-b w-[75%]">
							<div class="flex items-center p-2">
								<input readonly name="total_personal" id="total_personal" class="w-full text-gray py-2 text-center" value="<?= $registros['total_personal'] ?>">
							</div>

						</div>
						<div class="col border-l border-b w-[25%]">
							<div class="p-2">
								<input readonly name="total_horas_extras" id="total_horas_extras" class="w-full text-gray py-2 text-center" value="<?= $registros['total_horas_extras'] ?>">
							</div>
						</div>
					</div>
				</div>




				<h2 class="uppercase text-center w-full text-xl lg:text-2xl text-gray ">INCIDENCIAS</h2>

				<!-- Datos incidencias -->
				<div class="general__section">

					<div class="w-full flex border-gray header border-r border-t">
						<div class="col border-gray border-l border-b w-full lg:w-[50%] ">
							<p class="w-full text-center py-2">EN CASO DE INCIDENCIA SELECCIONE MOTIVO</p>
						</div>
						<div class="col border-gray border-l border-b w-full lg:w-[50%] ">
							<p class="w-full text-center py-2">HUBO DESCIACION DE CALIDAD, SELECCIONA CUAL(ES)</p>
						</div>
					</div>


					<?php
						$IncIds = !empty($registros['incidencias']) ? explode(',', $registros['incidencias']) : [];
						$DesvIds = !empty($registros['desviaciones']) ? explode(',', $registros['desviaciones']) : [];
						?>

					<div class="flex w-full border-gray border-r">

						<div class="col border-l w-[50%]">
							<div class="flex flex-col gap-y-2 items-center justify-center pt-2 ">
								<?php foreach ($incidencias as $in): ?>
									<div class="flex items-center border-b pb-2 border-gray justify-between w-full">
										<span class="px-4"><?= $in['incidencia'] ?></span>
										<div class="item--alt w-[10%]">
											<label class="label--check" for="incidencia_<?= $in['id'] ?>">
												<!-- Check if the current incidencia ID exists in the IncIds array -->
												<input type="checkbox" name="incidencias[]" value="<?= $in['id'] ?>" 
															class="checkbox_incidencia hidden" 
															id="incidencia_<?= $in['id'] ?>"
															<?php echo in_array($in['id'], $IncIds) ? 'checked' : ''; ?> 
												>
												<span class="checkbox-label-inc"><i class="fas fa-check"></i></span>
											</label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>


						<div class="col border-l w-[50%]">
							<div class="flex flex-col gap-y-2 items-center justify-center pt-2 ">
								<?php foreach ($desviaciones as $desv): ?>
									<div class="flex items-center border-b pb-2 border-gray justify-between w-full">
										<span class="px-4"><?= $desv['desviacion'] ?></span>
										<div class="item--alt w-[10%]">
											<label class="label--check" for="desviacion_<?= $desv['id'] ?>">
												<!-- Check if the current incidencia ID exists in the selectedIds array -->
												<input type="checkbox" name="desviaciones[]" value="<?= $desv['id'] ?>" 
															class="checkbox_incidencia hidden" 
															id="desviacion_<?= $desv['id'] ?>"
															<?php echo in_array($desv['id'], $DesvIds) ? 'checked' : ''; ?> 
												>
												<span class="checkbox-label-inc"><i class="fas fa-check"></i></span>
											</label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>


					</div>
				</div>

				<!-- Datos Tiempo muerto -->
				<div class="general__section">

					<div class="w-full flex border-gray header border-r border-t">
						<div class="col border-gray border-l border-b w-full lg:w-[50%] ">
							<p class="w-full text-center py-2">TIEMPO MUERTO TOTAL</p>
						</div>
						<div class="col border-gray border-l border-b w-full lg:w-[50%] ">
							<p class="w-full text-center py-2">TIEMPO EFECTIVO</p>
						</div>
					</div>

					<div class="flex w-full border-gray border-r">
						<div class="col border-l border-b w-[50%]">
							<div class="w-full flex items-center justify-center pt-2">
								<input readonly name="total_tiempo_efectivo" id="total_tiempo_efectivo" class="w-full text-gray py-2 text-center" value="<?= $registros['total_tiempo_muerto'] ?>">
							</div>
						</div>
						<div class="col border-l border-b w-[50%]">
							<div class="p-2">
								<input readonly name="total_tiempo_efectivo" id="total_tiempo_efectivo" class="w-full text-gray py-2 text-center" value="<?= $registros['total_tiempo_efectivo'] ?>">
							</div>
						</div>
					</div>
				</div>


				<h2 class="uppercase text-center w-full text-xl lg:text-2xl text-gray ">OBSERVACIONES</h2>

				<div class="w-full text-white flex flex-col items-center justify-between gap-y-2 ">
					<textarea name="observacion" id="" rows="4" placeholder="Escribe un comentario" class="w-full border border-gray p-2 text-gray outline-none resize-none " ><?= $registros['observacion'] ?></textarea>
				</div>

				<!-- <div class="w-full flex items-center justify-end pt-8 ">
					<button class=" text-2xl pdf-button " type="submit" >
						<span>Guardar</span>
					</button>
				</div> -->


			</div>


			<input type="hidden" id="produccionId" name="produccionId">
			<input type="hidden" id="firma_produccion" name="firma_produccion">
			<input type="hidden" id="fecha_firma_produccion" name="fecha_firma_produccion">
		</form>

	</div>


			


<script>

</script>
</body>
</html>


