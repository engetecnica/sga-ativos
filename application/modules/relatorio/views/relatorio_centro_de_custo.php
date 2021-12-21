<page>
<style media="all"><?php echo $css;?></style>
<header>
    <img src="<?php echo $header;?>">
</header>
  <h1>Centro de Custo</h1>
  <p>Relatório de sentro de custo, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>
  
  <h2>Ferramentas</h2>
  <?php if(count($relatorio->ferramentas) > 0) { ?>
  <table class="tabela">
      <thead>
          <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Nome</th>
            <th>Data</th>
            <th>Custo</th>
          </tr>
      </thead>
      <tbody>
        <?php foreach($relatorio->ferramentas as $ferramenta) { ?>
          <tr>
            <td><?php echo $ferramenta->id_ativo_externo; ?></td>
            <td><?php echo $ferramenta->codigo; ?></td>
            <td><?php echo $ferramenta->nome; ?></td>
            <td><?php echo date('d/m/Y H:i:s', strtotime($ferramenta->data_inclusao)); ?></td>
            <td><?php echo $this->formata_moeda($ferramenta->valor); ?></td>
          </tr>
        <?php } ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Total</td>
            <td><?php echo $relatorio->ferramentas_total; ?></td>
          </tr>
      </tbody>
  </table>
  <?php } else { ?>
    <p>Nenhuma Ferramenta registrada no período</p>
  <?php } ?>


  <h2>Equipamentos</h2>
  <?php if(count($relatorio->equipamentos) > 0) { ?>
  <table class="tabela">
      <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Marca</th>
            <th>Registro</th>
            <th>Custo</th>
          </tr>
      </thead>
      <tbody>
          <?php foreach($relatorio->equipamentos as $equipamento) { ?>
          <tr>
            <td><?php echo $equipamento->id_ativo_interno; ?></td>
            <td><?php echo $equipamento->nome; ?></td>
            <td><?php echo isset($equipamento->marca) ? $equipamento->marca : '-'; ?></td>
            <td><?php echo date('d/m/Y H:i:s', strtotime($equipamento->data_inclusao)); ?></td>
            <td><?php echo $this->formata_moeda($equipamento->valor); ?></td>
          </tr>
        <?php } ?>
         <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Total</td>
            <td><?php echo $relatorio->equipamentos_total; ?></td>
          </tr>
      </tbody>
  </table>
  <?php } else { ?>
    <p>Nenhum Equipamento registrado no peíodo</p>
  <?php } ?>

  <h2>Equipamentos Manutenções</h2>
  <?php if(count($relatorio->equipamentos_manutecoes) > 0) { ?>
  <table class="tabela">
      <thead>
          <tr>
            <th>ID Manutenção</th>
            <th>ID Equipamento</th>
            <th>Equipamento</th>
            <th>Data Saída</th>
            <th>Data Retordo</th>
            <th>Custo</th>
          </tr>
      </thead>
      <tbody>
          <?php foreach($relatorio->equipamentos_manutecoes as $manutencao) { ?>
          <tr>
            <td><?php echo $manutencao->id_manutencao; ?></td>
            <td><?php echo $manutencao->id_ativo_interno; ?></td>
            <td><?php echo $manutencao->nome; ?></td>
            <td><?php echo date('d/m/Y H:i:s', strtotime($manutencao->data_saida)); ?></td>
            <td><?php echo isset($manutencao->data_retorno) ? date('d/m/Y H:i:s', strtotime($manutencao->data_retorno)) : '-'; ?></td>
            <td><?php echo $this->formata_moeda($manutencao->manutencao_valor); ?></td>
          </tr>
        <?php } ?>
         <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Total</td>
            <td><?php echo $relatorio->equipamentos_manutecoes_total; ?></td>
          </tr>
      </tbody>
  </table>
  <?php } else { ?>
    <p>Nenhuma manutenção de equipamento registrada no peíodo</p>
  <?php } ?>

  <!-- <h2>Veiculos Abastecimentos</h2>
  <?php //if(count($relatorio->veiculos_abastecimentos) > 0) { ?>
  <table class="tabela">
      <thead>
          <tr>
            <th>ID Abastecimento</th>
            <th>ID Veículo</th>
            <th>Placa</th>
            <th>Km Inicial</th>
            <th>Km Final</th>
            <th>Quant. em Litros</th>
            <th>Data</th>
            <th>Custo por Litro</th>
            <th>Custo Total</th>
          </tr>
      </thead>
      <tbody>
        <?php foreach($relatorio->veiculos_abastecimentos as $i => $abastecimento) { ?>
          <tr>
            <td><?php echo $abastecimento->id_ativo_veiculo_quilometragem; ?></td>
            <td><?php echo $abastecimento->id_ativo_veiculo; ?></td>
            <td><?php echo $abastecimento->veiculo_placa; ?></td>
            <td><?php echo $abastecimento->veiculo_km_inicial;?></td>
            <td><?php echo $abastecimento->veiculo_km_final;?></td>
            <td><?php echo $abastecimento->veiculo_litros; ?></td>
            <td><?php echo date('d/m/Y H:i:s', strtotime($abastecimento->data));?> </td>
            <td><?php echo $this->formata_moeda($abastecimento->veiculo_custo); ?></td>
            <td><?php echo $this->formata_moeda($abastecimento->veiculo_custo_total); ?></td>
          </tr>
        <?php } ?>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>Total</td>
          <td><?php echo $relatorio->veiculos_abastecimentos_total; ?></td>
          </tr>
      </tbody>
  </table>
  <?php // } else { ?>
    <p>Nenhum abastecimento de veículo registrado no peíodo</p>
  <?php //} ?> -->

  <h2>Veiculos Manutenções</h2>
  <?php if(count($relatorio->veiculos_manutecoes) > 0) { ?>
    <table class="tabela">
      <thead>
          <tr>
            <th>ID Manutenção</th>
            <th>ID Veículo</th>
            <th>Placa</th>
            <th>Marca/Modelo</th>
            <th>Tipo</th>
            <th>Kilometragem</th>
            <th>Fornecedor</th>
            <th>Data</th>
            <th>Observação</th>
            <th>Custo</th>
          </tr>
      </thead>
      <tbody>
        <?php foreach($relatorio->veiculos_manutecoes as $i => $manutencao) { ?>
          <tr>
            <td><?php echo $manutencao->id_ativo_veiculo_manutencao; ?></td>
            <td><?php echo $manutencao->id_ativo_veiculo; ?></td>
            <td><?php echo $manutencao->veiculo_placa ?: $manutencao->id_interno_maquina; ?></td>
            <td><?php echo $manutencao->marca ? "{$manutencao->marca} - {$manutencao->modelo}" : '-'; ?></td>
            <td><?php echo ucfirst($manutencao->tipo_veiculo); ?></td>
            <td><?php echo $manutencao->veiculo_km_atual; ?></td>
            <td><?php echo $manutencao->fornecedor; ?></td>
            <td><?php echo date('d/m/Y H:i:s', strtotime($manutencao->data));?> </td>
            <td><?php echo $manutencao->veiculo_observacoes; ?></td>
            <td><?php echo $this->formata_moeda($manutencao->veiculo_custo); ?></td>
          </tr>
        <?php } ?>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>Total</td>
          <td><?php echo $relatorio->veiculos_manutecoes_total; ?></td>
          </tr>
      </tbody>
    </table>
  <?php } else { ?>
    <p>Nenhuma manutenção de veículo registrada no peíodo</p>
  <?php } ?>

<footer>
  <img src="<?php echo $footer; ?>"><br>
  <small><b>ENGETÉCNICA ENGENHARIA E CONSTRUÇÃO LTDA</b>, Rua João Bettega, n.1160, Portão, Curitiba-PR | Fone: (41) 4040-4676</small>
</footer>
</page>