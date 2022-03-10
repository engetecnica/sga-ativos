<?php $this->load->view('email_top', ['ilustration' => true, "assunto" => "Informe de Vencimentos", "dias" => $dias]); ?>

<?php 
   if (count((array) $relatorio) > 0){
    foreach($relatorio as $rel) {
?>
    <?php if ($rel->modulo == 'ativo_veiculo') { ?>   
    <?php if ($rel->tipo == 'manutencao') { ?> 
        <p style="<?php echo $styles['p'];?>">A Vencer em <?php echo isset($dias) ? $dias : 30; ?> dias ou de acordo com Quilometragem e/ou Tempo de Operação (Para Manutenções de veículos)</p>
        <h3 style="<?php echo $styles['title'];?>">Manutenções de Veículos</h3>
    
    <table style="<?php echo $styles['table'];?>">
        <thead style="<?php echo $styles['thead'];?>">
            <tr style="<?php echo $styles['tr'];?>">
                <th style="<?php echo $styles['tr_td_th']; echo $styles['first_th'];?>" >Manutenção ID</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Veículo ID</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Placa</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Marca/Modelo</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Fornecedor</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Tipo de Manutenção</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Custo</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Data Manutenção</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >KM Prox. Revisão *</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Tempo de Operação Prox. Revisão *</th>
                <th style="<?php echo $styles['tr_td_th']; echo $styles['last_th'];?>" >Data Vencimento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rel->data as $i => $manutencao) {?>
            <tr style="<?php echo $styles['tr']; echo $i % 2 == 0 ? $styles['tr']  : $styles['tr2'];?>">
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $manutencao->id_ativo_veiculo_manutencao; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $manutencao->id_ativo_veiculo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $manutencao->veiculo_placa ?: $manutencao->id_interno_maquina; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_array([$manutencao->marca, $manutencao->modelo], " | "); ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $manutencao->fornecedor; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $manutencao->servico; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_moeda($manutencao->veiculo_custo);?> </td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_data($manutencao->data_entrada);?> </td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo isset($manutencao->veiculo_km_saldo) ? $this->formata_posfix($manutencao->veiculo_km_saldo, 'KM') : "-";?> </td>
                <td style="<?php echo $styles['tr_td_th'];?>"><?php echo isset($manutencao->veiculo_horimetro_saldo) ? $this->formata_posfix($manutencao->veiculo_horimetro_saldo, 'Horas') : "-"; ?> </td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_data($manutencao->data_vencimento);?> </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <br><small style="<?php echo $styles['small'];?>">* Quilometragem e/ou tempo de operação restante até a proxima revisão. <small><br><br>
    <?php } ?>


    <?php if ($rel->tipo == 'ipva') { ?>  
    <h3 style="<?php echo $styles['title'];?>">IPVA</h3>
    <table style="<?php echo $styles['table'];?>">
        <thead style="<?php echo $styles['thead'];?>">
            <tr style="<?php echo $styles['tr'];?>">
                <th style="<?php echo $styles['tr_td_th']; echo $styles['first_th'];?>" >IPVA ID</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Veículo ID</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Marca/Modelo</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Placa / ID Interno (Máquina) </th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Ano Referência</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Custo</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Data Pagamento</th>
                <th style="<?php echo $styles['tr_td_th']; echo $styles['last_th'];?>" >Data Vencimento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rel->data as $i => $ipva) { ?>
            <tr style="<?php echo $styles['tr']; echo $i % 2 == 0 ? $styles['tr']  : $styles['tr2'];?>">
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ipva->id_ativo_veiculo_ipva; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ipva->id_ativo_veiculo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ipva->marca ." | ".$ipva->modelo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ipva->veiculo_placa ?: $ipva->id_interno_maquina; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ipva->ipva_ano; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_moeda($ipva->ipva_custo); ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_data($ipva->ipva_data_pagamento);?> </td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_data($ipva->ipva_data_vencimento);?> </td>
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
                <th style="<?php echo $styles['tr_td_th'];?>" >Placa / ID Interno (Máquina) </th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Mês Referência FIPE</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Custo</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Carência Inicio</th>
                <th style="<?php echo $styles['tr_td_th']; echo $styles['last_th'];?>" >Carência Final</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rel->data as $i => $seguro) { ?>
            <tr style="<?php echo $styles['tr']; echo $i % 2 == 0 ? $styles['tr']  : $styles['tr2']; ?>">
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $seguro->id_ativo_veiculo_seguro; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $seguro->id_ativo_veiculo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $seguro->marca ." | ".$seguro->modelo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $seguro->veiculo_placa ?: $seguro->id_interno_maquina; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo ucfirst($seguro->fipe_mes_referencia); ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_moeda($seguro->seguro_custo); ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_data($seguro->carencia_inicio);?> </td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_data($seguro->carencia_fim);?> </td>
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
            <tr style="<?php echo $styles['tr']; echo $i % 2 == 0 ? $styles['tr']  : $styles['tr2'];?>">
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ativo->id_ativo_externo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ativo->ativo_codigo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ativo->ativo_nome; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_data($ativo->data_inclusao);?> </td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_data($ativo->data_vencimento);?> </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>

   <?php } ?>


   <?php if ($rel->modulo == 'ativo_interno') { ?>   
    <?php if ($rel->tipo == 'calibracao') { ?>  
    <h3 style="<?php echo $styles['title'];?>">Manutenção de Equipamentos</h3>
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
            <tr style="<?php echo $styles['tr']; echo $i % 2 == 0 ? $styles['tr']  : $styles['tr2'];?>">
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ativo->id_ativo_externo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ativo->ativo_codigo; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $ativo->ativo_nome; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_data($ativo->data_inclusao);?> </td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_data($ativo->data_vencimento);?> </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>

   <?php } ?>

  <?php } } else {  ?>
    <p style="<?php echo $styles['p'];?>" >Nenhum item encontrado para o período.</p>
  <?php } ?>

<?php $this->load->view('email_footer'); ?>