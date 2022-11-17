<!-- MAIN CONTENT-->
<div id="ferramental_estoque_form" class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a  href="<?php echo base_url('ferramental_estoque'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>                       
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Retirada Manual</h2>

                    <div class="card">
                        <div class="card-header">
                            Retirada manual de Ferramental
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url('empresa/salvar'); ?>" method="post" enctype="multipart/form-data">

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_funcionario" class=" form-control-label">Funcionário</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select name="id_funcionario" id="id_funcionario" class="form-control select2">

                                        </select>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_funcionario" class=" form-control-label">Item</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select name="id_funcionario" id="id_funcionario" class="form-control select2">

                                        </select>
                                    </div>
                                </div>
                               
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="situacao" class=" form-control-label">Observações</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <textarea rows="5" class="form-control tinymce" id="descricao" name="descricao"></textarea>
                                    </div>
                                </div>
                                
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url('empresa');?>">
                                    <button class="btn btn-secondary" type="button">                                   
                                        <i class="fa fa-ban "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>                              
                                    </a>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

