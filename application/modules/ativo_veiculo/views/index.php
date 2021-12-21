<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="<?php echo base_url('ativo_veiculo/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Veículos</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="lista" style="min-height: 450px !important;">
                            <thead>
                                <tr>
                                    <th width="7%">Id</th>
                                    <th>Placa / ID Interna</th>
                                    <th>Veículo</th>
                                    <th>Tipo</th>
                                    <th>Tabela Fipe</th>
                                    <th>Referência</th>
                                    <th>Situação</th>
                                    <th class="text-right">Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr>
                                    <td><a href="<?php echo base_url('ativo_veiculo/editar/'.$valor->id_ativo_veiculo); ?>"><?php echo $valor->id_ativo_veiculo; ?></a></td>
                                    <td><?php echo $valor->veiculo_placa ?: $valor->id_interno_maquina; ?></td>
                                    <td><?php echo isset($valor->marca) ? "{$valor->marca} - {$valor->modelo}" : ''; ?></td>
                                    <td style="text-transform: uppercase;"><?php echo $valor->tipo_veiculo; ?></td>
                                    <td>R$ <?php echo number_format($valor->valor_fipe, 2, ",", "."); ?></td>
                                    <td style="text-transform: uppercase;"><?php echo $valor->fipe_mes_referencia; ?></td>
                                    <td>
                                      <?php $situacao = $this->get_situacao($valor->situacao, 'DESCARTADO', 'secondary');?>
                                      <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/editar/'.$valor->id_ativo_veiculo); ?>"><i class="fa fa-edit"></i> Editar</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/gerenciar/quilometragem/'.$valor->id_ativo_veiculo); ?>"><i class="fa fa-road"></i>&nbsp; Quilometragem</a>
                                                <!-- <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/gerenciar/abastecimento/'.$valor->id_ativo_veiculo); ?>"><i class="fas fa-gas-pump"></i>&nbsp; Abastecimento</a>      -->
                                                <?php if ($valor->tipo_veiculo == "maquina") {?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/gerenciar/operacao/'.$valor->id_ativo_veiculo); ?>"><i class="fa fa-industry"></i>&nbsp; Operação</a>
                                                <?php } ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/gerenciar/manutencao/'.$valor->id_ativo_veiculo); ?>"><i class="fas fa-wrench"></i> Manutenção</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/gerenciar/ipva/'.$valor->id_ativo_veiculo); ?>"><i class="fa fa-id-card"></i> IPVA</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/gerenciar/seguro/'.$valor->id_ativo_veiculo); ?>"><i class="fa fa-lock"></i> Seguro</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/gerenciar/depreciacao/'.$valor->id_ativo_veiculo); ?>"><i class="fa fa-sort-amount-asc"></i> Depreciação</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " href="<?php echo base_url("ativo_veiculo/editar/{$valor->id_ativo_veiculo}#anexos"); ?>"><i class="fa fa-files-o"></i> Anexos</a>
                                                <?php if ($this->ativo_veiculo_model->permit_delete($valor->id_ativo_veiculo)) {?>
                                                <div class="dropdown-divider"></div>
                                                <a href="javascript:void(0)" data-href="<?php echo base_url('ativo_veiculo'); ?>/deletar/<?php echo $valor->id_ativo_veiculo; ?>" data-registro="<?php echo $valor->id_ativo_veiculo;?>" 
                                                data-tabela="ativo_veiculo" class="dropdown-item  deletar_registro"><i class="fas fa-trash"></i>  Excluir</a>
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
