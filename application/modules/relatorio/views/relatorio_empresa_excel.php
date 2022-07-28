<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Empresas');


/* Sheet With Cell's */
$sheet->setCellValue('A2', 'Nome Fantasia');
$sheet->setCellValue('B2', 'Razão Social');
$sheet->setCellValue('C2', 'CNPJ');
$sheet->setCellValue('D2', 'Inscrição Estadual');
$sheet->setCellValue('E2', 'Inscrição Municipal');
$sheet->setCellValue('F2', 'Endereço');
$sheet->setCellValue('G2', 'Responsável');
$sheet->setCellValue('H2', 'Registro');
$sheet->setCellValue('I2', 'Situação');

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
foreach($data as $row){

    $situacao = $this->get_situacao($row->situacao);

    $sheet->setCellValue("A{$sheet->rows}", $row->nome_fantasia);
    $sheet->setCellValue("B{$sheet->rows}", $row->razao_social);
    $sheet->setCellValue("C{$sheet->rows}", $row->cnpj);
    $sheet->setCellValue("D{$sheet->rows}", $row->inscricao_estadual);
    $sheet->setCellValue("E{$sheet->rows}", $row->inscricao_municipal);
    $sheet->setCellValue("F{$sheet->rows}", $row->endereco.", ". $row->endereco_complemento . " - ". $row->endereco_bairro);
    $sheet->setCellValue("G{$sheet->rows}", $row->responsavel);
    $sheet->setCellValue("H{$sheet->rows}", date('d/m/Y', strtotime($row->data_criacao)));
    $sheet->setCellValue("I{$sheet->rows}", $situacao['texto']);
    $sheet->getRowDimension($sheet->rows)->setRowHeight(20, 'pt');

    $sheet->rows++;
}


$sheet->getStyle("A2:I2")
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB("CCCCCC");

foreach (range('A', 'I') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$sheet->getStyle("A2:I2")
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("A2:I2")
    ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);




return true;