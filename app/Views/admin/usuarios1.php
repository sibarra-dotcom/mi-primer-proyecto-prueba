<?php echo view('admin/_partials/header'); ?>
	<?php echo view('admin/_partials/navbar'); ?>

  <div class="flex w-full p-8 space-x-8">

    <?php echo view('admin/_partials/sidebar'); ?>


    <div class="w-full flex flex-col p-6 space-y-4 mx-2 md:mx-4  md:space-y-0 bg-white rounded border border-grayMid text-gray ">

    	<div class="w-full flex justify-between items-center">          
        <h2 class="text-2xl border-b-2 border-title "><?= esc($title) ?></h2>
        <a href="<?= base_url('admin/user_create') ?>" class=" px-6 py-2 bg-title-l hover:bg-title text-white text-xl rounded "> <i class="fas fa-plus mr-2"></i> Nuevo Usuario</a>    
    	</div>

    	<div class="py-10">
    		   
	    	<table id="tabla-users-admin">
	    		<thead>
	    			<tr>
	    				<th>Id</th>
	    				<th>Nombres</th>
	    				<th>Apellidos</th>
	    				<th>Rol</th>
	    				<th>Email</th>
	    				<th>Acciones</th>
	    			</tr>
	    		</thead>
	    		<tbody>
	    			<?php foreach ($users as $user): ?>
	    				<tr>
	    					<td><div class="w-16"><?= $user['id'] ?></div> </td>
	    					<td><div class="w-64"><?= $user['name'] ?></div> </td>
	    					<td><div class="w-64"><?= $user['last_name'] ?></div> </td>
	    					<td><div class="w-44"><?= $user['rol'] ?></div></td>
	    					<td> <div class="w-72"><?= $user['email'] ?></div> </td>
	    					<td>
	    						<div class="w-flex space-x-4 px-4 ">

	    							<a href="<?= base_url('admin/user_edit/' . $user['id']) ?>" ><i class="fas fa-edit text-blue "></i></a>
										<a href="<?= base_url('admin/user_delete/' . $user['id']) ?>"  onclick="return confirm('Are you sure you want to delete this user?');"><i class="fas fa-trash text-red"></i></a>

	    						</div>
	    						
	    					</td>
	    				</tr>
	    			<?php endforeach; ?>
	    		</tbody>
	    	</table>

    	</div>
    </div>

  </div>

</body>
</html>
