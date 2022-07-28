<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Estoque Geral');

foreach($data as $i => $obra) {

    if($i >= 1) {
        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $obra->codigo_obra);
        $spreadsheet->addSheet($sheet, 0);
    } else {
        $sheet->setTitle($obra->codigo_obra);         
    }

    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Paid');
    $drawing->setDescription('Paid');
    $drawing->setPath('assets/images/icon/logo.png'); // put your path and image here
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(10);
    $drawing->setOffsetY(10);
    $drawing->setWorksheet($sheet);

    $sheet
    ->getRowDimension('1')
    ->setRowHeight(70, 'pt');

    $sheet
    ->getRowDimension('2')
    ->setRowHeight(30, 'pt');          

    /* Sheet With Cell's */
    $sheet->setCellValue('A2', 'ID Grupo');
    $sheet->setCellValue('B2', 'Nome do Grupo');
    $sheet->setCellValue('C2', 'Estoque');
    $sheet->setCellValue('D2', 'Total');



    $sheet->rows = 3;

    foreach($obra->grupos as $grupo){

        $sheet->setCellValue("A{$sheet->rows}", $grupo->id_ativo_externo_grupo);
        $sheet->setCellValue("B{$sheet->rows}", $grupo->nome);
        $sheet->setCellValue("C{$sheet->rows}", $grupo->estoque);
        $sheet->setCellValue("D{$sheet->rows}", $grupo->total);
        $sheet->getRowDimension($sheet->rows)->setRowHeight(20, 'pt');

        $sheet->rows++;

    }

    $sheet->getStyle("A2:D2")
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB("CCCCCC");
    
    foreach (range('A', 'D') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    
    $sheet->getStyle("A2:D2")
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("A2:D2")
        ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER); 

}

return true;