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

                                <p style="text-transform: uppercase"><strong><font color="red"><?php echo $dados_veiculo->veiculo; ?> <?php echo $dados_veiculo->veiculo_placa; ?></font></strong></p>
                                <hr>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_km" class=" form-control-label">Quilometragem Atual</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="number" id="veiculo_km" name="veiculo_km" placeholder="000" class="form-control" 
                                        min="<?php echo $dados_veiculo->veiculo_km + 1; ?>"
                                        value="<?php echo isset($quilometragem) && isset($quilometragem->veiculo_km) ? $quilometragem->veiculo_km : $dados_veiculo->veiculo_km + 1; ?>">
                                    </div>
                                    
                                    <div class="col col-md-2">
                                        <label for="veiculo_litros" class=" form-control-label">Quant. em Litros</label>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <input type="text" id="veiculo_litros" name="veiculo_litros" placeholder="2,2L" class="form-control litros" 
                                        value="<?php echo isset($quilometragem) && isset($quilometragem->veiculo_litros) ? (int) $quilometragem->veiculo_litros : ''?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_custo" class=" form-control-label">Custo</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input type="text" id="veiculo_custo" name="veiculo_custo" placeholder="0.00" class="form-control valor" 
                                        value="<?php echo isset($quilometragem) && isset($quilometragem->veiculo_custo) ? $quilometragem->veiculo_custo : ''?>">
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="veiculo_km_data" class=" form-control-label">Data</label>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <input required="required" type="date" id="veiculo_km_data" name="veiculo_km_data" class="form-control" 
                                        value="<?php echo isset($quilometragem) && isset($quilometragem->veiculo_km_data) ? date('Y-m-d', strtotime($quilometragem->veiculo_km_data)) : date('Y-m-d', strtotime('now'))?>">
                                    </div>
                                </div>
                              
                                <?php
                                    $this->load->view('gerenciar_anexo', [
                                        'label' => "Comprovante Fiscal",
                                        'item' => isset($quilometragem) ? $quilometragem : null,
                                        'anexo' => "comprovante_fiscal",
                                        'controller' => 'ativo_veiculo',
                                        'tabela' => 'ativo_veiculo_quilometragem',
                                        'id_item' => isset($quilometragem) ? $quilometragem->id_ativo_veiculo_quilometragem : null,
                                        'redirect' => isset($quilometragem) ?  "ativo_veiculo/gerenciar/quilometragem/editar/{$id_ativo_veiculo}/{$quilometragem->id_ativo_veiculo_quilometragem}" : ""
                                    ]);
                                ?>
                                
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
