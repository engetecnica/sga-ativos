<page>
<style media="all"><?php echo $css;?></style>
<header>
    <img src="<?php echo $header;?>">
</header>

  <h1>Obras</h1>
  <p>Relatório de Obras, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>

  <table class="tabela">
      <thead id="">
          <tr>
            <th>ID</th>
            <th>Código/Nome</th>
            <th>Empresa</th>
            <th>Endereço</th>
            <th>Responsável</th>
            <!--
            <th>Responsável Celular</th>
            <th>Responsável Telefone</th>
            <th>Responsável Email</th>
            -->
            <th>Registro</th>
            <th>Situação</th>
          </tr>
      </thead>
      <tbody>
        <?php foreach($relatorio as $i => $obra) { ?>
          <tr>
            <td><?php echo $obra->id_obra; ?></td>
            <td><?php echo $obra->codigo_obra; ?></td>
            <td><?php echo $obra->empresa; ?></td>
            <td><?php echo "{$obra->endereco} {$obra->endereco_numero} {$obra->endereco_bairro} {$obra->endereco_cidade}";?> </td>
            <td><?php echo $obra->responsavel;?> </td>
            <!--
            <td><?php echo $obra->responsavel_celular;?> </td>
            <td><?php echo $obra->responsavel_telefone;?> </td>
            <td><?php echo $obra->responsavel_email;?> </td>
            -->
            <td><?php echo date('d/m/Y H:i:s', strtotime($obra->data_criacao)); ?></td>
            <td>
              <?php $situacao = $this->get_situacao($obra->situacao);?>
              <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
            </td>
          </tr>
        <?php } ?>
      </tbody>
  </table>


<footer>
  <img src="<?php echo $footer; ?>">
</footer>
</page>