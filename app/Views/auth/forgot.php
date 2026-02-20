<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="<?= base_url('css/main.css') ?>">
  
  <title><?= esc($title) ?></title>
</head>

<body class="p-4 md:p-0 h-screen bg-neutralDark flex items-center justify-center relative font-noto">

  <div class="flex flex-col space-y-4 items-center bg-white w-full mx-auto max-w-2xl p-8 rounded-lg shadow-lg ">
    <h1 class="text-4xl text-ctaDark"><?= esc($message) ?></h1>
    <img src="<?= base_url('img/const.png') ?>" alt="Page in Progress" class="w-64 h-64 rounded-md">
    <p class="text-xl"><?= esc($message) ?></p>
  </div>

</body>
</html>
