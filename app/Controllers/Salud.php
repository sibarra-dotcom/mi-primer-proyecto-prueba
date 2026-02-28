<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Salud extends BaseController
{
    /**
     * Roles de BD que tienen acceso al módulo de salud
     * y su mapeo al rol del SPA
     */
    private $rolMap = [
        'salud_ocupacional' => 'salud',
        'rrhh'              => 'rh',
        'admin'             => 'salud',
    ];

    /**
     * Nombres legibles para cada rol del SPA
     */
    private $rolNombres = [
        'salud' => 'Salud Ocupacional',
        'rh'    => 'Recursos Humanos',
    ];

    /**
     * Panel de expediente médico (requiere auth)
     */
    public function index()
    {
        $user = $this->session->get('user');
        if (!$user) {
            return redirect()->to(base_url('/'));
        }

        $rolBD = $user['rol'] ?? '';
        $saludRol = $this->mapRol($rolBD);

        if ($saludRol === null) {
            return redirect()->to(base_url('apps'))->with('msg_error', 'No tienes acceso al módulo de salud.');
        }

        $allRoles = ($rolBD === 'admin');

        $data = [
            'title'      => 'Expediente Médico',
            'saludRol'   => $saludRol,
            'allRoles'   => $allRoles,
            'rolNombres' => $this->rolNombres,
            'userName'   => $user['nombre'] ?? 'Usuario',
            'userEmail'  => $user['email'] ?? '',
            'rolNombre'  => $this->rolNombres[$saludRol] ?? $saludRol,
        ];

        return view('salud/index', $data);
    }

    private function mapRol(string $rolBD): ?string
    {
        return $this->rolMap[$rolBD] ?? null;
    }
}
