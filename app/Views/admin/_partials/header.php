<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>

  <script src="<?= base_url('js/Validate.min.js') ?>"></script>
  <script src="<?= base_url('js/helper.js') ?>"></script>

  <link rel="stylesheet" href="<?= base_url('css/main.css') ?>">
  <link rel="stylesheet" href="<?= base_url('css/nc_icons.min.css') ?>">
  <title><?= esc($title) ?></title>

<style>
  

  td {
    max-width: auto;
    border: 1px solid #ccc;
    padding: 2px 4px;
    text-align: left;
    color: black;
  }

  tr{
    background-color: white;
  }


  th {
    font-weight: 300;
    font-size: 14px;
    position: sticky;
    top: 0;
    padding: 10px 4px;
    background-color: #212121;
    color: #fff;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #e2e3e5;
  }



</style>
</head>

<body class="relative  flex flex-col font-noto">

  <nav class="w-full px-2 md:px-32 py-2 drop-shadow flex items-center justify-between">
    <a href="<?= base_url('user/dashboard') ?>" > <img src="<?= base_url('img/leaves.png') ?>" alt="Login Image" class="w-16 h-16 rounded-full "> </a>

    <div class="flex items-center space-x-6">
      <a href="<?= base_url('user/link') ?>" > <i class="fas fa-envelope text-twt text-2xl"></i> </a>
      <a href="<?= base_url('user/link') ?>" > <img src="<?= base_url('img/user_img.png') ?>" alt="Login Image" class="w-10 h-10 rounded-full border-2 border-cta"> </a>
    </div>
  </nav>