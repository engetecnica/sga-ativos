<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo isset($ativo) ? base_url("ativo_externo/manutencao/{$ativo->id_ativo_externo}") : ''; ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>Voltar ao Histórico</button></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25"><?php echo $ativo->nome ." - ".$ativo->codigo; ?></h2>

                    <div class="card">
                      <div class="card-header">
                          <?php 
                            if(isset($manutencao) && isset($manutencao->id_manutencao)){
                              echo  'Editar Registro de manutenção';
                            } else {
                              echo  'Novo Registro de manutenção';
                            } 
                          ?>
                        </div>
                        <div class="card-body">
                            <?php $no_permit_edit = isset($manutencao) && !$this->ativo_externo_model->permit_edit_manutencao($ativo->id_ativo_externo, $manutencao->id_manutencao); ?>

                            <form action="<?php echo base_url("ativo_externo/manutencao_salvar"); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($ativo) && isset($ativo->id_ativo_externo)){?>
                                  <input type="hidden" name="id_ativo_externo" id="id_ativo_externo" value="<?php echo $ativo->id_ativo_externo; ?>" <?php if ($no_permit_edit) echo "readonly"; ?> >
                                <?php } ?>

                                <?php if(isset($manutencao) && isset($manutencao->id_manutencao)){?>
                                  <input type="hidden" name="id_manutencao" id="id_manutencao" value="<?php echo $manutencao->id_manutencao; ?>" <?php if ($no_permit_edit) echo "readonly"; ?>>
                                <?php } ?>
                                
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="data_saida" class=" form-control-label">Data Saída</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input 
                                        <?php echo isset($manutencao) && isset($manutencao->id_manutencao) ? 'readonly' : ''?> 
                                        required="required" type="date" id="data_saida" name="data_saida" placeholder="Nome do Ativo" class="form-control" 
                                        value="<?php echo isset($manutencao) ? date('Y-m-d', strtotime($manutencao->data_saida)) : ''; ?>"
                                        <?php if ($no_permit_edit) echo "readonly"; ?> >
                                    </div>
                                <?php if(!isset($manutencao) || !isset($manutencao->id_manutencao)){?>
                                </div>
                                <?php } ?>

                                <?php if(isset($manutencao) && isset($manutencao->id_manutencao)){?>
                                    <div class="col col-md-2">
                                        <label for="data_retorno" class=" form-control-label">Data Retorno</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input 
                                          required="required" type="date" id="data_retorno" name="data_retorno" placeholder="Nome do Ativo" class="form-control" 
                                          value="<?php echo isset($manutencao->data_retorno) ? date('Y-m-d', strtotime($manutencao->data_retorno)) : ''; ?>"
                                          <?php if ($no_permit_edit) echo "readonly"; ?>
                                        >
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="valor" class=" form-control-label">Valor Atribuído</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input required="required"  type="text" id="valor" 
                                          name="valor" placeholder="0.00" class="form-control valor" 
                                          value="<?php if(isset($manutencao) && isset($manutencao->valor)){ echo $this->formata_moeda($manutencao->valor); }?>"
                                          <?php if ($no_permit_edit) echo "readonly"; ?>
                                        >
                                    </div>
                                
                                <?php } ?>
                              
                                <?php if (isset($manutencao)) {?>
                                    <div class="col col-md-2">
                                        <label for="situacao" class=" form-control-label">Situação</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <select name="situacao" id="situacao" class="form-control"  <?php if ($no_permit_edit) echo "readonly"; ?>>
                                            <option disabled value="" <?php if($manutencao->situacao==0){ echo "selected"; } ?>>Em Manutenção</option>
                                            <option value="1" <?php if($manutencao->situacao==1){ echo "selected"; } ?>>Manutenção OK</option>
                                            <option value="2" <?php if($manutencao->situacao==2){ echo "selected"; } ?>>Manutenção Com Pedência</option>
                                        </select>
                                    </div>
                                </div>
                                <?php } ?>

                                <div class="row">
                                    <div class="col col-md-2">
                                        <label for="descricao" class=" form-control-label">Descrição</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <textarea name="descricao" id="descricao" placeholder="Descreva o motivo da Manutenção aqui" class="form-control" <?php if ($no_permit_edit) echo "readonly"; ?>><?php if(isset($manutencao) && isset($manutencao->descricao)) echo ucwords($manutencao->descricao);?></textarea>
                                    </div>
                                </div>
                
                                
                                <?php if(!$no_permit_edit){ ?>
                                <hr>
                                <div class="pull-left">
                                    <button  <?php if ($no_permit_edit) echo "readonly"; ?> class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url("ativo_externo/manutencao/{$ativo->id_ativo_externo}");?>">
                                    <button class="btn btn-secondary" type="button">                                   
                                        <i class="fa fa-ban "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>                              
                                    </a>
                                </div>
                                <?php } ?>
                            </form>

                        </div>
                    </div>

                </div>
            </div>

        <?php if (isset($manutencao) && isset($anexos)) { ?>
        <div id="anexos" class="row">
          <div class="col-12">
            <?php $this->load->view('anexo/index', ['show_header' => false, 'permit_delete' => true]); ?>
          </div>
        </div>
        <?php } ?>
     
          <?php if (isset($manutencao) && isset($obs)) { ?>
            <div id="obs" class="row">
                <div class="col-12">
                    <?php $this->load->view('index_obs'); ?>
                </div>
            </div>
          <?php } ?>
        </div>
    </div>
</div>

<?php 
    if (isset($manutencao)): 
        $this->load->view('index_form_obs_modal'); 
        $this->load->view('anexo/index_form_modal', ["show_header" => false]); 
    endif;
?>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->