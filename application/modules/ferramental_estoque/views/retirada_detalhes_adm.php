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
                    <h2 class="title-1 m-b-25">Detalhes da Retirada Administração</h2>
                    <div class="card">
                        <div class="card-body table--no-card">
                            <!-- Detalhes da Retirada -->
                            <table class="table table--no-card table-responsive table-borderless table-striped table-earning" id="lista">
                                    <thead>
                                        <tr class="active">
                                          <th scope="col">Retirada ID</th>
                                          <th scope="col">Funcionário</th>
                                          <th scope="col">Obra</th>
                                          <th scope="col">Data</th>
                                          <th scope="col">Status</th>
                                          <th width="50%">Detalhes</th>
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
                                      </td>
                                    </tbody>
                            </table>

                            <hr>

                            <?php if(!empty($retirada->items)){ ?>
                            <h3 class="title-1 m-b-25">Itens</h3>
                            <table class="table table--no-card table-responsive table-borderless table-striped table-earning" id="lista2">
                                    <thead>
                                        <tr class="active">
                                            <th>Item Id</th>
                                            <th>Item</th>
                                            <th>Quantidade</th>
                                            <th>Data da Entrega</th>
                                            <th>Data da Devolucao</th>
                                            <th>Situação</th>
                                            <th>Detalhes</th>
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
                                            <td><?php echo isset($item->data_devolucao) ? date("d/m/Y H:i", strtotime($item->data_devolucao)) : '-'; ?></td>
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
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                            </table>
                            <?php } ?>

                           
                            <?php if($retirada->status == 14){?> 
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