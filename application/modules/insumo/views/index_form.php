<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <?php $id = isset($data) && isset($data->id_insumo) ? "#configuracao-{$data->id_insumo}" : ''?>
                        <a href="<?php echo base_url("insumo{$id}"); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Insumos</h2>

                    <div class="card">
                        <div class="card-header">
                            <?php echo isset($data) && isset($data->id_insumo) ? 'Editar Insumo' : 'Novo Insumo' ?>
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url('insumo/salvar'); ?>" method="post"
                                enctype="multipart/form-data">

                                <?php if(isset($data) && isset($data->id_insumo)){?>
                                <input type="hidden" name="id_insumo" id="id_insumo"
                                    value="<?php echo $data->id_insumo; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="tipo_insumo" class=" form-control-label">Tipo do Insumo</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select data-placeholder="Selecione um Tipo" id="tipo_insumo" name="tipo_insumo"
                                            class="form-control select2">
                                            <option></option>
                                            <?php foreach($tipo_insumo as $t){ ?>
                                                <?php if($t->id_insumo_configuracao_vinculo==null){ ?>
                                                    <optgroup label="<?php echo $t->titulo; ?>">
                                                        <?php foreach($tipo_insumo as $ts){ ?>
                                                                <option value="<?php echo $ts->id_insumo_configuracao ?>">
                                                                    <?php echo $ts->titulo; ?>
                                                                </option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="titulo" class=" form-control-label">Titulo</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" id="titulo" name="titulo" placeholder="Titulo do Insumo"
                                            class="form-control"
                                            value="<?php if(isset($detalhes) && isset($detalhes->titulo)){ echo $detalhes->titulo; } ?>">
                                    </div>
                                </div>

                                <div class="row form-group">

                                    <div class="col col-md-2">
                                        <label for="fornecedor" class=" form-control-label">Fornecedor</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select name="fornecedor" data-placeholder="Selecione o fornecedor" id="fornecedor"
                                            class="form-control select2">
                                            <option></option>
                                            <?php foreach ($fornecedor as $fo) { ?>
                                                <option
                                                   <?php isset($detalhes->id_fornecedor) && $fo->id_fornecedor == $detalhes->id_fornecedor ? 'selected' : ''?> 
                                                    value="<?php echo $fo->id_fornecedor; ?>">
                                                    <?php echo $fo->razao_social; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>                            

                                    <div class="col col-md-2">
                                        <label for="cod_insumo" class=" form-control-label">Código do insumo</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" id="cod_insumo" name="cod_insumo" placeholder="Código do insumo"
                                            class="form-control"
                                            value="<?php if(isset($detalhes) && isset($detalhes->slug)){ echo $detalhes->slug; } ?>">
                                    </div>

                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="valor" class=" form-control-label">Valor</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" id="valor" name="valor" placeholder="0.00"
                                            class="form-control"
                                            value="<?php if(isset($detalhes) && isset($detalhes->slug)){ echo $detalhes->slug; } ?>">
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="funcao" class=" form-control-label">Função</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" id="funcao" name="funcao" placeholder="Função"
                                            class="form-control"
                                            value="<?php if(isset($detalhes) && isset($detalhes->slug)){ echo $detalhes->slug; } ?>">
                                    </div>
                                    
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="quantidade" class=" form-control-label">Quantidade</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="number" required="required" id="quantidade" min="1" name="quantidade" class="form-control" value="1">
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="composicao" class=" form-control-label">Composição</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="composicao" name="composicao" placeholder="Composição"
                                            class="form-control"
                                            value="<?php if(isset($detalhes) && isset($detalhes->slug)){ echo $detalhes->slug; } ?>">
                                    </div>
                                </div>
                            
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="descricao_insumo" class=" form-control-label">Descrição</label>
                                    </div>  
                                    <div class="col-12 col-md-10">
                                        <textarea id="descricao_insumo" name="descricao_insumo" placeholder="Descrição" class="form-control" ><?php echo isset($detalhes) && isset($detalhes->veiculo_observacoes) ? $detalhes->veiculo_observacoes : '' ?></textarea>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="situacao" class=" form-control-label">Situação</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <select name="situacao" id="situacao" class="form-control">
                                            <option value="0"
                                                <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao==0){ echo "selected='selected'"; } ?>>
                                                Ativo</option>
                                            <option value="1"
                                                <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao==1){ echo "selected='selected'"; } ?>>
                                                Inativo</option>
                                        </select>
                                    </div>
                                </div>
                                                                

                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url("insumo{$id}");?>">
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