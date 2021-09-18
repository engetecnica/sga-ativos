<page>
<style media="all" ><?php echo $css;?></style>
<header>
    <img src="<?php echo $header;?>">
</header>

<h1>Informe de Vencimentos</h1>
<p>Relatório Informe de Vencimentos, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>.</p>

<?php 
   if (count($relatorio) > 0){

    foreach($relatorio as $rel) {?>

 <?php if ($rel->modulo == 'ativo_veiculo') { ?>   
    <?php if ($rel->tipo == 'manutencao') { ?>  
    <h2>Manuteções</h2>
    <table class="tabela">
        <thead>
            <tr>
                <th>Manutenção ID</th>
                <th>Veículo ID</th>
                <th>Marca/Modelo</th>
                <th>Placa</th>
                <th>Fornecedor</th>
                <th>Tipo Manutenção</th>
                <th>Data Manutenção</th>
                <th>Data Vencimento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rel->data as $i => $manutencao) { ?>
            <tr>
                <td><?php echo $manutencao->id_ativo_veiculo_manutencao; ?></td>
                <td><?php echo $manutencao->id_ativo_veiculo; ?></td>
                <td><?php echo $manutencao->veiculo; ?></td>
                <td><?php echo $manutencao->veiculo_placa; ?></td>
                <td><?php echo $manutencao->fornecedor; ?></td>
                <td><?php echo $manutencao->servico; ?></td>
                <td><?php echo date("d/m/Y", strtotime($manutencao->data_entrada));?> </td>
                <td><?php echo date("d/m/Y", strtotime($manutencao->data_vencimento));?> </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>


    <?php if ($rel->tipo == 'ipva') { ?>  
    <h2>IPVA</h2>
    <table class="tabela">
        <thead>
            <tr>
                <th>IPVA ID</th>
                <th>Veículo ID</th>
                <th>Marca/Modelo</th>
                <th>Placa</th>
                <th>Ano Referência</th>
                <th>Custo</th>
                <th>Data Pagamento</th>
                <th>Data Vencimento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rel->data as $i => $ipva) { ?>
            <tr>
                <td><?php echo $ipva->id_ativo_veiculo_ipva; ?></td>
                <td><?php echo $ipva->id_ativo_veiculo; ?></td>
                <td><?php echo $ipva->veiculo; ?></td>
                <td><?php echo $ipva->veiculo_placa; ?></td>
                <td><?php echo $ipva->ipva_ano; ?></td>
                <td><?php echo $this->formata_moeda($ipva->ipva_custo); ?></td>
                <td><?php echo date("d/m/Y", strtotime($ipva->ipva_data_pagamento));?> </td>
                <td><?php echo date("d/m/Y", strtotime($ipva->ipva_data_vencimento));?> </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>


    <?php if ($rel->tipo == 'seguro') { ?>  
    <h2>Seguro</h2>
    <table class="tabela">
        <thead>
            <tr>
                <th>Seguro ID</th>
                <th>Veículo ID</th>
                <th>Marca/Modelo</th>
                <th>Placa</th>
                <th>Mês Referência FIPE</th>
                <th>Custo</th>
                <th>Carência Inicio</th>
                <th>Carência Final</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rel->data as $i => $seguro) { ?>
            <tr>
                <td><?php echo $seguro->id_ativo_veiculo_seguro; ?></td>
                <td><?php echo $seguro->id_ativo_veiculo; ?></td>
                <td><?php echo $seguro->veiculo; ?></td>
                <td><?php echo $seguro->veiculo_placa; ?></td>
                <td><?php echo ucfirst($seguro->fipe_mes_referencia); ?></td>
                <td><?php echo $this->formata_moeda($seguro->seguro_custo); ?></td>
                <td><?php echo date("d/m/Y", strtotime($seguro->carencia_inicio));?> </td>
                <td><?php echo date("d/m/Y", strtotime($seguro->carencia_fim));?> </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>
  <?php } ?>

  <?php if ($rel->modulo == 'ativo_externo') { ?>   
    <?php if ($rel->tipo == 'calibracao') { ?>  
    <h2>Calibação/Aferição</h2>
    <table class="tabela">
        <thead>
            <tr>
                <th>Ativo ID</th>
                <th>Código</th>
                <th>Nome/Descrição</th>
                <th>Data Inclusão</th>
                <th>Data vencimento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rel->data as $i => $ativo) { ?>
            <tr>
                <td><?php echo $ativo->id_ativo_externo; ?></td>
                <td><?php echo $ativo->codigo; ?></td>
                <td><?php echo $ativo->nome; ?></td>
                <td><?php echo date("d/m/Y", strtotime($ativo->inclusao_certificado));?> </td>
                <td><?php echo date("d/m/Y", strtotime($ativo->validade_certificado));?> </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>

   <?php } ?>

  <?php } } else {  ?>
    <strong>Nenhum item encontrado para o período.</strong>
  <?php } ?>

<footer>
  <img src="<?php echo $footer; ?>"><br>
  <small><b>ENGETÉCNICA ENGENHARIA E CONSTRUÇÃO LTDA</b>, Rua João Bettega, n.1160, Portão, Curitiba-PR | Fone: (41) 4040-4676</small>
</footer>
</page>