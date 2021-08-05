<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/manutencao/'.$id_ativo_veiculo); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Veículo</h2>

                    <div class="card">
                        <div class="card-header">Registrar items do veículo</div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_veiculo/manutencao_salvar'); ?>" method="post" enctype="multipart/form-data">

                                <p><strong>CONTROLE DE MANUTENÇÕES DO VEÍCULO</strong></p>
                                <hr>
                                <p style="text-transform: uppercase"><strong><font color="red"><?php echo $dados_veiculo->veiculo; ?> <?php echo $dados_veiculo->veiculo_placa; ?></font></strong></p>
                                <hr>


                                <?php if(isset($dados_veiculo) && isset($id_ativo_veiculo)){?>
                                <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $id_ativo_veiculo; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_fornecedor" class=" form-control-label">Fornecedor</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select class="form-control" id="id_fornecedor" name="id_fornecedor">
                                            <?php foreach($fornecedores as $fornecedor){ ?>
                                            <option value="<?php echo $fornecedor->id_fornecedor; ?>"><?php echo $fornecedor->razao_social; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_ativo_configuracao" class=" form-control-label">Serviço</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select class="form-control" id="id_ativo_configuracao" name="id_ativo_configuracao">
                                            <?php foreach($tipo_servico as $servico){ ?>
                                            <option value="<?php echo $servico->id_ativo_configuracao; ?>"><?php echo $servico->titulo; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>                                

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_km_atual" class=" form-control-label">Quilometragem</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="veiculo_km_atual" name="veiculo_km_atual" placeholder="KM Atual" class="form-control" value="">
                                    </div> 

                                    <div class="col-12 col-md-2">
                                        <input type="text" id="veiculo_custo" name="veiculo_custo" placeholder="0.00" class="form-control valor" value="">
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <input type="date" id="veiculo_km_data" name="veiculo_km_data" class="form-control" value="<?php echo date("Y-m-d"); ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="descricao" class=" form-control-label">Descrição</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <textarea rows="5" class="form-control" id="descricao" name="descricao"></textarea>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="ordem_de_servico" class=" form-control-label">Ordem de Serviço</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="file" id="ordem_de_servico" name="ordem_de_servico" class="form-control" accept="application/pdf, image/gif, image/jpeg" style="margin-bottom: 5px;"> 
                                        <font size='2'>Formato aceito: <strong>*.PDF, *.JPG</strong></font>
                                    </div>
                                </div>
                                
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url('ativo_veiculo/gerenciar/manutencao/'.$id_ativo_veiculo);?>">
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
