<?php

if (!function_exists('fechaEspanol')) {
	function fechaEspanol($fecha)
	{
			$meses = [
					'01' => 'ENE',
					'02' => 'FEB',
					'03' => 'MAR',
					'04' => 'ABR',
					'05' => 'MAY',
					'06' => 'JUN',
					'07' => 'JUL',
					'08' => 'AGO',
					'09' => 'SEP',
					'10' => 'OCT',
					'11' => 'NOV',
					'12' => 'DIC'
			];

			list($dia, $mes, $anio) = explode('-', $fecha);

			return $dia . '-' . $meses[$mes] . '-' . $anio;
	}
}

if (!function_exists('load_asset')) {
  function load_asset($path) {
    return base_url($path) . '?v=' . filemtime(FCPATH . $path);
  }
}


if (!function_exists('formatInspTitle')) {
	function formatInspTitle($inputString) {
		$upperCaseString = strtoupper($inputString);
		$formattedString = str_replace('-', ' ', $upperCaseString);
		return $formattedString;
	}
}

if (!function_exists('extractNumericValue')) {
	/**
	 * Convert a string like "1,400,520 PIEZAS" to a numeric value (float) for calculations.
	 *
	 * @param string $input The numeric string (can include commas, spaces, and text like "PIEZAS")
	 * @return float The cleaned numeric value
	 */
	function extractNumericValue($input) {
			// Remove all non-numeric characters except the decimal point
			$numericString = preg_replace('/[^0-9\.]/', '', $input);

			// Return the numeric value (as float)
			return (float)$numericString;
	}
}


if (!function_exists('formatNumberMex')) {
	/**
	 * Convert a numeric value (like 1400520.86) to a formatted string with commas and a dot.
	 *
	 * @param float $number The numeric value to format
	 * @param int $decimals The number of decimal places to round to (default is 2)
	 * @return string The formatted number
	 */
	function formatNumberMex($number, $decimals = 2) {
			// Format the number with commas and the dot as decimal separator
			return number_format($number, $decimals, '.', ',');
	}

}


if (!function_exists('formatDateToMonthYear')) {
	function formatDateToMonthYear($dateStr) {
		setlocale(LC_TIME, 'es_ES');
		$date = new DateTime($dateStr);
		$formattedDate = strftime("%B %Y", $date->getTimestamp());
		return strtoupper($formattedDate);
	}
}

if (!function_exists('cleanFileName')) {
	function cleanFileName($fileName)	{
    $fileName = removeAccents($fileName);
    $fileName = preg_replace('/[^a-zA-Z0-9_]/u', '_', $fileName);
    $fileName = trim($fileName, '_');
    return $fileName;
	}
}

if (!function_exists('removeAccents')) {
	function removeAccents($str)
	{
		$accents = [
				'á' => 'a', 'à' => 'a', 'ä' => 'a', 'ã' => 'a', 'â' => 'a', 'å' => 'a', 'æ' => 'ae',
				'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e', 'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i',
				'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'õ' => 'o', 'ô' => 'o', 'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u',
				'ñ' => 'n', 'Ñ' => 'N', 'ç' => 'c', 'Ç' => 'C', 'ý' => 'y', 'ÿ' => 'y', 'Á' => 'A', 'É' => 'E', 'Í' => 'I',
				'Ó' => 'O', 'Ú' => 'U', 'Ñ' => 'N'
		];
	
		return strtr($str, $accents);
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
