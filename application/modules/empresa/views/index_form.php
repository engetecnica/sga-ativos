<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <?php $id = isset($detalhes) ? "#".$detalhes->id_empresa : '';?>
                        <a href="<?php echo base_url("empresa$id"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Empresas</h2>

                    <div class="card">
                        <div class="card-header">
                            <?php echo isset($detalhes) && isset($detalhes->id_empresa) ? 'Editar Empresa' : 'Nova Empresa' ?>
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url('empresa/salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($detalhes) && isset($detalhes->id_empresa)){?>
                                <input type="hidden" name="id_empresa" id="id_empresa" value="<?php echo $detalhes->id_empresa; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="razao_social" class=" form-control-label">Razão Social</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input required="required" type="text" id="razao_social" name="razao_social" placeholder="Razão Social" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->razao_social)){ echo $detalhes->razao_social; } ?>">
                                    </div>
                            
                                    <div class="col col-md-2">
                                        <label for="nome_fantasia" class=" form-control-label">Nome Fantasia</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input required="required" type="text" id="nome_fantasia" name="nome_fantasia" placeholder="Nome Fantasia" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->nome_fantasia)){ echo $detalhes->nome_fantasia; } ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="cnpj" class=" form-control-label">CNPJ</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input required="required" type="text" id="cnpj" name="cnpj" placeholder="00.000.000/0001-00" class="form-control cnpj" value="<?php if(isset($detalhes) && isset($detalhes->cnpj)){ echo $detalhes->cnpj; } ?>">
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="inscricao_estadual" class=" form-control-label">Inscrição Estadual</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="inscricao_estadual" name="inscricao_estadual" placeholder="" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->inscricao_estadual)){ echo $detalhes->inscricao_estadual; } ?>">
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="inscricao_municipal" class=" form-control-label">Inscrição Municipal</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="inscricao_municipal" name="inscricao_municipal" placeholder="" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->inscricao_municipal)){ echo $detalhes->inscricao_municipal; } ?>">
                                    </div>                                                                                                
                                </div>

                                <?php $this->view("endereco_contato/endereco_form_fields"); ?>

                                <?php $this->view("endereco_contato/contato_form_fields", ['prefix' => 'responsavel']); ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="responsavel" class=" form-control-label">Responsável</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" id="responsavel" name="responsavel" placeholder="Responsável" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->responsavel)){ echo $detalhes->responsavel; } ?>">
                                    </div>
                                </div>


                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="observacao" class=" form-control-label">Observações</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <textarea name="observacao" id="observacao" rows="9" placeholder="Observações..." class="form-control"><?php if(isset($detalhes) && isset($detalhes->observacao)){ echo $detalhes->observacao; } ?></textarea>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="situacao" class=" form-control-label">Situação</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <select name="situacao" id="situacao" class="form-control">
                                            <option value="0" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao==0){ echo "selected='selected'"; } ?>>Ativo</option>
                                            <option value="1" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao==1){ echo "selected='selected'"; } ?>>Inativo</option>
                                        </select>
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
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
