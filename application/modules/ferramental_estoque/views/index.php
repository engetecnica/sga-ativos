<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap"> <a href="<?php echo base_url('ferramental_estoque/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Nova Retirada</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                <h2 class="title-1 m-b-20">Estoque de Ferramentas</h2>
                    <div class="table table--no-card table-responsive table--no- m-b-40">
                        <h3 class="title-1 m-b-25">Retiradas</h3>
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th>Retirada ID</th>
                                    <th>Obra</th>
                                    <th>Funcionário</th>
                                    <th>Data</th>
                                    <th>Devolução Prevista</th>
                                    <th>Status</th>
                                    <th>Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($retiradas as $valor){ ?>
                                <tr>
                                    <td id="<?php echo $valor->id_retirada; ?>">
                                        <a class="" href="<?php echo base_url("ferramental_estoque/detalhes/{$valor->id_retirada}"); ?>">    
                                            <?php echo $valor->id_retirada; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $valor->obra; ?></td>
                                    <td><?php echo $valor->funcionario; ?></td>
                                    <td><?php echo date("d/m/Y H:i", strtotime($valor->data_inclusao)); ?></td>
                                    <td><?php echo isset($item->devolucao_prevista) ? date("d/m/Y H:i", strtotime($item->devolucao_prevista)) : '-'; ?></td>
                                    <td>
                                        <?php $status = $this->status($valor->status); ?>
                                        <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button id="ferramental_requisicao_detalhes" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="ferramental_requisicao_detalhes">
                                                <a class="dropdown-item btn" href="<?php echo base_url("ferramental_estoque/detalhes/{$valor->id_retirada}"); ?>">
                                                    <i class="fas fa-list"></i> Detalhes
                                                </a>

                                                <?php if(isset($valor->termo_de_reponsabilidade)) { ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item btn" target="_blank" href="<?php echo base_url("assets/uploads/termo_de_reponsabilidade/{$valor->termo_de_reponsabilidade}"); ?>">
                                                        <i class="fa fa-print"></i>&nbsp;Ver Termo de Resp.
                                                    </a>
                                                <?php } ?>

                                                <?php if($valor->status == 1) {?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item btn" href="<?php echo base_url("ferramental_estoque/editar/{$valor->id_retirada}"); ?>">
                                                        <i class="fas fa-edit"></i> Editar  
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a 
                                                        class="dropdown-item btn confirmar_registro"  data-tabela="<?php echo base_url("ferramental_estoque");?>" 
                                                        href="javascript:void(0)" data-registro="<?php echo $valor->id_retirada;?>"
                                                        data-acao="Remover Retirada"  data-redirect="true"
                                                        data-href="<?php echo base_url("ferramental_estoque/remove_retirada/{$valor->id_retirada}");?>"
                                                    >                                                   
                                                        <i class="fas fa-trash"></i> Excluir                                               
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