<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/es.js"></script>
  <script src="<?= load_asset('js/axios.js') ?>"></script>
  <script src="<?= load_asset('js/Service.js') ?>"></script>
  <script src="<?= load_asset('js/helper.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">

  <div class="relative flex flex-col gap-y-2 items-center justify-center font-titil ">

  	<?php echo view('dashboard/_partials/navbar'); ?>

		<div class="relative flex flex-col gap-y-2 w-full">
			<div class="relative text-title w-full py-2 px-4 lg:px-10 flex items-center justify-between ">

				<div class="hidden lg:flex gap-4 items-center absolute left-6 lg:left-10 top-1/2 -translate-y-1/2">
					<a href="<?= base_url('sorteo') ?>" class="hover:scale-110 transition-transform duration-100 ">
						<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
					</a>
				</div>

				<div class="w-full flex items-center justify-center">
					<div class="flex  gap-4 items-baseline font-semibold ">
						<p class="text-title text-2xl lg:text-3xl" >
							<?= session()->get('user_sorteo')['name'] . ' ' . session()->get('user_sorteo')['last_name'] ?>
						</p>
						<h2 class="text-gray text-2xl">- <?= esc($title_group) ?></h2>
					</div>
				</div>

			</div>

			<div class="relative w-full flex justify-center">

				<button id="spin" class="absolute top-0 right-12 lg:right-1/4 text-2xl lg:text-3xl font-bold rounded text-icon border-2 border-icon hover:bg-icon hover:text-white py-1 px-3 w-fit uppercase disabled:bg-icon disabled:text-white" type="button">
					<span>¡ Girar Aquí !</span>
				</button>

				<div id="roulette-wheel-container">
					<canvas id="canvas" width="700" height="640"></canvas>
				</div>

				<div class="absolute left-12 lg:left-1/4 bottom-6">
					<img src="<?= base_url('img/gibanibb_logo.png') ?>" class="w-48" alt="">
				</div>

			</div>

			<div id="premio" class=" hidden absolute top-0 left-0 h-full w-full bg-white flex flex-col gap-2">

				<!-- title -->
				<div class="relative text-title w-full py-2 px-4 lg:px-10 flex items-center justify-between ">

					<div class="hidden lg:flex gap-4 items-center absolute left-6 lg:left-10 top-1/2 -translate-y-1/2">
						<a href="<?= base_url('sorteo') ?>" class="hover:scale-110 transition-transform duration-100 ">
							<i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i>
						</a>
					</div>

					<div class="w-full flex items-center justify-center">
						<div class="flex  gap-4 items-baseline font-semibold ">
							<p class="text-title font-bold text-3xl lg:text-4xl" >
								¡ Felicidades !  - 
							</p>
							<p class="text-title text-2xl lg:text-3xl" >
								<?= session()->get('user_sorteo')['name'] . ' ' . session()->get('user_sorteo')['last_name'] ?>
							</p>
						</div>
					</div>

					<div class=" flex gap-4 items-center absolute right-6 lg:right-10 top-1/2 -translate-y-1/2">
						<a href="<?= base_url('sorteo') ?>" class=" text-3xl font-bold rounded text-icon border-2 border-icon hover:bg-icon hover:text-white py-1 px-3 w-fit uppercase" type="button">
							<span> ENTREGADO</span>
						</a>
					</div>

				</div>

				<div id="product-grid" class="w-full mx-auto md:w-11/12 grid grid-cols-1 md:grid-cols-5 gap-x-10 gap-y-8 px-4 overflow-y-auto min-h-[640px] ">
				</div>

			</div>
		</div>




  </div>


<script>

Service.setLoading();
Service.show('.loading'); 
	
const sections = <?= $productos ?>

sections.push({ nombre: "Gira otra vez", color: "#FF6347", imagen: "", codigo: "again" });

const duplicateSections = (sections) => {
	// return [...sections, ...sections];
	return sections;
}

const duplicatedSections = duplicateSections(sections);

let font_size = (duplicatedSections.length >= 15) ? 'bold 15px Helvetica, Arial' : 'bold 18px Helvetica, Arial' ;

const loadImages = (imageUrls, callback) => {
	let imagesLoaded = 0;
	const totalImages = imageUrls.length;

	const checkImagesLoaded = () => {
		imagesLoaded++;
		if (imagesLoaded === totalImages) {
			callback();
		}
	};

	imageUrls.forEach(url => {
		const img = new Image();
		img.src = url;
		img.onload = checkImagesLoaded;
	});
};

const imageUrls = [
	`${root}/img/arrow.png`,
	`${root}/img/center_image.png`
];

const arrowImage = new Image();
arrowImage.src = imageUrls[0];

const centerImage = new Image();
centerImage.src = imageUrls[1]; 

loadImages(imageUrls, () => {
	drawRouletteWheel();
});



// === Config ===
const canvas = document.getElementById("canvas");
// const ctx = canvas.getContext("2d");
ctx = canvas.getContext("2d");

const WHEEL_SIZE = 300;  // radius, make bigger if needed
const outsideRadius = WHEEL_SIZE;
const insideRadius = WHEEL_SIZE * 0.5;
const textRadius = WHEEL_SIZE * 0.8;

const centerImageSize = 320;
const arrowSize = 65;

let startAngle = 0;
let arc = Math.PI / (duplicatedSections.length / 2);
let spinTimeout, spinAngleStart, spinTime, spinTimeTotal;


const spinAndWinSound = new Audio(`${root}/public/assets/ruleta.mp3`); 
spinAndWinSound.preload = "auto"; // precargar para evitar delay
spinAndWinSound.volume = 1.0; // volumen máximo, ajústalo si quieres


// === Helpers ===
const easeOut = (t, b, c, d) => {
  const ts = (t /= d) * t;
  const tc = ts * t;
  return b + c * (tc + -3 * ts + 3 * t);
};

const playSound = (audio) => {
  audio.currentTime = 0; // reset to start
  audio.play();
};

const stopSound = (audio) => {
  audio.pause();
  audio.currentTime = 0;
};

// === Drawing ===
const drawRouletteWheel = () => {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.strokeStyle = "#28a745";
  ctx.lineWidth = 2;
  ctx.font = font_size;

  for (let i = 0; i < duplicatedSections.length; i++) {
    const angle = startAngle + i * arc;

    // section fill
    ctx.fillStyle = duplicatedSections[i].color;
    ctx.beginPath();
    ctx.arc(canvas.width / 2, canvas.height / 2, outsideRadius, angle, angle + arc, false);
    ctx.arc(canvas.width / 2, canvas.height / 2, insideRadius, angle + arc, angle, true);
    ctx.stroke();
    ctx.fill();

    // section border
    ctx.strokeStyle = "#28a745";
    ctx.beginPath();
    ctx.arc(canvas.width / 2, canvas.height / 2, outsideRadius, angle, angle + arc, false);
    ctx.arc(canvas.width / 2, canvas.height / 2, insideRadius, angle + arc, angle, true);
    ctx.closePath();
    ctx.stroke();

    // text
    ctx.save();
    ctx.fillStyle = "#fff";
    ctx.translate(
      canvas.width / 2 + Math.cos(angle + arc / 2) * textRadius,
      canvas.height / 2 + Math.sin(angle + arc / 2) * textRadius
    );
    ctx.rotate(angle + arc / 2 + Math.PI / 2);

    const text = duplicatedSections[i].nombre;
    // if (text !== "Gira otra vez" || text !== "PRODUCTO SORPRESA") {
    //   ctx.fillText(text, -ctx.measureText(text).width / 2, 8);
    // } else {
			if (text == "Gira otra vez") {
				ctx.fillText("Gira", -ctx.measureText(text).width / 5, 8);
				ctx.fillText("otra vez", -ctx.measureText(text).width / 3, 30);
			} else if (text == "PRODUCTO SORPRESA") {
				ctx.fillText("Producto", -ctx.measureText(text).width / 5, 8);
				ctx.fillText("Sorpresa", -ctx.measureText(text).width / 5, 30);
			} else {
				ctx.fillText(text, -ctx.measureText(text).width / 2, 8);
			}
    // }

    ctx.restore();
  }

  // center image
  ctx.save();
  ctx.translate(canvas.width / 2, canvas.height / 2);
  ctx.rotate(startAngle);
  ctx.drawImage(centerImage, -(centerImageSize / 2), -(centerImageSize / 2), centerImageSize, centerImageSize);
  ctx.restore();

  // arrow
  ctx.save();
  ctx.drawImage(arrowImage, (canvas.width / 2) - (arrowSize / 2), -2, arrowSize, arrowSize);
  ctx.restore();

	Service.hide('.loading');
};

const spin = () => {
  btnSpin.disabled = true;
  
  // Reset audio (por si se reproduce varias veces)
  spinAndWinSound.currentTime = 0;
  spinAndWinSound.play();

  // Configuración de giro (9 segundos exactos)
  spinAngleStart = Math.random() * 10 + 10;
  spinTime = 0;
  spinTimeTotal = 10000;
  rotateWheel();
};


const rotateWheel = () => {
  spinTime += 30; // cada frame suma 30ms porque setTimeout usa 30ms abajo
  if (spinTime >= spinTimeTotal) {
    stopRotateWheel();
    return;
  }

  const spinAngle = spinAngleStart - easeOut(spinTime, 0, spinAngleStart, spinTimeTotal);
  startAngle += (spinAngle * Math.PI / 180);
  drawRouletteWheel();

  spinTimeout = setTimeout(rotateWheel, 30);
};


const stopRotateWheel = () => {
	clearTimeout(spinTimeout);

  const degrees = startAngle * 180 / Math.PI + 90;
  const arcd = arc * 180 / Math.PI;
  const index = Math.floor((360 - degrees % 360) / arcd);

  const prize = duplicatedSections[index];

  if (prize.nombre !== "Gira otra vez") {
    Service.show('.loading'); 

    const formData = new FormData();
    formData.append('userId', "<?= session()->get('user_sorteo')['user_id'] ?>");
    formData.append('productoId', prize.id);

    Service.exec('post', `/sorteo/ruleta`, formData_header, formData)
      .then(r => {
        if (r.success) {
					let selectedCode = `${prize.codigo}-${prize.lote}`

					Service.show('#premio'); 

  				renderPremio(selectedCode);
					Service.hide('.loading'); 

        }
      });
  }

  btnSpin.disabled = false;
};

// === Init ===
const btnSpin = document.getElementById("spin");
btnSpin?.addEventListener("click", spin);

const renderPremio = (selectedCode) => {
	let inventario = document.querySelectorAll('div .box-premio')

	inventario.forEach((box) => {
		if (box.dataset.codigo === selectedCode) {
			box.classList.add("bg-icon", "bg-opacity-70", "border-2", "border-title", 'text-white'); 
		}
	});
}

const renderSections = () => {
	const productGrid = document.getElementById("product-grid");

	let sections_filtered = sections.filter(section => section.codigo !== "again");

	sections_filtered.forEach((section) => {

		const productBox = document.createElement("div");
		productBox.dataset.codigo = `${section.codigo}-${section.lote}`;
		productBox.className = "box-premio w-full flex flex-col gap-y-2 justify-center items-center h-64";

		productBox.innerHTML = `
			<img src="${root}/files/download?path=sorteo_invent/${section.imagen || 'default-image.jpg'}" class="w-44 h-44" alt="${section.nombre}">
			<p>${section.nombre} - ${section.codigo}</p>
		`;

		productGrid.appendChild(productBox);
	});
}

renderSections();

</script>
</body>
</html>