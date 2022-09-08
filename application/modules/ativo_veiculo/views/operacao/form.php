<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="<?php echo base_url('ativo_veiculo/operacao/'.$id_ativo_veiculo); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Tempo de Operação do Veículo</h2>
                    <div class="card">
                        <div class="card-header">
                            <?php echo isset($operacao) && isset($operacao->id_ativo_veiculo_operacao) ? "Editar Registro de Operação do Veículo" : "Novo Registro de Operação do Veículo"?>
                        </div>
                        <div class="card-body">
                            <?php 
                                $form_url = base_url("ativo_veiculo/operacao/{$id_ativo_veiculo}");
                                $form_url .= !isset($operacao) ? "" : "/{$operacao->id_ativo_veiculo_operacao}"; 
                            ?>

                            <form action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
                                <?php if(isset($id_ativo_veiculo)){?>
                                    <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $id_ativo_veiculo; ?>">
                                <?php } ?>

                                <?php if(isset($operacao) && isset($operacao->id_ativo_veiculo_operacao)) {  ?>
                                    <input type="hidden" id="id_ativo_veiculo_operacao" name="id_ativo_veiculo_operacao" value="<?php echo $operacao->id_ativo_veiculo_operacao;?>">
                                <?php } ?>

                                <p style="text-transform: uppercase">
                                    <strong style="color: red;">
                                     <?php echo $veiculo->veiculo; ?> <?php echo $veiculo->veiculo_placa ?: $veiculo->id_interno_maquina; ?>
                                    </strong>
                                </p>
                                <hr>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_horimetro" class=" form-control-label">Horimetro Atual</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="number" id="veiculo_horimetro" name="veiculo_horimetro" placeholder="00000" class="form-control" 
                                        min="<?php echo $operacao->veiculo_horimetro ?? $veiculo->veiculo_horimetro_atual; ?>"
                                        value="<?php echo $operacao->veiculo_horimetro ?? $veiculo->veiculo_horimetro_atual; ?>">
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="data" class=" form-control-label">Data</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="datetime-local" id="data" name="data" class="form-control" 
                                        value="<?php echo isset($operacao) ? date("Y-m-d\TH:i:s", strtotime($operacao->data)) : date("Y-m-d\TH:i:s", strtotime('now')); ?>">
                                    </div>
                                </div>
                              
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url("ativo_veiculo/operacao/{$id_ativo_veiculo}");?>">
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
        </div>
    </div>
</div>
<?php  if(isset($anexos) && isset($operacao)) $this->load->view('anexo/index_form_modal', ["show_header" => false]); ?>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
