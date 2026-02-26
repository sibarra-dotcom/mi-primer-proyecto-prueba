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
use App\Models\MantenimientoModel;
use App\Models\MantAdjunto;
use App\Models\EmailQueueModel;


class Mantenimiento extends BaseController
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
        if ($this->request->getMethod() === 'GET')
        {
            $mantenimiento = new MantenimientoModel();
						$maq = new MaquinariaModel();
            
            $_last = $mantenimiento->orderBy('id', 'DESC')->first();

            $data['title'] = 'Solicitud de Mantenimiento';
            $data['mant_num'] = $_last ? $_last['id'] + 1 : 1;

						$data['plantas'] = $maq->getPlantas();

            // $this->session->setFlashdata('msg', 'Cotización creada');
            // $this->session->setFlashdata('msg_error', 'Cotización creada');
						
						// echo "<pre>";
            // print_r($data);
            // exit;

            return view('mantenimiento/index', $data);
        }

        if ($this->request->getMethod() === 'POST')
        {
            $mant_adj = new MantAdjunto();
            $mant = new MantenimientoModel();

            // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);
            // exit;

            $_data = [
                'userId' => $this->session->get('user')['id'],
                'maqId' => $this->request->getPost('maqId'),
                'solicitante' => $this->request->getPost('solicitante'),
                'prioridad' => $this->request->getPost('prioridad'),
                'asunto' => $this->request->getPost('asunto'),
                'descripcion' => $this->request->getPost('descripcion'),
                'estado_maq' => $this->request->getPost('estado_maq'),
                'estado_ticket' => 1,
            ];

            $mant_id = $mant->insert($_data);
      

            // $targetDir = WRITEPATH . 'storage/mant/' . $this->request->getPost('mant_num');
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
                        
                        $data_file = [
                            // 'mantId' => $this->request->getPost('mant_num'),
                            // 'archivo' => 'mant' . DIRECTORY_SEPARATOR . $this->request->getPost('mant_num') . DIRECTORY_SEPARATOR . $originalFileName,
                            'mantId' => $mant_id,
                            'archivo' => 'mant' . DIRECTORY_SEPARATOR . $mant_id . DIRECTORY_SEPARATOR . $originalFileName,
                        ];

                        $mant_adj->insert($data_file);

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

								$estado_maq = $this->request->getPost('estado_maq');
								$prioridad = $this->request->getPost('prioridad');
								$maqId = $this->request->getPost('maqId');

								if($estado_maq == 'NO FUNCIONAL' && $prioridad == "ALTA") {

										$Maquinaria = new MaquinariaModel();

										$maq = $Maquinaria->where('id', $maqId)->get()->getRow();
										// print_r($maq);
										// exit;

										$subject = "Nueva notificacion";

										$body = "Notificacion de Mantenimiento \n";
										$body = " Equipo : " . $maq->nombre . "\n"; 
										$body .= " Planta : " . $maq->planta . "\n";
										$body .= " Linea n° : " . $maq->linea . "\n";
										$body .= " Estado : " . $estado_maq . "\n";
										$body .= " Prioridad : " . $prioridad . "\n";

										$email_model = new EmailQueueModel();

										$_data = [
											'recipient' => 'f996fe78.GIBANIBB.onmicrosoft.com@amer.teams.ms, c.lara@gibanibb.com',
											'subject' => $subject,
											'body' => $body,
											'status'    => 'pending'
										];
				
										$email_model->insert($_data);

										// $filePath = WRITEPATH . 'email_data.json';
										// file_put_contents($filePath, json_encode($data));

										// $command = "php " . FCPATH . "index.php mantenimiento/sendEmailProcess > /dev/null 2>&1 &";
										// shell_exec($command);
								}

                $this->session->setFlashdata('msg', 'Ticket creado');
                return redirect()->to('/mantenimiento');
            } else {

                $this->session->setFlashdata('msg_error', 'Ocurrio un Error');
                $this->session->setFlashdata('msg', 'Ocurrio un Error');
                return redirect()->to('/mantenimiento');
            }
        }
    }


		public function sendEmailProcess()
		{
				$filePath = WRITEPATH . 'email_data.json';

				if (!file_exists($filePath)) {
						file_put_contents(WRITEPATH . 'logs/log_email.txt', "Error: Email data file not found\n", FILE_APPEND);
						return;
				}
		
				$data = json_decode(file_get_contents($filePath), true);

				$email = \Config\Services::email();

				$email->setFrom('c.lara@gibanibb.com', 'C. Lara');
				$email->setTo($data['recipient']);
				$email->setSubject($data['subject']);
				$email->setMessage(nl2br($data['body']));
				
				if ($email->send()) {
					file_put_contents(WRITEPATH . 'logs/log_email.txt', "Email sent successfully\n", FILE_APPEND);
				} else {
						file_put_contents(WRITEPATH . 'logs/log_email.txt', "Error: " . print_r($email->printDebugger(), true) . "\n", FILE_APPEND);
				}
		
				unlink($filePath);
		}


}