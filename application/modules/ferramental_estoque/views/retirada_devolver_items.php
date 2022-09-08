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
                    <form action="<?php echo base_url('ferramental_estoque/devolver_items_retirada_salvar'); ?>" method="post" enctype="multipart/form-data"
                        class="confirm-submit"
                        data-acao="Devolver" data-icon="success" data-message="false"
                        data-title="Devolver Itens" data-redirect="true"
                        data-text="Clique 'Sim, Devolver!' para confirmar a devolução dos itens retirados."
                    > 
                        <h2 class="title-1 m-b-25">Devolver Itens da Retirada</h2>
                        <div class="card">
                            <input type="hidden" name="id_retirada" value="<?php echo $retirada->id_retirada; ?>">
                            <input type="hidden" name="id_obra" value="<?php echo $retirada->id_obra; ?>">
                            <input type="hidden" name="id_funcionario" value="<?php echo $retirada->id_funcionario; ?>">

                            <div class="card-body">

                                <!-- Detalhes da Retirada -->
                                <table class="table table--no-card table-responsive-md table-borderless table-striped table-earning">
                                    <thead>
                                        <tr class="active">
                                          <th scope="col" width="50%">Item ID</th>
                                          <th scope="col" width="50%">Item</th>
                                          <th scope="col" width="50%">Quantidade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php foreach($items as $i => $item){ ?>
                                        <input type="hidden" name="id_retirada_item[]" id="id_retirada_item[]" value="<?php echo $item->id_retirada_item; ?>">
                                      <tr>
                                        <td><?php echo $item->id_retirada_item; ?></td>
                                        <td><?php echo $item->nome; ?></td>
                                        <td><?php echo $item->quantidade; ?></td>
                                      </tr>
                                      <?php } ?>
                                    </tbody>
                                </table>
                                <hr>


                                <table class="table table--no-card table-responsive-md table-borderless table-striped table-earning">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="10%">Ativo ID</th>
                                            <th scope="col" width="10%">Código</th>
                                            <th scope="col" width="40%">Nome</th>
                                            <th scope="col" width="20%">Situação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($ativos as $ativo){ ?>
                                        <tr>
                                            <td><?php echo $ativo->id_ativo_externo; ?></td>
                                            <td><?php echo $ativo->codigo; ?></td>
                                            <td><?php echo $ativo->nome; ?></td>
                                            <td>
                                                <input type="hidden" name="id_ativo_externo_<?php echo $ativo->id_retirada_item; ?>[]" id="id_ativo_externo_<?php echo $ativo->id_retirada_item; ?>[]" value="<?php echo $ativo->id_ativo_externo; ?>">
                                                <input type="hidden" name="id_retirada_ativo_<?php echo $ativo->id_retirada_item; ?>[]" id="id_retirada_ativo_<?php echo $ativo->id_retirada_item; ?>[]" value="<?php echo $ativo->id_retirada_ativo; ?>">
                                                <select 
                                                    <?php echo (isset($no_aceite) && $no_aceite == true )? 'disabled' : ''; ?>
                                                    class="form-control" name="status_<?php echo $ativo->id_retirada_item; ?>[]" id="status_<?php echo $ativo->id_retirada_item; ?>[]" required="required"
                                                >
                                                <option readonly="readonly" value="" <?php if($ativo->status && $ativo->status==4) echo "selected='selected'"; ?>>Recebido</option>
                                                    <option value="9" <?php if($ativo->status && $ativo->status==9) echo "selected='selected'"; ?>>Devolvido</option>
                                                    <option value="8" <?php if($ativo->status && $ativo->status==8) echo "selected='selected'"; ?>>Devolvido (Com Defeito)</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php if ($retirada->status == 4) {?>
                          <div class="text-center">
                            <hr><button class="btn btn-primary" type="submit"><i class="fas fa-undo 4x"></i>&nbsp;Devolver Itens</button>
                          </div>
                        <?php  } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->