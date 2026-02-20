<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Inactive extends BaseController
{
    public function index()
    {
        helper(['form']);

        $data['title'] = 'Inactive';
        $data['message'] = 'Oops! The page you are looking for does not exist.';
        
        return view('inactive/index', $data);
    }


    public function link()
    {
        $data['title'] = 'Sitio en construcción.';
        // $data['message'] = 'We are currently working on updates.';
        $data['message'] = '';
        return view('inactive/link', $data);
    }
}
