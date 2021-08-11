<page>
<style media="all"><?php echo $css;?></style>
<header>
    <img src="<?php echo $header;?>">
</header>

  <h1>Veículos Disponíveis</h1>
  <p>Relatório de disponíveis, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>

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
            <td><?php echo $item->veiculo;?> </td>
            <td><?php echo $item->veiculo_km; ?></td>
            <td>
              <?php $situacao = $this->get_situacao($item->situacao);?>
              <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
            </td>
          </tr>
        <?php } ?>
      </tbody>
  </table>


<footer>
  <img src="<?php echo $footer; ?>"><br>
  <small><b>ENGETÉCNICA ENGENHARIA E CONSTRUÇÃO LTDA</b>, Rua João Bettega, n.1160, Portão, Curitiba-PR | Fone: (41) 4040-4676</small>
</footer>
</page>