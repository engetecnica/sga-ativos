<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/operacao/'.$id_ativo_veiculo); ?>">
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
                            <?php echo isset($operacao) && isset($operacao->id_ativo_veiculo_operacao) ? "Editar Registro de Operação do Veículo" : "Novo Registro de Operação do Veículo" ?>
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_veiculo/operacao_salvar'); ?>" method="post" enctype="multipart/form-data">
                                <?php if(isset($id_ativo_veiculo)){?>
                                    <input type="hidden" name="id_ativo_veiculo" id="id_ativo_veiculo" value="<?php echo $id_ativo_veiculo; ?>">
                                <?php } ?>

                                <?php if(isset($operacao) && isset($operacao->id_ativo_veiculo_operacao)) {  ?>
                                    <input type="hidden" id="id_ativo_veiculo_operacao" name="id_ativo_veiculo_operacao" value="<?php echo $operacao->id_ativo_veiculo_operacao;?>">
                                <?php } ?>

                                <p style="text-transform: uppercase"><strong><font color="red"><?php echo $dados_veiculo->veiculo; ?> <?php echo $dados_veiculo->veiculo_placa; ?></font></strong></p>
                                <hr>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="operacao_tempo" class=" form-control-label">Tempo de Operação</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input type="text" id="operacao_tempo" name="operacao_tempo" placeholder="000" class="form-control horas" 
                                        min="1"
                                        value="<?php echo isset($operacao) && isset($operacao->operacao_tempo) ? $operacao->operacao_tempo : 0; ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="operacao_periodo_fim" class=" form-control-label">Período Início</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="date" id="operacao_periodo_inicio" name="operacao_periodo_inicio" placeholder="00/00/000" class="form-control" 
                                        value="<?php echo isset($operacao) && isset($operacao->operacao_periodo_inicio) ? date('Y-m-d', strtotime($operacao->operacao_periodo_inicio)) : ''; ?>">
                                    </div>

                                    <div class="col-12 col-md-1">
                                        <input type="text" id="operacao_periodo_inicio_hora" name="operacao_periodo_inicio_hora" placeholder="00:00:00" class="form-control hora" 
                                        value="<?php echo isset($operacao) && isset($operacao->operacao_periodo_inicio) ? date('H:i:s', strtotime($operacao->operacao_periodo_inicio)) : ''; ?>">
                                    </div>


                                    <div class="col col-md-2">
                                        <label for="operacao_periodo_fim" class=" form-control-label">Período Fim</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="date" id="operacao_periodo_fim" name="operacao_periodo_fim" placeholder="00/00/000" class="form-control" 
                                        value="<?php echo isset($operacao) && isset($operacao->operacao_periodo_fim) ? date('Y-m-d', strtotime($operacao->operacao_periodo_fim)) : ''; ?>">
                                    </div>

                                    <div class="col-12 col-md-1">
                                        <input type="text" id="operacao_periodo_fim_hora" name="operacao_periodo_fim_hora" placeholder="00:00:00" class="form-control hora" 
                                        value="<?php echo isset($operacao) && isset($operacao->operacao_periodo_fim) ? date('H:i:s', strtotime($operacao->operacao_periodo_fim)) : ''; ?>">
                                    </div>
                                    
                                </div>

                
                              
                                <?
                                /*
                                    $this->load->view('gerenciar_anexo', [
                                        'label' => "Comprovante Fiscal",
                                        'item' => isset($operacao) ? $operacao : null,
                                        'anexo' => "comprovante_fiscal",
                                        'controller' => 'ativo_veiculo',
                                        'tabela' => 'ativo_veiculo_operacao',
                                        'id_item' => isset($operacao) ? $operacao->id_ativo_veiculo_operacao : null,
                                        'redirect' => isset($operacao) ?  "ativo_veiculo/gerenciar/operacao/editar/{$id_ativo_veiculo}/{$operacao->id_ativo_veiculo_operacao}" : ""
                                    ]);
                                */
                                ?> 
                                
                                <small>Tempo de Operação ou Período devem ser obrigatoriamente definido, um ou ambos. 
                                    Se somente o período for definido, será calculado de acordo com o tempo entre início e fim.</small>
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url("ativo_veiculo/gerenciar/operacao/{$id_ativo_veiculo}");?>">
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
