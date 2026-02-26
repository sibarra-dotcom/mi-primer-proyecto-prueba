<div class="formato__section ">
	<div class="formato__col-1 ">
		<img src="<?= base_url('img/gibanibb_logo.png') ?>">
	</div>

	<div class="formato__col-2">
		<div class="cell border-l border-b ">
			<span>TÍTULO: </span>
			<p><?= esc($formato['titulo']) ?></p>
		</div>	
		<div class="cell border-l border-b ">
			<span>CLAVE: </span>
			<p><?= esc($formato['clave']) ?></p>
		</div>	
		<div class="cell border-l ">
			<span>VERSIÓN: </span>
			<p><?= esc($formato['version']) ?></p>
		</div>						
	</div>

	<div class="formato__col-3">
		<div class="cell--alt border-l border-b">
			<span>PÁGINA: </span>
			<p class="num-page"><?= esc($formato['paginas']) ?></p>
		</div>						
		<div class="cell--alt cell--mobile border-l border-b  ">
			<span>ÚLTIMA REVISIÓN: </span>
			<p class="moment-date"><?= esc($formato['revision']) ?></p>
		</div>						
		<div class="cell--alt cell--mobile border-l  ">
			<span>FECHA DE VIGENCIA: </span>
			<p class="moment-date"><?= esc($formato['vigencia']) ?></p>
		</div>						
	</div>
</div>