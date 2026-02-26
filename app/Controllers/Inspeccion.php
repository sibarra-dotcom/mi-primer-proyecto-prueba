<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\Cotizacion;
use App\Models\CotizArchivo;
use App\Models\CotizDetalle;
use App\Models\ArticuloComm;
use App\Models\MaquinariaModel;

use App\Models\InspeccionFilesModel;
use App\Models\InspeccionFormatoModel;
use App\Models\InspeccionItemModel;
use App\Models\InspeccionModel;
use App\Models\InspeccionRegistroModel;
use App\Models\InspeccionSectionModel;
use App\Models\InspeccionRegEspecModel;

use App\Models\MantenimientoModel;
use App\Models\MantAdjunto;
use App\Models\EmailQueueModel;


class Inspeccion extends BaseController
{
    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();
    }

    public function init($format)
    {
        if ($this->request->getMethod() === 'GET')
        {
						$data['title'] = formatInspTitle('INSPECCIÓN ' . $format);
						return view("inspeccion/init/$format", $data);
				}
		}


		public function lista($slug = null)
    {
        if ($this->request->getMethod() === 'GET')
        {
					if ($slug) {
						$Formato = new InspeccionFormatoModel;
						$Section = new InspeccionSectionModel;

						// $formato = $Formato->where('slug', $slug)->first();

						$formato = $Formato
								->where('slug', $slug)
								->where('vigencia >= NOW()', null, false)
								->orderBy('vigencia', 'DESC')
								->first();

						if (!$formato) {
								return $this->response->setStatusCode(400)->setJSON([
										'success' => false,
										'error' => 'Formato no válido o fuera de vigencia',
										'slug' => $slug
								]);
						}

						$data['title'] = formatInspTitle('Lista INSPECCIÓN ' .  $slug);
						$data['formatoId'] = $formato['id'];
						$data['formatoSlug'] = $formato['slug'];

						return view("inspeccion/$slug/lista", $data);
					}
				}
		}

		public function upload_sap($inspeccionId)
    {
        if ($this->request->getMethod() === 'GET')
        {
						$Inspeccion = new InspeccionModel();

						$inspeccion = $Inspeccion->find($inspeccionId);

						if (!$inspeccion) {
								return $this->response->setJSON(['success' => false, 'msg' => "inspeccionID invalid"]);
						}

						$newStatus = ($inspeccion['upload_SAP'] == 1) ? 0 : 1;

						$Inspeccion->update($inspeccionId, ['upload_SAP' => $newStatus]);

						return $this->response->setJSON(['success' => true, 'upload_SAP' => $newStatus]);
				}
		}


		public function search_insp_materias($formatoId)
    {
        if ($this->request->getMethod() === 'POST')
        {
					// echo "<pre>";
					// 	print_r($_POST);	
					// exit;


						$Inspeccion = new InspeccionModel();		

						$Formato = new InspeccionFormatoModel;
						$formato = $Formato->find($formatoId);

						$searchParams = $this->request->getPost();

						if ($formato['slug'] === 'materias-primas') {
								$response = $Inspeccion->getInspeccionesBySlug($formato['slug'], $searchParams);
						} elseif ($formato['slug'] === 'materiales') {
								$response = $Inspeccion->getInspeccionesBySlug($formato['slug'], $searchParams);
						} else {
								$response = [];
						}

						return $this->response->setJSON($response);
				}
		}

    public function print($slug = null, $inspId = null)
    {
        if ($this->request->getMethod() === 'GET')
        {
						if ($slug && $inspId) {

							$Inspeccion = new InspeccionModel;
							$InspRegSpec = new InspeccionRegEspecModel;

							$Formato = new InspeccionFormatoModel;
							$Section = new InspeccionSectionModel;
							$Registro = new InspeccionRegistroModel;
							$user = new UserModel;

							$_insp = $Inspeccion->where('id', $inspId)->first();

							$formato = $Formato->where('id', $_insp['formatoId'])->first();

            
							$data['inspId'] = $inspId;
							$data['slug'] = $slug;

							$data['title'] = formatInspTitle("INSPECCIÓN $slug - n° " . format_id($inspId, "id"));

							$data['formato'] = $formato;

							$data['registros'] = $Registro->where('inspeccionId', $inspId)->findAll();
							
							$data['detalles'] = $InspRegSpec->where('inspeccionId', $inspId)->findAll();

							$data['almacen'] = $user->where('rol_id', 10)->get()->getResultArray();

							$data['items'] = $Section->getSections($formato['id'], ['datos-generales', 'etiqueta', 'adjunto']);

							$data['general'] = $Section->getSectionByTitle($formato['id'],'datos-generales');

							$data['etiqueta'] = $Section->getSectionByTitle($formato['id'],'etiqueta')[0];

							$data['adjunto'] = $Section->getSectionByTitle($formato['id'],'adjunto')[0];


							if($slug == 'materiales'){

								$data['secciones'] = [];

								foreach ($data['items'] as $item) {
										$sectionNumber = (int) $item['section_number'];

										if (!isset($data['secciones'][$sectionNumber])) {
												$data['secciones'][$sectionNumber] = [
														'title' => $item['titulo'],
														'items' => []
												];
										}

										$data['secciones'][$sectionNumber]['items'][] = $item;
								}

								ksort($data['secciones'], SORT_NUMERIC);

								$page1Sections = [];
								$page2Sections = [];

								// section_number 2 == 1. CONDICIONES DE VEHICULO
								// page separation for inspeccion de materiales
								foreach ($data['secciones'] as $sectionNumber => $sectionData) {
										if ($sectionNumber >= 1 && $sectionNumber <= 5) {
												$page1Sections[$sectionNumber] = $sectionData;
										} elseif ($sectionNumber >= 6 && $sectionNumber <= 12) {
												$page2Sections[$sectionNumber] = $sectionData;
										}
								}

								$data['page1Sections'] = $page1Sections;
								$data['page2Sections'] = $page2Sections;

								$data['items_espec_clear'] = ["4.1", "4.2", "4.3", "4.4", "4.4.1", "4.4.2", "4.4.3", "4.4.4", "4.4.5", "4.4.6", "4.5", "4.6", "4.7", "5.1", "5.2", "5.3", "5.3.1", "6.1", "6.2", "6.2.1", "6.2.2", "7.1", "8.1", "9.1", "9.2"];


								$data['items_espec_page1'] = ["4.4.1", "4.4.2", "4.4.3", "4.4.4", "4.4.5", "4.4.6", "4.5", "4.6", "4.7"];
								$data['items_espec_page2'] = ["5.3.1", "6.2.1", "6.2.2", "7.1", "8.1", "9.1", "9.2"];

								$data['single_resultado'] = ["4.6", "4.7", "8.1"];
								$data['items_checkbox'] = ["5.3.1", "6.2.1", "6.2.2", "7.1", "9.1", "9.2"];

								$generalItems = [];

								foreach ($data['general'] as $item) {
										$itemId = $item['id'];
										$observacion = '';

										// Find saved observacion by itemId
										foreach ($data['registros'] as $saved) {
												if ($saved['itemId'] == $itemId) {
														$observacion = $saved['observacion'] ?? '';
														break;
												}
										}

										$generalItems[] = [
												'itemId' => $itemId,
												'description' => $item['description'],
												'observacion' => $observacion,
										];
								}

								$data['generalItems'] = $generalItems;


							} else if($slug == 'materias-primas'){

								$data['secciones'] = [];

								foreach ($data['items'] as $item) {
									$data['secciones'][$item['titulo']][] = $item;
								}

								$generalItems = [];

								foreach ($data['general'] as $item) {
										$itemId = $item['id'];
										$observacion = '';

										// Find saved observacion by itemId
										foreach ($data['registros'] as $saved) {
												if ($saved['itemId'] == $itemId) {
														$observacion = $saved['observacion'] ?? '';
														break;
												}
										}

										$generalItems[] = [
												'itemId' => $itemId,
												'description' => $item['description'],
												'observacion' => $observacion,
										];
								}

								$data['generalItems'] = $generalItems;

							}



								// echo "<pre>";
								// print_r($data);
								// exit;
		
							return view("inspeccion/$slug/print/index", $data);
						} 
        }


    }


		public function index()
    {
        if ($this->request->getMethod() === 'GET')
        {
					$data['title'] = 'Inspecciones';
					return view('inspeccion/index', $data);
				}
		}

		private function getCurrentFormato($slug = null)
    {
			$Formato = new InspeccionFormatoModel;

			$formato = $Formato
					->where('slug', $slug)
					->where('vigencia >= NOW()', null, false)
					->orderBy('vigencia', 'DESC')
					->first();

			return $formato;
		}





    public function create($slug = null)
    {
        if ($this->request->getMethod() === 'GET')
        {
						if ($slug) {
							$Formato = new InspeccionFormatoModel;
							$Section = new InspeccionSectionModel;
							$user = new UserModel;

							// $formato = $Formato->where('slug', $slug)->first();
							$formato = $this->getCurrentFormato($slug);
            
							$data['inspId'] = "";
							$data['slug'] = $slug;

							$data['title'] = formatInspTitle('INSPECCIÓN ' .  $slug);
							$data['formato'] = $formato;


							$data['almacen'] = $user->where('rol_id', 10)->get()->getResultArray();

							$data['items'] = $Section->getSections($formato['id'], ['datos-generales', 'etiqueta', 'adjunto']);

							$data['general'] = $Section->getSectionByTitle($formato['id'],'datos-generales');

							$data['etiqueta'] = $Section->getSectionByTitle($formato['id'],'etiqueta')[0];

							$data['adjunto'] = $Section->getSectionByTitle($formato['id'],'adjunto')[0];

							$data['secciones'] = [];

							foreach ($data['items'] as $item) {
									$sectionNumber = (int) $item['section_number'];

									if (!isset($data['secciones'][$sectionNumber])) {
											$data['secciones'][$sectionNumber] = [
													'title' => $item['titulo'],
													'items' => []
											];
									}

									$data['secciones'][$sectionNumber]['items'][] = $item;
							}

							ksort($data['secciones'], SORT_NUMERIC);

							$page1Sections = [];
							$page2Sections = [];

							// section_number 2 == 1. CONDICIONES DE VEHICULO
							// page separation for inspeccion de materiales
							foreach ($data['secciones'] as $sectionNumber => $sectionData) {
									if ($sectionNumber >= 1 && $sectionNumber <= 5) {
											$page1Sections[$sectionNumber] = $sectionData;
									} elseif ($sectionNumber >= 6 && $sectionNumber <= 12) {
											$page2Sections[$sectionNumber] = $sectionData;
									}
							}

							$data['page1Sections'] = $page1Sections;
							$data['page2Sections'] = $page2Sections;

							$data['items_espec_clear'] = ["4.1", "4.2", "4.3", "4.4", "4.4.1", "4.4.2", "4.4.3", "4.4.4", "4.4.5", "4.4.6", "4.5", "4.6", "4.7", "5.1", "5.2", "5.3", "5.3.1", "6.1", "6.2", "6.2.1", "6.2.2", "7.1", "8.1", "9.1", "9.2"];


							$data['items_espec_page1'] = ["4.4.1", "4.4.2", "4.4.3", "4.4.4", "4.4.5", "4.4.6", "4.5", "4.6", "4.7"];
							$data['items_espec_page2'] = ["5.3.1", "6.2.1", "6.2.2", "7.1", "8.1", "9.1", "9.2"];

							$data['single_resultado'] = ["4.6", "4.7", "8.1"];
							$data['items_checkbox'] = ["5.3.1", "6.2.1", "6.2.2", "7.1", "9.1", "9.2"];


								// echo "<pre>";
								// print_r($data['page1Sections']);
								// exit;

							return view("inspeccion/$slug/create", $data);


							// return $this->failValidationErrors('mant_id is required.');
						} else {
							$data['title'] = 'Inspecciones';
							return view('inspeccion/index', $data);
						}
        }
    }


    public function details($slug = null, $inspId = null)
    {
        if ($this->request->getMethod() === 'GET')
        {
						if ($slug && $inspId) {
							$Inspeccion = new InspeccionModel;
							$Formato = new InspeccionFormatoModel;
							$Section = new InspeccionSectionModel;
							$Registro = new InspeccionRegistroModel;
							$user = new UserModel;

							$_insp = $Inspeccion->where('id', $inspId)->first();

							$formato = $Formato->where('id', $_insp['formatoId'])->first();

							// $formato = $this->getCurrentFormato($slug);

							
            
							$data['inspId'] = $inspId;
							$data['slug'] = $slug;

							$data['title'] = formatInspTitle("INSPECCIÓN $slug - n° " . format_id($inspId, "id"));

							$data['formato'] = $formato;

							$data['registros'] = $Registro->where('inspeccionId', $inspId)->findAll();

							$data['almacen'] = $user->where('rol_id', 10)->get()->getResultArray();

							$data['items'] = $Section->getSections($formato['id'], ['datos-generales', 'etiqueta', 'adjunto']);

							$data['general'] = $Section->getSectionByTitle($formato['id'],'datos-generales');

							$data['etiqueta'] = $Section->getSectionByTitle($formato['id'],'etiqueta')[0];

							$data['adjunto'] = $Section->getSectionByTitle($formato['id'],'adjunto')[0];

							$data['secciones'] = [];

							foreach ($data['items'] as $item) {
								$data['secciones'][$item['titulo']][] = $item;
							}


							$generalItems = [];

							foreach ($data['general'] as $item) {
									$itemId = $item['id'];
									$observacion = '';

									// Find saved observacion by itemId
									foreach ($data['registros'] as $saved) {
											if ($saved['itemId'] == $itemId) {
													$observacion = $saved['observacion'] ?? '';
													break;
											}
									}

									$generalItems[] = [
											'itemId' => $itemId,
											'description' => $item['description'],
											'observacion' => $observacion,
									];
							}

							$data['generalItems'] = $generalItems;


							// $this->session->setFlashdata('msg', 'Cotización creada');
							// $this->session->setFlashdata('msg_error', 'Cotización creada');
							
								// echo "<pre>";
								// print_r($data);
								// exit;
		
							return view("inspeccion/$slug/details", $data);


							// return $this->failValidationErrors('mant_id is required.');
						} 
        }


    }


    public function update($slug = null, $inspId = null)
    {
        if ($this->request->getMethod() === 'GET')
        {
						if (!hasRole('calidad') && !hasRole('admin')) {
								return redirect()->to('inspeccion/lista/materias-primas');
						}

						if ($slug && $inspId) {
							$Inspeccion = new InspeccionModel;


							$Formato = new InspeccionFormatoModel;
							$Section = new InspeccionSectionModel;
							$Registro = new InspeccionRegistroModel;
							$user = new UserModel;

							$_insp = $Inspeccion->where('id', $inspId)->first();

							$formato = $Formato->where('id', $_insp['formatoId'])->first();

							// $formato = $Formato->where('slug', $slug)->first();
            
							$data['inspId'] = $inspId;
							$data['slug'] = $slug;

							$data['title'] = formatInspTitle("INSPECCIÓN $slug - n° " . format_id($inspId, "id"));

							$data['formato'] = $formato;

							$data['registros'] = $Registro->where('inspeccionId', $inspId)->findAll();

							$data['almacen'] = $user->where('rol_id', 10)->get()->getResultArray();

							$data['items'] = $Section->getSections($formato['id'], ['datos-generales', 'etiqueta', 'adjunto']);

							$data['general'] = $Section->getSectionByTitle($formato['id'],'datos-generales');

							$data['etiqueta'] = $Section->getSectionByTitle($formato['id'],'etiqueta')[0];

							$data['etiqueta_row'] = $Section->getSectionByTitle($formato['id'],'etiqueta');

							$data['adjunto'] = $Section->getSectionByTitle($formato['id'],'adjunto')[0];

							$data['secciones'] = [];

							foreach ($data['items'] as $item) {
								$data['secciones'][$item['titulo']][] = $item;
							}




							$generalItems = [];

							foreach ($data['general'] as $item) {
									$itemId = $item['id'];
									$observacion = '';

									// Find saved observacion by itemId
									foreach ($data['registros'] as $saved) {
											if ($saved['itemId'] == $itemId) {
													$observacion = $saved['observacion'] ?? '';
													$id = $saved['id'] ?? '';
													break;
											}
									}

									$generalItems[] = [
											'itemId' => $itemId,
											'description' => $item['description'],
											'observacion' => $observacion,
											'id' => $id
									];
							}

							$data['generalItems'] = $generalItems;


							$etiq = [];

							foreach ($data['etiqueta_row'] as $item) {
									$itemId = $item['id'];
									$observacion = '';

									// Find saved observacion by itemId
									foreach ($data['registros'] as $saved) {
											if ($saved['itemId'] == $itemId) {
													$observacion = $saved['observacion'] ?? '';
													$id = $saved['id'] ?? '';
													$aprobado = $saved['aprobado'] ?? '';
													break;
											}
									}

									$etiq[] = [
											'itemId' => $itemId,
											'aprobado' => $aprobado,
											'description' => $item['description'],
											'observacion' => $observacion,
											'id' => $id
									];
							}

							$data['etiq'] = $etiq[0];



							// $this->session->setFlashdata('msg', 'Cotización creada');
							// $this->session->setFlashdata('msg_error', 'Cotización creada');
							
								// echo "<pre>";
								// print_r($data['etiqueta_row'][0]);
								// print_r($data['generalItems']);
								// exit;
		
							return view("inspeccion/$slug/update", $data);


							// return $this->failValidationErrors('mant_id is required.');
						} 
        }


    }



		public function mt_delete($id, $type = null)
    {
        if ($this->request->getMethod() === 'GET')
        {
					if (!$type) {
						$Model = new InspeccionModel();
						$row = $Model->find($id);
					} 

					if ($type && $type == "file"){
						$Model = new InspeccionRegistroModel();
						$row = $Model->find($id);
					}

					if ($row) {
						$deleted = $Model->delete($id);
						if($deleted) {
							$res = [
								'success' => true,
								'redirect' => '/success-page',
								'id' => $id,
								'type' => $type,
							];
						}
					 }

					return $this->response->setJSON($res);
					exit;

				}
					
		}

		public function materiales_delete($id, $type = null)
    {
        if ($this->request->getMethod() === 'GET')
        {
					if (!$type) {
						$Model = new InspeccionModel();
						$row = $Model->find($id);
					} 

					// if ($type && $type == "file"){
					// 	$Model = new InspeccionRegistroModel();
					// 	$row = $Model->find($id);
					// }

					if ($row) {
						$deleted = $Model->delete($id);
						if($deleted) {
							$res = [
								'success' => true,
								'redirect' => '/success-page',
								'id' => $id,
								'type' => $type,
							];
						}
					 }

					return $this->response->setJSON($res);
					exit;

				}
					
		}

    public function materias_primas()
    {
				if ($this->request->getMethod() === 'GET')
				{
						return redirect()->to('inspeccion');
				}

        if ($this->request->getMethod() === 'POST')
        {
            $Registro = new InspeccionRegistroModel();
            $Inspeccion = new InspeccionModel();
						$Formato = new InspeccionFormatoModel;

            // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);
            // exit;

						$slug = $this->request->getPost('slug');

						$formato = $this->getCurrentFormato($slug);
						$formatoId = $formato['id'];

						$inspId = $this->request->getPost('inspeccionId') ?? null;

						$inspeccionId = null;

						if($inspId) {
							$inspeccionId = $inspId;
						} else {
							$_data = [
									'userId' => $this->session->get('user')['id'],
									'formatoId' => $formatoId,
							];

							$inspeccionId = $Inspeccion->insert($_data);
						}

						$items = $this->request->getPost('items');
				
						foreach ($items as $item) {
								if (!isset($item['itemId'], $item['aprobado'])) {
										continue;
								}
				
								$Registro->insert([
										'inspeccionId' => $inspeccionId,
										'itemId'       => $item['itemId'],
										'aprobado'     => $item['aprobado'],
										'observacion'  => $item['observacion'] ?? null,
								]);
						}

						return $this->response->setJSON(['success' => true, 'inspeccionId' => $inspeccionId]);
        }
    }

		public function materias_primasu()
    {

        if ($this->request->getMethod() === 'POST')
        {
            $Registro = new InspeccionRegistroModel();
            $Inspeccion = new InspeccionModel();
						$Formato = new InspeccionFormatoModel;

            // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);
            // exit;


						$inspeccionId = $this->request->getPost('inspeccionId') ?? null;


						$items = $this->request->getPost('general_items');

						foreach ($items as $item) {
							if (isset($item['id'], $item['observacion'])) {
								$Registro->update($item['id'], ['observacion' => $item['observacion']]);
							} else {
								
							}
						}


						// $items = $this->request->getPost('general_items');
						// $Registro = new InspeccionRegistroModel();
				
						// foreach ($items as $item) {
						// 		if (!empty($item['id'])) {
						// 				$Registro->update($item['id'], [
						// 						'observacion' => $item['observacion'] ?? null,
						// 						// 'aprobado' => $item['aprobado'] ?? null,
						// 						// Add other fields here if needed
						// 				]);
						// 		}
						// }


						$etiqueta = $this->request->getPost('etiqueta');

						if (!empty($etiqueta['id']  && !empty($etiqueta['observacion']))) {
								$Registro->update($etiqueta['id'], [
										'observacion' => $etiqueta['observacion'] ?? null,
										'aprobado' => $etiqueta['aprobado'] ?? null,
								]);
						}
				
						return $this->response->setJSON(['success' => true, 'inspeccionId' => $inspeccionId]);
        }
    }


				// findAll(limit, offset)
		public function get_lista_insp($formatoSlug)
    {
				$Inspeccion = new InspeccionModel();
				$Formato = new InspeccionFormatoModel;

				if ($formatoSlug === 'materias-primas') {
						$response = $Inspeccion->getInspeccionesBySlug($formatoSlug, []);
				} elseif ($formatoSlug === 'materiales') {
						$response = $Inspeccion->getInspeccionesBySlug($formatoSlug, []);

						// $response = $Inspeccion->getMaterialesBySlug($formato['slug'], []);
				} else {
						$response = [];
				}

				return $this->response->setJSON($response);
        // return $this->respond($response, ResponseInterface::HTTP_OK);
    }

    public function materiales()
    {
				if ($this->request->getMethod() === 'GET')
				{
						return redirect()->to('inspeccion');
				}

        if ($this->request->getMethod() === 'POST')
        {
            $Registro = new InspeccionRegistroModel();
            $Inspeccion = new InspeccionModel();
						$Formato = new InspeccionFormatoModel;
						$InspRegEspec = new InspeccionRegEspecModel;

            // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);
            // exit;

						$slug = $this->request->getPost('slug');

						$formato = $Formato
								->where('slug', $slug)
								->where('vigencia >= NOW()', null, false)
								->orderBy('vigencia', 'DESC')
								->first();

						if (!$formato) {
								return $this->response->setStatusCode(400)->setJSON([
										'success' => false,
										'error' => 'Formato no válido o fuera de vigencia',
										'slug' => $slug
								]);
						}

						$formatoId = $formato['id'];

						$inspId = $this->request->getPost('inspeccionId') ?? null;

						$inspeccionId = null;

						if($inspId) {
							$inspeccionId = $inspId;
						} else {
							$_data = [
									'userId' => $this->session->get('user')['id'],
									'formatoId' => $formatoId,
							];

							$inspeccionId = $Inspeccion->insert($_data);
						}

						$items = $this->request->getPost('items');
				
						foreach ($items as $item) {
								if (!isset($item['itemId'], $item['aprobado'])) {
										continue;
								}
				
								$Registro->insert([
										'inspeccionId' => $inspeccionId,
										'itemId'       => $item['itemId'],
										'aprobado'     => $item['aprobado'],
										'observacion'  => $item['observacion'] ?? null,
								]);
						}

						$detalles = $this->request->getPost('detalles');

						$especs = $detalles['espec'] ?? [];
						$resultados = $detalles['resultados'] ?? [];

						foreach ($especs as $itemId => $especificacion) {

								$resultadoArray = $resultados[$itemId] ?? [];

								$data = [
										'inspeccionId'    => $inspeccionId,
										'itemId'          => $itemId,
										'especificacion'  => $especificacion,
										'resultado'       => json_encode($resultadoArray)
								];

								$InspRegEspec->insert($data);
						}

						return $this->response->setJSON(['success' => true, 'inspeccionId' => $inspeccionId]);
        }
    }

}