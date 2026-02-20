<?php echo view('_partials/header'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
  <script src="<?= base_url('js/axios.js') ?>"></script>
  <script src="<?= base_url('js/helper.js') ?>"></script>

  <title><?= esc($title) ?></title>
</head>

<body>
<body class="relative min-h-screen">
<img src="<?= base_url('img/laboratorio.svg') ?>" class="hidden md:flex absolute bottom-0 -left-2 ">  
<div class=" relative flex flex-col items-center justify-center font-titil ">

  <?php echo view('dashboard/_partials/navbar'); ?>

  <a href="<?= base_url('auth/signout') ?>" class="hidden absolute top-10 right-10 px-8 py-4 text-lg bg-red bg-opacity-60 text-white rounded hover:bg-opacity-80 cursor-pointer "> Cerrar Sesion </a>

  <div class=" mb-2 text-title w-full md:py-4 md:px-16 p-2 flex items-center ">
    <h2 class="text-center font-bold w-full text-3xl "><?= esc($title) ?></h2>
    <a href="<?= base_url('dashboard') ?>" class="self-end hover:scale-105 transition-transform duration-300 "> <i class="fas fa-arrow-turn-up fa-2x fa-rotate-270"></i></a>
  </div>

  <div class=" z-30 md:py-4 md:px-24  grid grid-cols-2 gap-16 md:grid-cols-3 lg:grid-cols-7 lg:gap-x-16 lg:gap-y-8 md:gap-20 ">

    <div class="card__app card__info">
      <a href="<?= base_url('cotizar') ?>" class="w-full h-full flex flex-col items-center justify-center">
        <!-- <i class="fas fa-file-lines fa-3x"></i> -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.25" stroke="none" class="h-20 w-20">
          <path stroke-linecap="round" stroke-linejoin="round" d="M 4.47,3.53 V 20.38 H 19.47 V 3.53 Z M 11.67,5.15 H 18.27 V 6.4 H 11.67 Z M 5.71,7.47 H 18.26 V 8.71 H 5.71 Z M 5.73,9.35 H 18.27 V 10.6 H 5.73 Z M 5.71,11.27 H 18.26 V 12.51 H 5.71 Z M 11.65,17.77 H 18.26 V 19.01 H 11.65 Z" />
        </svg>
        <p class="text-xs " style="font-size: 14px; font-weight: bold;">Cotizaciones</p>
      </a>
    </div>


    <div class="card__app card__info">
      <a href="<?= base_url('mantenimiento') ?>" class="w-full h-full flex flex-col items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.25" stroke="none" class="w-20 h-20">
          <path stroke-linecap="round" stroke-linejoin="round" d="M 10.76 4.53 L 8.2 5.43 L 8.41 7.41 A 6.09 6.09 0 0 0 7.65 8.11 L 5.68 7.73 L 4.5 10.17 L 6.06 11.44 A 6.09 6.09 0 0 0 6 12.47 L 4.35 13.59 L 5.24 16.14 L 7.1 15.95 L 3.91 18.58 A 1.46 1.46 0 1 0 5.97 20.64 L 7.58 18.68 L 9.99 19.84 L 11.25 18.29 A 6.09 6.09 0 0 0 12.28 18.35 L 13.4 20 L 15.96 19.1 L 15.76 17.11 A 6.09 6.09 0 0 0 16.52 16.42 L 18.49 16.8 L 19.66 14.36 L 18.1 13.09 A 6.09 6.09 0 0 0 18.16 12.06 L 19.82 10.94 L 18.92 8.39 L 16.94 8.59 A 6.09 6.09 0 0 0 16.24 7.81 L 16.62 5.86 L 14.17 4.69 L 12.92 6.23 A 6.09 6.09 0 0 0 11.88 6.18 L 10.76 4.53 z M 12.32 7.86 A 4.41 4.41 0 0 1 16.24 10.8 A 4.41 4.41 0 0 1 13.54 16.42 A 4.41 4.41 0 0 1 9.79 16 L 10.07 15.65 C 10.47 15.18 11.15 15.08 11.77 15.14 A 2.58 2.58 0 0 0 14.57 12.56 C 14.57 12.18 14.49 11.82 14.34 11.5 L 12.46 13.38 A 1.72 1.72 0 0 1 11.17 12.09 L 13.05 10.21 A 2.58 2.58 0 0 0 12.01 9.98 A 2.58 2.58 0 0 0 9.41 12.78 C 9.46 13.4 9.37 14.08 8.89 14.48 L 8.5 14.8 A 4.41 4.41 0 0 1 7.92 13.72 A 4.41 4.41 0 0 1 10.62 8.11 A 4.41 4.41 0 0 1 12.32 7.86 z M 4.76 19.04 A 0.77 0.77 0 0 1 5.53 19.81 A 0.77 0.77 0 0 1 4.76 20.58 A 0.77 0.77 0 0 1 3.99 19.81 A 0.77 0.77 0 0 1 4.76 19.04 z" />
        </svg>
        <p class="text-xs " style="font-size: 14px; font-weight: bold;">Mantenimiento</p>
      </a>
    </div>


    <div class="card__app card__info">
      <a href="<?= base_url('openia') ?>" class="w-full h-full flex flex-col items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.25" stroke="none" class="w-32 h-32">
          <path stroke-linecap="round" stroke-linejoin="round" d="M 11.18,3.81 C 9.42,3.8 7.86,4.94 7.32,6.61 6.19,6.84 5.22,7.55 4.65,8.55 3.76,10.07 3.96,11.99 5.14,13.3 4.78,14.39 4.9,15.59 5.49,16.58 6.36,18.11 8.12,18.89 9.85,18.52 10.61,19.39 11.71,19.88 12.86,19.87 14.62,19.87 16.18,18.74 16.73,17.06 17.85,16.83 18.83,16.13 19.4,15.12 20.28,13.6 20.08,11.69 18.9,10.38 19.27,9.29 19.14,8.09 18.56,7.1 17.68,5.57 15.92,4.78 14.2,5.15 13.43,4.29 12.33,3.8 11.18,3.81 Z M 11.18,4.86 C 11.89,4.86 12.57,5.1 13.11,5.55 13.08,5.57 13.04,5.59 13.01,5.61 L 9.81,7.46 C 9.65,7.55 9.55,7.72 9.55,7.91 L 9.55,12.42 V 12.42 L 8.2,11.64 C 8.18,11.63 8.17,11.62 8.17,11.6 V 7.87 C 8.17,6.2 9.52,4.86 11.18,4.86 Z M 15.11,6.12 C 16.12,6.14 17.1,6.68 17.65,7.62 18,8.23 18.12,8.94 18.01,9.63 17.98,9.62 17.94,9.6 17.91,9.58 L 14.71,7.73 C 14.55,7.64 14.35,7.64 14.19,7.73 L 10.28,9.99 V 8.43 C 10.28,8.41 10.29,8.39 10.3,8.38 L 13.54,6.52 C 14.03,6.23 14.57,6.1 15.11,6.12 Z M 7.12,7.76 C 7.12,7.78 7.12,7.83 7.12,7.87 V 11.56 C 7.12,11.75 7.22,11.92 7.38,12.02 L 11.29,14.27 9.94,15.05 C 9.92,15.06 9.91,15.06 9.89,15.05 L 6.66,13.19 C 5.22,12.35 4.73,10.52 5.56,9.08 L 5.56,9.08 C 5.91,8.47 6.46,8 7.12,7.76 Z M 14.13,8.62 C 14.14,8.62 14.15,8.62 14.15,8.62 L 17.39,10.49 C 18.83,11.32 19.32,13.16 18.49,14.6 18.14,15.21 17.58,15.68 16.92,15.92 V 12.11 C 16.92,11.93 16.82,11.75 16.66,11.66 L 16.66,11.66 12.76,9.41 14.11,8.63 C 14.12,8.62 14.12,8.62 14.13,8.62 Z M 12.02,9.83 13.76,10.83 V 12.84 L 12.02,13.85 10.28,12.84 V 10.83 Z M 14.49,11.26 15.85,12.04 C 15.86,12.05 15.87,12.06 15.87,12.08 V 15.81 C 15.87,17.47 14.52,18.82 12.86,18.82 12.16,18.82 11.48,18.57 10.94,18.12 10.96,18.11 11,18.09 11.03,18.07 L 14.23,16.22 C 14.39,16.13 14.49,15.96 14.49,15.77 Z M 13.76,13.69 V 15.25 C 13.76,15.27 13.75,15.28 13.74,15.29 L 10.51,17.16 C 9.07,17.99 7.23,17.5 6.4,16.06 H 6.4 C 6.04,15.45 5.92,14.73 6.04,14.04 6.06,14.05 6.1,14.08 6.13,14.1 L 9.33,15.94 C 9.49,16.04 9.7,16.04 9.86,15.94 Z" />
        </svg>

        <p class="text-xs " style="font-size: 14px; font-weight: bold;">IA Gibanibb</p>
      </a>
    </div>

   <div class="card__app card__info">
      <a href="https://desarrollosti.com/activos-ti/public/" class="w-full h-full flex flex-col items-center justify-center" target="_blank">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="0.55" stroke="currentColor" class=" h-24 w-24 stroke-title text-title hover:fill-none transition duration-300 ">
            <path stroke-linecap="round" stroke-linejoin="round" d="M 5.68 5.31 L 5.68 6.85 L 5.68 8.39 L 11.56 8.39 C 16.94 8.39 17.62 8.43 17.62 8.43 L 19.95 16.65 C 19.95 16.65 19.95 9.39 19.95 6.42 L 15.71 6.36 C 11.5 6.3 11.46 6.29 11.39 5.8 C 11.32 5.32 11.25 5.31 8.5 5.31 L 5.68 5.31 z M 10.16 9.59 L 3.47 9.6 C 3.97 11.49 5.53 17.62 5.64 18.07 L 5.86 18.87 L 12.77 18.87 L 19.69 18.87 L 19.18 17.09 C 18.9 16.11 18.28 14.02 17.82 12.45 L 16.97 9.59 L 10.16 9.59 z" />
          </svg>

        <p class="text-xs " style="font-size: 14px; font-weight: bold;">Activos TI</p>
      </a>
    </div>

    <div class="card__app card__info">
      <a href="<?= base_url('inactive/link') ?>" class="w-full h-full flex flex-col items-center justify-center">
        <img src="<?= base_url('img/keeper.svg') ?>" data-file="keeper" alt="" class="icon_svg object-contain ">
        <!-- <p class="text-xs ">Mantenimiento</p> -->
      </a>
    </div>

    <div class="card__app card__info">
      <a href="<?= base_url('inactive/link') ?>" class="w-full h-full flex flex-col items-center justify-center">
        <img src="<?= base_url('img/contabilidad.svg') ?>" data-file="contabilidad" alt="" class="icon_svg object-contain ">
        <!-- <p class="text-xs ">Mantenimiento</p> -->
      </a>
    </div>

    <div class="card__app card__info">
      <a href="<?= base_url('inactive/link') ?>" class="w-full h-full flex flex-col items-center justify-center">
        <img src="<?= base_url('img/nominas.svg') ?>" data-file="nominas" alt="" class="icon_svg object-contain ">
        <!-- <p class="text-xs ">Mantenimiento</p> -->
      </a>
    </div>

    <div class="card__app card__info">
      <a href="<?= base_url('inactive/link') ?>" class="w-full h-full flex flex-col items-center justify-center">
        <img src="<?= base_url('img/precisoft.svg') ?>" data-file="precisoft" alt="" class="icon_svg object-contain ">
        <!-- <p class="text-xs ">Mantenimiento</p> -->
      </a>
    </div>


    <div class="card__app card__info">
      <a href="<?= base_url('inactive/link') ?>" class="w-full h-full flex flex-col items-center justify-center">
        <img src="<?= base_url('img/workbeat.svg') ?>" data-file="workbeat" alt="" class="icon_svg object-contain ">
        <!-- <p class="text-xs ">Mantenimiento</p> -->
      </a>
    </div>
   <div class="card__app card__info">
      <a href="<?= base_url('inactive/link') ?>" class="w-full h-full flex flex-col items-center justify-center">
        <img src="<?= base_url('img/sap.svg') ?>" data-file="sap" alt="" class="icon_svg object-contain ">
        <!-- <p class="text-xs ">Mantenimiento</p> -->
      </a>
    </div>
    <div class="card__app card__info">
      <a href="<?= base_url('vacantes') ?>" class="w-full h-full flex flex-col items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="h-20 w-20">
          <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
        </svg>
        <p class="text-xs" style="font-size: 14px; font-weight: bold;">RH</p>
      </a>
    </div>

    <div class="card__app"><i class="fas fa-question fa-2x "></i></div>
    <div class="card__app"><i class="fas fa-question fa-2x "></i></div>
    <div class="card__app"><i class="fas fa-question fa-2x "></i></div>


    <div class="card__app"><i class="fas fa-question fa-2x "></i></div>
    <div class="card__app"><i class="fas fa-question fa-2x "></i></div>
    <div class="card__app"><i class="fas fa-question fa-2x "></i></div>
    <div class="card__app"><i class="fas fa-question fa-2x "></i></div>
    <div class="card__app"><i class="fas fa-question fa-2x "></i></div>
    <div class="card__app"><i class="fas fa-question fa-2x "></i></div>
    <div class="card__app"><i class="fas fa-question fa-2x "></i></div>

  </div>

</div>

<script>

const allIcons = document.querySelectorAll('.icon_svg');
allIcons?.forEach( icon => {
  let file = icon.getAttribute('data-file');

  icon.addEventListener('mouseover', () => {
      icon.src = `${root}/img/${file}hover.svg`;
      console.log(icon.src)
  });

  icon.addEventListener('mouseout', () => {
      icon.src = `${root}/img/${file}.svg`;
  });
});



</script>
</body>
</html>

