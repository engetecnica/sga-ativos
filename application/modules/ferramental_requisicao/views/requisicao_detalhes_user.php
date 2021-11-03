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
                    <form action="<?php echo base_url('ferramental_requisicao/liberar_requisicao'); ?>" method="post" enctype="multipart/form-data"> 
                        <h2 class="title-1 m-b-25">Detalhes da Requisição</h2>

                        <div class="card">
                            <input type="hidden" name="id_requisicao" value="<?php echo $requisicao->id_requisicao; ?>">
                            <input type="hidden" name="id_origem" value="<?php echo $requisicao->id_origem; ?>">
                            <input type="hidden" name="id_destino" value="<?php echo $requisicao->id_destino; ?>">
                            
                            <div class="card-body">

                                <!-- Detalhes da Requisição -->
                                <table class="m-t-20 table table-responsive table--no-card m-b-10 table-borderless table-striped table-earning">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="20%">Requisão ID</th>
                                            <th scope="col" width="20%">Solicitação</th>
                                            <th scope="col" width="20%">Tipo da Requisição</th>
                                            <th scope="col" width="5%">Status da Requisição</th>
                                            <?php if (($requisicao->status == 1) && ($user->id_usuario == $requisicao->id_solicitante)) {?>
                                                <th scope="col" width="20%">Opções</th>
                                            <?php }?>
                                            <?php if (isset($requisicao->requisicao) | isset($requisicao->devolucao)) { ?>
                                                <th><?php echo $requisicao->tipo == 1 ? 'Devolução' : 'Requisição' ?></th>
                                            <?php } ?>
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
                                            <?php if (($requisicao->status == 1) && ($user->id_usuario == $requisicao->id_solicitante)) {?>
                                            <td> 
                                                <a 
                                                    class="btn btn-sm btn-default confirmar_registro" href="javascript:void(0);"
                                                    data-tabela="<?php echo base_url("ferramental_requisicao");?>" 
                                                    data-title="Remover Requisição" data-acao="Remover"  data-redirect="true"
                                                    data-href="<?php echo base_url("ferramental_requisicao/deletar/{$requisicao->id_requisicao}");?>"
                                                >
                                                    <i class="fa fa-trash item-menu-interno"></i>
                                                </a>
                                            </td>
                                            <?php } ?>
                                             <?php if (isset($requisicao->requisicao) | isset($requisicao->devolucao)) { ?>
                                            <td> 
                                                <?php $relativa = $requisicao->tipo == 1 ? $requisicao->devolucao : $requisicao->requisicao; ?>
                                                <a class="btn btn-outline-primary" href="<?php echo base_url("ferramental_requisicao/detalhes/{$relativa->id_requisicao}"); ?>">
                                                    <?php echo $requisicao->tipo == 1 ? 'Ver Devolução' : 'Ver Requisição'?>
                                                </a>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="m-t-20 table table-responsive table--no-card m-b-10 table-borderless table-striped table-earning">
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

                                
                                <table class="m-t-20 table table-responsive table--no-card m-b-10 table-borderless table-striped table-earning">
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
                                <table class="table table-responsive table--no-card table-borderless table-striped table-earning" style="min-height: 200px;">
                                        <thead>
                                            <tr class="active">
                                                <th width="30%">Id</th>
                                                <th width="30%">Item</th>
                                                <th width="30%">Qtde. Solcitada</th>
                                                <th width="30%">Qtde. Liberada</th>
                                                <th width="30%">Atualizado</th>
                                                <th width="30%">Situação</th>
                                                <th width="30%">Opções</th>
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
                                                            Opções
                                                        </button>

                                                        <div class="dropdown-menu">
                                                            <?php if ($item->status == 3) {?>
                                                            <a class="dropdown-item btn-sm" href="<?php echo base_url("ferramental_requisicao/manual/{$requisicao->id_requisicao}/{$item->id_requisicao_item}"); ?>">
                                                                <i class="fas fa-clipboard-check item-menu-interno"></i> Aceitar Manualmente
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            <a 
                                                                class="dropdown-item btn-sm confirmar_registro" href="javascript:void(0);"
                                                                data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>" 
                                                                data-title="Aceitar Todos" data-acao="Aceitar" data-redirect="true"
                                                                data-href="<?php echo base_url("ferramental_requisicao/receber_item/{$requisicao->id_requisicao}/{$item->id_requisicao_item}/4");?>"
                                                            >
                                                                <i class="fa fa-check item-menu-interno"></i> Aceitar Todos
                                                            </a>
                                                            <div class="dropdown-divider"></div>

                                                            <a 
                                                                class="dropdown-item btn-sm confirmar_registro" href="javascript:void(0);"
                                                                data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>" 
                                                                data-title="Devolver Todos" data-acao="Devolver"  data-redirect="true"
                                                                data-href="<?php echo base_url("ferramental_requisicao/receber_item/{$requisicao->id_requisicao}/{$item->id_requisicao_item}/9");?>"
                                                            >
                                                                <i class="fa fa-truck item-menu-interno"></i> Devolver Todos
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            <?php } ?>
                                                            <a 
                                                                class="dropdown-item btn-sm btn-primary" 
                                                                href="<?php echo base_url("ferramental_requisicao/detalhes_item/{$requisicao->id_requisicao}/{$item->id_requisicao_item}"); ?>"
                                                            >
                                                                <i class="fa fa-list-alt item-menu-interno"></i> Listar de Ativos
                                                            </a>
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

                            <hr>
                            <div class="row">
                                    <hr>
                                    <div class="col offset-md-3 col-md-6 text-center d-flex flex-column">
                                    <?php if(($requisicao->status == 2) && (($user->id_usuario == $requisicao->id_despachante) || ($user->id_obra == $requisicao->id_origem))){ ?>
                                        <?php if($requisicao->tipo == 1){ ?>
                                            <a
                                            class="confirmar_registro text-center m-b-10"
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
                                        <?php  } ?>

                                        <?php if($requisicao->tipo == 2){ ?>
                                            <a
                                            class="confirmar_registro text-center m-b-10"
                                            href="javascript:void(0)"
                                            data-acao="Enviar" data-icon="success" data-message="false"
                                            data-title="Enviar para Transferencia" data-redirect="true"
                                            data-text="Clique 'Sim, Enviar!' para confirmar a transferencia dos itens dos itens marcados como devolvidos ou com defeito."
                                            data-href="<?php echo base_url("ferramental_requisicao/transferir_devolucao/{$requisicao->id_requisicao}");?>"
                                            data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>"
                                            >
                                            <button class="btn btn-md btn-success" type="button" id="entregar_items_retirada_btn">
                                                <i class="fa fa-truck 4x" aria-hidden="true"></i>&nbsp;
                                                Enviar para Transferência
                                            </button>
                                            </a>
                                            <small>Clique 'Enviar para Transferência' para confirmar a devolução dos itens marcados como devolvidos ou com defeito. 
                                            Somente após o a saída para transporte.</small>
                                        <?php  } } ?>

                                    </div>
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
<style>
/* This code is generated by: https://webdesign-assistant.com */
#liberar_requisicao_btn {
    text-decoration: none;
    font-size: 16px;
    color: #FFFFFF;
    font-family: arial;
    background: linear-gradient(to bottom, #FF480E, #D02718);
    border: solid #FF4B18 1px;
    border-radius: 5px;
    padding:10px;
    text-shadow: 0px 1px 2px #000000;
    *box-shadow: 0px 1px 5px #0D2444;
    -webkit-transition: all 0.15s ease;
    -moz-transition: all 0.15s ease;
    -o-transition: all 0.15s ease;
    transition: all 0.15s ease;
    width: 240px;
}
#liberar_requisicao_btn:hover{
    opacity: 0.9;
    background: linear-gradient(to bottom, #C02028, #D02718);
    border: 1px solid #c02028;
    *box-shadow: 0px 1px 2px #000000;
}
</style>