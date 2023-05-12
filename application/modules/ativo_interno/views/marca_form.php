<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <?php $id = isset($marca_detalhe) && isset($marca_detalhe->id_ativo_interno_marca) ? "#" . $marca_detalhe->id_ativo_interno_marca : ''; ?>
                        <a href="<?php echo base_url("ativo_interno/marca"); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Ativo Interno - Marca</h2>

                    <div class="card">
                        <div class="card-header">
                            <?php echo isset($marca_detalhe) && isset($marca_detalhe->id_ativo_interno_marca) ? 'Editar Marca' : 'Nova Marca' ?>
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_interno/marca_salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if (isset($marca_detalhe) && isset($marca_detalhe->id_ativo_interno_marca)) { ?>
                                    <input type="hidden" name="id_ativo_interno_marca" value="<?php echo $marca_detalhe->id_ativo_interno_marca; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="titulo" class=" form-control-label">Marca do Ativo Interno</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input required="required" type="text" id="titulo" name="titulo" placeholder="Digite a Marca" class="form-control" value="<?php if (isset($marca_detalhe) && isset($marca_detalhe->titulo)) {
                                                                                                                                                                        echo $marca_detalhe->titulo;
                                                                                                                                                                    } ?>" autofocus>
                                    </div>
                                </div>

                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>

                                    <a href="<?php echo base_url("ativo_interno/marca"); ?>">
                                        <button class="btn btn-secondary" type="button">
                                            <i class="fa fa-ban "></i>&nbsp;
                                            <span id="cancelar-form">Cancelar</span>
                                        </button>
                                    </a>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
</div>

<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->