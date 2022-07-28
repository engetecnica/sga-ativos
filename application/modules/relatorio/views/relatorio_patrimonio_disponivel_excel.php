<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
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

//$sheet_ferramentas = clone $sheet->setTitle('Ferramentas');
$ferramentas1 = new Spreadsheet();
$sheet_ferramentas = $ferramentas1->getActiveSheet();
$sheet_ferramentas->setTitle('Veículos Depreciação');


$ferramentas = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$ferramentas->setName('Paid');
$ferramentas->setDescription('Paid');
$ferramentas->setPath('assets/images/icon/logo.png'); // put your path and image here
$ferramentas->setCoordinates('A1');
$ferramentas->setOffsetX(10);
$ferramentas->setOffsetY(10);
$ferramentas->setWorksheet($sheet_ferramentas);


$sheet_ferramentas
    ->getRowDimension('1')
    ->setRowHeight(70, 'pt');








$sheet_equipamentos = clone $sheet->setTitle('Equipamentos');
$sheet_veiculos = clone $sheet->setTitle('Veículos');
$sheet_patrimonio = clone $sheet->setTitle('Patrimônio Total');

/* 
    Table hearder Deinitions
*/
$sheet_ferramentas->setCellValue('A2', 'ID');
$sheet_ferramentas->setCellValue('B2', 'ID Grupo');
$sheet_ferramentas->setCellValue('C2', 'Código');
$sheet_ferramentas->setCellValue('D2', 'Nome');
$sheet_ferramentas->setCellValue('E2', 'Obra');
$sheet_ferramentas->setCellValue('F2', 'Registro');
$sheet_ferramentas->setCellValue('G2', 'Descarte');
$sheet_ferramentas->setCellValue('H2', 'Situação');

$sheet_equipamentos->setCellValue('A2', 'ID');
$sheet_equipamentos->setCellValue('B2', 'Nome');
$sheet_equipamentos->setCellValue('C2', 'Marca');
$sheet_equipamentos->setCellValue('D2', 'Obra');
$sheet_equipamentos->setCellValue('E2', 'Registro');
$sheet_equipamentos->setCellValue('F2', 'Descarte');
$sheet_equipamentos->setCellValue('G2', 'Situação');

$sheet_veiculos->setCellValue('A2', 'ID');
$sheet_veiculos->setCellValue('B2', 'Placa');
$sheet_veiculos->setCellValue('C2', 'Tipo');
$sheet_veiculos->setCellValue('D2', 'Marca/Modelo');
$sheet_veiculos->setCellValue('E2', 'Kilometragem');
$sheet_veiculos->setCellValue('F2', 'Situação');









if ($data->show_valor_total) {
    $sheet_patrimonio->setCellValue('A2', 'Ferramentas Quantidade');
    $sheet_patrimonio->setCellValue('B2', 'Ferramentas Valor');
    $sheet_patrimonio->setCellValue('C2', 'Equipamentos Quantidade');
    $sheet_patrimonio->setCellValue('D2', 'Equipamentos Valor');
    $sheet_patrimonio->setCellValue('E2', 'Veículos Quantidade');
    $sheet_patrimonio->setCellValue('F2', 'Veículos Valor');
    $sheet_patrimonio->setCellValue('G2', 'Valor Total do Patrimônio');
} else {
    $sheet_patrimonio->setCellValue('A2', 'Ferramentas Quantidade');
    $sheet_patrimonio->setCellValue('B2', 'Equipamentos Quantidade');
    $sheet_patrimonio->setCellValue('C2', 'Veículos Quantidade');
}

if ($data->show_valor_total) {
    $sheet_ferramentas->setCellValue('I2', 'Valor');
    $sheet_equipamentos->setCellValue('G2', 'Valor');
    $sheet_veiculos->setCellValue('G2', 'Valor FIPE');
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
    $sheet_veiculos->setCellValue("B{$v}", $veiculo->veiculo_placa ?: $veiculo->id_interno_maquina);
    $sheet_veiculos->setCellValue("C{$v}", ucfirst($veiculo->tipo_veiculo));
    $sheet_veiculos->setCellValue("D{$v}", isset($veiculo->marca) ? "{$veiculo->marca} - {$veiculo->modelo}" : '-');
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
    $sheet_ferramentas->setCellValue("G{$f}", $ferramentas_total);
    $sheet_ferramentas->setCellValue("H{$f}", 'Valor Total em Ferramentas');
    $sheet_ferramentas->setCellValue("I{$f}", $this->formata_moeda($ferramentas_valor_total));
} else {
    $sheet_ferramentas->setCellValue("G{$f}", 'Quantidade Total em Ferramentas');
    $sheet_ferramentas->setCellValue("H{$f}", $ferramentas_total);
}

$e++;
if ($data->show_valor_total) {
    $sheet_equipamentos->setCellValue("E{$e}", 'Quantidade Total em Equipamentos');
    $sheet_equipamentos->setCellValue("F{$e}",$equipamentos_total);
    $sheet_equipamentos->setCellValue("G{$e}", 'Valor Total em Equipamentos');
    $sheet_equipamentos->setCellValue("H{$e}", $this->formata_moeda($equipamentos_valor_total));
} else {
    $sheet_equipamentos->setCellValue("F{$e}", 'Quantidade Total em Equipamentos');
    $sheet_equipamentos->setCellValue("G{$e}", $equipamentos_total);
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
    $sheet_patrimonio->setCellValue('A3', $ferramentas_total);
    $sheet_patrimonio->setCellValue('B3', $this->formata_moeda($ferramentas_valor_total));
    $sheet_patrimonio->setCellValue('C3', $equipamentos_total);
    $sheet_patrimonio->setCellValue('D3', $this->formata_moeda($equipamentos_valor_total));
    $sheet_patrimonio->setCellValue('E3', $veiculos_total);
    $sheet_patrimonio->setCellValue('F3', $this->formata_moeda($veiculos_valor_total));
    $sheet_patrimonio->setCellValue('G3', $this->formata_moeda(array_sum([
        $ferramentas_valor_total, 
        $equipamentos_valor_total, 
        $veiculos_valor_total
    ])));
} else {
    $sheet_patrimonio->setCellValue('A3', $ferramentas_total);
    $sheet_patrimonio->setCellValue('B3', $equipamentos_total);
    $sheet_patrimonio->setCellValue('C3', $veiculos_total);
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
$sheet_ferramentas_range = "A2:". ($data->show_valor_total ? "I2" : "H2");
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
$sheet_equipamentos_range = "A2:". ($data->show_valor_total ? "H2" : "G2");
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
$sheet_veiculos_range = "A2:". ($data->show_valor_total ? "G2" : "F2");
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
$sheet_patrimonio->getStyle('A2:G2')
->getFont()
->getColor()
->setARGB($font_color);

$sheet_patrimonio->getStyle('A2:G2')
->getFill()
->setFillType(Fill::FILL_SOLID)
->getStartColor()
->setARGB($bg_color);

$sheet_patrimonio->getStyle('A2:G2')
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet_patrimonio->getStyle('A2:G2')
    ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

$sheet_patrimonio->getRowDimension('2')->setRowHeight($row_height, $row_unity);
$sheet_patrimonio->getRowDimension($f)->setRowHeight($row_height, $row_unity);

foreach (range('A', 'G') as $col) {
    $sheet_patrimonio->getColumnDimension($col)->setAutoSize(true);
}

return true;