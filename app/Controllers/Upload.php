<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\Proveedor;
use App\Models\Cotizacion;
use App\Models\CotizDetalle;
use App\Models\MaquinariaModel;
use App\Models\ArticuloAdj;
use App\Models\ArticuloComm;
use App\Models\CotizArchivo;
use App\Models\Aprobacion;
use App\Models\ProveedorContacto;
use App\Models\MantenimientoModel;

use App\Models\MaquinariaFilesModel;
use App\Models\InspeccionFilesModel;

use App\Models\ProductosModel;


use App\Models\MantComm;
use App\Models\MantAdjunto;


class Upload extends ResourceController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }


		public function inspeccion()
		{

			if ($this->request->getMethod() === 'POST')
			{

					$tipo = $this->request->getPost('tipo');

					if ($tipo == "etiqueta") {

						$targetDir = WRITEPATH . 'storage/inspecciones/etiquetas/';

						if (!is_dir($targetDir)) {
								mkdir($targetDir, 0755, true);
						}

						$file = $this->request->getFile('etiqueta');

						// echo "<pre>";
						// print_r($_FILES);
						// print_r($_POST);
						// print_r($file);
						// echo  $file->isValid() ? 'is valid': 'invalid file';
						// echo $file->getMimeType();
						// exit;

						$validationErrors = [];
						$url = '';
						
						if ($file && $file->isValid()) {

								$allowedMimeTypes = ['image/png', 'image/jpeg', 'image/jpg'];
								$maxSize = 1024 * 1024 * 8; // 8MB in bytes

								if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
										$validationErrors[] = [
												'file' => $file->getName(),
												'error' => 'Invalid file type',
												'mime' => $file->getMimeType()
										];
										// continue;
								}

								if ($file->getSize() > $maxSize) {
										$validationErrors[] = [
												'file' => $file->getName(),
												'error' => 'File exceeds maximum size of 8MB'
										];
										// continue;
								}

								if ($file->isValid() && !$file->hasMoved()) {

									$originalFileName = $file->getClientName();
									// $file->store($targetDir, $originalFileName);
									$file->move($targetDir, $originalFileName);

									$url = 'inspecciones' . DIRECTORY_SEPARATOR . 'etiquetas' . DIRECTORY_SEPARATOR . $originalFileName;

								} else {
										$validationErrors[] = [
												'file' => $file->getName(),
												'error' => $file->getErrorString()
										];
										// continue;

										return $this->response->setStatusCode(400)->setJSON(['error' => 'Upload failed']);
								}
						}

						if (!empty($validationErrors)) {
							return $this->respond([
										'message' => $validationErrors,
								], ResponseInterface::HTTP_BAD_REQUEST);
						} else {
								return $this->respond([
										'message' => "File created successfully.",
										'url' => $url,
								], ResponseInterface::HTTP_OK);
						}
					
					}

					if ($tipo == "certif") {

						$File = new InspeccionFilesModel;

						$inspeccionId = $this->request->getPost('inspeccionId');

						$targetDir = WRITEPATH . 'storage/inspecciones/' . $inspeccionId;

						if (!is_dir($targetDir)) {
								mkdir($targetDir, 0755, true);
						}

						$file = $this->request->getFile('certif');

						$validationErrors = [];
						$url = '';
						
						if ($file && $file->isValid()) {

								$allowedMimeTypes = ['image/png', 'image/jpeg', 'image/jpg'];
								$maxSize = 1024 * 1024 * 8; // 8MB in bytes

								if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
										$validationErrors[] = [
												'file' => $file->getName(),
												'error' => 'Invalid file type'
										];
										// continue;
								}

								if ($file->getSize() > $maxSize) {
										$validationErrors[] = [
												'file' => $file->getName(),
												'error' => 'File exceeds maximum size of 8MB'
										];
										// continue;
								}

								if ($file->isValid() && !$file->hasMoved()) {

										$originalFileName = $file->getClientName();
										$file->move($targetDir, $originalFileName);

										$url = 'inspecciones' . DIRECTORY_SEPARATOR . $inspeccionId . DIRECTORY_SEPARATOR . $originalFileName;

										$data_file = [
												'userId' => $this->session->get('user')['id'],
												'inspeccionId' => $inspeccionId,
												'archivo' => 'inspecciones' . DIRECTORY_SEPARATOR . $inspeccionId . DIRECTORY_SEPARATOR . $originalFileName,
										];

										$File->insert($data_file);

								} else {
										$validationErrors[] = [
												'file' => $file->getName(),
												'error' => $file->getErrorString()
										];
										// continue;

										return $this->response->setStatusCode(400)->setJSON(['error' => 'Upload failed']);
								}


						}

						if (!empty($validationErrors)) {
							return $this->respond([
										'message' => $validationErrors,
								], ResponseInterface::HTTP_BAD_REQUEST);
						} else {
								return $this->respond([
										'message' => "File created successfully.",
										'url' => $url,
								], ResponseInterface::HTTP_OK);
						}
						

					}
					
					
			}
		}





}
