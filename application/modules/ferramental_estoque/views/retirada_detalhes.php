<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url("ferramental_estoque#{$retirada->id_retirada}"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                        <h2 class="title-1 m-b-25">Detalhes da Retirada</h2>
                        
                        <div class="card">
                          
                            <div class="card-body">
                                <h4 class="title-2 m-b-10">Dados da retirada</h4>

                                <?php if(isset($retirada->id_retirada_pai) && $retirada->id_retirada_pai > 0){ ?>
                                    <a href="<?php echo base_url('ferramental_estoque/detalhes/'.$retirada->id_retirada_pai); ?>">
                                        <button class="btn btn-info text-center">
                                            Renovação da Retirada: ID - <?php echo $retirada->id_retirada_pai; ?>
                                        </button>
                                    </a>
                                <hr>
                                <?php } ?>
                                <table class="table table-responsive-md table-striped table-bordered">
                                    <tr>
                                        <th>ID</th>
                                        <th>Funcionário</th>
                                        <th>Obra</th>
                                        <th>Data</th>
                                        <th>Devolução Prevista</th>
                                        <th>Status</th>
                                        <th>Gerenciar</th>
                                    </tr>
                                    <tr>
                                      <td><?php echo $retirada->id_retirada; ?></td>
                                      <td><?php echo $retirada->funcionario; ?></td>
                                      <td><?php echo $retirada->obra; ?></td>
                                      <td><?php echo $this->formata_data_hora($retirada->data_inclusao); ?></td>
                                      <td><?php echo $this->formata_data_hora($retirada->devolucao_prevista); ?></td>
                                      <td>
                                          <?php $status = $this->status($retirada->status); ?>
                                          <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                      </td>
                                      <td>
                                          <div class="btn-group" role="group">
                                            <button id="ferramental_requisicao_detalhes" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="ferramental_requisicao_detalhes">
                                                <a class="dropdown-item btn" href="<?php echo base_url("ferramental_estoque/detalhes_item/{$retirada->id_retirada}"); ?>">
                                                    <i class="fas fa-list"></i> Detalhar Itens
                                                </a>

                                                <?php if($retirada->status == 1){  ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a
                                                        class="dropdown-item btn confirmar_registro" data-tabela="<?php echo base_url("ferramental_estoque/detalhes/{$retirada->id_retirada}");?>" 
                                                        href="javascript:void(0)" data-registro="<?php echo $retirada->id_retirada;?>"
                                                        data-acao="Liberar Retirada"  data-redirect="true"
                                                        data-href="<?php echo base_url("ferramental_estoque/liberar_retirada/{$retirada->id_retirada}");?>"
                                                    >
                                                        <i class="fa fa-check 4x"></i>&nbsp;Liberar Retirada
                                                    </a>
                                                <?php } ?>

                                                <?php if(isset($retirada->termo_de_responsabilidade) && $retirada->status == 2){  ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a
                                                        class="dropdown-item btn confirmar_registro"
                                                        href="javascript:void(0)"
                                                        data-acao="Marcar" data-icon="info" data-message="false"
                                                        data-title="Marcar como Entregues" data-redirect="true"
                                                        data-text="Clique 'Sim, Marcar!' para confirmar o a entrega de todos os itens para o funcionário, não esquecendo de imprimir o Termo de Responsabilidade."
                                                        data-href="<?php echo base_url("ferramental_estoque/entregar_items_retirada/{$retirada->id_retirada}");?>"
                                                        data-tabela="<?php echo base_url("ferramental_estoque/detalhes/{$retirada->id_retirada}");?>"
                                                    >
                                                        <i class="fas fa-clipboard-list 4x"></i>&nbsp;Marcar como Entregues
                                                    </a>
                                                <?php } ?>

                                                <?php if(isset($retirada->termo_de_responsabilidade) && $retirada->status == 4){  ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a
                                                        class="dropdown-item btn confirmar_registro"
                                                        href="javascript:void(0)"
                                                        data-acao="Devolver" data-icon="info" data-message="false"
                                                        data-title="Devolver Retirada" data-redirect="true"
                                                        data-text="Clique 'Sim, Devolver!' para confirmar a devolução dos itens por parte do funcionário, considerando que imprimiu e assinou o Termo de Responsabilidade corretamente como esperado."
                                                        data-href="<?php echo base_url("ferramental_estoque/devolver_items_retirada/{$retirada->id_retirada}");?>"
                                                    >
                                                        <i class="fas fa-undo 4x"></i>&nbsp;Marcar como Devolvidos
                                                    </a>
                                                <?php } ?>

                                                <?php if($user->nivel == 1 && $retirada->status == 14){?> 
                                                    <div class="dropdown-divider"></div>
                                                    <a
                                                        class="dropdown-item btn confirmar_registro" data-tabela="<?php echo base_url("ferramental_estoque/detalhes/{$retirada->id_retirada}");?>" 
                                                        href="javascript:void(0)" data-registro="<?php echo $retirada->id_retirada;?>"
                                                        data-acao="Autorizar Retirada"  data-redirect="true"
                                                        data-href="<?php echo base_url("ferramental_estoque/liberar_retirada/{$retirada->id_retirada}");?>"
                                                    >
                                                       <i class="fa fa-check "></i>&nbsp;Autorizar Retirada
                                                    </a>
                                                <?php } ?>

                                                <?php if(isset($retirada->termo_de_responsabilidade)) { ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item btn" target="_blank" href="<?php echo base_url("assets/uploads/{$retirada->termo_de_responsabilidade}"); ?>">
                                                        <i class="fa fa-print"></i>&nbsp;Ver Termo de Resp.
                                                    </a>
                                                <?php } ?>

                                                <?php if(!isset($retirada->termo_de_responsabilidade) && in_array($retirada->status, [2, 4])){  ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item btn" download href="<?php echo base_url("ferramental_estoque/impimir_termo_resposabilidade/{$retirada->id_retirada}");?>">
                                                        <i class="fa fa-print 4x"></i>&nbsp;Imprimir Termo de Resp.
                                                    </a>
                                                <?php } ?>

                                                <?php if($retirada->status == 1) {?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item btn" href="<?php echo base_url("ferramental_estoque/editar/{$retirada->id_retirada}"); ?>">
                                                        <i class="fas fa-edit"></i> Editar  
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a 
                                                        class="dropdown-item btn confirmar_registro"  data-tabela="<?php echo base_url("ferramental_estoque");?>" 
                                                        href="javascript:void(0)" data-registro="<?php echo $retirada->id_retirada;?>"
                                                        data-acao="Remover Retirada"  data-redirect="true"
                                                        data-href="<?php echo base_url("ferramental_estoque/remove_retirada/{$retirada->id_retirada}");?>"
                                                    >                                                   
                                                        <i class="fas fa-trash"></i> Excluir                                               
                                                    </a>
                                                <?php } ?>
                                                
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " data-toggle="modal"  href="" data-target="#ajudaModal">
                                                    <i class="fa fa-question-circle"></i> Ajuda
                                                </a>
                                            </div>
                                        </div>
                                      </td>
                                    </tr>
                                </table>
                                <hr>

                                <?php if(!empty($retirada->items)){ ?>
                                <h4 class="title-2 m-b-10">Itens da Retirada</h4>
                                <table style="min-height: 180px;" class="table table--no-card table-responsive-md table-borderless table-striped table-earning" id="lista">
                                    <thead>
                                        <tr class="active">
                                            <th width="10%" scope="col">Item Id</th>
                                            <th width="60%" scope="col">Item</th>
                                            <th width="2.5%" scope="col">Código</th>
                                            <th width="10%" scope="col">Data da Entrega</th>
                                            <th width="10%" scope="col">Data da Devolucao</th>
                                            <th width="2.5%"scope="col">Situação</th>
                                            <th width="5%" scope="col">Gerenciar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($retirada->items as $item){ ?>
                                        <tr  width="100%">
                                            <td>
                                                <a href="<?php echo base_url("ferramental_estoque/detalhes_item/{$item->id_retirada}/{$item->id_retirada_item}"); ?>">
                                                    <?php echo $item->id_retirada_item; ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url("ferramental_estoque/detalhes_item/{$item->id_retirada}/{$item->id_retirada_item}"); ?>">
                                                    <?php echo $item->nome; ?>
                                                </a>
                                            </td>
                                            <td><?php echo $item->codigo; ?></td>
                                            <td><?php echo $this->formata_data_hora($item->data_retirada); ?></td>
                                            <td><?php echo $this->formata_data_hora($item->data_devolucao); ?></td>
                                            <td>
                                                <?php $status = $this->status($item->status); ?>
                                                <span class="badge badge-sm badge-<?php echo $status['class']; ?>">
                                                    <?php echo  $status['texto']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button id="ferramental_requisicao_item_detalhes" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Gerenciar
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="ferramental_requisicao_item_detalhes">
                                                        <a class="dropdown-item" href="<?php echo base_url("ferramental_estoque/detalhes_item/{$item->id_retirada}/{$item->id_retirada_item}"); ?>" class="btn btn-sm btn-secondary">
                                                            <i class="fa fa-list-alt"></i>&nbsp;Detalhes
                                                        </a>

                                                        <?php if($retirada->status == 4){  ?>
                                                            <div class="dropdown-divider"></div>
                                                            <a
                                                                class="dropdown-item confirmar_registro"
                                                                href="javascript:void(0)"
                                                                data-acao="Devolver" data-icon="info" data-message="false"
                                                                data-title="Devolver Retirada" data-redirect="true"
                                                                data-text="Clique 'Sim, Devolver!' para confirmar a devolução dos itens por parte do funcionário, considerando que imprimiu e assinou o Termo de Responsabilidade corretamente como esperado."
                                                                data-href="<?php echo base_url("ferramental_estoque/devolver_items_retirada/{$retirada->id_retirada}/{$item->id_retirada_item}");?>"
                                                            >
                                                                <i class="fas fa-undo 4x"></i>&nbsp;Devolver Item
                                                            </a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php } ?>

                               <div class="col">
                                    <h4 class="title-2 m-b-10">Observações</h4>
                                    <p class="m-t-20"><?php echo $retirada->observacoes ?? ''; ?></p>
                               </div>
                            </div>
                        </div>
               

                <?php if(in_array($retirada->status, [2, 4]) && isset($anexos)){  ?>
                    <div id="anexos" class="row">
                        <div class="col-12">
                            <?php $this->load->view('anexo/index', ['show_header' => false, 'permit_delete' => true]); ?>
                        </div>
                    </div>
                <?php }  ?>

                </div>
            </div>
    </div>
</div>
</div>

<?php 
    $this->load->view('retirada_modal_ajuda');
    $this->load->view('anexo/index_form_modal', ["show_header" => false]);
?>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
