<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Centro de Custo');


/* Sheet With Cell's */
$sheet->setCellValue('A2', 'Tipo');
$sheet->setCellValue('B2', 'Quantidade');
$sheet->setCellValue('C2', 'Custo Médio Unitário');
$sheet->setCellValue('D2', 'Custo Total');

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

/* Ferramentas */
$ferramentas_custo_medio =  $this->formata_moeda(
                            (   
                                str_replace(",", ".",
                                    str_replace(".", "",
                                        str_replace("R$ ", "", 
                                            $data->ferramentas_total
                                        )
                                    )
                                )
                            ) / count($data->ferramentas)
                        );



$sheet->setCellValue("A3", "Ferramentas");
$sheet->setCellValue("B3", count($data->ferramentas));
$sheet->setCellValue("C3", $ferramentas_custo_medio);
$sheet->setCellValue("D3", $data->ferramentas_total);

/* Equipamentos */
$equipamentos_custo_medio =  $this->formata_moeda(
    (   
        str_replace(",", ".",
            str_replace(".", "",
                str_replace("R$ ", "", 
                    $data->equipamentos_total
                )
            )
        )
    ) / count($data->equipamentos)
);
$sheet->setCellValue("A4", "Equipamentos");
$sheet->setCellValue("B4", count($data->equipamentos));
$sheet->setCellValue("C4", $equipamentos_custo_medio);
$sheet->setCellValue("D4", $data->equipamentos_total);

/* Equipamentos Manutenções */
$equipamentos_manutencoes_custo_medio =  $this->formata_moeda(
    (   
        str_replace(",", ".",
            str_replace(".", "",
                str_replace("R$ ", "", 
                    $data->equipamentos_manutecoes_total
                )
            )
        )
    ) / count($data->equipamentos_manutecoes)
);
$sheet->setCellValue("A5", "Equipamentos Manutenções");
$sheet->setCellValue("B5", count($data->equipamentos_manutecoes));
$sheet->setCellValue("C5", $equipamentos_manutencoes_custo_medio);
$sheet->setCellValue("D5", $data->equipamentos_manutecoes_total);

/* Veículos Abastecimentos */
$veiculos_abastecimento_custo_medio =  $this->formata_moeda(
    (   
        str_replace(",", ".",
            str_replace(".", "",
                str_replace("R$ ", "", 
                    $data->veiculos_abastecimentos_total
                )
            )
        )
    ) / (($data->veiculos_abastecimentos) ? count($data->veiculos_abastecimentos) : 1)
);
$sheet->setCellValue("A6", "Veículos Abastecimentos");
$sheet->setCellValue("B6", ($data->veiculos_abastecimentos) ? count($data->veiculos_abastecimentos) : 0);
$sheet->setCellValue("C6", $veiculos_abastecimento_custo_medio);
$sheet->setCellValue("D6", $data->veiculos_abastecimentos_total);

/* Veículos Manutenções */
$veiculos_manutencoes_custo_medio =  $this->formata_moeda(
    (   
        str_replace(",", ".",
            str_replace(".", "",
                str_replace("R$ ", "", 
                    $data->veiculos_manutecoes_total
                )
            )
        )
    ) / count($data->veiculos_manutecoes)
);
$sheet->setCellValue("A7", "Veículos Manutenções");
$sheet->setCellValue("B7", count($data->veiculos_manutecoes));
$sheet->setCellValue("C7", $veiculos_manutencoes_custo_medio);
$sheet->setCellValue("D7", $data->veiculos_manutecoes_total);


$linha = $sheet->rows + 1;
$sheet->setCellValue("G{$linha}", "Total");
$sheet->setCellValue("H{$linha}", $data->veiculos_abastecimentos_total);
$sheet->getStyle("G{$linha}:H{$linha}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB("CCCCCC");


/* Relatório - Ferramentas */
include('relatorio_centro_de_custo_ferramentas.php');

/* Relatório - Equipamentos */
include('relatorio_centro_de_custo_equipamentos.php');

/* Relatório - Equipamentos Manutenções */
include('relatorio_centro_de_custo_equipamentos_manutencoes.php');

/* Relatório - Veículos Abastecimentos */
include('relatorio_centro_de_custo_veiculos_abastecimentos.php');

/* Relatório - Veículos Manutenções */
include('relatorio_centro_de_custo_veiculos_manutencoes.php');


$sheet->getStyle("A2:D2")
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB("CCCCCC");

foreach (range('A', 'D') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}


return true;