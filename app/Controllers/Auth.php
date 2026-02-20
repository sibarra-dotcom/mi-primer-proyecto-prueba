<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;

class Auth extends BaseController
{

    // $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);
    // public function loginAuth()
    // {
    //     $session = session();
    //     $userModel = new UserModel();
    //     $email = $this->request->getVar('email');
    //     $password = $this->request->getVar('password');
        
    //     $data = $userModel->where('email', $email)->first();
        
    //     if($data){
    //         $pass = $data['password'];
    //         $authenticatePassword = password_verify($password, $pass);
    //         if($authenticatePassword){
    //             $ses_data = [
    //                 'id' => $data['id'],
    //                 'name' => $data['name'],
    //                 'email' => $data['email'],
    //                 'isLoggedIn' => TRUE
    //             ];
    //             $session->set($ses_data);
    //             return redirect()->to('/dashboard');
            
    //         }else{
    //             $session->setFlashdata('msg', 'Password is incorrect.');
    //             return redirect()->to('/signin');
    //         }
    //     }else{
    //         $session->setFlashdata('msg', 'Email does not exist.');
    //         return redirect()->to('/signin');
    //     }
    // }


    public function signin()
    {
			if ($this->request->getMethod() === 'POST') {
        $userModel = new UserModel();
				
				if(isset($_POST['password'])) {
					$email_post = $this->request->getPost('email');
					$password = $this->request->getPost('password');

					$emailUsername = explode('@', $email_post)[0];
					$email = $emailUsername . '@gibanibb.com';

					$user = $userModel->getUserByEmail($email);

					if (!$user) {
							$this->session->setFlashdata('error', 'Email no registrado.');
							return redirect()->to('/');
					}
	
					if ($user && password_verify($password, $user['password'])) {
							$this->session->set('isLoggedIn', true);
							$this->session->set('userRole', $user['rol']);
							$this->session->set('user', $user);
	
							if ($user['rol_id'] == 1) {
									return redirect()->to('/admin/dashboard');
							} else {
									return redirect()->to('/dashboard');
							}
					} else {
							$this->session->setFlashdata('error', 'Contraseña incorrecta');
							return redirect()->to('/');
					}
				}

				if(isset($_POST['pin'])) {
					$pin = $this->request->getPost('pin');
					// echo "<pre>";
					// print_r($_POST);
					// exit;

					$email_post = $this->request->getPost('email');

					$emailUsername = explode('@', $email_post)[0];
					$email = $emailUsername . '@gibanibb.com';

					$user = $userModel->getUserByPIN($email, $pin);
	
					if (!$user) {
							$this->session->setFlashdata('error', 'PIN no registrado.');
							return redirect()->to('/');
					}
	
					if ($user) {
							$this->session->set('isLoggedIn', true);
							$this->session->set('userRole', $user['rol']);
							$this->session->set('user', $user);
	
							if ($user['rol_id'] == 1) {
									return redirect()->to('/admin/dashboard');
							} else {
									return redirect()->to('/dashboard');
							}
					} else {
							$this->session->setFlashdata('error', 'Contraseña incorrecta');
							return redirect()->to('/');
					}
				}

			}


    }

    // si el metodo es private no se podrá acceder
    public function signout()
    {
        $this->session->destroy();
        return redirect()->to('/');
    }

    public function forgot()
    {
        $data['title'] = 'En construcción';
        $data['message'] = 'En construcción..';
        
        return view('auth/forgot', $data);
    }
}