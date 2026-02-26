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
use App\Models\InformeresModel;

use App\Models\OpeniaQueriesModel;

class Informeres extends BaseController
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
				$this->Informeres = new InformeresModel();
		}

		public function informe_resultados()
    {
				if ($this->request->getMethod() === 'GET')
				{
						$data['title'] = 'Liberación de Mezclas';
						$data['title_group'] = 'Producción';
						return view('produccion/_informe_resultados', $data);
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

				$data['detalle_producto'] = $this->Productos->where('codigo', $orden_fab['num_articulo'])->first();;

				$data['liberaciones'] = $this->Liberacion->getAll($ordenId);

				return $this->response->setJSON($data);
			}
    }

		public function data_informe(string $ordenId, $id = null)
    {
			if ($this->request->getMethod() === 'GET')
			{
				if($ordenId && $id) {
					// pendiente
					return $this->response->setJSON($this->Liberacion->getAll($ordenId, $id));
				}

				// $orden_fab = $this->ordenFab->where('id', $ordenId)->first();

				$data['informe'] = $this->Informeres->where('ordenId', $ordenId)->first();;

				// $data['liberaciones'] = $this->Liberacion->getAll($ordenId);

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
			
				$data['formato'] = $this->formatoDocs->getCurrentFormatoBySlug("informe_resultados");


				$orden_fab = $this->ordenFab->where('num_orden', $num_orden)->first();

				$data['producto'] = $this->Productos->where('codigo', $orden_fab['num_articulo'])->first();

				$data['orden_fab'] = $orden_fab;

				$data['title'] = 'Informe Resultados';
				$data['title_group'] = 'Producción';

				// echo "<pre>";
				// print_r($data);
				// exit;

				return view('informeres/create', $data);
			}

			if ($this->request->getMethod() === 'POST')
			{
				// echo "<pre>";
				// print_r($_POST);
				// exit;

				$fields = [
					'ordenId',
					'clave_rastreabilidad',
					'lote',
					'caducidad',
				];

				foreach ($fields as $field) {
						$value = $this->request->getPost($field);
						$data_informe[$field] = empty($value) ? "" : $value;
				}

				$data_informe['userId'] = $this->session->get('user')['id'];
				$data_informe['resultados'] =	$this->request->getPost('resultados') ?: [];

				$fecha_emision = $this->request->getPost('fecha_emision');

				if(!empty($fecha_emision)) {
					$data_informe['fecha_emision'] = $fecha_emision;
				}

				$fecha_recepcion = $this->request->getPost('fecha_recepcion');

				if(!empty($fecha_recepcion)) {
					$data_informe['fecha_recepcion'] = $fecha_recepcion;
				}

				$hora_muestra = $this->request->getPost('hora_muestra');

				if(!empty($hora_muestra)) {
					$data_informe['hora_muestra'] = $hora_muestra;
				}



				if(empty($this->request->getPost('id'))) {
					$informe_id = $this->Informeres->insert($data_informe);
				} else {
					$informe_id = $this->request->getPost('id');
					$this->Informeres->update($informe_id, $data_informe);
				}

				if ($informe_id) {
						return $this->response->setJSON([
								'success' => true,
								'redirect' => '/success-page'
						]);

				} else {
						return $this->response->setJSON([
								'success' => false,
								'errors' => "error en la base de datos"
						]);
				}

			}
    }

	 	public function ordenes_informe()
    {
        if ($this->request->getMethod() === 'GET')
        {
            $data['title'] = 'Lista Orden de Fabricación';
            $data['title_group'] = 'Producción';

            return view('produccion/ordenes_informe', $data);
        }

		}

		public function firmas($informeId)
    {
			if ($this->request->getMethod() === 'GET')
      {
					if (!$informeId) {
							return $this->failValidationErrors('informe_id is required.');
					}

					$informe = $this->Informeres->where('id', $informeId)->first();

					$calidad = $this->user->where("id", $informe['calidadId'])->first();
					$laboratorio = $this->user->where("id", $informe['laboratorioId'])->first();

					return $this->response->setJSON([
							'informe' => $informe,
							'laboratorio' => $laboratorio,
							'calidad' => $calidad,
					], ResponseInterface::HTTP_OK);
			}

			if ($this->request->getMethod() === 'POST')
      {
				$informeId = $this->request->getPost('informeId');
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


				if($userId) {
					$data["$area"] = $userId;
				}

				// echo "<pre>";
        // print_r($data);
        // exit;

				$response = $this->Informeres->update($informeId, $data);

        return $this->response->setJSON(['success' => $response, 'informeId' => $informeId]);


			}
    }

}