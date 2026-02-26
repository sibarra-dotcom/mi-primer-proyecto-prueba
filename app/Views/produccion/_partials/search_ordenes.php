		<form id="form_search" class="search-column flex flex-col gap-y-4 w-full items-center justify-center p-3 " method="post" >
			<?= csrf_field() ?>

			<div class="relative flex flex-col  w-full">
				<h5 class="text-center text-xs uppercase">Numero de Orden</h5>
				<input type="text" id="num_orden" name="num_orden" placeholder="Numero orden" >
			</div>

			<div class="relative flex flex-col  w-full">
				<h5 class="text-center text-xs uppercase">Articulo</h5>
				<input type="text" id="desc_articulo" name="desc_articulo" placeholder="Articulo" >
			</div>

			<div class="relative flex flex-col  w-full">
				<h5 class="text-center text-xs uppercase">Codigo Articulo</h5>
				<input type="text" id="num_articulo" name="num_articulo" placeholder="Codigo Articulo" >
			</div>

			<div class="relative flex flex-col  w-full">
				<h5 class="text-center text-xs uppercase">Nombre Deudor</h5>
				<input type="text" id="nombre_deudor" name="nombre_deudor" placeholder="Nombre Deudor" >
			</div>
			
			<div class="relative flex flex-col  w-full">
				<h5 class="text-center text-xs uppercase">Lote</h5>
				<input type="text" id="lote" name="lote" placeholder="Lote" >
			</div>

			<div class="flex flex-col  w-full">
				<h5 class="text-center text-xs uppercase">Fecha Inicio</h5>
				<input id="fecha" type="date" name="fecha_primer_reporte" >
			</div>


			<div class="relative flex flex-col  w-full">
				<h5 class="text-center text-xs uppercase">STATUS</h5>
				<select name="status_pedido" >
					<option value="" selected>Seleccionar...</option>
					<option value="PENDIENTE">PENDIENTE</option>
					<option value="NO APROBADO">NO APROBADO</option>
					<option value="APROBADO">APROBADO</option>
				</select>
			</div>

			<div class="flex flex-col   w-full">
				<h5 class="text-center text-xs uppercase">Ultima Fecha Registro</h5>
				<input id="fecha" type="date" name="fecha_ultimo_reporte" >
			</div>

			<div class="flex w-full gap-4">
				<button id="search_reset" class="btn btn-sm btn--cancel" type="button">
					<i class="fas fa-refresh"></i>
				</button>
				<button class="btn btn-md btn--search" type="submit">
					<i class="fas fa-search"></i>
					<span>BUSCAR</span>
				</button>
			</div>

		</form>