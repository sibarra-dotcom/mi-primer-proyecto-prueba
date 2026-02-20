<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="<?= base_url('css/main.css') ?>">
  
  <title><?= esc($title) ?></title>
</head>

<body class="min-h-screen bg-grayMid bg-opacity-60 font-titil text-super flex flex-col items-center justify-center space-y-8  p-4 md:p-0 ">

  <h1 class="text-4xl">404</h1>
  <h3 class="text-xl">The page you are looking for does not exist.</h3>

  <img src="<?= base_url('img/404.webp') ?>" alt="404 Image" class="w-64 h-64 rounded-xl">

  <a href='https://api.whatsapp.com/send/?phone=51992819526&text=<?php echo urlencode("Hola, tengo una consulta") ?>' class="rounded px-8 py-2 bg-icon text-white text-xl ">Contact to Support</a>

</body>
</html>