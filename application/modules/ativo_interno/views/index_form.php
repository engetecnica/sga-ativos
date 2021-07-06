<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('ativo_interno'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Ativo Interno</h2>

                    <div class="card">
                        <div class="card-header">Novo Ativo</div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_interno/salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($detalhes) && isset($detalhes->id_ativo_interno)){?>
                                <input type="hidden" name="id_ativo_interno" id="id_ativo_interno" value="<?php echo $detalhes->id_ativo_interno; ?>">
                                <?php } ?>

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
                                        <label for="valor" class=" form-control-label">Valor Atribuído</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="valor" name="valor" placeholder="0.00" class="form-control valor" value="<?php if(isset($detalhes) && isset($detalhes->valor)){ echo number_format($detalhes->valor, 2, ',', '.'); } ?>">
                                    </div>
                                    <div class="col col-md-1">
                                        <label for="quantidade" class=" form-control-label">Quantidade</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="number" id="quantidade" name="quantidade" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->quantidade)){ echo $detalhes->quantidade; } else { echo "1"; } ?>">
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
                                    <div class="col-12 col-md-10">
                                        <select name="situacao" id="situacao" class="form-control">
                                            <option value="1" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao==1){ echo "selected='selected'"; } ?>>Inativo</option>
                                            <option value="0" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao==0){ echo "selected='selected'"; } ?>>Ativo</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url('ativo_interno');?>">
                                    <button class="btn btn-info" type="button">                                                    
                                        <i class="fa fa-remove "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>                                                
                                    </a>
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
