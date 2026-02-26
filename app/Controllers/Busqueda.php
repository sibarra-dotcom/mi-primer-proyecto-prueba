<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\Cotizacion;
use App\Models\CotizArchivo;
use App\Models\CotizDetalle;
use App\Models\ArticuloComm;
use App\Models\ArticuloAdj;
use App\Models\MantAdjunto;

class Busqueda extends BaseController
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
            $cotizacion = new Cotizacion();
            $cotiz_detalle = new CotizDetalle();
            
            $_last = $cotizacion->orderBy('id', 'DESC')->first();

            $data['title'] = 'Búsqueda de Cotizaciones';
            $data['results'] = $cotizacion->getListaWithComment();

            $data['cotiz_num'] = $_last ? $_last['id'] + 1 : 1;

            // $this->session->setFlashdata('msg', 'Cotización creada');
            // $this->session->setFlashdata('msg_error', 'Cotización creada');

            // echo "<pre>";
            // print_r($data);
            // exit;

            return view('busqueda/index', $data);
        }

    }

		public function upload_ficha()
    {

				if ($this->request->getMethod() === 'POST')
        {
						// echo "<pre>";
						// print_r($_FILES);
						// print_r($_POST);
						// exit;

            $Art_adj = new ArticuloAdj();

						$artId = $this->request->getPost('artId');

						$cotizId = $this->request->getPost('cotizId');

						$targetDir = WRITEPATH . 'storage/' . $cotizId;

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
                            'articuloId' => $artId,
                            'archivo'      => $cotizId . DIRECTORY_SEPARATOR . $originalFileName,
                        ];

                        $Art_adj->insert($data);
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