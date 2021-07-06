<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/ipva/'.$id_ativo_veiculo); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Veículo</h2>

                    <div class="card">
                        <div class="card-header">Registrar itens do veículo</div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_veiculo/ipva_salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($dados_veiculo) && isset($id_ativo_veiculo)){?>
                                <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $id_ativo_veiculo; ?>">
                                <?php } ?>

                                <p><strong>CONTROLE DE IPVA DE VEÍCULO</strong></p>
                                <hr>
                                <p style="text-transform: uppercase"><strong><font color="red"><?php echo $dados_veiculo->veiculo; ?> <?php echo $dados_veiculo->veiculo_placa; ?></font></strong></p>
                                <hr>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="ipva_ano" class=" form-control-label">Referência</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <select class="form-control" id="ipva_ano" name="ipva_ano">
                                            <?php for($i=date("Y"); $i<=date("Y")+5; $i++){ ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>   
                                

                                    <div class="col-12 col-md-2">
                                        <input type="text" id="ipva_custo" name="ipva_custo" placeholder="0.00" class="form-control valor" value="">
                                    </div>

                                    <div class="col col-md-1">
                                        <label for="ipva_data_vencimento" class=" form-control-label">Vencimento</label>
                                    </div>                                    

                                    <div class="col-12 col-md-2">
                                        <input type="date" id="ipva_data_vencimento" name="ipva_data_vencimento" class="form-control" value="<?php echo date("Y-m-d"); ?>">
                                    </div>

                                    <div class="col col-md-1">
                                        <label for="ipva_data_pagamento" class=" form-control-label">Pagamento</label>
                                    </div>                                    

                                    <div class="col-12 col-md-2">
                                        <input type="date" id="ipva_data_pagamento" name="ipva_data_pagamento" class="form-control" value="">
                                    </div>                                    
                                </div>


                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="comprovante_ipva" class=" form-control-label">Comprovante</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="file" id="comprovante_ipva" name="comprovante_ipva" class="form-control" accept="application/pdf, image/gif, image/jpeg" style="margin-bottom: 5px;"> 
                                        <font size='2'>Formato aceito: <strong>*.PDF, *.JPG</strong></font>
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
