<?php

namespace App\Controllers;

class Apps extends BaseController
{
    public function index()
    {
        $data['title'] = 'Aplicaciones';
        return view('apps/index', $data);
    }
}
