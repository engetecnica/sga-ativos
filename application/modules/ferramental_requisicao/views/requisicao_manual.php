<style type="text/css">
    .texto-historico { font-size: 12px; font-family: Tahoma; padding: 5px !important; }
</style>

<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('ferramental_requisicao'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <form action="<?php echo base_url('ferramental_requisicao/aceite_manual'); ?>" method="post" enctype="multipart/form-data"> 

                        <input type="hidden" name="id_requisicao" id="id_requisicao" value="<?php echo $requisicao_manual->id_requisicao; ?>">
                        <?php
                        #echo "<pre>";
                        #print_r($requisicao_manual);
                        #echo "</pre>";
                        ?>


                        <h2 class="title-1 m-b-25">Detalhar Itens da Requisição</h2>

                        <div class="card">

         
                            <div class="card-body">

                                <!-- Detalhes da Requisição -->
                                <table class="table table-borderless table-striped table-earning">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="10%">Cód. Item</th>
                                            <th scope="col">Item</th>
                                            <th scope="col">Observações</th>
                                            <th scope="col" width="15%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($requisicao_manual->itens as $manual){ ?>
                                        <tr>
                                            <td><?php echo $manual->codigo; ?></td>
                                            <td><?php echo $manual->nome; ?></td>
                                            <td><input type="" class="form-control" name="observacoes[]" id="observacoes[]" placeholder="<?php echo $manual->codigo; ?> - Observações" value="<?php if($manual->observacoes) echo $manual->observacoes; ?>" <?php if($manual->status_item){ ?>disabled="disabled" <?php } ?>></td>

                                            <td>
                                                <input type="hidden" name="id_ativo_externo[]" id="id_ativo_externo[]" value="<?php echo $manual->id_ativo_externo; ?>">
                                                <select class="form-control" name="status[]" id="status[]" <?php if($manual->status_item){ ?>disabled="disabled" <?php } ?>>
                                                    <option value="4" <?php if($manual->status_item && $manual->status_item==4) echo "selected='selected'"; ?>>Recebido</option>
                                                    <option value="5" <?php if($manual->status_item && $manual->status_item==5) echo "selected='selected'"; ?>>Em Operação</option>
                                                    <option value="8" <?php if($manual->status_item && $manual->status_item==8) echo "selected='selected'"; ?>>Com Defeito </option>
                                                    <option value="9" <?php if($manual->status_item && $manual->status_item==9) echo "selected='selected'"; ?>>Devolvido</option>
                                                    <option value="10" <?php if($manual->status_item && $manual->status_item==10) echo "selected='selected'"; ?>>Fora de Operação</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <?php if(!$manual->status_item){ ?>
                                <hr>
                                <div class="text-center">
                                    <p class="text-center" style="padding: 25px;"><b>Atenção:</b> Todos os itens acima descritos foram transferidos e estão sendo recebidos por você. <br>Ao atestar que os itens estão todos funcionando, a responsabilidade é inteiramente sua, por isso,<br> <font color='red'>confira a situação de cada item antes de aceitá-los.</font> </p>
                                    <hr>
                                    <button class="btn btn-danger" type="submit" id="">
                                        <i class="fa fa-check "></i>&nbsp;
                                        Estou de acordo e quero aceitar os itens.
                                    </button>
                                </div>   
                                <?php } ?>                             

                            </div>
                        </div>
                    </form>
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