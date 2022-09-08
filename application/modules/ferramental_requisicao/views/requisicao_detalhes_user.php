<style type="text/css">
    .texto-historico { font-size: 12px; font-family: Tahoma; padding: 5px !important; }
</style>

<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="<?php echo base_url("ferramental_requisicao#{$requisicao->id_requisicao}"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                        <h2 class="title-1 m-b-25">Detalhes da Requisição</h2>
                        <div class="card">
                            <input type="hidden" name="id_requisicao" value="<?php echo $requisicao->id_requisicao; ?>">
                            <input type="hidden" name="id_origem" value="<?php echo $requisicao->id_origem; ?>">
                            <input type="hidden" name="id_destino" value="<?php echo $requisicao->id_destino; ?>">
                            
                            <div class="card-body">

                                <!-- Detalhes da Requisição -->
                                <table class="m-t-20 table table-responsive-md table-striped table-bordered">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="20%">Requisão ID</th>
                                            <th scope="col" width="20%">Solicitação</th>
                                            <th scope="col" width="20%">Tipo</th>
                                            <th scope="col" width="5%">Status</th>
                                            <?php if (isset($requisicao->requisicao) | isset($requisicao->devolucao)) { ?>
                                                <th><?php echo $requisicao->tipo == 1 ? 'Devolução' : 'Requisição' ?></th>
                                            <?php } ?>
                                            <?php if (isset($requisicao->id_requisicao_mae)) { ?>
                                                <th>Requisição de Origem</th>
                                            <?php } ?>
                                            <?php if (isset($requisicao->id_requisicao_filha) && isset($requisicao->data_inclusao_filha)) { ?>
                                                <th>Requisição Complementar</th>
                                            <?php } ?>
                                            <th>Gerenciar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $requisicao->id_requisicao; ?></td>
                                            <td><?php echo date("d/m/Y H:i", strtotime($requisicao->data_inclusao)); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo $requisicao->tipo == 1 ? 'primary': 'secondary';?>"><?php echo $requisicao->tipo == 1 ? 'Requisição': 'Devolução';?></span>
                                            </td>
                                            <td width="10%">
                                                <?php $status = $this->status($requisicao->status); ?>
                                                <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                            </td>
                                            <?php if (isset($requisicao->requisicao) | isset($requisicao->devolucao)) { ?>
                                            <td> 
                                                <?php $relativa = $requisicao->tipo == 1 ? $requisicao->devolucao : $requisicao->requisicao; ?>
                                                <a class="btn btn-sm btn-outline-primary" href="<?php echo base_url("ferramental_requisicao/detalhes/{$relativa->id_requisicao}"); ?>">
                                                    <?php echo $requisicao->tipo == 1 ? 'Ver Devolução' : 'Ver Requisição'?>
                                                </a>
                                            </td>
                                            <?php } ?>
                                            <?php if (isset($requisicao->id_requisicao_mae)) { ?>
                                                <td scope="col" width="30%"> 
                                                    <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao_mae}"); ?>">
                                                        Ver Requisição de Origem
                                                    </a>
                                                </td>
                                            <?php } ?>
                                            <?php if (isset($requisicao->id_requisicao_filha) && isset($requisicao->data_inclusao_filha)) { ?>
                                                <td scope="col" width="30%"> 
                                                    <a class="btn btn-sm btn-outline-primary" href="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao_filha}"); ?>">
                                                        Ver Requisição Complementar
                                                    </a>
                                                </td>
                                            <?php } ?>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button id="ferramental_requisicao_detalhes" type="button" class="btn btn-<?php echo $status['class'];?> btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Gerenciar
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="ferramental_requisicao_detalhes">
                                                    <?php if (($requisicao->status == 1) && ($user->id_usuario == $requisicao->id_solicitante || $user->id_obra == $requisicao->id_destino)) {?>
                                                        <a 
                                                            class="dropdown-item  confirmar_registro" href="javascript:void(0);"
                                                            data-tabela="<?php echo base_url("ferramental_requisicao");?>" 
                                                            data-title="Remover Requisição" data-acao="Remover"  data-redirect="true"
                                                            data-href="<?php echo base_url("ferramental_requisicao/deletar/{$requisicao->id_requisicao}");?>"
                                                        >
                                                            <i class="fa fa-trash"></i> Excluir
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                    <?php } ?>
                                                    <?php if(($requisicao->status == 2) && (($user->id_usuario == $requisicao->id_despachante) || ($user->id_obra == $requisicao->id_origem))){ ?>
                                                        <?php if($requisicao->tipo == 1){ ?>
                                                            <a
                                                            class="dropdown-item  confirmar_registro"
                                                            href="javascript:void(0)"
                                                            data-acao="Enviar" data-icon="success" data-message="false"
                                                            data-title="Enviar para Transferência" data-redirect="true"
                                                            data-text="Clique 'Sim, Enviar!' para confirmar a transferência dos itens solicitados."
                                                            data-href="<?php echo base_url("ferramental_requisicao/transferir_requisicao/{$requisicao->id_requisicao}");?>"
                                                            data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>"
                                                            >
                                                                <i class="fa fa-truck 4x" aria-hidden="true"></i>&nbsp;
                                                                Enviar para Transferência
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                        <?php  } ?>

                                                        <?php if($requisicao->tipo == 2){ ?>
                                                            <a
                                                            class="dropdown-item  confirmar_registro"
                                                            href="javascript:void(0)"
                                                            data-acao="Enviar" data-icon="success" data-message="false"
                                                            data-title="Enviar para Transferência" data-redirect="true"
                                                            data-text="Clique 'Sim, Enviar!' para confirmar a transferência dos itens dos itens marcados como devolvidos ou com defeito."
                                                            data-href="<?php echo base_url("ferramental_requisicao/transferir_devolucao/{$requisicao->id_requisicao}");?>"
                                                            data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>"
                                                            >
                                                                <i class="fa fa-truck" aria-hidden="true"></i>&nbsp;
                                                                Enviar para Transferência
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                        <?php  }  } ?>

                                                        <?php if ($requisicao->status == 3 && $requisicao->id_destino == $user->id_obra) {?>
                                                            <a class="dropdown-item" href="<?php echo base_url("ferramental_requisicao/manual/{$requisicao->id_requisicao}"); ?>">
                                                                <i class="fas fa-clipboard-check item-menu-interno"></i> Aceitar Manualmente
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                        <?php } ?>

                                                        <?php if ($requisicao->status == 3) { ?>
                                                            <a 
                                                                class="dropdown-item"
                                                                href="<?php echo base_url("ferramental_requisicao/gerar_romaneio/{$requisicao->id_requisicao}");?>"
                                                            >
                                                                <i class="fa fa-table"></i>&nbsp; Gerar Romaneio 
                                                            </a>
                                                        <?php } ?>

                                                        <?php if ($requisicao->status == 3 && $requisicao->romaneio) { ?>
                                                            <div class="dropdown-divider" ></div>
                                                            <a 
                                                                class="dropdown-item" target="_blank"
                                                                href="<?php echo base_url("assets/uploads/{$requisicao->romaneio}");?>"
                                                            >
                                                                <i class="fa fa-eye"></i>&nbsp; Visualizar Romaneio 
                                                            </a>
                                                            <div class="dropdown-divider" ></div>
                                                            <a 
                                                                class="dropdown-item" download
                                                                href="<?php echo base_url("assets/uploads/{$requisicao->romaneio}");?>"
                                                            >
                                                                <i class="fa fa-download"></i>&nbsp; Baixar Romaneio 
                                                            </a>
                                                            <div class="dropdown-divider" ></div>
                                                        <?php } ?>

                                                        <?php if (($user->id_usuario == $requisicao->id_solicitante || $user->id_obra == $requisicao->id_destino) && $this->ferramental_requisicao_model->permit_solicitar_items_nao_inclusos($requisicao->id_requisicao)) {?>
                                                            <a 
                                                                class="dropdown-item  confirmar_registro" href="javascript:void(0);"
                                                                data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>" 
                                                                data-title="Solicitar Itens não Inclusos" data-acao="Solicitar"  data-redirect="true"
                                                                data-href="<?php echo base_url("ferramental_requisicao/solicitar_items_nao_inclusos/{$requisicao->id_requisicao}");?>"
                                                            >
                                                                <i class="fa fa-list"></i>&nbsp; Solicitar Itens não Inclusos
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                        <?php } ?>

                                                        <?php if ($this->ferramental_requisicao_model->permit_devolver_items_requisicao($requisicao->id_requisicao)) {?>
                                                            <a 
                                                                class="dropdown-item  confirmar_registro" href="javascript:void(0);"
                                                                data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>" 
                                                                data-title="Devolver Ativos não Recebidos ou Com Defeito" data-acao="Devolver"  data-redirect="true"
                                                                data-href="<?php echo base_url("ferramental_requisicao/devolver_items_requisicao/{$requisicao->id_requisicao}");?>"
                                                            >
                                                                <i class="fa fa-list"></i>&nbsp; Devolver Ativos não Recebidos/Com Defeito
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                        <?php } ?>

                                                        <a class="dropdown-item " data-toggle="modal"  href="" data-target="#ajudaModal">
                                                            <i class="fa fa-question-circle"></i> Ajuda
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="m-t-20 table table-responsive-md table--no-card m-b-10 table-borderless table-striped table-earning">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="30%">Despachante</th>
                                            <th scope="col" width="30%">Origem</th>
                                            <th scope="col" width="30%">Solicitante</th>
                                            <th scope="col" width="5%">Destino</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $requisicao->despachante_nome ; ?></td>
                                            <td><?php echo $requisicao->origem ; ?></td>
                                            <td><?php echo $requisicao->solicitante_nome ; ?></td>
                                            <td><?php echo $requisicao->destino ; ?></td>
                                        </tr>
                                    </tbody>
                                </table> 

                                
                                <table class="m-t-20 table table-responsive-md table--no-card m-b-10 table-borderless table-striped table-earning">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="30%">Solicitado</th>
                                            <th scope="col" width="30%"><?php echo $requisicao->status == 15 ? 'Recusado' : 'Liberado'; ?></th>
                                            <th scope="col" width="30%">Transferido</th>
                                            <th scope="col" width="5%">Recebido</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo date("d/m/Y H:i", strtotime($requisicao->data_inclusao)); ?></td>
                                            <td><?php echo $requisicao->data_liberado ? date("d/m/Y H:i", strtotime($requisicao->data_liberado)) : '-'; ?></td>
                                            <td><?php echo $requisicao->data_transferido ? date("d/m/Y H:i", strtotime($requisicao->data_transferido)) : '-'; ?></td>
                                            <td><?php echo $requisicao->data_recebido ? date("d/m/Y H:i", strtotime($requisicao->data_recebido)) : '-'; ?></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <hr>

                                <?php if(!empty($requisicao->items)){ ?>
                                <h3 class="title-1 m-b-25">Itens</h3>
                                <table class="table table-responsive table--no-card table-borderless table-striped table-earning" style="min-height: 250px;">
                                        <thead>
                                            <tr class="active">
                                                <th width="30%">Id</th>
                                                <th width="30%">Item</th>
                                                <th width="30%">Qtde. Solcitada</th>
                                                <th width="30%">Qtde. Liberada</th>
                                                <th width="30%">Atualizado</th>
                                                <th width="30%">Situação</th>
                                                <th width="30%">Gerenciar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($requisicao->items as $item){ 
                                                $status = $this->status($item->status);
                                            ?>
                                            <tr>
                                                <td><?php echo $item->id_requisicao_item; ?></td>
                                                <td>
                                                    <?php if (in_array($requisicao->status, [2,3,4,9,11,14])) {?>
                                                    <a 
                                                        href="<?php echo base_url("ferramental_requisicao/detalhes_item/{$requisicao->id_requisicao}/{$item->id_requisicao_item}"); ?>"
                                                    >
                                                        <?php echo $item->nome; ?>
                                                    </a>
                                                    <?php } else { echo $item->nome; }?>
                                                </td>
                                                <td><?php echo $item->quantidade; ?></td>
                                                <td><?php echo $item->quantidade_liberada; ?></td>
                                                <td>
                                                    <?php $data = "data_{$status['slug']}"?>
                                                    <?php echo isset($item->$data) ? date("d/m/Y H:i", strtotime($item->$data)) : '-'; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="badge badge-sm badge-<?php echo $status['class']; ?>">
                                                        <?php echo $status['texto']; ?>
                                                    </button>
                                                </td>
                                                <td>
                                                <?php if ($requisicao->tipo == 1 &&  in_array($item->status, [3, 13])) {?>
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
                                                            <a 
                                                                class="dropdown-item" 
                                                                href="<?php echo base_url("ferramental_requisicao/detalhes_item/{$requisicao->id_requisicao}/{$item->id_requisicao_item}"); ?>"
                                                            >
                                                                <i class="fa fa-list-alt"></i>&nbsp; Detalhar Itens
                                                            </a>
                                                            
                                                            <?php if ($item->status == 3 && $requisicao->id_destino == $user->id_obra) {?>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item" href="<?php echo base_url("ferramental_requisicao/manual/{$requisicao->id_requisicao}/{$item->id_requisicao_item}"); ?>">
                                                                <i class="fas fa-clipboard-check item-menu-interno"></i> Aceitar Manualmente
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            <a 
                                                                class="dropdown-item confirmar_registro" href="javascript:void(0);"
                                                                data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>" 
                                                                data-title="Aceitar Todos" data-acao="Aceitar" data-redirect="true"
                                                                data-href="<?php echo base_url("ferramental_requisicao/receber_item/{$requisicao->id_requisicao}/{$item->id_requisicao_item}/4");?>"
                                                            >
                                                                <i class="fa fa-check item-menu-interno"></i>&nbsp; Aceitar Todos
                                                            </a>
                                                            <div class="dropdown-divider"></div>

                                                            <a 
                                                                class="dropdown-item confirmar_registro" href="javascript:void(0);"
                                                                data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>" 
                                                                data-title="Devolver Todos" data-acao="Devolver"  data-redirect="true"
                                                                data-href="<?php echo base_url("ferramental_requisicao/receber_item/{$requisicao->id_requisicao}/{$item->id_requisicao_item}/9");?>"
                                                            >
                                                                <i class="fa fa-truck item-menu-interno"></i>&nbsp; Devolver Todos
                                                            </a>
                                                            <?php } ?>
                                                            
                                                        </div>
                                                    </div>
                                                <?php } else { ?> 
                                                    -
                                                <?php }  ?>        
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                </table>
                                <?php } ?>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('requisicao_modal_ajuda'); ?>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->