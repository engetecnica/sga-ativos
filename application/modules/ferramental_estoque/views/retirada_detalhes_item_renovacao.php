<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url("ferramental_estoque/detalhes/{$retirada->id_retirada}"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>Voltar a Retirada</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <form action="<?php echo base_url('ferramental_estoque/salvar_renovacao'); ?>" method="post" enctype="multipart/form-data"> 
                        <h2 class="title-1 m-b-25">Renovar Retirada</h2>




                        <div class="card ">


                        

                            <input type="hidden" name="id_retirada" value="<?php echo $retirada->id_retirada; ?>">
                            <input type="hidden" name="id_obra" value="<?php echo $retirada->id_obra; ?>">
                            <div class="card-body">


                            <table class="table table-responsive-md table-striped table-bordered">
                                    <tr>
                                        <th>ID</th>
                                        <th>Funcionário</th>
                                        <th>Obra</th>
                                        <th>Data</th>
                                        <th>Devolução Prevista</th>
                                        <th>Status</th>
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
                                    </tr>
                                </table>
                                <hr>                            

                                <?php 
                                foreach($retirada->items as $item){ 
                                ?>

                                <!-- Detalhes da Retirada -->
                                <table class="table table--no-card table-responsive-md table-borderless table-striped table-earning">
                                    <thead>
                                        <tr class="active">
                                          <th scope="col" width="15%">Item ID</th>
                                          <th scope="col" width="30%">Item</th>
                                          <th scope="col" width="15%">Quantidade</th>
                                          <th scope="col" width="5%">Data Retirada</th>
                                          <th scope="col" width="5%">Data Devolução</th>
                                          <th scope="col" width="10%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td><?php echo $item->id_retirada_item; ?></td>
                                        <td><?php echo $item->nome; ?></td>
                                        <td><?php echo $item->quantidade; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <?php $status = $this->status($item->status); ?>
                                            <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                        </td>
                                      </tr>
                                      <tr style='border-bottom:2px solid #666'>
                                        <th>Itens devolvidos</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>Renovar</th>
                                      </tr>
                                      <?php foreach($item->ativos as $ativo){ ?>
                                      <tr>
                                        <td></td>
                                        <td><?php echo $ativo->nome; ?></td>
                                        <td>1</td>
                                        <td><?php echo $this->formata_data_hora($ativo->data_retirada); ?></td>
                                        <td><?php echo $this->formata_data_hora($ativo->data_devolucao); ?></td>
                                        <td>
                                        <label class="customcheck">
                                            <input type="checkbox" name="renovar[]" id="renovar[]" value="<?php echo $ativo->id_retirada_item; ?>">
                                            <span class="checkmark"></span>
                                        </label>
                                        </td>
                                      </tr>
                                      <?php } ?>


                                    </tbody>
                                </table>
                                <hr>
                                <?php } ?>

                                <table class="table table--no-card table-responsive-md table-borderless ">

                                    <tr>
                                        <td style="width:80%; text-align: right"><label>Prazo:</label></td>
                                        <td><input type="datetime-local" class="form-control" id="data_entrega" name="data_entrega" required></td>
                                    </tr>                                
                                </table>

                                        <hr>
                                <div class="pull-right">
                                    <button class="btn btn-primary">Salvar Renovação</button>
                                </div>

                               
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->