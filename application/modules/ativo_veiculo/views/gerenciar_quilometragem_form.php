<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/quilometragem/'.$id_ativo_veiculo); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Quilometragem</h2>
                    <div class="card">
                        <div class="card-header">
                            <?php echo isset($quilometragem) && isset($quilometragem->id_ativo_veiculo_quilometragem) ? "Editar Registro de Quilometragem" : "Novo Registro de Quilometragem" ?>
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_veiculo/quilometragem_salvar'); ?>" method="post" enctype="multipart/form-data">
                                <?php if(isset($id_ativo_veiculo)){?>
                                    <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $id_ativo_veiculo; ?>">
                                <?php } ?>

                                <?php if(isset($quilometragem) && isset($quilometragem->id_ativo_veiculo_quilometragem)) {  ?>
                                    <input type="hidden" id="id_ativo_veiculo_quilometragem" name="id_ativo_veiculo_quilometragem" value="<?php echo $quilometragem->id_ativo_veiculo_quilometragem;?>">
                                <?php } ?>

                                <p style="text-transform: uppercase">
                                    <strong style="color: red;">
                                     <?php echo $veiculo->veiculo; ?> <?php echo $veiculo->veiculo_placa ?: $veiculo->id_interno_maquina; ?>
                                    </strong>
                                </p>
                                <hr>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_km" class=" form-control-label">Quilometragem Atual</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="number" id="veiculo_km" name="veiculo_km" placeholder="000" class="form-control" 
                                        min="<?php echo $veiculo->veiculo_km_atual; ?>"
                                        value="<?php echo $veiculo->veiculo_km_atual; ?>">
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="data" class=" form-control-label">Data</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="datetime-local" id="data" name="data" class="form-control" 
                                        value="<?php echo isset($quilometragem) ? date("Y-m-d\TH:i:s", strtotime($quilometragem->data)) : date("Y-m-d\TH:i:s", strtotime('now')); ?>">
                                    </div>
                                </div>
                              
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url("ativo_veiculo/gerenciar/quilometragem/{$id_ativo_veiculo}");?>">
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

            <?php if (isset($anexos) && isset($operacao)) { ?>
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
