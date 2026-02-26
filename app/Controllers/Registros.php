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


use App\Models\ReportesModel;
use App\Models\ReportesRegistroModel;
use App\Models\ReportesPersonalModel;
use App\Models\ReporteIncModel;
use App\Models\ReporteDesvModel;
use App\Models\ProductosModel;
use App\Models\ProductosProcModel;


// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\OpeniaQueriesModel;

class Registros extends BaseController
{
		protected $session;
		private $httpClient;
		private $openiaTable;
		private $user;

		public function __construct()
		{
				$this->session = \Config\Services::session();
				$this->httpClient = \Config\Services::curlrequest();
				$this->openiaTable = new OpeniaQueriesModel();
				$this->user = new UserModel();
		}


    public function index()
    {
				$data['title'] = 'Registros';

				// echo "<pre>";
				// print_r($data);
				// exit;

				return view('registros/index', $data);
    }

    public function lista()
    {
			{
        if ($this->request->getMethod() === 'GET')
        {

					$data['title'] = 'LISTA REGISTRO DIARIO PRODUCCION';
					$data['title1'] = 'Manufactura GIBANIBB';

	
					return view('registros/lista', $data);
        }
			}
		}

		public function details($reporteId)
    {
			{
        if ($this->request->getMethod() === 'GET')
        {
					$Proc = new ProductosProcModel;
					$Inc = new ReporteIncModel;
					$Desv = new ReporteDesvModel;

					$RepPersonal = new ReportesPersonalModel();
					$RepRegistros = new ReportesRegistroModel();

					$Rep = new ReportesModel();


					$data['title'] = 'DETALLES REGISTRO DIARIO PRODUCCION';
					$data['title1'] = 'Manufactura GIBANIBB';

					$data['incidencias'] = $Inc->findAll();
					$data['desviaciones'] = $Desv->findAll();

					$data['personal'] = $RepPersonal->getById($reporteId);

					// $data['registros'] = $RepRegistros->where('reporteId', $reporteId)->first();
					$data['registros'] = $RepRegistros->getById($reporteId);

					$data['reporte'] = $Rep->getById($reporteId);
        
	
				// echo "<pre>";
				// print_r($data);
				// exit;

					return view('registros/details', $data);
        }


			}
		}



    public function incidencias()
    {
			{
        if ($this->request->getMethod() === 'GET')
        {
					$Proc = new ProductosProcModel;
					$Inc = new ReporteIncModel;
					$Desv = new ReporteDesvModel;

					$data['title'] = 'REGISTRO DIARIO PRODUCCION';
					$data['title1'] = 'Manufactura GIBANIBB';

					// $data['procesos'] = $maq->orderBy('id', 'DESC')->findAll();
					$data['procesos'] = $Proc->findAll();
					$data['incidencias'] = $Inc->findAll();
					$data['desviaciones'] = $Desv->findAll();
					// $data['procesos'] = $Proc->findAll();

					$data['operarios'] = $this->user->where('rol_id', 11)->get()->getResultArray();
					$data['lider_pro'] = $this->user->where('rol_id', 8)->get()->getResultArray();
	
					// echo "<pre>";
					// print_r($data);
					// exit;
	
					return view('registros/incidencias', $data);
        }

        if ($this->request->getMethod() === 'POST')
        {

						// echo "<pre>";
						// print_r($_POST);
						// exit;


						$formatoId = 2;

						$RepPersonal = new ReportesPersonalModel();
						$RepRegistros = new ReportesRegistroModel();

						$Rep = new ReportesModel();


						$data_prov = [
							'userId' => $this->session->get('user')['id'],
							'formatoId' => $formatoId,
							'produccionId' => $this->request->getPost('produccionId'),
							'firma_produccion' => $this->request->getPost('firma_produccion'),
							'fecha_firma_produccion' => $this->request->getPost('fecha_firma_produccion'),
						];

						$rep_id = $Rep->insert($data_prov);


						$desviaciones = $this->request->getPost('desviaciones');
						$incidencias = $this->request->getPost('incidencias');
						
						$desviaciones = is_array($desviaciones) ? implode(',', $desviaciones) : $desviaciones;
						$incidencias = is_array($incidencias) ? implode(',', $incidencias) : $incidencias;


						$data_registro = [
								'reporteId'            => $rep_id,
								'turno'                => $this->request->getPost('turno'),
								'incidencias'          => $incidencias,
								'desviaciones'         => $desviaciones,
								'nro_orden'            => $this->request->getPost('nro_orden'),
								'linea'                => $this->request->getPost('linea'),
								'productoId'           => $this->request->getPost('productoId'),
								'codigo'               => $this->request->getPost('codigo'),
								'procesoId'            => $this->request->getPost('procesoId'),
								'tipo_proceso'         => $this->request->getPost('tipo_proceso'),
								'meta_piezas'          => $this->request->getPost('meta_piezas'),
								'meta_cantidad'        => $this->request->getPost('meta_cantidad'),
								'meta_medida'          => $this->request->getPost('meta_medida'),
								'real_piezas'          => $this->request->getPost('real_piezas'),
								'real_cantidad'        => $this->request->getPost('real_cantidad'),
								'real_medida'          => $this->request->getPost('real_medida'),
								'total_personal'       => $this->request->getPost('total_personal'),
								'total_horas_extras'   => $this->request->getPost('total_horas_extras'),
								'total_tiempo_muerto'  => $this->request->getPost('total_tiempo_muerto'),
								'total_tiempo_efectivo'=> $this->request->getPost('total_tiempo_efectivo'),
								'observacion'          => $this->request->getPost('observacion'),
						];
	
						$RepRegistros->insert($data_registro);



						// $ids = $this->request->getPost('operario_id');
						// $hours = $this->request->getPost('operario_hours');

						// foreach ($ids as $index => $id) {
						// 		$data = [
						// 			'reporteId' => $rep_id,
						// 			'personalId' => $id,
						// 			'horas'      => $hours[$index],
						// 		];

						// 		$RepPersonal->insert($data);
						// }



						$ids   = $this->request->getPost('operario_id');
						$hours = $this->request->getPost('operario_hours');
						
						if (is_array($ids) && is_array($hours)) {
								foreach ($ids as $index => $id) {
										if (
												isset($hours[$index]) &&
												!empty($id) &&              // avoid empty id
												is_numeric($hours[$index]) // ensure hours is numeric
										) {
												$data = [
														'reporteId'  => $rep_id,
														'personalId' => $id,
														'horas'      => $hours[$index],
												];
						
												$RepPersonal->insert($data);
										}
								}
						}
						

						if ($RepRegistros) {
								// If no errors, respond with a redirect URL
								return $this->response->setJSON([
										'success' => true,
										'reporteId' => $rep_id,
										'redirect' => '/success-page'
								]);

								// $this->session->setFlashdata('msg', 'Creado Correctamente');
								// return redirect()->to('/maquinaria');

						} else {
								// If errors, respond with the errors array
								return $this->response->setJSON([
										'success' => false,
										'errors' => $validationErrors
								]);
								// $this->session->setFlashdata('msg_error', 'Ocurrio un Error');
								// $this->session->setFlashdata('msg', 'Ocurrio un Error');
								// return redirect()->to('/maquinaria');
						}
							

				}
			}
		}


		public function grafico()
    {
				$data['title'] = 'Meta de Producción vs Producción Real';

				// echo "<pre>";
				// print_r($data);
				// exit;

				return view('registros/grafico', $data);
    }

}
