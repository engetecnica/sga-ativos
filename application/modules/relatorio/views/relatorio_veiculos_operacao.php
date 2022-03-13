<h1>Veículos Quilometragens</h1>
<p>Relatório de tempo de operação das máquinas, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>

<?php if (count($relatorio) > 0) {?>
  <table class="tabela">
      <thead>
          <tr>
            <th>ID Veículo</th>
            <th>Veículo</th>
            <th>Tipo</th>
            <th>Placa/ID Internada</th>
            <th>Horimetro Inicial</th>
            <th>Horimetro Atual</th>
            <th>Horimetro Ultima Revisão</th>
            <th>Horimetro Próxima Revisão</th>
            <th>Horimetro Rodados</th>
            <th>Data Inclusão</th>
          </tr>
      </thead>
      <tbody>
        <?php foreach($relatorio as $veiculo) { ?>
          <tr>
            <td><?php echo $veiculo->id_ativo_veiculo; ?></td>
            <td><?php echo isset($veiculo->marca) ? "{$veiculo->marca} - {$veiculo->modelo}" : '-'; ?></td>
            <td><?php echo ucfirst($veiculo->tipo_veiculo); ?></td>
            <td><?php echo $veiculo->veiculo_placa ?: $veiculo->id_interno_maquina; ?></td>
            <td><?php echo $veiculo->horimetro_inicial ?: 0;?></td>
            <td><?php echo $veiculo->horimetro_atual ?: $veiculo->horimetro_inicial;?></td>
            <td><?php echo $veiculo->horimetro_ultima_revisao ?: 0;?></td>
            <td><?php echo $veiculo->horimetro_proxima_revisao ?: 0;?></td>
            <td><?php echo $veiculo->horimetro_rodado ?: 0;?></td>
            <td><?php echo $this->formata_data_hora($veiculo->data);?> </td>
          </tr>
        <?php } ?>
      </tbody>
  </table>
<?php } else { ?>
  <p>Nenhum abastecimento de veículo registrado no peíodo</p>
<?php } ?>