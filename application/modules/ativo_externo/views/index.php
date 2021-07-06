<!-- MAIN CONTENT-->
<style>
    .btn-contagem {
        width: 50px;
        height: 30px;
        font-weight: bold;
    }
    .btn-codigo {
        width: 100%;
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
                    <h2 class="title-1 m-b-25">Ativo Externo</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th width="10%">Código</th>
                                    <th>Item</th>
                                    <th>Obra</th>
                                    <th width="10%">Inclusão</th>
                                    <th width="10%">Liberação</th>
                                    <th width="10%">Situação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr>
                                    <td><button type='button' class="btn btn-sm btn-outline-success btn-codigo"><?php echo $valor->codigo; ?></button></td>
                                    <td><?php echo $valor->nome; ?></td>
                                    <td><?php echo $valor->codigo_obra." - ".$valor->endereco; ?></td>
                                    <td><?php echo date("d/m/Y H:i", strtotime($valor->data_inclusao)); ?></td>
                                    <td><?php if($valor->data_liberacao=="0000-00-00 00:00:00"){ echo "-"; } else { echo date("d/m/Y H:i", strtotime($valor->data_liberacao)); } ?></td>
                                    <td><button class="btn btn-info btn-sm">Estoque</button></td>
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


