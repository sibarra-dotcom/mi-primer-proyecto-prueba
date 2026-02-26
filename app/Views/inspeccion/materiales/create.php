
<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>

  <link rel="stylesheet" href="<?= load_asset('_partials/inspeccion.css') ?>">

	<script src="https://unpkg.com/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>

  <title><?= esc($title) ?></title>
</head>
<body class="relative min-h-screen">

  <div class=" relative h-full pb-16 flex flex-col gap-y-8 items-center justify-center font-titil ">

  	<?php echo view('dashboard/_partials/navbar'); ?>

    <div class="text-title w-full md:p-4 md:px-16 p-2 flex items-center ">
      <h2 class="text-center font-bold w-full text-2xl lg:text-3xl "><?= esc($title) ?></h2>
      <a href="<?= base_url('inspeccion/init/materiales') ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
    </div>


		<form id="form_inspeccion" method="post" class="pdf-container" enctype='multipart/form-data'>
			<?= csrf_field() ?>

			<!-- Pagina 1 -->
			<div class=" page__container">
				<!-- Datos Formato Inspeccion -->
				<?php echo view("inspeccion/$slug/_partials/format-header"); ?>

				<!-- Datos Generales Inspeccion -->
				<div class="general__section">

					<div class="general__col header border-r border-t  ">
						<div class=" col w-full lg:w-[15%] border-l border-b">
							<span>LOTE INTERNO</span>
							<input type="text" data-field="LOTE INTERNO" class="w-full to_uppercase text-gray h-8 lg:hidden ">
						</div>
						<div class=" col w-full lg:w-[48%] border-l border-b">
							<span>MATERIAL</span>
							<input type="text" data-field="MATERIAL" class="w-full to_uppercase text-gray h-8 lg:hidden ">
						</div>
						<div class=" col w-full lg:w-[12%] border-l border-b">
							<span>PRESENTACION DEL EMBALAJE</span>
							<input type="text" data-field="PRESENTACION DEL EMBALAJE" class="w-full to_uppercase text-gray h-8 lg:hidden ">
						</div>
						<div class=" col w-full lg:w-[13%] border-l border-b">
							<span>CANTIDAD (Paquetes)</span>
							<input type="text" data-field="CANTIDAD (Paquetes)" class="w-full to_uppercase text-gray h-8 lg:hidden ">
						</div>
						<div class=" col w-full lg:w-[12%] border-l border-b">
							<span>CANTIDAD TOTAL (Piezas)</span>
							<input type="text" data-field="CANTIDAD TOTAL (Piezas)" class="w-full to_uppercase text-gray h-8 lg:hidden ">
						</div>
					</div>

					<div class="general__row border-r">
						<div class="col border-l border-b w-[15%]">
							<input type="text" data-field="LOTE INTERNO" class="w-full to_uppercase h-8" >
						</div>
						<div class="col border-l border-b w-[48%]">
							<input type="text" data-field="MATERIAL" class="w-full to_uppercase h-8">
						</div>
						<div class="col border-l border-b w-[12%]">
							<input type="text" data-field="PRESENTACION DEL EMBALAJE" class="w-full to_uppercase h-8">
						</div>
						<div class="col border-l border-b w-[13%]">
							<input type="text" data-field="CANTIDAD (Paquetes)" class="w-full to_uppercase h-8">
						</div>
						<div class="col border-l border-b w-[12%]">
							<input type="text" data-field="CANTIDAD TOTAL (Piezas)" class="w-full to_uppercase h-8">
						</div>
					</div>


					<div class="general__col header border-r">
						<div class="col border-l border-b w-full lg:w-[15%]">
							<span>FECHA DE ARRIBO</span>
							<input type="text" data-field="FECHA DE ARRIBO" class="text-center w-full to_uppercase text-gray h-8 lg:hidden" value="<?= date('d-m-Y'); ?>">
						</div>
						<div class="col border-l border-b w-full lg:w-[28%]">
							<span>LOTE EXTERNO</span>
							<input type="text" data-field="LOTE EXTERNO" class="text-center w-full to_uppercase text-gray h-8 lg:hidden">
						</div>
						<div class="col border-l border-b w-full lg:w-[42%]">
							<span>PROVEEDOR</span>
							<input type="text" data-field="PROVEEDOR" class="text-center w-full to_uppercase text-gray h-8 lg:hidden">
						</div>
						<div class="col border-l border-b w-full lg:w-[15%]">
							<span>CADUCIDAD</span>
							<input type="text" data-field="CADUCIDAD" class="text-center w-full to_uppercase text-gray h-8 lg:hidden">
						</div>
					</div>

					<div class="general__row border-r">
						<div class="col border-l border-b w-[15%]">
							<input type="text" data-field="FECHA DE ARRIBO" class="w-full to_uppercase h-8" value="<?= date('d-m-Y'); ?>">
						</div>
						<div class="col border-l border-b w-[28%]">
							<input type="text" data-field="LOTE EXTERNO" class="w-full to_uppercase h-8">
						</div>
						<div class="col border-l border-b w-[42%]">
							<input type="text" data-field="PROVEEDOR" class="w-full to_uppercase h-8">
						</div>
						<div class="col border-l border-b w-[15%]">
							<input type="text" data-field="CADUCIDAD" class="w-full to_uppercase h-8">
						</div>
					</div>


					<div class="general__col border-r ">
						<div class="col header border-l border-b w-full lg:w-[25%]">
							<span class="">NOMBRE DEL TRANSPORTISTA</span>
							<input type="text" data-field="NOMBRE DEL TRANSPORTISTA" class="w-full to_uppercase mobile-input lg:hidden h-8 text-gray">
						</div>

						<div class="col-hidden border-l border-b w-[40%] px-2">
							<input type="text" data-field="NOMBRE DEL TRANSPORTISTA" class="w-full to_uppercase desktop-input h-8">
						</div>

						<div class="col header border-l border-b w-full lg:w-[20%] ">
							<span class="">NÚMERO DE PLACA</span>
							<input type="text" data-field="NUMERO DE PLACA" class="w-full to_uppercase mobile-input lg:hidden h-8 text-gray">
						</div>

						<div class="col-hidden border-l border-b w-[15%] px-2">
							<input type="text" data-field="NUMERO DE PLACA" class="w-full to_uppercase desktop-input h-8">
						</div>
					</div>

				</div>

				<!-- Datos Secciones Inspeccion -->
				<div class="list__section">
					<span>* Anotar con un <i class="fas fa-check"></i> cuando el material cumpla con cada criterio de análisis y marcar con un <i class="fas fa-close"></i> cuando esta NO cumpla. Si el material se rechaza se deberá realizar el reporte correspondiente.</span>
					<div class="w-full flex flex-col border-t  border-gray ">

						<?php foreach($page1Sections as $section): ?>
						<div class="list__row header-b border-r">
							<div class="col border-l border-b w-[65%] lg:w-[50%]"><?= esc($section['title']) ?></div>
							<div class="col border-l border-b w-[10%]">SI CUMPLE</div>
							<div class="col border-l border-b w-[10%]">NO CUMPLE</div>
							<div class="col border-l border-b w-[15%] lg:w-[30%]">
								<span>OBSERVACIONES</span>
								<!-- <button type="button" data-section-title="<?= esc($section['title']) ?>" class=" btn_na absolute -bottom-10 right-4 bg-icon text-white rounded-lg py-1 px-3 text-lg">N.A.</button> -->
								<!-- <button type="button" data-id="<?= esc($section['items'][0]['id']) ?>" data-section="<?= esc($section['title']) ?>" data-modal="modal_comm_unico" class="btn_open_modal absolute -bottom-12 right-4 border-gray text-icon p-1 text-4xl"><i class="fas fa-comment"></i></button> -->
							</div>

						</div>
					
							<?php foreach($section['items'] as $item): ?>
								<div class="list__row border-r">
									<div class="item border-l border-b w-[65%] lg:w-[50%]">
										<div class="flex w-full items-center justify-between ">

											<?php if (in_array($item['item_number'], $items_espec_page1, true)): ?> 
												<?= esc($item['item_number']) ?>
												<?= esc($item['description']) ?>
												<div class="ml-4 md:w-1/2 flex justify-end gap-2 px-2 text-gray rounded">

													<input type="hidden" value="NA" name="detalles[espec][<?= esc($item['id']) ?>]">

													<?php if (in_array($item['item_number'], $single_resultado, true)): ?> 
														<input type="text" placeholder="N.A." class="text-center flex-1 border border-gray  " name="detalles[resultados][<?= esc($item['id']) ?>][]">
													<?php else: ?> 
														<input type="text" placeholder="N.A." class="text-center w-24 border border-gray  " name="detalles[resultados][<?= esc($item['id']) ?>][]">
														<input type="text" placeholder="N.A." class="text-center w-24 border border-gray  " name="detalles[resultados][<?= esc($item['id']) ?>][]">
														<input type="text" placeholder="N.A." class="text-center w-24 border border-gray  " name="detalles[resultados][<?= esc($item['id']) ?>][]">
													<?php endif; ?>
												</div>
												
											<?php else: ?> 
												<?= esc($item['item_number']) ?>
												<?= esc($item['description']) ?>
											<?php endif; ?>

											<?php if (in_array($item['item_number'], ["4.4"], true)): ?> 

												<div class="w-80 text-white text-center bg-title bg-opacity-80 p-2">RESULTADO</div>
											<?php endif; ?>
										</div>
									</div>

									<div class="item--alt border-l border-b w-[10%]">
										<label class="label--check">
											<input type="radio" data-id="<?= esc($item['id']) ?>" name="items[<?= esc($item['id']) ?>][check]" data-value="si" class="checkbox_inspec hidden">
											<span class="checkbox-label-si"><i class="fas fa-check "></i></span>
										</label>
									</div>

									<div class="item--alt border-l border-b w-[10%]">
										<label class="label--check">
											<input type="radio" data-id="<?= esc($item['id']) ?>" name="items[<?= esc($item['id']) ?>][check]" data-value="no" class="checkbox_inspec hidden" >	
											<span class="checkbox-label-no"><i class="fas fa-x "></i></span>
										</label>
									</div>

									<div class="relative item--alt border-l border-b py-1 px-3 w-[15%] lg:w-[30%]">
										<?php if (in_array($item['item_number'], $items_espec_clear, true)): ?> 
											<button type="button" class="btn_clear_radio rounded-full h-8 w-8 absolute top-1 right-2 bg-red bg-opacity-70 text-white"><i class="fas fa-refresh"></i></button>
										<?php endif; ?> 
										

										<input type="text" data-section-title="<?= esc($title) ?>" name="items[<?= esc($item['id']) ?>][observacion]" class="w-full h-8 bg-white focus:bg-grayLight " placeholder="<?= esc($item['item_number'] . " .- N.A.") ?>" >
										<input type="hidden" name="items[<?= esc($item['id']) ?>][aprobado]" >
										<input type="hidden" name="items[<?= esc($item['id']) ?>][itemId]" value="<?= esc($item['id']) ?>">
									</div>

								</div>
							<?php endforeach; ?>

						<?php endforeach; ?>

					</div>
				</div>
			</div>


			<!-- Pagina 2 -->
			<div class=" page__container">
				<!-- Datos Formato Inspeccion -->
				<?php echo view("inspeccion/$slug/_partials/format-header"); ?>

				<!-- Datos Secciones Inspeccion -->
				<div class="list__section">
					<span>* Anotar con un <i class="fas fa-check"></i> cuando el material cumpla con cada criterio de análisis y marcar con un <i class="fas fa-close"></i> cuando esta NO cumpla. Si el material se rechaza se deberá realizar el reporte correspondiente.</span>

					<div class="w-full flex flex-col border-t  border-gray ">

						<?php foreach($page2Sections as $section): ?>
						<div class="list__row header-b border-r">
							<div class="col border-l border-b w-[65%] lg:w-[50%]"><?= esc($section['title']) ?></div>
							<div class="col border-l border-b w-[10%]">SI CUMPLE</div>
							<div class="col border-l border-b w-[10%]">NO CUMPLE</div>
							<div class="col border-l border-b w-[15%] lg:w-[30%]">
								<span>OBSERVACIONES</span>
								<!-- <button type="button" data-section-title="<?= esc($section['title']) ?>" class=" btn_na absolute -bottom-10 right-4 bg-icon text-white rounded-lg py-1 px-3 text-lg">N.A.</button> -->
								<!-- <button type="button" data-id="<?= esc($section['items'][0]['id']) ?>" data-section="<?= esc($section['title']) ?>" data-modal="modal_comm_unico" class="btn_open_modal absolute -bottom-12 right-4 border-gray text-icon p-1 text-4xl"><i class="fas fa-comment"></i></button> -->
							</div>

						</div>
					
							<?php foreach($section['items'] as $item): ?>
								<div class="list__row border-r">
									<div class="item border-l border-b w-[65%] lg:w-[50%]">
										<div class="flex w-full items-center justify-between ">

											<?php if (in_array($item['item_number'], $items_espec_page2, true)): ?> 
												<?= esc($item['item_number']) ?>
												<?= esc($item['description']) ?>
												<div class="ml-4 md:w-1/2 flex justify-end gap-2 px-2 text-gray rounded">

													<input type="hidden" value="NA" name="detalles[espec][<?= esc($item['id']) ?>]">

													<?php if (in_array($item['item_number'], $single_resultado, true)): ?> 
														<input type="text" placeholder="N.A." class="w-48 text-center border border-gray  " name="detalles[resultados][<?= esc($item['id']) ?>][]">
													<?php else: ?> 
														<?php if (in_array($item['item_number'], $items_checkbox, true)): ?> 
															<div class="w-64 flex items-center justify-around p-1">
																<input
																	type="hidden"
																	name="detalles[resultados][<?= esc($item['id']) ?>][]"
																	value="no"
																	id="resultado_<?= esc($item['id']) ?>_1"
																/>

																<input
																	type="checkbox"
																	class="checkbox_resultados border-2 border-grayMid  accent-title w-8 h-8 text-title"
																	data-id="<?= esc($item['id']) ?>"
																	data-target="resultado_<?= esc($item['id']) ?>_1"
																	data-value="si"
																/>

																<input
																	type="hidden"
																	name="detalles[resultados][<?= esc($item['id']) ?>][]"
																	value="no"
																	id="resultado_<?= esc($item['id']) ?>_2"
																/>

																<input
																	type="checkbox"
																	class="checkbox_resultados border-2 border-grayMid  accent-title w-8 h-8 text-title"
																	data-id="<?= esc($item['id']) ?>"
																	data-target="resultado_<?= esc($item['id']) ?>_2"
																	data-value="si"
																/>

																<input
																	type="hidden"
																	name="detalles[resultados][<?= esc($item['id']) ?>][]"
																	value="no"
																	id="resultado_<?= esc($item['id']) ?>_3"
																/>

																<input
																	type="checkbox"
																	class="checkbox_resultados border-2 border-grayMid  accent-title w-8 h-8 text-title"
																	data-id="<?= esc($item['id']) ?>"
																	data-target="resultado_<?= esc($item['id']) ?>_3"
																	data-value="si"
																/>


															</div>
														<?php else: ?> 
															<input type="text" placeholder="N.A." class="text-center w-24 border border-gray  " name="detalles[resultados][<?= esc($item['id']) ?>][]">
															<input type="text" placeholder="N.A." class="text-center w-24 border border-gray  " name="detalles[resultados][<?= esc($item['id']) ?>][]">
															<input type="text" placeholder="N.A." class="text-center w-24 border border-gray  " name="detalles[resultados][<?= esc($item['id']) ?>][]">
														<?php endif; ?>
													<?php endif; ?>
												</div>
												
											<?php else: ?> 
												<?= esc($item['item_number']) ?>
												<?= esc($item['description']) ?>
											<?php endif; ?>

											<?php if (in_array($item['item_number'], ["5.3","6.2"], true)): ?> 

												<div class="w-64 text-white text-center bg-title bg-opacity-80 p-2">RESULTADO</div>
											<?php endif; ?>
										</div>
									</div>

									<div class="item--alt border-l border-b w-[10%]">
										<label class="label--check">
											<input type="radio" data-id="<?= esc($item['id']) ?>" name="items[<?= esc($item['id']) ?>][check]" data-value="si" class="checkbox_inspec hidden">
											<span class="checkbox-label-si"><i class="fas fa-check "></i></span>
										</label>
									</div>

									<div class="item--alt border-l border-b w-[10%]">
										<label class="label--check">
											<input type="radio" data-id="<?= esc($item['id']) ?>" name="items[<?= esc($item['id']) ?>][check]" data-value="no" class="checkbox_inspec hidden" >	
											<span class="checkbox-label-no"><i class="fas fa-x "></i></span>
										</label>
									</div>

									<div class="relative item--alt border-l border-b py-1 px-3 w-[15%] lg:w-[30%]">
										<?php if (in_array($item['item_number'], $items_espec_clear, true)): ?> 
											<button type="button" class="btn_clear_radio rounded-full h-8 w-8 absolute top-1 right-2 bg-red bg-opacity-70 text-white"><i class="fas fa-refresh"></i></button>
										<?php endif; ?> 
										
										<input type="text" data-section-title="<?= esc($title) ?>" name="items[<?= esc($item['id']) ?>][observacion]" class="w-full h-8 bg-white focus:bg-grayLight " placeholder="<?= esc($item['item_number'] . " .- N.A.") ?>">
										<input type="hidden" name="items[<?= esc($item['id']) ?>][aprobado]" >
										<input type="hidden" name="items[<?= esc($item['id']) ?>][itemId]" value="<?= esc($item['id']) ?>">
									</div>

								</div>
							<?php endforeach; ?>

						<?php endforeach; ?>

					</div>
				</div>

				<!-- Datos Etiqueta Inspeccion -->
				<div class="etiqueta__section">

					<div class="file__section border-t border-l  ">

						<div class="file__section--title header-b border-b border-r  ">
							<?= esc($etiqueta['description']) ?>
							<input type="hidden" name="items[<?= esc($etiqueta['id']) ?>][itemId]" value="<?= esc($etiqueta['id']) ?>">
							<input type="hidden" name="items[<?= esc($etiqueta['id']) ?>][aprobado]" >
							<input type="hidden" name="items[<?= esc($etiqueta['id']) ?>][observacion]" class="url_etiqueta">
						</div>

						<div class="flex flex-col items-center justify-center gap-y-6 w-full h-96 border-r  border-gray ">

							<div class="flex flex-col items-center justify-center gap-y-6 mx-auto w-1/2 ">

								<img id="img_etiqueta_insp" class="h-64 w-full bg-grayLight   border-none object-cover ">  

								<button data-input="cameraInput" data-img="img_etiqueta" data-modal="modal_etiqueta" class="btn_open_modal btn_open_camera pdf-button " type="button"  >
									<span class="text-lg ">Cargar imagen</span>
								</button>

							</div>
						</div>

						<div class="file__section--value border-r border-t border-b">
							<label class="label--check">
								<span>Aprobado</span>
								<input type="radio" data-id="<?= esc($etiqueta['id']) ?>" name="items[<?= esc($etiqueta['id']) ?>][check]" data-value="si" class="checkbox_inspec hidden">
								<span class="checkbox-label-si"><i class="fas fa-check "></i></span>
							</label>

							<label class="label--check">
								<span>Rechazado</span>
								<input type="radio" data-id="<?= esc($etiqueta['id']) ?>" name="items[<?= esc($etiqueta['id']) ?>][check]" data-value="no" class="checkbox_inspec hidden" >	
								<span class="checkbox-label-no"><i class="fas fa-x "></i></span>
							</label>
						</div>

					</div>

					<div class="w-full flex justify-center pt-12 ">
						<button data-modal="modal_confirm" class="btn_open_modal text-2xl pdf-button" type="button" >
							<i class="fas fa-save "></i>
							<span>Guardar</span>
						</button>
					</div>


					<div class=" hidden firmas__section ">
						<div class="firmas__firma  border-t border-l  ">

							<div class="firma__header header-b border-b border-r ">
								ALMACEN DE MATERIALES Y MATERIAS PRIMAS
							</div>

							<div class="flex flex-col text-center justify-between w-full h-64  text-gray  border-r  border-gray p-4 ">
								<img id="almacen_firma" class="h-44 w-full ">  
								<p id="almacen_nombre"></p>

								<select id="almacenId" class="my-2 text-center to_uppercase" required>
									<option value="" >Seleccionar ....</option>
									<?php foreach ($almacen as $user): ?>
										<option value="<?= $user['id'] ?>"><?= $user['name'] . ' ' . $user['last_name']?></option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="flex firma__footer header-b border-b border-r border-t ">
								<span>Nombre y Firma</span>
							</div>

							<div class="flex firma__footer border-r border-b ">
								<button data-modal="modal_pin" class="btn_open_modal flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">Ingresar PIN
								</button>
							</div>
						</div>

						<div class="firmas__firma  border-t border-l  ">
							<div class="firma__header header-b border-b border-r ">
								Control de calidad
							</div>

							<div class="flex flex-col text-center justify-between w-full h-64  text-gray  border-r  border-gray p-4 ">
								<img id="calidad_firma" class="h-44 w-full ">  
								<!-- <p id="calidad_nombre"><?= session()->get('user')['name'] . ' ' . session()->get('user')['last_name'] ?></p> -->
								<p id="calidad_nombre"></p>
							</div>

							<div class="flex firma__footer header-b border-b border-r border-t ">
								<span>Nombre y Firma</span>
							</div>

							<div class="flex firma__footer border-r border-b ">
								<button data-field="firma_calidad" data-id="" data-user-id="<?= session()->get('user')['id'] ?>" data-area="calidadId" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
									Firmar
								</button>
							</div>


						</div>

					</div>

				</div>
			</div>

			<!-- Pagina 3 -->
			<div class=" page__container">
				<!-- Datos Adjunto Inspeccion -->
				<div class="adjunto__section border">

					<div class="file__section--title header-b border-b border-r">
						<span class="px-20 font-semibold"><?= esc($adjunto['description']) ?></span>
						<button id="add-image-btn" class="flex items-center bg-white gap-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-5 w-fit text-base" type="button"  >
							<i class="fas fa-plus"></i>
							<span>Agregar página</span>
						</button>
					</div>

					<div id="image-grid" class="grid grid-cols-1 gap-2 lg:grid-cols-2">
						<!-- first element adjunto file -->
						<div class="image-box flex p-4">
							<div class="file__container">
								<button data-input="fileInput" data-img="image-1" data-modal="modal_files" class="btn_open_modal btn_open_camera" type="button">Cargar Pag. 1</button>
								<span><i class="fas fa-check fa-3x"></i></span>
								<img id="image-1-saved">  
							</div>
						</div>
					</div>

				</div>
			</div>


			<input type="hidden" id="inspId" value="<?= esc($inspId ?? '') ?>">
		</form>

	</div>

<!-- Modal Certificado -->
<div id="modal_files" class="hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
	<div class=" flex flex-col gap-y-6 mx-4 bg-white border-2 border-icon p-6 w-full lg:max-w-2xl h-[90%]">

		<div class="relative flex w-full justify-center text-center ">
			<h3 class="text-gray text-xl uppercase">Certificado</h3>
			<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
		</div>

		<img id="image-preview" alt="Captured Photo" class="flex justify-center items-center mx-auto w-full h-[82%] border-title border-2 rounded">

		<div class="flex w-full  mx-auto justify-between text-sm ">
			<button data-input="fileInput" class=" btn_retry flex space-x-4 shadow-bottom-right text-error border-2 border-error hover:bg-error hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
				REINTENTAR
			</button>
			<button id="btn_upload_file" class=" btn_save_file flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
				GUARDAR
			</button>
		</div>

	</div>

	<input type="file" id="fileInput" accept="image/*" capture="environment" class="hidden">
</div>

<!-- Modal etiqueta -->
<div id="modal_etiqueta" class="hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
	<div class=" flex flex-col gap-y-6 mx-4 bg-white border-2 border-icon p-6 w-full lg:max-w-2xl h-[60%]">

		<div class="relative flex w-full justify-center text-center ">
			<h3 class="text-gray text-xl uppercase">Etiqueta</h3>
			<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
		</div>

		<img id="img_etiqueta" alt="Captured Photo" class="flex justify-center items-center mx-auto w-full h-[70%] border-title border-2 rounded">

		<div class="flex w-full  mx-auto justify-between text-sm ">
			<button data-input="cameraInput" class="btn_retry flex space-x-4 shadow-bottom-right text-error border-2 border-error hover:bg-error hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
				REINTENTAR
			</button>
			<button data-item="<?= esc($etiqueta['id']) ?>" data-img="img_etiqueta" data-modal="modal_etiqueta" class=" btn_save_tag flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
				GUARDAR
			</button>
		</div>

	</div>

	<input type="file" data-img="img_etiqueta" id="cameraInput"  capture="environment" class="hidden">
</div>

<!-- Modal confirm -->
<div id="modal_confirm" class="hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
	<div class=" flex flex-col  items-center gap-y-6 mx-4 bg-white border-2 border-icon p-6 w-full lg:max-w-2xl h-[50%]">

		<div class="relative flex w-full justify-center text-center ">
			<h3 class="text-gray text-xl uppercase">Confirmar</h3>
			<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
		</div>

		<div class="relative flex w-full justify-center text-center  py-20 ">
			<h3 class="text-gray text-3xl">¿Seguro de guardar cambios?</h3>
		</div>

		<div class="flex w-full items-center justify-center text-sm ">
			<button id="btn_submit" class="  flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
				CONFIRMAR
			</button>
		</div>

	</div>

</div>


<!-- Modal Comentario unico -->
<div id="modal_comm_unico" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
	<div class=" flex flex-col gap-y-8 bg-white mx-4 border-2 border-icon p-8 w-full md:w-[500px]">

		<div class="relative flex w-full justify-center text-center ">
			<h3 class="text-gray text-xl uppercase">Comentario único</h3>
			<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
		</div>

		<div class="relative flex w-full justify-center text-center ">
			<p id="section_title" class="text-gray"></p>
		</div>

		<textarea id="modal_textarea" class="p-4 w-full border border-grayMid outline-none resize-none bg-grayLight text-gray drop-shadow " rows="3" placeholder="Escribe tu comentario..."></textarea>

		<div class="flex justify-end space-x-12 text-sm ">
			<button id="btn_save_comment" class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
				GUARDAR
			</button>
		</div>

	</div>
</div>

<!-- Modal Pin -->
<div id="modal_pin" class="hidden fixed inset-0 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
	<div class=" flex flex-col space-y-8 bg-white border-2 border-icon p-10 w-full md:w-[600px]">

		<div class="relative flex w-full justify-center text-center ">
			<h3 class="text-gray text-xl uppercase">Ingresar PIN</h3>
			<div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
		</div>

		<div class="bg-white p-6 flex flex-col gap-y-8 text-center w-full">

			<input id="pinInput" type="text" class="w-full p-3 border rounded text-center text-2xl" readonly>

			<div class="grid grid-cols-3 gap-4 ">
				<button type="button" class="pin-btn">1</button>
				<button type="button" class="pin-btn">2</button>
				<button type="button" class="pin-btn">3</button>
				<button type="button" class="pin-btn">4</button>
				<button type="button" class="pin-btn">5</button>
				<button type="button" class="pin-btn">6</button>
				<button type="button" class="pin-btn">7</button>
				<button type="button" class="pin-btn">8</button>
				<button type="button" class="pin-btn">9</button>
				<div></div>
				<button type="button" class="pin-btn col-span-1">0</button>
				<div></div>
			</div>

			<p id="pin-response" class="text-warning text-lg"></p>

			<div class="flex w-full justify-between text-sm">
				<button id="clearPin" class=" flex space-x-4 shadow-bottom-right text-error border-2 border-error hover:bg-error hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >Limpiar</button>

				<button data-field="firma_almacen" data-area="almacenId" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">	Firmar</button>

			</div>

		</div>

	</div>
</div>

<script>
Service.setLoading();


const initClearRadioButtons = () => {
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn_clear_radio');
        if (!btn) return;

        const row = btn.closest('.list__row');
        if (!row) return;

        row.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.checked = false;
        });
    });
};

initClearRadioButtons();

let inspId = document.querySelector("#inspId");

const grid = document.getElementById('image-grid');
const btn_add_image = document.getElementById('add-image-btn');
btn_add_image.addEventListener('click', () => {
	const imageCount = grid.querySelectorAll('img[id^="image-"]').length;
	const newImageId = imageCount + 1;

	const wrapper = document.createElement('div');
	wrapper.className = 'flex p-4';
	wrapper.innerHTML = `
		<div class="file__container">
			<button data-input="fileInput" data-img="image-${newImageId}" data-modal="modal_files" class="btn_open_modal btn_open_camera" type="button">Cargar Pag. ${newImageId}</button>

			<span>
				<i class="fas fa-check fa-3x"></i>
			</span>

			<img id="image-${newImageId}-saved">  
		</div>
	`;

	grid.appendChild(wrapper);

	initRowBtn();
	initBtnUpload();
});



document.getElementById("clearPin").addEventListener("click", function() {
	document.getElementById("pinInput").value = "";
	document.querySelector("#pin-response").innerText = "";
});

document.querySelectorAll(".pin-btn").forEach(button => {
	button.addEventListener("click", function() {
		document.getElementById("pinInput").value += this.textContent;
	});
});


document.querySelectorAll('input[type="text"]').forEach(input => {
  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault(); // Don't submit the form
    }
  });
});

const lockChecklistRadios = () => {
  document.querySelectorAll('.checkbox_inspec').forEach(radio => {
    if (radio.checked) {
      const name = radio.name;
      document.querySelectorAll(`input[name="${name}"]`).forEach(input => {
        input.disabled = true;
      });
    }
  });
};




	const previewPhoto = (e) => {
		Service.show('.loading');

		const file = e.target.files[0];
		if (file) {

			let img = e.target.getAttribute('data-img');
			// console.log(img)
		
			const reader = new FileReader();
			reader.onload = function(event) {

				let preview = '';

				if(img == "img_etiqueta") {
					preview = document.getElementById(img);
				} else {
					preview = document.getElementById("image-preview");
				}

				preview.src = event.target.result; 

				Service.hide('.loading');
			};

			document.body.classList.add('no-scroll');

			reader.readAsDataURL(file);
		}
	}

	const cameraInput = document.querySelector('#cameraInput');
	cameraInput?.addEventListener('change', previewPhoto);

	const fileInput = document.querySelector('#fileInput');
	fileInput?.addEventListener('change', previewPhoto);



	allBtnTag = document.querySelectorAll('.btn_save_tag')
	allBtnTag.forEach( btn => {
		btn?.addEventListener('click', (e) => {
			let img = btn.getAttribute('data-img');
			let modal = btn.getAttribute('data-modal');
			let item = btn.getAttribute('data-item');

			let input_file = document.querySelector(`input[data-img="${img}"]`);
			let button_modal = document.querySelector(`button[data-modal="${modal}"].btn_open_camera`);

			let modal_active = document.querySelector('.modal_active');
			if (modal_active) {
				modal_active.classList.add('hidden');
				modal_active.classList.remove('modal_active');
			}

			if (img === "img_etiqueta") {

				const file = input_file.files[0];

				if (!file) return;

				const formData = new FormData();
				formData.append('etiqueta', file);
				formData.append('tipo', 'etiqueta');

				// debugFormData(formData);

				Service.show('.loading');

				let image = document.querySelector('#img_etiqueta_insp');

				Service.exec('post', `/upload/inspeccion`, formData_header, formData)
				.then(r => {
					button_modal.style.display = "none";

					const input_obs = document.querySelector(`input[name="items[${item}][observacion]"`);
					
					input_obs.value = r.url;

					const img_new = new Image();
					
					img_new.onload = function() {
						setTimeout( () => {
							Service.hide('.loading');
							document.body.classList.remove('no-scroll');
							image.src = `${root}/files/download?path=${r.url}`;
						}, 3000)

					};
					
					img_new.src = `${root}/files/download?path=${r.url}`;

				});  

			} 
		});
	});



	const initBtnUpload = () => {

		allBtnCamera = document.querySelectorAll('.btn_open_camera')
		allBtnCamera.forEach( btn => {
			if (btn.dataset.bound === 'true') return; 
			btn.dataset.bound = 'true';

			btn.addEventListener('click', (e) => {
				let img = e.currentTarget.getAttribute('data-img');
				let input = e.currentTarget.getAttribute('data-input');

				document.getElementById("btn_upload_file").setAttribute('data-image', img); 
				document.getElementById("image-preview").src = "<?= base_url('img/no_img_alt.png') ?>";
				console.log(img);

				if(img == "img_etiqueta") {
					document.getElementById(img).src = "<?= base_url('img/no_img_alt.png') ?>";
				}
				
				document.getElementById(input).value = ''; 
				document.getElementById(input).setAttribute('data-img', img); 
				document.getElementById(input).click();
			});
		})

		allBtnRetry = document.querySelectorAll('.btn_retry')
		allBtnRetry.forEach( btn => {
			if (btn.dataset.bound === 'true') return; 
			btn.dataset.bound = 'true';

			btn.addEventListener('click', (e) => {
				let input = e.currentTarget.getAttribute('data-input');
				document.getElementById(input).value = ''; 
				document.getElementById(input).click();
			});
		})


		allBtnFile = document.querySelectorAll('.btn_save_file')
		allBtnFile.forEach( btn => {
			if (btn.dataset.bound === 'true') return; 
			btn.dataset.bound = 'true';

			btn?.addEventListener('click', (e) => {

				let img = btn.getAttribute('data-image');

				let fileInput = document.querySelector(`#fileInput`);
				let button_modal = document.querySelector(`button[data-img="${img}"].btn_open_modal`);
				let icon_success = button_modal.nextElementSibling;

				// console.log(fileInput.files[0]);
				// console.log(fileInput.value);
				// console.log(icon_success);
				// console.log(button_file);
				// return;

				let modal_active = document.querySelector('.modal_active');
				if (modal_active) {
					modal_active.classList.add('hidden');
					modal_active.classList.remove('modal_active');
					document.body.classList.remove('no-scroll');
				}

				let file = fileInput.files[0];

				if (!inspId.value || !file) return;

				const formData = new FormData();
				formData.append('certif', file);
				formData.append('inspeccionId', inspId.value);
				formData.append('tipo', 'certif');

				Service.show('.loading');

				Service.exec('post', `/upload/inspeccion`, formData_header, formData)
				.then(r => {
					button_modal.style.display = "none";
					icon_success.style.display = "flex";

					fileInput.value = '';

					document.querySelector(`#${img}-saved`).src = `${root}/files/download?path=${r.url}`;

					Service.hide('.loading');
				});  

			});
		});
	}

	initBtnUpload();



const pages = document.querySelectorAll('.page__container');
const totalPages = <?= esc($formato['paginas']) ?>

pages.forEach((page, index) => {
	const numElem = page.querySelector('.num-page');
	if (numElem) {
		numElem.textContent = `${index + 1} de ${totalPages}`;
	}
});



const generalItems = <?= json_encode($general) ?>;
const inputs = document.querySelectorAll("input[data-field]");
// console.log(inputs)

inputs.forEach(input => {
	const label = input.dataset.field?.trim().toUpperCase();
	// console.log(label)

	const item = generalItems.find(i => i.description.trim().toUpperCase() === label);

	if (item) {
		input.name = `items[${item.id}][observacion]`;

		// const parent = input.parentElement;
		
		const aprobadoInput = document.createElement("input");
		aprobadoInput.type = "hidden";
		aprobadoInput.name = `items[${item.id}][aprobado]`;
		aprobadoInput.value = "si";

		const itemIdInput = document.createElement("input");
		itemIdInput.type = "hidden";
		itemIdInput.name = `items[${item.id}][itemId]`;
		itemIdInput.value = item.id;

		input.parentElement.appendChild(aprobadoInput);
		input.parentElement.appendChild(itemIdInput);
	}
});


const toggleCheckboxItems = (e) => {
  const checkbox = e.target;
  const targetId = checkbox.dataset.target;
  const hiddenInput = document.getElementById(targetId);

  hiddenInput.value = checkbox.checked ? 'si' : 'no';
	console.log(hiddenInput);	
};

const allCheckboxToggleItems = document.querySelectorAll('.checkbox_resultados');

allCheckboxToggleItems.forEach(input => {
  input.addEventListener('change', toggleCheckboxItems);
});


const toggleCheckbox = (e) => {
	let _val = e.target.getAttribute('data-value');
	let id = e.target.getAttribute('data-id');

	document.querySelector(`input[name="items[${id}][aprobado]"]`).value = _val;
	// console.log(_val);
};

const allCheckboxToggle = document.querySelectorAll('.checkbox_inspec');
allCheckboxToggle?.forEach(input => {
	input.addEventListener('change', toggleCheckbox);
});


const allMomentDate = document.querySelectorAll('.moment-date');
allMomentDate?.forEach( p => {
	p.innerText = formatToMonthYear(p.innerText);
});
			


			
const modal_comm_unico = document.querySelector('#modal_comm_unico');
const modal_textarea = document.querySelector('#modal_textarea');
const section_title = document.querySelector('#section_title');

const btn_save_comment = document.querySelector('#btn_save_comment');
btn_save_comment.addEventListener('click', (e) => {

	let title = e.currentTarget.getAttribute('data-section-title');
			// console.log(e.currentTarget)

	
		// let comm_container = 	document.querySelector(`div[data-com-id="${id}"]`);	
		let comm_input = 	document.querySelectorAll(`input[data-section-title="${title}"]`);	
		// console.log(comm_input)

	if (modal_textarea.value) {
		comm_input.forEach( input => {
			input.value = modal_textarea.value;	
		})
			
		// comm_container.innerHTML = modal_textarea.value;	
		// comm_container.classList.remove('hidden');

	}

	modal_comm_unico.classList.add('hidden');
	modal_comm_unico.classList.remove('modal_active');
	document.body.classList.remove('no-scroll');

});


const allBtnNa = document.querySelectorAll('.btn_na');
allBtnNa?.forEach( btn => {
	btn.addEventListener('click', (e) => {
		let title = e.currentTarget.getAttribute('data-section-title');
		let comm_input = 	document.querySelectorAll(`input[data-section-title="${title}"]`);	

		comm_input.forEach( input => {
			input.value = "N.A.";	
		})

	});
});


const initRowBtn = () => {
	const allInputToUpper = document.querySelectorAll('.to_uppercase');
	allInputToUpper?.forEach( input => {
		input.addEventListener('input', e => {
			input.value = e.target.value.toUpperCase();
		});
	});

	const allBtnOpen = document.querySelectorAll('.btn_open_modal');
	allBtnOpen?.forEach( (btn, index) => {

		btn.addEventListener('click', e => {
			// console.log(e.currentTarget)

			let modal_id = e.currentTarget.getAttribute('data-modal');
			let modal = document.querySelector(`#${modal_id}`);
			// console.log(modal_id)

			modal.classList.add('modal_active');
			modal.classList.remove('hidden');
			document.body.classList.add('no-scroll');

			if (modal_id == 'modal_comm_unico') {

				modal_textarea.value = '';

				let section = e.currentTarget.getAttribute('data-section');

				let id = e.currentTarget.getAttribute('data-id');
				btn_save_comment.setAttribute('data-section-title', section);

				section_title.innerHTML = section;

				currentCommentInput = document.querySelector(`input[name="items[${id}][observacion]"]`);
				// console.log(currentCommentInput)

				modal_textarea.value = currentCommentInput.value || '';
			}

		});
	});

	const allBtnClose = document.querySelectorAll('.btn_close_modal')
	allBtnClose?.forEach( btn => {
		btn.addEventListener('click', (e) => {
			let modal_active = document.querySelector('.modal_active');
			if (modal_active) {
				modal_active.classList.add('hidden');
				modal_active.classList.remove('modal_active');
				document.body.classList.remove('no-scroll');

				let pin = document.querySelector('#pinInput');
				if(pin) {
					pin.value = '';
					document.querySelector("#pin-response").innerText = '';
				}

			}
		});
	});

}

initRowBtn();



const modal_confirm = document.querySelector('#modal_confirm');
const almacenId = document.querySelector('#almacenId');

almacenId?.addEventListener('change', e => {
	firma_almacen.setAttribute('data-user-id', e.target.value.trim());
	// console.log(firma_almacen)
});

let firma_almacen = document.querySelector('button[data-field="firma_almacen"]');
let firma_calidad = document.querySelector('button[data-field="firma_calidad"]');



// Function to check if it's mobile
function isMobile() {
  return window.innerWidth <= 1024; // Adjust based on your breakpoints
}

// Function to toggle the disabled state of inputs based on screen size
function toggleInputs(itemNumbers) {
  itemNumbers.forEach(number => {
    const mobileInput = document.querySelector(`input[name="items[${number}][observacion]"]:nth-of-type(1)`);
    const desktopInput = document.querySelector(`input[name="items[${number}][observacion]"]:nth-of-type(2)`);

    if (isMobile()) {
      // On mobile, disable the desktop input and enable the mobile input
      if (desktopInput) desktopInput.disabled = true;
      if (mobileInput) mobileInput.disabled = false;
    } else {
      // On desktop, disable the mobile input and enable the desktop input
      if (mobileInput) mobileInput.disabled = true;
      if (desktopInput) desktopInput.disabled = false;
    }
  });
}

// Function to collect FormData with the correct value from the appropriate input
function getCorrectInputValues(itemNumbers, form_id) {
  const formData = new FormData(document.getElementById(form_id));
	formData.append('slug', 'materiales')
	
  itemNumbers.forEach(number => {
    const inputs = document.querySelectorAll(`input[name="items[${number}][observacion]"]`);
    const correctInput = isMobile() ? inputs[0] : inputs[1]; // Mobile or desktop
    formData.set(`items[${number}][observacion]`, correctInput.value);
  });

  return formData;
}

// Detect all unique item numbers dynamically by inspecting the rendered inputs
function detectItemNumbers() {
  const itemNumbers = [];
  const inputs = document.querySelectorAll('.general__section input[name^="items["]');

  // Loop through each input and extract the item number
  inputs.forEach(input => {
    const match = input.name.match(/items\[(\d+)\]\[observacion\]/); // Regex to extract the number
    if (match) {
      const itemNumber = match[1]; // The number (ID) of the item
      if (!itemNumbers.includes(itemNumber)) {
        itemNumbers.push(itemNumber); // Add unique item numbers to the list
      }
    }
  });

  return itemNumbers;
}

const itemNumbers = detectItemNumbers();


// Initialize and setup the inputs
  // Detect item numbers dynamically
toggleInputs(itemNumbers);  // Pass detected numbers to toggleInputs()

// Listen to window resize events to toggle inputs based on screen size
window.addEventListener('resize', () => {
  toggleInputs(itemNumbers); // Reapply the toggling logic on resize
});

// // Example usage
// // const updatedFormData = getCorrectInputValues(itemNumbers);
// // console.log([...updatedFormData.entries()]);







const btn_submit = document.querySelector('#btn_submit');
btn_submit?.addEventListener('click', e => {

	let etiqueta = document.querySelector('.url_etiqueta');
	let firmas__section = document.querySelector('.firmas__section');


	// if(etiqueta.value !== "") {
		Service.stopSubmit(e.target, true);
		Service.show('.loading');

		const formData = getCorrectInputValues(itemNumbers, 'form_inspeccion');
	
		// debugFormData(formData); return;

		Service.exec('post', `inspeccion/materiales`, formData_header, formData)
		.then(r => {
			if(r.inspeccionId){
				inspId.value = r.inspeccionId;

				Service.hide('.loading');
				Service.hide('#btn_submit');
				lockChecklistRadios();

				firma_almacen.setAttribute('data-id', r.inspeccionId);
				firma_calidad.setAttribute('data-id', r.inspeccionId);

				modal_confirm.classList.remove('modal_active');
				modal_confirm.classList.add('hidden');
				document.body.classList.remove('no-scroll');

				firmas__section.classList.add('flex');
				firmas__section.classList.remove('hidden');

				Service.stopSubmit(e.target, false);
			}
		});

	// } else {
	// 	console.log('not ready')
		// mostrar mensaje es obligatorio subir etiqueta

	// }
});



const initFirmas = (inspeccionId) => {

	Service.exec('get', `/get_firmas_insp/${inspeccionId}`)
	.then( r => {

		firma_almacen.setAttribute('data-id', inspeccionId);
		firma_calidad.setAttribute('data-id', inspeccionId);


		if ( r.almacen && r.almacen.name ) {
			document.querySelector('#almacen_nombre').textContent = `${r.almacen.name} ${r.almacen.last_name}`; 
		}

		if ( r.calidad && r.calidad.name ) {
			document.querySelector('#calidad_nombre').textContent = `${r.calidad.name} ${r.calidad.last_name}`; 
		}


		if ( r.inspeccion.firma_calidad == "si" ) {
			document.querySelector('#calidad_firma').src = `${root}/files/download?path=${r.calidad.signature}`; 
		} else {
			document.querySelector('#calidad_firma').src = `${root}/img/no_img_alt.png`; 
		}

		if ( r.inspeccion.firma_almacen == "si" ) {
			document.querySelector('#almacen_firma').src = `${root}/files/download?path=${r.almacen.signature}`; 
		} else {
			document.querySelector('#almacen_firma').src = `${root}/img/no_img_alt.png`; 
		}

	});

}


const initFiles = (inspeccionId) => {
	Service.exec('get', `/get_files_insp/${inspeccionId}`)
	.then( r => {

		if ( r.file.archivo ) {
			document.querySelector('#img_certif_insp').src = `${root}/files/download?path=${r.file.archivo}`; 
		} else {
			document.querySelector('#img_certif_insp').src = `${root}/img/no_img_alt.png`; 
		}

	});
}


const submitFirma = (e) => {
	let field = e.target.getAttribute('data-field');
	let id = e.target.getAttribute('data-id');
	let userId = e.target.getAttribute('data-user-id');
	let area = e.target.getAttribute('data-area');
	let pin = document.querySelector('#pinInput');

	// console.log(field, id); return;

	e.target.disabled = true;

	const formData = new FormData();
	formData.append('field', field);
	formData.append('inspeccionId', id);

	if(userId !== undefined) {
		formData.append('userId', userId);
		formData.append('area', area);
	}

	if(pin.value !== undefined) {
		formData.append('pin', pin.value);
	}

	Service.show('.loading');

	// const formObject = {};
	// formData.forEach((value, key) => {
	// 	formObject[key] = value;
	// });
	// console.log(formObject);

	// return;

	Service.exec('post', `${root}/add_firma_insp`, formData_header, formData)
	.then( r => {
		if(r.success) {

			let modal_active = document.querySelector("#modal_pin");
			modal_active.classList.remove('modal_active');
			modal_active.classList.add('hidden');
			document.body.classList.remove('no-scroll');

			pin.value = '';
			document.querySelector("#pin-response").innerText = "";

			e.target.disabled = false;
			initFirmas(r.inspeccionId); 
			Service.hide('.loading');
		} else {
			document.querySelector("#pin-response").innerText = r.message;
			e.target.disabled = false;
			Service.hide('.loading');
		}

	});
}

const allBtnFirmar = document.querySelectorAll('.btn_firmar');
	allBtnFirmar?.forEach( btn => {
	btn.addEventListener('click', submitFirma);
});

</script>
</body>
</html>
