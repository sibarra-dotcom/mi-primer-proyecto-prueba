<div class="formato__section ">
	<div class="formato__col-1 ">
		<img src="<?= base_url('img/gibanibb_logo.png') ?>">
	</div>

	<div class="formato__col-2 ">
		<div>
			<span>TÍTULO: </span>
			<p><?= esc($formato['titulo']) ?></p>
		</div>	
		<div>
			<span>CLAVE: </span>
			<p><?= esc($formato['clave']) ?></p>
		</div>	
		<div>
			<span>VERSIÓN: </span>
			<p><?= esc($formato['version']) ?></p>
		</div>						
	</div>

	<div class="formato__col-3 ">
		<div>
			<span>PÁGINA: </span>
			<p class="num-page"><?= esc($formato['paginas']) ?></p>
		</div>						
		<div>
			<span>ÚLTIMA REVISIÓN: </span>
			<p class="moment-date"><?= esc($formato['revision']) ?></p>
		</div>						
		<div>
			<span>FECHA DE VIGENCIA: </span>
			<p class="moment-date"><?= esc($formato['vigencia']) ?></p>
		</div>						
	</div>
</div>