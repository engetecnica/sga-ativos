<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Veículos Disponíveis');


/* Sheet With Cell's */
$sheet->setCellValue('A2', '#');
$sheet->setCellValue('B2', 'Placa');
$sheet->setCellValue('C2', 'Tipo');
$sheet->setCellValue('D2', 'Marca/Modelo');
$sheet->setCellValue('E2', 'KM');
$sheet->setCellValue('F2', 'Situação');

$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Paid');
$drawing->setDescription('Paid');
$drawing->setPath('assets/images/icon/logo.png'); // put your path and image here
$drawing->setCoordinates('A1');
$drawing->setOffsetX(10);
$drawing->setOffsetY(10);
$drawing->setWorksheet($spreadsheet->getActiveSheet());

$writer = new Xlsx($spreadsheet);

$sheet
    ->getRowDimension('1')
    ->setRowHeight(70, 'pt');

    $sheet
    ->getRowDimension('2')
    ->setRowHeight(30, 'pt');    

$sheet->rows = 3;
foreach($data as $i=>$row){

    $situacao = $this->get_situacao($row->situacao);

    $sheet->setCellValue("A{$sheet->rows}", $i+1);
    $sheet->setCellValue("B{$sheet->rows}", $row->veiculo_placa);
    $sheet->setCellValue("C{$sheet->rows}", ucfirst($row->tipo_veiculo));
    $sheet->setCellValue("D{$sheet->rows}", isset($row->marca) ? "{$row->marca} - {$row->modelo}" : '-');
    $sheet->setCellValue("E{$sheet->rows}", $row->veiculo_km);
    $sheet->setCellValue("F{$sheet->rows}", $situacao['texto']);
    $sheet->getRowDimension($sheet->rows)->setRowHeight(20, 'pt');

    $sheet->rows++;
}


$sheet->getStyle("A2:F2")
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB("CCCCCC");

foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$sheet->getStyle("A2:F2")
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("A2:F2")
    ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);




return true;