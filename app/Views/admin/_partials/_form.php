

    
    <h2>Login</h2>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="error">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>


    <?php echo form_open(base_url('auth/signin'), array("id" => "form_login", "class" => "form_login", "role" => "form")); ?>

        <?php echo form_label('Email:', 'email'); ?>
        <?php echo form_input(['name' => 'email', 'id' => 'email', 'type' => 'email', 'required' => true]); ?>


        <?php echo form_label('Contraseña:', 'password'); ?>
        <?php echo form_input(['name' => 'password', 'id' => 'password', 'type' => 'password', 'required' => true]); ?>

        <?php echo form_submit('btn_login', 'Iniciar Sesion', ['class' => 'btn btn-primary', 'id' => 'submit-button']); ?>

    <?php echo form_close(); ?>

</body>
</html>
