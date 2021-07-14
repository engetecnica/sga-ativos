<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
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
                    <h2 class="title-1 m-b-25"><?php echo  $ativo->nome; ?> - Manutenção</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th width="7%">Id</th>
                                    <th>Data da Saída</th>
                                    <th>Data da Retorno</th>
                                    <th>Situação</th>
                                    <th>Valor</th>
                                    <th class="text-right">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr>
                                    <td><?php echo $valor->id_manutencao; ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($valor->data_saida)); ?></td>
                                    <td><?php echo $valor->data_retorno ? date("d/m/Y", strtotime($valor->data_retorno)) : "-" ?></td>
                                    <td>
                                      <?php $situacao = $this->get_situacao_manutencao($valor->manutencao_situacao);?>
                                      <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                    </td>
                                    <td><?php echo $this->formata_moeda($valor->manutencao_valor);?></td>
                                    <td class="text-right">
                                          <a href="<?php echo base_url("ativo_interno/manutencao_editar/{$ativo->id_ativo_interno}/{$valor->id_manutencao}"); ?>"><i class="fas fa-edit"></i></a>
                                
                                        <?php if ((int) $valor->situacao == 0) { ?>
                                          <a 
                                            href="javascript:void(0)" 
                                            data-href="<?php echo base_url("ativo_interno/manutencao_remover/{$ativo->id_ativo_interno}/{$valor->id_manutencao}"); ?>" 
                                            data-registro="<?php echo $valor->id_ativo_interno;?>" 
                                            data-tabela="ativo_interno/manutencao/<?php echo $ativo->id_ativo_interno;?>" class="deletar_registro"
                                          >
                                            <i class="fas fa-remove"></i>
                                          </a>
                                        <?php } ?>
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
                  <a href="<?php echo base_url("ativo_interno/manutencao_adicionar/{$ativo->id_ativo_interno}"); ?>">
                    <button class="btn-custom">
                      <i class="zmdi zmdi-plus"></i>&nbsp;Adicionar
                    </button>
                  </a>
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
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
