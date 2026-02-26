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


use App\Models\OpeniaQueriesModel;

class Produccion extends BaseController
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
		}

		public function delete_operario($id)
		{
				$RepProdPersonal = new RepProdPersonalModel();
				$row = $RepProdPersonal->find($id);
				
				if ($row) {
						$RepProdPersonal->delete($id);
						return $this->response->setJSON(['status' => 'success', 'message' => 'Operario deleted successfully']);
				} else {
						return $this->response->setJSON(['status' => 'error', 'message' => 'Operario not found']);
				}
		}

		public function delete_incidencia($id)
		{
				$RepProdTiempo = new RepProdTiempoModel();
				$row = $RepProdTiempo->find($id);
				
				if ($row) {
						$RepProdTiempo->delete($id);
						return $this->response->setJSON(['status' => 'success', 'message' => 'Operario deleted successfully']);
				} else {
						return $this->response->setJSON(['status' => 'error', 'message' => 'Operario not found']);
				}
		}

		public function ordenes_update($orden_id)
    {
        if ($this->request->getMethod() === 'GET')
        {
						if(!$orden_id) {
							return redirect()->to('produccion/ordenes_lista');
						}

            $data['title'] = 'Actualizar Orden de Fabricación';
						$data['orden_id'] = $orden_id;

            return view('produccion/ordenes_update', $data);
        }

		}

		public function ordenes_create()
    {
        if ($this->request->getMethod() === 'GET')
        {
            $maq = new MaquinariaModel();
					
						$data['plantas'] = $maq->getPlantas();
            $data['title'] = 'Nueva Orden de Fabricación';

            return view('produccion/ordenes_create', $data);
        }

				if ($this->request->getMethod() === 'POST')
        {
						// echo "<pre>";
						// print_r($_FILES);
						// print_r($_POST);
						// exit;

            $OrdenFab = new OrdenFabModel();

						$orden_data['userId'] = $this->session->get('user')['id'];

						$fields = [
								'num_orden',
								'fecha_vencimiento',
								'fecha_compromiso',
								'omg',
								'codigo_cliente',
								'pedido',
								'tipo_orden',
								'nombre_deudor',
								'status_pedido',
								'origen',
								'cantidad_plan',
								'num_articulo',
								'desc_articulo',
								'lote',
								'rango_min',
								'rango_ideal',
								'rango_max',
								'caducidad',
								'rfc_cliente',
								'num_piezas',
						];

						foreach ($fields as $field) {
							$orden_data[$field] = $this->request->getPost($field);
						}


						if(!empty($this->request->getPost('id'))) {
							$orden_id = $this->request->getPost('id');
							$updated = $OrdenFab->update($orden_id, $orden_data);
						} else {
							$orden_id = $OrdenFab->insert($orden_data);
						}


            if ($orden_id || $updated) {
                // If no errors, respond with a redirect URL
                return $this->response->setJSON([
                    'success' => true,
                    'redirect' => '/success-page'
                ]);

                // $this->session->setFlashdata('msg', 'Creado Correctamente');
                // return redirect()->to('/maquinaria');

            } else {
                // If errors, respond with the errors array
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $updated
                ]);
                // $this->session->setFlashdata('msg_error', 'Ocurrio un Error');
                // $this->session->setFlashdata('msg', 'Ocurrio un Error');
                // return redirect()->to('/maquinaria');
            }
        }

		}

		public function lista_paros()
    {
        if ($this->request->getMethod() === 'GET')
        {
            $data['title'] = 'Lista Paros';
            $data['title_group'] = 'Producción';

            return view('produccion/lista_paros', $data);
        }
		}


		public function get_lista_paros(string $date = null)
    {
        if ($this->request->getMethod() === 'GET')
        {
						$OrdenFab = new OrdenFabModel();

						$incidencias = $OrdenFab->getTiemposIncidencias($date);

						return $this->response->setJSON($incidencias);

            if (empty($validationErrors)) {
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

		public function get_incidencias_daterange(string $startDate, string $endDate)
    {
        if ($this->request->getMethod() === 'GET')
        {
						$RepProdTiempo = new RepProdTiempoModel();

						$incidencias = $RepProdTiempo->getIncidenciasByDateRange($startDate, $endDate);

						return $this->response->setJSON($incidencias);

            if (empty($validationErrors)) {
                // If no errors, respond with a redirect URL
                return $this->response->setJSON([
                    'success' => true,
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


    public function ordenes_lista()
    {
        if ($this->request->getMethod() === 'GET')
        {
            $data['title'] = 'Lista Orden de Fabricación';
            $data['title_group'] = 'Producción';

            return view('produccion/ordenes_lista', $data);
        }

        if ($this->request->getMethod() === 'POST')
        {
							// echo "<pre>";
							// print_r($_FILES);
							// print_r($this->request->getFiles());
							// print_r($_POST);
							// exit;

		
            $maq = new MaquinariaModel();
            $Maq_files = new MaquinariaFilesModel();

            $data_maq = [
                'clave' => $this->request->getPost('clave'),
                'estado' => $this->request->getPost('estado'),
                'tipo' => $this->request->getPost('tipo'),
                'nombre' => $this->request->getPost('nombre'),
                'marca' => $this->request->getPost('marca'),
                'modelo' => $this->request->getPost('modelo'),
                'serie' => $this->request->getPost('serie'),
                'year' => $this->request->getPost('fechaAdqui'),
                'planta' => $this->request->getPost('planta'),
                'linea' => $this->request->getPost('linea'),
                'fechaAdqui' => $this->request->getPost('fechaAdqui'),
            ];


						if(!empty($this->request->getPost('id'))) {
							$maquinaria_id = $this->request->getPost('id');
							$maq->update($maquinaria_id, $data_maq);
						} else {
							$maquinaria_id = $maq->insert($data_maq);
						}

            
						$targetDir = WRITEPATH . 'storage/maquinaria/' . $maquinaria_id;


            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $files = $this->request->getFiles();

            $savedFilePaths = []; 
            $validationErrors = [];

            if ( !empty($files) && $files['archivo'][0]->isValid() ) {

                foreach ($files['archivo'] as $file) { 

                    $allowedMimeTypes = [
                        'image/png',
                        'image/jpeg',
                        'image/jpg',
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    ];

                    $maxSizeInMB = 10; // size in MB
                    $maxSize = $maxSizeInMB * 1024 * 1024; 

                    if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
                        $validationErrors[] = [
                            'file' => $file->getName(),
                            'error' => 'Invalid file type'
                        ];
                        continue;
                    }

                    if ($file->getSize() > $maxSize) {
                        $validationErrors[] = [
                            'file' => $file->getName(),
                            'error' => "File exceeds maximum $maxSizeInMB MB"
                        ];
                        continue;
                    }

                    if ($file->isValid() && !$file->hasMoved()) {
                        $originalFileName = $file->getClientName();
                        $file->move($targetDir, $originalFileName);

                        $data = [
														'userId' => $this->session->get('user')['id'],
                            'maquinariaId' => $maquinaria_id,
                            'archivo'      => $maquinaria_id . DIRECTORY_SEPARATOR . $originalFileName,
                        ];

                        $Maq_files->insert($data);
                    } else {
                        $validationErrors[] = [
                            'file' => $file->getName(),
                            'error' => $file->getErrorString()
                        ];
                        continue;
                    }
                }


            }


            if (empty($validationErrors)) {
                // If no errors, respond with a redirect URL
                return $this->response->setJSON([
                    'success' => true,
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

		public function ordenes_registros($num_orden)
    {
        if ($this->request->getMethod() === 'GET')
        {
						$OrdenFab = new OrdenFabModel();

						$data['ordenfab'] = $OrdenFab->where('num_orden', $num_orden)->first();

            $data['title'] = 'Lista Registros - Orden Fab. #' . $num_orden;
            $data['title_group'] = 'Producción';
						$data['num_orden'] = $num_orden;

            return view('produccion/ordenes_registros', $data);
        }
		}

    public function index()
    {
				if ($this->request->getMethod() === 'GET')
				{
						$data['title'] = 'Producción';
						$data['title_group'] = 'Producción';
						return view('produccion/index', $data);
    		}
    }

    public function lista()
    {
				if ($this->request->getMethod() === 'GET')
				{
						$data['title'] = 'LISTA PROCESOS REPORTADOS';
						$data['title_group'] = 'Producción';
						return view('produccion/lista', $data);
				}
		}

		public function certif()
    {
				if ($this->request->getMethod() === 'GET')
				{
						$data['title'] = 'CERTIFICADO COA';
						$data['title_group'] = 'Producción';
						return view('produccion/_certif', $data);
				}
		}
		public function informe_resultados()
    {
				if ($this->request->getMethod() === 'GET')
				{
						$data['title'] = 'INFORME DE RESULTADOS';
						$data['title_group'] = 'Producción';
						return view('produccion/_informe_resultados', $data);
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
					$Turnos = new TurnosModel();


					$data['title'] = 'DETALLES PROCESOS REPORTADOS';
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

					return view('produccion/details', $data);
        }


			}
		}

		public function print_reg_diario($num_orden, $registroId)
    {
			if ($this->request->getMethod() === 'GET')
			{
				// if(!$num_orden) {
				// 	return redirect()->to('produccion/ordenes_lista');
				// }
				$Prod = new ProductosModel();

				$OrdenFab = new OrdenFabModel();

				$Proc = new ProductosProcModel;
				$Inc = new ReporteIncModel;
				$Desv = new ReporteDesvModel;

				$Formato = new InspeccionFormatoModel;
				$Section = new InspeccionSectionModel;



				$RepInsp = new ReporteInspecModel;
				$RepLimp = new ReporteLimpModel;

				$RepProd = new ReportesProdModel;
				// $user = new UserModel;

				$RepProdInsp = new RepProdInspModel;
				$RepLimpInsp = new RepProdLimpiezaModel;
				$RepPersonal = new RepProdPersonalModel;
				$RepTiempo = new RepProdTiempoModel;



				$data['insp_data'] = $RepProdInsp->where('reporteId', $registroId)->findAll();
				$data['limp_data'] = $RepLimpInsp->where('reporteId', $registroId)->findAll();
				$data['personal_data'] = $RepPersonal->where('reporteId', $registroId)->findAll();
				$data['tiempo_data'] = $RepTiempo->where('reporteId', $registroId)->findAll();


				$formato = $Formato->where('slug', 'reporte-produccion')->first();

				$data['inspId'] = "";
				$data['formato'] = $formato;


				$data['ordenfab'] = $OrdenFab->where('num_orden', $num_orden)->first();
				$data['registroId'] = $registroId;


				$data['acumulado'] = $RepProd->getAcumulado($data['ordenfab']['id']);


				$data['section_titles'] = $Section->getTitlesByFormatId(2);


				foreach ($data['section_titles'] as $title) {
					$titles[$title['section_number']] = $title['titulo'];
				}


				$data['secciones'] = $titles;

				$data['title'] = 'REGISTRO DIARIO PRODUCCION';
				$data['title_group'] = 'Producción';

				// $data['procesos'] = $maq->orderBy('id', 'DESC')->findAll();

				$data['procesos'] = $Proc->findAll();
				$data['incidencias'] = $Inc->findAll();
				$data['desviaciones'] = $Desv->findAll();
				$data['inspeccion'] = json_encode($RepInsp->findAll());
				$data['limpieza'] = json_encode($RepLimp->findAll());
				// $data['procesos'] = $Proc->findAll();

				
				$data['operarios'] = $this->user->getAllPersonal();


				// $data['operarios'] = $this->user->where('rol_id', 11)->get()->getResultArray();
				$data['lider_pro'] = $this->user->where('rol_id', 8)->get()->getResultArray();


				$data['reporte'] = $RepProd->getById($registroId);

					// echo "<pre>";
					// print_r($data);
					// exit;

				$data['lineas'] = $Prod->select('linea')->distinct()->findAll();
				$data['turnos'] = $this->turnos->getTurnosWithPlanta();

				return view('produccion/print_reg_diario', $data);

			}
		}


    public function registro_diariop($num_orden, $registroId)
    {
			if ($this->request->getMethod() === 'GET')
			{
				// if(!$num_orden) {
				// 	return redirect()->to('produccion/ordenes_lista');
				// }
				$Prod = new ProductosModel();

				$OrdenFab = new OrdenFabModel();

				$Proc = new ProductosProcModel;
				$Inc = new ReporteIncModel;
				$Desv = new ReporteDesvModel;

				$Formato = new InspeccionFormatoModel;
				$Section = new InspeccionSectionModel;



				$RepInsp = new ReporteInspecModel;
				$RepLimp = new ReporteLimpModel;

				$RepProd = new ReportesProdModel;
				// $user = new UserModel;

				$RepProdInsp = new RepProdInspModel;
				$RepLimpInsp = new RepProdLimpiezaModel;
				$RepPersonal = new RepProdPersonalModel;
				$RepTiempo = new RepProdTiempoModel;



				$data['insp_data'] = $RepProdInsp->where('reporteId', $registroId)->findAll();
				$data['limp_data'] = $RepLimpInsp->where('reporteId', $registroId)->findAll();
				$data['personal_data'] = $RepPersonal->where('reporteId', $registroId)->findAll();
				$data['tiempo_data'] = $RepTiempo->where('reporteId', $registroId)->findAll();


				$formato = $Formato->where('slug', 'reporte-produccion')->first();

				$data['inspId'] = "";
				$data['formato'] = $formato;


				$data['ordenfab'] = $OrdenFab->where('num_orden', $num_orden)->first();
				$data['registroId'] = $registroId;


				$data['acumulado'] = $RepProd->getAcumulado($data['ordenfab']['id']);


				$data['section_titles'] = $Section->getTitlesByFormatId(2);


				foreach ($data['section_titles'] as $title) {
					$titles[$title['section_number']] = $title['titulo'];
				}


				$data['secciones'] = $titles;

				$data['title'] = 'REGISTRO DIARIO PRODUCCION';
				$data['title_group'] = 'Producción';

				// $data['procesos'] = $maq->orderBy('id', 'DESC')->findAll();

				$data['procesos'] = $Proc->findAll();
				$data['incidencias'] = $Inc->findAll();
				$data['desviaciones'] = $Desv->findAll();
				$data['inspeccion'] = json_encode($RepInsp->findAll());
				$data['limpieza'] = json_encode($RepLimp->findAll());
				// $data['procesos'] = $Proc->findAll();

				
				$data['operarios'] = $this->user->getAllPersonal();


				// $data['operarios'] = $this->user->where('rol_id', 11)->get()->getResultArray();
				$data['lider_pro'] = $this->user->where('rol_id', 8)->get()->getResultArray();


				$data['reporte'] = $RepProd->getById($registroId);

					// echo "<pre>";
					// print_r($data);
					// exit;

				$data['lineas'] = $Prod->select('linea')->distinct()->findAll();
				$data['turnos'] = $this->turnos->getTurnosWithPlanta();

				return view('produccion/registro_diariop', $data);

			}
		}



    public function registro_update()
		{
			if ($this->request->getMethod() === 'POST')
			{
					// echo "<pre>";
					// print_r($_POST);
					// exit;

					$User = new UserModel();
					$RepProd = new ReportesProdModel();
					$RepProdLimpieza = new RepProdLimpiezaModel();
					$RepProdTiempo = new RepProdTiempoModel();
					$RepProdPersonal = new RepProdPersonalModel();
					$RepProdInsp = new RepProdInspModel();
					$RepInc = new ReporteIncModel();

					$fields = [
							'cajas_turno',
							'linea',
							'piezas_producidas',
							'muestras',
							'piezas_acumuladas',
							'batch_inicial',
							'batch_final',
							'cantidad_mezcla',
							'lote_mezcla',
							'peso_tm',
							'peso_tv',
							'colectiva',
							'status_fabricacion',
							'observacion_produc',
							'observacion_reporte',
							'hora_inicio_registro',
							'hora_fin_registro',
					];

					foreach ($fields as $field) {
						$reporte_data[$field] = $this->request->getPost($field);
					}

					$created_at = $this->request->getPost('created_at');

					if(!empty($created_at)) {
						$reporte_data['created_at'] = $created_at;
					}

					$reporteId = $this->request->getPost('reporteId');
				
					$updated = $RepProd->update($reporteId, $reporte_data);

					$componentesData = $this->request->getPost('componentes');

					foreach ($componentesData as $componente) {
							$data = [
									'cumple' => $componente['cumple'],
									'merma'  => $componente['merma'],
									'unidad' => $componente['unidad'],
							];
							$RepProdInsp->update($componente['id'], $data);
					}

					$limpiezaData = $this->request->getPost('limpieza');

					foreach ($limpiezaData as $item) {
							$data = [
									'cumple' => $item['cumple'],
							];

							$RepProdLimpieza->update($item['id'], $data);
					}

					$operarios = $this->request->getPost('operarios');

					if (!empty($operarios['id']) && !empty($operarios['puesto']) && !empty($operarios['record'])) {
							$ids = $operarios['id'];
							$puestos = $operarios['puesto'];
							$records = $operarios['record'];

							foreach ($ids as $index => $id) {
									$recordId = $records[$index];
									$operario = $User->find($id);

									if (!$operario) {
											continue;
									}

									$data = [
											'reporteId'  => $reporteId,
											'personalId' => $id,
											'puesto'     => $puestos[$index],
									];

									if ($recordId) {
											$RepProdPersonal->update($recordId, $data);
									} else {
											$RepProdPersonal->insert($data);
									}
							}
					}


					$incidenciaIds = $this->request->getPost('incidencia_id'); 
					$incidenteRecords = $this->request->getPost('incidente_record');
					$horaInicio = $this->request->getPost('hora_inicio');      
					$minutosInicio = $this->request->getPost('minutos_inicio'); 
					$horaFin = $this->request->getPost('hora_fin');              
					$tiempoParo = $this->request->getPost('tiempo_paro'); 

					if (!empty($incidenciaIds) && !empty($tiempoParo)) {
							foreach ($incidenciaIds as $index => $incidId) {
									$incidencia = $RepInc->find($incidId);

									if (!$incidencia) {
											continue;
									}

									$recordId = $incidenteRecords[$index];

									$horaInicioFormatted = str_pad($horaInicio[$index], 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutosInicio[$index], 2, '0', STR_PAD_LEFT);

									$data = [
											'reporteId'    => $reporteId,
											'incidId'      => $incidId,
											'hora_inicio'  => $horaInicioFormatted, 
											'hora_fin'     => $horaFin[$index],   
											'tiempo_paro'  => $tiempoParo[$index],
									];

									if ($recordId) {
											$RepProdTiempo->update($recordId, $data);
									} else {
											$RepProdTiempo->insert($data);
									}
							}
					}

					if ($updated) {
							// If no errors, respond with a redirect URL
							return $this->response->setJSON([
									'success' => true,
									'reporteId' => $updated,
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

    public function registro_diario($num_orden = null)
    {
			if ($this->request->getMethod() === 'GET')
			{
				// if(!$num_orden) {
				// 	return redirect()->to('produccion/ordenes_lista');
				// }

				$OrdenFab = new OrdenFabModel();

				$Proc = new ProductosProcModel;
				$Inc = new ReporteIncModel;
				$Desv = new ReporteDesvModel;

				$Formato = new InspeccionFormatoModel;
				$Section = new InspeccionSectionModel;

				$RepInsp = new ReporteInspecModel;
				$RepLimp = new ReporteLimpModel;

				$RepProd = new ReportesProdModel;

				$formato = $Formato->where('slug', 'reporte-produccion')->first();

				$data['inspId'] = "";
				$data['formato'] = $formato;

				$data['ordenfab'] = $OrdenFab->where('num_orden', $num_orden)->first();

				$data['acumulado'] = $RepProd->getAcumulado($data['ordenfab']['id']);

				$data['section_titles'] = $Section->getTitlesByFormatId(2);

				foreach ($data['section_titles'] as $title) {
					$titles[$title['section_number']] = $title['titulo'];
				}

				$data['secciones'] = $titles;

				$data['title'] = 'REGISTRO DIARIO PRODUCCION';
				$data['title_group'] = 'Producción';

				$data['procesos'] = $Proc->findAll();
				$data['incidencias'] = $Inc->findAll();
				$data['desviaciones'] = $Desv->findAll();
				$data['inspeccion'] = json_encode($RepInsp->findAll());
				$data['limpieza'] = json_encode($RepLimp->findAll());
				
				$data['operarios'] = $this->user->getAllPersonal();

				$data['lider_pro'] = $this->user->where('rol_id', 8)->get()->getResultArray();
				$data['turnos'] = $this->turnos->getTurnosWithPlanta();

				// echo "<pre>";
				// print_r($data);
				// exit;

				return view('produccion/registro_diario', $data);
			}

			if ($this->request->getMethod() === 'POST')
			{

					// echo "<pre>";
					// print_r($_POST);
					// print_r([
					// 	$this->request->getPost('operarios'),
						// $this->request->getPost('incidencia_id')
					// ]);

					// exit;

					$RepProd = new ReportesProdModel();

					$formatoId = 2;

					$reporte_data['userId'] = $this->session->get('user')['id'];
					$reporte_data['formatoId'] = $formatoId;

					$fields = [
							'ordenId',
							'turnoId',
							'produccionId',
							'firma_produccion',
							'fecha_firma_produccion',
							'cajas_turno',
							'linea',
							'piezas_producidas',
							'muestras',
							'piezas_acumuladas',
							'batch_inicial',
							'batch_final',
							'cantidad_mezcla',
							'lote_mezcla',
							// 'peso_tm',
							// 'peso_tv',   modified 09-10-2025
							'colectiva',
							'status_fabricacion',
							'observacion_produc',
							'observacion_reporte',
							'hora_inicio_registro',
							'hora_fin_registro'
					];

					foreach ($fields as $field) {
						$reporte_data[$field] = $this->request->getPost($field);
					}

					$rep_prod_id = $RepProd->insert($reporte_data);

					$RepProdLimpieza = new RepProdLimpiezaModel();
					$RepProdTiempo = new RepProdTiempoModel();
					$RepProdPersonal = new RepProdPersonalModel();
					$RepProdInsp = new RepProdInspModel();

					$componentes = $this->request->getPost('componentes');

					foreach ($componentes as $componente) {
							$data = [
									'reporteId' => $rep_prod_id, 
									'itemId'    => $componente['id'],
									'cumple'    => $componente['cumple'],
									'merma'     => $componente['merma'],
									'unidad'    => $componente['unidad'],
							];

							$RepProdInsp->insert($data);
					}

					$limpieza = $this->request->getPost('limpieza');

					foreach ($limpieza as $limp) {
							$data = [
									'reporteId' => $rep_prod_id, 
									'limpId'    => $limp['id'],
									'cumple'    => $limp['cumple'],
							];

							$RepProdLimpieza->insert($data);
					}

					$incidenciaIds = $this->request->getPost('incidencia_id');  
					$horaInicio = $this->request->getPost('hora_inicio');        
					$minutosInicio = $this->request->getPost('minutos_inicio');  
					$horaFin = $this->request->getPost('hora_fin');              
					$tiempoParo = $this->request->getPost('tiempo_paro');        
					
					if (!empty($incidenciaIds[0]) && !empty($tiempoParo[0])) {
						foreach ($incidenciaIds as $index => $incidId) {
								$horaInicioFormatted = str_pad($horaInicio[$index], 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutosInicio[$index], 2, '0', STR_PAD_LEFT);

								$data = [
										'reporteId'    => $rep_prod_id,
										'incidId'      => $incidId,
										'hora_inicio'  => $horaInicioFormatted,
										'hora_fin'     => $horaFin[$index],    
										'tiempo_paro'  => $tiempoParo[$index], 
								];

								$RepProdTiempo->insert($data);
						}
					}

					$operarios = $this->request->getPost('operarios');

					$operariosIds = $operarios['id'];
					$puestos = $operarios['puesto'];

					if (!empty($operariosIds[0]) && !empty($puestos[0])) {	
						foreach ($operariosIds as $index => $id) {
								$data = [
										'reporteId' => $rep_prod_id,
										'personalId' => $id,
										'puesto'      => $puestos[$index],
								];

								$RepProdPersonal->insert($data);
						}
					}

					if ($rep_prod_id) {
							return $this->response->setJSON([
									'success' => true,
									'reporteId' => $rep_prod_id,
									'redirect' => '/success-page'
							]);

							// $this->session->setFlashdata('msg', 'Creado Correctamente');
							// return redirect()->to('/maquinaria');
					} else {
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

    public function registro_diario1()
    {
			if ($this->request->getMethod() === 'GET')
			{
				$Proc = new ProductosProcModel;
				$Inc = new ReporteIncModel;
				$Desv = new ReporteDesvModel;
				$Desv = new ReporteDesvModel;
				

				$data['title'] = 'REGISTRO DIARIO PRODUCCION';
				$data['title_group'] = 'Manufactura GIBANIBB';

			
				// $data['procesos'] = $maq->orderBy('id', 'DESC')->findAll();
				$data['procesos'] = $Proc->findAll();
				$data['incidencias'] = $Inc->findAll();
				$data['desviaciones'] = $Desv->findAll();
				$data['turnos'] = $this->turnos->getTurnosWithPlanta();

				
				$data['operarios'] = $this->user->getAllPersonal();
				// $data['operarios'] = $this->user->where('rol_id', 11)->get()->getResultArray();
				$data['lider_pro'] = $this->user->where('rol_id', 8)->get()->getResultArray();

				// echo "<pre>";
				// print_r($data);
				// exit;

				return view('produccion/registro_diario1', $data);
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
							// 'turno'                => $this->request->getPost('turno'),
							'turnoId'                => $this->request->getPost('turnoId'),
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

		public function get_merma_by_date(string $date = null)
    {
        if ($this->request->getMethod() === 'GET')
        {
						$OrdenFab = new OrdenFabModel();

						$mermas = $OrdenFab->getMermaByDate($date);

						return $this->response->setJSON($mermas);

            // if (empty($validationErrors)) {
            //     return $this->response->setJSON([
            //         'success' => true,
            //         'redirect' => '/success-page'
            //     ]);
            // } else {
            //     return $this->response->setJSON([
            //         'success' => false,
            //         'errors' => $validationErrors
            //     ]);
            // }
        }
		}

		public function reporte_merma()
    {
			if ($this->request->getMethod() === 'GET')
			{
					$data['title'] = 'Reporte Merma';
					$data['title_group'] = 'Producción';

					return view('produccion/reporte_merma', $data);
			}

			// if ($this->request->getMethod() === 'GET')
			// {
			// 	$OrdenFab = new OrdenFabModel;

			// 	$data['title'] = 'Reporte Merma';

			// 	$data['turnos'] = $this->turnos->getTurnosWithPlanta();
			// 	$data['ordenes'] = array_column($OrdenFab->select('num_orden')->get()->getResultArray(), 'num_orden');

			// 	return view('produccion/reporte_merma', $data);
			// }
    }

		public function reporte_dashboard()
    {
			if ($this->request->getMethod() === 'GET')
			{
				$OrdenFab = new OrdenFabModel;

				$data['title'] = 'Producción Dashboard';

				$data['turnos'] = $this->turnos->getTurnosWithPlanta();
				$data['ordenes'] = array_column($OrdenFab->select('num_orden')->get()->getResultArray(), 'num_orden');

				return view('produccion/reporte_dashboard', $data);
			}
    }

		public function reporte_ordenfab($turnoId = null, $date = null)
    {
			if ($this->request->getMethod() === 'GET')
			{

				if($turnoId && $date) {
					$OrdenFab = new OrdenFabModel();
					$reporte = $OrdenFab->getByTurnoIdDate($turnoId, $date);

					return $this->response->setJSON($reporte);
				}

				$data['title'] = 'Reporte Ordenes de Fabricación';
				$data['turnos'] = $this->turnos->getTurnosWithPlanta();
				// echo "<pre>";
				// print_r($data);
				// exit;


				return view('produccion/reporte_ordenfab', $data);
			}
			
			if ($this->request->getMethod() === 'POST')
			{

				if ($response) {
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

		public function reporte_meta($date = null)
    {
			if ($this->request->getMethod() === 'GET')
			{
				if($date) {
					$OrdenFab = new OrdenFabModel();
					// $reporte_date = $OrdenFab->getReporteByDate($date);
					$reporte_date = $OrdenFab->getDailyMeta($date);

					return $this->response->setJSON($reporte_date);
				}

				$data['title'] = 'Reporte Meta Diaria';

				// echo "<pre>";
				// print_r($data);
				// exit;

				return view('produccion/reporte_meta', $data);
			}

			if ($this->request->getMethod() === 'POST')
			{

				// 				echo "<pre>";
				// print_r($_POST);
				// exit;

				$RepProdMeta = new RepProdMetaModel;

				$rows = $this->request->getPost('rows');

				try {
						foreach ($rows as $row) {
								$exists = $RepProdMeta->where('ordenId', $row['ordenId'])
																			->where('DATE(fecha)', $row['fecha'])
																			->get()
																			->getRowArray();

								$data = [
										'porcentaje_avanzado' => $row['porcentaje_avanzado'],
										'meta_dia'            => $row['meta_dia'],
										'total_producido'     => $row['total_producido'],
										'cumplimiento'        => $row['cumplimiento'],
										'fecha'               => $row['fecha'],
								];

								if ($exists) {
										// ✅ use update($id, $data) instead of where()->update()
										$success = $RepProdMeta->update($exists['id'], $data);
								} else {
										$success = $RepProdMeta->insert(array_merge($data, [
												'ordenId' => $row['ordenId'],
										]));
								}

								// handle real errors
								if ($success === false && !empty($RepProdMeta->errors())) {
										return $this->response->setJSON([
												'success' => false,
												'errors'  => $RepProdMeta->errors(),
										]);
								}
						}

						return $this->response->setJSON(['success' => true]);

				} catch (\Exception $e) {
						return $this->response->setJSON([
								'success' => false,
								'message' => $e->getMessage(),
						]);
				}


			}
    }

		public function obs_reporte($reporteId = null)
    {
			if ($this->request->getMethod() === 'GET')
			{
				$RepProd = new ReportesProdModel();

				if($reporteId) {
					return $this->response->setJSON($RepProd->getObsReporte($reporteId));
				}

				return $this->response->setJSON($RepProd->getAllInventario());
			}
			
			if ($this->request->getMethod() === 'POST')
			{
				// echo "<pre>";
				// print_r($_POST);
				// exit;

				$RepProd = new ReportesProdModel();

				$repId = $this->request->getPost('repId');

				$data_rep = [
					'obs_reporte' => $this->request->getPost('obs_reporte'),
				];

				$response = $RepProd->update($repId, $data_rep);

				if ($response) {
						return $this->response->setJSON([
								'success' => true,
								'redirect' => '/success-page'
						]);
				} else {
						return $this->response->setJSON([
								'success' => false,
								'errors' => 'fail to update'
						]);
				}
			}
    }

		public function grafico()
    {
				$data['title'] = 'Producción Dashboard';

				// echo "<pre>";
				// print_r($data);
				// exit;

				return view('produccion/grafico', $data);
    }

		public function personal()
    {
			if ($this->request->getMethod() === 'GET')
			{

				$data['title'] = 'Lista Operarios';

				// echo "<pre>";
				// print_r($data);
				// exit;

				return view('produccion/personal', $data);
			}

			if ($this->request->getMethod() === 'POST')
			{

				// echo "<pre>";
				// print_r($_POST);
				// exit;


				$UserOp = new UserOperarioModel();
				$userId = $this->request->getPost('id');
				
				$empleadoId = $this->request->getPost('empleadoId');
				
				if (!empty($empleadoId)) {
					$existingEmpleado = $UserOp
						->where('empleadoId', $empleadoId);
				
					if (!empty($userId)) {
						$existingEmpleado = $existingEmpleado->where('userId !=', $userId);
					}
				
					$existingEmpleado = $existingEmpleado->first();
				
					if ($existingEmpleado) {
						return $this->response->setJSON([
							'success' => false,
							'errors' => [
								'empleadoId' => 'Error: Id de empleado ya existe.'
							]
						]);
					}
				}

				$data_user = [
					'name'      => $this->request->getPost('name'),
					'last_name' => $this->request->getPost('last_name'),
				];

				if (empty($userId)) {
					$data_user['rol_id'] = 11;
					$userId = $this->user->insert($data_user);
				} else {
					$this->user->update($userId, $data_user);
				}


				$data_op = [
						'empleadoId' => $this->request->getPost('empleadoId'),
						'turno' => $this->request->getPost('turno'),
						'puesto' => $this->request->getPost('puesto'),
				];

				$row_op = $UserOp->where('userId', $userId)->first();

				if(empty($row_op)) {
					$data_op['userId'] = $userId;
					$response = $UserOp->insert($data_op);
				} else {
					$response = $UserOp->update($row_op['id'], $data_op);
				}


				if ($response) {
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

		public function personal_old()
    {
			if ($this->request->getMethod() === 'GET')
			{

				$data['title'] = 'Lista Operarios';

				// echo "<pre>";
				// print_r($data);
				// exit;

				return view('produccion/personal_old', $data);
			}

			if ($this->request->getMethod() === 'POST')
			{

				// echo "<pre>";
				// print_r($_POST);
				// exit;


				$UserOp = new UserOperarioModel();
				$userId = $this->request->getPost('id');
				
				$empleadoId = $this->request->getPost('empleadoId');
				
				if (!empty($empleadoId)) {
					$existingEmpleado = $UserOp
						->where('empleadoId', $empleadoId);
				
					if (!empty($userId)) {
						$existingEmpleado = $existingEmpleado->where('userId !=', $userId);
					}
				
					$existingEmpleado = $existingEmpleado->first();
				
					if ($existingEmpleado) {
						return $this->response->setJSON([
							'success' => false,
							'errors' => [
								'empleadoId' => 'Error: Id de empleado ya existe.'
							]
						]);
					}
				}

				$data_user = [
					'name'      => $this->request->getPost('name'),
					'last_name' => $this->request->getPost('last_name'),
				];

				if (empty($userId)) {
					$data_user['rol_id'] = 11;
					$userId = $this->user->insert($data_user);
				} else {
					$this->user->update($userId, $data_user);
				}


				$data_op = [
						'empleadoId' => $this->request->getPost('empleadoId'),
						'turno' => $this->request->getPost('turno'),
						'puesto' => $this->request->getPost('puesto'),
				];

				$row_op = $UserOp->where('userId', $userId)->first();

				if(empty($row_op)) {
					$data_op['userId'] = $userId;
					$response = $UserOp->insert($data_op);
				} else {
					$response = $UserOp->update($row_op['id'], $data_op);
				}


				if ($response) {
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


		public function all_personal($id = null)
    {
			if ($this->request->getMethod() === 'GET')
			{
				if($id) {
				return $this->response->setJSON($this->user->getAllPersonal($id));
				}

				return $this->response->setJSON($this->user->getAllPersonal());
			}
    }

		public function all_procesos($id = null)
    {
			if ($this->request->getMethod() === 'GET')
			{
				$Proc = new ProductosProcModel();

				if($id) {
				return $this->response->setJSON($Proc-> getById($id));
				}

				return $this->response->setJSON($Proc->orderBy('id', 'DESC')->findAll());
			}
    }


    public function search_lista_ordenes()
    {
			if ($this->request->getMethod() === 'POST')
			{
        // $searchCriteria = $this->request->getJSON(true); 
        
        // echo "<pre>";
        // print_r($_POST);
        // exit;

				$OrdenFab = new OrdenFabModel();

        $searchCriteria = $this->request->getPost();
        $result = $OrdenFab->getAll($searchCriteria);

        return $this->response->setJSON($result);
    	}
    }

		public function all_ordenes($id = null)
    {
			if ($this->request->getMethod() === 'GET')
			{
				$OrdenFab = new OrdenFabModel();

				if($id) {
				return $this->response->setJSON($OrdenFab->getById($id));
				}

				// return $this->response->setJSON($OrdenFab->orderBy('id', 'DESC')->findAll());
				return $this->response->setJSON($OrdenFab->getAll());
			}
    }

		public function all_productos($id = null)
    {
			if ($this->request->getMethod() === 'GET')
			{
				$Prod = new ProductosModel();

				if($id) {
				return $this->response->setJSON($Prod-> getById($id));
				}

				return $this->response->setJSON($Prod->orderBy('id', 'DESC')->findAll());
			}
    }


		public function procesos()
    {
			if ($this->request->getMethod() === 'GET')
			{

				$data['title'] = 'Lista Procesos';

				// echo "<pre>";
				// print_r($data);
				// exit;

				return view('produccion/procesos', $data);
			}

			if ($this->request->getMethod() === 'POST')
			{

				// echo "<pre>";
				// print_r($_POST);
				// exit;

				$Proc = new ProductosProcModel();

				$data_proc = [
						'descripcion' => $this->request->getPost('descripcion'),
						'planta' => $this->request->getPost('planta'),
				];

				if(empty($this->request->getPost('id'))) {
					$procId = $Proc->insert($data_proc);
				} else {
					$procId = $this->request->getPost('id');
					$Proc->update($procId, $data_proc);
				}

				if ($procId) {
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

		public function productos()
    {
			if ($this->request->getMethod() === 'GET')
			{
				$Prod = new ProductosModel();

				$data['title'] = 'Lista Productos';
				$data['lineas'] = $Prod->select('linea')->distinct()->findAll();

				// echo "<pre>";
				// print_r($data);
				// exit;

				return view('produccion/productos', $data);
			}

			if ($this->request->getMethod() === 'POST')
			{

				// echo "<pre>";
				// print_r($_POST);
				// exit;

				$Prod = new ProductosModel();

				$fields = [
					'codigo',
					'descripcion',
					'linea',
					// 'unidad',
					// 'caja',
					'peso_volumen',
					'unidad_medida',
					'mesofilos',
					'coliformes',
					'coli',
					'hongos_levaduras',
					'color',
					'sabor',
					'olor',
					'descripcion_visual',
					'p_especifico_min',
					'p_especifico_max',
					'humedad',
					'brix_min',
					'brix_max',
					'ph_min',
					'ph_max',
					'acidez_min',
					'acidez_max',
					'densidad_min',
					'densidad_max',	
					'tiempo_desintegracion',
					'mf',
					'volumen_liquido',
					'volumen_polvo',
					'desintegracion_capsula',
					'densidad_capsula',
					'humedad_capsula',
					'contenido_capsula',
				];

				foreach ($fields as $field) {
						$value = $this->request->getPost($field);
						$data_prod[$field] = empty($value) ? "N.A." : $value;
				}

				if(empty($this->request->getPost('id'))) {
					$prodId = $Prod->insert($data_prod);
				} else {
					$prodId = $this->request->getPost('id');
					$Prod->update($prodId, $data_prod);
				}

				if ($prodId) {
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

}