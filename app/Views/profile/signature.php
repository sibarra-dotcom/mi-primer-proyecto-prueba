<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/signature_pad@5.0.7/dist/signature_pad.umd.min.js"></script>

  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>
  <script src="<?= load_asset('js/modal.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body>
	<div id="overlay"></div>  
  <div id="loadingOverlay"><div id="loadingSpinner"></div></div>  

<div class=" relative flex flex-col items-center justify-center font-titil ">
	<img src="<?= base_url('img/perfil.svg') ?>" class="flex absolute bottom-2 right-8 -z-10 opacity-90 w-5/6 h-3/4 ">  


  <?php echo view('dashboard/_partials/navbar'); ?>

  <a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer "> Cerrar Sesion </a>

  <div class=" mb-2 text-title w-full md:py-4 md:px-16 p-2 flex items-center ">
    <h2 class="text-center font-bold w-full text-3xl "><?= esc($title) ?></h2>
    <a href="<?= base_url('profile') ?>" class="self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
  </div>


	<div class="mb-10  flex flex-col items-center justify-center gap-y-2 ">
		<h2 class="text-3xl text-title font-bold "><?= session()->get('user')['name'] . " " . session()->get('user')['last_name']?></h2>
		<h2 class="text-xl text-gray font-bold "><?= session()->get('user')['email']?></h2>
	</div>

	<div class="mb-4 w-2/3 flex flex-col items-center justify-center gap-y-4 ">
		<h2 class="text-xl text-gray font-bold ">Firma Actual</h2>
    
		<img src="<?= !empty($signature) ? base_url("/files/download?path=" . urlencode($signature)) : base_url('img/no_img_alt.png') ?>" class="object-contain h-44 w-1/2 border-2 border-title">  

	</div>

	<?php if(empty($signature)) : ?>

	<form id="form_signature" method="post" enctype='multipart/form-data' class="mb-4 w-2/3 flex flex-col items-center justify-center gap-y-4 ">
		<h2 class="text-xl text-gray font-bold ">Crea tu firma</h2>
		<?= csrf_field() ?>
    
    <canvas id="signature-pad" class="h-72 w-full border-2 border-title"></canvas>
		<!-- <input type="hidden" name="signature" id="signatureInput">   -->
		<input type="file" id="signatureInput" name="signature" class="hidden">

		<div class="flex w-full justify-between text-sm ">
			<button type="button" id="clear-btn" class=" flex space-x-4 shadow-bottom-right text-error border-2 border-error hover:bg-error hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button" >
				LIMPIAR
			</button>
			<button  type="submit" class=" flex space-x-4 shadow-bottom-right text-gray border-2 border-icon hover:bg-icon hover:border-grayLight hover:text-white py-2 px-8 w-fit " type="button">
				GUARDAR
			</button>
		</div>

	</form>
	<?php endif; ?>


</div>

<?php echo view('_partials/_modal_msg_tablet'); ?>


<script>

let overlay = document.querySelector('.backdrop-mobile-menu');
	let mobile_checkbox = document.querySelector('#mobile_checkbox');
	mobile_checkbox?.addEventListener('change', e => {
		if(e.target.checked) {
			document.body.classList.add('no-scroll');
			overlay.style.display = 'block';
		} else {
			document.body.classList.remove('no-scroll');
			overlay.style.display = 'none';
		}
	});

	window.addEventListener('click', e => {
		let mobile_checkbox = document.querySelector('#mobile_checkbox');
		if (e.target == overlay) {
			mobile_checkbox.checked = false;
			// form_ticket.reset();
			document.body.classList.remove('no-scroll');
			overlay.style.display = 'none';
		}
	});


	const initRowBtn = () => {
    const allBtnOpen = document.querySelectorAll('.btn_open_modal');
    allBtnOpen?.forEach( (btn, index) => {

      btn.addEventListener('click', e => {
        // console.log(e.currentTarget)

        let modal_id = e.currentTarget.getAttribute('data-modal');
        let modal = document.querySelector(`#${modal_id}`);
        // console.log(modal_id)

        modal.classList.add('modal_active');
        modal.classList.remove('hidden');

        if (modal_id == 'modal_ticket') {
          let id = e.currentTarget.getAttribute('data-id');
          initModalTicket(id);
        }

      });
    });

    const allBtnClose = document.querySelectorAll('.btn_close_modal')
    allBtnClose?.forEach( btn => {
      btn.addEventListener('click', (e) => {
        let modal_active = document.querySelector('.modal_active');
        if (modal_active) {

          modal_active.classList.add('hidden');
          modal_active.classList.remove('modal_active');
        }
      });
    });

  }

  initRowBtn();



const canvas = document.getElementById("signature-pad");
const signaturePad = new SignaturePad(canvas, {
    minWidth: 0.5,  
    maxWidth: 2.5,  
    throttle: 10,  
    penColor: "black"
});

document.getElementById("clear-btn").addEventListener("click", () => {
	signaturePad.clear();
});

function resizeCanvas() {
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
}

window.addEventListener("resize", resizeCanvas);
resizeCanvas();



function dataURLtoFile(dataURL, filename) {
    let arr = dataURL.split(","), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);

    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }

    return new File([u8arr], filename, { type: mime });
}


const form_signature = document.querySelector('#form_signature');
form_signature?.addEventListener('submit', e => {
	e.preventDefault();
	let btn = e.target.querySelector('button[type="submit"]');

	document.querySelector('#loadingOverlay').style.display = 'block';
	btn.disabled = true;

	if (!signaturePad.isEmpty()) {

		const signatureData = signaturePad.toDataURL("image/png"); 
    const file = dataURLtoFile(signatureData, "signature.png"); 
    const fileInput = document.getElementById("signatureInput");
    let dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    fileInput.files = dataTransfer.files;

    console.log("File assigned to input:", fileInput.files[0]);
		e.target.submit();

		// document.getElementById("signatureInput").value = signaturePad.toDataURL("image/png");

		// e.target.submit();

	} else {
		btn.disabled = false;
	}
});



</script>
</body>
</html>

