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
                                <!-- Detalhes da Retirada -->
                                <table class="table table--no-card table-responsive-md table-borderless table-striped table-earning" id="lista">
                                    <thead>
                                        <tr class="active">
                                          <th width="10%" scope="col">Retirada ID</th>
                                          <th width="10%" scope="col">Funcionário</th>
                                          <th width="" scope="col">Obra</th>
                                          <th width="" scope="col">Data</th>
                                          <th width="" scope="col">Status</th>
                                          <th width="" scope="col">Detalhes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <td><?php echo $retirada->id_retirada; ?></td>
                                      <td><?php echo $retirada->funcionario; ?></td>
                                      <td><?php echo $retirada->obra; ?></td>
                                      <td><?php echo date("d/m/Y H:i", strtotime($retirada->data_inclusao)); ?></td>
                                      <td>
                                          <?php $status = $this->status($retirada->status); ?>
                                          <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                      </td>
                                      <td>
                                          <a href="<?php echo base_url("ferramental_estoque/detalhes_item/{$retirada->id_retirada}"); ?>" 
                                            class="btn btn-sm btn-outline-<?php echo $status['class']; ?>"
                                          >
                                              Detalhes
                                          </a>
                                        
                                        <?php if(isset($retirada->termo_de_reponsabilidade)) { ?>
                                          <a target="_blank" href="<?php echo base_url("assets/uploads/ferramental_estoque/{$retirada->termo_de_reponsabilidade}"); ?>">
                                            <button id="btnGroupDrop1" class="btn btn-danger" type="button" id="impimir_termo_btn">
                                                <i class="fa fa-print 4x"></i>&nbsp; 
                                                Termo de Reponsabilidade
                                            </button>
                                          </a>
                                        <?php } ?>

                                          <?php if($retirada->status == 1) {?>
                                            <a href="<?php echo base_url("ferramental_estoque/editar/{$retirada->id_retirada}"); ?>">
                                                <button class="btn btn-sm btn-primary" type="button">                                                    
                                                    <i class="fas fa-edit"></i>
                                                </button>   
                                            </a>
                                            <a 
                                            class="confirmar_registro"  data-tabela="<?php echo base_url("ferramental_estoque");?>" 
                                            href="javascript:void(0)" data-registro="<?php echo $retirada->id_retirada;?>"
                                            data-acao="Remover Retirada"  data-redirect="true"
                                            data-href="<?php echo base_url("ferramental_estoque/remove_retirada/{$retirada->id_retirada}");?>"
                                            >
                                                <button class="btn btn-sm btn-danger" type="button">                                                    
                                                    <i class="fas fa-trash"></i>
                                                </button>                                                
                                            </a>
                                          <?php } ?>
                                      </td>
                                    </tbody>
                                </table>

                                <hr>

                                <?php if(!empty($retirada->items)){ ?>
                                <h3 class="title-1 m-b-25">Itens</h3>
                                <table class="table table--no-card table-responsive-md table-borderless table-striped table-earning" id="lista2">
                                    <thead>
                                        <tr class="active">
                                            <th width="10%" scope="col">Item Id</th>
                                            <th width="10%" scope="col">Item</th>
                                            <th width="" scope="col">Quantidade</th>
                                            <th width="" scope="col">Data da Entrega</th>
                                            <th width="" scope="col">Data da Devolucao</th>
                                            <th width="" scope="col">Devolução Prevista</th>
                                            <th width="" scope="col">Situação</th>
                                            <th width="" scope="col">Detalhes</th>
                                            <?php if($user->nivel == 2 && $retirada->status == 4){  ?>
                                                <th>Devolver</th>
                                            <?php } ?>
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
                                            <td><?php echo $item->quantidade; ?></td>
                                            <td><?php echo isset($item->data_retirada) ? date("d/m/Y H:i", strtotime($item->data_retirada)) : '-'; ?></td>
                                            <td><?php echo isset($item->devolucao_prevista) ? date("d/m/Y H:i", strtotime($item->data_devolucao)) : '-'; ?></td>
                                            <td><?php echo isset($item->devolucao_prevista) ? date("d/m/Y H:i", strtotime($item->devolucao_prevista)) : '-'; ?></td>
                                            <td>
                                                <?php $status = $this->status($item->status); ?>
                                                <span class="badge badge-sm badge-<?php echo $status['class']; ?>">
                                                    <?php echo  $status['texto']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url("ferramental_estoque/detalhes_item/{$item->id_retirada}/{$item->id_retirada_item}"); ?>" class="btn btn-sm btn-outline-<?php echo $status['class']; ?>">
                                                    Detalhes
                                                </a>
                                            </td>
                                            <?php if($user->nivel == 2 && $retirada->status == 4){  ?>
                                            <td>
                                                <a
                                                    class="confirmar_registro pull-right"
                                                    href="javascript:void(0)"
                                                    data-acao="Devolver" data-icon="info" data-message="false"
                                                    data-title="Devolver Retirada" data-redirect="true"
                                                    data-text="Clique 'Sim, Devolver!' para confirmar a devolução dos itens por parte do funcionário, considerando que imprimiu e assinou o Termo de Responsabilidade corretamente como esperado."
                                                    data-href="<?php echo base_url("ferramental_estoque/devolver_items_retirada/{$retirada->id_retirada}/{$item->id_retirada_item}");?>"
                                                >
                                                    <button class="btn btn-sm btn-secondary" type="button" id="Devolver_retirada_btn">
                                                        <i class="fas fa-undo 4x"></i>&nbsp;
                                                        Devolver
                                                    </button>
                                                </a>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php } ?>

                                <div class="container row">
                                <?php if(in_array($retirada->status, [2, 4])){  ?>
                                    <hr>
                                    <form class="col" action="<?php echo base_url("ferramental_estoque/anexar_termo_resposabilidade/{$retirada->id_retirada}"); ?>" method="post" enctype="multipart/form-data">
                                        <div class="row form-group">
                                            <div class="col col-md-2">
                                                <label for="ferramental_estoque" class=" form-control-label">Anexar Termo</label>
                                            </div>
                                            <div class="col col-md-10">
                                                <input required="required" type="file" id="ferramental_estoque" name="ferramental_estoque" class="form-control" accept="application/pdf, image/*, application/vnd.ms-excel" style="margin-bottom: 5px;"> 
                                                <small size='2'>Formato aceito: <strong>*.PDF, *.XLS, *.XLSx, *.JPG, *.PNG, *.JPEG, *.GIF</strong></small>
                                                <small size='2'>Tamanho Máximo: <strong><?php echo $upload_max_filesize;?></strong></small>
                                            </div>
                                        </div>
                                        
                                        <div class="pull-right">
                                            <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i>&nbsp; Anexar</button>
                                        </div>
                                    </form>
                                <?php } ?>
                                </div>


                                <?php if($retirada->status == 1){  ?>
                                    <hr>
                                    <div class="text-center">
                                      <a
                                        class="confirmar_registro" data-tabela="<?php echo base_url("ferramental_estoque/detalhes/{$retirada->id_retirada}");?>" 
                                        href="javascript:void(0)" data-registro="<?php echo $retirada->id_retirada;?>"
                                        data-acao="Liberar Retirada"  data-redirect="true"
                                        data-href="<?php echo base_url("ferramental_estoque/liberar_retirada/{$retirada->id_retirada}");?>"
                                      >
                                        <button class="btn btn-md btn-primary" type="submit" id="liberar_retirada_btn">
                                            <i class="fa fa-check 4x"></i>&nbsp;
                                            Liberar Retirada
                                        </button>
                                      </a><br>
                                      <small>Clique para Liberar ou Recusar a Requisição dos itens solicitados.</small>
                                    </div>
                                <?php } ?>

                             <div class="text-center m-b-40">

                                <?php if(!isset($retirada->termo_de_reponsabilidade) && in_array($retirada->status, [2, 4])){  ?>
                                      <a class="pull-left" download href="<?php echo base_url("ferramental_estoque/impimir_termo_resposabilidade/{$retirada->id_retirada}");?>">
                                        <button class="btn btn-md btn-primary2" type="button" id="impimir_termo_btn">
                                            <i class="fa fa-print 4x"></i>&nbsp;
                                            Imprimir Termo de Reponsabilidade
                                        </button>
                                      </a>
                                <?php } ?>
                                    
                            
                                <?php if(isset($retirada->termo_de_reponsabilidade) && $retirada->status == 2){  ?>
                                      <a
                                        class="confirmar_registro text-center"
                                        href="javascript:void(0)"
                                        data-acao="Marcar" data-icon="info" data-message="false"
                                        data-title="Marcar como Entregues" data-redirect="true"
                                        data-text="Clique 'Sim, Marcar!' para confirmar o a entrega de todos os itens para o funcionário, não esquecendo de imprimir o Termo de Responsabilidade."
                                        data-href="<?php echo base_url("ferramental_estoque/entregar_items_retirada/{$retirada->id_retirada}");?>"
                                        data-tabela="<?php echo base_url("ferramental_estoque/detalhes/{$retirada->id_retirada}");?>"
                                      >
                                        <button class="btn btn-md btn-success" type="button" id="entregar_items_retirada_btn">
                                            <i class="fas fa-clipboard-list 4x"></i>&nbsp;
                                           Marcar como Entregues
                                        </button>
                                      </a>
                                <?php } ?>

                                <?php if(isset($retirada->termo_de_reponsabilidade) && $retirada->status == 4){  ?>
                                      <a
                                        class="confirmar_registro text-center"
                                        href="javascript:void(0)"
                                        data-acao="Devolver" data-icon="info" data-message="false"
                                        data-title="Devolver Retirada" data-redirect="true"
                                        data-text="Clique 'Sim, Devolver!' para confirmar a devolução dos itens por parte do funcionário, considerando que imprimiu e assinou o Termo de Responsabilidade corretamente como esperado."
                                        data-href="<?php echo base_url("ferramental_estoque/devolver_items_retirada/{$retirada->id_retirada}");?>"
                                      >
                                        <button class="btn btn-md btn-secondary" type="button" id="Devolver_retirada_btn">
                                            <i class="fas fa-undo 4x"></i>&nbsp;
                                            Marcar como Devolvidos
                                        </button>
                                      </a>
                                <?php } ?>
                                </div>

                                <?php if($user->nivel == 1 && $retirada->status == 14){?> 
                                <hr>
                                <div class="text-center">
                                    <a
                                        class="confirmar_registro" data-tabela="<?php echo base_url("ferramental_estoque/detalhes/{$retirada->id_retirada}");?>" 
                                        href="javascript:void(0)" data-registro="<?php echo $retirada->id_retirada;?>"
                                        data-acao="Autorizar Retirada"  data-redirect="true"
                                        data-href="<?php echo base_url("ferramental_estoque/liberar_retirada/{$retirada->id_retirada}");?>"
                                    >
                                        <button class="btn-custom" type="submit" id="liberar_retirada_btn">
                                            <i class="fa fa-check "></i>&nbsp;
                                            Autorizar Retirada
                                        </button>
                                    </a>
                                </div>
                            <?php } ?>


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
