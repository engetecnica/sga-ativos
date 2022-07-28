<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
//$sheet->setTitle('Estoque Geral');

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
    $sheet->setCellValue('A2', 'ID Equipamento');
    $sheet->setCellValue('B2', 'Nome do Grupo');
    $sheet->setCellValue('C2', 'Marca');
    $sheet->setCellValue('D2', 'Registro');
    $sheet->setCellValue('E2', 'Situação');



    $sheet->rows = 3;

    $situacao = $this->get_situacao($obra->situacao);

    foreach($obra->equipamentos as $equipamento){

        $sheet->setCellValue("A{$sheet->rows}", $equipamento->id_ativo_interno);
        $sheet->setCellValue("B{$sheet->rows}", $equipamento->nome);
        $sheet->setCellValue("C{$sheet->rows}", isset($equipamento->marca) ? $equipamento->marca : '-');
        $sheet->setCellValue("D{$sheet->rows}", date('d/m/Y H:i:s', strtotime($obra->data_criacao)));
        $sheet->setCellValue("E{$sheet->rows}", $situacao['texto']);
        $sheet->getRowDimension($sheet->rows)->setRowHeight(20, 'pt');

        $sheet->rows++;

    }

    $sheet->getStyle("A2:D2")
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB("CCCCCC");
    
    foreach (range('A', 'E') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    
    $sheet->getStyle("A2:E2")
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("A2:E2")
        ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER); 

}

return true;