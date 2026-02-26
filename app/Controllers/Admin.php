<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\RoleModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();
    }

    public function dashboard()
    {
        $data['users'] = $this->userModel->getUsersWithRoles();
        $data['title'] = 'Portal Gibanibb Administrador';      
        return view('admin/dashboard', $data);
    }


    public function usuarios()
    {
        $data['users'] = $this->userModel->getUsersWithRoles();
        $data['title'] = 'Lista Usuarios';
        return view('admin/usuarios', $data);
    }

    public function user_create()
    {
				$Roles = new RoleModel;
        $data['title'] = "Crear Nuevo Usuario";
        $data['roles'] = $Roles->findAll();
        return view('admin/user_create', $data);
    }

    public function user_create_post()
    {
        $this->validation->setRules([
            'name'  => 'required|min_length[3]',
            'last_name'  => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'pin' => 'required|min_length[4]|numeric',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $data = [
            'rol_id'    => $this->request->getPost('rol_id'),
            'name'      => $this->request->getPost('name'),
            'last_name' => $this->request->getPost('last_name'),
            'email'     => $this->request->getPost('email'),
            'pin'     => $this->request->getPost('pin'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ];

        $this->userModel->insert($data);

        return redirect()->to('/admin/usuarios')->with('success', 'User created successfully');
    }


    public function user_edit($id)
    {
				$Roles = new RoleModel;

        $user = $this->userModel
            ->select('users.*, roles.rol')
            ->join('roles', 'roles.id = users.rol_id')
            ->where('users.id', $id)
            ->first();

        // Check if user exists
        if (!$user) {
            return redirect()->to('/admin/usuarios')->with('error', 'User not found');
        }

        $data['user'] = $user;
        $data['title'] = "Editar Usuario";
        $data['roles'] = $Roles->findAll();

        // Load the view with the user data for editing
        return view('admin/user_edit', $data);
    }

    public function user_delete($id)
    {
        // Fetch the user to check if they exist
        $user = $this->userModel->find($id);

        if (!$user) {
             return redirect()->to('/admin/usuarios')->with('error', 'User not found');
        }

        // Delete the user
        $this->userModel->delete($id);

        // Redirect back to the user list with a success message
        return redirect()->to('/admin/usuarios')->with('success', 'User deleted successfully');
    }

    public function user_update($id)
    {

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }


        // Define validation rules for the update
        $rules = [
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email,id,{id}]',
                'errors' => [
                    'is_unique' => 'The email address is already in use.'
                ]
            ],
            'name' => 'required|min_length[3]',
            'pin' => 'required|min_length[4]|numeric',
            'last_name'  => 'required|min_length[3]',
        ];

        // Dynamically replace `{id}` in the email rule with the actual user ID
        $rules['email']['rules'] = str_replace('{id}', $id, $rules['email']['rules']);


        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'rol_id'    => $this->request->getPost('rol_id'),
            'name'      => $this->request->getPost('name'),
            'last_name' => $this->request->getPost('last_name'),
            'email'     => $this->request->getPost('email'),
            'pin'     => $this->request->getPost('pin'),
        ];

        if ($this->userModel->update($id, $data)) {
            return redirect()->to('/admin/usuarios')->with('success', 'User updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update user');
        }
    }


}
