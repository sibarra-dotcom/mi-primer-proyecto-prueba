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
use App\Models\CotizArchivo;
use App\Models\Aprobacion;
use App\Models\ProveedorContacto;
use App\Models\MantenimientoModel;
use App\Models\MantComm;
use App\Models\MantAdjunto;

// API ResourceController automatically provides index(), show(), create(), update(), and delete().


class Api extends ResourceController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
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



    public function get_aprobacion()
    {
        $artId = $this->request->getGet('art_id');
        $area = $this->request->getGet('area');

        if (!$artId || !$area) {
            return $this->failValidationErrors('Both art_id and area are required.');
        }

        $aprobacion = new Aprobacion();
        $response = $aprobacion->getAprobacionByArtId($artId, $area);

        if (empty($response)) {
            return $this->respond([
                'message' => "No data found for art_id: {$artId} and area: {$area}.",
						], ResponseInterface::HTTP_BAD_REQUEST);
        }

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

    public function edit_mant()
    {
        // echo "<pre>";
        // print_r($_POST);
        // print_r($_FILES);
        // exit;


        $mant = new MantenimientoModel();
        $mant_adj = new MantAdjunto();

        $mant_id = $this->request->getPost('mant_id');

        $data_det = [
            'estado_ticket' => $this->request->getPost('estado_ticket'),
            'diagnostico' => $this->request->getPost('diagnostico'),
            'reparacion_detalle' => $this->request->getPost('reparacion_detalle'),
            'responsableId' => $this->request->getPost('responsableId'),
            'cambio_pieza' => $this->request->getPost('cambio_pieza'),
            'compra_pieza' => $this->request->getPost('compra_pieza'),
            'requiere_limpieza' => $this->request->getPost('requiere_limpieza'),
            'nota_inventario' => $this->request->getPost('nota_inventario'),
        ];

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
        // print_r($_FILES);
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

        if ( $files['archivo'][0]->isValid() ) {
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

                $maxSizeInMB = 2; // Enter size in MB (2, 3, etc)
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
                        'error' => 'File exceeds maximum size of 2MB'
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
                'message' => $validationErrors,
            ], ResponseInterface::HTTP_BAD_REQUEST);
        } else {
            return $this->respond([
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


    public function search()
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


    public function maq_search()
    {
			
        // $searchCriteria = $this->request->getJSON(true); 
        
        // echo "<pre>";
        // print_r($_POST);
        // exit;

        $searchCriteria = $this->request->getPost();

        $maq = new Maquinaria;
        $results = $maq->search($searchCriteria);

        return $this->response->setJSON($results);
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

				$encargado = $user->where('email', 'mt_jefe@gibanibb.com')->first();

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


    public function get_maq_linea($planta, $linea)
    {
        $maq = new MaquinariaModel();
        $maquinarias = $maq->where('planta', $planta)
                             ->where('linea', $linea)
                             ->findAll();

        return $this->respond($maquinarias);
    }


}
