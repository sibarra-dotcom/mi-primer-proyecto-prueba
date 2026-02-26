<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
		protected $session;

		public function __construct()
		{
				$this->session = \Config\Services::session();
		}


    public function index()
    {
				$user = new UserModel();

				if ($this->request->getMethod() === 'GET')
				{
					$data['title'] = 'Mi Perfil';

					$user = $user->where('id', $this->session->get('user')['id'])->first();
					$data['signature'] = $user['signature'];
					$data['aviso_confirmado'] = $user['confirmar_aviso'];

					$data['picture'] = $user['picture'];

					$data['profile_complete'] = $user['signature'] ? 100 : 80;
					$data['message'] = 'Iniciar Sesion';
					
					return view('profile/index', $data);
					// return redirect()->to('/inactive');
				}

				if ($this->request->getMethod() === 'POST')
				{
					// echo "<pre>";
					// print_r($_POST);
					// print_r($_FILES);
					// exit;

					$fileName = "profile_" . time() . ".png";
					$targetDir = WRITEPATH . 'storage/profiles/';

					if (!is_dir($targetDir)) {
							mkdir($targetDir, 0755, true);
					}

					$file = $this->request->getFile('archivo');

					if ($file->isValid() && !$file->hasMoved())  {

							$file->move($targetDir, $fileName);

							$userId = $this->session->get('user')['id'];

							$data_det = [
								'picture' => 'profiles' . DIRECTORY_SEPARATOR . $fileName,
							];

							$response = $user->update($userId, $data_det);

							if ($response) {
								$this->session->setFlashdata('msg', 'Firma guardada con éxito.');
								return redirect()->to('/profile');
							} else {
									$this->session->setFlashdata('msg_error', 'Error al actualizar el usuario.');
							}

					} else {
							$this->session->setFlashdata('msg_error', 'Ocurrio un Error');
							$this->session->setFlashdata('msg', 'Ocurrio un Error');
							return redirect()->to('/profile');
					}

				}

    }

		public function change_password()
    {
				if ($this->request->getMethod() === 'GET')
				{

					$data['title'] = 'Cambiar Contraseña';
					$data['message'] = 'Iniciar Sesion';
					
					return view('profile/change_password', $data);
				}

				if ($this->request->getMethod() === 'POST')
				{
	
						// echo "<pre>";
						// print_r($_POST);
						// exit;
	
						$user = new UserModel();

						$userId = $this->session->get('user')['id'];


						$hashedPassword = password_hash($this->request->getPost("password"), PASSWORD_DEFAULT);

						$data_det = [
								'password' => $hashedPassword
						];
						
						$response = $user->update($userId, $data_det);
	

						if ($response) {
							$this->session->setFlashdata('msg', 'Actualizado con éxito.');
							return redirect()->to('/profile/change_password');
	
						} else {
								$this->session->setFlashdata('msg_error', 'Ocurrio un Error');
								$this->session->setFlashdata('msg', 'Ocurrio un Error');
								return redirect()->to('/profile/change_password');
						}
	
				}

    }

		public function confirmar_aviso()
    {
				if ($this->request->getMethod() === 'POST')
				{
	
					// echo "<pre>";
					// print_r( $this->request->getPost());
					// exit;

					$user = new UserModel();
					$userId = $this->session->get('user')['id'];

					$data_det = [
							'confirmar_aviso' => $this->request->getPost("confirmar_aviso")
					];
					
					$response = $user->update($userId, $data_det);

					if ($response) {
							return $this->response->setJSON([
									'success' => true,
									'redirect' => '/success-page'
							]);
					} else {
							return $this->response->setJSON([
									'success' => false,
									'errors' => "error on update"
							]);
					}
				}
		}
		
				

		public function signature()
    {
			if ($this->request->getMethod() === 'GET')
			{
				$user = new UserModel();

        $data['title'] = 'Firma Digital';
        $data['message'] = 'Iniciar Sesion';
				$data['signature'] = $user->where('id', $this->session->get('user')['id'])->first()['signature'];

        return view('profile/signature', $data);
        // return redirect()->to('/inactive');
			}

			if ($this->request->getMethod() === 'POST')
			{

					// echo "<pre>";
					// print_r($_POST);
					// print_r($_FILES);
					// exit;

					$user = new UserModel();

					$fileName = "signature_" . time() . ".png";
					$targetDir = WRITEPATH . 'storage/signatures/';

					if (!is_dir($targetDir)) {
							mkdir($targetDir, 0755, true);
					}

					$file = $this->request->getFile('signature');

					if ($file->isValid() && !$file->hasMoved())  {

							$file->move($targetDir, $fileName);

							$userId = $this->session->get('user')['id'];

							$data_det = [
								'signature' => 'signatures' . DIRECTORY_SEPARATOR . $fileName,
							];

							$response = $user->update($userId, $data_det);

							if ($response) {
								$this->session->setFlashdata('msg', 'Firma guardada con éxito.');
								return redirect()->to('/profile');
							} else {
									$this->session->setFlashdata('msg_error', 'Error al actualizar el usuario.');
							}

					} else {
							$this->session->setFlashdata('msg_error', 'Ocurrio un Error');
							$this->session->setFlashdata('msg', 'Ocurrio un Error');
							return redirect()->to('/profile/signature');
					}

					// $user = new UserModel();

					// $signatureBase64 = $this->request->getPost("signature");
	
					// // Decode Base64 and save the image
					// $signatureData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $signatureBase64));
					// $fileName = "signature_" . time() . ".png";
					// $targetDir = WRITEPATH . 'storage/signatures/';
	
					// // Ensure directory exists
					// if (!is_dir($targetDir)) {
					// 		mkdir($targetDir, 0755, true);
					// }
	
					// $filePath = $targetDir . $fileName;
					// $bytesWritten = file_put_contents($filePath, $signatureData);
	
					// if ($bytesWritten !== false) {
					// 		$userId = $this->session->get('user')['id'];

					// 		$data_det = [
					// 			'signature' => 'signatures' . DIRECTORY_SEPARATOR . $fileName,
					// 		];

					// 		$response = $user->update($userId, $data_det);

					// 		if ($response) {
          //       $this->session->setFlashdata('msg', 'Firma guardada con éxito.');
          //       return redirect()->to('/profile/signature');
					// 		} else {
					// 				$this->session->setFlashdata('msg_error', 'Error al actualizar el usuario.');
					// 		}

					// } else {
					// 		$this->session->setFlashdata('msg_error', 'Ocurrio un Error');
					// 		$this->session->setFlashdata('msg', 'Ocurrio un Error');
					// 		return redirect()->to('/profile/signature');
					// }

			}
    }
}
