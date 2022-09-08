<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap"> <?php $id = isset($detalhes) && isset($detalhes->id_ativo_configuracao) ? "#configuracao-{$detalhes->id_ativo_configuracao}" : ''?>
                        <a href="<?php echo base_url("ativo_configuracao{$id}"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Configurações</h2>

                    <div class="card">
                        <div class="card-header">
                            <?php echo isset($detalhes) && isset($detalhes->id_ativo_configuracao) ? 'Editar Configuração' : 'Nova Configuração' ?>
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_configuracao/salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($detalhes) && isset($detalhes->id_ativo_configuracao)){?>
                                <input type="hidden" name="id_ativo_configuracao" id="id_ativo_configuracao" value="<?php echo $detalhes->id_ativo_configuracao; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="razao_social" class=" form-control-label">Tipo da Configuração</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select class="form-control" id="id_ativo_configuracao_vinculo" name="id_ativo_configuracao_vinculo">
                                            <option value="0">Configuração Principal</option>
                                            <?php foreach($lista_categoria as $valor){ ?>
                                            <option value="<?php echo $valor->id_ativo_configuracao; ?>" <?php if(isset($detalhes) && $detalhes->id_ativo_configuracao_vinculo==$valor->id_ativo_configuracao){ echo "selected=selected"; } ?>><?php echo $valor->titulo; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                               
                                    <div class="col col-md-2">
                                        <label for="titulo" class=" form-control-label">Titulo</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" id="titulo" name="titulo" placeholder="Titulo da Configuração" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->titulo)){ echo $detalhes->titulo; } ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="slug" class=" form-control-label">Slug/Apelidio</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" id="slug" name="slug" placeholder="Slug/Apelidio da Configuração" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->slug)){ echo $detalhes->slug; } ?>">
                                    </div>

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
                                    <a href="<?php echo base_url("ativo_configuracao{$id}");?>">
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
