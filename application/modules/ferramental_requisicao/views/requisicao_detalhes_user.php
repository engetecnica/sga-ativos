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
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('ferramental_requisicao'); ?>">
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
                                <table class="table table-responsive table-borderless table-striped table-earning" id="lista">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col">Requisão ID</th>
                                            <th scope="col">Solicitação</th>
                                            <th scope="col">Solicitante</th>
                                            <th scope="col">Destino</th>
                                            <th scope="col">Status da Requisição</th>
                                            <?php if (in_array($requisicao->status, [1, 2, 11]) && ($user->id_usuario == $requisicao->id_solicitante)) {?>
                                                <th scope="col">Opções</th>
                                            <?php }?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $requisicao->id_requisicao; ?></td>
                                            <td><?php echo date("d/m/Y H:i", strtotime($requisicao->data_inclusao)); ?></td>
                                            <td><?php echo $requisicao->solicitante; ?></td>
                                            <td><?php echo $requisicao->destino; ?></td>
                                            <td>
                                                <?php $status = $this->get_requisicao_status($requisicao->status_lista, $requisicao->status)?>
                                                <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                            </td>
                                            <td> 
                                            <?php if (($requisicao->status == 1) && ($user->id_usuario == $requisicao->id_solicitante)) {?>
                                                <a 
                                                    class="btn btn-sm btn-danger confirmar_registro" href="javascript:void(0);"
                                                    data-tabela="<?php echo base_url("ferramental_requisicao");?>" 
                                                    data-title="Remover Requisição" data-acao="Remover"  data-redirect="true"
                                                    data-href="<?php echo base_url("ferramental_requisicao/deletar/{$requisicao->id_requisicao}");?>"
                                                >
                                                    <i class="fa fa-trash item-menu-interno"></i>
                                                </a>
                                            <?php  } ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <hr>

                                <?php if(!empty($requisicao->items)){ ?>
                                <h3 class="title-1 m-b-25">Itens</h3>
                                <table class="table table-responsive table-borderless table-striped table-earning" id="lista2">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col">Id</th>
                                            <th scope="col">Item</th>
                                            <th scope="col">Qtde. Solcitada</th>
                                            <th scope="col">Qtde. Liberada</th>
                                            <th scope="col">Data</th>
                                            <th scope="col">Situação</th>
                                            <th scope="col">Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($requisicao->items as $item){ ?>
                                        <tr>
                                            <td><?php echo $item->id_requisicao_item; ?></td>
                                            <td>
                                                <?php if (in_array($requisicao->status, [2,4,9,11])) {?>
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
                                                <?php echo isset($item->data_liberado) ? date("d/m/Y H:i", strtotime($item->data_liberado)) : '-'; ?>
                                            </td>
                                            <td>
                                                <?php $status = $this->get_requisicao_status($requisicao->status_lista, $item->status);?>
                                                <button type="button" class="badge badge-sm badge-<?php echo $status['class']; ?>">
                                                    <?php echo  $status['texto']; ?>
                                                </button>
                                            </td>
                                            <td>
                                            <?php if ($item->status == 2) {?>
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
                                                        <?php if ($item->status == 2) {?>
                                                        <a class="dropdown-item btn-sm" href="<?php echo base_url("ferramental_requisicao/manual/{$requisicao->id_requisicao}/{$item->id_requisicao_item}"); ?>">
                                                        <i class="fas fa-clipboard-check item-menu-interno"></i> Aceitar Manualmente
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a 
                                                            class="dropdown-item btn-sm confirmar_registro" href="javascript:void(0);"
                                                            data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>" 
                                                            data-title="Aceitar Todos" data-acao="Aceitar" data-redirect="true"
                                                            data-href="<?php echo base_url("ferramental_requisicao/aceitar_tudo/{$requisicao->id_requisicao}/{$item->id_requisicao_item}");?>"
                                                        >
                                                            <i class="fa fa-check item-menu-interno"></i> Aceitar Todos
                                                        </a>
                                                        <div class="dropdown-divider"></div>

                                                        <a 
                                                        class="dropdown-item btn-sm confirmar_registro" href="javascript:void(0);"
                                                            data-tabela="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>" 
                                                            data-title="Devolver Todos" data-acao="Devolver"  data-redirect="true"
                                                            data-href="<?php echo base_url("ferramental_requisicao/devolver_tudo/{$requisicao->id_requisicao}/{$item->id_requisicao_item}");?>"
                                                        >
                                                            <i class="fa fa-truck item-menu-interno"></i> Devolver Todos
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <?php } ?>
                                                        <a 
                                                            class="dropdown-item btn btn-sm btn-primary" 
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