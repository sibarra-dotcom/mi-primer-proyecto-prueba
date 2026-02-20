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

class Mtickets extends BaseController
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
					$user = new UserModel();
						// echo "<pre>";
						// print_r($_SESSION);
						// exit;
	
					
					$data['plantas'] = $maq->getPlantas();
					$data['users_mt'] = $user->where('rol_id', 6)->get()->getResultArray();
					$data['jefes_mt'] = $user->where('rol_id', 7)->get()->getResultArray();
					// cambiar a email jefe mantenimiento
					$data['jefe'] = $user->where('email', 'mt_jefe@gibanibb.com')->first();
					$data['signature'] = $user->where('id', $this->session->get('user')['id'])->first()['signature'];

					$data['title'] = 'Tickets de Mantenimiento';
					return view('mtickets/index', $data);
        }
    }
}
