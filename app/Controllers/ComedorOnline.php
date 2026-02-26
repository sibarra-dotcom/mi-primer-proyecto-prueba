<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\UserOperarioModel;

use App\Models\ComedorMenuModel;
use App\Models\ComedorEntregaModel;

class ComedorOnline extends BaseController
{
	  public function pedido()
    {
				if ($this->request->getMethod() === 'GET')
        {

					if (!$this->session->get('user_pedido')) {
							return redirect()->to('comedor_online');
					}

					$data['title'] = 'Dashboard de usuario';

					$Menu = new ComedorMenuModel;

					$today = date('Y-m-d');
					$menu = $Menu->where('fecha', $today)->first();

					if ($menu) {
							$data['menu_dia'] = $menu['descripcion'];
					} else {
							$data['menu_dia'] = 'No menu available for today';
					}

					return view('comedor_online/pedido', $data);
				}

				if ($this->request->getMethod() === 'POST')
        {
					// echo "<pre>";
					// print_r($_POST);
					// exit;

					  $Entrega = new ComedorEntregaModel;

            $data = [
                'userId' => $this->request->getPost('userId'),
                'nombre' => $this->request->getPost('nombre'),
                'fecha' => $this->request->getPost('fecha'),
                'menu_dia' => $this->request->getPost('menu_dia') ?? "",
                'menu_base' => $this->request->getPost('menu_base') ?? "",
                'horario' => $this->request->getPost('horario'),
                'observacion' => $this->request->getPost('observacion'),
            ];

						$inserted = $Entrega->insert($data);
		
						if ($inserted) {
								return $this->response->setJSON([
									'success' => true,
									'message' => 'user found.'
								]);
						} else {
								return $this->response->setJSON([
									'success' => false,
									'message' => 'ocurrio un error.',
									'csrf' => [
											'name' => csrf_token(),
											'hash' => csrf_hash(),
									]
								]);
						}
				}


		}


    public function index()
    {
				if ($this->request->getMethod() === 'GET')
        {
					$this->session->remove('user_pedido');

					$data['title'] = 'Dashboard de usuario';
					return view('comedor_online/index', $data);
				}

				if ($this->request->getMethod() === 'POST')
        {
        		$User = new UserModel();
				    $UserOp = new UserOperarioModel();
						$Entrega = new ComedorEntregaModel;

						$pin = $this->request->getPost('pin');

						$user = $User->getByPIN($pin);
						$operario = $UserOp->getByEmpleadoId($pin);

						if (!$user && !$operario) 
						{
								return $this->response->setJSON([
									'success' => false,
									'message' => 'PIN o ID no registrado.',
									 'csrf' => [
												'name' => csrf_token(),
												'hash' => csrf_hash(),
										]
								]);
						}

						if ($user && !$operario) {

								$user_entregado = $Entrega->checkComidaDia($user['user_id']);

								if (count($user_entregado) >= 2) {
									return $this->response->setJSON([
										'success' => false,
										'message' => 'Usuario ya se no puede registrar.',
										'csrf' => [
												'name' => csrf_token(),
												'hash' => csrf_hash(),
										]
									]);
								}

								$this->session->set('user_pedido', $user);
								return $this->response->setJSON([
									'success' => true,
									'message' => 'user found.'
								]);
						} 
		
						if (!$user && $operario) {
								$user_entregado = $Entrega->checkComidaDia($operario['user_id']);

								if (count($user_entregado) >= 2) {
									return $this->response->setJSON([
										'success' => false,
										'message' => 'Usuario ya se no puede registrar.',
										'csrf' => [
												'name' => csrf_token(),
												'hash' => csrf_hash(),
										]
									]);
								}

								$this->session->set('user_pedido', $operario);
								return $this->response->setJSON([
									'success' => true,
									'message' => 'user found.'
								]);
						}
        }

    }


}
