<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="<?php echo base_url("ativo_veiculo/abastecimento/{$id_ativo_veiculo}"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Abastecimento</h2>
                    <div class="card">
                        <div class="card-header">
                            <?php echo isset($abastecimento) && isset($abastecimento->id_ativo_veiculo_abastecimento) ? "Editar Registro de Abastecimento" : "Novo Registro de Abastecimento" ?>
                        </div>
                        <div class="card-body">
                            <?php 
                                $form_url = base_url("ativo_veiculo/abastecimento/{$id_ativo_veiculo}");
                                $form_url .= !isset($abastecimento) ? "" : "/{$abastecimento->id_ativo_veiculo_abastecimento}"; 
                            ?>

                            <form id="abastecimento_form" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
                                <?php if(isset($id_ativo_veiculo)){?>
                                    <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $id_ativo_veiculo; ?>">
                                <?php } ?>

                                <?php if(isset($abastecimento) && isset($abastecimento->id_ativo_veiculo_abastecimento)) {  ?>
                                    <input type="hidden" id="id_ativo_veiculo_abastecimento" name="id_ativo_veiculo_abastecimento" value="<?php echo $abastecimento->id_ativo_veiculo_abastecimento;?>">
                                <?php } ?>

                                <p style="text-transform: uppercase">
                                    <strong style="color: red;">
                                     <?php echo $veiculo->veiculo; ?> <?php echo $veiculo->veiculo_placa ?: $veiculo->id_interno_maquina; ?>
                                    </strong>
                                </p>
                                <hr>


                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_fornecedor" class=" form-control-label">Fornecedor</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <select required="required" class="form-control select2" id="id_fornecedor" name="id_fornecedor">
                                            <option value="">Selecione um Fornecedor</option>
                                            <?php foreach($fornecedores as $fornecedor){ ?>
                                                <option <?php echo (isset($abastecimento) && isset($abastecimento->id_fornecedor)) && (int) $abastecimento->id_fornecedor === (int) $fornecedor->id_fornecedor ? 'selected="selected"' : '';?>
                                                 value="<?php echo  $fornecedor->id_fornecedor; ?>"><?php echo $fornecedor->razao_social; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>


                                    <div class="col col-md-2">
                                        <label for="combustivel" class=" form-control-label">Combustível</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <select required="required" class="form-control" id="combustivel" name="combustivel" v-model="combustivel">
                                            <option :value="null">Selecione o Tipo do Combustível</option>
                                            <?php foreach($combustiveis as $combustivel){ ?>
                                            <option <?php echo (isset($abastecimento) && isset($abastecimento->combustivel)) && $abastecimento->combustivel == $combustivel->slug ? 'selected="selected"' : '';?>
                                             value="<?php echo $combustivel->slug; ?>"><?php echo $combustivel->nome; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <input type="hidden" id="combustivel_unidade_tipo" name="combustivel_unidade_tipo" v-model="combustivel_unidade_tipo">
                        
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_km" class=" form-control-label">Abastecimento Atual</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="number" id="veiculo_km" name="veiculo_km" placeholder="000" class="form-control" 
                                        min="<?php echo $veiculo->veiculo_km_atual; ?>"
                                        value="<?php echo isset($abastecimento) && isset($abastecimento->veiculo_km) ? $abastecimento->veiculo_km : $veiculo->veiculo_km_atual; ?>">
                                    </div>
                                    
            
                                    <div class="col col-md-2">
                                        <label for="combustivel_unidade_valor" class=" form-control-label">Custo Por Unidade</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="text" id="combustivel_unidade_valor" name="combustivel_unidade_valor" placeholder="0.00" class="form-control valor"  v-model="combustivel_unidade_valor"
                                        value="<?php echo isset($abastecimento) && isset($abastecimento->combustivel_unidade_valor) ? $abastecimento->combustivel_unidade_valor : ''?>">
                                    </div>

                                </div>


                                <div class="row form-group">

                                    <div class="col col-md-2">
                                        <label for="abastecimento_custo" class=" form-control-label">Custo Total</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="text" id="abastecimento_custo" name="abastecimento_custo" placeholder="0.00" class="form-control valor" 
                                        value="<?php echo isset($abastecimento) && isset($abastecimento->abastecimento_custo) ? $abastecimento->abastecimento_custo : ''?>">
                                    </div>
                                
                                    <div class="col col-md-2">
                                        <label for="abastecimento_data" class=" form-control-label">Data</label>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <input required="required" type="date" id="abastecimento_data" name="abastecimento_data" class="form-control" 
                                        value="<?php echo isset($abastecimento) && isset($abastecimento->abastecimento_data) ? date('Y-m-d', strtotime($abastecimento->abastecimento_data)) : date('Y-m-d', strtotime('now'))?>">
                                    </div>
                                </div>
                                
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url("ativo_veiculo/abastecimento/{$id_ativo_veiculo}");?>">
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

            <?php if (isset($anexos) && isset($abastecimento)) { ?>
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
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->


<script>
var abastecimento_form = new Vue({
    el: "#abastecimento_form",
    data(){
        return {
            combustiveis: JSON.parse(`<?php echo json_encode($combustiveis); ?>`)  || [],
            combustivel: `<?php if (isset($abastecimento) && isset($abastecimento->combustivel)) echo $abastecimento->combustivel; ?>` || null,
            combustivel_unidade_tipo: `<?php if (isset($abastecimento) && isset($abastecimento->combustivel_unidade_tipo)) echo $abastecimento->combustivel_unidade_tipo; ?>` || null,
            combustivel_unidade_valor: `<?php if (isset($abastecimento) && isset($abastecimento->combustivel_unidade_valor)) echo $abastecimento->combustivel_unidade_valor; ?>` || null,
        }
    },
    watch: {
        combustivel(){
            if (this.combustivel) {
                let combustivel = this.combustiveis.find(c => c.slug == this.combustivel)
                if (combustivel) {
                    this.combustivel_unidade_valor = combustivel.valor_medio.replace('.',',')
                    this.combustivel_unidade_tipo = combustivel.unidade
                    setTimeout(() => {window.$('.valor').mask('000.000.000.000,00 R$', {reverse: true})}, 100)
                    this.combustivel_unidade_valor += " R$"
                }
            }
        }
    },
})
</script>