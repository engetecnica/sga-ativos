<h1>Ferramentas em Estoque</h1>
<p>Relatório de Ferramentas em estoque, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>
  
<?php foreach($relatorio as $i => $obra) { ?>
<h2><?php echo $obra->codigo_obra;?></h2>
<table class="tabela">
    <thead>
        <tr>
          <th>ID Grupo</th>
          <th>Grupo Nome</th>
          <th>Em estoque</th>
          <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($obra->grupos as $j => $grupo) { ?>
        <tr>
          <td><?php echo $grupo->id_ativo_externo_grupo; ?></td>
          <td><?php echo $grupo->nome; ?></td>
          <td><?php echo $grupo->estoque;?> </td>
          <td><?php echo $grupo->total;?> </td>
        </tr>
      <?php } ?>
    </tbody>
</table>
<?php } ?>