<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/ipva/adicionar/'.$id_ativo_veiculo); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                    </div>
                    <div class="overview-wrap m-t-10">
                        <a href="<?php echo base_url("ativo_veiculo/editar/{$id_ativo_veiculo}"); ?>">
                        <button class="">
                        <i class="zmdi zmdi-arrow-left"></i>&nbsp;Editar Ativo</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar IPVA</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th width="7%">Veículo</th>
                                    <th>Placa</th>
                                    <th>Custo</th>
                                    <th>Ano</th>
                                    <th>Pagamento</th>
                                    <th>Vencimento</th>
                                    <th>Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach($lista as $valor){ 
                                ?>
                                <tr>
                                    <td><?php echo $valor->id_ativo_veiculo_ipva; ?></td>
                                    <td><?php echo $valor->veiculo; ?></td>
                                    <td><?php echo $valor->veiculo_placa; ?></td>
                                    <td>R$ <?php echo number_format($valor->ipva_custo, 2, ',', '.'); ?></td>
                                    <td><?php echo $valor->ipva_ano; ?></td>
                                    <td><?php echo $valor->ipva_data_pagamento ? date("d/m/Y", strtotime($valor->ipva_data_pagamento)) : "-"; ?></td>
                                    <td><?php echo $valor->ipva_data_vencimento ? date("d/m/Y", strtotime($valor->ipva_data_vencimento)) : "-"; ?></td>
                                    <td width="15%">
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupGerenciarIpva" type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar IPVA
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupGerenciarIpva">
                                                <?php if($this->ativo_veiculo_model->permit_edit_ipva($valor->id_ativo_veiculo, $valor->id_ativo_veiculo_ipva)){ ?>
                                                    <a class="dropdown-item" href="<?php echo base_url("ativo_veiculo/gerenciar/ipva/editar/{$valor->id_ativo_veiculo}/{$valor->id_ativo_veiculo_ipva}");?>">
                                                    Editar
                                                    </a>
                                                <?php } ?>

                                                <?php if($valor->comprovante_ipva){ ?>
                                                    <a class="dropdown-item" target="_blank" href="<?php echo base_url("assets/uploads/comprovante_ipva/{$valor->comprovante_ipva}"); ?>">
                                                        Visualizar Comprovante
                                                    </a>  
                                                    <a class="dropdown-item" target="_blank" download href="<?php echo base_url("assets/uploads/comprovante_ipva/{$valor->comprovante_ipva}"); ?>">
                                                        Baixar Comprovante
                                                    </a>                           
                                                <?php } ?>

                                                <?php if($this->ativo_veiculo_model->permit_delete_ipva($valor->id_ativo_veiculo, $valor->id_ativo_veiculo_ipva)){ ?>
                                                    <a href="javascript:void(0)" data-href="<?php echo base_url("ativo_veiculo/ipva_deletar/{$valor->id_ativo_veiculo}/{$valor->id_ativo_veiculo_ipva}"); ?>" 
                                                        data-registro="<?php echo $valor->id_ativo_veiculo;?>" data-redirect="true"
                                                        data-tabela="ativo_veiculo/gerenciar/ipva/<?php echo $valor->id_ativo_veiculo; ?>" class="dropdown-item deletar_registro">Excluir</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                               <?php } ?>
                            </tbody>
                        </table>
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
