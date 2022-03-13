<h1>Veículos Depreciação</h1>
<p>Relatório de depreciação, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>

<?php if (count($relatorio->data) > 0) {?>
<table class="tabela">
    <thead>
        <tr>
          <th>ID Depreciação</th>
          <th>Veiculo Marca/Modelo</th>
          <th>Placa / ID INterno (Máquina)</th>
          <th>Mês Referência</th>
          <th>Valor Fipe</th>
          <th>Data de Inclusão</th>
          <th>Depreciação em % *</th>
          <th>Depreciação em R$ *</th>
        </tr>
    </thead>
    <tbody>
      <?php foreach($relatorio->data as $i => $valor) {?>
        <tr>
          <td><?php echo $valor->id_ativo_veiculo_depreciacao; ?></td>
          <td><?php echo "{$valor->marca} | {$valor->modelo}"; ?></td>
          <td><?php echo $valor->veiculo_placa ?: $valor->id_interno_maquina; ?></td>
          <td><?php echo $this->formata_mes_referecia($valor->fipe_mes_referencia, $valor->fipe_ano_referencia); ?></td>
          <td><?php echo $this->formata_moeda($valor->fipe_valor); ?></td>
          <td><?php echo $this->formata_data_hora($valor->data); ?></td>
          <td style="<?php echo $valor->direcao === 'up' ? "color: green;" : "color: red;" ;?>">
              <?php echo $valor->direcao === 'up' ? "+ " : "- " ; echo "{$valor->depreciacao_porcentagem} %"; ?>
          </td>
          <td style="<?php echo $valor->direcao === 'up' ? "color: green;" : "color: red;" ;?>">
              <?php echo $valor->direcao === 'up' ? "+ " : "- " ; echo $this->formata_moeda($valor->depreciacao_valor); ?>
          </td>
        </tr>
      <?php } ?>
    </tbody>
</table>
<?php } else { ?>
  <p>Nenhuma depreciação de veículo registrada no peíodo</p>
<?php } ?>