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
                    <h2 class="title-1 m-b-25">Ativo Externo</h2>

                    <div class="card">
                        <div class="card-header">Novo Ativo</div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_externo/salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($detalhes) && isset($detalhes->id_ativo_externo)){?>
                                <input type="hidden" name="id_ativo_externo" id="id_ativo_externo" value="<?php echo $detalhes->id_ativo_externo; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_ativo_externo_categoria" class=" form-control-label">Categoria</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select 
                                            class="form-control" 
                                            name="id_ativo_externo_categoria" 
                                            id="id_ativo_externo_categoria"
                                        >
                                            <option value="0">Nenhuma categoria selecionada</option>

                                            <?php foreach ($categoria as $value) { ?>
                                                <option value="<?php echo $value->id_ativo_externo_categoria; ?>"><?php echo $value->nome; ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>                                

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_obra" class=" form-control-label">Obra</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select 
                                            class="form-control" 
                                            name="id_obra" 
                                            id="id_obra"
                                        >
                                            <option value="0">Nenhuma obra selecionada</option>

                                            <?php foreach ($obra as $value) { ?>
                                                <option value="<?php echo $value->id_obra; ?>"><?php echo $value->codigo_obra." - ".$value->endereco; ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>                                 

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="nome" class=" form-control-label">Nome do Ativo</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="text" id="nome" name="nome" placeholder="Nome do Ativo" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->nome)){ echo $detalhes->nome; } ?>">
                                    </div>                                    
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="quantidade" class=" form-control-label">Quantidade</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="number" id="quantidade" name="quantidade" value="1" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->quantidade)){ echo $detalhes->quantidade; } ?>">
                                    </div>                                    
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="observacao" class=" form-control-label">Descrição</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <textarea name="observacao" id="observacao" rows="9" placeholder="Descrição..." class="form-control"><?php if(isset($detalhes) && isset($detalhes->observacao)){ echo $detalhes->observacao; } ?></textarea>
                                    </div>
                                </div>


                                <!--
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="situacao" class=" form-control-label">Situação</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select name="situacao" id="situacao" class="form-control">
                                            <option value="1" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao==1){ echo "selected='selected'"; } ?>>Inativo</option>
                                            <option value="0" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao==0){ echo "selected='selected'"; } ?>>Ativo</option>
                                        </select>
                                    </div>
                                </div>
                                -->

                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Processar Ativo</span>
                                    </button>
                                    <!--
                                    <a href="<?php echo base_url('ativo_externo');?>">
                                    <button class="btn btn-info" type="button">                                                    
                                        <i class="fa fa-remove "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>                                                
                                    </a>
                                    -->
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
