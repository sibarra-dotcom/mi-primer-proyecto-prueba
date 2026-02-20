<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Vacantes extends BaseController
{
    /**
     * Roles de BD que tienen acceso al módulo de vacantes
     * y su mapeo al rol del SPA
     */
    private $rolMap = [
        'rrhh'                              => 'rh',
        'admin'                             => 'rh',
        'gerencia_dei'                      => 'gerente-do',
        'coordinador_ti'                    => 'jefe-it',
        'gerente_comercial'                 => 'jefe-ventas',
        'encargado_de_mercadotecnia'        => 'jefe-marketing',
        'gerente_manufactura'               => 'jefe-operaciones',
        'gerente_administracion_y_finanzas' => 'jefe-finanzas',
    ];

    /**
     * Nombres legibles para cada rol del SPA
     */
    private $rolNombres = [
        'rh'               => 'Recursos Humanos',
        'gerente-do'       => 'Gerente de Desarrollo Org.',
        'jefe-it'          => 'Jefe de IT',
        'jefe-ventas'      => 'Jefe de Ventas',
        'jefe-marketing'   => 'Jefe de Marketing',
        'jefe-operaciones' => 'Jefe de Operaciones',
        'jefe-finanzas'    => 'Jefe de Finanzas',
        'gerente-finanzas' => 'Gerente de Finanzas',
    ];

    /**
     * Panel administrativo de vacantes (requiere auth)
     */
    public function index()
    {
        $user = $this->session->get('user');
        if (!$user) {
            return redirect()->to(base_url('/'));
        }

        $rolBD = $user['rol'] ?? '';
        $vacantesRol = $this->mapRol($rolBD);

        if ($vacantesRol === null) {
            return redirect()->to(base_url('apps'))->with('msg_error', 'No tienes acceso al módulo de vacantes.');
        }

        // Admin y RRHH pueden probar todos los roles con un selector
        $allRoles = in_array($rolBD, ['admin', 'rrhh']);

        // Doble rol: gerente_administracion_y_finanzas tiene jefe-finanzas Y gerente-finanzas
        $dobleRol = ($rolBD === 'gerente_administracion_y_finanzas');

        $data = [
            'title'       => 'Módulo de Vacantes',
            'vacantesRol' => $vacantesRol,
            'dobleRol'    => $dobleRol,
            'allRoles'    => $allRoles,
            'rolNombres'  => $this->rolNombres,
            'userName'    => $user['nombre'] ?? 'Usuario',
            'rolNombre'   => $this->rolNombres[$vacantesRol] ?? $vacantesRol,
        ];

        return view('vacantes/index', $data);
    }

    /**
     * Portal público de empleo (sin auth)
     */
    public function portal()
    {
        return view('vacantes/portal');
    }

    /**
     * Seguimiento de postulación (sin auth)
     */
    public function mipostulacion()
    {
        return view('vacantes/mipostulacion');
    }

    /**
     * Mapea el rol de la BD al rol del SPA
     * Retorna null si el rol no tiene acceso
     */
    private function mapRol(string $rolBD): ?string
    {
        return $this->rolMap[$rolBD] ?? null;
    }
}
