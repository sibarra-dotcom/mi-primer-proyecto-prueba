
<?php echo view('admin/_partials/header'); ?>
	<?php echo view('admin/_partials/navbar'); ?>

  <div class="flex w-full p-8 space-x-8">

    <?php echo view('admin/_partials/sidebar'); ?>


    <div class="w-full flex flex-col md:flex-row mx-2 p-3 space-y-4 md:mx-4  md:space-y-0 bg-white rounded border border-grayMid ">

      <div class="flex flex-col items-center space-y-8 p-10 mx-auto">
        <a href="<?= base_url('admin/dashboard') ?>"><img src="<?= base_url('img/logo.jpeg') ?>" alt="Clinica" class="w-24 h-24"></a>
        <h1 class="text-center text-4xl text-title"><?= esc($title) ?></h1>
      </div>


    </div>


  </div>

</body>
</html>

