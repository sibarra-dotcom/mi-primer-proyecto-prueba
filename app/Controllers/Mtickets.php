<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Models\UserModel;
use App\Models\Cotizacion;
use App\Models\CotizArchivo;
use App\Models\CotizDetalle;
use App\Models\ArticuloComm;
use App\Models\MaquinariaModel;
use App\Models\MantenimientoModel;
use App\Models\MantAdjunto;
use App\Models\FormatosDocsModel;

class Mtickets extends BaseController
{
    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();
    }

		public function print($ticketId = null)
    {
        if ($this->request->getMethod() === 'GET')
        {

						$FormatoDocs = new FormatosDocsModel;
						$User = new UserModel();

						$formato = $FormatoDocs->getCurrentFormatoBySlug("informacion_falla");

						$users_mt = $User->where('rol_id', 6)->get()->getResultArray();
						$jefes_mt = $User->where('rol_id', 7)->get()->getResultArray();

						$responsables = [];

						foreach ($users_mt as $user) {
							$responsables[$user['id']] = $user['name'].' '.$user['last_name'];
						}

						foreach ($jefes_mt as $user_jefe) {
							$responsables[$user_jefe['id']] = $user_jefe['name'].' '.$user_jefe['last_name'];
						}

						$data['responsables'] = $responsables;
						$data['ticketId'] = $ticketId;

						$data['title'] = "Reporte de Ticket de Mantenimiento " . $ticketId;

						$data['formato'] = $formato;

						// echo "<pre>";
						// print_r($data);
						// exit;
	
						return view("mtickets/print", $data);
        }
    }

    public function index()
    {
        if ($this->request->getMethod() === 'GET')
        {
					$maq = new MaquinariaModel();
					$user = new UserModel();
					
					$data['plantas'] = $maq->getPlantas();
					$data['users_mt'] = $user->where('rol_id', 6)->get()->getResultArray();
					$data['jefes_mt'] = $user->where('rol_id', 7)->get()->getResultArray();
					// cambiar a email jefe mantenimiento
					// $data['jefe'] = $user->where('email', 'mt_jefe@gibanibb.com')->first();
					$data['jefe'] = $user->where('rol_id', 7)->first();
					$data['signature'] = $user->where('id', $this->session->get('user')['id'])->first()['signature'];

					$data['title'] = 'Tickets de Mantenimiento';

						// 					echo "<pre>";
						// print_r($data);
						// exit;

					return view('mtickets/index', $data);
        }
    }

		public function get_by_daterange(string $startDate, string $endDate)
		{
				if ($this->request->getMethod() !== 'GET') {
						return $this->response->setStatusCode(405);
				}

				$Mantenimiento = new MantenimientoModel();
				$records = $Mantenimiento->getMticketsByDateRange($startDate, $endDate);

				$spreadsheet = new Spreadsheet();
				$sheet = $spreadsheet->getActiveSheet();

				/* =======================
				* EXCEL HEADERS
				* ======================= */
				$headers = [
						'A1' => 'MAQUINA',
						'B1' => 'RESPONSABLE',
						'C1' => 'SOLICITANTE',
						'D1' => 'PRIORIDAD',
						'E1' => 'ASUNTO',
						'F1' => 'DESCRIPCIÓN',
						'G1' => 'ESTADO DE MÁQUINA',
						'H1' => 'DIAGNÓSTICO',
						'I1' => 'REPARACIÓN DETALLE',
						'J1' => 'FECHA CREACIÓN',
						'K1' => 'FECHA INICIO DE REPARACIÓN',
						'L1' => 'FECHA DE CIERRE',
						'M1' => 'CAMBIO DE PIEZA',
						'N1' => 'COMPRA DE PIEZA',
						'O1' => 'NOTA INVENTARIO',
						'P1' => 'TIEMPO MUERTO',
						'Q1' => 'PLANTA',
				];

				foreach ($headers as $cell => $text) {
						$sheet->setCellValue($cell, $text);
				}

				/* =======================
				* DATA ROWS
				* ======================= */
				$row = 2;
				foreach ($records as $record) {

						$sheet->setCellValue('A' . $row, $record['maquina']);
						$sheet->setCellValue('B' . $row, $record['responsable']);
						$sheet->setCellValue('C' . $row, $record['solicitante']);
						$sheet->setCellValue('D' . $row, $record['prioridad']);
						$sheet->setCellValue('E' . $row, $record['asunto']);
						$sheet->setCellValue('F' . $row, $record['descripcion']);
						$sheet->setCellValue('G' . $row, $record['estado_maq']);
						$sheet->setCellValue('H' . $row, $record['diagnostico']);
						$sheet->setCellValue('I' . $row, $record['reparacion_detalle']);
						$sheet->setCellValue('J' . $row, $record['fecha_creacion']);
						$sheet->setCellValue('K' . $row, $record['fecha_inicio_reparacion']);
						$sheet->setCellValue('L' . $row, $record['fecha_cierre']);
						$sheet->setCellValue('M' . $row, $record['cambio_pieza']);
						$sheet->setCellValue('N' . $row, $record['compra_pieza']);
						$sheet->setCellValue('O' . $row, $record['nota_inventario']);
						$sheet->setCellValue('P' . $row, $record['tiempo_muerto']);
						$sheet->setCellValue('Q' . $row, $record['planta']);

						$row++;
				}

				/* =======================
				* AUTO-SIZE COLUMNS
				* ======================= */
				foreach (range('A', 'Q') as $column) {

						// Skip columns H and I
						if (in_array($column, ['H', 'I'])) {
								continue;
						}

						$sheet->getColumnDimension($column)->setAutoSize(true);
				}

				/* =======================
				* FIXED WIDTH FOR H & I
				* ======================= */
				$sheet->getColumnDimension('H')->setWidth(30);
				$sheet->getColumnDimension('I')->setWidth(55);

				/* =======================
				* FILTERS & WRAP
				* ======================= */
				$sheet->setAutoFilter('A1:Q1');

				// Wrap text for long-text columns
				$sheet->getStyle('F:F')->getAlignment()->setWrapText(true);
				$sheet->getStyle('H:H')->getAlignment()->setWrapText(true);
				$sheet->getStyle('I:I')->getAlignment()->setWrapText(true);

				/* =======================
				* FILE NAME (DD-MM-YYYY)
				* ======================= */
				$start = date('d-m-Y', strtotime($startDate));
				$end   = date('d-m-Y', strtotime($endDate));

				$filename = "reporte_mantenimiento_{$start}_al_{$end}.xlsx";

				/* =======================
				* OUTPUT
				* ======================= */
				$writer = new Xlsx($spreadsheet);

				return $this->response
						->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
						->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
						->setHeader('Cache-Control', 'max-age=0')
						->setBody($writer->save('php://output'));
		}

}
