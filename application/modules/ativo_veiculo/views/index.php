<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
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
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th width="7%">Id</th>
                                    <th>Tipo</th>
                                    <th>Veículo</th>
                                    <th>Placa</th>
                                    <th>Tabela Fipe</th>
                                    <th>Referência</th>
                                    <th>Situação</th>
                                    <th width="7%">Registros</th>
                                    <th class="text-right">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr>
                                    <td><?php echo $valor->id_ativo_veiculo; ?></td>
                                    <td style="text-transform: uppercase;"><?php echo $valor->tipo_veiculo; ?></td>
                                    <td><?php echo $valor->veiculo; ?></td>
                                    <td><?php echo $valor->veiculo_placa; ?></td>
                                    <td>R$ <?php echo number_format($valor->valor_fipe, 2, ",", "."); ?></td>
                                    <td style="text-transform: uppercase;"><?php echo $valor->fipe_mes_referencia; ?></td>
                                    <td>
                                      <?php $situacao = $this->get_situacao($valor->situacao, 'DESCARTADO', 'secondary');?>
                                      <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar Veículo
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <a class="dropdown-item" href="<?php echo base_url('ativo_veiculo/gerenciar/quilometragem/'.$valor->id_ativo_veiculo); ?>">Registrar Quilometragem</a>
                                                <a class="dropdown-item" href="<?php echo base_url('ativo_veiculo/gerenciar/manutencao/'.$valor->id_ativo_veiculo); ?>">Registrar Manutenção</a>
                                                <a class="dropdown-item" href="<?php echo base_url('ativo_veiculo/gerenciar/ipva/'.$valor->id_ativo_veiculo); ?>">Registrar IPVA</a>
                                                <a class="dropdown-item" href="<?php echo base_url('ativo_veiculo/gerenciar/depreciacao/'.$valor->id_ativo_veiculo); ?>">Registrar Depreciação</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <a href="javascript:void(0)" data-href="<?php echo base_url('ativo_veiculo'); ?>/deletar/<?php echo $valor->id_ativo_veiculo; ?>" data-registro="<?php echo $valor->id_ativo_veiculo;?>" data-tabela="ativo_veiculo" class="deletar_registro"><i class="fas fa-remove"></i></a>
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
