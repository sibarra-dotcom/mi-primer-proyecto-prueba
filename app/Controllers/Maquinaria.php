<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\Cotizacion;
use App\Models\CotizArchivo;
use App\Models\CotizDetalle;
use App\Models\ArticuloComm;
use App\Models\MaquinariaModel;
use App\Models\MaquinariaFilesModel;
use App\Models\MantenimientoModel;
use App\Models\MantAdjunto;

class Maquinaria extends BaseController
{
    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();
    }

		public function delete($id, $type = null)
    {
        if ($this->request->getMethod() === 'GET')
        {
					if (!$type) {
						$Model = new MaquinariaModel();
						$row = $Model->find($id);
					} 

					if ($type && $type == "file"){
						$Model = new MaquinariaFilesModel();
						$row = $Model->find($id);
					}

					if ($row) {
						$deleted = $Model->delete($id);
						if($deleted) {
							$res = [
								'success' => true,
								'redirect' => '/success-page',
								'id' => $id,
								'type' => $type,
							];
						}
					 }

					return $this->response->setJSON($res);
					exit;

				}
					
		}

    public function index()
    {
        if ($this->request->getMethod() === 'GET')
        {
            $maq = new MaquinariaModel();
					
						$data['plantas'] = $maq->getPlantas();
            $data['title'] = 'Máquinas';

            return view('maquinaria/index', $data);
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
}