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

use App\Models\FormatosDocsModel;
use App\Models\LiberacionModel;

use App\Models\OpeniaQueriesModel;

class Liberaciones extends BaseController
{
		protected $session;
		private $httpClient;
		private $openiaTable;
		private $user;
		private $turnos;

		public function __construct()
		{
				$this->session = \Config\Services::session();
				$this->httpClient = \Config\Services::curlrequest();
				$this->openiaTable = new OpeniaQueriesModel();
				$this->user = new UserModel();
				$this->turnos = new TurnosModel();
				$this->formatoDocs = new FormatosDocsModel();
				$this->ordenFab = new OrdenFabModel();
				$this->Productos = new ProductosModel();
				$this->Liberacion = new LiberacionModel();
		}

		public function liberacion()
    {
				if ($this->request->getMethod() === 'GET')
				{
						$data['title'] = 'Liberación de Mezclas';
						$data['title_group'] = 'Producción';
						return view('produccion/_liberacion', $data);
				}
		}

		public function detalles(string $ordenId, $id = null)
    {
			if ($this->request->getMethod() === 'GET')
			{
				if($ordenId && $id) {
					// pendiente
					return $this->response->setJSON($this->Liberacion->getAll($ordenId, $id));
				}

				$orden_fab = $this->ordenFab->where('id', $ordenId)->first();

				// $data['detalle_producto'] = $this->Productos->where('codigo', $orden_fab['num_articulo'])->first();

				$data['detalle_producto'] = $this->Productos->getByCodigoWithRanges($orden_fab['num_articulo']);

				$data['liberaciones'] = $this->Liberacion->getAll($ordenId);

				return $this->response->setJSON($data);
			}
    }

					// 		if (empty($errors)) {
					// 	return $this->returnJSON(true, 'Creado correctamente!', 'uber/details_caso/' . $caso_id);
					// } else {
					// 	return $this->returnJSON(false, 'Ocurrió un error');
					// }


		public function create(string $num_orden = null)
    {
			if ($this->request->getMethod() === 'GET')
			{
			
				$data['formato'] = $this->formatoDocs->getCurrentFormatoBySlug("liberacion_mezclas");


				$orden_fab = $this->ordenFab->where('num_orden', $num_orden)->first();

				$data['producto'] = $this->Productos->where('codigo', $orden_fab['num_articulo'])->first();

				$liberaciones = $this->Liberacion->getAll($orden_fab['id']);

				$data['dilusion'] = !empty($liberaciones) ? (end($liberaciones)['dilusion'] ?? '') : '';

				$data['orden_fab'] = $orden_fab;

				$data['title'] = 'Liberación de Mezclas';
				$data['title_group'] = 'Producción';

				// echo "<pre>";
				// print_r($data);
				// exit;

				return view('liberaciones/index', $data);
			}

			if ($this->request->getMethod() === 'POST')
			{
				// echo "<pre>";
				// print_r($_POST);
				// exit;

				$fields = [
					'ordenId',
					'especificacion',
					'hora_inicio',
					'hora_fin',
					'fecha',
					'descripcion_visual',
					'color',
					'sabor',
					'aroma',
					'humedad',
					'densidad',	
					'ph',
					'brix',
					'acidez',
					'tiempo_desintegracion',
					'lote_producto',
					'dilusion'
				];

				foreach ($fields as $field) {
						$value = $this->request->getPost($field);
						$data_liber[$field] = empty($value) ? "N.A." : $value;
				}

				$data_liber['userId'] = $this->session->get('user')['id'];


				if(empty($this->request->getPost('id'))) {
					$liber_id = $this->Liberacion->insert($data_liber);
				} else {
					$liber_id = $this->request->getPost('id');
					$this->Liberacion->update($liber_id, $data_liber);
				}

				if ($liber_id) {
						return $this->response->setJSON([
								'success' => true,
								'redirect' => '/success-page'
						]);

				} else {
						return $this->response->setJSON([
								'success' => false,
								'errors' => $validationErrors
						]);
				}

			}
    }

	 	public function ordenes_liberacion()
    {
        if ($this->request->getMethod() === 'GET')
        {
            $data['title'] = 'Lista Orden de Fabricación';
            $data['title_group'] = 'Producción';

            return view('produccion/ordenes_liberacion', $data);
        }

		}

		public function firmas($liberacionId)
    {
			if ($this->request->getMethod() === 'GET')
      {
					if (!$liberacionId) {
							return $this->failValidationErrors('liberacion_id is required.');
					}

					$liberacion = $this->Liberacion->where('id', $liberacionId)->first();

					$calidad = $this->user->where("id", $liberacion['calidadId'])->first();
					$laboratorio = $this->user->where("id", $liberacion['laboratorioId'])->first();
					$produccion = $this->user->where("id", $liberacion['produccionId'])->first();
					$desarrollo = $this->user->where("id", $liberacion['desarrolloId'])->first();

					return $this->response->setJSON([
							'liberacion' => $liberacion,
							'laboratorio' => $laboratorio,
							'calidad' => $calidad,
							'produccion' => $produccion,
							'desarrollo' => $desarrollo,
					], ResponseInterface::HTTP_OK);
			}

			if ($this->request->getMethod() === 'POST')
      {
				$liberacionId = $this->request->getPost('liberacionId');
				$field = $this->request->getPost('field');

				$userId = $this->request->getPost('userId') ?? '';
				$area = $this->request->getPost('area') ?? '';

        $data = [
            "$field" => "si",
        ];

				if($area == 'laboratorioId') {
					$data["fecha_firma_laboratorio"] = date('Y-m-d H:i:s');
				}

				if($area == 'calidadId') {
					$data["fecha_firma_calidad"] = date('Y-m-d H:i:s');
				}

				if($area == 'produccionId') {
					$data["fecha_firma_produccion"] = date('Y-m-d H:i:s');
				}

				if($area == 'desarrolloId') {
					$data["fecha_firma_desarrollo"] = date('Y-m-d H:i:s');
				}


				if($userId) {
					$data["$area"] = $userId;
				}

				// echo "<pre>";
        // print_r($data);
        // exit;

				$response = $this->Liberacion->update($liberacionId, $data);

        return $this->response->setJSON(['success' => $response, 'liberacionId' => $liberacionId]);


			}
    }
}