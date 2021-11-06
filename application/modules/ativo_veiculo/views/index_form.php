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

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Veículos</h2>

                    <div class="card">
                        <div class="card-header"><?php echo isset($detalhes) && isset($detalhes->id_ativo_veiculo) ? "Editar" : "Novo"; ?> Veículo</div>
                        <div class="card-body">
                                <?php if (isset($detalhes) && isset($detalhes->id_ativo_veiculo)) {?>
                                <p class="m-b-10"><strong>DADOS DO VEICULO</strong></p>
                        
                                <table class="table table-responsive-md table-striped table-bordered">
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Modelo</th>
                                        <th>Ano</th>
                                        <th>Referência</th>
                                        <th>Valor FIPE</th>
                                        <th>Data</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo $detalhes->tipo_veiculo; ?></td>
                                        <td><?php echo $detalhes->veiculo; ?></td>
                                        <td><?php echo $detalhes->ano; ?></td>
                                        <td><?php echo $detalhes->fipe_mes_referencia; ?></td>
                                        <td>R$ <?php echo number_format($detalhes->valor_fipe, 2, ',', '.'); ?></td>
                                        <td><?php echo date("d/m/Y H:i:s", strtotime($detalhes->data)); ?></td>
                                    </tr>
                                </table>
                                <hr>
                                <?php } ?>

                            <form action="<?php echo base_url('ativo_veiculo/salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($detalhes) && isset($detalhes->id_ativo_veiculo)){?>
                                <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $detalhes->id_ativo_veiculo; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="tipo_veiculo" class=" form-control-label">Tipo</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <select <?php echo !isset($detalhes) && !isset($detalhes->id_ativo_veiculo) ? 'required="required"' : ''?> class="form-control selectpicker" 
                                            id="tipo_veiculo" name="tipo_veiculo" data-live-search="true"
                                        >
                                            <option value="">Tipo</option>
                                            <option value="moto">Moto</option>
                                            <option value="carro">Carro</option>
                                            <option value="caminhao">Caminhão</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <select <?php echo !isset($detalhes) && !isset($detalhes->id_ativo_veiculo) ? 'required="required"' : ''?> class="form-control selectpicker" id="id_marca" name="id_marca" data-live-search="true">
                                            <option value="">Marca</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <select <?php echo !isset($detalhes) && !isset($detalhes->id_ativo_veiculo) ? 'required="required"' : ''?> class="form-control selectpicker" id="id_modelo" name="id_modelo" data-live-search="true">
                                            <option value="">Modelo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="tipo_veiculo" class=" form-control-label">Ano/Modelo</label>
                                    </div>                                    
                                    
                                    <div class="col-12 col-md-3">
                                        <select <?php echo !isset($detalhes) && !isset($detalhes->id_ativo_veiculo) ? 'required="required"' : ''?> class="form-control selectpicker" id="ano" name="ano" data-live-search="true">
                                            <option value="">Ano</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <select <?php echo !isset($detalhes) && !isset($detalhes->id_ativo_veiculo) ? 'required="required"' : ''?> class="form-control" id="veiculo" name="veiculo">
                                            <option value="">Veículo</option>
                                        </select>
                                    </div>                                    

                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="valor_fipe" class=" form-control-label">Valor Fipe</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="text" id="valor_fipe" name="valor_fipe" placeholder="0,00" class="valor form-control" 
                                        value="<?php echo isset($detalhes) && isset($detalhes->valor_fipe) ? $detalhes->valor_fipe : '' ?>" readonly="">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <input required="required" type="text" id="codigo_fipe" name="codigo_fipe" placeholder="Cód Fipe" class="form-control" 
                                        value="<?php echo isset($detalhes) && isset($detalhes->codigo_fipe) ? $detalhes->codigo_fipe : '' ?>" readonly="">
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <input required="required" type="text" id="fipe_mes_referencia" name="fipe_mes_referencia" placeholder="Referência" style="text-transform: uppercase;" class="form-control" 
                                        value="<?php echo isset($detalhes) && isset($detalhes->fipe_mes_referencia) ? $detalhes->fipe_mes_referencia : '' ?>" readonly="">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_placa" class=" form-control-label">Placa</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="text" id="veiculo_placa" name="veiculo_placa" placeholder="Placa" class="form-control veiculo_placa" 
                                        value="<?php echo isset($detalhes) && isset($detalhes->veiculo_placa) ? $detalhes->veiculo_placa : '' ?>">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <input type="text" id="veiculo_renavam" name="veiculo_renavam" placeholder="Renavam" class="form-control" 
                                        value="<?php echo isset($detalhes) && isset($detalhes->veiculo_renavam) ? $detalhes->veiculo_renavam : '' ?>">
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <input type="text" id="veiculo_observacoes" name="veiculo_observacoes" placeholder="Observações" class="form-control" 
                                        value="<?php echo isset($detalhes) && isset($detalhes->veiculo_observacoes) ? $detalhes->veiculo_observacoes : '' ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_km" class=" form-control-label">Quilometragem</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="text" id="veiculo_km" name="veiculo_km" placeholder="KM" class="form-control" 
                                        value="<?php echo isset($detalhes) && isset($detalhes->veiculo_km) ? $detalhes->veiculo_km : '' ?>">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <input required="required" type="text" id="valor_funcionario" name="valor_funcionario" placeholder="Valor Funcionário" class="form-control valor" 
                                        value="<?php echo isset($detalhes) && isset($detalhes->valor_funcionario) ? $detalhes->valor_funcionario : '' ?>">
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <input required="required" type="text" id="valor_adicional" name="valor_adicional" placeholder="Valor Adicional" class="form-control valor" 
                                        value="<?php echo isset($detalhes) && isset($detalhes->valor_adicional) ? $detalhes->valor_adicional : '' ?>">
                                    </div>                                    
                                </div>


                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="situacao" class=" form-control-label">Data de Inclusão</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="date" id="veiculo_km_data" name="veiculo_km_data" class="form-control" 
                                        value="<?php echo isset($detalhes) && isset($detalhes->data) ? date("Y-m-d", strtotime($detalhes->data)) : date("Y-m-d"); ?>">
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <select name="situacao" id="situacao" class="form-control">
                                            <option <?php echo (isset($detalhes) && isset($detalhes->situacao)) && $detalhes->situacao == '0' ? 'selected="selected"' : '' ?> value="0">Ativo</option>
                                            <option <?php echo (isset($detalhes) && isset($detalhes->situacao)) && $detalhes->situacao == '1' ? 'selected="selected"' : '' ?> value="1">Inativo</option>
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

                                <?php if (isset($detalhes) && isset($detalhes->id_ativo_veiculo)) { ?>
                                <div class="pull-right">
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-info btn-md dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar Veículo
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/gerenciar/quilometragem/'.$detalhes->id_ativo_veiculo); ?>"><i class="fa fa-car"></i> Quilometragem</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/gerenciar/manutencao/'.$detalhes->id_ativo_veiculo); ?>"><i class="fas fa-wrench"></i> Manutenção</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/gerenciar/ipva/'.$detalhes->id_ativo_veiculo); ?>"><i class="fa fa-id-card"></i> IPVA</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/gerenciar/seguro/'.$detalhes->id_ativo_veiculo); ?>"><i class="fa fa-lock"></i> Seguro</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/gerenciar/depreciacao/'.$detalhes->id_ativo_veiculo); ?>"><i class="fa fa-sort-amount-asc"></i> Depreciação</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " href="<?php echo base_url('anexo/index/9/'.$detalhes->id_ativo_veiculo); ?>"><i class="fa fa-files-o"></i> Anexos</a>
                                            </div>
                                        </div>
                                </div>
                                <?php } ?>
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
