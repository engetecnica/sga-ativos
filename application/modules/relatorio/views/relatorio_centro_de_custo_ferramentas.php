<?php

use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$ferramentas_sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "Ferramentas");
$spreadsheet->addSheet($ferramentas_sheet, 0);


/* Sheet With Cell's */
$ferramentas_sheet->setCellValue('A2', 'ID');
$ferramentas_sheet->setCellValue('B2', 'Código');
$ferramentas_sheet->setCellValue('C2', 'Nome');
$ferramentas_sheet->setCellValue('D2', 'Data de Inclusão');
$ferramentas_sheet->setCellValue('E2', 'Custo');

$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Paid');
$drawing->setDescription('Paid');
$drawing->setPath('assets/images/icon/logo.png'); // put your path and image here
$drawing->setCoordinates('A1');
$drawing->setOffsetX(10);
$drawing->setOffsetY(10);
$drawing->setWorksheet($ferramentas_sheet);

$writer = new Xlsx($spreadsheet);

$ferramentas_sheet
    ->getRowDimension('1')
    ->setRowHeight(70, 'pt');

    $ferramentas_sheet
    ->getRowDimension('2')
    ->setRowHeight(30, 'pt');    

$ferramentas_sheet->rows = 3;

foreach($data->ferramentas as $row){
    $ferramentas_sheet->setCellValue("A{$ferramentas_sheet->rows}", $row->id_ativo_externo);
    $ferramentas_sheet->setCellValue("B{$ferramentas_sheet->rows}", $row->codigo);
    $ferramentas_sheet->setCellValue("C{$ferramentas_sheet->rows}", $row->nome);
    $ferramentas_sheet->setCellValue("D{$ferramentas_sheet->rows}", date('d/m/Y H:i:s', strtotime($row->data_inclusao)));
    $ferramentas_sheet->setCellValue("E{$ferramentas_sheet->rows}", $this->formata_moeda($row->valor));

    $ferramentas_sheet->getRowDimension($ferramentas_sheet->rows)->setRowHeight(20, 'pt');

    $ferramentas_sheet->rows++;
}


$linha = $ferramentas_sheet->rows + 1;
$ferramentas_sheet->setCellValue("D{$linha}", "Total");
$ferramentas_sheet->setCellValue("E{$linha}", $data->ferramentas_total);
$ferramentas_sheet->getStyle("D{$linha}:E{$linha}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB("CCCCCC");

    
$ferramentas_sheet->getStyle("A2:E2")
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB("CCCCCC");

foreach (range('A', 'E') as $col) {
    $ferramentas_sheet->getColumnDimension($col)->setAutoSize(true);
}
