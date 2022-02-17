<h1>Patromônio Diponível</h1>
<p>Relatório de patromônio diponível, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>

<?php
  $ferramentas_total = 0;
  $ferramentas_valor_total = 0;
  $equipamentos_total = 0;
  $equipamentos_valor_total = 0;
  $veiculos_total = 0;
  $veiculos_valor_total = 0;
?>

<?php foreach($relatorio->obras as $obra) { if (isset($obra)) { ?>
<h2><?php echo $obra->codigo_obra;?></h2>
<h3>Ferramentas</h3>
<?php if(count($obra->ferramentas) > 0) { ?>
<table class="tabela">
    <thead>
        <tr>
          <th>ID</th>
          <th>Código</th>
          <th>Nome</th>
          <th>Registro</th>
          <th>Descarte</th>
          <th>Situação</th>
          <?php if ($relatorio->show_valor_total) { ?>
            <th>Valor</th>
          <?php } ?>
        </tr>
    </thead>
    <tbody>
      <?php  foreach($obra->ferramentas as $ferramenta) {
          $ferramentas_total++;
          $ferramentas_valor_total += $ferramenta->valor;
        ?>
        <tr>
          <td><?php echo $ferramenta->id_ativo_externo; ?></td>
          <td><?php echo $ferramenta->codigo; ?></td>
          <td><?php echo $ferramenta->nome; ?></td>
          <td><?php echo date('d/m/Y H:i:s', strtotime($ferramenta->data_inclusao)); ?></td>
          <td><?php echo isset($ferramenta->data_descarte) ? date('d/m/Y H:i:s', strtotime($ferramenta->data_descarte)) : '-'; ?></td>
          <td>
            <?php $situacao = $this->status($ferramenta->situacao);?>
            <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
          </td>
          <?php if ($relatorio->show_valor_total) { ?>
            <td><?php echo $this->formata_moeda($ferramenta->valor); ?></td>
          <?php } ?>
        </tr>
      <?php } ?>
      <?php if ($relatorio->show_valor_total) { ?>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>Valor total em Ferramentas</td>
          <td><?php echo $this->formata_moeda($obra->ferramentas_total); ?></td>
        </tr>
      <?php } ?>
    </tbody>
</table>
<?php } else { ?>
  <p>Nenhuma Ferramenta registrada no Local</p>
<?php } ?>

<h3>Equipamentos</h3>
<?php if(count($obra->equipamentos) > 0) { ?>
<table class="tabela">
    <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Marca</th>
          <th>Registro</th>
          <th>Descarte</th>
          <th>Situação</th>
          <?php if ($relatorio->show_valor_total) { ?>
            <th>Valor</th>
          <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach($obra->equipamentos as $equipamento) { 
           $equipamentos_total++;
           $equipamentos_valor_total += $equipamento->valor;  
        ?>
        <tr>
          <td><?php echo $equipamento->id_ativo_interno; ?></td>
          <td><?php echo $equipamento->nome; ?></td>
          <td><?php echo isset($equipamento->marca) ? $equipamento->marca : '-'; ?></td>
          <td><?php echo date('d/m/Y H:i:s', strtotime($equipamento->data_inclusao)); ?></td>
          <td><?php echo isset($equipamento->data_descarte) ? date('d/m/Y H:i:s', strtotime($equipamento->data_descarte)) : '-'; ?></td>
          <td>
            <?php $situacao = $this->get_situacao($equipamento->situacao);?>
            <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
          </td>
          <?php if ($relatorio->show_valor_total) { ?>
            <td><?php echo $this->formata_moeda($equipamento->valor); ?></td>
          <?php } ?>
        </tr>
      <?php } ?>
      <?php if ($relatorio->show_valor_total) { ?>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>Valor total em Equipamentos</td>
          <td><?php echo $this->formata_moeda($obra->equipamentos_total); ?></td>
        </tr>
      <?php } ?>
    </tbody>
</table>
<?php } else { ?>
  <p>Nenhum Equipamento registrado no Local</p>
<?php }}} ?>

<h3>Veículos</h3>
<?php if(count($relatorio->veiculos) > 0) { ?>
<table class="tabela">
    <thead>
        <tr>
          <th>ID</th>
          <th>Placa</th>
          <th>Tipo</th>
          <th>Marca/Modelo</th>
          <th>Kilometragem</th>
          <th>Situação</th>
          <?php if ($relatorio->show_valor_total) { ?>
            <th>Valor FIPE</th>
          <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach($relatorio->veiculos as $j => $veiculo) {
          $veiculos_total++;
          $veiculos_valor_total += $veiculo->valor_fipe;   
        ?>
        <tr>
          <td><?php echo $veiculo->id_ativo_veiculo; ?></td>
          <td><?php echo $veiculo->veiculo_placa ?: $veiculo->id_interno_maquina; ?></td>
          <td><?php echo ucfirst($veiculo->tipo_veiculo);?> </td>
          <td><?php echo isset($veiculo->marca) ? "{$veiculo->marca} - {$veiculo->modelo}" : '-';?> </td>
          <td><?php echo $veiculo->veiculo_km; ?></td>
          <td>
            <?php $situacao = $this->get_situacao($veiculo->situacao);?>
            <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
          </td>
          <?php if ($relatorio->show_valor_total) { ?>
            <td><?php echo $this->formata_moeda($veiculo->valor_fipe); ?></td>
          <?php } ?>
        </tr>
      <?php } ?>
      <?php if ($relatorio->show_valor_total) { ?>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>Valor total em Veículos</td>
          <td><?php echo $this->formata_moeda($relatorio->veiculos_total); ?></td>
        </tr>
      <?php } ?>
    </tbody>
</table>
<?php } else { ?>
  <p>Nenhum Veículo registrado</p>
<?php } ?>

<h3>Patromônio Total</h3>
<table class="tabela">
    <thead>
        <tr>
          <th>Ferramentas Quantidade</th>
          <?php if ($relatorio->show_valor_total) { ?>
            <th>Ferramentas Valor</th>
          <?php } ?>
          <th>Equipamentos Quantidade</th>
          <?php if ($relatorio->show_valor_total) { ?>
            <th>Equipamentos Valor</th>
          <?php } ?>
          <th>Veículos Quantidade</th>
          <?php if ($relatorio->show_valor_total) { ?>
            <th>Veículos Valor</th>
          <?php } ?>
        </tr>
    </thead>
    <tbody>
        <tr>
          <td><?php echo $ferramentas_total; ?></td>
          <?php if ($relatorio->show_valor_total) { ?>
          <td><?php echo $this->formata_moeda($ferramentas_valor_total); ?></td>
          <?php } ?>
          <td><?php echo $equipamentos_total; ?></td>
          <?php if ($relatorio->show_valor_total) { ?>
          <td><?php echo $this->formata_moeda($equipamentos_valor_total); ?></td>
          <?php } ?>
          <td><?php echo $veiculos_total; ?></td>
          <?php if ($relatorio->show_valor_total) { ?>
          <td><?php echo $this->formata_moeda($veiculos_valor_total); ?></td>
          <?php } ?>
        </tr>
        <?php if ($relatorio->show_valor_total) { ?>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>Valor do Patrimônio</td>
          <td><?php echo $this->formata_moeda(array_sum([$ferramentas_valor_total, $equipamentos_valor_total, $veiculos_valor_total])); ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>