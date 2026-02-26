<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Models\ComedorEntregaModel; 
use App\Models\SorteoEntregaModel; 
use App\Models\SorteoPeriodoModel; 

class Export extends Controller
{
    public function comedor_entregas()
    {
        $date = $this->request->getGet('date') ?: date('Y-m-d');
        $ComedorEntregaModel = new ComedorEntregaModel();

        $records = $ComedorEntregaModel->where('DATE(fecha)', $date)->orderBy('id', 'DESC')->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', '# PEDIDO')
              ->setCellValue('B1', 'FECHA Y HORA')
              ->setCellValue('C1', 'NOMBRE')
              ->setCellValue('D1', 'MENU DEL DIA')
              ->setCellValue('E1', 'MENU BASE')
              ->setCellValue('F1', 'ESPECIFICACIONES')
              ->setCellValue('G1', 'HORARIO')
              ->setCellValue('H1', 'FIRMA');

        $row = 2;
				$counter = 1;
        foreach ($records as $record) {
						$sheet->setCellValue('A' . $row, $counter);
            $sheet->setCellValue('B' . $row, $record['fecha']);
            $sheet->setCellValue('C' . $row, $record['nombre']);
            $sheet->setCellValue('D' . $row, $record['menu_dia']);
            $sheet->setCellValue('E' . $row, $record['menu_base']);
            $sheet->setCellValue('F' . $row, $record['observacion']);
            $sheet->setCellValue('G' . $row, $record['horario']);
						$counter++;
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        $filename = 'comedor_' . $date . '.xlsx';
        return $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                             ->setHeader('Content-Disposition', 'attachment;filename="' . $filename . '"')
                             ->setHeader('Cache-Control', 'max-age=0')
                             ->setBody($writer->save('php://output')); 
    }

    public function sorteo_entregas(string $type = null)
    {
				$SorteoEntregaModel = new SorteoEntregaModel();

				if ($type && $type == "all") {
					$records = $SorteoEntregaModel->getLista();

					$date = \DateTime::createFromFormat('Y-m-d', date('Y-m-d'));	
					$formatted = $date->format('d-m-Y');
					$tag = "hasta_" . $formatted;

				} else {
					$SortPeriod = new SorteoPeriodoModel();
					$periodo_id = $SortPeriod->getCurrentPeriodId();
					$current_period = $SortPeriod->where('id', $periodo_id)->first();
					$records = $SorteoEntregaModel->getListaByPeriod($current_period);

					$date_start = \DateTime::createFromFormat('Y-m-d', $current_period['fecha_ingreso']);	
					$formatted_start = $date_start->format('d-m-Y');

					$date_end = \DateTime::createFromFormat('Y-m-d', $current_period['fecha_corte']);	
					$formatted_end = $date_end->format('d-m-Y');

					$tag = "de_" . $formatted_start . "_a_" . $formatted_end;
				}

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', '# ENTREGA')
              ->setCellValue('B1', 'NOMBRE')
              ->setCellValue('C1', 'CARGO')
              ->setCellValue('D1', 'SEDE - TURNO')
              ->setCellValue('E1', 'PRODUCTO')
              ->setCellValue('F1', 'CODIGO')
              ->setCellValue('G1', 'LOTE')
              ->setCellValue('H1', 'FECHA ENTREGA');

        $row = 2;
				$counter = 1;
        foreach ($records as $record) {
						$sheet->setCellValue('A' . $row, $counter);
            $sheet->setCellValue('B' . $row, $record['name'] . " "  . $record['last_name']);
            $sheet->setCellValue('C' . $row, $record['rol']);
            $sheet->setCellValue('D' . $row, $record['turno']);
            $sheet->setCellValue('E' . $row, $record['descripcion']);
            $sheet->setCellValue('F' . $row, $record['codigo']);
            $sheet->setCellValue('G' . $row, $record['lote']);
						$sheet->setCellValue('H' . $row, $record['created_at']);
						$counter++;
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        $filename = 'sorteo_entrega_' . $tag . '.xlsx';
        return $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                             ->setHeader('Content-Disposition', 'attachment;filename="' . $filename . '"')
                             ->setHeader('Cache-Control', 'max-age=0')
                             ->setBody($writer->save('php://output')); 
    }



}