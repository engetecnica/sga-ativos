<?php
use \PhpOffice\PhpSpreadsheet\Style\Fill;
use \PhpOffice\PhpSpreadsheet\Style\Alignment;

$ferramentas_total = 0;
$ferramentas_valor_total = 0;
$equipamentos_total = 0;
$equipamentos_valor_total = 0;
$veiculos_total = 0;
$veiculos_valor_total = 0;

/* 
    Worksheets Deinitions
*/

$sheet_ferramentas = clone $sheet->setTitle('Ferramentas');
$sheet_equipamentos = clone $sheet->setTitle('Equipamentos');
$sheet_veiculos = clone $sheet->setTitle('Veículos');
$sheet_patrimonio = clone $sheet->setTitle('Patrimônio Total');

/* 
    Table hearder Deinitions
*/
$sheet_ferramentas->setCellValue('A1', 'ID');
$sheet_ferramentas->setCellValue('B1', 'ID Grupo');
$sheet_ferramentas->setCellValue('C1', 'Código');
$sheet_ferramentas->setCellValue('D1', 'Nome');
$sheet_ferramentas->setCellValue('E1', 'Obra');
$sheet_ferramentas->setCellValue('F1', 'Registro');
$sheet_ferramentas->setCellValue('G1', 'Descarte');
$sheet_ferramentas->setCellValue('H1', 'Situação');

$sheet_equipamentos->setCellValue('A1', 'ID');
$sheet_equipamentos->setCellValue('B1', 'Nome');
$sheet_equipamentos->setCellValue('C1', 'Marca');
$sheet_equipamentos->setCellValue('D1', 'Obra');
$sheet_equipamentos->setCellValue('E1', 'Registro');
$sheet_equipamentos->setCellValue('F1', 'Descarte');
$sheet_equipamentos->setCellValue('G1', 'Situação');

$sheet_veiculos->setCellValue('A1', 'ID');
$sheet_veiculos->setCellValue('B1', 'Placa');
$sheet_veiculos->setCellValue('C1', 'Tipo');
$sheet_veiculos->setCellValue('D1', 'Marca/Modelo');
$sheet_veiculos->setCellValue('E1', 'Kilometragem');
$sheet_veiculos->setCellValue('F1', 'Situação');

if ($data->show_valor_total) {
    $sheet_patrimonio->setCellValue('A1', 'Ferramentas Quantidade');
    $sheet_patrimonio->setCellValue('B1', 'Ferramentas Valor');
    $sheet_patrimonio->setCellValue('C1', 'Equipamentos Quantidade');
    $sheet_patrimonio->setCellValue('D1', 'Equipamentos Valor');
    $sheet_patrimonio->setCellValue('E1', 'Veículos Quantidade');
    $sheet_patrimonio->setCellValue('F1', 'Veículos Valor');
    $sheet_patrimonio->setCellValue('G1', 'Valor Total do Patrimônio');
} else {
    $sheet_patrimonio->setCellValue('A1', 'Ferramentas Quantidade');
    $sheet_patrimonio->setCellValue('B1', 'Equipamentos Quantidade');
    $sheet_patrimonio->setCellValue('C1', 'Veículos Quantidade');
}

if ($data->show_valor_total) {
    $sheet_ferramentas->setCellValue('I1', 'Valor');
    $sheet_equipamentos->setCellValue('G1', 'Valor');
    $sheet_veiculos->setCellValue('G1', 'Valor FIPE');
}

/* 
    Table data Deinitions
*/
$e = $f = $v = 2;
if (count($data->obras) > 0) {
    foreach ($data->obras as $obra) {
        if ($obra) {
            foreach ($obra->ferramentas as $ferramenta) {
                $ferramentas_total++;
                $ferramentas_valor_total += $ferramenta->valor;
                $sheet_ferramentas->setCellValue("A{$f}", $ferramenta->id_ativo_externo);
                $sheet_ferramentas->setCellValue("B{$f}", $ferramenta->id_ativo_externo_grupo);
                $sheet_ferramentas->setCellValue("C{$f}", $ferramenta->codigo);
                $sheet_ferramentas->setCellValue("D{$f}", $ferramenta->nome);
                $sheet_ferramentas->setCellValue("E{$f}", $ferramenta->obra);
                $sheet_ferramentas->setCellValue("F{$f}", $this->formata_data_hora($ferramenta->data_inclusao));
                $sheet_ferramentas->setCellValue("G{$f}",  $this->formata_data_hora($ferramenta->data_descarte));
                $sheet_ferramentas->setCellValue("H{$f}", $this->status($ferramenta->situacao)['texto']);

                if ($data->show_valor_total) {
                    $sheet_ferramentas->setCellValue("I{$f}", $this->formata_moeda($ferramenta->valor));
                }
                $f++;
            }
            
            foreach ($obra->equipamentos as $equipamento) {
                $equipamentos_total++;
                $equipamentos_valor_total += $equipamento->valor;

                $sheet_equipamentos->setCellValue("A{$e}", $equipamento->id_ativo_interno);
                $sheet_equipamentos->setCellValue("B{$e}", $equipamento->nome);
                $sheet_equipamentos->setCellValue("C{$e}", isset($equipamento->marca) ? $equipamento->marca : '');
                $sheet_equipamentos->setCellValue("D{$e}", $equipamento->obra);
                $sheet_equipamentos->setCellValue("E{$e}", $this->formata_data_hora($equipamento->data_inclusao));
                $sheet_equipamentos->setCellValue("F{$e}",  $this->formata_data_hora($equipamento->data_descarte));
                $sheet_equipamentos->setCellValue("G{$e}", $this->get_situacao($equipamento->situacao)['texto']);

                if ($data->show_valor_total) {
                    $sheet_equipamentos->setCellValue("H{$e}", $this->formata_moeda($equipamento->valor));
                }
                $e++;
            }
        }
    }
}

foreach ($data->veiculos as $veiculo) {
    $veiculos_total++;
    $veiculos_valor_total += $veiculo->valor_fipe;

    $sheet_veiculos->setCellValue("A{$v}", $veiculo->id_ativo_veiculo);
    $sheet_veiculos->setCellValue("B{$v}", $veiculo->veiculo_placa);
    $sheet_veiculos->setCellValue("C{$v}", $veiculo->tipo_veiculo);
    $sheet_veiculos->setCellValue("D{$v}", $veiculo->veiculo);
    $sheet_veiculos->setCellValue("E{$v}", $veiculo->veiculo_km);
    $sheet_veiculos->setCellValue("F{$v}", $this->get_situacao($veiculo->situacao)['texto']);

    if ($data->show_valor_total) {
        $sheet_veiculos->setCellValue("G{$v}", $this->formata_moeda($veiculo->valor_fipe));
    }
    $v++;
}


$f++;
if ($data->show_valor_total) {
    $sheet_ferramentas->setCellValue("F{$f}", 'Quantidade Total em Ferramentas');
    $sheet_ferramentas->setCellValue("G{$f}",$ferramentas_total);
    $sheet_ferramentas->setCellValue("H{$f}", 'Valor Total em Ferramentas');
    $sheet_ferramentas->setCellValue("I{$f}", $this->formata_moeda($ferramentas_valor_total));
} else {
    $sheet_ferramentas->setCellValue("G{$f}", 'Quantidade Total em Ferramentas');
    $sheet_ferramentas->setCellValue("H{$f}",$ferramentas_total);
}

$e++;
if ($data->show_valor_total) {
    $sheet_equipamentos->setCellValue("E{$e}", 'Quantidade Total em Equipamentos');
    $sheet_equipamentos->setCellValue("F{$e}",$equipamentos_total);
    $sheet_equipamentos->setCellValue("G{$e}", 'Valor Total em Equipamentos');
    $sheet_equipamentos->setCellValue("H{$e}", $this->formata_moeda($equipamentos_valor_total));
} else {
    $sheet_equipamentos->setCellValue("F{$e}", 'Quantidade Total em Equipamentos');
    $sheet_equipamentos->setCellValue("G{$e}",$equipamentos_total);
}

$v++;
if ($data->show_valor_total) {
    $sheet_veiculos->setCellValue("D{$v}", 'Quantidade Total de Veículos');
    $sheet_veiculos->setCellValue("E{$v}",$veiculos_total);
    $sheet_veiculos->setCellValue("F{$v}", 'Valor Total em Veículos');
    $sheet_veiculos->setCellValue("G{$v}", $this->formata_moeda($veiculos_valor_total));
} else {
    $sheet_veiculos->setCellValue("E{$v}", 'Quantidade Total de Veículos');
    $sheet_veiculos->setCellValue("F{$v}", $veiculos_total);
}

if ($data->show_valor_total) {
    $sheet_patrimonio->setCellValue('A2', $ferramentas_total);
    $sheet_patrimonio->setCellValue('B2', $this->formata_moeda($ferramentas_valor_total));
    $sheet_patrimonio->setCellValue('C2', $equipamentos_total);
    $sheet_patrimonio->setCellValue('D2', $this->formata_moeda($equipamentos_valor_total));
    $sheet_patrimonio->setCellValue('E2', $veiculos_total);
    $sheet_patrimonio->setCellValue('F2', $this->formata_moeda($veiculos_valor_total));
    $sheet_patrimonio->setCellValue('G2', $this->formata_moeda(array_sum([
        $ferramentas_valor_total, 
        $equipamentos_valor_total, 
        $veiculos_valor_total
    ])));
} else {
    $sheet_patrimonio->setCellValue('A2', $ferramentas_total);
    $sheet_patrimonio->setCellValue('B2', $equipamentos_total);
    $sheet_patrimonio->setCellValue('C2', $veiculos_total);
}


/* 
    Style Deinitions
*/
$font_color = "FFFFFF";
$bg_color = "f5811e";
$font_color2 = "000";
$bg_color2 = "CCCCCC";
$row_height = 30;
$row_unity = "pt";

//Format header ferramentas
$sheet_ferramentas_range = "A1:". ($data->show_valor_total ? "I1" : "H1");
$sheet_ferramentas_range2 = $data->show_valor_total ? "F$f:I$f" : "G$f:H$f";
$spreadsheet->addSheet($sheet_ferramentas, 0);
$sheet_ferramentas->getStyle($sheet_ferramentas_range)
->getFont()
->getColor()
->setARGB($font_color);

$sheet_ferramentas->getStyle($sheet_ferramentas_range)
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB($bg_color);

$sheet_ferramentas->getStyle($sheet_ferramentas_range2)
->getFont()
->getColor()
->setARGB($font_color2);

$sheet_ferramentas->getStyle($sheet_ferramentas_range2)
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB($bg_color2);

$sheet_ferramentas->getStyle($sheet_ferramentas_range)
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet_ferramentas->getStyle($sheet_ferramentas_range)
    ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

$sheet_ferramentas->getStyle($sheet_ferramentas_range2)
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet_ferramentas->getStyle($sheet_ferramentas_range2)
    ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

$sheet_ferramentas->getRowDimension('1')->setRowHeight($row_height, $row_unity);
$sheet_ferramentas->getRowDimension($f)->setRowHeight($row_height, $row_unity);

foreach (range('A', 'I') as $col) {
    $sheet_ferramentas->getColumnDimension($col)->setAutoSize(true);
}


//Format header equipamentos
$sheet_equipamentos_range = "A1:". ($data->show_valor_total ? "H1" : "G1");
$sheet_equipamentos_range2 = $data->show_valor_total ? "E$e:H$e" : "F$e:G$e";
$spreadsheet->addSheet($sheet_equipamentos, 1);
$sheet_equipamentos->getStyle($sheet_equipamentos_range)
->getFont()
->getColor()
->setARGB($font_color);

$sheet_equipamentos->getStyle($sheet_equipamentos_range)
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB($bg_color);

$sheet_equipamentos->getStyle($sheet_equipamentos_range2)
->getFont()
->getColor()
->setARGB($font_color2);

$sheet_equipamentos->getStyle($sheet_equipamentos_range2)
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB($bg_color2);

$sheet_equipamentos->getStyle($sheet_equipamentos_range)
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet_equipamentos->getStyle($sheet_equipamentos_range)
    ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

$sheet_equipamentos->getStyle($sheet_equipamentos_range2)
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet_equipamentos->getStyle($sheet_equipamentos_range2)
    ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

$sheet_equipamentos->getRowDimension('1')->setRowHeight($row_height, $row_unity);
$sheet_equipamentos->getRowDimension($e)->setRowHeight($row_height, $row_unity);

foreach (range('A', 'H') as $col) {
    $sheet_equipamentos->getColumnDimension($col)->setAutoSize(true);
}


//Format header veículos
$sheet_veiculos_range = "A1:". ($data->show_valor_total ? "G1" : "F1");
$sheet_veiculos_range2 = $data->show_valor_total ? "D$v:G$v" : "E$v:F$v";
$spreadsheet->addSheet($sheet_veiculos, 3);
$sheet_veiculos->getStyle($sheet_veiculos_range)
->getFont()
->getColor()
->setARGB($font_color);

$sheet_veiculos->getStyle($sheet_veiculos_range)
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB($bg_color);

$sheet_veiculos->getStyle($sheet_veiculos_range2)
->getFont()
->getColor()
->setARGB($font_color2);

$sheet_veiculos->getStyle($sheet_veiculos_range2)
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB($bg_color2);

$sheet_veiculos->getStyle($sheet_veiculos_range)
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet_veiculos->getStyle($sheet_veiculos_range)
    ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

$sheet_veiculos->getStyle($sheet_veiculos_range2)
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet_veiculos->getStyle($sheet_veiculos_range2)
    ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

$sheet_veiculos->getRowDimension('1')->setRowHeight($row_height, $row_unity);
$sheet_veiculos->getRowDimension($v)->setRowHeight($row_height, $row_unity);

foreach (range('A', 'G') as $col) {
    $sheet_veiculos->getColumnDimension($col)->setAutoSize(true);
}


//Format header Patimonio
$spreadsheet->addSheet($sheet_patrimonio, 4);
$sheet_patrimonio->getStyle('A1:G1')
->getFont()
->getColor()
->setARGB($font_color);

$sheet_patrimonio->getStyle('A1:G1')
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB($bg_color);

$sheet_patrimonio->getStyle('A1:G1')
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet_patrimonio->getStyle('A1:G1')
    ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

$sheet_patrimonio->getRowDimension('1')->setRowHeight($row_height, $row_unity);
$sheet_patrimonio->getRowDimension($f)->setRowHeight($row_height, $row_unity);

foreach (range('A', 'G') as $col) {
    $sheet_patrimonio->getColumnDimension($col)->setAutoSize(true);
}

return true;