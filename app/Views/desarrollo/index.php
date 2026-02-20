<?php echo view('_partials/header'); ?>

  <link rel="stylesheet" href="<?= base_url('_partials/mtickets.css') ?>">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">

  <img src="<?= base_url('img/nuevo_desarrollo.svg') ?>" class="hidden lg:flex absolute bottom-0 left-0 ">
  <img src="<?= base_url('img/nuevo_desarrollo.svg') ?>" class="flex lg:hidden absolute bottom-0 left-0 ">

    <div class=" relative h-full flex flex-col items-center justify-center font-titil ">

      <?php echo view('cotizar/_partials/navbar'); ?>

      <a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer">Cerrar Sesión</a>

      <div class="text-title w-full md:pt-4 md:pb-8 md:px-16 p-2 flex items-center ">
        <h2 class="text-center font-bold w-full text-2xl lg:text-3xl "><?= esc($title) ?></h2>
        <a href="<?= base_url('apps') ?>" class="hidden md:flex self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
      </div>

    <?php  
      echo "<pre >";
      // print_r($user_id);
      // print_r($_SESSION);
      echo "</pre>";
     ?>

			<div class="w-full pl-10 pr-8 ">
				<div class="relative w-full flex flex-col gap-y-8 text-sm h-[75vh] overflow-y-scroll ">

					<!-- paso 1 -->
					<div class="flex flex-col gap-y-8">

						<h3 class="pt-12 text-center text-gray text-2xl font-semibold">Datos del Producto</h3>

						<div class="pt-8 grid md:grid-cols-3 gap-x-4 gap-y-8 place-items-center lg:flex lg:justify-center w-full pt-8 lg:pt-2 ">
              
							<div class="w-fit items-center flex flex-col "> 
								<span class=" text-gray uppercase">Fecha</span>
								<span class="text-white  py-1 px-4 bg-icon uppercase"><?php echo date('d-m-Y'); ?></span> 
							</div>


              <div class="relative flex flex-col w-3/4 md:w-56">
                <h5 class="text-center uppercase">Nombre Cliente</h5>
                <input type="text" id="cliente" name="cliente" placeholder="Razon social cliente" required>
              </div>

							<div class="relative flex flex-col w-3/4 md:w-80">
                <h5 class="text-center uppercase">Nombre del Producto</h5>
                <input type="text" id="product" name="product" required>
              </div>

							<div class="relative flex flex-col w-3/4 md:w-56">
                <h5 class="text-center uppercase">Clave</h5>
                <input type="text" id="clave" name="clave" placeholder="ejemplo: AA-NNN-V" required>
              </div>


							<div class="flex flex-col w-3/4 md:w-56">
                <h5 class="text-center uppercase">Linea De Producción</h5>
								<select id="linea" name="linea" >
									<option value="" disabled selected>Seleccionar...</option>
								</select>
              </div>


            </div>

						<h3 class="pt-12 text-center text-gray text-2xl font-semibold">Especificaciones del Producto</h3>

						<div class="mb-4 grid md:grid-cols-3 gap-x-4 gap-y-8 place-items-center lg:flex lg:justify-center w-full pt-8 lg:pt-2 ">
              
							<div class="relative flex flex-col w-3/4 md:w-56">
                <h5 class="text-center uppercase">Gramaje</h5>
                <input type="text" id="gramos" name="gramos" required>
              </div>

							<div class="relative flex flex-col w-3/4 md:w-56">
                <h5 class="text-center uppercase">Tamaño de porción</h5>
                <input type="text" id="porcion" name="porcion" required>
              </div>
							<div class="relative flex flex-col w-3/4 md:w-56">
                <h5 class="text-center uppercase">Porción por Envase</h5>
                <input type="text" id="porciones" name="porciones" required>
              </div>
            </div>

						<div class="flex flex-col pt-10 gap-y-4 border-b border-gray">
							<p class="text-xl text-center text-gray">Paso 1 de 6</p>
						</div>
					</div>

					<!-- paso 2 -->
					<div class="flex flex-col gap-y-8">

						<h3 class="pt-12 text-center text-gray text-2xl font-semibold">Fórmula</h3>

						<h3 class=" text-center text-gray text-xl ">Materias Primas de linea</h3>

						<div class="flex flex-col w-full items-start gap-y-4 lg:px-12 ">

							<div class="relative w-full text-sm h-64 overflow-y-scroll ">
								<table id="tabla-materias-linea" >
									<thead>
										<tr>
											<th>Item</th>
											<th>SAP</th>
											<th>Materia Prima/Ingrediente</th>
											<th>Por porcion</th>
											<th>%</th>
											<th>Kg</th>
											<th>% SAP + Merma</th>
											<th>Costo</th>
											<th>Act</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><div class="w-full flex justify-center">1</div></td>
											<td><input name="sap[]" type="text" class="input_alt to_uppercase" required placeholder="Ingresa codigo"></td>
											<td><input name="articulo[]" type="text" class="input_alt" required placeholder="Nombre del Articulo"></td>
											<td><input name="porcion[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td><input name="porcentaje[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td><input name="peso[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
            
											<td><input name="calculo[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td><input name="costo[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td>
												<button class="btn-delete text-gray hover:text-red  mx-auto" type="button"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
												<path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
											</svg></button>
											</td>
										</tr>
									</tbody>
								</table>
							</div>

							<div class=" w-full flex flex-col space-y-8 lg:flex-row items-center lg:space-x-8 lg:space-y-0 justify-end  ">
								<h5 class="text-center uppercase">Costo Primo</h5>
								<input type="text" id="costo_primo" name="costo_primo" class="w-64" required>
							</div>

							<button type="button" data-table="tabla-materias-linea" class="btn_add_row self-end rounded text-icon border-2 border-icon hover:bg-icon hover:text-white px-4 py-2 w-fit" type="button"><i class="fa fa-plus text-lg"></i></button>

						</div>

						<h3 class="pt-12 text-center text-gray text-xl ">Materias Primas Nuevas</h3>

						<div class="flex flex-col w-full items-start gap-y-4 lg:px-12 ">

							<div class="relative w-full text-sm h-64 overflow-y-scroll ">
								<table id="tabla-materias-nuevo" >
									<thead>
										<tr>
											<th>Item</th>
											<th>SAP</th>
											<th>Materia Prima/Ingrediente</th>
											<th>Por porcion</th>
											<th>%</th>
											<th>Kg</th>
											<th>% SAP + Merma</th>
											<th>Costo</th>
											<th>Act</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><div class="w-full flex justify-center">1</div></td>
											<td><input name="sap[]" type="text" class="input_alt to_uppercase" required placeholder="Ingresa codigo"></td>
											<td><input name="articulo[]" type="text" class="input_alt" required placeholder="Nombre del Articulo"></td>
											<td><input name="porcion[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td><input name="porcentaje[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td><input name="peso[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
            
											<td><input name="calculo[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td><input name="costo[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td>
												<button class="btn-delete text-gray hover:text-red  mx-auto" type="button"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
												<path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
											</svg></button>
											</td>
										</tr>
									</tbody>
								</table>
							</div>

							<div class=" w-full flex flex-col space-y-8 lg:flex-row items-center lg:space-x-8 lg:space-y-0 justify-end  ">
								<h5 class="text-center uppercase">Costo Primo</h5>
								<input type="text" id="costo_primo" name="costo_primo" class="w-64" required>
							</div>

							<button type="button" data-table="tabla-materias-nuevo" class="btn_add_row self-end rounded text-icon border-2 border-icon hover:bg-icon hover:text-white px-4 py-2 w-fit" type="button"><i class="fa fa-plus text-lg"></i></button>

						</div>


						<div class="flex flex-col pt-10 gap-y-4 border-b border-gray">
							<p class="text-xl text-center text-gray">Paso 2 de 6</p>
						</div>
					</div>

					<!-- paso 3 -->
					<div class="flex flex-col gap-y-8">

						<h3 class="pt-12 text-center text-gray text-2xl font-semibold">Materiales</h3>

						<h3 class=" text-center text-gray text-xl ">Materiales de linea</h3>

						<div class="flex flex-col w-full items-start gap-y-4 lg:px-12 ">

							<div class="relative w-full text-sm h-64 overflow-y-scroll ">
								<table id="tabla-materiales-linea" >
									<thead>
										<tr>
											<th>Item</th>
											<th>SAP</th>
											<th>Materia Prima/Ingrediente</th>
											<th>Por porcion</th>
											<th>%</th>
											<th>Kg</th>
											<th>% SAP + Merma</th>
											<th>Costo</th>
											<th>Act</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><div class="w-full flex justify-center">1</div></td>
											<td><input name="sap[]" type="text" class="input_alt to_uppercase" required placeholder="Ingresa codigo"></td>
											<td><input name="articulo[]" type="text" class="input_alt" required placeholder="Nombre del Articulo"></td>
											<td><input name="porcion[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td><input name="porcentaje[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td><input name="peso[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
            
											<td><input name="calculo[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td><input name="costo[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td>
												<button class="btn-delete text-gray hover:text-red  mx-auto" type="button"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
												<path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
											</svg></button>
											</td>
										</tr>
									</tbody>
								</table>
							</div>

							<div class=" w-full flex flex-col space-y-8 lg:flex-row items-center lg:space-x-8 lg:space-y-0 justify-end  ">
								<h5 class="text-center uppercase">Costo Primo</h5>
								<input type="text" id="costo_primo" name="costo_primo" class="w-64" required>
							</div>

							<button type="button" data-table="tabla-materiales-linea" class="btn_add_row self-end rounded text-icon border-2 border-icon hover:bg-icon hover:text-white px-4 py-2 w-fit" type="button"><i class="fa fa-plus text-lg"></i></button>

						</div>

						<h3 class="pt-12 text-center text-gray text-xl ">Materiales Nuevos</h3>

						<div class="flex flex-col w-full items-start gap-y-4 lg:px-12 ">

							<div class="relative w-full text-sm h-64 overflow-y-scroll ">
								<table id="tabla-materiales-nuevo" >
									<thead>
										<tr>
											<th>Item</th>
											<th>SAP</th>
											<th>Materia Prima/Ingrediente</th>
											<th>Por porcion</th>
											<th>%</th>
											<th>Kg</th>
											<th>% SAP + Merma</th>
											<th>Costo</th>
											<th>Act</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><div class="w-full flex justify-center">1</div></td>
											<td><input name="sap[]" type="text" class="input_alt to_uppercase" required placeholder="Ingresa codigo"></td>
											<td><input name="articulo[]" type="text" class="input_alt" required placeholder="Nombre del Articulo"></td>
											<td><input name="porcion[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td><input name="porcentaje[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td><input name="peso[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
            
											<td><input name="calculo[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td><input name="costo[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
											<td>
												<button class="btn-delete text-gray hover:text-red  mx-auto" type="button"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
												<path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
											</svg></button>
											</td>
										</tr>
									</tbody>
								</table>
							</div>

							<div class=" w-full flex flex-col space-y-8 lg:flex-row items-center lg:space-x-8 lg:space-y-0 justify-end  ">
								<h5 class="text-center uppercase">Costo Primo</h5>
								<input type="text" id="costo_primo" name="costo_primo" class="w-64" required>
							</div>

							<button type="button" data-table="tabla-materiales-nuevo" class="btn_add_row self-end rounded text-icon border-2 border-icon hover:bg-icon hover:text-white px-4 py-2 w-fit" type="button"><i class="fa fa-plus text-lg"></i></button>

						</div>


						<div class="flex flex-col pt-10 gap-y-4 border-b border-gray">
							<p class="text-xl text-center text-gray">Paso 3 de 6</p>
						</div>
					</div>

					<!-- paso 4 -->
					<div class="flex flex-col gap-y-8">

						<h3 class="pt-12 text-center text-gray text-2xl font-semibold">¿Se requiere algún proceso de conservación?</h3>

						<div class="flex flex-col w-full items-center space-y-2 px-20 ">
							<div class="flex items-center justify-center gap-x-24 pt-4">
								<label class="flex flex-col justify-center items-center">
										<input type="radio" data-name="cambio_pieza" name="tog1" data-value="no" class="checkbox_conserv hidden">
										<span class="checkbox-label"><i class="fas fa-check "></i></span>
										<span>No</span>
								</label>

								<label class="flex flex-col justify-center items-center">
										<input type="radio" data-name="cambio_pieza" name="tog1" data-value="si" class="checkbox_conserv hidden" >	
										<span class="checkbox-label"><i class="fas fa-check "></i></span>
										<span>Si</span>
								</label>
							</div>
						</div>

						<div id="conserv_section" class="hidden  my-4 mx-auto flex flex-col w-1/2  gap-y-4 text-sm text-gray ">
							<div class=" flex flex-col items-center w-full items-start gap-y-4  ">
								<h5 class="text-center text-gray text-xl">Por favor selecciona el proceso de conservacion requerido:</h5>
								<select name="estado_ticket" class="text-base w-1/3" >
									<option value="" disabled selected>Seleccionar...</option>
									<option value="1">REGRIGERACION</option>
									<option value="2">OTRO </option>
								</select>

							</div>
						</div>

						<div class="flex flex-col pt-10 gap-y-4 border-b border-gray">
							<p class="text-xl text-center text-gray">Paso 4 de 6</p>
						</div>
					</div>

					<!-- paso 5 -->
					<div class="flex flex-col gap-y-8">

						<h3 class="pt-12 text-center text-gray text-2xl font-semibold">Observaciones</h3>

						<div class="flex flex-col w-full items-start space-y-2 py-12 px-20 ">
							<textarea name="descripcion" class="p-4 w-full border border-grayMid outline-none resize-none bg-grayLight text-gray drop-shadow " rows="4" required placeholder="Escribe aquí tus observaciones (opcional)"></textarea>
						</div>

						<div class="flex flex-col pt-10 gap-y-4 border-b border-gray">
							<p class="text-xl text-center text-gray">Paso 5 de 6</p>
						</div>
					</div>

					<!-- paso 6 -->
					<div class="flex flex-col gap-y-4 px-12 ">

						<h3 class="pt-12 text-center text-gray text-2xl font-semibold">Firmas del Documento</h3>

						<div class="my-10 bg-white flex items-start gap-x-12 w-full">
							
							<div class="flex flex-col w-full items-center gap-y-2 justify-center w-1/3 ">
								
								<img id="resp_firma" class="h-44 w-full border-2 border-grayMid bg-grayLight">  

								<p id="resp_nombre" class="font-bold">Eustorgio Moran</p>
								<h5 class="text-center uppercase">Realiza Mantenimiento</h5>

								<button data-field="firma_responsable" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">FIRMAR</button>

							</div>
							<div class="flex flex-col w-full items-center gap-y-2 justify-center w-1/3 ">
								
								<img id="solic_firma" class="h-44 w-full border-2 border-grayMid bg-grayLight">  

								<p id="solic_nombre" class="font-bold">Sergio Ibarra</p>
								<h5 class="text-center uppercase">Solicitante</h5>

							</div>
							<div class="flex flex-col w-full items-center gap-y-2 justify-center w-1/3 ">
								
								<img id="encar_firma" class="h-44 w-full border-2 border-grayMid bg-grayLight">  

								<p id="encar_nombre" class="font-bold">Francisco Amezcua</p>
								<h5 class="text-center uppercase">Encargado Mantenimiento</h5>

								<button data-field="firma_encargado" class="btn_firmar flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">FIRMAR</button>

							</div>
						</div>
						
						<div class=" py-10 w-full flex flex-col space-y-8 lg:flex-row items-center lg:space-x-12 lg:space-y-0 justify-end  ">

							<button id="btn_open_camera" data-modal="modal_files" class="btn_open_modal w-64 lg:w-fit flex  items-center justify-center space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 " type="button" >
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
									<path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
									<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
								</svg>

								<span>ADJUNTAR </span>
							</button>

							<button class=" w-64 lg:w-fit  flex justify-center items-center  space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 " type="submit">
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6  rotate-180">
									<path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
								</svg>
								<span>ENVIAR</span>
							</button>

						</div>



					</div>

				</div>
			</div>
			


    </div>



<?php echo view('_partials/_modal_msg_tablet'); ?>

<script>
  Service.setLoading();

	const toggleConserv = (e) => {
		let _val = e.target.getAttribute('data-value');
		let name = e.target.getAttribute('data-name');
		let conserv_section = document.querySelector('#conserv_section');

		// document.querySelector(`input[name="${name}"]`).value = _val;

		console.log(_val);

		if(name == "cambio_pieza") {
			if(_val == 'si') {
				conserv_section.classList.remove('hidden');
			} else {
				conserv_section.classList.add('hidden');

			}
		}

	};

	const allCheckboxConserv = document.querySelectorAll('.checkbox_conserv');
	allCheckboxConserv?.forEach(input => {
		input.addEventListener('change', toggleConserv);
	});



const allInputToUpper = document.querySelectorAll('.to_uppercase');
allInputToUpper?.forEach( input => {
  input.addEventListener('input', e => {
    input.value = e.target.value.toUpperCase();
  });
});

let overlay = document.querySelector('.backdrop-mobile-menu');
let mobile_checkbox = document.querySelector('#mobile_checkbox');
mobile_checkbox?.addEventListener('change', e => {
  if(e.target.checked) {
    document.body.classList.add('no-scroll');
    overlay.style.display = 'block';
  } else {
    document.body.classList.remove('no-scroll');
    overlay.style.display = 'none';
  }
});

window.addEventListener('click', e => {
  let mobile_checkbox = document.querySelector('#mobile_checkbox');
  if (e.target == overlay) {
    mobile_checkbox.checked = false;
    document.body.classList.remove('no-scroll');
    overlay.style.display = 'none';
  }
});


const initBtnDelete = () => {
	const delBtn = document.querySelectorAll('.btn-delete');
    delBtn?.forEach( btn => {
      btn.addEventListener('click', (e) => {
        e.currentTarget.parentNode.parentNode.remove();
      });
    });
}


const allBtnAddRow = document.querySelectorAll('.btn_add_row');
allBtnAddRow?.forEach( btn => {
	btn.addEventListener('click', (e) => {
		let table = e.currentTarget.getAttribute('data-table');
		let tbody = document.querySelector(`#${table} tbody`);

		let rowCount = tbody.querySelectorAll('tr').length + 1;

    let nuevaFila = 
    `
    <tr>
				<td><div class="w-full flex justify-center">${rowCount}</div></td>
				<td><input name="sap[]" type="text" class="input_alt to_uppercase" required placeholder="Ingresa codigo"></td>
				<td><input name="articulo[]" type="text" class="input_alt" required placeholder="Nombre del Articulo"></td>
				<td><input name="porcion[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
				<td><input name="porcentaje[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
				<td><input name="peso[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>

				<td><input name="calculo[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
				<td><input name="costo[]" type="text" class="input_alt to_uppercase" required placeholder="Cantidad"></td>
				<td>
        <button class="btn-delete text-gray hover:text-red mx-auto" type="button">${getIcon('delete')}</button>
      </td>
    </tr>
    `;

    tbody.insertAdjacentHTML('beforeend', nuevaFila);
		initBtnDelete();

  });
});


</script>
</body>
</html>