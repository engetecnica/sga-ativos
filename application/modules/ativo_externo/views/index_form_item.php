<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('ativo_externo'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">configuração de ativo</h2>

                    <div class="card">
                        <div class="card-header">Por favor, defina um código para cada item</div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_externo/gravar_itens'); ?>" method="post" enctype="multipart/form-data">

                            <input 
                                type="hidden" 
                                name="id_ativo_externo_categoria" 
                                id="id_ativo_externo_categoria"
                                value="<?php echo $item[1]['id_ativo_externo_categoria']; ?>"
                            >
                            
                            <input 
                                type="hidden" 
                                name="id_obra" 
                                id="id_obra"
                                value="<?php echo $item[1]['id_obra']; ?>"
                            >

                            <input 
                                type="hidden"
                                id="observacao" 
                                name="observacao" 
                                value="<?php echo $item[1]['observacao']; ?>"
                            >

                            <?php
                                #echo "<pre>";
                                #print_r($item);
                                #echo "</pre>";
                            ?>


                                <?php foreach($item as $value){ ?>
                                <div class="row form-group">
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="codigo[]" name="codigo[]" class="form-control" placeholder="Código Item" >
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="text" id="item[]" name="item[]" class="form-control" value="<?php echo $value['nome']; ?>" readonly>
                                    </div>            
                                </div>
                                <?php } ?>

                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-save "></i>&nbsp;
                                        <span id="submit-form">Salvar Configurações</span>
                                    </button>
                                </div>                                

                            </form>

                        </div>
                    </div>

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
