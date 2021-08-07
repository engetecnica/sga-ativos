<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('ativo_veiculo'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Veículos</h2>

                    <div class="card">
                        <div class="card-header">Novo Veículo</div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_veiculo/salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($detalhes) && isset($detalhes->id_ativo_veiculo)){?>
                                <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $detalhes->id_ativo_veiculo; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="tipo_veiculo" class=" form-control-label">Tipo</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <select required="required" class="form-control selectpicker" id="tipo_veiculo" name="tipo_veiculo" data-live-search="true">
                                            <option>Tipo</option>
                                            <option value="moto">Moto</option>
                                            <option value="carro">Carro</option>
                                            <option value="caminhao">Caminhão</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <select required="required" class="form-control selectpicker" id="id_marca" name="id_marca" data-live-search="true">
                                            <option>Marca</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <select required="required" class="form-control selectpicker" id="id_modelo" name="id_modelo" data-live-search="true">
                                            <option>Modelo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="tipo_veiculo" class=" form-control-label">Ano/Modelo</label>
                                    </div>                                    
                                    
                                    <div class="col-12 col-md-3">
                                        <select required="required" class="form-control selectpicker" id="ano" name="ano" data-live-search="true">
                                            <option>Ano</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <select required="required" class="form-control" id="veiculo" name="veiculo">
                                            <option>Veículo</option>
                                        </select>
                                    </div>                                    

                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="valor_fipe" class=" form-control-label">Valor Fipe</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="text" id="valor_fipe" name="valor_fipe" placeholder="0,00" class="form-control" value="" readonly="">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <input required="required" type="text" id="codigo_fipe" name="codigo_fipe" placeholder="Cód Fipe" class="form-control" value="" readonly="">
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <input required="required" type="text" id="fipe_mes_referencia" name="fipe_mes_referencia" placeholder="Referência" style="text-transform: uppercase;" class="form-control" value="" readonly="">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_placa" class=" form-control-label">Placa</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="text" id="veiculo_placa" name="veiculo_placa" placeholder="Placa" class="form-control placa" value="">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <input type="text" id="veiculo_renavam" name="veiculo_renavam" placeholder="Renavam" class="form-control" value="">
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <input type="text" id="veiculo_observacoes" name="veiculo_observacoes" placeholder="Observações" class="form-control" value="">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_km" class=" form-control-label">Quilometragem</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="text" id="veiculo_km" name="veiculo_km" placeholder="KM" class="form-control" value="">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <input required="required" type="text" id="valor_funcionario" name="valor_funcionario" placeholder="Valor Funcionário" class="form-control valor" value="">
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <input required="required" type="text" id="valor_adicional" name="valor_adicional" placeholder="Valor Adicional" class="form-control valor" value="">
                                    </div>                                    
                                </div>


                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="situacao" class=" form-control-label">Data de Inclusão</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="date" id="veiculo_km_data" name="veiculo_km_data" class="form-control" value="<?php echo date("Y-m-d"); ?>">
                                    </div>

                                    <div class="col-12 col-md-7">
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
