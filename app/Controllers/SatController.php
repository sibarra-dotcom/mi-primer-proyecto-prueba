<?php

namespace App\Controllers;

use App\Services\SatAuthService;
use App\Services\SatDownloadService;
use App\Services\CfdiValidatorService;

class SatController extends BaseController
{
    protected $authService;
    protected $downloadService;
    protected $validatorService;

    public function __construct()
    {
        $this->authService = new SatAuthService();
        $this->downloadService = new SatDownloadService();
        $this->validatorService = new CfdiValidatorService();
    }

    // Authenticate with SAT and get a token
    public function autenticar()
    {
        $token = $this->authService->autenticar(
            'TU_RFC',
            WRITEPATH . 'certs/csd.cer',
            WRITEPATH . 'certs/csd.key',
            'PASSWORD_DEL_CSD'
        );

        return $this->response->setJSON(['token' => $token]);
    }

    // Validate uploaded CFDI XML and optionally sign it
    public function validarXml()
    {
        $file = $this->request->getFile('xml');

        if (!$file->isValid()) {
            return $this->response->setStatusCode(400)
                                  ->setJSON(['error' => 'Archivo no válido']);
        }

        $resultado = $this->validatorService->validar($file->getTempName());

        return $this->response->setJSON($resultado);
    }

    // Download XML CFDI from SAT (requires prior auth)
    public function descargarXml()
    {
        $token = $this->authService->getToken(); // assume token is cached
        $rfc = 'TU_RFC';
        $fechaInicial = '2026-01-01';
        $fechaFinal = '2026-01-28';

        $xmlFiles = $this->downloadService->descargar($token, $rfc, $fechaInicial, $fechaFinal);

        return $this->response->setJSON(['archivos' => $xmlFiles]);
    }

		public function firmar()
		{
				$file = $this->request->getFile('xml');

				$result = $this->validatorService->firmarXml(
						$file->getTempName(),
						WRITEPATH . 'certs/csd.key',
						WRITEPATH . 'certs/csd.cer',
						'PASSWORD_DEL_CSD'
				);

				return $this->response->setJSON($result);
		}

}
