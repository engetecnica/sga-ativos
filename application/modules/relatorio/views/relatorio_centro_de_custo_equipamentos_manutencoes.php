<?php

use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$manutencao_sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "Equipamentos - Manutenções");
$spreadsheet->addSheet($manutencao_sheet, 0);


/* Sheet With Cell's */
$manutencao_sheet->setCellValue('A2', 'ID Manutenção');
$manutencao_sheet->setCellValue('B2', 'ID Equipamento');
$manutencao_sheet->setCellValue('C2', 'Equipamento');
$manutencao_sheet->setCellValue('D2', 'Data de Saída');
$manutencao_sheet->setCellValue('E2', 'Data de Retorno');
$manutencao_sheet->setCellValue('F2', 'Custo');

$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Paid');
$drawing->setDescription('Paid');
$drawing->setPath('assets/images/icon/logo.png'); // put your path and image here
$drawing->setCoordinates('A1');
$drawing->setOffsetX(10);
$drawing->setOffsetY(10);
$drawing->setWorksheet($manutencao_sheet);

$writer = new Xlsx($spreadsheet);

$manutencao_sheet
    ->getRowDimension('1')
    ->setRowHeight(70, 'pt');

    $manutencao_sheet
    ->getRowDimension('2')
    ->setRowHeight(30, 'pt');    

$manutencao_sheet->rows = 3;

foreach($data->equipamentos_manutecoes as $row){
    $manutencao_sheet->setCellValue("A{$manutencao_sheet->rows}", $row->id_manutencao);
    $manutencao_sheet->setCellValue("B{$manutencao_sheet->rows}", $row->id_ativo_interno);
    $manutencao_sheet->setCellValue("C{$manutencao_sheet->rows}", ($row->nome) ? $row->nome : "-");
    $manutencao_sheet->setCellValue("D{$manutencao_sheet->rows}", date('d/m/Y H:i:s', strtotime($row->data_saida)));
    $manutencao_sheet->setCellValue("E{$manutencao_sheet->rows}", date('d/m/Y H:i:s', strtotime($row->data_retorno)));
    $manutencao_sheet->setCellValue("F{$manutencao_sheet->rows}", $this->formata_moeda($row->manutencao_valor));

    $manutencao_sheet->getRowDimension($manutencao_sheet->rows)->setRowHeight(20, 'pt');

    $manutencao_sheet->rows++;
}

$linha = $manutencao_sheet->rows + 1;
$manutencao_sheet->setCellValue("E{$linha}", "Total");
$manutencao_sheet->setCellValue("F{$linha}", $data->equipamentos_manutecoes_total);
$manutencao_sheet->getStyle("E{$linha}:F{$linha}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB("CCCCCC");


$manutencao_sheet->getStyle("A2:F2")
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB("CCCCCC");

foreach (range('A', 'F') as $col) {
    $manutencao_sheet->getColumnDimension($col)->setAutoSize(true);
}
