<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/seguro/'.$id_ativo_veiculo); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Seguro do Veículo</h2>

                    <div class="card">
                        <div class="card-header">Registrar items do veículo</div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_veiculo/seguro_salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($id_ativo_veiculo)){?>
                                <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $id_ativo_veiculo; ?>">
                                <?php } ?>

                                <p><strong>CONTROLE DE SEGURO DE VEÍCULO</strong></p>
                                <hr>
                                <p style="text-transform: uppercase"><strong><font color="red"><?php echo $dados_veiculo->veiculo; ?> <?php echo $dados_veiculo->veiculo_placa; ?></font></strong></p>
                                <hr>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="carencia_inicio" class=" form-control-label">Carência Inicio</label>
                                    </div>                                    

                                    <div class="col-12 col-md-2">
                                        <input required="required" type="date" id="carencia_inicio" name="carencia_inicio" class="form-control" value="<?php echo date("Y-m-d"); ?>">
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="carencia_fim" class=" form-control-label">Carência Final</label>
                                    </div>                                    

                                    <div class="col-12 col-md-2">
                                        <input required="required" type="date" id="carencia_fim" name="carencia_fim" class="form-control" value="">
                                    </div> 
                                    
                                    <div class="col col-md-2">
                                        <label for="seguro_ano" class=" form-control-label">Custo</label>
                                    </div>
        
                                    <div class="col-12 col-md-2">
                                        <input required="required" type="text" id="seguro_custo" name="seguro_custo" placeholder="0.00" class="form-control valor" value="">
                                    </div>
                                </div>


                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="contrato_seguro" class=" form-control-label">Contrado</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input required="required" type="file" id="contrato_seguro" name="contrato_seguro" class="form-control" accept="application/pdf, image/*, application/vnd.ms-excel" style="margin-bottom: 5px;"> 
                                        <small size='2'>Formato aceito: <strong>*.PDF, *.XLS, *.XLSx, *.JPG, *.PNG, *.JPEG, *.GIF</strong></small>
                                        <small size='2'>Tamanho Máximo: <strong><?php echo $upload_max_filesize;?></strong></small>
                                    </div>
                                </div>
                                
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url('ativo_veiculo');?>">
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
