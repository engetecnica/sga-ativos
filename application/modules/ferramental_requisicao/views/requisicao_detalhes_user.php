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
                            <input type="hidden" name="id_obra" value="<?php echo $requisicao->id_obra; ?>">
                            <div class="card-body">

                                <!-- Detalhes da Requisição -->
                                <table class="table table-borderless table-striped table-earning" >
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="20%">Solicitação</th>
                                            <th scope="col" width="20%">Usuário</th>
                                            <th scope="col" width="20%">Destino</th>
                                            <th scope="col">Status da Requisição</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo date("d/m/Y H:i", strtotime($requisicao->data_inclusao)); ?></td>
                                            <td><?php echo $requisicao->usuario_solicitante; ?></td>
                                            <td><?php echo $requisicao->codigo_obra; ?></td>
                                            <td width="10%">
                                                <?php $status = $this->get_requisicao_status($requisicao->status)?>
                                                <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <hr>


                                <?php if(!empty($itens_pendentes)){ ?>
                                <table class="table table-borderless table-striped table-earning" id="lista">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="10%">Id</th>
                                            <th scope="col" width="30%">Item</th>
                                            <th scope="col" width="20%">Qtde. Solcitada</th>
                                            <th scope="col">Qtde. Liberada</th>
                                            <th scope="col">Data da Liberação</th>
                                            <th scope="col">Situação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($itens_pendentes as $item){ ?>
                                        <tr>
                                            <td><?php echo $item->id_requisicao_item; ?></td>
                                            <td><?php echo $item->nome; ?></td>
                                            <td><?php echo $item->quantidade; ?></td>
                                            <td><?php echo $item->quantidade_liberada; ?></td>
                                            <td><?php echo date("d/m/Y H:i", strtotime($item->data_liberado)); ?></td>
                                            <td width="10%">
                                                <?php $status = $this->get_requisicao_status($requisicao->status)?>
                                                <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php } ?>

                                <?php if(!empty($itens_liberados)){ ?>
                                <table class="table table-borderless table-striped table-earning" id="lista">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="10%">Id</th>
                                            <th scope="col" width="40%">Item</th>
                                            <th scope="col" width="20%">Qtde. Solcitada</th>
                                            <th scope="col">Qtde. Liberada</th>
                                            <th scope="col" width="150">Data</th>
                                            <th scope="col">Situação</th>
                                            <th scope="col">Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($itens_liberados as $item){ ?>
                                        <tr>
                                            <td><?php echo $item->id_requisicao_item; ?></td>
                                            <td><?php echo $item->nome; ?></td>
                                            <td><?php echo $item->quantidade; ?></td>
                                            <td><?php echo $item->quantidade_liberada; ?></td>
                                            <td>
                                                <?php echo date("d/m/Y H:i", strtotime($item->data_liberado)); ?>
                                            </td>
                                            <td width="10%">
                                                <?php $status = $this->get_requisicao_status($requisicao->status)?>
                                                <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button 
                                                        class="btn btn-secondary btn-sm dropdown-toggle" 
                                                        type="button" 
                                                        data-toggle="dropdown" 
                                                        aria-haspopup="true" 
                                                        aria-expanded="false"
                                                        <?php if($item->status == 'liberado'){ ?>
                                                            disabled="disabled"
                                                        <?php } ?>

                                                    >Modificações</button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item btn-sm" href="<?php echo base_url("ferramental_requisicao/manual/{$requisicao->id_requisicao}/{$item->id_requisicao_item}"); ?>">
                                                        <i class="fas fa-clipboard-check item-menu-interno"></i> Aceitar Manualmente
                                                        </a>
                                                        <div class="dropdown-divider"></div>

                                                        <a class="dropdown-item btn-sm aceitar-todos" data-id_requisicao="<?php echo $requisicao->id_requisicao; ?>" href="javascript:void(0);">
                                                            <i class="fa fa-check item-menu-interno"></i> Aceitar Todos
                                                        </a>
                                                        <div class="dropdown-divider"></div>

                                                        <a class="dropdown-item btn-sm devolver-todos" data-id_requisicao="<?php echo $requisicao->id_requisicao; ?>" href="javascript:void(0);">
                                                            <i class="fa fa-truck item-menu-interno"></i> Devolver Todos
                                                        </a>
                                                        <div class="dropdown-divider"></div>

                                                        <a 
                                                            class="dropdown-item btn btn-sm btn-primary" 
                                                            href="<?php echo base_url("ferramental_requisicao/detalhes_item/{$requisicao->id_requisicao}/{$item->id_requisicao_item}"); ?>"
                                                        >
                                                            <i class="fa fa-list-alt item-menu-interno"></i> Listar de Ativos
                                                        </a>

                                                    </div>
                                                </div>                                             
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php } ?>                                
                                                         

                                <?php if($this->session->userdata('logado')->nivel==1){  ?>
                                    <?php 
                                        if($requisicao->status_texto=="Pendente"){ 
                                    ?> 

                                    <hr>
                                    <div class="text-center">
                                        <button class="" type="submit" id="liberar_requisicao_btn">
                                            <i class="fa fa-send "></i>&nbsp;
                                            Liberar Requisição
                                        </button>
                                    </div>

                                    <?php } ?>
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