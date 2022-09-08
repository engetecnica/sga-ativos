<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url("ativo_interno/editar/{$ativo->id_ativo_interno}"); ?>">
                          <button class="au-btn au-btn-icon au-btn--blue">
                            <i class="fa fa-arrow-left"></i>Voltar ao Ativo
                          </button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Manutenção | <?php echo  $ativo->nome; ?></h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th width="7%">Id</th>
                                    <th>Data da Saída</th>
                                    <th>Data da Retorno</th>
                                    <th>Situação</th>
                                    <th>Valor</th>
                                    <th class="text-right">Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr>
                                    <td><?php echo $valor->id_manutencao; ?></td>
                                    <td><?php echo $this->formata_data($valor->data_saida); ?></td>
                                    <td><?php echo $this->formata_data($valor->data_retorno); ?></td>
                                    <td>
                                      <?php $situacao = $this->get_situacao_manutencao($valor->manutencao_situacao);?>
                                      <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                    </td>
                                    <td><?php echo $this->formata_moeda($valor->manutencao_valor);?></td>
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
                                              <a class="dropdown-item " href="<?php echo base_url("ativo_interno/manutencao_editar/{$ativo->id_ativo_interno}/{$valor->id_manutencao}"); ?>"><i class="fas fa-edit"></i> Editar</a>
                                              <?php if ($this->ativo_interno_model->permit_delete_manutencao($ativo->id_ativo_interno, $valor->id_manutencao)) { ?>
                                                <div class="dropdown-divider"></div>
                                                <a 
                                                  href="javascript:void(0)" 
                                                  data-href="<?php echo base_url("ativo_interno/manutencao_remover/{$valor->id_manutencao}"); ?>" 
                                                  data-registro="<?php echo $ativo->id_ativo_interno;?>" 
                                                  data-tabela="ativo_interno/manutencao/<?php echo $ativo->id_ativo_interno;?>" class="dropdown-item  deletar_registro"
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

            <?php if($this->ativo_interno_model->permit_create_manutencao($ativo->id_ativo_interno)) { ?>
            <div class="row">
                <div class="col-12 col-md-12 text-center">
                  <a href="<?php echo base_url("ativo_interno/manutencao_adicionar/{$ativo->id_ativo_interno}"); ?>">
                    <button class="btn-custom">
                      <i class="zmdi zmdi-plus"></i>&nbsp;Adicionar
                    </button>
                  </a>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
