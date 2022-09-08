<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="<?php echo base_url('ativo_veiculo'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <?php

            echo "<pre>";
            print_r($veiculo);
            echo "</pre>";

            ?>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Veículo</h2>

                    <div class="card">
                        <div class="card-header">Registrar items do veículo</div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_veiculo/salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($detalhes) && isset($detalhes->id_ativo_veiculo)){?>
                                <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $detalhes->id_ativo_veiculo; ?>">
                                <?php } ?>

                                <p><strong>quilometragem</strong></p>
                                <hr>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_km" class=" form-control-label">quilometragem Inicial</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="veiculo_km" name="veiculo_km" placeholder="KM" class="form-control" value="">
                                    </div>

                                    <div class="col col-md-1">
                                        <label for="veiculo_km" class=" form-control-label">Final</label>
                                    </div>                                   

                                    <div class="col-12 col-md-2">
                                        <input type="text" id="veiculo_km" name="veiculo_km" placeholder="KM" class="form-control" value="">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input type="date" id="veiculo_km_data" name="veiculo_km_data" class="form-control" value="<?php echo date("Y-m-d"); ?>">
                                    </div>
                                </div>


                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="situacao" class=" form-control-label">Situação</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <select name="situacao" id="situacao" class="form-control">
                                            <option value="0">Ativo</option>
                                            <option value="1">Inativo</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url('ativo_veiculo');?>">
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
