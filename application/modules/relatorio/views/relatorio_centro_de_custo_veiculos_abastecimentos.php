<?php

use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$abastecimento_sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "Veículos - Abastecimentos");
$spreadsheet->addSheet($abastecimento_sheet, 0);


/* Sheet With Cell's */
$abastecimento_sheet->setCellValue('A2', 'ID Abastecimento');
$abastecimento_sheet->setCellValue('B2', 'ID Veículo');
$abastecimento_sheet->setCellValue('C2', 'Placa/ID Interno');
$abastecimento_sheet->setCellValue('D2', 'KM Atual');
$abastecimento_sheet->setCellValue('E2', 'Combustível');
$abastecimento_sheet->setCellValue('F2', 'Unidades  (L/M&sup3;)');
$abastecimento_sheet->setCellValue('G2', 'Custo');
$abastecimento_sheet->setCellValue('H2', 'Data');


$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Paid');
$drawing->setDescription('Paid');
$drawing->setPath('assets/images/icon/logo.png'); // put your path and image here
$drawing->setCoordinates('A1');
$drawing->setOffsetX(10);
$drawing->setOffsetY(10);
$drawing->setWorksheet($abastecimento_sheet);

$writer = new Xlsx($spreadsheet);

$abastecimento_sheet
    ->getRowDimension('1')
    ->setRowHeight(70, 'pt');

    $abastecimento_sheet
    ->getRowDimension('2')
    ->setRowHeight(30, 'pt');    

$abastecimento_sheet->rows = 3;




foreach($data->veiculos_abastecimentos as $row){
    $abastecimento_sheet->setCellValue("A{$abastecimento_sheet->rows}", $row->id_ativo_veiculo_abastecimento);
    $abastecimento_sheet->setCellValue("B{$abastecimento_sheet->rows}", isset($row->marca) ? "{$row->marca} - {$row->modelo}" : '-');
    $abastecimento_sheet->setCellValue("C{$abastecimento_sheet->rows}", $row->veiculo_placa ?: $row->id_interno_maquina);
    $abastecimento_sheet->setCellValue("D{$abastecimento_sheet->rows}", $row->veiculo_km);
    $abastecimento_sheet->setCellValue("E{$abastecimento_sheet->rows}", ucfirst($row->combustivel));
    $abastecimento_sheet->setCellValue("F{$abastecimento_sheet->rows}", $row->combustivel_unidade_total ." " . $row->combustivel_unidade_tipo == '0' ? 'L' : "M&sup3;");
    $abastecimento_sheet->setCellValue("G{$abastecimento_sheet->rows}", $this->formata_moeda($row->abastecimento_custo));
    $abastecimento_sheet->setCellValue("H{$abastecimento_sheet->rows}", $this->formata_data($row->abastecimento_data));

    $abastecimento_sheet->getRowDimension($abastecimento_sheet->rows)->setRowHeight(20, 'pt');

    $abastecimento_sheet->rows++;
}

$linha = $abastecimento_sheet->rows + 1;
$abastecimento_sheet->setCellValue("G{$linha}", "Total");
$abastecimento_sheet->setCellValue("H{$linha}", $data->veiculos_abastecimentos_total);
$abastecimento_sheet->getStyle("G{$linha}:H{$linha}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB("CCCCCC");


$abastecimento_sheet->getStyle("A2:H2")
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB("CCCCCC");

foreach (range('A', 'H') as $col) {
    $abastecimento_sheet->getColumnDimension($col)->setAutoSize(true);
}
