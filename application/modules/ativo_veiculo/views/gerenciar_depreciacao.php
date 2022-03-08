<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/depreciacao/adicionar/'.$id_ativo_veiculo); ?>">
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

            <?php 
                $total = 0;
                foreach($lista as $valor){ 
                    $total += (float) $valor->veiculo_valor_depreciacao;
                }
            ?>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Depreciação</h2>
                    <div  class="table-responsive m-b-40">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th width="15%">Veículo</th>
                                    <th width="15%">Placa</th>
                                    <th width="15%">Cód Fipe</th>
                                    <th>Ano</th>
                                    <th>Inclusão</th>
                                    <th width="15%">Valor Fipe</th>
                                    <th>Total Depreciado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $veiculo->veiculo; ?></td>
                                    <td><?php echo $veiculo->veiculo_placa; ?></td>
                                    <td><?php echo $veiculo->codigo_fipe; ?></td>
                                    <td><?php echo $veiculo->ano; ?></td>
                                    <td><?php echo $this->formata_data($veiculo->data); ?></td>
                                    <td style="color:blue;"><?php echo $this->formata_moeda($veiculo->valor_fipe); ?></td>
                                    <td style="color:red;"><?php echo $this->formata_moeda($total); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="section__content section__content--p30">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th width="15%">ID</th>
                                    <th width="15%">Data/Hora</th>
                                    <th width="20%">Referência</th>
                                    <th width="15%">Kilometragem</th>
                                    <th width="15%">Valor Depreciado</th>
                                    <th width="15%">Saldo Restante</th>
                                    <th>Observações</th>
                                    <th>Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $total_depreciacao = 0;
                                    $saldo_depreciacao = (float) $veiculo->valor_fipe; 
                                    foreach($lista as $valor) {
                                        $debito_depreciacao = (float) $valor->veiculo_valor_depreciacao;
                                        $total_depreciacao += (float) $debito_depreciacao;
                                        $saldo_depreciacao -= (float) $debito_depreciacao;
                                ?>
                                <tr>
                                    <td><?php echo $valor->id_ativo_veiculo_depreciacao; ?></td>
                                    <td><?php echo $this->formata_data_hora($valor->veiculo_data); ?></td>
                                    <td><?php echo ucfirst($valor->fipe_mes_referencia); ?></td>
                                    <td><?php echo $valor->veiculo_km . " KM"; ?></td>
                                    <td style="color: red;"><?php echo $this->formata_moeda($debito_depreciacao); ?></td>
                                    <td style="color: green;"><?php echo $this->formata_moeda($saldo_depreciacao); ?></td>
                                    <td><?php echo $valor->veiculo_observacoes; ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupGerenciarDepreciacao" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupGerenciarDepreciacao">
                                                <?php if($this->ativo_veiculo_model->permit_edit_depreciacao($valor->id_ativo_veiculo, $valor->id_ativo_veiculo_depreciacao)){ ?>
                                                    <a class="dropdown-item btn" href="<?php echo base_url("ativo_veiculo/gerenciar/depreciacao/editar/{$valor->id_ativo_veiculo}/{$valor->id_ativo_veiculo_depreciacao}");?>">
                                                    <i class="fa fa-edit"></i>Editar
                                                    </a>
                                                <?php } ?>

                                                <?php if($this->ativo_veiculo_model->permit_delete_depreciacao($valor->id_ativo_veiculo, $valor->id_ativo_veiculo_depreciacao)){ ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a href="javascript:void(0)" data-href="<?php echo base_url("ativo_veiculo/depreciacao_deletar/{$valor->id_ativo_veiculo}/{$valor->id_ativo_veiculo_depreciacao}"); ?>" 
                                                        data-registro="<?php echo $valor->id_ativo_veiculo;?>" data-redirect="true"
                                                        data-tabela="ativo_veiculo/gerenciar/depreciacao/<?php echo $valor->id_ativo_veiculo; ?>" class="dropdown-item btn deletar_registro">
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

