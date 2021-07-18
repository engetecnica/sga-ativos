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
                        <?php if ($mode == 'insert'){ ?>
                            <div class="card-header">Novo Ativo</div>     
                        <?php } ?>

                        <?php if ($mode == 'update'){ ?>
                            <div class="card-header">Editar Ativo</div>
                        <?php } ?> 
                                        
                        <?php if ($mode == 'insert_grupo'){ ?>
                            <div class="card-header">Novo Ativo - Grupo</div>     
                        <?php } ?>  

                        <?php if ($mode == 'update_grupo'){ ?>
                            <div class="card-header">Editar Ativo - Grupo</div>
                        <?php } ?> 

                        <div class="card-body">
                            <form 
                                action="<?php echo $url; ?>"
                                method="post" 
                                enctype="multipart/form-data"
                             >
                                <input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">
                                
                                <?php if (in_array($mode, ['update', 'update_grupo'])){ ?>
                                <?php if(isset($detalhes) && isset($detalhes->id_ativo_externo)){?>
                                    <input type="hidden" name="id_ativo_externo[]" id="id_ativo_externo[]" value="<?php echo $detalhes->id_ativo_externo; ?>">
                                    <input type="hidden" name="codigo" id="codigo" value="<?php echo $detalhes->codigo; ?>">
                                <?php } }?>

                                <?php if (in_array($mode, ['update', 'insert_grupo', 'update_grupo'])){ ?>
                                <?php if(isset($detalhes) && isset($detalhes->id_ativo_externo_grupo)){?>
                                    <input type="hidden" name="id_ativo_externo_grupo" id="id_ativo_externo_grupo" value="<?php echo $detalhes->id_ativo_externo_grupo; ?>">
                                <?php }} ?>
                                
                                <?php if (in_array($mode, ['insert', 'update'])){ ?>
                                <?php if(isset($detalhes) && isset($detalhes->id_ativo_externo)){?>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="tipo" class=" form-control-label">Tipo</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select required="required" class="form-control" name="tipo" id="tipo">
                                            <option value="0">Unidade</option>
                                            <option value="1">Kit</option>
                                        </select>
                                    </div>
                                </div>
                                <?php }} ?>

                                <?php if (in_array($mode, ['insert_grupo', 'update_grupo'])){ ?>
                                <?php if((isset($detalhes) && !isset($detalhes->id_ativo_externo)) && isset($detalhes->tipo) ){?>
                                    <input type="hidden" name="tipo" id="tipo" value="<?php echo $detalhes->tipo; ?>">
                                <?php } } ?>
                                
                                <?php if (in_array($mode, ['insert', 'update'])){ ?>
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
                                                <option 
                                                    <?php echo (isset($detalhes) && isset($detalhes->id_ativo_externo_categoria)) && ($detalhes->id_ativo_externo_categoria == $value->id_ativo_externo_categoria) ? "selected" : ''; ?>
                                                    value="<?php echo $value->id_ativo_externo_categoria; ?>"
                                                >
                                                    <?php echo $value->nome; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <?php } ?>
                                
                                <?php if (in_array($mode, ['insert', 'update'])){ ?>
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
                                                <option 
                                                    <?php echo (isset($detalhes) && isset($detalhes->id_obra)) && ($detalhes->id_obra == $value->id_obra) ? "selected" : ''; ?>
                                                    value="<?php echo $value->id_obra; ?>"
                                                >
                                                    <?php echo $value->codigo_obra." - ".$value->endereco; ?>
                                                </option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>    
                                <?php } ?>                             

                                <?php if (in_array($mode, ['insert', 'update', 'update_grupo'])){ ?>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="nome" class=" form-control-label">Nome do Ativo</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input required="required" type="text" id="nome" name="nome" placeholder="Nome do Ativo" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->nome)){ echo $detalhes->nome; } ?>">
                                    </div>                                    
                                </div>
                                <?php } ?>

                                <?php if (in_array($mode, ['insert_grupo'])){ ?>
                                    <input type="hidden" id="nome" name="nome" value="<?php if(isset($detalhes) && isset($detalhes->nome)){ echo $detalhes->nome; } ?>">
                                <?php } ?>

                                <?php if (in_array($mode, ['insert', 'insert_grupo'])){ ?>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="quantidade" class=" form-control-label">Quantidade</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input 
                                            <?php echo (isset($detalhes) && isset($detalhes->quantidade)) ? 'readonly' : ''; ?>
                                            type="number" required="required" id="quantidade" min="1" name="quantidade" class="form-control" 
                                            value="<?php echo (isset($detalhes) && isset($detalhes->quantidade)) ? $detalhes->quantidade : '1' ; ?>"
                                        >
                                    </div>                               
                                </div>
                                <?php } ?>

                                <?php if (in_array($mode, ['insert', 'update'])){ ?>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="observacao" class=" form-control-label">Descrição</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <textarea name="observacao" id="observacao" rows="9" placeholder="Descrição..." class="form-control"><?php if(isset($detalhes) && isset($detalhes->observacao)){ echo $detalhes->observacao; } ?></textarea>
                                    </div>
                                </div>
                                <?php } ?>


                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <?php if(isset($detalhes) && isset($detalhes->id_ativo_externo)){?>
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <span id="submit-form">Continuar</span>
                                        <?php } else { ?>
                                            <i class="fa fa-send "></i>&nbsp;
                                            <span id="submit-form">Processar Ativo</span>
                                        <?php } ?>
                                    </button>

                                    <a href="<?php echo base_url('ativo_externo');?>">
                                    <button class="btn btn-info" type="button">                                                    
                                        <i class="fa fa-remove "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>                                                
                                    </a>
                                </div>

                                <div class="pull-right">
                                    <?php if(isset($detalhes) && $detalhes->tipo == 1) { ?>
                                        <a href="<?php echo base_url('ativo_externo'); ?>/editar_itens/<?php echo $detalhes->id_ativo_externo; ?>">
                                            <button type="button" class="btn btn-outline-primary">Editar Itens</button>
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
