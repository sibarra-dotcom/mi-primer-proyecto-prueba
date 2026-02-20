<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\Cotizacion;
use App\Models\CotizArchivo;
use App\Models\CotizDetalle;
use App\Models\ArticuloComm;
use App\Models\MantAdjunto;

class Aprobaciones extends BaseController
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

            // $cotizacion = new Cotizacion();
            // $cotiz_detalle = new CotizDetalle();
            
            // $_last = $cotizacion->orderBy('id', 'DESC')->first();

            $data['title'] = 'Aprobaciones';
            // $data['results'] = $cotizacion->getListaWithAprob();

            // $data['cotiz_num'] = $_last ? $_last['id'] + 1 : 1;

            // $this->session->setFlashdata('msg', 'Cotización creada');
            // $this->session->setFlashdata('msg_error', 'Cotización creada');

            // echo "<pre>";
            // print_r($data);
            // exit;

            return view('aprobaciones/index', $data);

        }

    }
}