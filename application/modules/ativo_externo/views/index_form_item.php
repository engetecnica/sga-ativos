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
                                required="required"
                            >

                            <input 
                                type="hidden" 
                                name="tipo" 
                                id="tipo"
                                value="<?php echo $item[1]['tipo']; ?>"
                                required="required"
                            >
                            
                            <input 
                                type="hidden" 
                                name="id_obra" 
                                id="id_obra"
                                value="<?php echo $item[1]['id_obra']; ?>"
                                required="required"
                            >

                            <input 
                                type="hidden"
                                id="observacao" 
                                name="observacao" 
                                value="<?php echo $item[1]['observacao']; ?>"
                            >

                                <?php foreach($item as $value){ ?>
                                    <?php if(isset($value) && isset($value['id_ativo_externo'])){?>
                                        <input type="hidden" name="id_ativo_externo" id="id_ativo_externo" value="<?php echo $value['id_ativo_externo']; ?>">
                                    <?php } ?>

                                    <div class="row form-group">
                                        <div class="col-12 col-md-2">
                                            <input type="text" id="codigo[]" name="codigo[]" class="form-control" value="<?php echo isset($value['codigo']) ? $value['codigo'] : ''; ?>" placeholder="Código Item"  required="required" >
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" id="item[]" name="item[]" class="form-control" value="<?php echo $value['nome']; ?>" readonly  required="required">
                                        </div>            
                                    </div>
                                <?php } ?>

                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-save "></i>&nbsp;
                                        <span id="submit-form">Salvar Configurações</span>
                                    </button>
                                    <?php if(isset($item[1]) && isset($item[1]['id_ativo_externo'])){?>
                                    <a href="<?php echo base_url('ativo_externo'); ?>/editar/<?php echo $item[1]['id_ativo_externo']; ?>">
                                    <button class="btn btn-info" type="button">                                                    
                                        <i class="fa fa-arrow-left"></i>&nbsp;
                                        <span id="cancelar-form">Voltar</span>
                                    </button>
                                    </a>
                                    <?php } ?>                           
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
