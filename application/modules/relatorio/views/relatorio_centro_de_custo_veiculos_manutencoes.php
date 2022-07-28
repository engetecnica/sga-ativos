<?php

use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$veiculos_manutecoes_sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "Veículos - Manutenções");
$spreadsheet->addSheet($veiculos_manutecoes_sheet, 0);


/* Sheet With Cell's */
$veiculos_manutecoes_sheet->setCellValue('A2', 'ID Manutenção');
$veiculos_manutecoes_sheet->setCellValue('B2', 'ID Veículo');
$veiculos_manutecoes_sheet->setCellValue('C2', 'Placa');
$veiculos_manutecoes_sheet->setCellValue('D2', 'Marca/Modelo');
$veiculos_manutecoes_sheet->setCellValue('E2', 'Tipo');
$veiculos_manutecoes_sheet->setCellValue('F2', 'Kilometragem');
$veiculos_manutecoes_sheet->setCellValue('G2', 'Fornecedor');
$veiculos_manutecoes_sheet->setCellValue('H2', 'Data');
$veiculos_manutecoes_sheet->setCellValue('I2', 'Observações');
$veiculos_manutecoes_sheet->setCellValue('J2', 'Custo');


$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Paid');
$drawing->setDescription('Paid');
$drawing->setPath('assets/images/icon/logo.png'); // put your path and image here
$drawing->setCoordinates('A1');
$drawing->setOffsetX(10);
$drawing->setOffsetY(10);
$drawing->setWorksheet($veiculos_manutecoes_sheet);

$writer = new Xlsx($spreadsheet);

$veiculos_manutecoes_sheet
    ->getRowDimension('1')
    ->setRowHeight(70, 'pt');

    $veiculos_manutecoes_sheet
    ->getRowDimension('2')
    ->setRowHeight(30, 'pt');    

$veiculos_manutecoes_sheet->rows = 3;

foreach($data->veiculos_manutecoes as $row){
    $veiculos_manutecoes_sheet->setCellValue("A{$veiculos_manutecoes_sheet->rows}", $row->id_ativo_veiculo_manutencao);
    $veiculos_manutecoes_sheet->setCellValue("B{$veiculos_manutecoes_sheet->rows}", $row->id_ativo_veiculo);
    $veiculos_manutecoes_sheet->setCellValue("C{$veiculos_manutecoes_sheet->rows}", $row->veiculo_placa ?: $row->id_interno_maquina);
    $veiculos_manutecoes_sheet->setCellValue("D{$veiculos_manutecoes_sheet->rows}", $row->marca ? "{$row->marca} - {$row->modelo}" : '-');
    $veiculos_manutecoes_sheet->setCellValue("E{$veiculos_manutecoes_sheet->rows}", ucfirst($row->tipo_veiculo));
    $veiculos_manutecoes_sheet->setCellValue("F{$veiculos_manutecoes_sheet->rows}", $row->veiculo_km_atual);
    $veiculos_manutecoes_sheet->setCellValue("G{$veiculos_manutecoes_sheet->rows}", $row->fornecedor);
    $veiculos_manutecoes_sheet->setCellValue("H{$veiculos_manutecoes_sheet->rows}", date('d/m/Y H:i:s', strtotime($row->data)));
    $veiculos_manutecoes_sheet->setCellValue("I{$veiculos_manutecoes_sheet->rows}", $row->veiculo_observacoes);
    $veiculos_manutecoes_sheet->setCellValue("J{$veiculos_manutecoes_sheet->rows}", $this->formata_moeda($row->veiculo_custo));

    $veiculos_manutecoes_sheet->getRowDimension($veiculos_manutecoes_sheet->rows)->setRowHeight(20, 'pt');

    $veiculos_manutecoes_sheet->rows++;
}

$linha = $veiculos_manutecoes_sheet->rows + 1;
$veiculos_manutecoes_sheet->setCellValue("I{$linha}", "Total");
$veiculos_manutecoes_sheet->setCellValue("J{$linha}", $data->veiculos_manutecoes_total);
$veiculos_manutecoes_sheet->getStyle("I{$linha}:J{$linha}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB("CCCCCC");


$veiculos_manutecoes_sheet->getStyle("A2:J2")
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB("CCCCCC");

foreach (range('A', 'J') as $col) {
    $veiculos_manutecoes_sheet->getColumnDimension($col)->setAutoSize(true);
}
