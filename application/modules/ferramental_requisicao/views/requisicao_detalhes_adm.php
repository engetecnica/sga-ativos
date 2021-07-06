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

                        <?php
                        #echo "<pre>";
                        #print_r($requisicao_liberada);
                        #echo "</pre>";

                        ?>


                        <h2 class="title-1 m-b-25">Detalhes da Requisição Administração</h2>

                        <div class="card">

                            <input type="hidden" name="id_requisicao" value="<?php echo $requisicao->id_requisicao; ?>">
                            <input type="hidden" name="id_obra" value="<?php echo $requisicao->id_obra; ?>">
                            <div class="card-body">

                                <!-- Detalhes da Requisição -->
                                <table class="table table-borderless table-striped table-earning">
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
                                            <td>                            
                                                <button type="button"  class="btn btn-sm btn-<?php echo $requisicao->classe; ?>">
                                                    <?php echo $requisicao->status_texto; ?>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <hr>


                                <?php if(!empty($requisicao->itens)){ ?>
                                <table class="table table-borderless table-striped table-earning">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="40%">Item</th>
                                            <th scope="col" width="20%">Qtde. Solcitada</th>
                                            <th scope="col">Estoque</th>
                                            <th scope="col" width="150">Liberar</th>
                                            <th scope="col">Situação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($requisicao->itens as $req){ ?>
                                            <input type="hidden" name="item[]" id="item[]" value="<?php echo $req->item; ?>">
                                            <input type="hidden" name="quantidade_solicitada[]" id="quantidade_solicitada[]" value="<?php echo $req->quantidade; ?>">
                                        <tr>
                                            <td><?php echo $req->item; ?></td>
                                            <td><?php echo $req->quantidade; ?></td>
                                            <td><?php echo $req->total_estoque; ?></td>
                                            <td>
                                                <input type="number" class="form-control" id="quantidade[]" name="quantidade[]" placeholder="0" min="1" max="<?php echo $req->quantidade; ?>" <?php if($req->total_estoque==0) echo "disabled"; ?> >
                                            </td>
                                            <td><button type="button" class="btn btn-sm btn-<?php echo $req->classe; ?>"><?php echo $req->status_texto; ?></button></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php } ?>

                                <?php if(!empty($requisicao_liberada->itens) && $requisicao->status_texto=='Liberado'){ ?>
                                <table class="table table-borderless table-striped table-earning">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="40%">Item</th>
                                            <th scope="col" width="20%">Qtde. Solcitada</th>
                                            <th scope="col">Liberada</th>
                                            <th scope="col" width="150">Data</th>
                                            <th scope="col">Situação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($requisicao_liberada->itens as $req){ ?>
                                        <tr>
                                            <td><?php echo $req->nome; ?></td>
                                            <td><?php echo $req->quantidade; ?></td>
                                            <td><?php echo $req->quantidade_liberada; ?></td>
                                            <td>
                                                <?php echo date("d/m/Y H:i", strtotime($req->data_liberado)); ?>
                                            </td>
                                            <td><button type="button" class="btn btn-sm btn-<?php echo $req->status_item_classe; ?>"><?php echo $req->status_item; ?></button></td>
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