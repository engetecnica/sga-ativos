<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/manutencao/'.$id_ativo_veiculo); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Veículo</h2>

                    <div class="card">
                        <div class="card-header">Registrar items do veículo</div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_veiculo/manutencao_salvar'); ?>" method="post" enctype="multipart/form-data">
                                
                                <p><strong>CONTROLE DE MANUTENÇÕES DO VEÍCULO</strong></p>
                                <hr>
                                <p style="text-transform: uppercase"><strong><font color="red"><?php echo $dados_veiculo->veiculo; ?> <?php echo $dados_veiculo->veiculo_placa; ?></font></strong></p>
                                <hr>


                                <?php if(isset($id_ativo_veiculo)){?>
                                <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $id_ativo_veiculo; ?>">
                                <?php } ?>

                                <?php if(isset($manutencao) && isset($manutencao->id_ativo_veiculo_manutencao)){?>
                                <input type="hidden" name="id_ativo_veiculo_manutencao" id="id_ativo_veiculo_manutencao" value="<?php echo $manutencao->id_ativo_veiculo_manutencao; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_fornecedor" class=" form-control-label">Fornecedor</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select required="required" class="form-control" id="id_fornecedor" name="id_fornecedor">
                                            <option value="">Selecione um Fornecedor</option>
                                            <?php foreach($fornecedores as $fornecedor){ ?>
                                                <option <?php echo (isset($manutencao) && isset($manutencao->id_fornecedor)) && (int) $manutencao->id_fornecedor === (int) $fornecedor->id_fornecedor ? 'selected="selected"' : '';?>
                                                 value="<?php echo  $fornecedor->id_fornecedor; ?>"><?php echo $fornecedor->razao_social; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                             
                                    <div class="col col-md-1">
                                        <label for="id_ativo_configuracao" class=" form-control-label">Serviço</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select required="required" class="form-control" id="id_ativo_configuracao" name="id_ativo_configuracao">
                                            <option  alue="">Selecione o Tipo do Serviço</option>
                                            <?php foreach($tipo_servico as $servico){ ?>
                                            <option <?php echo (isset($manutencao) && isset($manutencao->id_ativo_configuracao)) && $manutencao->id_ativo_configuracao == $servico->id_ativo_configuracao ? 'selected="selected"' : '';?>
                                             value="<?php echo $servico->id_ativo_configuracao; ?>"><?php echo $servico->titulo; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                 </div>


                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_km_atual" class=" form-control-label">Quilometragem</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="number" id="veiculo_km_atual" name="veiculo_km_atual" placeholder="KM Atual" class="form-control" 
                                            value="<?php echo (int) $dados_veiculo->veiculo_km; ?>" min="<?php echo (int) $dados_veiculo->veiculo_km; ?>">
                                    </div> 


                                    <div class="col col-md-1">
                                        <label for="veiculo_custo" class=" form-control-label">Custo</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input required="required" type="text" id="veiculo_custo" name="veiculo_custo" placeholder="0.00" class="form-control valor" value="<?php echo isset($manutencao) ? $manutencao->veiculo_custo : '0,00'?>">
                                    </div>
                                </div>
                             

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="data_entrada" class=" form-control-label">Data Serviço</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="date" id="data_entrada" name="data_entrada" class="form-control" 
                                        value="<?php echo isset($manutencao) && isset($manutencao->data_entrada) ? date('Y-m-d', strtotime($manutencao->data_entrada)) : ''?>">
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="data_vencimento" class=" form-control-label">Data Vencimento</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input type="date" id="data_vencimento" name="data_vencimento" class="form-control" 
                                        value="<?php echo isset($manutencao) && isset($manutencao->data_vencimento) ? date('Y-m-d', strtotime($manutencao->data_vencimento)) : ''?>">
                                    </div>
                                </div>

                              
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="descricao" class=" form-control-label">Descrição</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <textarea rows="5" class="form-control" id="descricao" name="descricao"></textarea>
                                    </div>
                                </div>
                                
                                <?php 
                                    $this->load->view('gerenciar_anexo', [
                                        'label' => "Ordem de Serviço",
                                        'item' => isset($manutencao) ? $manutencao : null,
                                        'anexo' => "ordem_de_servico",
                                    ]);
                                ?>
                                
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url('ativo_veiculo/gerenciar/manutencao/'.$id_ativo_veiculo);?>">
                                    <button class="btn btn-info" type="button">                                                    
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
