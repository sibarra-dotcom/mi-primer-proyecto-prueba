<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        $data['title'] = 'Dashboard de usuario';
        return view('dashboard/index', $data);
    }
}
