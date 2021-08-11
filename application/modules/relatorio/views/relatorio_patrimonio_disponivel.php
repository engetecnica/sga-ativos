<page>
<style media="all"><?php echo $css;?></style>
<header>
    <img src="<?php echo $header;?>">
</header>
  <h1>Patromônio Diponível</h1>
  <p>Relatório de patromônio diponível, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>
  
  <?php foreach($relatorio->obras as $obra) { ?>
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
          </tr>
      </thead>
      <tbody>
        <?php foreach($obra->ferramentas as $ferramenta) { ?>
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
            <th>Registro</th>
            <th>Descarte</th>
            <th>Situação</th>
          </tr>
      </thead>
      <tbody>
          <?php foreach($obra->equipamentos as $equipamento) { ?>
          <tr>
            <td><?php echo $equipamento->id_ativo_interno; ?></td>
            <td><?php echo $equipamento->nome; ?></td>
            <td><?php echo date('d/m/Y H:i:s', strtotime($equipamento->data_inclusao)); ?></td>
            <td><?php echo isset($equipamento->data_descarte) ? date('d/m/Y H:i:s', strtotime($equipamento->data_descarte)) : '-'; ?></td>
            <td>
              <?php $situacao = $this->get_situacao($equipamento->situacao);?>
              <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
            </td>
          </tr>
        <?php } ?>
      </tbody>
  </table>
  <?php } else { ?>
    <p>Nenhum Equipamento registrado no Local</p>
  <?php }} ?>


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
          </tr>
      </thead>
      <tbody>
          <?php foreach($relatorio->veiculos as $j => $veiculo) { ?>
          <tr>
            <td><?php echo $veiculo->id_ativo_veiculo; ?></td>
            <td><?php echo $veiculo->veiculo_placa; ?></td>
            <td><?php echo ucfirst($veiculo->tipo_veiculo);?> </td>
            <td><?php echo $veiculo->veiculo;?> </td>
            <td><?php echo $veiculo->veiculo_km; ?></td>
            <td>
              <?php $situacao = $this->get_situacao($veiculo->situacao);?>
              <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
            </td>
          </tr>
        <?php } ?>
      </tbody>
  </table>
  <?php } else { ?>
    <p>Nenhum Veículo registrado</p>
  <?php } ?>


<footer>
  <img src="<?php echo $footer; ?>"><br>
  <small><b>ENGETÉCNICA ENGENHARIA E CONSTRUÇÃO LTDA</b>, Rua João Bettega, n.1160, Portão, Curitiba-PR | Fone: (41) 4040-4676</small>
</footer>
</page>