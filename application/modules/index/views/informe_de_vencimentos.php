<div id="informe_vencimentos" class="col-12">
    <div class="top-campaign">

        <div class="row">
            <div class="col-12 col-md-9">
                <h3 class="title-3">Lançamentos a Vencer</h3>
                <p>A Vencer <?php echo (isset($informe_vencimentos['dias']) && (int) $informe_vencimentos['dias'] > 0) ? "nos próximos {$informe_vencimentos['dias']} dias" : "Hoje"; ?>, 
                    até <?php echo $this->formata_data_hora(date('Y-m-d H:i:s', strtotime("+{$informe_vencimentos['dias']} days"))); ?>
                </p>
            </div>
            <div class="col-12 col-md-3">
                <div class="input-group">
                    <select v-model="informe_vencimentos" class="form-control">
                        <option 
                            v-for="(value, dias) in informe_vencimentos_dias" 
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
        <?php if (count((array) $informe_vencimentos['relatorio']) > 0) { ?>  
            <?php if (in_array('manutencao', array_keys((array) $informe_vencimentos['relatorio'])) && count($informe_vencimentos['relatorio']->manutencao->data) > 0) { ?>  
            <strong class="title-5 m-t-30">Manutenções de Veículos</strong> 
            <table class="table table-responsive table-borderless table-striped table-earning m-b-30 m-t-10">
                <thead>
                    <tr>
                        <th>Manutenção ID</th>
                        <th>Veículo ID</th>
                        <th>Placa/ID Interna Máquina</th>
                        <th width="50%">Marca/Modelo</th>
                        <th>Fornecedor</th>
                        <th>Tipo de Manutenção</th>
                        <th>Custo</th>
                        <th>Data Manutenção</th>
                        <th>Data Vencimento</th>
                        <th>KM Prox. Revisão *</th>
                        <th>Tempo de Operação Prox. Revisão *</th>
                        <th>Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($informe_vencimentos['relatorio']->manutencao->data as $i => $manutencao) { ?>
                    <tr>
                        <td><?php echo $manutencao->id_ativo_veiculo_manutencao; ?></td>
                        <td><?php echo $manutencao->id_ativo_veiculo; ?></td>
                        <td><?php echo $manutencao->veiculo_placa ?: $manutencao->id_interno_maquina; ?></td>
                        <td><?php echo $manutencao->veiculo; ?></td>
                        <td><?php echo $manutencao->fornecedor; ?></td>
                        <td><?php echo $manutencao->servico; ?></td>
                        <td><?php echo $this->formata_moeda($manutencao->veiculo_custo); ?></td>
                        <td><?php echo $this->formata_data($manutencao->data_entrada);?> </td>
                        <td><?php echo $this->formata_data($manutencao->data_vencimento); ?> </td>
                        <td><?php echo $manutencao->veiculo_km_proxima_revisao > 0 ? ($manutencao->veiculo_km_proxima_revisao - $manutencao->veiculo_km_atual). " KM" : "-"; ?> </td>
                        <td><?php echo isset($manutencao->horas_credito) && isset($manutencao->horas_debito) ? ($manutencao->horas_credito - $manutencao->horas_debito) . " Horas" : "-"; ?> </td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo base_url("ativo_veiculo/gerenciar/manutencao/editar/{$manutencao->id_ativo_veiculo}/{$manutencao->id_ativo_veiculo_manutencao}") ?>">Mais Detalhes</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p class="m-b-30 m-t-10 text-right">* Quilometragem e/ou tempo de operação restante até a proxima revisão. </p><br>
            <?php } else { ?>
                <strong class="title-5 m-t-30">Manutenções</strong> 
                <p class="m-b-30">Nenhum item encontrado para o período.</p>
            <?php } ?>

            <strong class="title-5 m-t-30">IPVA</strong>
            <?php if (in_array('ipva', array_keys((array) $informe_vencimentos['relatorio'])) && count($informe_vencimentos['relatorio']->ipva->data) > 0) { ?>  
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
                    <?php foreach($informe_vencimentos['relatorio']->ipva->data as $i => $ipva) { ?>
                    <tr>
                        <td><?php echo $ipva->id_ativo_veiculo_ipva; ?></td>
                        <td><?php echo $ipva->id_ativo_veiculo; ?></td>
                        <td><?php echo $ipva->veiculo; ?></td>
                        <td><?php echo $ipva->veiculo_placa; ?></td>
                        <td><?php echo $ipva->ipva_ano; ?></td>
                        <td><?php echo $this->formata_moeda($ipva->ipva_custo); ?></td>
                        <td><?php echo $this->formata_data($ipva->ipva_data_pagamento);?> </td>
                        <td><?php echo $this->formata_data($ipva->ipva_data_vencimento);?> </td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo base_url("ativo_veiculo/gerenciar/ipva/editar/{$ipva->id_ativo_veiculo}/{$ipva->id_ativo_veiculo_ipva}") ?>">Mais Detalhes</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <p class="m-b-30">Nenhum item encontrado para o período.</p>
            <?php } ?>

            <strong class="title-5 m-t-30">Seguro</strong>
            <?php if (in_array('seguro', array_keys((array) $informe_vencimentos['relatorio'])) && count($informe_vencimentos['relatorio']->seguro->data) > 0) { ?>  
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
                    <?php foreach($informe_vencimentos['relatorio']->seguro->data as $i => $seguro) { ?>
                    <tr>
                        <td><?php echo $seguro->id_ativo_veiculo_seguro; ?></td>
                        <td><?php echo $seguro->id_ativo_veiculo; ?></td>
                        <td><?php echo $seguro->veiculo; ?></td>
                        <td><?php echo $seguro->veiculo_placa; ?></td>
                        <td><?php echo ucfirst($seguro->fipe_mes_referencia); ?></td>
                        <td><?php echo $this->formata_moeda($seguro->seguro_custo); ?></td>
                        <td><?php echo $this->formata_data($seguro->carencia_inicio);?> </td>
                        <td><?php echo $this->formata_data($seguro->carencia_fim);?> </td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo base_url("ativo_veiculo/gerenciar/seguro/editar/{$seguro->id_ativo_veiculo}/{$seguro->id_ativo_veiculo_seguro}") ?>">Mais Detalhes</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <p class="m-b-30">Nenhum item encontrado para o período.</p>
            <?php } ?>
      

       
            <strong class="title-5 m-t-30">Certificado de Calibação/Aferição</strong>   
            <?php if (in_array('calibracao', array_keys((array) $informe_vencimentos['relatorio'])) && count($informe_vencimentos['relatorio']->calibracao->data) > 0) { ?>  
            <table class="table table-responsive table-borderless table-striped table-earning m-b-30 m-t-10">
                <thead>
                    <tr>
                        <th>ID Certificado</th>
                        <th width="20%">Ativo</th>
                        <th>Data de Inclusão</th>
                        <th>Data de Vencimento</th>
                        <th>Situação</th>
                        <th width="30%">Observação</th>
                        <th>Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($informe_vencimentos['relatorio']->calibracao->data as $certificado) {?>
                    <tr>
                        <td><?php echo $certificado->id_certificado; ?></td>
                        <td><?php echo "{$certificado->ativo_codigo} - {$certificado->ativo_nome}"; ?></td>
                        <td><?php echo $this->formata_data($certificado->data_inclusao);?> </td>
                        <td><?php echo $this->formata_data($certificado->data_vencimento);?> </td>
                        <td>
                            <?php 
                                $vigencia = $certificado->vigencia ? 'Vigente' : 'Expirado';
                                $class = $certificado->vigencia ? "info" : "warning";
                            ?>
                            <span class="badge badge-<?php echo $class; ?>"><?php echo $vigencia; ?></span>
                        </td>
                        <td><?php echo $certificado->observacao; ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo base_url("ativo_externo/certificado_de_calibracao/{$certificado->id_ativo_externo}/{$certificado->id_certificado}") ?>">Mais Detalhes</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <p class="m-b-30">Nenhum item encontrado para o período.</p>
            <?php } ?>
        
        <?php } else {  ?>
            <p>Nenhum item encontrado para o período.</p>
        <?php } ?>
        </div>
        </div>
    </div>
</div>

<script>
    var informe_vencimentos = new Vue({
        el: "#informe_vencimentos",
        data(){
            return {
                pinned: 0,
                informe_vencimentos: 0,
                informe_vencimentos_dias: {
                    5 : 'em 5 Dias',
                    15: 'em 15 Dias',
                    30: 'em 30 Dias',
                }
            }
        },
        methods: {
            setPinned(){
                this.pinned = this.informe_vencimentos
                localStorage.pinned = this.pinned
                window.location.href = `/?informe_vencimentos=${this.pinned}#informe_vencimentos`
            }
        },
        created(){
            if (localStorage.pinned != undefined) {
                this.pinned = parseInt(localStorage.pinned)
            } else {
                let dias = parseInt('<?php echo $informe_vencimentos['dias']; ?>')
                if (dias >= 0) {
                    this.pinned = dias
                } else {
                    this.pinned = 30
                }
            }
            this.informe_vencimentos = this.pinned
        }
    })
</script>