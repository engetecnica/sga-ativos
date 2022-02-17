
<h1>Funcionários</h1>
<p>Relatório de Funcionários, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>

<table class="tabela">
    <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Empresa</th>
          <th>Obra</th>
          <th>Registro</th>
          <th>Situação</th>
        </tr>
    </thead>
    <tbody>
      <?php foreach($relatorio as $i => $funcionario) { ?>
        <tr>
          <td><?php echo $funcionario->id_funcionario; ?></td>
          <td><?php echo $funcionario->nome; ?></td>
          <td><?php echo $funcionario->empresa;?> </td>
          <td><?php echo $funcionario->obra;?> </td>
          <td><?php echo date('d/m/Y H:i:s', strtotime($funcionario->data_criacao)); ?></td>
          <td>
            <?php $situacao = $this->get_situacao($funcionario->situacao);?>
            <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
          </td>
        </tr>
      <?php } ?>
    </tbody>
</table>
