<!-- MAIN CONTENT-->
<div class="main-content" id="ativo_externo_form">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <?php $id = isset($detalhes) ? (isset($detalhes->id_ativo_externo) ? "#ativo-". $detalhes->id_ativo_externo : "#grupo-".$detalhes->id_ativo_externo_grupo) : ''; ?>
                        <a href="<?php echo base_url("ativo_externo$id"); ?>">
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
                                action="<?php echo $form_url; ?>"
                                method="post" 
                                enctype="multipart/form-data"
                                class="confirm-submit"
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
                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="tipo" class=" form-control-label">Tipo</label>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <select required="required" class="form-control" name="tipo" id="tipo">
                                                <option <?php echo isset($detalhes->tipo) && $detalhes->tipo === '0' ? "selected='selected'" : ''; ?> value="0">Unidade</option>
                                                <option <?php echo isset($detalhes->tipo) && $detalhes->tipo === '1' ? "selected='selected'" : ''; ?> value="1">Kit</option>
                                                <option <?php echo isset($detalhes->tipo) && $detalhes->tipo === '2' ? "selected='selected'" : ''; ?> value="2">Metros</option>
                                                <option <?php echo isset($detalhes->tipo) && $detalhes->tipo === '3' ? "selected='selected'" : ''; ?> value="3">Conjunto</option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php } else { ?>
                                        <input type="hidden" name="tipo" id="tipo" value="<?php echo $detalhes->tipo; ?>">
                                    <?php } ?>

                               
                                <?php if (in_array($mode, ['insert', 'update'])){ ?>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_ativo_externo_categoria" class=" form-control-label">Categoria</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select 
                                            required="required"
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

                                    <div class="col col-md-2">
                                        <label for="id_obra" class=" form-control-label">Obra</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select 
                                            class="form-control select2" 
                                            name="id_obra" 
                                            id="id_obra"
                                            required="required"
                                            readonly
                                            disabled
                                        >
                                            <option value="">Nenhuma obra selecionada</option>
                                            <?php foreach ($obra as $value) { ?>
                                                <option 
                                                    <?php echo ((isset($detalhes) && isset($detalhes->id_obra)) && ($detalhes->id_obra == $value->id_obra))|| (!isset($detalhes) && $value->id_obra == $user->id_obra)  ? "selected" : ''; ?>
                                                    value="<?php echo $value->id_obra; ?>"
                                                >
                                                    <?php echo $value->codigo_obra." - ".$value->endereco; ?>
                                                </option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>   
                                <?php } ?>
                                                             

                                <?php if (in_array($mode, ['insert_grupo'])){ ?>
                                    <input type="hidden" id="nome" name="nome" value="<?php if(isset($detalhes) && isset($detalhes->nome)){ echo $detalhes->nome; } ?>">
                                <?php } ?>

                                <?php if (in_array($mode, ['insert', 'update', 'insert_grupo', 'update_grupo'])){ ?>
                                    <div class="row form-group">
                                <?php } ?>  
                                    
                                <?php if (in_array($mode, ['insert', 'update', 'update_grupo'])){ ?>
                                    <div class="col col-md-2">
                                        <label for="nome" class=" form-control-label">Nome</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input required="required" type="text" id="nome" name="nome" placeholder="Nome do Ativo" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->nome)){ echo $detalhes->nome; } ?>">
                                    </div>                                  
                                <?php } ?>


                                <?php if (in_array($mode, ['insert', 'update' , 'insert_grupo', 'update_grupo'])){ ?>
                                
                                    <?php if (in_array($mode, ['insert', 'update', 'insert_grupo'])){ ?>
                                    <div class="col col-md-2">
                                        <label for="valor" class=" form-control-label">Valor Unitário</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input
                                        required="required"  type="text" id="valor" name="valor" placeholder="0.00" class="form-control valor" 
                                        value="<?php if(isset($detalhes) && isset($detalhes->valor)){ echo number_format($detalhes->valor, 2, ',', '.'); } ?>">
                                    </div>
                                    
                                    <?php } ?>

                                    <?php if (in_array($mode, ['insert', 'update', 'update_grupo'])){ ?>
                                    </div>
                                    <?php } ?>

                                    <?php if (in_array($mode, ['insert', 'insert_grupo'])){ ?>

                                        <?php if (in_array($mode, ['insert', 'update', 'update_grupo' ])){ ?>
                                            <div class="row form-group">
                                        <?php } ?>
                                        <div class="col col-md-2">
                                            <label for="quantidade" class=" form-control-label">Quantidade</label>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <input 
                                                <?php echo (isset($detalhes) && isset($detalhes->quantidade)) ? 'readonly' : ''; ?>
                                                type="number" required="required" id="quantidade" min="1" name="quantidade" class="form-control" 
                                                value="<?php echo (isset($detalhes) && isset($detalhes->quantidade)) ? $detalhes->quantidade : '1' ; ?>"
                                            >
                                        </div> 
                                        <?php if (in_array($mode, ['insert', 'update', 'insert_grupo', 'update_grupo' ])){ ?>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>   
                                <?php } ?>

                                <?php if (in_array($mode, ['insert', 'update', 'update_grupo'])){ ?>
                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="observacao" class=" form-control-label">Descrição</label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <textarea name="observacao" id="observacao" rows="9" placeholder="Descrição..." class="form-control"><?php if(isset($detalhes) && isset($detalhes->observacao)){ echo $detalhes->observacao; } ?></textarea>
                                        </div>
                                    </div>
                                <?php }?>

                                
                                <div class="row form-group">
                                    <?php if (in_array($mode, ['insert', 'update', 'update_grupo'])){ ?>
                                    <div class="col-12 col-md-4">
                                        <label for="necessita_calibracao" class=" form-control-label">Necessecitam de calibração ou aferiação</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <select v-model="necessita_calibracao" required="required" class="form-control" id="necessita_calibracao" name="necessita_calibracao">
                                            <option <?php echo isset($detalhes->necessita_calibracao) && $detalhes->necessita_calibracao == '0' ? "selected='selected'" : ''; ?> value="0">Não</option>
                                            <option <?php echo isset($detalhes->necessita_calibracao) && $detalhes->necessita_calibracao == '1' ? "selected='selected'" : ''; ?> value="1">Sim</option>
                                        </select>
                                    </div>
                                    <?php } else { ?>
                                        <input type="hidden" name="necessita_calibracao" id="necessita_calibracao" value="<?php echo $detalhes->necessita_calibracao; ?>" />
                                    <?php } ?>

                                    <?php // if (in_array($mode, ['update']) && $this->ativo_externo_model->permit_edit_situacao($detalhes->id_ativo_externo)){ ?>
                                    <?php if (in_array($mode, ['update'])){ ?>
                                        <div class="col col-md-2">
                                            <label for="situacao" class=" form-control-label">Situação</label>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <select 
                                                class="form-control" 
                                                name="situacao[]" 
                                                id="situacao[]"
                                                required="required"
                                                
                                            >
                                                <?php 
                                                    foreach ($status_lista as $value) { 
                                                        if (in_array($value->id_status, [5, 8, 10, 12])) {
                                                ?>
                                                    <option 
                                                        <?php echo (isset($detalhes) && isset($detalhes->situacao)) && ($detalhes->situacao == $value->id_status) || !isset($detalhes) && $value->id_status == 12  ? "selected" : ''; ?>
                                                        value="<?php echo $value->id_status; ?>"
                                                    >
                                                        <?php echo $this->status($value->id_status)['texto']; ?>
                                                    </option>
                                                <?php } } ?>

                                            </select>
                                        </div>
                                    <?php } ?>
                                </div>

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

                                    <a href="<?php echo base_url("ativo_externo$id");?>" class="m-t-10">
                                    <button class="btn btn-secondary" type="button">                                   
                                        <i class="fa fa-ban "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>                              
                                    </a>
                                </div>
                                <?php if (isset($detalhes) && isset($detalhes->id_ativo_externo)) { ?>
                                <div class="pull-right btn-group m-t-10" role="group">
                                    <button id="ativo_externo_group" type="button" class="btn btn-outline-info btn-md dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Gerenciar Ativo
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="ativo_externo_group">
                                            <?php if((isset($detalhes) && isset($detalhes->id_ativo_externo)) && $detalhes->tipo == 1) { ?>
                                                <a class="dropdown-item " href="<?php echo base_url("ativo_externo/editar_items/{$detalhes->id_ativo_externo}"); ?>">
                                                    <i class="fa fa-th-large"></i> Editar Itens (KIT)
                                                </a>
                                                <div class="dropdown-divider"></div>
                                            <?php } ?>

                                            <a class="dropdown-item " href="<?php echo base_url("ativo_externo/manutencao/{$detalhes->id_ativo_externo}"); ?>">
                                                <i class="fa fa-wrench"></i>&nbsp; Manutenção
                                            </a>
                                            
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item " href="<?php echo base_url("ativo_externo/certificado_de_calibracao/{$detalhes->id_ativo_externo}"); ?>">
                                             <i class="fa fa-balance-scale"></i>&nbsp; Cert. de Calibração
                                            </a>
                                            <?php if ($detalhes->situacao == 8) {?>
                                                <div class="dropdown-divider"></div>
                                                <a href="javascript:void(0)" 
                                                data-href="<?php echo base_url('ativo_externo'); ?>/descartar/<?php echo $detalhes->id_ativo_externo; ?>"  redirect="true" 
                                                data-tabela="ativo_externo" class="dropdown-item  confirmar_registro"><i class="fa fa-ban"></i> Descartar</a>
                                            <?php } ?>
                                            
                                            <!-- <div class="dropdown-divider"></div>
                                            <a class="dropdown-item " href="<?php echo base_url("anexo/index/12/{$detalhes->id_ativo_externo}"); ?>">
                                                <i class="fa fa-files-o"></i> Anexos
                                            </a> -->
                                    </div>
                                </div>
                                <?php } ?>
                            </form>

                        </div>
                    </div>

                </div>
            </div>

            <?php if (isset($anexos)) { ?>
            <div id="anexos" class="row">
                <div class="col-12">
                    <?php $this->load->view('anexo/index', ['show_header' => false, 'permit_delete' => true]); ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php $this->load->view('anexo/index_form_modal', ["show_header" => false]); ?>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->