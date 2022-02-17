<h1>Veículos Depreciação</h1>
<p>Relatório de depreciação, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>

<?php if (count($relatorio) > 0) {?>
<table class="tabela">
    <thead>
        <tr>
          <th>ID</th>
          <th>Placa</th>
          <th>Tipo</th>
          <th>Marca/Modelo</th>
          <th>Kilometragem</th>
          <th>Valor FIPE</th>
          <th>Valor Depreciação</th>
          <th>Data/Hora</th>
        </tr>
    </thead>
    <tbody>
      <?php foreach($relatorio as $i => $item) {?>
        <tr>
          <td><?php echo  $item->id_ativo_veiculo_depreciacao; ?></td>
          <td><?php echo $item->veiculo_placa; ?></td>
          <td><?php echo ucfirst($item->tipo_veiculo);?> </td>
          <td><?php echo isset($item->marca) ? "{$item->marca} - {$item->modelo}" : '-';?> </td>
          <td><?php echo $item->veiculo_km; ?></td>
          <td><?php echo $this->formata_moeda($item->veiculo_valor_fipe); ?></td>
          <td><?php echo $this->formata_moeda($item->veiculo_valor_depreciacao); ?></td>
          <td><?php echo date('d/m/Y H:i:s', strtotime($item->data));?> </td>
        </tr>
      <?php } ?>
    </tbody>
</table>
<?php } else { ?>
  <p>Nenhuma depreciação de veículo registrada no peíodo</p>
<?php } ?>