<h1>Ferramentas Diponíveis na Obra (Em uso ou não)</h1>
<p>Relatório de Ferramentas diponíveis, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>
  
<?php 
if (isset($relatorio['obras'])) {
foreach($relatorio['obras'] as $i => $obra) { ?>
<h2><?php echo $obra->codigo_obra;?></h2>
<table class="tabela">
    <thead>
        <tr>
          <th>Grupo ID</th>
          <th>Grupo Nome</th>
          <th>Em estoque</th>
          <th>Liberado</th>
          <th>Em Trânsito</th>
          <th>Em Operação</th>
          <th>Fora de Operação</th>
          <th>Com Defeito</th>
          <th>Total de Itens</th>
          <?php if($relatorio['show_valor_total']) { ?>
          <th>Valor total em Itens do Grupo</th>
          <?php } ?>
        </tr>
    </thead>
    <tbody>
      <?php foreach($obra->grupos as $j => $grupo) { ?>
        <tr>
          <td><?php echo $grupo->id_ativo_externo_grupo; ?></td>
          <td><?php echo $grupo->nome; ?></td>
          <td><?php echo $grupo->estoque;?> </td>
          <td><?php echo $grupo->liberado; ?> </td>
          <td><?php echo $grupo->transito; ?> </td>
          <td><?php echo $grupo->emoperacao; ?> </td>
          <td><?php echo $grupo->foradeoperacao; ?> </td>
          <td><?php echo $grupo->comdefeito; ?> </td>
          <td><?php echo $grupo->total;?> </td>
          <?php if($relatorio['show_valor_total']) { ?>
          <td><?php echo $this->formata_moeda($grupo->total_grupo);?> </td>
          <?php } ?>
        </tr>
      <?php } ?>
        
      <?php if($relatorio['show_valor_total']) { ?>
        <tr>
          <td></td>
          <td></td>
          <!--<td></td>-->
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td class="td-destak">Total Obra</td>
          <td class="td-destak"><?php echo $this->formata_moeda($obra->total_obra) ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php } } ?>