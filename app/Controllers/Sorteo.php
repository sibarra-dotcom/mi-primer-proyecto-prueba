<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\UserOperarioModel;

use App\Models\SorteoProdModel;
use App\Models\SorteoInvModel;
use App\Models\SorteoEntregaModel;
use App\Models\SorteoPeriodoModel;

class Sorteo extends BaseController
{
    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();
    }

		public function inventario()
    {
        if ($this->request->getMethod() === 'GET')
        {
        		$SortPeriod = new SorteoPeriodoModel();
						$periodo_id = $SortPeriod->getCurrentPeriodId();
        
            $data['title_group'] = 'Inventario GIBB NUTRITION (Tienda Fisica)';
            $data['title'] = 'Periodo';
						$data['periodo'] = $SortPeriod->find($periodo_id);
						$data['periodos'] = $SortPeriod->findAll();

            return view('sorteo/inventario', $data);
        }

        if ($this->request->getMethod() === 'POST')
        {
							// echo "<pre>";
							// print_r($_FILES);
							// print_r($_POST);
							// exit;

            $SortPro = new SorteoProdModel;
            $SortInv = new SorteoInvModel;
						$SortPeriod = new SorteoPeriodoModel();

						if(empty($this->request->getPost('action'))) {
							$rules = [
									'codigo' => 'required|max_length[50]',
									'color' => 'max_length[50]',
							];

							$array_post = $this->request->getPost();

							if ($this->validation->setRules($rules)->run($array_post)) {
								// insert or update

								// $model->insert($data_prod);
							} else {
									return $this->response->setJSON([
											'success' => false,
											'message' => $this->validation->getErrors(),
									]);
							}
						}

            $data_prod = [
                'nombre' => $this->request->getPost('nombre'),
                'lote' => $this->request->getPost('lote'),
            ];

						if(!empty($this->request->getPost('codigo'))) {
							$data_prod['codigo'] = $this->request->getPost('codigo');
						}

						if(!empty($this->request->getPost('descripcion'))) {
							$data_prod['descripcion'] = $this->request->getPost('descripcion');
						}

						if(!empty($this->request->getPost('color'))) {
							$data_prod['color'] = $this->request->getPost('color');
						} 
						
						if(!empty($this->request->getPost('fecha_ingreso'))) {
							$data_prod['fecha_ingreso'] = $this->request->getPost('fecha_ingreso');
						}

						if(!empty($this->request->getPost('fecha_vencimiento'))) {
							$data_prod['fecha_vencimiento'] = $this->request->getPost('fecha_vencimiento');
						}

						if(!empty($this->request->getPost('id'))) {
							$producto_id = $this->request->getPost('id');
							$SortPro->update($producto_id, $data_prod);
						} else {
							$producto_id = $SortPro->insert($data_prod);
						}

            $data_inv = [
                'productoId' => $producto_id,
                'stock' => $this->request->getPost('stock'),
								'periodoId' => $this->request->getPost('periodoId'),
            ];

						if(!empty($this->request->getPost('inv_id'))) {
							$inv_id = $this->request->getPost('inv_id');
							$SortInv->update($inv_id, $data_inv);
						} else {
							$inv_id = $SortInv->insert($data_inv);
						}

						$targetDir = WRITEPATH . 'storage/sorteo_invent/' . $producto_id;


            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $files = $this->request->getFiles();

            $savedFilePaths = []; 
            $validationErrors = [];

            if ( !empty($files) && $files['archivo'][0]->isValid() ) {

                foreach ($files['archivo'] as $file) { 

                    $allowedMimeTypes = [
												'image/avif',
                        'image/png',
                        'image/jpeg',
                        'image/jpg',
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

                        // $originalFileName = $file->getClientName();
                        // $file->move($targetDir, $originalFileName);

												// $data_p = [
												// 	'imagen' => $producto_id . DIRECTORY_SEPARATOR . $originalFileName,
												// ];

												// $SortPro->update($producto_id, $data_p);


												// Get the original file name
												$originalFileName = $file->getClientName();

												// Define the full path where the original file will be stored
												$originalFilePath = $targetDir . DIRECTORY_SEPARATOR . $originalFileName;

												// Move the original file to the target directory
												$file->move($targetDir, $originalFileName);

												// Extract the base file name without the extension
												$fileBaseName = pathinfo($originalFileName, PATHINFO_FILENAME);

												// Define the converted file path (keep the original name but change the extension to .avif)
												$convertedFilePath = $targetDir . DIRECTORY_SEPARATOR . $fileBaseName . '.avif';

												// Convert the image to .avif using ImageMagick (ensure ImageMagick is installed)
												
												// exec("convert $originalFilePath $convertedFilePath");

													$quality = 90;
													exec("convert " . escapeshellarg($originalFilePath) . " -quality {$quality} " . escapeshellarg($convertedFilePath));

												// After conversion, update the database with the new file path (use the original name with .avif extension)
												$data_p = [
														'imagen' => $producto_id . DIRECTORY_SEPARATOR . $fileBaseName . '.avif',
												];

												// Update the database
												$SortPro->update($producto_id, $data_p);

												// Optional: Remove the original file after conversion (if you don't need it anymore)
												unlink($originalFilePath);


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
                return $this->response->setJSON([
                    'success' => true,
                    'redirect' => '/success-page',
										'periodoId' => $this->request->getPost('periodoId')
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $validationErrors
                ]);
            }
        }
		}

		public function lista()
    {
        if ($this->request->getMethod() === 'GET')
        {
            $data['title_group'] = 'Sorteo';
            $data['title'] = 'Lista de Premios Entregado';

            return view('sorteo/lista', $data);
        }
		}

		public function ruleta()
    {
        if ($this->request->getMethod() === 'GET')
        {
						if(!$this->session->get('user_sorteo')) {
							return redirect()->to('sorteo');
						}

				    $Prod = new SorteoProdModel();
						$SortPeriod = new SorteoPeriodoModel();

						$data['productos'] = json_encode($Prod->getProductsByPeriod($SortPeriod->getCurrentPeriodId()));
            $data['title_group'] = 'Tu turno de girar ha llegado';
            $data['title'] = 'Sorteo Gibanibb';

            return view('sorteo/ruleta', $data);
        }
				
				if ($this->request->getMethod() === 'POST')
				{
						$Entrega     = new SorteoEntregaModel();
						$Inventario  = new SorteoInvModel();
						$SortPeriod  = new SorteoPeriodoModel();

						$userId     = $this->request->getPost('userId');
						$productoId = $this->request->getPost('productoId');
						$periodoId  = $SortPeriod->getCurrentPeriodId();

						if (!$userId || !$productoId || !$periodoId) {
								return $this->response->setStatusCode(400)->setJSON([
										'success' => false,
										'message' => 'Datos incompletos'
								]);
						}

						// 1️⃣ Insert entrega
						$Entrega->insert([
								'userId'     => $userId,
								'productoId' => $productoId,
						]);

						// 2️⃣ Get inventory row for product + period
						$inventario = $Inventario
								->where('productoId', $productoId)
								->where('periodoId', $periodoId)
								->where('activo', 1)
								->first();

						if (!$inventario) {
								// No inventory or already inactive
								$this->session->remove('user_sorteo');

								return $this->response->setJSON([
										'success' => true,
										'message' => 'Entrega registrada (sin inventario activo)'
								]);
						}

						// 3️⃣ Count entregas for this product in current period
						$totalEntregas = $Entrega
								->select('COUNT(*) as total')
								->join(
										'sorteo_inventario si',
										'si.productoId = sorteo_entregas.productoId',
										'inner'
								)
								->where('sorteo_entregas.productoId', $productoId)
								->where('si.periodoId', $periodoId)
								->get()
								->getRow()
								->total;

						// 4️⃣ Check stock
						if (($inventario['stock'] - $totalEntregas) < 1) {
								$Inventario->update($inventario['id'], [
										'activo' => 0
								]);
						}

						// 5️⃣ Clear session and return
						$this->session->remove('user_sorteo');

						return $this->response->setJSON([
								'success' => true,
								'message' => 'Entrega registrada correctamente'
						]);
				}


				// if ($this->request->getMethod() === 'POST')
        // {
        //    	$Entrega = new SorteoEntregaModel;

        //     $data = [
        //         'userId' => $this->request->getPost('userId'),
        //         'productoId' => $this->request->getPost('productoId'),
        //     ];

				// 		$inserted = $Entrega->insert($data);
		
				// 		if ($inserted) {
				// 				$this->session->remove('user_sorteo');

				// 				return $this->response->setJSON([
				// 					'success' => true,
				// 					'message' => 'user found.'
				// 				]);
				// 		}
				// }
		}

		public function premio($product_code)
    {
        if ($this->request->getMethod() === 'GET')
        {
						$Prod = new SorteoProdModel();
						$SortPeriod = new SorteoPeriodoModel();
						$data['productos'] = json_encode($Prod->getProductsByPeriod($SortPeriod->getCurrentPeriodId()));

            $data['title_group'] = '¡Felicidades!';
            $data['title'] = '¡Has Ganado!';
            $data['code'] = $product_code;

            return view('sorteo/premio', $data);
        }
		}

		public function activar_producto()
    {
        if ($this->request->getMethod() === 'POST')
        {
						$SortInv = new SorteoInvModel;

						$inventarioId = $this->request->getPost('inventarioId');

						$data_inv = [
              'activo' => $this->request->getPost('activo'),
            ];

						$updated = $SortInv->update($inventarioId, $data_inv);

						if ($updated) {
							return $this->response->setJSON([
								'success' => true,
								'message' => 'user found.'
							]);
						
						} else {
							return $this->response->setJSON([
								'success' => false,
								'message' => 'Usuario ya se registró en el sorteo.'
							]);
						}

        }
		}


		public function lista_entregado()
    {
        if ($this->request->getMethod() === 'GET')
        {

				    $Entrega = new SorteoEntregaModel();

						$lista = $Entrega->getLista();

						return $this->response->setJSON($lista);
        }
		}

		public function lista_inventario($periodoId = null)
    {
        if ($this->request->getMethod() === 'GET')
        {

				    $Invent = new SorteoInvModel();

						$lista = $Invent->getLista($periodoId);

						return $this->response->setJSON($lista);
        }
		}

		public function all_inventario($productId = null)
    {
			if ($this->request->getMethod() === 'GET')
			{
				$Invent = new SorteoInvModel();

				if($productId) {
				return $this->response->setJSON($Invent->getByProductId($productId));
				}

				return $this->response->setJSON($Invent->getAllInventario());
			}
    }


    public function index()
    {
        if ($this->request->getMethod() === 'GET')
        {
            $data['title_group'] = 'Marketing';
            $data['title'] = 'Sorteo Gibanibb';

            return view('sorteo/index', $data);
        }

        if ($this->request->getMethod() === 'POST')
        {
        		$User = new UserModel();
				    $UserOp = new UserOperarioModel();
						$Entrega = new SorteoEntregaModel;

						$pin = $this->request->getPost('pin');

						$user = $User->getByPIN($pin);
						$operario = $UserOp->getByEmpleadoId($pin);

						if (!$user && !$operario) 
						{
								return $this->response->setJSON([
									'success' => false,
									'message' => 'PIN o ID no registrado.'
								]);
						}

						if ($user && !$operario) {
							
								$user_entregado = $Entrega->checkEntregaSorteo($user['user_id']);

								if ($user_entregado) {
									return $this->response->setJSON([
										'success' => false,
										'message' => 'Usuario ya se registró en el sorteo.'
									]);
								}

								$this->session->set('user_sorteo', $user);
								return $this->response->setJSON([
									'success' => true,
									'message' => 'user found.',
									'username' => $user['name'] . ' ' . $user['last_name']
								]);
						} 
		
						if (!$user && $operario) {
								$user_entregado = $Entrega->checkEntregaSorteo($operario['user_id']);

								if ($user_entregado) {
									return $this->response->setJSON([
										'success' => false,
										'message' => 'Usuario ya se registró en el sorteo.'
									]);
								}

								$this->session->set('user_sorteo', $operario);
								return $this->response->setJSON([
									'success' => true,
									'message' => 'operario found.',
									'username' => $operario['name'] . ' ' . $operario['last_name']
								]);
						}
        }
    }
}