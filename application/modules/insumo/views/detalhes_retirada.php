<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($this->permitido($permissoes, 13, 'adicionar')) { ?>
                    <div class="overview-wrap">
                        <a href="<?php echo base_url('insumo/retirada'); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue" style="margin-left: 10px;">
                                <i class="zmdi zmdi-plus"></i>Retiradas
                            </button>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <?php

//                       $this->dd($detalhes);
?>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-20">Detalhes da Retirada</h2>
                    <div class="table table--no-card table-responsive table--no- m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="detalhes_retirada">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuário</th>
                                    <th>Usuário Retirou</th>
                                    <th>Data Retirada</th>
                                    <th>Situação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $detalhes->id_insumo_retirada; ?></td>
                                    <td><?php echo $detalhes->id_usuario; ?></td>
                                    <td><?php echo $detalhes->id_funcionario; ?></td>
                                    <td><?php echo $this->formata_data_hora($detalhes->created_at); ?></td>
                                    <td><span
                                            class="badge badge-<?php echo ($this->get_situacao_insumo($detalhes->status)['class']) ?? '-'; ?>"><?php echo ($this->get_situacao_insumo($detalhes->status)['texto']) ?? '-'; ?></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-20">Itens da Retirada</h2>
                    <div class="table table--no-card table-responsive table--no- m-b-40">

                        <form action="<?php echo base_url('insumo/retirada/salvar_devolucao'); ?>" method="post"
                            enctype="multipart/form-data">

                            <input type="hidden" name="id_insumo_retirada" id="id_insumo_retirada"
                                value="<?php echo $detalhes->id_insumo_retirada; ?>">

                            <table class="table table-borderless table-striped table-earning" id="itens_retirada">
                                <thead>
                                    <tr>
                                        <th>Insumo</th>
                                        <th>Quantidade Retirada</th>
                                        <th>Data Retirada</th>
                                        <th>Devolução</th>
                                        <th>Situação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($detalhes->insumos as $insumo){ ?>
                                    <tr>
                                        <td><?php echo $insumo->id_insumo; ?></td>
                                        <td><?php echo $insumo->quantidade;?></td>
                                        <td><?php echo $this->formata_data_hora($insumo->created_at); ?></td>
                                        <td>
                                        <?php 
                                            if($detalhes->status == 3 or $detalhes->status == 2){
                                                 echo ($insumo->devolucao->quantidade) ?? '-';
                                        } else { ?>
                                            <input type="number" name="item_devolvido[<?php echo $insumo->id;?>]" id=""
                                                max="<?php echo $insumo->quantidade;?>" min="0" class="form-control"
                                                style="width: 100px;" value="0">
                                        <?php } ?>
                                        </td>
                                        <td><span
                                                class="badge badge-<?php echo ($this->get_situacao_insumo($insumo->status)['class']) ?? '-'; ?>"><?php echo ($this->get_situacao_insumo($insumo->status)['texto']) ?? '-'; ?></span>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <hr>
                            <?php if($detalhes->status == 1){ ?>
                            <div class="pull-right m-b-20 m-r-40">
                                <button class="btn btn-primary submit-form">
                                    <i class="fa fa-send "></i>&nbsp;
                                    <span id="submit-form">Registrar Devolução</span>
                                </button>
                                <a href="<?php echo base_url("insumo");?>">
                                    <button class="btn btn-secondary" type="button">
                                        <i class="fa fa-ban "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>
                                </a>
                            </div>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->