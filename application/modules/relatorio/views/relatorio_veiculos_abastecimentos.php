<page>
<style media="all"><?php echo $css;?></style>
<header>
    <img src="<?php echo $header;?>">
</header>

  <h1>Veículos Abastecimentos</h1>
  <p>Relatório de abastecimentos, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>

<?php if (count($relatorio->abastecimentos) > 0) {?>
  <table class="tabela">
      <thead>
          <tr>
            <th>#</th>
            <th>Placa</th>
            <th>Km Atual</th>
            <th>Quant. em Litros</th>
            <th>Data/Hora</th>
            <th>Custo Por Litro</th>
            <th>Custo Total</th>
          </tr>
      </thead>
      <tbody>
        <?php foreach($relatorio->abastecimentos as $i => $item) { ?>
          <tr>
            <td><?php echo ($i + 1); ?></td>
            <td><?php echo $item->veiculo_placa; ?></td>
            <td><?php echo $item->veiculo_km;?></td>
            <td><?php echo $item->veiculo_litros; ?></td>
            <td><?php echo date('d/m/Y H:i:s', strtotime($item->data));?> </td>
            <td><?php echo $this->formata_moeda($item->veiculo_custo); ?></td>
            <td><?php echo $this->formata_moeda($item->veiculo_custo_total); ?></td>
          </tr>
        <?php } ?>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Total</td>
            <td><?php echo $relatorio->total; ?></td>
          </tr>
      </tbody>
  </table>
  <?php } else { ?>
    <p>Nenhum abastecimento de veículo registrado no peíodo</p>
  <?php } ?>

<footer>
  <img src="<?php echo $footer; ?>"><br>
  <small><b>ENGETÉCNICA ENGENHARIA E CONSTRUÇÃO LTDA</b>, Rua João Bettega, n.1160, Portão, Curitiba-PR | Fone: (41) 4040-4676</small>
</footer>
</page>