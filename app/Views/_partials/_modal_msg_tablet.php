<?php if (session()->getFlashdata('msg')) : ?>
  <?php if (session()->getFlashdata('msg_error')) : ?>
  <div id="msg_alert" class=" modal_active fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
    <div class=" flex flex-col items-center justify-between space-y-8 bg-white border-2 border-icon p-8 m-4 w-full lg:max-w-2xl h-[85%] ">
      
      <div class=" relative flex w-full justify-center text-center  ">
        <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
      </div>

      <h3 class="text-super text-4xl text-center ">Falló el envío, por favor <br> intenta de nuevo</h3>

      <div class="flex justify-center space-x-12 text-sm ">
        <button class="btn_close_modal flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
          ACEPTAR
        </button>
      </div>

    </div>
  </div>

  <?php else: ?>
  <div id="msg_alert" class=" modal_active fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
		<div class=" flex flex-col items-center justify-between space-y-8 bg-white border-2 border-icon p-8 m-4 w-full lg:max-w-2xl h-[85%] ">
      
      <div class=" relative flex w-full justify-center text-center  ">
        <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
      </div>

      <h3 class="text-title text-4xl ">¡Enviado con éxito!</h3>

      <div class="flex justify-center space-x-12 text-sm ">
        <button class="btn_close_modal flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
          ACEPTAR
        </button>
      </div>

    </div>
  </div>

  <?php endif; ?>
<?php endif; ?>


  <div id="edit_success" class=" hidden fixed inset-0 z-50 bg-dark bg-opacity-50 flex items-center justify-center font-titil ">
    <div class=" flex flex-col items-center justify-between space-y-8 bg-white border-2 border-icon p-10 w-full md:w-[700px] h-96 ">
      
      <div class=" relative flex w-full justify-center text-center  ">
        <div class="btn_close_modal absolute -top-4 right-0 text-4xl text-gray cursor-pointer">&times;</div>
      </div>

      <h3 class="text-title text-4xl ">¡Enviado con éxito!</h3>

      <div class="flex justify-center space-x-12 text-sm ">
        <button class="btn_close_modal flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
          ACEPTAR
        </button>
      </div>

    </div>
  </div>
