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
                        <h2 class="title-1 m-b-25">Detalhes da Requisição Administração</h2>
                        <div class="card">
                            <input type="hidden" name="id_requisicao" value="<?php echo $requisicao->id_requisicao; ?>">
                            <input type="hidden" name="id_origem" value="<?php echo $requisicao->id_origem; ?>">
                            <input type="hidden" name="id_destino" value="<?php echo $requisicao->id_destino; ?>">
                            <div class="card-body">
                                <!-- Detalhes da Requisição -->
                                <table class="table table-borderless table-striped table-earning">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="20%">Requisão ID</th>
                                            <th scope="col" width="20%">Solicitação</th>
                                            <th scope="col" width="20%">Solicitante</th>
                                            <th scope="col" width="20%">Destino</th>
                                            <th scope="col">Status da Requisição</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $requisicao->id_requisicao; ?></td>
                                            <td><?php echo date("d/m/Y H:i", strtotime($requisicao->data_inclusao)); ?></td>
                                            <td><?php echo $requisicao->solicitante; ?></td>
                                            <td><?php echo $requisicao->destino; ?></td>
                                            <td width="10%">
                                                <?php $status = $this->get_requisicao_status($requisicao->status_lista, $requisicao->status)?>
                                                <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <hr>

                                <?php if(!empty($requisicao->items)){ ?>
                                <h3 class="title-1 m-b-25">Itens</h3>
                                <table class="table table-responsive table-borderless table-striped table-earning" id="lista">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="10%">Id</th>
                                            <th scope="col" width="20%">Item</th>
                                            <th scope="col">Estoque</th>
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
                                                <?php if (in_array($requisicao->status, [2,4,9,11])) {?>
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
                                                <?php $status = $this->get_requisicao_status($requisicao->status_lista, $item->status);?>
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
                                    <div class="text-center">
                                    <?php if(in_array($requisicao->status, [1, 11])){?>
                                        <button class="" type="submit" id="liberar_requisicao_btn">
                                            <i class="fa fa-checked"></i>&nbsp;
                                            Liberar Requisição
                                        </button>
                                    <?php } if($requisicao->status == 2){ ?>
                                      <a
                                        class="confirmar_registro text-center"
                                        href="javascript:void(0)"
                                        data-acao="Enviar" data-icon="info" data-message="false"
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
                                    </div>
                                <?php } } ?>
                               

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
/* #liberar_requisicao_btn {
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
} */
</style>