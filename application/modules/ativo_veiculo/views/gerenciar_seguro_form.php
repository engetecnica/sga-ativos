<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/seguro/'.$id_ativo_veiculo); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Seguro do Veículo</h2>

                    <div class="card">
                        <div class="card-header">
                            <?php echo isset($seguro) && isset($seguro->id_ativo_veiculo_seguro) ? "Editar Registro de Seguro" : "Novo Registro de Seguro" ?>
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_veiculo/seguro_salvar'); ?>" method="post" enctype="multipart/form-data">
                                <?php if(isset($id_ativo_veiculo)){?>
                                    <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $id_ativo_veiculo; ?>">
                                <?php } ?>

                                <?php if(isset($seguro) && isset($seguro->id_ativo_veiculo_seguro)){?>
                                    <input type="hidden" name="id_ativo_veiculo_seguro" id="id_ativo_veiculo_seguro" value="<?php echo $seguro->id_ativo_veiculo_seguro; ?>">
                                <?php } ?>

                                <p style="text-transform: uppercase">
                                    <strong style="color: red;">
                                     <?php echo $veiculo->veiculo; ?> <?php echo $veiculo->veiculo_placa ?: $veiculo->id_interno_maquina; ?>
                                    </strong>
                                </p>
                                <hr>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="carencia_inicio" class=" form-control-label">Carência Inicio</label>
                                    </div>                                    

                                    <div class="col-12 col-md-4">
                                        <input required="required" type="date" id="carencia_inicio" name="carencia_inicio" class="form-control" 
                                        value="<?php echo  isset($seguro) && isset($seguro->carencia_inicio) ? date('Y-m-d', strtotime($seguro->carencia_inicio)) : date("Y-m-d"); ?>">
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="carencia_fim" class=" form-control-label">Carência Final</label>
                                    </div>                                    

                                    <div class="col-12 col-md-4">
                                        <input required="required" type="date" id="carencia_fim" name="carencia_fim" class="form-control" 
                                        value="<?php echo isset($seguro) && isset($seguro->carencia_fim) ? date('Y-m-d', strtotime($seguro->carencia_fim)) : '';?>">
                                    </div> 
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="seguro_ano" class=" form-control-label">Custo</label>
                                    </div>
        
                                    <div class="col-12 col-md-4">
                                        <input required="required" type="text" id="seguro_custo" name="seguro_custo" placeholder="0.00" class="form-control valor" 
                                        value="<?php echo isset($seguro) && isset($seguro->seguro_custo) ? $seguro->seguro_custo : '';?>">
                                    </div>
                                </div>

                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url("ativo_veiculo/gerenciar/seguro/{$id_ativo_veiculo}");?>">
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

            <?php if (isset($anexos) && isset($seguro)) { ?>
                <div id="anexos" class="row">
                    <div class="col-12">
                        <?php $this->load->view('anexo/index', ['show_header' => false, 'permit_delete' => true]); ?>
                    </div>
                </div>
            <?php } ?>

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
<?php $this->load->view('anexo/index_form_modal', ["show_header" => false]); ?>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
