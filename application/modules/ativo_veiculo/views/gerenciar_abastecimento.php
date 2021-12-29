<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/abastecimento/adicionar/'.$id_ativo_veiculo); ?>">
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
                    <h2 class="title-1 m-b-25">Gerenciar Abastecimento</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Km Atual</th>
                                    <th>Combustível</th>
                                    <th>Unidades (L/M&sup3;)</th>
                                    <th>Custo</th>
                                    <th>Data</th>
                                    <th>Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach($lista as $valor){ 
                                        @$media = ($valor->veiculo_km_final - $valor->veiculo_km_inicial) / $valor->veiculo_litros;
                                ?>
                                <tr>
                                    <td><?php echo $valor->id_ativo_veiculo_abastecimento; ?></td>
                                    <td><?php echo $valor->veiculo_km; ?></td>
                                    <td><?php echo ucfirst($valor->combustivel); ?></td>
                                    <td><?php echo $valor->combustivel_unidade_total ." "; echo $valor->combustivel_unidade_tipo == '0' ? 'L' : "M&sup3;"; ?></td>
                                    <td><?php echo $this->formata_moeda($valor->abastecimento_custo); ?></td>
                                    <td><?php echo $this->formata_data($valor->abastecimento_data); ?></td>
                                    <td> 
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupGerenciarAbastecimento" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupGerenciarAbastecimento">
                                                <?php 
                                                    $permit_edit = $this->ativo_veiculo_model->permit_edit_abastecimento($valor->id_ativo_veiculo, $valor->id_ativo_veiculo_abastecimento);
                                                    if($permit_edit){
                                                 ?>
                                                    <a class="dropdown-item " href="<?php echo base_url("ativo_veiculo/gerenciar/abastecimento/editar/{$valor->id_ativo_veiculo}/{$valor->id_ativo_veiculo_abastecimento}");?>">
                                                    <i class="fa fa-edit"></i> Editar
                                                    </a>
                                                <?php } ?>

                                                <?php if(isset($valor->comprovante) && $valor->comprovante != null){ ?>
                                                    <?php if($permit_edit){ ?> <div class="dropdown-divider"></div> <?php } ?>
                                                    <a class="dropdown-item" href="<?php echo base_url("ativo_veiculo/gerenciar/abastecimento/editar/{$valor->id_ativo_veiculo}/{$valor->id_ativo_veiculo_abastecimento}#anexos"); ?>">
                                                        <i class="fa fa-files-o"></i>&nbsp; Anexos
                                                    </a>
                                                <?php } ?>

                                                <?php if($this->ativo_veiculo_model->permit_delete_abastecimento($valor->id_ativo_veiculo, $valor->id_ativo_veiculo_abastecimento)){ ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a href="javascript:void(0)" data-href="<?php echo base_url("ativo_veiculo/abastecimento_deletar/{$valor->id_ativo_veiculo}/{$valor->id_ativo_veiculo_abastecimento}"); ?>" 
                                                        data-registro="<?php echo $valor->id_ativo_veiculo;?>" data-redirect="true"
                                                        data-tabela="ativo_veiculo/gerenciar/abastecimento/<?php echo $valor->id_ativo_veiculo; ?>" 
                                                        class="dropdown-item  deletar_registro"
                                                    >
                                                    <i class="fa fa-trash"></i> Excluir
                                                    </a>
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
