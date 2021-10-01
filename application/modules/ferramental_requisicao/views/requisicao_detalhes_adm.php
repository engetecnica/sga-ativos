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
                        class="confirm-submit" action="<?php echo base_url('ferramental_requisicao/liberar_requisicao'); ?>" 
                        method="post" enctype="multipart/form-data"
                        data-acao="Liberar" data-icon="success" data-message="false"
                        data-title="Liberar Transferência" data-redirect="true"
                        data-text="Clique 'Sim, Liberar!' para confirmar a liberação da Requisição dos itens solicitados."
                    > 
                        <h2 class="title-1 m-b-25">Detalhes da Requisição Administração</h2>
                        <div class="card">
                            <input type="hidden" name="id_requisicao" value="<?php echo $requisicao->id_requisicao; ?>">
                            <input type="hidden" name="id_origem" value="<?php echo $requisicao->id_origem; ?>">
                            <input type="hidden" name="id_destino" value="<?php echo $requisicao->id_destino; ?>">
                            <div class="card-body">
                                <!-- Detalhes da Requisição -->
                                <table class="table table-responsive table--no-card table-borderless table-striped table-earning">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="30%">Requisão ID</th>
                                            <th scope="col" width="30%">Solicitação</th>
                                            <th scope="col" width="30%">Tipo da Requisição</th>
                                            <th scope="col" width="30%" >Status da Requisição</th>
                                            <?php if (isset($requisicao->requisicao) | isset($requisicao->devolucao)) { ?>
                                                <th><?php echo $requisicao->tipo == 1 ? 'Devolução' : 'Requisição' ?></th>
                                            <?php } ?>
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
                                                    <a class="btn btn-outline-primary" href="<?php echo base_url("ferramental_requisicao/detalhes/{$relativa->id_requisicao}"); ?>">
                                                        <?php echo $requisicao->tipo == 1 ? 'Ver Devolução' : 'Ver Requisição'?>
                                                    </a>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="m-t-20 table table-responsive table--no-card table-borderless table-striped table-earning">
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
                                            <td><?php echo $requisicao->despachante ; ?></td>
                                            <td><?php echo $requisicao->origem ; ?></td>
                                            <td><?php echo $requisicao->solicitante ; ?></td>
                                            <td><?php echo $requisicao->destino ; ?></td>
                                        </tr>
                                    </tbody>
                                </table> 

                                <table class="m-t-20 table table-responsive table--no-card table-borderless table-striped table-earning">
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
                                <table class="table table-responsive table--no-card table-borderless table-striped table-earning">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="10%">Id</th>
                                            <th scope="col" width="20%">Item</th>
                                            <th scope="col" width="20%">Estoque</th>
                                            <th scope="col" width="20%">Qtde. Solcitada</th>
                                            <th scope="col" width="20%">Qtde. Liberada</th>
                                            <th scope="col" width="150">Liberar</th>
                                            <th scope="col">Situação</th>
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
                                                <?php if (in_array($requisicao->status, [1, 11])) {?>
                                                <input id="item[]" name="item[]" type="hidden" value="<?php echo $item->id_requisicao_item; ?>"> 
                                                <input type="hidden" name="quantidade_solicitada[]" id="quantidade_solicitada[]" value="<?php echo $item->quantidade; ?>">
                                                <input type="number" class="form-control" id="quantidade[]" name="quantidade[]" placeholder="0" 
                                                min="0" max="<?php 
                                                    echo ($item->estoque > $item->quantidade) ? $item->quantidade - $item->quantidade_liberada : $item->estoque - $item->quantidade_liberada; 
                                                ?>" 
                                                <?php if($item->estoque == 0) echo "disabled"; ?> 
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
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php } ?>

                                <?php if($user->nivel == 1){  ?>
                                    <hr>
                                    <div class="col offset-md-3 col-md-6 text-center d-flex flex-column">

                                    <?php if(in_array($requisicao->status, [1, 11])){?>
                                        <div class="text-center">
                                            <button class=" btn-custom m-b-10" type="submit" id="liberar_requisicao_btn" >
                                                <i class="fa fa-check"></i>&nbsp;
                                                Liberar Requisição
                                            </button>

                                            <a
                                                class="confirmar_registro m-b-10"
                                                href="javascript:void(0)"
                                                data-acao="Recusar" data-icon="warning" data-message="false"
                                                data-title="Recusar Requisição" data-redirect="true"
                                                data-text="Clique 'Sim, Recusar!' para recusar a Requisição dos itens solicitados."
                                                data-href="<?php echo base_url("ferramental_requisicao/recusar_requisicao/{$requisicao->id_requisicao}");?>"
                                                data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>"
                                            >
                                                <button class="btn btn-md btn-light" type="button" id="entregar_items_retirada_btn">
                                                    <i class="fa fa-ban 4x" aria-hidden="true"></i>&nbsp;
                                                    Recusar Requisição
                                                </button>
                                            </a>
                                        </div>
                                        <small>Clique para Liberar ou Recusar a Requisição dos itens solicitados.</small>
                                    <?php } if(($requisicao->tipo == 1 && in_array($requisicao->status, [2,11])) && (($user->id_usuario == $requisicao->id_despachante) || ($user->id_obra == $requisicao->id_origem))){ ?>
                                        <a
                                            class="confirmar_registro text-center m-b-10 m-t-20"
                                            href="javascript:void(0)"
                                            data-acao="Enviar" data-icon="success" data-message="false"
                                            data-title="Enviar para Transferencia" data-redirect="true"
                                            data-text="Clique 'Sim, Enviar!' para confirmar a transferencia dos itens solicitados."
                                            data-href="<?php echo base_url("ferramental_requisicao/transferir_requisicao/{$requisicao->id_requisicao}");?>"
                                            data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>"
                                        >
                                            <button class="btn btn-md btn-success" type="button" id="entregar_items_retirada_btn">
                                                <i class="fa fa-truck 4x" aria-hidden="true"></i>&nbsp;
                                                Enviar para Transferência
                                            </button>
                                        </a>
                                        <small>Clique 'Enviar para Transferência' para confirmar a transferência dos itens solicitados. 
                                        Somente após o a saída para transporte.</small>
                                        </div>
                                    <?php  } ?>


                                    <?php if ($requisicao->tipo == 2 && $requisicao->status == 3) {?>
                                        <a 
                                            class="text-center confirmar_registro m-b-10"
                                            href="javascript:void(0)"
                                            data-acao="Receber" data-icon="info" data-message="false"
                                            data-title="Receber Devoluções" data-redirect="true"
                                            data-text="Clique 'Sim, Receber!' para confirmar a transferência de itens devolvidos ou com defeito."
                                            data-href="<?php echo base_url("ferramental_requisicao/receber_devolucoes/{$requisicao->id_requisicao}");?>"
                                            data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>" >
                                        
                                            <button class="btn btn-md btn-secondary" type="button" id="entregar_items_retirada_btn">
                                                <i class="fas fa-clipboard-check item-menu-interno"></i> Receber Devoluções
                                            </button>
                                        </a>
                                        <small>Clique 'Receber Devoluções' para confirmar a transferência de itens devolvidos ou com defeito.</small>
                                    <?php  } ?>
                                    </div>
                                <?php  } ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="copyright">
                        <p>Copyright © <?php echo date("Y"); ?>. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->