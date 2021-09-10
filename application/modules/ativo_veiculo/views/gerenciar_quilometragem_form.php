<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/quilometragem/'.$id_ativo_veiculo); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Veículo</h2>
                    <?php var_dump($dados_veiculo); ?>
                    
                    <div class="card">
                        <div class="card-header">Registrar items do veículo</div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_veiculo/quilometragem_salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($id_ativo_veiculo)){?>
                                <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $id_ativo_veiculo; ?>">
                                <?php } ?>

                                <p><strong>CONTROLE DE QUILOMETRAGEM DE VEÍCULO</strong></p>
                                <hr>
                                <p style="text-transform: uppercase"><strong><font color="red"><?php echo $dados_veiculo->veiculo; ?> <?php echo $dados_veiculo->veiculo_placa; ?></font></strong></p>
                                <hr>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_km_inicial" class=" form-control-label">Quilometragem</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input required="required" type="number" id="veiculo_km_inicial" name="veiculo_km_inicial" placeholder="KM Inicial" class="form-control" min="<?php echo (int) $dados_veiculo->veiculo_km; ?>" value="<?php echo (int) $dados_veiculo->veiculo_km; ?>">
                                    </div>   

                                    <div class="col-12 col-md-2">
                                        <input required="required" type="number" id="veiculo_km_final" name="veiculo_km_final" placeholder="KM Final" class="form-control" value="<?php echo (int) $dados_veiculo->veiculo_km + 1; ?>" min="<?php echo (int) $dados_veiculo->veiculo_km + 1; ?>">
                                    </div>  

                                    <div class="col-12 col-md-2">
                                        <input required="required" type="text" id="veiculo_litros" name="veiculo_litros" placeholder="Litros" class="form-control litros" value="">
                                    </div>                                  

                                    <div class="col-12 col-md-2">
                                        <input required="required" type="text" id="veiculo_custo" name="veiculo_custo" placeholder="0.00" class="form-control valor" value="">
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <input required="required" type="date" id="veiculo_km_data" name="veiculo_km_data" class="form-control" value="<?php echo date("Y-m-d"); ?>">
                                    </div>
                                </div>


                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="comprovante_fiscal" class=" form-control-label">Comprovante Fiscal</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input required="required" type="file" id="comprovante_fiscal" name="comprovante_fiscal" class="form-control" accept="application/pdf, image/*, application/vnd.ms-excel" style="margin-bottom: 5px;"> 
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
