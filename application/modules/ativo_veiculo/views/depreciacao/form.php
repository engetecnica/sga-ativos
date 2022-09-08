<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="<?php echo base_url("ativo_veiculo/depreciacao/{$id_ativo_veiculo}"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Registro de Depreciação</h2>
                    <div class="card">
                        <div class="card-header">
                            <?php echo isset($depreciacao) && isset($depreciacao->id_ativo_veiculo_depreciacao) ? "Editar Registro de Depreciação" : "Novo Registro de Depreciação" ?>
                        </div>
                        <div class="card-body">
                            <?php
                                $form_url = base_url("ativo_veiculo/depreciacao/{$id_ativo_veiculo}");
                                $form_url .= !isset($depreciacao) ? "" : "/{$depreciacao->id_ativo_veiculo_depreciacao}"; 
                            ?>

                            <form action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
                                <?php if(isset($id_ativo_veiculo)){?>
                                <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $id_ativo_veiculo; ?>">
                                <?php } ?>

                                <?php if(isset($depreciacao) && isset($depreciacao->fipe_valor)){?>
                                    <input type="hidden" name="fipe_valor" id="fipe_valor" value="<?php echo $depreciacao->fipe_valor; ?>">
                                <?php } ?>

                                <?php if(isset($depreciacao) && isset($depreciacao->fipe_mes_referencia)){?>
                                    <input type="hidden" name="fipe_mes_referencia" id="fipe_mes_referencia" value="<?php echo $depreciacao->fipe_mes_referencia; ?>">
                                <?php } ?>

                                <?php if(isset($depreciacao) && isset($depreciacao->fipe_ano_referencia)){?>
                                    <input type="hidden" name="fipe_ano_referencia" id="fipe_ano_referencia" value="<?php echo $depreciacao->fipe_ano_referencia; ?>">
                                <?php } ?>

                                <?php if(isset($depreciacao) && isset($depreciacao->id_ativo_veiculo_depreciacao)){?>
                                    <input type="hidden" name="id_ativo_veiculo_depreciacao" id="id_ativo_veiculo_depreciacao" value="<?php echo $depreciacao->id_ativo_veiculo_depreciacao; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="fipe_valor" class=" form-control-label">Valor de Atual do Bem</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input required="required" class="form-control valor" placeholder="0,00 R$" value="<?php echo $depreciacao->fipe_valor ?? $veiculo->veiculo_valor_atual; ?>"  id="fipe_valor" name="fipe_valor">
                                    </div>

                                    <div class="col-12 col-md-1">
                                        <label for="fipe_mes_referencia" class=" form-control-label">Referência</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <select 
                                            required="required" type="number" id="fipe_mes_referencia" name="fipe_mes_referencia" class="form-control"  
                                            value="1"
                                        >
                                            <option value="">Selecione Um Mês</option>
                                            <?php 
                                                foreach($meses_ano as $mes) {
                                                $selected = 
                                                (isset($depreciacao) && isset($depreciacao->fipe_mes_referencia)) && 
                                                $depreciacao->fipe_mes_referencia == $mes['id'];
                                            ?>
                                                <option <?php if($selected) echo "selected"; ?> value="<?php echo $mes['id']; ?>"><?php echo $mes['nome']; ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input required="required" type="number" id="fipe_ano_referencia" name="fipe_ano_referencia" placeholder="2022" class="form-control" 
                                        min="<?php echo (int) date('Y') - 5; ?>" max="<?php echo (int) date('Y'); ?>" 
                                        value="<?php echo isset($depreciacao) && isset($depreciacao->fipe_mes_referencia) ? $depreciacao->fipe_ano_referencia : 2022; ?>"
                                        >
                                    </div>
                                </div>
                               
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url("ativo_veiculo/depreciacao/{$id_ativo_veiculo}");?>">
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

            <?php if (isset($anexos) && isset($depreciacao)) { ?>
                <div id="anexos" class="row">
                    <div class="col-12">
                        <?php $this->load->view('anexo/index', ['show_header' => false, 'permit_delete' => true]); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php $this->load->view('anexo/index_form_modal', ["show_header" => false]); ?>