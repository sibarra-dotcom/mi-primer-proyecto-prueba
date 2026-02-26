<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\CURLRequest; 

use App\Models\UserModel;

use App\Models\InspeccionFilesModel;

use App\Models\InspeccionFormatoModel;
use App\Models\InspeccionSectionModel;
use App\Models\InspeccionRegistroModel;

use App\Models\ReporteLimpModel;
use App\Models\ReporteInspecModel;

use App\Models\ReportesModel;

use App\Models\ReportesProdModel;
use App\Models\RepProdInspModel;
use App\Models\RepProdLimpiezaModel;
use App\Models\RepProdPersonalModel;
use App\Models\RepProdTiempoModel;
use App\Models\RepProdMetaModel;

use App\Models\MaquinariaModel;

use App\Models\ReportesRegistroModel;
use App\Models\ReportesPersonalModel;
use App\Models\ReporteIncModel;
use App\Models\ReporteDesvModel;
use App\Models\ProductosModel;
use App\Models\ProductosProcModel;
use App\Models\UserOperarioModel;

use App\Models\OrdenFabModel;
use App\Models\TurnosModel;


class OrdenFab extends BaseController
{
		protected $session;
		private $httpClient;
		private $user;
		private $turnos;

		public function __construct()
		{
				$this->session = \Config\Services::session();
				$this->httpClient = \Config\Services::curlrequest();
				$this->user = new UserModel();
				$this->turnos = new TurnosModel();
		}

		public function get_resumen($num_orden)
    {
			if ($this->request->getMethod() === 'GET')
			{
					$Reporte = new ReportesModel;
					$OrdenFab = new OrdenFabModel;
					$RepReg = new ReportesRegistroModel;

					// $production = $Reporte->getProductionData(2025, 9, $num_orden);
					// $orden = $OrdenFab->where('num_orden', $num_orden)->first();

					// $donut = $OrdenFab->getDonutData($num_orden);
					// $gauge = $OrdenFab->getGaugeData($num_orden);

					$production = $OrdenFab->getProductionData(2026, 01, $num_orden);
					$orden = $OrdenFab->where('num_orden', $num_orden)->first();

					$gauge = $OrdenFab->getGaugeData($num_orden);
					$donut = $OrdenFab->getDonutData($num_orden);


					$incidencias = $OrdenFab->getIncidenciasData($num_orden);

 					$total_fabricadas = $OrdenFab->getTotalPiezas($num_orden);
 					$piezas_totales = formatNumberMex(extractNumericValue($orden['cantidad_plan']), 0);

					$numericCantidadPlan = extractNumericValue($orden['cantidad_plan']);

					$piezas_faltantes = $numericCantidadPlan - $total_fabricadas['total'];

 					$piezas_faltantes =  formatNumberMex($piezas_faltantes, 0);

					$all_fechas = $OrdenFab->getFechasOrdenFab($num_orden);
					$all_tiempos = $OrdenFab->getPersonalTiempos($num_orden);

					$merma = $OrdenFab->getTotalMerma($num_orden);

					return $this->response->setJSON([
						'success' => true,
						'orden' =>[
							'num_orden' => "O.F. " . $num_orden,
							'desc_articulo' => $orden['desc_articulo'],
							'omg' => "OC N° " . $orden['omg'],
							'id' => $orden['id'],
							'status' => $production,
							'total_piezas_fabricadas' => formatNumberMex($total_fabricadas['total'], 0),
							'operadores_requeridos' => $production,
							'piezas_faltantes' => $piezas_faltantes,
							'duracion_total' => $production,
							'piezas_totales' => $piezas_totales . ' PZ',
							'tiempo_efectivo' => $production,
							'scrapt' => $merma['merma_percent'],
							'incidencias_total' => $incidencias['total_incidencias'],
							'tiempo_muerto' => $production,
							'incidencias' => $incidencias['incidencias'],
							'donut' => $donut,
							'gauge' => $gauge,
							'production' => $production,
							'fecha_compromiso'     => $all_fechas['fecha_compromiso'],
							'fecha_arranque'       => $all_fechas['fecha_arranque'],
							'fecha_termino'        => $all_fechas['fecha_termino'],
							'diferencia_compromiso'=> $all_fechas['diferencia_compromiso'],
							'status_orden'         => $all_fechas['status_orden'],
							'operadores_requeridos'         => $all_tiempos['operadores_requeridos'],
							'duracion_total'         => $all_tiempos['duracion_total'],
							'tiempo_efectivo'         => $all_tiempos['tiempo_efectivo'],
							'tiempo_muerto'         => $all_tiempos['tiempo_muerto'],
						]
					]);
			}


    }

		public function get_last()
    {
			if ($this->request->getMethod() === 'GET')
			{
					$Reporte = new ReportesModel;
					$OrdenFab = new OrdenFabModel;
					$RepReg = new ReportesRegistroModel;

					$last_orden = $OrdenFab->orderBy('id', 'DESC')->first();

					// $production = $OrdenFab->getProductionData(2025, 9, $last_orden['num_orden']);
					$production = $OrdenFab->getProductionData(2026, 01, $last_orden['num_orden']);
					$orden = $OrdenFab->where('num_orden', $last_orden['num_orden'])->first();

					$gauge = $OrdenFab->getGaugeData($last_orden['num_orden']);
					$donut = $OrdenFab->getDonutData($last_orden['num_orden']);

					$incidencias = $OrdenFab->getIncidenciasData($last_orden['num_orden']);

 					$total_fabricadas = $OrdenFab->getTotalPiezas($last_orden['num_orden']);
 					$piezas_totales = formatNumberMex(extractNumericValue($last_orden['cantidad_plan']), 0);

					$numericCantidadPlan = extractNumericValue($last_orden['cantidad_plan']);

					$piezas_faltantes = $numericCantidadPlan - $total_fabricadas['total'];

 					$piezas_faltantes =  formatNumberMex($piezas_faltantes, 0);

					$all_fechas = $OrdenFab->getFechasOrdenFab($last_orden['num_orden']);
					$all_tiempos = $OrdenFab->getPersonalTiempos($last_orden['num_orden']);

					$merma = $OrdenFab->getTotalMerma($last_orden['num_orden']);

					return $this->response->setJSON([
						'success' => true,
						'orden' =>[
							'num_orden' => "O.F. " . $last_orden['num_orden'],
							'desc_articulo' => $last_orden['desc_articulo'],
							'omg' => "OC N° " . $last_orden['omg'],
							'id' => $orden['id'],
							'status' => $production,
							'total_piezas_fabricadas' => formatNumberMex($total_fabricadas['total'], 0),
							'operadores_requeridos' => $production,
							'piezas_faltantes' => $piezas_faltantes,
							'duracion_total' => $production,
							'piezas_totales' => $piezas_totales . ' PZ',
							'tiempo_efectivo' => $production,
							'scrapt' => $merma['merma_percent'],
							'incidencias_total' => $incidencias['total_incidencias'],
							'tiempo_muerto' => $production,
							'incidencias' => $incidencias['incidencias'],
							'donut' => $donut,
							'gauge' => $gauge,
							'production' => $production,
							'fecha_compromiso'     => $all_fechas['fecha_compromiso'],
							'fecha_arranque'       => $all_fechas['fecha_arranque'],
							'fecha_termino'        => $all_fechas['fecha_termino'],
							'diferencia_compromiso'=> $all_fechas['diferencia_compromiso'],
							'status_orden'         => $all_fechas['status_orden'],
							'operadores_requeridos'         => $all_tiempos['operadores_requeridos'],
							'duracion_total'         => $all_tiempos['duracion_total'],
							'tiempo_efectivo'         => $all_tiempos['tiempo_efectivo'],
							'tiempo_muerto'         => $all_tiempos['tiempo_muerto'],
						]
					]);
			}


    }

}


