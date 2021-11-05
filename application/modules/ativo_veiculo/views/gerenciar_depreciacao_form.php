<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/depreciacao/'.$id_ativo_veiculo); ?>">
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

                            <form action="<?php echo base_url('ativo_veiculo/depreciacao_salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($id_ativo_veiculo)){?>
                                <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $id_ativo_veiculo; ?>">
                                <?php } ?>

                                <p><strong>REGISTRO DE DEPRECIAÇÃO</strong></p>
                                <hr>
                                <table class="table table-responsive-md table-striped table-bordered table-sm">
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Modelo</th>
                                        <th>Ano</th>
                                        <th>Referência</th>
                                        <th>Valor</th>
                                        <th>Data</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo $dados_veiculo->tipo_veiculo; ?></td>
                                        <td><?php echo $dados_veiculo->veiculo; ?></td>
                                        <td><?php echo $dados_veiculo->ano; ?></td>
                                        <td><?php echo $dados_veiculo->fipe_mes_referencia; ?></td>
                                        <td>R$ <?php echo number_format($dados_veiculo->valor_fipe, 2, ',', '.'); ?></td>
                                        <td><?php echo date("d/m/Y H:i:s", strtotime($dados_veiculo->data)); ?></td>
                                    </tr>
                                    <?php foreach($lista as $value){ ?>
                                    <tr>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td><?php echo $value->fipe_mes_referencia; ?></td>
                                        <td>R$ <?php echo number_format($value->valor_fipe, 2, ',', '.'); ?></td>
                                        <td><?php echo date("d/m/Y H:i:s", strtotime($value->veiculo_data)); ?></td>
                                    </tr>
                                    <?php } ?> 
                                </table>
                                <hr>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="tipo_veiculo" class=" form-control-label">Tipo</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input  class="form-control" readonly value="<?php echo $dados_veiculo->tipo_veiculo; ?>"  id="tipo_veiculo" name="tipo_veiculo">
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <input type="text" class="form-control" readonly value="<?php echo $dados_veiculo->fabricante->marca; ?>">
                                        <input type="hidden" class="form-control" readonly value="<?php echo $dados_veiculo->id_marca; ?>"  id="id_marca" name="id_marca">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="tipo_veiculo" class=" form-control-label">Ano/Modelo</label>
                                    </div>                                    
                                    
                                    <div class="col-12 col-md-3">
                                        <input class="form-control" readonly value="<?php echo $dados_veiculo->ano; ?>"  id="ano" name="ano">
                                        <input type="hidden" class="form-control" readonly value="<?php echo $dados_veiculo->id_modelo; ?>"  id="id_modelo" name="id_modelo">
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <input class="form-control" readonly value="<?php echo $dados_veiculo->veiculo; ?>"  id="veiculo" name="veiculo">
                                    </div>                                    

                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="valor_fipe" class=" form-control-label">Valor Fipe</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input class="form-control" readonly value="<?php echo number_format($dados_veiculo->valor_fipe, 2, ',', '.'); ?>"  id="valor_fipe" name="valor_fipe">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <input class="form-control" readonly value="<?php echo $dados_veiculo->codigo_fipe; ?>"  id="codigo_fipe" name="codigo_fipe">
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <input class="form-control" readonly value="<?php echo $dados_veiculo->fipe_mes_referencia; ?>"  id="fipe_mes_referencia" name="fipe_mes_referencia">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_km" class=" form-control-label">Quilometragem</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="number" id="veiculo_km" name="veiculo_km" placeholder="KM" class="form-control" min="<?php echo $dados_veiculo->veiculo_km; ?>"  value="<?php echo $dados_veiculo->veiculo_km; ?>">
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <input type="text" id="veiculo_observacoes" name="veiculo_observacoes" placeholder="Observações" class="form-control" value="">
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
