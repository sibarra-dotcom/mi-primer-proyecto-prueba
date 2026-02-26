<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\Proveedor;
use App\Models\ProveedorContacto;

class Proveedores extends BaseController
{
    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();
    }

		// not used
    public function contacto($contactoId)
    {
        if ($this->request->getMethod() === 'GET')
        {

            $prov_cont = new ProveedorContacto();

            $data['prov_cont'] = $prov_cont->where('id', $contactoId)->first();
            $data['title'] = 'Contacto Proveedor';

            // echo "<pre>";
            // print_r($data);
            // exit;
   
            return view('proveedores/contacto', $data);
        }

        if ($this->request->getMethod() === 'POST')
        {

            $prov_cont = new ProveedorContacto();
            $contactoId =  $this->request->getPost('contactoId');

            $data_det = [
                'nombre' => $this->request->getPost('nombre'),
                'puesto' => $this->request->getPost('puesto'),
                'telefono' => $this->request->getPost('telefono'),
                'correo' => $this->request->getPost('correo'),
            ];

            $response = $prov_cont->update($contactoId, $data_det);

            if ($response) {

                $this->session->setFlashdata('msg', 'Creado Correctamente');
                return redirect()->to("/proveedores/contacto/$contactoId");

            } else {

                $this->session->setFlashdata('msg_error', 'Ocurrio un Error');
                $this->session->setFlashdata('msg', 'Ocurrio un Error');
                return redirect()->to("/proveedores/contacto/$contactoId");
            }      
        

        }

    }

		// not used
    public function details($proveedorId)
    {
        if ($this->request->getMethod() === 'GET')
        {

            $prov = new Proveedor();
            $prov_cont = new ProveedorContacto();

            $data['proveedor'] = $prov->find($proveedorId);
            $data['prov_cont'] = $prov_cont->where('proveedorId', $proveedorId)->findAll();
            $data['title'] = 'Proveedores';
   
            return view('proveedores/details', $data);
        }

        if ($this->request->getMethod() === 'POST')
        {
            $prov = new Proveedor();
            $prov_cont = new ProveedorContacto();
            $proveedorId =  $this->request->getPost('proveedorId');
                
            if(isset($_POST['crear_cont'])) {

                $data_prov = [
                    'proveedorId' => $this->request->getPost('proveedorId'),
                    'nombre' => $this->request->getPost('nombre'),
                    'puesto' => $this->request->getPost('puesto'),
                    'telefono' => $this->request->getPost('telefono'),
                    'correo' => $this->request->getPost('correo'),
                ];

                $prov_cont->insert($data_prov);
                
                if ($prov_cont) {

                    $this->session->setFlashdata('msg', 'Creado Correctamente');
                    return redirect()->to("/proveedores/details/$proveedorId");

                } else {

                    $this->session->setFlashdata('msg_error', 'Ocurrio un Error');
                    $this->session->setFlashdata('msg', 'Ocurrio un Error');
                    return redirect()->to("/proveedores/details/$proveedorId");
                }

            }

            if(isset($_POST['editar_prov'])) {

                $data_det = [
                    'razon_social' => $this->request->getPost('razon_social'),
                    'direccion' => $this->request->getPost('direccion'),
                    'pais' => $this->request->getPost('pais'),
                    'tipo_prov' => $this->request->getPost('tipo_prov'),
                    'sitio_web' => $this->request->getPost('sitio_web'),
                ];

                $response = $prov->update($proveedorId, $data_det);

                if ($response) {

                    $this->session->setFlashdata('msg', 'Creado Correctamente');
                    return redirect()->to("/proveedores/details/$proveedorId");

                } else {

                    $this->session->setFlashdata('msg_error', 'Ocurrio un Error');
                    $this->session->setFlashdata('msg', 'Ocurrio un Error');
                    return redirect()->to("/proveedores/details/$proveedorId");
                }      
            }

        }

    }


    public function index()
    {
        if ($this->request->getMethod() === 'GET')
        {
            $prov = new Proveedor();

            $data['proveedores'] = $prov->orderBy('id', 'DESC')->findAll();
            
            $data['title'] = 'Proveedores';
            // $data['user_id'] = $this->session->get('user')['id'];

            // $this->session->setFlashdata('msg', 'Cotización creada');
            // $this->session->setFlashdata('msg_error', 'Cotización creada');
            return view('proveedores/index', $data);
        }

        if ($this->request->getMethod() === 'POST')
        {

            // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);

            // exit;

            $prov = new Proveedor();
						$prov_cont = new ProveedorContacto();

						// $btn = $this->request->getPost('create_prov');

						if(isset($_POST['create_prov'])) {

							// echo "<pre>";
							// print_r($_POST);
							// echo "</pre>";

							// exit;

							$data_prov = [
                'razon_social' => $this->request->getPost('razon_social'),
                'direccion' => $this->request->getPost('direccion'),
                'pais' => $this->request->getPost('pais'),
                // 'tipo_prov' => $this->request->getPost('tipo_prov'),
                // 'sitio_web' => $this->request->getPost('sitio_web'),
            	];

							$prov_id = $prov->insert($data_prov);
      
							$nombre = $this->request->getPost('nombre');
							$correo = $this->request->getPost('correo');
							$telefono = $this->request->getPost('telefono');
							$puesto = $this->request->getPost('puesto');
	
							for ($i = 0; $i < count($nombre); $i++) {
									$prov_cont->insert([
											'proveedorId' => $prov_id,
											'nombre' => $nombre[$i],
											'correo' => $correo[$i],
											'telefono' => $telefono[$i],
											'puesto' => $puesto[$i],
									]);
							}

            
							if ($prov_id) {
									return $this->response->setJSON([
									    'status' => 'success',
									    'redirect' => '/success-page'
									]);
	
									// $this->session->setFlashdata('msg', 'Creado Correctamente');
									// return redirect()->to('/proveedores');
	
							} else {

									return $this->response->setJSON([
									    'status' => 'error',
									    // 'errors' => $validationErrors
									]);
									// $this->session->setFlashdata('msg_error', 'Ocurrio un Error');
									// $this->session->setFlashdata('msg', 'Ocurrio un Error');
									// return redirect()->to('/proveedores');
							}

						} else if (isset($_POST['create_contacto'])) {


							$prov_id = $this->request->getPost('proveedorId');
      
							$nombre = $this->request->getPost('nombre');
							$correo = $this->request->getPost('correo');
							$telefono = $this->request->getPost('telefono');
							$puesto = $this->request->getPost('puesto');
	
							for ($i = 0; $i < count($nombre); $i++) {
									$prov_cont->insert([
											'proveedorId' => $prov_id,
											'nombre' => $nombre[$i],
											'correo' => $correo[$i],
											'telefono' => $telefono[$i],
											'puesto' => $puesto[$i],
									]);

							}

							if ($prov_id) {
									return $this->response->setJSON([
									    'status' => 'success',
									    'redirect' => '/success-page'
									]);
	
									// $this->session->setFlashdata('msg', 'Creado Correctamente');
									// return redirect()->to('/proveedores');
	
							} else {
									return $this->response->setJSON([
									    'status' => 'error',
									    // 'errors' => $validationErrors
									]);
									// $this->session->setFlashdata('msg_error', 'Ocurrio un Error');
									// $this->session->setFlashdata('msg', 'Ocurrio un Error');
									// return redirect()->to('/proveedores');
							}


						} else if (isset($_POST['edit_prov'])) {


							$prov = new Proveedor();

							$prov_id = $this->request->getPost('proveedorId');

							$data_prov = [
									'razon_social' => $this->request->getPost('razon_social'),
									'direccion' => $this->request->getPost('direccion'),
									'pais' => $this->request->getPost('pais'),
							];
	
							$response = $prov->update($prov_id, $data_prov);

							if ($response) {
									// If no errors, respond with a redirect URL
									// return $this->response->setJSON([
									//     'status' => 'success',
									//     'redirect' => '/success-page'
									// ]);
	
									$this->session->setFlashdata('msg', 'Creado Correctamente');
									return redirect()->to('/proveedores');
	
							} else {
									// If errors, respond with the errors array
									// return $this->response->setJSON([
									//     'status' => 'error',
									//     'errors' => $validationErrors
									// ]);
									$this->session->setFlashdata('msg_error', 'Ocurrio un Error');
									$this->session->setFlashdata('msg', 'Ocurrio un Error');
									return redirect()->to('/proveedores');
							}


						}
            


        }
    }
}