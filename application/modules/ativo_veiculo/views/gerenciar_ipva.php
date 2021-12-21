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
                        <a href="<?php echo base_url("ativo_veiculo"); ?>">
                        <button class="">
                        <i class="zmdi zmdi-arrow-left"></i>&nbsp;Listar Todos os Veículos</button></a>
                    </div>
                    <div class="overview-wrap m-t-10">
                        <a href="<?php echo base_url("ativo_veiculo/editar/{$id_ativo_veiculo}"); ?>">
                        <button class="">
                        <i class="zmdi zmdi-arrow-left"></i>&nbsp;Editar Veículo</button></a>
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
                                    <td>R$ <?php echo $this->formata_moeda($valor->ipva_custo); ?></td>
                                    <td><?php echo $valor->ipva_ano; ?></td>
                                    <td><?php echo $this->formata_data($valor->ipva_data_pagamento);?></td>
                                    <td><?php echo $this->formata_data($valor->ipva_data_vencimento); ?></td>
                                    <td width="15%">
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupGerenciarIpva" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupGerenciarIpva">
                                                <?php 
                                                    $permit_edit = $this->ativo_veiculo_model->permit_edit_ipva($valor->id_ativo_veiculo, $valor->id_ativo_veiculo_ipva);
                                                    if($permit_edit){ 
                                                ?>
                                                    <a class="dropdown-item " href="<?php echo base_url("ativo_veiculo/gerenciar/ipva/editar/{$valor->id_ativo_veiculo}/{$valor->id_ativo_veiculo_ipva}");?>">
                                                    <i class="fa fa-edit"></i>Editar
                                                    </a>
                                                <?php } ?>

                                                <?php if(isset($valor->comprovante) && $valor->comprovante != null){ ?>
                                                    <?php if($permit_edit){ ?> <div class="dropdown-divider"></div> <?php } ?>
                                                    <a class="dropdown-item" href="<?php echo base_url("ativo_veiculo/gerenciar/ipva/editar/{$valor->id_ativo_veiculo}/{$valor->id_ativo_veiculo_ipva}#anexos"); ?>">
                                                        <i class="fa fa-files-o"></i>&nbsp; Anexos
                                                    </a>
                                                <?php } ?>

                                                <?php if($this->ativo_veiculo_model->permit_delete_ipva($valor->id_ativo_veiculo, $valor->id_ativo_veiculo_ipva)){ ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a href="javascript:void(0)" data-href="<?php echo base_url("ativo_veiculo/ipva_deletar/{$valor->id_ativo_veiculo}/{$valor->id_ativo_veiculo_ipva}"); ?>" 
                                                        data-registro="<?php echo $valor->id_ativo_veiculo;?>" data-redirect="true"
                                                        data-tabela="ativo_veiculo/gerenciar/ipva/<?php echo $valor->id_ativo_veiculo; ?>" class="dropdown-item  deletar_registro">
                                                        <i class="fa fa-trash"></i> Excluir</a>
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
