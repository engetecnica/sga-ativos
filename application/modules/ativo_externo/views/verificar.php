<!-- MAIN CONTENT-->
<style>
    .btn-contagem {
        width: 50px;
        height: 30px;
        font-weight: bold;
    }
</style>
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('ativo_externo/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Verificação de Ativo Externo</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <?php

                        //print_r($lista);
                        ?>
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th width="30%">Item</th>
                                    <th>Obra</th>
                                    <th width="10%">Uso</th>
                                    <th width="10%">Liberado</th>
                                    <th width="10%">Fora de Operação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr>
                                    <td><?php echo $valor['item']; ?></td>
                                    <td><?php echo $valor['obra']; ?></td>
                                    <td><?php echo $valor['emuso']; ?></td>
                                    <td><?php echo $valor['liberado']; ?></td>
                                    <td><?php echo $valor['foradeoperacao']; ?></td>
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
