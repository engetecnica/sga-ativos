<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo isset($ativo) ? base_url("ativo_interno/manutencao/{$ativo->id_ativo_interno}") : ''; ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>Voltar ao Histórico</button></a>
                    </div>
                </div>
            </div>
        <?php if($ativo->situacao <= 1){ ?>
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25"><?php echo $ativo->nome; ?></h2>

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

                            <form action="<?php echo base_url("ativo_interno/manutencao_salvar"); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($ativo) && isset($ativo->id_ativo_interno)){?>
                                  <input type="hidden" name="id_ativo_interno" id="id_ativo_interno" value="<?php echo $ativo->id_ativo_interno; ?>">
                                <?php } ?>

                                <?php if(isset($manutencao) && isset($manutencao->id_manutencao)){?>
                                  <input type="hidden" name="id_manutencao" id="id_manutencao" value="<?php echo $manutencao->id_manutencao; ?>">
                                <?php } ?>
                                
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="data_saida" class=" form-control-label">Data Saída</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input 
                                        <?php echo isset($manutencao) && isset($manutencao->id_manutencao) ? 'readonly' : ''?> 
                                        required="required" type="date" id="data_saida" name="data_saida" placeholder="Nome do Ativo" class="form-control" 
                                        value="<?php echo isset($manutencao) ? date('Y-m-d', strtotime($manutencao->data_saida)) : ''; ?>">
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
                                        >
                                    </div>
                                
                                <?php } ?>
                              
                                <?php if (isset($manutencao) && (int) $ativo->situacao <= 1) {?>
                                    <div class="col col-md-2">
                                        <label for="situacao" class=" form-control-label">Situação</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <select name="situacao" id="situacao" class="form-control">
                                            <option disabled value="" <?php if($manutencao->situacao==0){ echo "selected"; } ?>>Em Manutenção</option>
                                            <option value="1" <?php if($manutencao->situacao==1){ echo "selected"; } ?>>Manutenção OK</option>
                                            <option value="2" <?php if($manutencao->situacao==2){ echo "selected"; } ?>>Manutenção Com Pedência</option>
                                        </select>
                                    </div>
                                </div>
                                <?php } ?>
                                
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url("ativo_interno/manutencao/{$ativo->id_ativo_interno}");?>">
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
        <?php } ?>
          <?php if (isset($manutencao) && isset($obs)) { ?>
            <div id="obs" class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Observações</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th width="7%">Id</th>
                                    <th width="10%">Usuário</th>
                                    <th>Texto</th>
                                    <th width="10%">Data de Inclusão</th>
                                    <th width="10%">Data da Edição</th>
                                    <th width="10%" class="text-right">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($obs as $valor){ ?>
                                <tr width="100%">
                                    <td><?php echo $valor->id_obs; ?></td>
                                    <td><?php echo $valor->usuario; ?></td>
                                    <td><?php echo $valor->texto; ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($valor->data_inclusao)); ?></td>
                                    <td><?php echo $valor->data_edicao ? date("d/m/Y", strtotime($valor->data_edicao)) : "-" ?></td>
                      
                                    <td class="text-right">
                                     

                                      <div class="btn-group">
                                            <button 
                                                class="btn btn-secondary btn-sm dropdown-toggle" 
                                                type="button"
                                                data-toggle="dropdown" 
                                                aria-haspopup="true" 
                                                aria-expanded="false"
                                            >
                                                Gerenciar
                                            </button>
                                            <div class="dropdown-menu">
                                                <?php if($ativo->situacao <= 1){ ?>
                                                <a class="dropdown-item " href="<?php echo base_url("ativo_interno/manutencao_obs_editar/{$ativo->id_ativo_interno}/{$valor->id_manutencao}/{$valor->id_obs}"); ?>">
                                                <i class="fas fa-edit"></i> Editar
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a 
                                                    href="javascript:void(0)#obs" 
                                                    data-href="<?php echo base_url("ativo_interno/manutencao_obs_remover/{$valor->id_obs}"); ?>" 
                                                    data-registro="<?php echo $valor->id_obs;?>" 
                                                    data-tabela="ativo_interno/manutencao_editar/<?php echo "{$ativo->id_ativo_interno}/{$manutencao->id_manutencao}";?>" 
                                                    class="dropdown-item  deletar_registro"
                                                >
                                                    <i class="fas fa-trash"></i> Excluir
                                                </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                               <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php if($ativo->situacao <= 1){ ?>
            <div class="row">
                <div class="col-12 col-md-12 text-center">
                  <a href="<?php echo base_url("ativo_interno/manutencao_obs_adicionar/{$ativo->id_ativo_interno}/{$manutencao->id_manutencao}"); ?>">
                    <button class="btn-custom">
                    <i class="fa fa-comments" aria-hidden="true"></i>&nbsp;Adicionar
                    </button>
                  </a>
                </div>
            </div>
            <?php } ?>
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
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
