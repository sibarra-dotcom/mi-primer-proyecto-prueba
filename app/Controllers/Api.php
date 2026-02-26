<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\Proveedor;
use App\Models\Cotizacion;
use App\Models\CotizDetalle;
use App\Models\MaquinariaModel;
use App\Models\ArticuloAdj;
use App\Models\ArticuloComm;
use App\Models\ArticuloCondModel;

use App\Models\CotizArchivo;
use App\Models\Aprobacion;
use App\Models\ProveedorContacto;
use App\Models\MantenimientoModel;

use App\Models\InspeccionModel;
use App\Models\InspeccionFilesModel;

use App\Models\MaquinariaFilesModel;


use App\Models\MantComm;
use App\Models\MantAdjunto;

// API ResourceController automatically provides index(), show(), create(), update(), and delete().

use App\Models\ReportesProdModel;
use App\Models\RepProdInspModel;
use App\Models\RepProdLimpiezaModel;
use App\Models\RepProdPersonalModel;
use App\Models\RepProdTiempoModel;


use App\Models\ReportesModel;
use App\Models\ReportesRegistroModel;
use App\Models\ReportesPersonalModel;
use App\Models\ReporteIncModel;
use App\Models\ReporteDesvModel;
use App\Models\ProductosModel;
use App\Models\ProductosProcModel;


class Api extends ResourceController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

		// public function afiliados($action = null, $userId = null) 
		// {
		// 	if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) 
		// 	{
		// 		if($action == "list" && is_null($userId)) {
		// 			echo json_encode($this->Adm->readAfiliados(3)); exit;
		// 		}
	
		// 		if($action == "edit" && $userId) {
		// 			echo json_encode($this->Adm->get('users', '*', [ 'id' => $userId])); exit;
		// 		}
	
		// 		$data['countries'] = $this->Adm->select('countries', '*');
		// 		$data['documentos'] = $this->Adm->select('tipos_documento_identidad', '*');
		// 		$data['regimen'] = $this->Adm->select('tipos_regimen', '*');
		// 		$this->view('admin/afiliados', view_data($data));
		// 	}		

		// }

					  //       echo "<pre>";
        // print_r($_POST);
        // print_r($_FILES);
        // exit;


		public function search_productos($description)
    {
        $Prod = new ProductosModel();
				$res =  $Prod->like('descripcion', $description)->findAll(5);

        return $this->response->setJSON($res);
    }

		public function productos($action = null, $productId = null)
    {
			if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) 
			{
				$Prod = new ProductosModel;

				if($action == "list" && is_null($productId)) {
					$productos = $Prod->findAll();

					if (!$productos) {
							return $this->failNotFound("productos with ID {$productId} not found.");
					}
	
					return $this->respond($productos, ResponseInterface::HTTP_OK);
				}
	
				if($action == "edit" && $productId) {
					
					$producto = $Prod->where('id', $productId)->first();

					if (!$producto) {
							return $this->failNotFound("producto with ID {$productId} not found.");
					}
	
					return $this->respond($producto, ResponseInterface::HTTP_OK);
				}

			}

    }


		public function all_tickets_pendientes()
    {
        $tickets = new MantenimientoModel();
        $response = $tickets->getAllPendientes();

        return $this->respond($response, ResponseInterface::HTTP_OK);
    }

		public function get_notif()
    {
        $tickets = new MantenimientoModel();
        $response = $tickets->where(['prioridad' => 'ALTA', 'estado_maq' => 'NO FUNCIONAL'])->getAll();

        return $this->respond($response, ResponseInterface::HTTP_OK);
    }

		public function all_tickets()
    {
        $tickets = new MantenimientoModel();
        $response = $tickets->getAll();

        return $this->respond($response, ResponseInterface::HTTP_OK);
    }

		public function get_ticket($id)
    {
				$tickets = new MantenimientoModel();
        $response = $tickets->getTicket($id);

        return $this->respond($response);
    }

		public function get_condiciones($articuloId)
    {
				$Cond = new ArticuloCondModel();
				$response = $Cond->where('articuloId', $articuloId)->findAll();
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }

		public function search_ticket()
    {
			  //       echo "<pre>";
        // print_r($_POST);
        // print_r($_FILES);
        // exit;


        $tickets = new MantenimientoModel();
        $searchCriteria = $this->request->getPost();
        $res = $tickets->search($searchCriteria);

        return $this->response->setJSON($res);
    }

		public function get_user_tickets()
    {
			$tickets = new MantenimientoModel();
			$response = $tickets->getTickets($this->session->get('user')['id']);
			return $this->respond($response, ResponseInterface::HTTP_OK);
    }


    public function all_articles()
    {
        $cotizacion = new Cotizacion();
        $response = $cotizacion->getListaWithAprob(10);

        return $this->respond($response, ResponseInterface::HTTP_OK);
    }

		public function all_proveedores()
    {
				$prov = new Proveedor();
				// findAll(limit, offset)

        $response = $prov->orderBy('id', 'DESC')->findAll();

        return $this->respond($response, ResponseInterface::HTTP_OK);
    }

		public function all_reportes($num_orden = null)
    {	
				$RepProd = new ReportesProdModel();
				$Rep = new ReportesModel();

				if($num_orden) {
						return $this->respond($RepProd->getAllByOrdenFab($num_orden), ResponseInterface::HTTP_OK);
				}

        return $this->respond($Rep->getAll(), ResponseInterface::HTTP_OK);
    }

		public function all_reportes_old()
    {	
				$Rep = new ReportesModel();
        return $this->respond($Rep->getAll(), ResponseInterface::HTTP_OK);
    }

		public function all_reporte($reporteId = null)
    {	

				$RepProdLimpieza = new RepProdLimpiezaModel();
				$RepProdTiempo = new RepProdTiempoModel();
				$RepProdPersonal = new RepProdPersonalModel();
				$RepProdInsp = new RepProdInspModel();


				$data = [
					// 'personal' => $RepProdPersonal->getByReporteId($reporteId),
					// 'limpieza' => $RepProdLimpieza->getByReporteId($reporteId),
					'tiempo' => $RepProdTiempo->getByReporteId($reporteId),
					// 'inspeccion' => $RepProdInsp->getByReporteId($reporteId),

				];
        return $this->respond($data, ResponseInterface::HTTP_OK);
    }

		public function all_inspecciones($formatoId)
    {
				$Inspeccion = new InspeccionModel();
				// findAll(limit, offset)

        $response = $Inspeccion->getAll($formatoId);
        // $response = $Inspeccion->where('formatoId', $formatoId)->orderBy('id', 'DESC')->findAll();

        return $this->respond($response, ResponseInterface::HTTP_OK);
    }

    public function delete_article($art_id = null)
    {
        if (!$art_id) {
            return $this->failValidationErrors("art_id is required.");
        }

        $cotiz_detalle = new CotizDetalle();
        $article = $cotiz_detalle->find($art_id);

        if (!$article) {
            return $this->respond([
                'message' => "Article with ID {$art_id} not found.",
            ], ResponseInterface::HTTP_BAD_REQUEST);
        }

        $cotiz_detalle->delete($art_id);
        return $this->respondDeleted([
            "message" => "Article deleted successfully",
            "art_id" => $art_id
        ]);
    }

    public function delete_proveedor($provId = null)
    {
        if (!$provId) {
            return $this->failValidationErrors("provId is required.");
        }

        $Proveedor = new Proveedor();
        $row = $Proveedor->find($provId);

        if (!$row) {
            return $this->respond([
                'message' => "Proveedor with ID {$provId} not found.",
            ], ResponseInterface::HTTP_BAD_REQUEST);
        }

        $Proveedor->delete($provId);
        return $this->respondDeleted([
            "message" => "Article deleted successfully",
            "provId" => $provId
        ]);
    }



    /**
     * GET: Fetch a single file by ID
     * Example: GET /api/get_file/10
     */
    public function get_file($id = null)
    {
        if (!$id) {
            return $this->failValidationErrors('File ID is required.');
        }

        $file = $this->model->find($id);

        if (!$file) {
            return $this->failNotFound("File with ID {$id} not found.");
        }

        return $this->respond($file, ResponseInterface::HTTP_OK);
    }

    /**
     * PUT/PATCH: Update a comment by ID
     * Example: PUT /api/update_comment/5
     */
    public function update_comment($id = null)
    {
        if (!$id) {
            return $this->failValidationErrors('Comment ID is required.');
        }

        $json = $this->request->getJSON();
        if (!$json) {
            return $this->failValidationErrors('Invalid JSON input.');
        }

        $comment = $this->model->find($id);
        if (!$comment) {

            return $this->respond([
                'message' => "Comment with ID {$id} not found.",
            ], ResponseInterface::HTTP_NO_CONTENT); 

            return $this->failNotFound("Comment with ID {$id} not found.");
        }

        $this->model->update($id, (array) $json);

        return $this->respond([
            'message' => "Comment with ID {$id} updated successfully.",
            'updated_id' => $id
        ], ResponseInterface::HTTP_OK);
    }

   /**
     * PUT: Fully update a record
     * PATCH: Partially update a record
     */
    public function update($id = null)
    {
        $json = $this->request->getJSON();
        if ($json && $this->model->find($id)) {
            $this->model->update($id, (array) $json);
            return $this->respond(['message' => 'Record updated successfully']);
        }
        return $this->failNotFound('Record not found or invalid JSON');
    }



    public function get_aprobacion($artId, $area)
    {
        if (!$artId || !$area) {
            return $this->failValidationErrors('Both art_id and area are required.');
        }

        $aprobacion = new Aprobacion();
        $response = $aprobacion->getAprobacionByArtId($artId, $area);
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }

    public function get_art_ficha($artId)
    {
			  $art_archivo = new ArticuloAdj();

        if (!$artId) {
            return $this->failValidationErrors('art_id required.');
        }

        $aprobacion = new Aprobacion();
        $response = $art_archivo->getFicha($artId);
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }


    public function get_adjuntos()
    {
        $cotizId = $this->request->getGet('cotiz_id');

        if (!$cotizId) {
            return $this->failValidationErrors('cotiz_id is required.');
        }

        $cotiz_archivo = new CotizArchivo;

        $response = $cotiz_archivo->getAdjuntos($cotizId);

        if (empty($response)) {
            return $this->respond([
                'message' => "No data found for cotiz_id: {$cotizId}.",
            ], ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->respond($response, ResponseInterface::HTTP_OK);
    }

    // pasar mas de 2 parametros de busqueda (find)
    // $res = $user->where(['nombre' => $query, 'status' => 'active'])->find();

    public function get_comentarios()
    {
        $artId = $this->request->getGet('art_id');

        if (!$artId) {
            return $this->failValidationErrors('art_id is required.');
        }

        $art_comm = new ArticuloComm;

        return $this->respond([
            'comments' => $art_comm->getCommentsByArtId($artId),
            'num_comments' => $art_comm->countComments($artId),
        ], ResponseInterface::HTTP_OK);
    }


    public function get_articulo_coti()
    {
        $cotiz_id = $this->request->getGet('cotiz_id');
        $art_id = $this->request->getGet('art_id');

        if (!$art_id || !$cotiz_id) {
            return $this->failValidationErrors('Both art_id and cotiz_id are required.');
        }

        $cotizacion = new Cotizacion();
        $response = $cotizacion->searchSingleArt($cotiz_id, $art_id);

        return $this->respond($response, ResponseInterface::HTTP_OK);
    }

		public function add_limpieza_mt()
    {
        // echo "<pre>";
        // print_r($_POST);
        // exit;

        $mant = new MantenimientoModel();

        $mantId = $this->request->getPost('mantId');
        $area = $this->request->getPost('area');

				$data_det = [
					'requiere_limpieza' => $this->request->getPost('requiere_limpieza'),
					"fecha_inicio_$area" => date('Y-m-d H:i:s')
				];

        $response = $mant->update($mantId, $data_det);

        return $this->response->setJSON(['success' => $response, 'mantId' => $mantId]);
		}

    public function edit_mant()
    {
        // echo "<pre>";
        // print_r($_POST);
        // print_r($_FILES);
        // exit;


        $mant = new MantenimientoModel();
        $mant_adj = new MantAdjunto();

        $mant_id = $this->request->getPost('mant_id');
				$estado_ticket = $this->request->getPost('estado_ticket');

        $data_det = [
            'imputable' => $this->request->getPost('imputable'),
            'estado_ticket' => $this->request->getPost('estado_ticket'),
            'diagnostico' => $this->request->getPost('diagnostico'),
            'reparacion_detalle' => $this->request->getPost('reparacion_detalle'),
            'responsableId' => $this->request->getPost('responsableId'),
            'cambio_pieza' => $this->request->getPost('cambio_pieza'),
            'compra_pieza' => $this->request->getPost('compra_pieza'),
            'requiere_limpieza' => $this->request->getPost('requiere_limpieza'),
            'nota_inventario' => $this->request->getPost('nota_inventario'),
        ];


        if ($estado_ticket == "2") {
					$data_fecha_notificacion = [
						"fecha_inicio_liberacion" => date('Y-m-d H:i:s')
					];

					$mant->update($mant_id, $data_fecha_notificacion);
				}


				$fecha_repar = $this->request->getPost('fecha_reparacion');
				$fecha_arr = $this->request->getPost('fecha_arranque');
				$fecha_cie = $this->request->getPost('fecha_cierre');

				$datetime_pattern = '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/';

				if (!empty($fecha_repar) && preg_match($datetime_pattern, $fecha_repar) !== false && $fecha_repar !== "00-00-0000") {
						$data_det['fecha_reparacion'] = $fecha_repar;
				}
				if (!empty($fecha_arr) && preg_match($datetime_pattern, $fecha_arr) !== false && $fecha_arr !== "00-00-0000") {
					$data_det['fecha_arranque'] = $fecha_arr;
				}
				if (!empty($fecha_cie) && preg_match($datetime_pattern, $fecha_cie) !== false && $fecha_cie !== "00-00-0000") {
						$data_det['fecha_cierre'] = $fecha_cie;
				}
				
        $response = $mant->update($mant_id, $data_det);
        // echo "<pre>";
        // print_r($data_det);
        // print_r($response);
        // exit;

        $targetDir = WRITEPATH . 'storage/mant/' . $mant_id;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

				$files = $this->request->getFiles();

				$savedFilePaths = []; 
				$validationErrors = [];

				if ( $files['archivo']->isValid() ) {

						// foreach ($files['archivo'] as $file) { 
							$file = $files['archivo'];

								$allowedMimeTypes = ['image/png', 'image/jpeg', 'image/jpg'];
								$maxSize = 2048 * 1024 * 4; // 8MB in bytes

								if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
										$validationErrors[] = [
												'file' => $file->getName(),
												'error' => 'Invalid file type'
										];
										// continue;
								}

								if ($file->getSize() > $maxSize) {
										$validationErrors[] = [
												'file' => $file->getName(),
												'error' => 'File exceeds maximum size of 8MB'
										];
										// continue;
								}

								if ($file->isValid() && !$file->hasMoved()) {

										$originalFileName = $file->getClientName();
										$file->move($targetDir, $originalFileName);
										
										$data = [
												// 'mantId' => $this->request->getPost('mant_num'),
												// 'archivo' => 'mant' . DIRECTORY_SEPARATOR . $this->request->getPost('mant_num') . DIRECTORY_SEPARATOR . $originalFileName,
												'mantId' => $mant_id,
												'archivo' => 'mant' . DIRECTORY_SEPARATOR . $mant_id . DIRECTORY_SEPARATOR . $originalFileName,
										];

										$mant_adj->insert($data);

								} else {
										$validationErrors[] = [
												'file' => $file->getName(),
												'error' => $file->getErrorString()
										];
										// continue;
								}
						// }
				}

				if (empty($validationErrors)) {

					// $estado_maq = $this->request->getPost('estado_maq');
					// $prioridad = $this->request->getPost('prioridad');
					// $maqId = $this->request->getPost('maqId');

						$this->session->setFlashdata('msg', 'Ticket creado');
						return redirect()->to('/mtickets');
				} else {
						$this->session->setFlashdata('msg_error', 'Ocurrio un Error');
						$this->session->setFlashdata('msg', 'Ocurrio un Error');
						return redirect()->to('/mtickets');
				}
    }


    public function edit_art()
    {
        // echo "<pre>";
        // print_r($_POST);
        // print_r($this->request->getFiles());
        // exit;

        // $art_archivo = new ArticuloAdj();
        $cotiz_archivo = new CotizArchivo();
        $cotizacion = new Cotizacion();
        $cotiz_detalle = new CotizDetalle();

        $art_id = $this->request->getPost('art_id');
        $cotiz_id = $this->request->getPost('cotiz_id');

        $data_det = [
            'nombreDelArticulo' => $this->request->getPost('nombreDelArticulo'),
            'costoPorUnidad' => $this->request->getPost('costoPorUnidad'),
            'divisa' => $this->request->getPost('divisa'),
            'impuesto' => $this->request->getPost('impuesto'),
            'medicion' => $this->request->getPost('medicion'),
            'minimo' => $this->request->getPost('minimo'),
            'importe' => $this->request->getPost('importe'),
            'diasDeEnvio' => $this->request->getPost('diasDeEnvio'),
            'cantidadPer' => $this->request->getPost('cantidadPer'),
            'periodo' => $this->request->getPost('periodo'),
            'tipoDia' => $this->request->getPost('tipoDia'),
        ];

        $response = $cotiz_detalle->update($art_id, $data_det);

        $targetDir = WRITEPATH . 'storage/' . $cotiz_id;

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

                $maxSizeInMB = 4;
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
                        'error' => "File exceeds $maxSizeInMB MB"
                    ];
                    continue;
                }

                if ($file->isValid() && !$file->hasMoved()) {

                    $originalFileName = $file->getClientName();
                    $file->move($targetDir, $originalFileName);
                    
                    // $data_file = [
                    //     'userId'        => $this->session->get('user')['id'],
                    //     'articuloId'    => $art_id,
                    //     'archivo'       => $cotiz_id . DIRECTORY_SEPARATOR . $originalFileName,
                    // ];

                    // $art_archivo->insert($data_file);

                    $data_file = [
                        'cotizacionId' => $cotiz_id,
                        'archivo'      => $cotiz_id . DIRECTORY_SEPARATOR . $originalFileName,
                    ];

                    $cotiz_archivo->insert($data_file);

                } else {
                    $validationErrors[] = [
                        'file' => $file->getName(),
                        'error' => $file->getErrorString()
                    ];
                    continue;
                }
            }
        }


        if (!empty($validationErrors)) {
            return $this->respond([
								'success' => false,
                'message' => $validationErrors[0]['error'] . ' [' . $validationErrors[0]['file'] . ']',
            ], ResponseInterface::HTTP_OK);
        } else {
            return $this->respond([
								'success' => true,
                'message' => "Record updated successfully.",
                'rows' => $response,
            ], ResponseInterface::HTTP_OK);
        }
    }



    public function get_maquina()
    {
        $maq = new MaquinariaModel;
        $query = $this->request->getGet('q');

        if (strlen($query) >= 2) {
            $res = $maq->like('nombre', $query)->findAll(); // Limit to 10 results
            return $this->response->setJSON($res);
        }

        return $this->response->setJSON([]);
    }

		public function get_proveedor_contacto()
    {

        $id = $this->request->getGet('prov_id');

        if (!$id) {
            return $this->failValidationErrors('Proveedor_id is required.');
        }

        $prov = new Proveedor;
        $prov_cont = new ProveedorContacto;

				$proveedor = $prov->where('id', $id)->first();
    
        $contactos = $prov_cont->where('proveedorId', $id)->findAll();

				$response = [
						'proveedor' => $proveedor,
						'contactos' => $contactos
				];

        if (empty($response)) {
            return $this->respond([
                'message' => "No data found for proveedorId: {$id}.",
            ], ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->respond($response, ResponseInterface::HTTP_OK);
    }

    public function get_proveedor()
    {
        $proveedor = new Proveedor();
        $query = $this->request->getGet('q');

        if (strlen($query) >= 3) {
            $res = $proveedor->like('razon_social', $query)->findAll(10); // Limit to 10 results
            return $this->response->setJSON($res);
        }

        return $this->response->setJSON([]);
    }

    public function get_contactos()
    {
        $proveedorContacto = new ProveedorContacto();
        $query = $this->request->getGet('q');

        $res = $proveedorContacto->where('proveedorId', $query)->findAll(10); // Limit to 10 results
        return $this->response->setJSON($res);
    
        return $this->response->setJSON([]);
    }


    public function get_articulo()
    {
        $articulo = new CotizDetalle();
        $query = $this->request->getGet('q');

        if (strlen($query) >= 3) {
            $res = $articulo->like('nombreDelArticulo', $query)->findAll();
            return $this->response->setJSON($res);
        }

        return $this->response->setJSON([]);
    }


    public function search_articulos()
    {
        // $searchCriteria = $this->request->getJSON(true); 
        
        // echo "<pre>";
        // print_r($_POST);
        // exit;
        $cotizacion = new Cotizacion();
        $searchCriteria = $this->request->getPost();
        $res = $cotizacion->search($searchCriteria);

        return $this->response->setJSON($res);
    }


    public function add_aprobacion()
    {
        // echo "<pre>";
        // print_r($_POST);
        // exit;

        $data = [
            'userId' => $this->session->get('user')['id'],
            'articuloId' => $this->request->getPost('art_id'),
            'area' => $this->request->getPost('area'),
            'status' => $this->request->getPost('status'),
            'comentario' => $this->request->getPost('comentario'),
        ];

        $aprobId = $this->request->getPost('aprobId');

        $aprobacion = new Aprobacion();

        if(empty($aprobId)) {
				// echo "<pre>";
        // print_r($_POST);
        // exit;
            $response = $aprobacion->insert($data);

            return $this->respondCreated([
                'message' => 'Record created successfully',
                'id' => $response,
                'area' => $this->request->getPost('area'),
                'art_id' => $this->request->getPost('art_id'),
            ]);

        } else {
            $response = $aprobacion->update($aprobId, $data);

            return $this->respond([
                'message' => "Record updated successfully.",
                'rows' => $response,
                'area' => $this->request->getPost('area'),
                'art_id' => $this->request->getPost('art_id'),
            ], ResponseInterface::HTTP_OK);
        }
    }

		public function add_comment_mt()
    {
        // echo "<pre>";
        // print_r($_POST);
        // exit;

        $mant_comment = new MantComm();

        $data = [
            'userId' => $this->session->get('user')['id'],
            'mantId' => $this->request->getPost('mant_id'),
            'comentario' => $this->request->getPost('comentario'),
        ];

        $res = $mant_comment->insert($data);

        return $this->response->setJSON(['success' => $res, 'mant_id' => $this->request->getPost('mant_id')]);
    }

		public function add_firma_reporte_diario()
    {
        // echo "<pre>";
        // print_r($_POST);
        // exit;

        // $Repor = new ReportesModel();

				// $produccionId = $this->request->getPost('produccionId');
				$field = $this->request->getPost('field');

				$userId = $this->request->getPost('userId') ?? '';
				$area = $this->request->getPost('area') ?? '';


				if($area == 'produccionId') {

					$User = new UserModel();

					$user = $User->where('id', $userId)->first();
	
					if (!$user) {

						return $this->response->setJSON([
							'success' => false, 
							'message' => "PIN incorrecto"
						]);

					} else {

						// if($userId) {
						// 	$data["$area"] = $userId;
						// }
		
						return $this->response->setJSON([
							'success' => true, 
							'produccionId' => $userId,
							'signature' => $user['signature'],
							'fecha_firma' => date('Y-m-d H:i:s'),
							'firma' => "si",
						]);

					}
				}

		}


		public function add_firma_reporte()
    {
        // echo "<pre>";
        // print_r($_POST);
        // exit;

        // $Repor = new ReportesModel();

				// $produccionId = $this->request->getPost('produccionId');
				$field = $this->request->getPost('field');

				$userId = $this->request->getPost('userId') ?? '';
				$area = $this->request->getPost('area') ?? '';


				if($area == 'produccionId') {

					$User = new UserModel();

					$pin = $this->request->getPost('pin');

					$user = $User->getUserByPINId($userId, $pin);
	
					if (!$user) {

						return $this->response->setJSON([
							'success' => false, 
							'message' => "PIN incorrecto"
						]);

					} else {

						// if($userId) {
						// 	$data["$area"] = $userId;
						// }
		
						return $this->response->setJSON([
							'success' => true, 
							'produccionId' => $userId,
							'signature' => $user['signature'],
							'fecha_firma' => date('Y-m-d H:i:s'),
							'firma' => "si",
						]);

					}
				}

		}

		public function add_firma_insp()
    {
        // echo "<pre>";
        // print_r($_POST);
        // exit;

        $Inspeccion = new InspeccionModel();

				$inspeccionId = $this->request->getPost('inspeccionId');
				$field = $this->request->getPost('field');

				$userId = $this->request->getPost('userId') ?? '';
				$area = $this->request->getPost('area') ?? '';

        $data = [
            "$field" => "si",
        ];

				if($area == 'almacenId') {

					$userModel = new UserModel();

					$pin = $this->request->getPost('pin');

					$user = $userModel->getUserByPINId($userId, $pin);
	
					if (!$user) {

						return $this->response->setJSON([
							'success' => false, 
							'message' => "PIN incorrecto"
						]);

					} else {

						$data["fecha_firma_almacen"] = date('Y-m-d H:i:s');

						if($userId) {
							$data["$area"] = $userId;
						}
		
						// echo "<pre>";
						// print_r($data);
						// exit;
		
						$response = $Inspeccion->update($inspeccionId, $data);
		
						return $this->response->setJSON([
							'success' => $response, 
							'inspeccionId' => $inspeccionId,
						]);

					}
				}

				if($area == 'calidadId') {
					$data["fecha_firma_calidad"] = date('Y-m-d H:i:s');

					if($userId) {
						$data["$area"] = $userId;
					}
	
					// echo "<pre>";
					// print_r($data);
					// exit;
	
					$response = $Inspeccion->update($inspeccionId, $data);
	
					return $this->response->setJSON(['success' => $response, 'inspeccionId' => $inspeccionId]);

				}
    }


		public function add_firma_mt()
    {
        // echo "<pre>";
        // print_r($_POST);
        // exit;

        $mant = new MantenimientoModel();

				$mantId = $this->request->getPost('mantId');
				$field = $this->request->getPost('field');

				$userId = $this->request->getPost('userId') ?? '';
				$area = $this->request->getPost('area') ?? '';

        $data = [
            "$field" => "si",
        ];

				if($area == 'limpiezaId') {
					$data["fecha_cierre_limpieza"] = date('Y-m-d H:i:s');
				}

				if($area == 'calidadId') {
					$data["fecha_cierre_liberacion"] = date('Y-m-d H:i:s');
				}


				if($userId) {
					$data["$area"] = $userId;
				}

				// echo "<pre>";
        // print_r($data);
        // exit;

				$response = $mant->update($mantId, $data);

        return $this->response->setJSON(['success' => $response, 'mantId' => $mantId]);
    }

    public function add_comment()
    {
        // echo "<pre>";
        // print_r($_POST);
        // exit;

        $art_comment = new ArticuloComm();

        $data = [
            'userId' => $this->session->get('user')['id'],
            'articuloId' => $this->request->getPost('art_id'),
            'comentario' => $this->request->getPost('comentario'),
        ];

        $res = $art_comment->insert($data);
        $count = $art_comment->countComments($this->request->getPost('art_id'));

        return $this->response->setJSON(['success' => $res, 'num_comments' => $count, 'art_id' => $this->request->getPost('art_id')]);
    }


		public function get_empleados($nombre)
    {
			$users = new UserModel;
			$empleados = $users->like('name', $nombre)->findAll();
			return $this->respond($empleados);
    }


    public function get_lineas($planta)
    {	
				$maq = new MaquinariaModel();
				$lineas = $maq->select('linea')->distinct()->where('planta', $planta)->findAll();

				return $this->respond($lineas);
    }

    public function get_lineas_alt()
    {	
				$lineas = [
					['linea' => 'Línea de Envasado'],
					['linea' => 'Línea de Acondicionado'],
					['linea' => 'Servicios Generales'],
				];

				return $this->respond($lineas);
    }
		

    public function get_comentarios_mt($mantId)
    {
        if (!$mantId) {
            return $this->failValidationErrors('mant_id is required.');
        }

        $mant_comm = new MantComm;

        return $this->respond([
            'comments' => $mant_comm->getCommentsByMantId($mantId),
        ], ResponseInterface::HTTP_OK);
    }

		public function get_adjuntos_mt($mantId)
    {
        if (!$mantId) {
            return $this->failValidationErrors('mant_id is required.');
        }

        $mant_adj = new MantAdjunto;

				$adjuntos = $mant_adj->where('mantId', $mantId)->findAll();

        return $this->respond([
            'adjuntos' => $adjuntos,
        ], ResponseInterface::HTTP_OK);
    }

		public function get_firmas_insp($inspeccionId)
    {
        if (!$inspeccionId) {
            return $this->failValidationErrors('id is required.');
        }

				$user = new UserModel;
				$Inspeccion = new InspeccionModel;

				$inspeccion = $Inspeccion->where('id', $inspeccionId)->first();

				$calidad = $user->where("id", $inspeccion['calidadId'])->first();
				$almacen = $user->where("id", $inspeccion['almacenId'])->first();

        return $this->respond([
						'inspeccion' => $inspeccion,
						'calidad' => $calidad,
						'almacen' => $almacen,
        ], ResponseInterface::HTTP_OK);
    }

		public function delete_files_insp($inspeccionId)
		{
				if (!$inspeccionId) {
						return $this->failValidationErrors('inspeccionId is required.');
				}
		
				$Files = new InspeccionFilesModel();
		
				// Get all matching files
				$files = $Files->where("inspeccionId", $inspeccionId)->findAll();
		
				if (!$files) {
						return $this->respond(['success' => false, 'message' => 'No files found.'], ResponseInterface::HTTP_NOT_FOUND);
				}
		
				foreach ($files as $file) {
						// Optional: Delete the physical file if needed
						// if (!empty($file['file_path']) && file_exists($file['file_path'])) {
						// 		@unlink($file['file_path']);
						// }
		
						// Delete the database record
						$Files->delete($file['id']);
				}
		
				return $this->respond(['success' => true, 'message' => 'Files deleted.'], ResponseInterface::HTTP_OK);
		}


		public function get_files_insp($inspeccionId)
    {
        if (!$inspeccionId) {
            return $this->failValidationErrors('id is required.');
        }

				$Files = new InspeccionFilesModel;

				$files = $Files->where("inspeccionId", $inspeccionId)->findAll();

        return $this->respond($files, ResponseInterface::HTTP_OK);
    }


		public function get_firmas_mt($mantId)
    {
        if (!$mantId) {
            return $this->failValidationErrors('mant_id is required.');
        }

				$user = new UserModel;
				$mant = new MantenimientoModel;

				$ticket = $mant->where('id', $mantId)->first();

				$solicitante = $user->where("CONCAT(name, ' ', last_name) =", [$ticket['solicitante']])->first();

				$responsable = $user->where("id", $ticket['responsableId'])->first();
				$calidad = $user->where("id", $ticket['calidadId'])->first();
				$produccion = $user->where("id", $ticket['produccionId'])->first();
				$limpieza = $user->where("id", $ticket['limpiezaId'])->first();

				// $encargado = $user->where('email', 'mt_jefe@gibanibb.com')->first();
				$encargado = $user->where('rol_id', 7)->first();

        return $this->respond([
						'ticket' => $ticket,
						'solic' => $solicitante,
						'resp' => $responsable,
						'encar' => $encargado,
						'produccion' => $produccion,
						'limpieza' => $limpieza,
						'calidad' => $calidad,
        ], ResponseInterface::HTTP_OK);
    }

		public function search_proveedor()
    {
        $searchCriteria = $this->request->getPost();

        $Prov = new Proveedor;
        $results = $Prov->search($searchCriteria);

        return $this->response->setJSON($results);
    }

		public function search_maquina()
    {
			
        // $searchCriteria = $this->request->getJSON(true); 
        
        // echo "<pre>";
        // print_r($_POST);
        // exit;

        $searchCriteria = $this->request->getPost();

        $maq = new MaquinariaModel;
        $results = $maq->search($searchCriteria);

        return $this->response->setJSON($results);
    }


		public function all_maquinas()
    {
			
        // $searchCriteria = $this->request->getJSON(true); 
        
        // echo "<pre>";
        // print_r($_POST);
        // exit;

				$Maq = new MaquinariaModel;
				$results = $Maq->findAll();

        return $this->response->setJSON($results);
    }

		public function get_maq($maquinariaId)
    {
        $Maq = new MaquinariaModel;
				$res = $Maq->where('id', $maquinariaId)->first(); 
				return $this->response->setJSON($res);
    }

		public function get_maq_files($maquinariaId)
    {
        if (!$maquinariaId) {
            return $this->failValidationErrors('mant_id is required.');
        }

        $Maq_files = new MaquinariaFilesModel;

				$adjuntos = $Maq_files->where('maquinariaId', $maquinariaId)->findAll();

        return $this->respond($adjuntos, ResponseInterface::HTTP_OK);
    }


    public function get_maq_linea($planta, $linea)
    {
        $maq = new MaquinariaModel();
        $maquinarias = $maq->where('planta', $planta)
                             ->where('linea', $linea)
                             ->findAll();

        return $this->respond($maquinarias);
    }


}
