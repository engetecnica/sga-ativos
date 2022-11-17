<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($this->permitido($permissoes, 13, 'adicionar')) { ?>
                        <div class="overview-wrap"> 
                            <a href="<?php echo base_url('insumo/adicionar'); ?>">
                                <button class="au-btn au-btn-icon au-btn--blue2">
                                    <i class="zmdi zmdi-plus"></i>Novo Insumo
                                </button>
                            </a>
                         
                            <a href="<?php echo base_url('insumo/retirada'); ?>">
                                <button class="au-btn au-btn-icon au-btn--blue" style="margin-left: 10px;">
                                    <i class="zmdi zmdi-plus"></i>Nova Retirada
                                </button>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-20">Insumos</h2>
                    <div class="table table--no-card table-responsive table--no- m-b-40">
                        
                        <table 
                            class="table table-borderless table-striped table-earning" 
                            id="insumos"
                        ></table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->