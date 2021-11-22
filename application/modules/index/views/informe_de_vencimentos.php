<div v-show="informe_vencimentos == <?php echo $dias;?>" class="col-12">
    <div class="top-campaign">

        <div class="row">
            <div class="col-12 col-md-9">
                <h3 class="title-3">Lançamentos a Vencer</h3>
                <p>A Vencer <?php echo (isset($dias) && (int) $dias > 0) ? "nos próximos {$dias} dias" : "Hoje"; ?>, 
                    até <?php echo $this->formata_data_hora(date('Y-m-d H:i:s', strtotime("+{$dias} days"))); ?>
                </p>
            </div>
            <div class="col-12 col-md-3">
                <div class="input-group">
                    <select v-model="informe_vencimentos" class="form-control">
                        <option 
                            v-for="(dias, value) in informe_vencimentos_dias" 
                            :key="dias" 
                            :value="dias" 
                            :selected="pinned == informe_vencimentos"
                        >
                            A vencer {{value}}
                        </option>
                    </select>
                    <div class="input-group-append">
                        <span @click="setPinned()" 
                            style="cursor: pointer;" :class="informe_vencimentos === pinned ? 'btn-primary' : 'btn-secondary'" 
                            class="input-group-text"
                        >
                            <i 
                                onMouseOver="this.style.color='#FFF'"
                                :style="informe_vencimentos === pinned ? 'color: #FFF;' : 'color: #6c757d;'" 
                                class="fa fa-thumb-tack"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
        <div class="col-12 m-t-40">
        <?php 
        if (count($informe_vencimentos) > 0){
            foreach($informe_vencimentos as $rel) {?>
        <?php if ($rel->modulo == 'ativo_veiculo') { ?>   
            <?php if ($rel->tipo == 'manutencao') { ?>  
            <strong class="title-5 m-t-30">Manuteções</strong>
            <table class="table table-responsive table-borderless table-striped table-earning m-b-30 m-t-10">
                <thead>
                    <tr>
                        <th>Manutenção ID</th>
                        <th>Veículo ID</th>
                        <th width="50%">Marca/Modelo</th>
                        <th>Placa</th>
                        <th>Fornecedor</th>
                        <th>Tipo de Manutenção</th>
                        <th>Data Manutenção</th>
                        <th>Data Vencimento</th>
                        <th>Detalhes</th>
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
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo base_url("ativo_veiculo/gerenciar/manutencao/editar/{$manutencao->id_ativo_veiculo}/{$manutencao->id_ativo_veiculo_manutencao}") ?>">Mais Detalhes</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } ?>

            <?php if ($rel->tipo == 'ipva') { ?>  
            <strong class="title-5 m-t-30">IPVA</strong>
            <table class="table table-responsive table-borderless table-striped table-earning m-b-30 m-t-10">
                <thead>
                    <tr>
                        <th>IPVA ID</th>
                        <th>Veículo ID</th>
                        <th width="50%">Marca/Modelo</th>
                        <th>Placa</th>
                        <th>Ano Referência</th>
                        <th>Custo</th>
                        <th>Data Pagamento</th>
                        <th>Data Vencimento</th>
                        <th>Detalhes</th>
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
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo base_url("ativo_veiculo/gerenciar/ipva/editar/{$ipva->id_ativo_veiculo}/{$ipva->id_ativo_veiculo_ipva}") ?>">Mais Detalhes</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } ?>

            <?php if ($rel->tipo == 'seguro') { ?>  
            <strong class="title-5 m-t-30">Seguro</strong>
            <table class="table table-responsive table-borderless table-striped table-earning m-b-30 m-t-10">
                <thead>
                    <tr>
                        <th>Seguro ID</th>
                        <th>Veículo ID</th>
                        <th width="50%">Marca/Modelo</th>
                        <th>Placa</th>
                        <th>Mês Referência FIPE</th>
                        <th>Custo</th>
                        <th>Carência Inicio</th>
                        <th>Carência Final</th>
                        <th>Detalhes</th>
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
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo base_url("ativo_veiculo/gerenciar/seguro/editar/{$seguro->id_ativo_veiculo}/{$seguro->id_ativo_veiculo_seguro}") ?>">Mais Detalhes</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } ?>
        <?php } ?>

        <?php if ($rel->modulo == 'ativo_externo') { ?>   
            <?php if ($rel->tipo == 'calibracao') { ?>  
            <strong class="title-5 m-t-30">Certificado de Calibação/Aferição</strong>
            <table class="table table-responsive table-borderless table-striped table-earning m-b-30 m-t-10">
                <thead>
                    <tr>
                        <th>Ativo ID</th>
                        <th>Código</th>
                        <th width="50%">Nome/Descrição</th>
                        <th width="25%">Data Inclusão</th>
                        <th width="25%">Data vencimento</th>
                        <th>Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rel->data as $ativo) {?>
                    <tr>
                        <td><?php echo $ativo->id_ativo_externo; ?></td>
                        <td><?php echo $ativo->codigo; ?></td>
                        <td><?php echo $ativo->nome; ?></td>
                        <td><?php echo date("d/m/Y", strtotime($ativo->inclusao_certificado));?> </td>
                        <td><?php echo date("d/m/Y", strtotime($ativo->validade_certificado));?> </td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo base_url("ativo_externo/certificado_de_calibracao/{$ativo->id_ativo_externo}") ?>">Mais Detalhes</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } ?>
        <?php } ?>
        <?php } } else {  ?>
            <p>Nenhum item encontrado para o período.</p>
        <?php } ?>
        </div>
        </div>
    </div>
</div>