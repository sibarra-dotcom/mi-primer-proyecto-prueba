<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data['title'] = 'Iniciar Sesion';
        $data['message'] = 'Iniciar Sesion';
        
        return view('home/index', $data);
        // return redirect()->to('/inactive');
    }
}
