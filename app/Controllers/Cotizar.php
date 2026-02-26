<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\Cotizacion;
use App\Models\CotizArchivo;
use App\Models\CotizDetalle;
use App\Models\ArticuloComm;
use App\Models\ArticuloCondModel;
use App\Models\MantAdjunto;

class Cotizar extends BaseController
{
    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        if ($this->request->getMethod() === 'GET') {

						if (!hasAnyRole(['admin', 'desarrollo', 'calidad', 'cotizador', 'costos'])) {
								// return redirect()->to('apps');
								return redirect()->to(previous_url());
						}

            $cotizacion = new Cotizacion();
            
            $_last = $cotizacion->orderBy('id', 'DESC')->first();

            $data['title'] = 'Formulario de Cotizaciones';
            // $data['user_id'] = $this->session->get('user')['id'];
            $data['cotiz_num'] = $_last ? $_last['id'] + 1 : 1;

            // $this->session->setFlashdata('msg', 'Cotización creada');
            // $this->session->setFlashdata('msg_error', 'Cotización creada');
            
            return view('cotizar/index', $data);
        }

        if ($this->request->getMethod() === 'POST') {

            $cotiz_archivo = new CotizArchivo();
            $cotizacion = new Cotizacion();
            $cotiz_detalle = new CotizDetalle();
            $art_comment = new ArticuloComm();
            $cond_comment = new ArticuloCondModel();

            // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);
            // exit;

            $data_cotiz = [
                'userId' => $this->session->get('user')['id'],
                'proveedorId' => $this->request->getPost('proveedorId'),
                'contactoId' => $this->request->getPost('contactoId'),
                'fecha' => $this->request->getPost('fecha'),
                'origen' => $this->request->getPost('origen'),
                'vigencia' => $this->request->getPost('vigencia'),
                'incoterm' => $this->request->getPost('incoterm'),
            ];

            $cotiz_id = $cotizacion->insert($data_cotiz);
      
            $nombreDelArticulo = $this->request->getPost('nombreDelArticulo');
            $costoPorUnidad = $this->request->getPost('costoPorUnidad');
            $divisa = $this->request->getPost('divisa');
            $impuesto = $this->request->getPost('impuesto');
            $medicion = $this->request->getPost('medicion');
            $minimo = $this->request->getPost('minimo');
            $importe = $this->request->getPost('importe');
            $diasDeEnvio = $this->request->getPost('diasDeEnvio');
            $cantidadPer = $this->request->getPost('cantidadPer');
            $periodo = $this->request->getPost('periodo');
            $tipoDia = $this->request->getPost('tipoDia');
            $comentario = $this->request->getPost('comentario');
            $condicion = $this->request->getPost('condicion');

						$userId = $this->session->get('user')['id'];

            for ($i = 0; $i < count($nombreDelArticulo); $i++) {
                $articuloId = $cotiz_detalle->insert([
                    // 'cotizacionId' => $this->request->getPost('cotiz_num'),
                    'cotizacionId' => $cotiz_id,
                    'nombreDelArticulo' => $nombreDelArticulo[$i],
                    'costoPorUnidad' => $costoPorUnidad[$i],
                    'divisa' => $divisa[$i],
                    'impuesto' => $impuesto[$i],
                    'medicion' => $medicion[$i],
                    'minimo' => $minimo[$i],
                    'importe' => $importe[$i],
                    'diasDeEnvio' => $diasDeEnvio[$i],
                    'cantidadPer' => $cantidadPer[$i],
                    'periodo' => $periodo[$i],
                    'tipoDia' => $tipoDia[$i],
                ]);


                // $data_comment = [
                //     'userId' => $this->session->get('user')['id'],
                //     'articuloId' => $articuloId,
                //     'comentario' => $comentario[$i],
                // ];

                // $rules_comment = [
                //     'comentario' => 'required|trim',
                // ];

                // if ($this->validation->setRules($rules_comment)->run($data_comment)) {
                //     $art_comment->insert($data_comment);
                // } else {
                //     // Handle validation error (optional)
                //     // $validation->getErrors();
                // }

		

                // $art_comment->insert([
                //     'userId' => $this->session->get('user')['id'],
                //     'articuloId' => $articuloId,
                //     'comentario' => !empty(trim($comentario[$i])) ? $comentario[$i] : 'empty',
                // ]);

								// $cond_comment->insert([
								// 		'userId' => $this->session->get('user')['id'],
								// 		'articuloId' => $articuloId,
								// 		'condicion' => !empty(trim($condicion[$i])) ? $condicion[$i] : 'empty',
								// ]);

								if (!empty($comentario[$i]) && trim($comentario[$i]) !== '') {
										$art_comment->insert([
												'userId'     => $userId,
												'articuloId' => $articuloId,
												'comentario' => trim($comentario[$i]),
										]);
								}
								
								if (!empty($condicion[$i]) && trim($condicion[$i]) !== '') {
										$cond_comment->insert([
												'userId'     => $userId,
												'articuloId' => $articuloId,
												'condicion'  => trim($condicion[$i]),
										]);
								}
								
								
            }


            // $data_detalles = [];
            // for ($i = 0; $i < count($nombreDelArticulo); $i++) {
            //     $data_detalles[] = [
            //         'cotizacionId' => $this->request->getPost('cotiz_num'),
            //         'nombreDelArticulo' => $nombreDelArticulo[$i],
            //         'costoPorUnidad' => $costoPorUnidad[$i],
            //         'divisa' => $divisa[$i],
            //         'impuesto' => $impuesto[$i],
            //         'medicion' => $medicion[$i],
            //         'minimo' => $minimo[$i],
            //         'importe' => $importe[$i],
            //         'diasDeEnvio' => $diasDeEnvio[$i],
            //         'dias' => $dias[$i],
            //         'periodo' => $periodo[$i],
            //         'tipoDia' => $tipoDia[$i],
            //         'comentario' => $comentario[$i],
            //     ];
            // }

            // $cotiz_detalle->insertBatch($data_detalles);


            // $targetDir = WRITEPATH . 'storage/' . $this->request->getPost('cotiz_num');
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

												$originalFileName = cleanFileName($originalFileName);

                        $file->move($targetDir, $originalFileName);
                        

                        // $data = [
                        //     'cotizacionId' => $this->request->getPost('cotiz_num'),
                        //     'archivo'      => $this->request->getPost('cotiz_num') . DIRECTORY_SEPARATOR . $originalFileName,
                        // ];

                        $data = [
                            'cotizacionId' => $cotiz_id,
                            'archivo'      => $cotiz_id . DIRECTORY_SEPARATOR . $originalFileName,
                        ];

                        $cotiz_archivo->insert($data);

                        // $savedFilePaths[] = $targetDir . DIRECTORY_SEPARATOR . $originalFileName;

                    } else {

                        $validationErrors[] = [
                            'file' => $file->getName(),
                            'error' => $file->getErrorString()
                        ];
                        continue;

                    }

                    // if ($file->isValid() && !$file->hasMoved()) {
                        // $newFileName = $file->getRandomName();
                        // $file->move($targetDir, $newFileName);
                        // $uploadedFileNames[] = $newFileName;

                    //     $originalFileName = $file->getClientName();
                    //     $file->move($targetDir, $originalFileName); 
                    //     $uploadedFileNames[] = $originalFileName; 
                    // }
         
                    // Common Errors from getErrorString():
                    // The file exceeds the upload_max_filesize directive in php.ini.
                    // The file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
                    // The file was only partially uploaded.
                    // No file was uploaded.
                    // The file is not writable.

                     // else {
                        // array_push($upload_errors, $file->getErrorString());

                        // return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                        //     ->setJSON(['error' => 'error on upload']);
                    // }
                }


            }


            if (empty($validationErrors)) {
                // If no errors, respond with a redirect URL
                // return $this->response->setJSON([
                //     'status' => 'success',
                //     'redirect' => '/success-page'
                // ]);

                $this->session->setFlashdata('msg', 'Cotización creada');
                return redirect()->to('/cotizar');

            } else {
                // If errors, respond with the errors array
                // return $this->response->setJSON([
                //     'status' => 'error',
                //     'errors' => $validationErrors
                // ]);
                $this->session->setFlashdata('msg_error', 'Ocurrio un Error');
                $this->session->setFlashdata('msg', 'Ocurrio un Error');
                return redirect()->to('/cotizar');
            }
        }
    }

}

