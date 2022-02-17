<h1>Veículos Disponíveis</h1>
<p>Relatório de disponíveis, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>
  
<?php if (count($relatorio) > 0) {?>
<table class="tabela">
    <thead>
        <tr>
          <th>#</th>
          <th>Placa</th>
          <th>Tipo</th>
          <th>Marca/Modelo</th>
          <th>Kilometragem</th>
          <th>Situação</th>
        </tr>
    </thead>
    <tbody>
      <?php foreach($relatorio as $i => $item) { ?>
        <tr>
          <td><?php echo ($i + 1); ?></td>
          <td><?php echo $item->veiculo_placa; ?></td>
          <td><?php echo ucfirst($item->tipo_veiculo);?> </td>
          <td><?php echo isset($item->marca) ? "{$item->marca} - {$item->modelo}" : '-';?> </td>
          <td><?php echo $item->veiculo_km; ?></td>
          <td>
            <?php $situacao = $this->get_situacao($item->situacao);?>
            <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
          </td>
        </tr>
      <?php } ?>
    </tbody>
</table>
<?php } else { ?>
  <p>Nenhum veículo disponível no peíodo</p>
<?php } ?>