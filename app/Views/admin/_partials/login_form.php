
<?php
  $data['title'] = "Login Page";
  echo view('home/_partials/header', $data);
?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="error">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>


<div class="showcase font-noto">

  <div class="h-screen hidden md:flex w-full p-4 md:w-1/2 mx-auto bg-gray bg-opacity-10">

  </div>


  <section class="h-screen flex items-center space-x-8 w-full md:w-1/2 px-4 py-14  bg-gray bg-opacity-10 ">
    
    <form action="<?php echo base_url('auth/signin') ?>" method="POST" class=" form__container--sm  mx-auto " autocomplete="off" >
      <?= csrf_field() ?>

      <img src="/img/logo.png" alt="" class=" mx-auto h-32 ">

      <div class="form__input  form__input--lg">
        <i class="fas fa-user text-primary "></i>
        <input type="text" name="usuario" placeholder="Usuario " class="outline-none w-full " required>
      </div>

      <div class="form__input  form__input--lg ">
        <i class="fas fa-lock text-primary"></i>
        <input type="password" name="password" placeholder="Contraseña" class="outline-none w-full " required >
        <div id="btn_show"><i class="fas fa-eye text-ctaDark"></i></div>
      </div>

      <button class="form__btn " > Iniciar Sesión </button>

        
    </form>
  </section>


</div>




<!-- <script src="js/main.js"></script> -->
<!-- <script src="js/main.min.js"></script> -->
</body>
</html>
