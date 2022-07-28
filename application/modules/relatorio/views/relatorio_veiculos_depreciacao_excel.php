<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Veículos Depreciação');


/* Sheet With Cell's */
$sheet->setCellValue('A2', 'ID Depreciação');
$sheet->setCellValue('B2', 'Veiculo Marca/Modelo');
$sheet->setCellValue('C2', 'Placa / ID INterno (Máquina)');
$sheet->setCellValue('D2', 'Mês Referência');
$sheet->setCellValue('E2', 'Valor Fipe');
$sheet->setCellValue('F2', 'Data de Inclusão');
$sheet->setCellValue('G2', 'Depreciação em %');
$sheet->setCellValue('H2', 'Depreciação em R$');

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


if($data->data){
    foreach($data->data as $i=>$row){
        $sheet->setCellValue("A{$sheet->rows}", $row->id_ativo_veiculo_depreciacao);
        $sheet->setCellValue("B{$sheet->rows}", isset($row->marca) ? "{$row->marca} - {$row->modelo}" : '-');
        $sheet->setCellValue("C{$sheet->rows}", $row->veiculo_placa ?: $row->id_interno_maquina);
        $sheet->setCellValue("D{$sheet->rows}", $this->formata_mes_referecia($row->fipe_mes_referencia, $row->fipe_ano_referencia));
        $sheet->setCellValue("E{$sheet->rows}", $this->formata_moeda($row->fipe_valor));
        $sheet->setCellValue("G{$sheet->rows}", $this->formata_data_hora($row->data));
        $sheet->setCellValue("H{$sheet->rows}", "{$row->depreciacao_porcentagem} %");
        $sheet->setCellValue("I{$sheet->rows}", $this->formata_moeda($row->depreciacao_valor));
        $sheet->getRowDimension($sheet->rows)->setRowHeight(20, 'pt');

        $sheet->rows++;
    }
} else {
    $sheet->setCellValue("A3", "Nenhum registro.");
}



$sheet->getStyle("A2:H2")
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB("CCCCCC");

foreach (range('A', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$sheet->getStyle("A2:H2")
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("A2:H2")
    ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);




return true;