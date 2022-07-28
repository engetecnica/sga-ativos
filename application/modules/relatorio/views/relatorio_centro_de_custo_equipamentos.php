<?php

use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$equipamentos_sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "Equipamentos");
$spreadsheet->addSheet($equipamentos_sheet, 0);


/* Sheet With Cell's */
$equipamentos_sheet->setCellValue('A2', 'ID');
$equipamentos_sheet->setCellValue('B2', 'Nome');
$equipamentos_sheet->setCellValue('C2', 'Marca');
$equipamentos_sheet->setCellValue('D2', 'Data de Inclusão');
$equipamentos_sheet->setCellValue('E2', 'Custo');

$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Paid');
$drawing->setDescription('Paid');
$drawing->setPath('assets/images/icon/logo.png'); // put your path and image here
$drawing->setCoordinates('A1');
$drawing->setOffsetX(10);
$drawing->setOffsetY(10);
$drawing->setWorksheet($equipamentos_sheet);

$writer = new Xlsx($spreadsheet);

$equipamentos_sheet
    ->getRowDimension('1')
    ->setRowHeight(70, 'pt');

    $equipamentos_sheet
    ->getRowDimension('2')
    ->setRowHeight(30, 'pt');    

$equipamentos_sheet->rows = 3;

foreach($data->equipamentos as $row){
    $equipamentos_sheet->setCellValue("A{$equipamentos_sheet->rows}", $row->id_ativo_interno);
    $equipamentos_sheet->setCellValue("B{$equipamentos_sheet->rows}", $row->nome);
    $equipamentos_sheet->setCellValue("C{$equipamentos_sheet->rows}", ($row->marca) ? $row->marca : "-");
    $equipamentos_sheet->setCellValue("D{$equipamentos_sheet->rows}", date('d/m/Y H:i:s', strtotime($row->data_inclusao)));
    $equipamentos_sheet->setCellValue("E{$equipamentos_sheet->rows}", $this->formata_moeda($row->valor));

    $equipamentos_sheet->getRowDimension($equipamentos_sheet->rows)->setRowHeight(20, 'pt');

    $equipamentos_sheet->rows++;
}

$linha = $equipamentos_sheet->rows + 1;
$equipamentos_sheet->setCellValue("D{$linha}", "Total");
$equipamentos_sheet->setCellValue("E{$linha}", $data->equipamentos_total);
$equipamentos_sheet->getStyle("D{$linha}:E{$linha}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB("CCCCCC");


$equipamentos_sheet->getStyle("A2:E2")
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB("CCCCCC");

foreach (range('A', 'E') as $col) {
    $equipamentos_sheet->getColumnDimension($col)->setAutoSize(true);
}
