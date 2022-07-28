<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Funcionários');


/* Sheet With Cell's */
$sheet->setCellValue('A2', 'Matrícula');
$sheet->setCellValue('B2', 'Nome Completo');
$sheet->setCellValue('C2', 'Telefone');
$sheet->setCellValue('D2', 'Data de Nascimento');
$sheet->setCellValue('E2', 'CPF');
$sheet->setCellValue('F2', 'Empresa de Registro');
$sheet->setCellValue('G2', 'Obra');
$sheet->setCellValue('H2', 'Situação');

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

    $sheet->setCellValue("A{$sheet->rows}", $row->matricula);
    $sheet->setCellValue("B{$sheet->rows}", $row->nome);
    $sheet->setCellValue("C{$sheet->rows}", $row->celular);
    $sheet->setCellValue("D{$sheet->rows}", date('d/m/Y', strtotime($row->data_nascimento)));
    $sheet->setCellValue("E{$sheet->rows}", $row->cpf);
    $sheet->setCellValue("F{$sheet->rows}", $row->empresa);
    $sheet->setCellValue("G{$sheet->rows}", $row->obra);
    $sheet->setCellValue("H{$sheet->rows}", $situacao['texto']);
    $sheet->getRowDimension($sheet->rows)->setRowHeight(20, 'pt');

    $sheet->rows++;
}


$sheet->getStyle("A2:H2")
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB("CCCCCC");

foreach (range('A', 'I') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$sheet->getStyle("A2:H2")
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("A2:H2")
    ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);




return true;