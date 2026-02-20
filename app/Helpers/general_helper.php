<?php

if (!function_exists('load_asset')) {
  function load_asset($path) {
    return base_url($path) . '?v=' . filemtime(FCPATH . $path);
  }
}


if (! function_exists('setArticleStatus')) {

// echo setArticleStatus(1, 'client');    // Outputs: "00001"
// echo setArticleStatus(2, 'turnero');    // Outputs: "1002"
// echo setArticleStatus(2, 'reserva');    // Outputs: "100002"
  function setArticleStatus($status) {
    switch($status) {
      case "PENDIENTE":
      return ['color' => 'text-pendiente border-pendiente text-opacity-0', 'icon' => '<i class="fa fa-minus px-1"></i>'];
      break;
      case "APROBADO":
      return ['color' => 'text-aprobado border-aprobado', 'icon' => '<i class="fa fa-check px-1"></i>'];
      break;
      case "NO APROBADO":
      return ['color' => 'text-rechazado border-rechazado', 'icon' => '<i class="fa fa-minus px-1"></i>'];
      break;
    }
  }

}

function renderProgressBar($percentage) {
	$percentage = max(0, min(100, $percentage)); 
	?>
	<div class="progress-container">
			<div class="progress-bar" style="width: <?= $percentage ?>%;">
			</div>
	</div>
	<?php
}



if (! function_exists('format_id')) {

// echo format_id(1, 'client');    // Outputs: "00001"
// echo format_id(2, 'turnero');    // Outputs: "1002"
// echo format_id(2, 'reserva');    // Outputs: "100002"
  function format_id($id, $type) {
    switch($type) {
      case "id":
      return str_pad($id, 6, '0', STR_PAD_LEFT);
      break;
      case "turnero":
      $base = 1000;
      return str_pad($base + $id, 4, '0', STR_PAD_LEFT);
      break;
      case "reserva":
      $base = 100000;
      return str_pad($base + $id, 6, '0', STR_PAD_LEFT);
      break;
    }
  }

}

if (! function_exists('dateToString')) {
  function dateToString($dateTimeString) {
    $dateTime = new DateTime($dateTimeString);
    return $dateTime->format('d-m-Y');
  }
}

if (! function_exists('calculateAge')) {
  function calculateAge($dob) {
    $dobDateTime = new DateTime($dob);
    $todayDateTime = new DateTime();

    $age = $todayDateTime->diff($dobDateTime);
    return $age->y;
  }
}

if (! function_exists('getDateRange')) {
  function getDateRange($startDate, $endDate) {
    $dateArray = [];
    $currentDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    while ($currentDate <= $endDate) {
      $dateArray[] = date('Y-m-d', $currentDate);
      $currentDate = strtotime('+1 day', $currentDate);
    }

    return $dateArray;
  }
}

if (! function_exists('ellipsis')) {
  function ellipsis($string, $maxLength) {
    if (strlen($string) <= $maxLength) {
      return $string;
    }

    $string_ellipsis = substr($string, 0, $maxLength);
    return $string_ellipsis . '.';
  }
}
