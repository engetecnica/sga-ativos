<page>
<style media="all"><?php echo $css;?></style>
<header>
    <img src="<?php echo $header;?>">
</header>

  <h1>Equipamentos em Estoque</h1>
  <p>Relatório de Ferramentas diponíveis, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>
  
  <?php foreach($relatorio as $i => $obra) { ?>
  <h2><?php echo $obra->codigo_obra;?></h2>
  <table class="tabela">
      <thead>
          <tr>
            <th>ID Equipamento</th>
            <th>Nome</th>
            <th>Marca</th>
            <th>Registro</th>
            <th>Situação</th>
          </tr>
      </thead>
      <tbody>
          <?php foreach($obra->equipamentos as $j => $equipamento) { ?>
          <tr>
            <td><?php echo $equipamento->id_ativo_interno; ?></td>
            <td><?php echo $equipamento->nome; ?></td>
            <td><?php echo isset($equipamento->marca) ? $equipamento->marca : '-'; ?></td>
            <td><?php echo date('d/m/Y H:i:s', strtotime($obra->data_criacao)); ?></td>
            <td>
              <?php $situacao = $this->get_situacao($obra->situacao);?>
              <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
          </tr>
        <?php } ?>
      </tbody>
  </table>
  <?php } ?>


<footer>
  <img src="<?php echo $footer; ?>"><br>
  <small><b>ENGETÉCNICA ENGENHARIA E CONSTRUÇÃO LTDA</b>, Rua João Bettega, n.1160, Portão, Curitiba-PR | Fone: (41) 4040-4676</small>
</footer>
</page>