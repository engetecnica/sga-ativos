<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Veículos Quilometragens');


/* Sheet With Cell's */
$sheet->setCellValue('A2', 'ID Veículo');
$sheet->setCellValue('B2', 'Veiculo');
$sheet->setCellValue('C2', 'Tipo');
$sheet->setCellValue('D2', 'Placa / ID Interno');
$sheet->setCellValue('E2', 'Km Inicial');
$sheet->setCellValue('F2', 'Km Atual');
$sheet->setCellValue('G2', 'Km Última Revisão');
$sheet->setCellValue('H2', 'Km Próxima Revisão');
$sheet->setCellValue('I2', 'Km Rodados');
$sheet->setCellValue('J2', 'Data Inclusão');

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


if($data){
    foreach($data as $i=>$row){
        $sheet->setCellValue("A{$sheet->rows}", $row->id_ativo_veiculo);
        $sheet->setCellValue("B{$sheet->rows}", isset($row->marca) ? "{$row->marca} - {$row->modelo}" : '-');
        $sheet->setCellValue("C{$sheet->rows}", $row->tipo_veiculo);
        $sheet->setCellValue("D{$sheet->rows}", $row->veiculo_placa ?: $row->id_interno_maquina);
        $sheet->setCellValue("E{$sheet->rows}", $row->km_inicial ?: 0);
        $sheet->setCellValue("G{$sheet->rows}", $row->km_atual ?: $row->km_atual);
        $sheet->setCellValue("H{$sheet->rows}", $row->km_ultima_revisao ?: 0);
        $sheet->setCellValue("I{$sheet->rows}", $row->km_proxima_revisao ?: 0);
        $sheet->setCellValue("I{$sheet->rows}", $row->km_rodado ?: 0);
        $sheet->setCellValue("I{$sheet->rows}", $this->formata_data_hora($row->data));
        $sheet->getRowDimension($sheet->rows)->setRowHeight(20, 'pt');

        $sheet->rows++;
    }
} else {
    $sheet->setCellValue("A3", "Nenhum registro.");
}



$sheet->getStyle("A2:J2")
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB("CCCCCC");

foreach (range('A', 'J') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$sheet->getStyle("A2:J2")
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("A2:J2")
    ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);




return true;