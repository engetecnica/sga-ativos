<h1>Veículos Abastecimentos</h1>
<p>Relatório de abastecimentos, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>


<?php if (count($relatorio->abastecimentos) > 0) {?>
  <table class="tabela">
      <thead>
          <tr>
            <th>ID Abastecimento</th>
            <th>Placa/ID Interno</th>
            <th width="7%">Veículo</th>
            <th>Km Atual</th>
            <th>Combustível</th>
            <th>Unidades (L/M&sup3;)</th>
            <th>Custo</th>
            <th>Data</th>
          </tr>
      </thead>
      <tbody>
        <?php foreach($relatorio->abastecimentos as $i => $item) { ?>
          <tr>
            <td><?php echo ($i + 1); ?></td>
            <td><?php echo $item->veiculo_placa ?: $item->id_interno_maquina; ?></td>
            <td><?php echo isset($item->marca) ? "{$item->marca} - {$item->modelo}" : '-' ;?></td>
            <td><?php echo $item->veiculo_km;?></td>
            <td><?php echo ucfirst($item->combustivel); ?></td>
            <td><?php echo $item->combustivel_unidade_total ." "; echo $item->combustivel_unidade_tipo == '0' ? 'L' : "M&sup3;"; ?></td>
            <td><?php echo $this->formata_moeda($item->abastecimento_custo); ?></td>
            <td><?php echo $this->formata_data($item->abastecimento_data); ?></td>
          </tr>
        <?php } ?>
          <tr>
            <?php if ($relatorio->show_resultados_todos === false) { ?>
            <td>Consumo Médio (L/M&sup3;  por KM)</td>
            <td><?php echo $relatorio->consumo_medio;?></td>
            <td>Quilômetros Rodados (KM)</td>
            <td><?php echo $relatorio->km_rodados;?></td>
            <td>Unidades (L/M&sup3;)</td>
            <td><?php echo $relatorio->unidades;?></td>
            <?php } else { ?>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            <?php } ?>
            <td>Custo Total</td>
            <td><?php echo $relatorio->total; ?></td>
          </tr>
      </tbody>
  </table>
<?php } else { ?>
  <p>Nenhum abastecimento de veículo registrado no peíodo</p>
<?php } ?>