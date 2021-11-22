<?php $this->load->view('email_top', ['ilustration' => ['schedule_meeting'], "assunto" => "Informe de Vencimentos", "dias" => $dias]); ?>

<strong style="<?php echo $styles['strong'];?>">A Vencer em <?php echo isset($dias) ? $dias : 30; ?> dias</strong><br>


<?php 
   if (count($relatorio) > 0){
    foreach($relatorio as $rel) {
?>
    <?php if ($rel->modulo == 'ativo_veiculo') { ?>   
    <?php if ($rel->tipo == 'manutencao') { ?>  
    <h3 style="<?php echo $styles['title'];?>">Manuteções</h3>
    <table style="<?php echo $styles['table'];?>">
        <thead style="<?php echo $styles['thead'];?>">
            <tr style="<?php echo $styles['tr'];?>">
                <th style="<?php echo $styles['tr_td_th']; echo $styles['first_th'];?>" >Manutenção ID</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Veículo ID</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Marca/Modelo</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Placa</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Fornecedor</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Tipo de Manutenção</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Data Manutenção</th>
                <th style="<?php echo $styles['tr_td_th']; echo $styles['last_th'];?>" >Data Vencimento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rel->data as $i => $manutencao) { ?>
            <tr style="<?php echo $styles['tr'];?>">
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $manutencao->id_ativo_veiculo_manutencao; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $manutencao->id_ativo_veiculo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $manutencao->veiculo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $manutencao->veiculo_placa; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $manutencao->fornecedor; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $manutencao->servico; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo date("d/m/Y", strtotime($manutencao->data_entrada));?> </td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo date("d/m/Y", strtotime($manutencao->data_vencimento));?> </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>


    <?php if ($rel->tipo == 'ipva') { ?>  
    <h3 style="<?php echo $styles['title'];?>">IPVA</h3>
    <table style="<?php echo $styles['table'];?>">
        <thead style="<?php echo $styles['thead'];?>">
            <tr style="<?php echo $styles['tr'];?>">
                <th style="<?php echo $styles['tr_td_th']; echo $styles['first_th'];?>" >IPVA ID</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Veículo ID</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Marca/Modelo</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Placa</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Ano Referência</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Custo</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Data Pagamento</th>
                <th style="<?php echo $styles['tr_td_th']; echo $styles['last_th'];?>" >Data Vencimento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rel->data as $i => $ipva) { ?>
            <tr style="<?php echo $styles['tr']; echo $i + 1 % 2 == 0 ? $styles['tr']  : $styles['tr2'];?>">
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ipva->id_ativo_veiculo_ipva; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ipva->id_ativo_veiculo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ipva->veiculo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ipva->veiculo_placa; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ipva->ipva_ano; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_moeda($ipva->ipva_custo); ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo date("d/m/Y", strtotime($ipva->ipva_data_pagamento));?> </td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo date("d/m/Y", strtotime($ipva->ipva_data_vencimento));?> </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>


    <?php if ($rel->tipo == 'seguro') { ?>  
    <h3 style="<?php echo $styles['title'];?>">Seguro</h3>
    <table style="<?php echo $styles['table'];?>">
        <thead style="<?php echo $styles['thead'];?>">
            <tr style="<?php echo $styles['tr'];?>">
                <th style="<?php echo $styles['tr_td_th']; echo $styles['first_th'];?>" >Seguro ID</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Veículo ID</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Marca/Modelo</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Placa</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Mês Referência FIPE</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Custo</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Carência Inicio</th>
                <th style="<?php echo $styles['tr_td_th']; echo $styles['last_th'];?>" >Carência Final</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rel->data as $i => $seguro) { ?>
            <tr style="<?php echo $styles['tr']; echo $i + 1 % 2 == 0 ? $styles['tr']  : $styles['tr2']; ?>">
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $seguro->id_ativo_veiculo_seguro; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $seguro->id_ativo_veiculo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $seguro->veiculo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $seguro->veiculo_placa; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo ucfirst($seguro->fipe_mes_referencia); ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_moeda($seguro->seguro_custo); ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo date("d/m/Y", strtotime($seguro->carencia_inicio));?> </td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo date("d/m/Y", strtotime($seguro->carencia_fim));?> </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>
  <?php } ?>

  <?php if ($rel->modulo == 'ativo_externo') { ?>   
    <?php if ($rel->tipo == 'calibracao') { ?>  
    <h3 style="<?php echo $styles['title'];?>">Certificado de Calibação/Aferição</h3>
    <table style="<?php echo $styles['table'];?>">
        <thead style="<?php echo $styles['thead'];?>">
            <tr style="<?php echo $styles['tr'];?>">
                <th style="<?php echo $styles['tr_td_th']; echo $styles['first_th'];?>" >Ativo ID</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Código</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Nome/Descrição</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Data Inclusão</th>
                <th style="<?php echo $styles['tr_td_th']; echo $styles['last_th'];?>" >Data vencimento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rel->data as $i => $ativo) { $i++; ?>
            <tr style="<?php echo $styles['tr']; echo ($i % 2 )== 0 ? $styles['tr']  : $styles['tr2'];?>">
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ativo->id_ativo_externo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ativo->codigo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ativo->nome; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo date("d/m/Y", strtotime($ativo->inclusao_certificado));?> </td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo date("d/m/Y", strtotime($ativo->validade_certificado));?> </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>

   <?php } ?>

  <?php } } else {  ?>
    <strong style="<?php echo $styles['strong'];?>" >Nenhum item encontrado para o período.</strong>
  <?php } ?>

<br>
<p style="<?php echo $styles['p'];?>">Relatório Informe de Vencimentos, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?></p>
<br> </br>

<?php $this->load->view('email_footer'); ?>