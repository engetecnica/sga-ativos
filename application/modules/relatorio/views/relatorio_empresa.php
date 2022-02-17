<h1>Empresas</h1>
<p>Relatório de Empresas, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>

<table class="tabela">
    <thead>
        <tr>
          <th>ID</th>
          <th>Nome Fantasia</th>
          <th>Razão Social</th>
          <th>CNPJ</th>
          <th>Inscrição Estadual</th>
          <th>Inscrição Municipal</th>
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
      <?php foreach($relatorio as $i => $empresa) { ?>
        <tr>
          <td><?php echo $empresa->id_empresa; ?></td>
          <td><?php echo $empresa->nome_fantasia; ?></td>
          <td><?php echo $empresa->razao_social; ?></td>
          <td><?php echo $empresa->cnpj; ?></td>
          <td><?php echo $empresa->inscricao_estadual; ?></td>
          <td><?php echo $empresa->inscricao_municipal; ?></td>
          <td><?php echo "{$empresa->endereco} {$empresa->endereco_numero} {$empresa->endereco_bairro} {$empresa->endereco_cidade}";?> </td>
          <td><?php echo $empresa->responsavel;?> </td>
          <!--
          <td><?php echo $empresa->responsavel_celular;?> </td>
          <td><?php echo $empresa->responsavel_telefone;?> </td>
          <td><?php echo $empresa->responsavel_email;?> </td>
          -->
          <td><?php echo date('d/m/Y H:i:s', strtotime($empresa->data_criacao)); ?></td>
          <td>
            <?php $situacao = $this->get_situacao($empresa->situacao);?>
            <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
          </td>
        </tr>
      <?php } ?>
    </tbody>
</table>