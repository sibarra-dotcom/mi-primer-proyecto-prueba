<?php

namespace App\Controllers;

class Home extends BaseController
{
		protected $session;

		public function __construct()
		{
				$this->session = \Config\Services::session();
		}

    public function index()
    {
        $data['title'] = 'Iniciar Sesion';
        $data['message'] = 'Iniciar Sesion';
        
        return view('home/index', $data);
        // return redirect()->to('/inactive');
    }

		public function session_check()
		{
				if (!$this->session->has('user') || !isset($this->session->get('user')['id'])) {
					return $this->response->setJSON([
							'success' => false,
							'msg' => 'Session expired',
					]);

				}

				return $this->response->setJSON([
						'success' => true,
						'msg' => 'OK',
				]);

		}
}
