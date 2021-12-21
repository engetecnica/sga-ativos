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
                        <div class="card-header">
                            <?php echo isset($depreciacao) && isset($depreciacao->id_ativo_veiculo_depreciacao) ? "Editar Registro de Depreciação" : "Novo Registro de Depreciação" ?>
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_veiculo/depreciacao_salvar'); ?>" method="post" enctype="multipart/form-data">
                                <?php if(isset($id_ativo_veiculo)){?>
                                <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $id_ativo_veiculo; ?>">
                                <?php } ?>

                                <?php if(isset($dados_veiculo) && isset($dados_veiculo->valor_fipe)){?>
                                    <input type="hidden" name="valor_fipe" id="valor_fipe" value="<?php echo $dados_veiculo->valor_fipe; ?>">
                                <?php } ?>

                                <?php if(isset($dados_veiculo) && isset($dados_veiculo->fipe_mes_referencia)){?>
                                    <input type="hidden" name="fipe_mes_referencia" id="fipe_mes_referencia" value="<?php echo $dados_veiculo->fipe_mes_referencia; ?>">
                                <?php } ?>

                                <?php if(isset($depreciacao) && isset($depreciacao->id_ativo_veiculo_depreciacao)){?>
                                    <input type="hidden" name="id_ativo_veiculo_depreciacao" id="id_ativo_veiculo_depreciacao" value="<?php echo $depreciacao->id_ativo_veiculo_depreciacao; ?>">
                                <?php } ?>

                                <?php 
                                    $total_depreciacao = 0;
                                    $debito_depreciacao = 0;
                                    $saldo_depreciacao = (float) $dados_veiculo->valor_fipe; 
                                    foreach($lista as $valor) {
                                        $debito_depreciacao = (float) $valor->veiculo_valor_depreciacao;
                                        $total_depreciacao += (float) $debito_depreciacao;
                                        $saldo_depreciacao -= (float) $debito_depreciacao;
                                    }
                                ?>

                                <table class="table table-responsive-md table-striped table-bordered table-sm">
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Modelo/Código FIPE</th>
                                        <th>Ano</th>
                                        <th>Referência</th>
                                        <th>Valor</th>
                                        <th>Data</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo $dados_veiculo->tipo_veiculo; ?></td>
                                        <td><?php echo $dados_veiculo->veiculo ." / ".$dados_veiculo->codigo_fipe; ?></td>
                                        <td><?php echo $dados_veiculo->ano; ?></td>
                                        <td><?php echo ucfirst($dados_veiculo->fipe_mes_referencia); ?></td>
                                        <td><?php echo $this->formata_moeda($dados_veiculo->valor_fipe); ?></td>
                                        <td><?php echo $this->formata_data($dados_veiculo->data); ?></td>
                                    </tr>
                                    <?php foreach($lista as $value){ ?>
                                    <tr>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td><?php echo ucfirst($value->fipe_mes_referencia); ?></td>
                                        <td><?php echo $this->formata_moeda($value->veiculo_valor_fipe); ?></td>
                                        <td><?php echo $this->formata_data($value->veiculo_data); ?></td>
                                    </tr>
                                    <?php } ?> 
                                    <?php if(isset($saldo_depreciacao)) { ?>
                                        <tr>
                                        <td></td>
                                        <td></td>
                                        <th>Total Depreciado</th>
                                        <td style="color: red;"><?php echo $this->formata_moeda($total_depreciacao); ?></td>
                                        <th>Saldo Restante</th>
                                        <td style="color: green;"><?php echo $this->formata_moeda($saldo_depreciacao); ?></td>
                                    </tr>
                                    <?php } ?>
                                </table>
                                <hr>
<!-- 
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
                                </div> -->

                                <!-- <div class="row form-group">
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

                                </div> -->

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_valor_depreciacao" class=" form-control-label">Valor de Depreciação</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input required="required" class="form-control valor" placeholder="0,00 R$" value="<?php echo isset($depreciacao) && isset($depreciacao->veiculo_valor_depreciacao) ?  $depreciacao->veiculo_valor_depreciacao : ''; ?>"  id="veiculo_valor_depreciacao" name="veiculo_valor_depreciacao">
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="veiculo_km" class=" form-control-label">Quilometragem</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input required="required" type="number" id="veiculo_km" name="veiculo_km" placeholder="0 KM" class="form-control" 
                                            min="<?php echo $dados_veiculo->veiculo_km; ?>"  value="<?php echo isset($depreciacao) && isset($depreciacao->veiculo_km) ?  $depreciacao->veiculo_km : ''; ?>">
                                    </div>

                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_valor_depreciacao" class=" form-control-label">Observações</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <textarea type="text" id="veiculo_observacoes" name="veiculo_observacoes" placeholder="Observações" class="form-control" 
                                        value="<?php echo isset($depreciacao) && isset($depreciacao->veiculo_observacoes) ?  $depreciacao->veiculo_observacoes : ''; ?>"></textarea>
                                    </div>                                   
                                </div>
                               
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url("ativo_veiculo/gerenciar/depreciacao/{$id_ativo_veiculo}");?>">
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
