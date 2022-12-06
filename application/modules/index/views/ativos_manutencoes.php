<div class="col-12">
    <div class="top-campaign">
        <h3 class="title-3">Manutenções De Equipamentos</h3>
        <p>Acompanhamento de manutenções em ativos internos </p><br>
        <?php if (count($ativo_interno_manutencoes) > 0){ ?>
        <table class="table table-responsive table-borderless table-striped table-earning">
            <thead>
                <tr>
                    <th>Manutenção ID</th>
                    <th>Equipamento ID</th>
                    <th width="30%">Equipamento/Marca</th>
                    <th>Obra</th>
                    <th>Situação</th>
                    <th>Observações</th>
                    <th>Data Saída</th>
                    <th>Data Retorno</th>
                    <th>Gerenciar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ativo_interno_manutencoes as $i => $manutencao) { $i++; ?>
                <tr>
                    <td><a href="<?php echo base_url("/ativo_interno/manutencao_editar/{$manutencao->id_ativo_interno}/{$manutencao->id_manutencao}"); ?>"><?php echo $manutencao->id_manutencao;?></a></td>
                    <td><a href="<?php echo base_url("/ativo_interno/editar/{$manutencao->id_ativo_interno}"); ?>"><?php echo $manutencao->id_ativo_interno;?></a></td>
                    <td><?php echo "{$manutencao->nome} | {$manutencao->marca}";?> </td>
                    <td><?php echo $manutencao->codigo_obra;?></td>
                    <td>
                        <?php $situacao = $this->get_situacao_manutencao($manutencao->situacao);?>
                        <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                    </td>
                    <td><a href="#" onclick="verObs('<?php echo $manutencao->id_manutencao;?>', 'ativo_interno')"><?php echo count($manutencao->observacoes);?></a></td>
                    <td><?php echo $this->formata_data($manutencao->data_saida);?> </td>
                    <td><?php echo $this->formata_data($manutencao->data_retorno);?> </td>
                    <td>
                        <div class="btn-group">
                            <button 
                                class="btn btn-secondary btn-sm dropdown-toggle" 
                                type="button"
                                data-toggle="dropdown" 
                                aria-haspopup="true" 
                                aria-expanded="false"
                            >
                                Gerenciar
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item " href="<?php echo base_url("ativo_interno/manutencao_editar/{$manutencao->id_ativo_interno}/{$manutencao->id_manutencao}"); ?>"><i class="fas fa-edit"></i> Editar</a>
                                <?php if (count($manutencao->observacoes) > 0) {?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" onclick="verObs('<?php echo $manutencao->id_manutencao;?>', 'ativo_interno')" ><i class="fa fa-comments"></i>&nbsp; Ver Observações</a>
                                <?php } ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else {  ?>
            <p>Nenhuma manutenção de Equipamento em andamento ou pendente</p>
        <?php } ?>
    </div>
</div>

<div class="col-12">
    <div class="top-campaign">
        <h3 class="title-3">Manutenções De Ferramentas</h3>
        <p>Acompanhamento de manutenções em ativos externos </p><br>
        <?php if (count($ativo_externo_manutencoes) > 0){ ?>
        <table class="table table-responsive table-borderless table-striped table-earning">
            <thead>
                <tr>
                    <th>Manutenção ID</th>
                    <th>Equipamento ID</th>
                    <th width="30%">Codigo/Equipamento</th>
                    <th>Obra</th>
                    <th>Situação</th>
                    <th>Observações</th>
                    <th>Data Saída</th>
                    <th>Data Retorno</th>
                    <th>Gerenciar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ativo_externo_manutencoes as $i => $manutencao) { $i++; ?>
                <tr>
                    <td><a href="<?php echo base_url("/ativo_externo/manutencao_editar/{$manutencao->id_ativo_externo}/{$manutencao->id_manutencao}"); ?>"><?php echo $manutencao->id_manutencao;?></a></td>
                    <td><a href="<?php echo base_url("/ativo_externo/editar/{$manutencao->id_ativo_externo}"); ?>"><?php echo $manutencao->id_ativo_externo;?></a></td>
                    <td><?php echo "{$manutencao->codigo} | {$manutencao->nome}"; ?> </td>
                    <td><?php echo $manutencao->codigo_obra;?></td>
                    <td>
                        <?php $situacao = $this->get_situacao_manutencao($manutencao->situacao);?>
                        <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                    </td>
                    <td><a href="#" onclick="verObs('<?php echo $manutencao->id_manutencao;?>', 'ativo_externo')"><?php echo count($manutencao->observacoes);?></a></td>
                    <td><?php echo $this->formata_data($manutencao->data_saida);?> </td>
                    <td><?php echo $this->formata_data($manutencao->data_retorno);?> </td>
                    <td>
                        <div class="btn-group">
                            <button 
                                class="btn btn-secondary btn-sm dropdown-toggle" 
                                type="button"
                                data-toggle="dropdown" 
                                aria-haspopup="true" 
                                aria-expanded="false"
                            >
                                Gerenciar
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item " href="<?php echo base_url("ativo_externo/manutencao_editar/{$manutencao->id_ativo_externo}/{$manutencao->id_manutencao}"); ?>"><i class="fas fa-edit"></i> Editar</a>
                                <?php if (count($manutencao->observacoes) > 0) {?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" onclick="verObs('<?php echo $manutencao->id_manutencao;?>', 'ativo_externo')" ><i class="fa fa-comments"></i>&nbsp; Ver Observações</a>
                                <?php } ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else {  ?>
            <p>Nenhuma manutenção de Ferramenta em andamento ou pendênte</p>
        <?php } ?>
    </div>
</div>

<div class="modal" tabindex="1001" role="dialog" id="index_obs_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-comments"></i>&nbsp; Observações</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="obs_modal">
                <div v-for="obs in observacoes" class="index_obs">
                    <div class="body">
                        <div>
                            <img v-if="obs.avatar" :src="`${base_url}assets/uploads/avatar/${obs.avatar}`" alt="Imagem do usuário">
                            <img v-else src="<?php echo base_url('assets/images/icon/avatar-01.jpg'); ?>" alt="Imagem do usuário" />
                            <div>
                                <strong>{{obs.nome}}</strong><br>
                                <small><b>Criado:</b> {{ window.formata_data_hora(obs.data_criacao) }}</small><br>
                                <small v-if="obs.data_edicao"><b>Atualizado:</b> {{ window.formata_data_hora(obs.data_edicao) }}</small> 
                            </div>
                        </div>
                        <p>{{obs.texto}}</p>
                    </div>
                </div>
                <p class="padding: 40px 20px;" v-if="observacoes.length === 0">Nenhuma observação encontrada para a manutenção</p>
            </div>
        </div>
    </div>
</div>

<script>
    var obs_modal = new Vue({
        el: "#obs_modal",
        data(){
            return {
                observacoes : [],
            }
        }
    })

    var ativo_interno_manutencoes = JSON.parse(`<?php echo json_encode($ativo_interno_manutencoes);?>`) || []
    var ativo_externo_manutencoes = JSON.parse(`<?php echo json_encode($ativo_externo_manutencoes);?>`) || []

    function verObs(id_manutencao, tipo){
        event.preventDefault()
        switch(tipo) {
            case "ativo_interno":
                obs_modal.observacoes = ativo_interno_manutencoes.find((m) => m.id_manutencao == id_manutencao).observacoes || null
            break;

            case "ativo_externo":
                obs_modal.observacoes = ativo_externo_manutencoes.find((m) => m.id_manutencao == id_manutencao).observacoes || null
            break;
        }
        $('#index_obs_modal').modal({show: true})
    }
</script>