<?php echo view('admin/_partials/header'); ?>
	<?php echo view('admin/_partials/navbar'); ?>

  <div class="flex w-full p-8 space-x-8">

    <?php echo view('admin/_partials/sidebar'); ?>


    <div class="w-full flex flex-col p-6 space-y-4 mx-2 md:mx-4  md:space-y-0 bg-white rounded border border-grayMid text-gray ">

    	<div class="w-full flex justify-between items-center">          
        <h2 class="text-2xl border-b-2 border-title "><?= esc($title) ?></h2>  
				<a href="<?= base_url('admin/usuarios') ?>" class="flex gap-x-4 items-center text-link">
					<i class="fas fa-arrow-left"></i>
					<span>Volver a Lista</span>
				</a>
    	</div>

    	<div class="py-10">
    		  
    		<?php if (session()->getFlashdata('errors')): ?>
    		<div class="alert alert-danger w-full text-warning flex items-center justify-center p-2">
    			<ul>
		    		<?php foreach (session()->getFlashdata('errors') as $error): ?>
		    			<li><?= esc($error) ?></li>
		    		<?php endforeach; ?>
		    	</ul>
		    </div>
		    <?php endif; ?>

        <form id="form_create" method="post" class="w-full md:w-1/2 mx-auto flex flex-col space-y-4 border rounded p-4 border-grayMid">
				  <?= csrf_field() ?>

          <div class="flex space-x-4 items-center">
            <label for="name" class="text-lg w-1/2 ">Nombres</label>
            <div class="flex flex-col w-full">
              <input type="text" class=" px-2 py-1 rounded border border-neutralDark outline-none" id="name" name="name" placeholder="Nombres"  required>
            </div>
          </div>

          <div class="flex space-x-4 items-center">
            <label for="last_name" class="text-lg w-1/2 ">Apellidos</label>
            <div class="flex flex-col w-full">
              <input type="text" class=" px-2 py-1 rounded border border-neutralDark outline-none" id="last_name" name="last_name" placeholder="Apellido"  required>
            </div>
          </div>

          <div class="flex space-x-4 items-center">
            <label for="email" class="text-lg w-1/2 ">Email</label>
            <div class="flex flex-col w-full">
              <input type="email" class=" px-2 py-1 rounded border border-neutralDark outline-none" id="email" name="email" placeholder="Email"  required>
            </div>
          </div>

          <div class="flex space-x-4 items-center">
            <label for="password" class="text-lg w-1/2 ">Contraseña</label>
            <div class="relative flex flex-col w-full ">
              <input type="password" data-type="password"  class="w-full px-2 py-1 rounded border border-neutralDark outline-none" id="password" name="password" placeholder="Contraseña"   required>
            </div>
          </div>

          <div class="flex space-x-4 items-center">
            <label for="pin" class="text-lg w-1/2 ">PIN</label>
            <div class="flex flex-col w-full">
              <input type="text" data-excluded="1" class=" px-2 py-1 rounded border border-neutralDark outline-none" id="pin" name="pin" placeholder="PIN"  required>
            </div>
          </div>

          <div class="flex space-x-4 items-center">
            <label for="rol_id" class="text-lg w-1/2 ">Rol</label>
            <div class="flex flex-col w-full">
              <select name="rol_id" id="rol_id" class=" px-2 py-1 rounded border border-neutralDark outline-none" required>
								<?php foreach ($roles as $rol): ?>
								<option value="<?= esc($rol['id'])?>"><?= esc($rol['rol'])?></option>
								<?php endforeach; ?>
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

Service.setLoading();

let allInputPwd = document.querySelectorAll('input[data-type]');
Validate.setBtnDisplay(allInputPwd, 1);

const form_create = document.querySelector('#form_create');
const inputs_create = form_create.querySelectorAll('input:not([type="hidden"])');
// const inputs_create = form_create.querySelectorAll('input:not([type="hidden"]):not([type="password"])');

Validate.setKeyUp(inputs_create);

form_create.addEventListener('submit', e => {
  e.preventDefault();
	Service.stopSubmit(e.target, true);

  let btn = e.target.querySelector('button[type="submit"]');

  if (Validate.Form(inputs_create)) {
		Service.show('.loading');
    btn.innerHTML = Service.loader_sm();
		Service.stopSubmit(e.target, false);

    e.target.submit();
  } else { 
    console.log('form login invalid');
  }
});

</script>
</body>
</html>
