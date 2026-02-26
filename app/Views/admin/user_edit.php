
<?php echo view('admin/_partials/header'); ?>
<?php echo view('admin/_partials/navbar'); ?>

<div class="flex w-full p-8 space-x-8">

  <?php echo view('admin/_partials/sidebar'); ?>


  <div class="w-full flex flex-col p-6 space-y-4 mx-2 md:mx-4  md:space-y-0 bg-white rounded border border-grayMid text-gray  ">

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

  <form id="form_update" action="<?= base_url('admin/user_update/' . $user['id']) ?>" method="post" class="w-full md:w-1/2 mx-auto flex flex-col space-y-4 border rounded p-4 border-grayMid">
    <?= csrf_field() ?>

    <div class="flex space-x-4 items-center">
      <label for="name" class="text-lg w-1/2 ">Nombres</label>
      <div class="flex flex-col w-full">
        <input type="text" class=" px-2 py-1 rounded border border-neutralDark outline-none" id="name" name="name"  value="<?= esc($user['name']) ?>" required>
      </div>
    </div>

    <div class="flex space-x-4 items-center">
      <label for="last_name" class="text-lg w-1/2 ">Apellidos</label>
      <div class="flex flex-col w-full">
        <input type="text" class=" px-2 py-1 rounded border border-neutralDark outline-none" id="last_name" name="last_name"  value="<?= esc($user['last_name']) ?>"  required>
      </div>
    </div>

    <div class="flex space-x-4 items-center">
      <label for="email" class="text-lg w-1/2 ">Email</label>
      <div class="flex flex-col w-full">
        <input type="email" class=" px-2 py-1 rounded border border-neutralDark outline-none" id="email" name="email"  value="<?= esc($user['email']) ?>"  required>
      </div>
    </div>

		<div class="flex space-x-4 items-center">
			<label for="pin" class="text-lg w-1/2 ">PIN</label>
			<div class="flex flex-col w-full">
				<input type="text" data-excluded="1" class=" px-2 py-1 rounded border border-neutralDark outline-none" id="pin" name="pin" placeholder="PIN" value="<?= esc($user['pin']) ?>" required>
			</div>
		</div>

    <div class="flex space-x-4 items-center">
      <label for="rol_id" class="text-lg w-1/2 ">Rol</label>
      <div class="flex flex-col w-full">
        <select name="rol_id" id="rol_id" class=" px-2 py-1 rounded border border-neutralDark outline-none" required>
          <option value="<?= esc($user['rol_id']) ?>" selected><?= esc($user['rol']) ?></option>
					<?php foreach ($roles as $rol): ?>
						<option value="<?= esc($rol['id'])?>"><?= esc($rol['rol'])?></option>
					<?php endforeach; ?>
        </select>
      </div>
    </div>


    <div class="form__submit pt-6">
      <button type="submit">
        <i class="fas fa-paper-plane mr-2"></i>
        Guardar Cambios
      </button>
    </div>

  </form>

</div>
</div>

</div>
<script>
Service.setLoading();


const form_update = document.querySelector('#form_update');
const inputs_update = form_update.querySelectorAll('input:not([type="hidden"])');

Validate.setKeyUp(inputs_update);

form_update?.addEventListener('submit', e => {
  e.preventDefault();
	Service.stopSubmit(e.target, true);

  let btn = e.target.querySelector('button[type="submit"]');

  if (Validate.Form(inputs_update)) {
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

