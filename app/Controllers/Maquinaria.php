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

class Maquinaria extends BaseController
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
            $maq = new MaquinariaModel();
            // findAll(limit, offset)

            $data['maquinas'] = $maq->orderBy('id', 'DESC')->findAll();
            
            $data['title'] = 'Lista Maquinaria';
            // $data['user_id'] = $this->session->get('user')['id'];

            // $this->session->setFlashdata('msg', 'Cotización creada');
            // $this->session->setFlashdata('msg_error', 'Cotización creada');
            return view('maquinaria/index', $data);
        }

        if ($this->request->getMethod() === 'POST')
        {
            $maq = new MaquinariaModel();
            
            $data_maq = [
                'nombre' => $this->request->getPost('nombre'),
                'marca' => $this->request->getPost('marca'),
                'modelo' => $this->request->getPost('modelo'),
                'serie' => $this->request->getPost('serie'),
                'year' => $this->request->getPost('year'),
                'planta' => $this->request->getPost('planta'),
                'linea' => $this->request->getPost('linea'),
                'fechaAdqui' => $this->request->getPost('fechaAdqui'),
            ];

            $maq->insert($data_maq);
            
            if ($maq) {
                // If no errors, respond with a redirect URL
                // return $this->response->setJSON([
                //     'status' => 'success',
                //     'redirect' => '/success-page'
                // ]);

                $this->session->setFlashdata('msg', 'Creado Correctamente');
                return redirect()->to('/maquinaria');

            } else {
                // If errors, respond with the errors array
                // return $this->response->setJSON([
                //     'status' => 'error',
                //     'errors' => $validationErrors
                // ]);
                $this->session->setFlashdata('msg_error', 'Ocurrio un Error');
                $this->session->setFlashdata('msg', 'Ocurrio un Error');
                return redirect()->to('/maquinaria');
            }
        }
    }
}