<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="<?php echo base_url('ferramental_requisicao/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>&nbsp;
                            <?php if ($user->nivel == 1) { ?>
                                Nova Transferência
                            <?php } if ($user->nivel == 2) { ?>
                                Nova Requisição
                            <?php }  ?>
                        </button></a>
                        </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Requisição de Ferramentas</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning border-bottom-1" id="lista">
                            <thead>
                                <tr>
                                    <th>Requisão ID</th>
                                    <th>Data</th>
                                    <th>Tipo</th>
                                    <th>É uma requisição complementar?</th>
                                    <th>Complementa</th>
                                    <th>Status</th>
                                    <th>Origem</th>
                                    <th>Destino</th>
                                    <th>Solicitante</th>
                                    <th>Despachante</th>
                                    <th>Opções</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr>
                                    <td id="<?php echo $valor->id_requisicao; ?>">
                                        <a href="<?php echo base_url("ferramental_requisicao/detalhes/{$valor->id_requisicao}");?>">
                                            <?php echo $valor->id_requisicao; ?>
                                        </a>
                                    </td>
                                    <td><?php echo date("d/m/Y H:i:s", strtotime($valor->data_inclusao)); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $valor->tipo == 1 ? 'primary': 'secondary';?>"><?php echo $valor->tipo == 1 ? 'Requisição': 'Devolução';?></span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $valor->id_requisicao_mae === null ? 'danger': 'success' ;?>"><?php echo $valor->id_requisicao_mae === null ? 'Não': 'Sim';?></span>
                                    </td>
                                    <td>
                                        <?php if($valor->id_requisicao_mae != null) { ?>
                                            <a href="<?php echo base_url("ferramental_requisicao/detalhes/{$valor->id_requisicao_mae}");?>">
                                                <?php echo $valor->id_requisicao_mae; ?>
                                            </a>
                                        <?php } else {?>
                                            -
                                        <?php }?>
                                    </td>
                                    <td>
                                        <?php $status = $this->status($valor->status); ?>
                                        <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                    </td>
                                    <td><?php echo $valor->origem; ?></td>
                                    <td><?php echo $valor->destino; ?></td>
                                    <td><?php echo $valor->solicitante_nome; ?></td>
                                    <td><?php echo $valor->despachante_nome; ?></td>
                                    <td>
                                    <div class="btn-group" role="group">
                                        <button id="<?php echo "requisicao_group{$valor->id_requisicao}";?>" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Gerenciar
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="<?php echo "requisicao_group{$valor->id_requisicao}";?>">
                                        <a 
                                            class="dropdown-item" 
                                            href="<?php echo base_url("ferramental_requisicao/detalhes/{$valor->id_requisicao}");?>"
                                        >
                                            <i class="fas fa-list"></i> Detalhes 
                                        </a>

                                        <?php if ($valor->status == 3) { ?>
                                            <div class="dropdown-divider" ></div>

                                            <a 
                                                class="dropdown-item"
                                                href="<?php echo base_url("ferramental_requisicao/gerar_romaneio/{$valor->id_requisicao}");?>"
                                            >
                                                <i class="fa fa-table"></i>&nbsp; Gerar Romaneio 
                                            </a>
                                        <?php } ?>


                                        <?php if ($valor->status == 3 && $valor->romaneio) { ?>
                                            <div class="dropdown-divider" ></div>

                                            <a 
                                                class="dropdown-item" target="_blank"
                                                href="<?php echo base_url("assets/uploads/{$valor->romaneio}");?>"
                                            >
                                                <i class="fa fa-eye"></i>&nbsp; Visualizar Romaneio 
                                            </a>
                                            <div class="dropdown-divider" ></div>
                                            <a 
                                                class="dropdown-item" download
                                                href="<?php echo base_url("assets/uploads/{$valor->romaneio}");?>"
                                            >
                                                <i class="fa fa-download"></i>&nbsp; Baixar Romaneio 
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