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
                    <form 
                        class="confirm-submit" id="requisicao_form" action="<?php echo base_url('ferramental_requisicao/liberar_requisicao'); ?>" 
                        method="post" enctype="multipart/form-data"
                        data-acao="Liberar" data-icon="success" data-message="false"
                        data-title="Liberar Requisição" data-redirect="true"
                        data-text="Clique 'Sim, Liberar!' para confirmar a liberação dos itens solicitados na Requisição."
                    > 
                        <h2 class="title-1 m-b-25">Detalhes da Requisição Administração</h2>
                        <div class="card">
                            <input type="hidden" name="id_requisicao" value="<?php echo $requisicao->id_requisicao; ?>">
                            <input type="hidden" name="id_origem" value="<?php echo $requisicao->id_origem; ?>">
                            <input type="hidden" name="id_destino" value="<?php echo $requisicao->id_destino; ?>">
                            <div class="card-body">
                                <!-- Detalhes da Requisição -->
                                <table class="table table-responsive-md table-striped table-bordered">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="30%">Requisão ID</th>
                                            <th scope="col" width="30%">Solicitação</th>
                                            <th scope="col" width="30%">Tipo</th>
                                            <th scope="col" width="30%" >Status</th>
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
                                            <td scope="col" width="30%"><?php echo $requisicao->id_requisicao; ?></td>
                                            <td scope="col" width="30%"><?php echo date("d/m/Y H:i", strtotime($requisicao->data_inclusao)); ?></td>
                                            <td scope="col" width="30%">
                                                <span class="badge badge-<?php echo $requisicao->tipo == 1 ? 'primary': 'secondary';?>"><?php echo $requisicao->tipo == 1 ? 'Requisição': 'Devolução';?></span>
                                            </td>
                                            <td scope="col" width="30%">
                                                <?php $status = $this->status($requisicao->status); ?>
                                                <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                            </td>
                                            <?php if (isset($requisicao->requisicao) | isset($requisicao->devolucao)) { ?>
                                                <td scope="col" width="30%"> 
                                                    <?php $relativa = ($requisicao->tipo == 1) ? $requisicao->devolucao : $requisicao->requisicao; ?>
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
                                                        <?php if ($requisicao->status != 1) { ?>
                                                        <a 
                                                            class="dropdown-item" 
                                                            href="<?php echo base_url("ferramental_requisicao/detalhes_item/{$requisicao->id_requisicao}"); ?>"
                                                        >
                                                            <i class="fa fa-list-alt item-menu-interno"></i> Listar de Ativos
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <?php } ?>
                                                       
                                                        <?php if($user->nivel == 1) { ?>
                                                            <?php if(in_array($requisicao->status, [1, 11])){ ?>
                                                                    <?php if ($user->id_obra == $requisicao->id_origem || ($user->id_obra != $requisicao->id_destino && !$requisicao->id_origem)) { ?>
                                                                        <a
                                                                        class="dropdown-item confirmar_registro" href="javascript:void(0)">
                                                                        <i class="fa fa-check-circle 2x"></i>&nbsp;
                                                                        <button class="" style="text-align: left !important;" type="submit" formtarget="requisicao_form" >
                                                                            Liberar Requisição
                                                                        </button>
                                                                        </a>
                                                                        
                                                                        <div class="dropdown-divider"></div>
                                                                    <?php } ?>

                                                                    <?php 
                                                                        if (
                                                                            ($user->id_usuario != $requisicao->id_solicitante && 
                                                                            ($user->id_obra == $requisicao->id_origem || (!$requisicao->id_origem && $user->nivel == 1)))
                                                                        ) { 
                                                                    ?>
                                                                        <a
                                                                            class="dropdown-item  confirmar_registro"
                                                                            href="javascript:void(0)"
                                                                            data-acao="Recusar" data-icon="warning" data-message="false"
                                                                            data-title="Recusar Requisição" data-redirect="true"
                                                                            data-text="Clique 'Sim, Recusar!' para recusar a Requisição dos itens solicitados."
                                                                            data-href="<?php echo base_url("ferramental_requisicao/recusar_requisicao/{$requisicao->id_requisicao}");?>"
                                                                            data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>"
                                                                        >
                                                                            <i class="fa fa-ban" aria-hidden="true"></i>&nbsp; Recusar Requisição
                                                                        </a>
                                                                        <div class="dropdown-divider"></div>
                                                                    <?php }  ?>
                                                                <?php } ?>

                                                                <?php 
                                                                    if((in_array($requisicao->status, [2,11])) && 
                                                                        ($user->id_usuario == $requisicao->id_despachante || $user->id_obra == $requisicao->id_origem)) { 
                                                                        $transferir_method = $requisicao->tipo == 1 ? "transferir_requisicao" : "transferir_devolucao";
                                                                ?>
                                                                    <a
                                                                        class="dropdown-item  confirmar_registro"
                                                                        href="javascript:void(0)"
                                                                        data-acao="Enviar" data-icon="success" data-message="false"
                                                                        data-title="Enviar para Transferencia" data-redirect="true"
                                                                        data-text="Clique 'Sim, Enviar!' para confirmar a transferencia dos itens solicitados."
                                                                        data-href="<?php echo base_url("ferramental_requisicao/{$transferir_method}/{$requisicao->id_requisicao}");?>"
                                                                        data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>"
                                                                    >
                                                                        <i class="fa fa-truck 4x" aria-hidden="true"></i>&nbsp; Enviar para Transferência
                                                                    </a>
                                                                    <div class="dropdown-divider"></div>
                                                                <?php  } ?>


                                                                <?php if (($requisicao->tipo == 2 && $requisicao->status == 3) && $user->id_obra == $requisicao->id_destino) {?>
                                                                    <a 
                                                                        class="dropdown-item  confirmar_registro"
                                                                        href="javascript:void(0)"
                                                                        data-acao="Receber" data-icon="info" data-message="false"
                                                                        data-title="Receber Devoluções" data-redirect="true"
                                                                        data-text="Clique 'Sim, Receber!' para confirmar a transferência de itens devolvidos ou com defeito."
                                                                        data-href="<?php echo base_url("ferramental_requisicao/receber_devolucoes/{$requisicao->id_requisicao}");?>"
                                                                        data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>" 
                                                                    >
                                                                        <i class="fas fa-clipboard-check"></i>&nbsp; Receber Devoluções
                                                                    </a>
                                                                    <div class="dropdown-divider"></div>
                                                                <?php  } ?>

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
                                                                    <div class="dropdown-divider"></div>
                                                                <?php } ?>

                                                                <?php if ($requisicao->status == 3 &&  $requisicao->romaneio) { ?>
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
                                                                    <div class="dropdown-divider"></div>
                                                                <?php } ?>
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

                                                        <?php if (($requisicao->status == 1) && ($user->id_usuario == $requisicao->id_solicitante)) {?>
                                                            <a 
                                                                class="dropdown-item  confirmar_registro" href="javascript:void(0);"
                                                                data-tabela="<?php echo base_url("ferramental_requisicao");?>" 
                                                                data-title="Excluir Requisição" data-acao="Excluir"  data-redirect="true"
                                                                data-href="<?php echo base_url("ferramental_requisicao/deletar/{$requisicao->id_requisicao}");?>"
                                                            >
                                                                <i class="fa fa-trash"></i> Excluir
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                        <?php } ?>

                                                        <a class="dropdown-item" data-toggle="modal" href="" data-target="#ajudaModal">
                                                            <i class="fa fa-question-circle"></i> Ajuda
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="m-t-20 table table-responsive-md table--no-card table-borderless table-striped table-earning">
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
                                            <td><?php echo $requisicao->origem; ?></td>
                                            <td><?php echo $requisicao->solicitante_nome ; ?></td>
                                            <td><?php echo $requisicao->destino ; ?></td>
                                        </tr>
                                    </tbody>
                                </table> 

                                <table class="m-t-20 table table-responsive-md table--no-card table-borderless table-striped table-earning">
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
                                            <td><?php echo $this->formata_data_hora($requisicao->data_liberado); ?></td>
                                            <td><?php echo $this->formata_data_hora($requisicao->data_transferido); ?></td>
                                            <td><?php echo $this->formata_data_hora($requisicao->data_recebido); ?></td>
                                        </tr>
                                    </tbody>
                                </table> 
                                <hr>

                                <?php if(!empty($requisicao->items)){ ?>
                                <h3 class="title-1 m-b-25">Itens</h3>
                                <table class="table table-responsive table--no-card table-borderless table-striped table-earning"  style="min-height: 200px;">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="10%">Id</th>
                                            <th scope="col" width="20%">Item</th>
                                            <th scope="col" width="20%">Estoque</th>
                                            <th scope="col" width="20%">Qtde. Solicitada</th>
                                            <th scope="col" width="20%">Qtde. Liberada</th>
                                            <th scope="col" width="150">Liberar</th>
                                            <th scope="col">Situação</th>
                                            <th scope="col" width="30%">Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($requisicao->items as $item){ ?>
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
                                            <td><?php echo $item->estoque; ?></td>
                                            <td><?php echo $item->quantidade; ?></td>
                                            <td><?php echo $item->quantidade_liberada; ?></td>
                                            <td>
                                                <?php if (in_array($requisicao->status, [1, 11]) && ($user->id_obra == $requisicao->id_origem || ($user->id_obra != $requisicao->id_destino && !$requisicao->id_origem))) {?>
                                                <input id="item[]" name="item[]" type="hidden" value="<?php echo $item->id_requisicao_item; ?>"> 
                                                <input type="hidden" name="quantidade_solicitada[]" id="quantidade_solicitada[]" value="<?php echo $item->quantidade; ?>">
                                                
                                                <?php $quantidade = ($item->estoque > $item->quantidade) ? $item->quantidade - $item->quantidade_liberada : $item->estoque - $item->quantidade_liberada; ?>
                                                <input 
                                                    type="number" class="form-control" id="quantidade[]" name="quantidade[]" placeholder="0" 
                                                    min="0" max="<?php echo $quantidade; ?>" value="<?php echo $quantidade;?>" <?php if($item->estoque == 0) echo " readonly"; ?> 
                                                >
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php $status = $this->status($item->status);?>
                                                <button type="button" class="badge badge-sm badge-<?php echo $status['class']; ?>">
                                                    <?php echo  $status['texto']; ?>
                                                </button>
                                            </td>
                                            <td>
                                            <?php if (($requisicao->tipo == 1 && in_array($item->status, [3, 13])) && $requisicao->id_destino == $user->id_obra) {?>
                                                <div class="btn-group">
                                                    <button 
                                                        class="btn btn-secondary btn-sm dropdown-toggle" 
                                                        type="button"
                                                        data-toggle="dropdown" 
                                                        aria-haspopup="true" 
                                                        aria-expanded="false"
                                                    >
                                                        Opções
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <?php if ($item->status == 3) {?>
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
                                                        <div class="dropdown-divider"></div>
                                                        <?php } ?>
                                                        <a 
                                                            class="dropdown-item" 
                                                            href="<?php echo base_url("ferramental_requisicao/detalhes_item/{$requisicao->id_requisicao}/{$item->id_requisicao_item}"); ?>"
                                                        >
                                                            <i class="fa fa-list-alt item-menu-interno"></i>&nbsp; Listar de Ativos
                                                        </a>
                                                    </div>
                                                </div>   
                                                <?php } else {   ?>   
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
            </form>
        </div>
    </div>
        </div>
    </div>
</div>

<?php $this->load->view('requisicao_modal_ajuda'); ?>

<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->