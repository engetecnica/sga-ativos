<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/ipva/'.$id_ativo_veiculo); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar IPVA</h2>

                    <div class="card">
                        <div class="card-header">
                            <?php echo isset($ipva) && isset($ipva->id_ativo_veiculo_ipva) ? "Editar Registro de IPVA" : "Novo Registro de IPVA" ?>
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_veiculo/ipva_salvar'); ?>" method="post" enctype="multipart/form-data">
                                <?php if(isset($id_ativo_veiculo)){?>
                                    <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $id_ativo_veiculo; ?>">
                                <?php } ?>

                                <?php if(isset($ipva) && isset($ipva->id_ativo_veiculo_ipva)){?>
                                    <input type="hidden" name="id_ativo_veiculo_ipva" id="id_ativo_veiculo_ipva" value="<?php echo $ipva->id_ativo_veiculo_ipva; ?>">
                                <?php } ?>

                                <p style="text-transform: uppercase">
                                    <strong style="color: red;">
                                     <?php echo $veiculo->veiculo; ?> <?php echo $veiculo->veiculo_placa ?: $veiculo->id_interno_maquina; ?>
                                    </strong>
                                </p>
                                <hr>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="ipva_ano" class=" form-control-label">Referência</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select required="required" class="form-control select2" id="ipva_ano" name="ipva_ano" value="<?php echo isset($ipva) && isset($ipva->ipva_ano) ? $ipva->ipva_ano : '' ?>">
                                            <option value="">Ano</option>

                                            <?php if (isset($ipva) && isset($ipva->ipva_ano)) {?>
                                                <option  selected="selected" value="<?php echo  $ipva->ipva_ano; ?>"><?php echo $ipva->ipva_ano; ?></option>
                                            <?php } ?>

                                            <?php 
                                                for($ano = date("Y") + 1; $ano >= date("Y") - 10; $ano--){ 
                                                if ($this->ativo_veiculo_model->permit_add_ipva($veiculo->id_ativo_veiculo, $ano)) {
                                            ?>
                                                <option <?php echo isset($ipva) && isset($ipva->ipva_ano) && $ipva->ipva_ano == $ano  ? 'selected="selected"' : '';?> value="<?php echo $ano; ?>"><?php echo $ano; ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>   
                                
                                    <div class="col col-md-2">
                                        <label for="ipva_custo" class=" form-control-label">Custo</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input required="required" type="text" id="ipva_custo" name="ipva_custo" placeholder="0.00" class="form-control valor" 
                                        value="<?php echo isset($ipva) && isset($ipva->ipva_custo) ? $ipva->ipva_custo : ''?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="ipva_data_pagamento" class=" form-control-label">Pagamento</label>
                                    </div>                                    

                                    <div class="col-12 col-md-4">
                                        <input required="required" type="date" id="ipva_data_pagamento" name="ipva_data_pagamento" class="form-control" 
                                        value="<?php echo isset($ipva) && isset($ipva->ipva_data_pagamento) ? date("Y-m-d", strtotime($ipva->ipva_data_pagamento)) : ''?>">
                                    </div>
                            
                                
                                    <div class="col col-md-2">
                                        <label for="ipva_data_vencimento" class=" form-control-label">Vencimento</label>
                                    </div>        
                                    <div class="col-12 col-md-4">
                                        <input required="required" type="date" id="ipva_data_vencimento" name="ipva_data_vencimento" class="form-control" 
                                        value="<?php echo isset($ipva) && isset($ipva->ipva_data_vencimento) ? date("Y-m-d", strtotime($ipva->ipva_data_vencimento)) : ''?>">
                                    </div>
                                </div>
                                
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url("ativo_veiculo/gerenciar/ipva/{$id_ativo_veiculo}");?>">
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

            <?php if (isset($anexos) && isset($ipva)) { ?>
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
