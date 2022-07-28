<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

foreach($data['obras'] as $i => $obra) {

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
        $sheet->setCellValue('A2', 'Produto');
        $sheet->setCellValue('B2', 'Estoque');
        $sheet->setCellValue('C2', 'Liberado');
        $sheet->setCellValue('D2', 'Trânsito');
        $sheet->setCellValue('E2', 'Operação');
        $sheet->setCellValue('F2', 'Fora de Operação');
        $sheet->setCellValue('G2', 'Defeito');
        $sheet->setCellValue('H2', 'Total Itens');
        $sheet->setCellValue('I2', 'Valor Unitário');
        $sheet->setCellValue('J2', 'Valor do Grupo');

        $sheet->rows = 3;

        foreach($obra->grupos as $grupo){

            $sheet->setCellValue("A{$sheet->rows}", $grupo->nome);
            $sheet->setCellValue("B{$sheet->rows}", $grupo->estoque);
            $sheet->setCellValue("C{$sheet->rows}", $grupo->liberado);
            $sheet->setCellValue("D{$sheet->rows}", $grupo->transito);
            $sheet->setCellValue("E{$sheet->rows}", $grupo->emoperacao);
            $sheet->setCellValue("F{$sheet->rows}", $grupo->foradeoperacao);
            $sheet->setCellValue("G{$sheet->rows}", $grupo->comdefeito);
            $sheet->setCellValue("H{$sheet->rows}", $grupo->total);
            $sheet->setCellValue("I{$sheet->rows}", $this->formata_moeda($grupo->total_grupo / $grupo->total));
            $sheet->setCellValue("J{$sheet->rows}", $this->formata_moeda($grupo->total_grupo));
            $sheet->getRowDimension($sheet->rows)->setRowHeight(20, 'pt');

            $sheet->rows++;

        }

        $setCellValueTotalObra = $sheet->rows + 1;
        $sheet->setCellValue("I{$setCellValueTotalObra}", "Total Obra");
        $sheet->setCellValue("J{$setCellValueTotalObra}", $this->formata_moeda($obra->total_obra));    
        
        /* Configurações das Células e Colunas */

        $sheet->getStyle("I{$setCellValueTotalObra}")
        ->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB("CCCCCC");    
        
        $sheet->getStyle("J{$setCellValueTotalObra}")
        ->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB("EDEDED");
        
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

}


/* Aba de Total Geral */

$sheetTotal = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "Total Geral");
$spreadsheet->addSheet($sheetTotal); 

$sheetTotal
        ->getRowDimension('1')
        ->setRowHeight(70, 'pt');

$sheetTotal
        ->getRowDimension('2')
        ->setRowHeight(30, 'pt');   

$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Paid');
        $drawing->setDescription('Paid');
        $drawing->setPath('assets/images/icon/logo.png'); // put your path and image here
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(10);
        $drawing->setWorksheet($sheetTotal);


$sheetTotal->setCellValue("A2", "Obra");
$sheetTotal->setCellValue("B2", "Total");

$sheetTotal->rows = 3;
$sheetTotal->total_geral  = 0;

foreach($data['obras'] as $i => $obra) {

    $sheetTotal->setCellValue("A{$sheetTotal->rows}", $obra->codigo_obra);  
    $sheetTotal->setCellValue("B{$sheetTotal->rows}", $this->formata_moeda($obra->total_obra));  

    $sheetTotal->total_geral += $obra->total_obra;
    $sheetTotal->rows++;
}

$sheetTotal->rows = $sheetTotal->rows+1;

$sheetTotal->setCellValue("A{$sheetTotal->rows}", "Somatória");
$sheetTotal->setCellValue("B{$sheetTotal->rows}", $this->formata_moeda($sheetTotal->total_geral));

/* Configurações das Células e Colunas */
$sheetTotal->getStyle("A2:B2")
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB("CCCCCC");

foreach (range('A', 'B') as $col) {
    $sheetTotal->getColumnDimension($col)->setAutoSize(true);
}

$sheetTotal->getStyle("A2:B2")
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheetTotal->getStyle("A2:B2")
    ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER); 

  




return true;