<?php echo view('admin/_partials/header'); ?>

  <div class="flex w-full p-8 space-x-8">

    <?php echo view('admin/_partials/sidebar'); ?>


    <div class="w-full flex flex-col p-6 space-y-4 mx-2 md:mx-4  md:space-y-0 bg-white rounded border border-neutralDark ">

    	<div class="w-full flex justify-between items-center">          
        <h2 class="text-2xl border-b-2 border-ctaLight "><?= esc($title) ?></h2>  
    	</div>

    	<div class="py-10">
    		  
    		<?php if (session()->getFlashdata('errors')): ?>
    		<div class="alert alert-danger">
    			<ul>
		    		<?php foreach (session()->getFlashdata('errors') as $error): ?>
		    			<li><?= esc($error) ?></li>
		    		<?php endforeach; ?>
		    	</ul>
		    </div>
		    <?php endif; ?>

        <form id="form_create" method="post" class="w-full md:w-1/2 mx-auto flex flex-col space-y-4 border rounded p-4 border-neutralDark">
				  <?= csrf_field() ?>

          <div class="flex space-x-4 items-center">
            <label for="name" class="text-lg w-1/2 ">Nombres</label>
            <div class="flex flex-col w-full">
              <input type="text" class=" px-2 py-1 rounded border border-neutralDark outline-none" id="name" name="name" placeholder="Nombres"  required>
              <span class="hidden tooltip">Mín. 3 letras</span>
            </div>
          </div>

          <div class="flex space-x-4 items-center">
            <label for="last_name" class="text-lg w-1/2 ">Apellidos</label>
            <div class="flex flex-col w-full">
              <input type="text" class=" px-2 py-1 rounded border border-neutralDark outline-none" id="last_name" name="last_name" placeholder="Apellido"  required>
              <span class="hidden tooltip">Mín. 3 letras</span>
            </div>
          </div>

          <div class="flex space-x-4 items-center">
            <label for="email" class="text-lg w-1/2 ">Email</label>
            <div class="flex flex-col w-full">
              <input type="email" class=" px-2 py-1 rounded border border-neutralDark outline-none" id="email" name="email" placeholder="Email"  required>
              <span class="hidden tooltip">Email inválido.</span>
            </div>
          </div>

          <div class="flex space-x-4 items-center">
            <label for="password" class="text-lg w-1/2 ">Contraseña</label>
            <div class="relative flex flex-col w-full ">
              <input type="password" class="w-full px-2 py-1 rounded border border-neutralDark outline-none" id="password" name="password" placeholder="Contraseña"   required>
              <span class="hidden tooltip">Mín. 6 letras, 1 mayúscula y 1 número</span>
              <div class="btn_eye absolute top-1 right-4 text-ctaDark text-xl cursor-pointer"><i class="fas fa-eye"></i></div>
            </div>
          </div>

          <div class="flex space-x-4 items-center">
            <label for="rol_id" class="text-lg w-1/2 ">Rol</label>
            <div class="flex flex-col w-full">
              <select name="rol_id" id="rol_id" class=" px-2 py-1 rounded border border-neutralDark outline-none" required>
              	<option value="1">Admin</option>
              	<option value="3">Usuario</option>
              </select>
            </div>
          </div>



          <div class="form__submit pt-6">
            <button type="submit">
              <i class="fas fa-paper-plane mr-2"></i>
              Registrar
            </button>
          </div>

        </form>

    	</div>
    </div>

  </div>

<script>


let password = document.querySelector('input[name="password"]')

let btn_show = document.querySelector('.btn_eye')
btn_show?.addEventListener('click', e => {

  let icon = e.currentTarget.querySelector('i');

  if (icon.classList.contains('fa-eye')) {
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }

  if (password.type === "password") {
    password.type = "text";
  } else {
    password.type = "password";
  }

})


const form_create = document.querySelector('#form_create');
// const inputs_create = form_create.querySelectorAll('input:not([type="hidden"])');
const inputs_create = form_create.querySelectorAll('input:not([type="hidden"]):not([type="password"])');

Validate.setKeyUp(inputs_create);

form_create.addEventListener('submit', e => {
  e.preventDefault();
  let btn = e.target.querySelector('button[type="submit"]');


  if (Validate.Form(inputs_create)) {

    btn.disabled = true;
    btn.innerHTML = '<div class="loader"></div>';

    // return;


    e.target.submit();
    // return;


  } else { 
    console.log('form login invalid');
  }
});


</script>
</body>
</html>
