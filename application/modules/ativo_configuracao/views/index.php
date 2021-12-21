<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url('ativo_configuracao/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Configurações de Ativos</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th width="7%">Id</th>
                                    <th>Titulo</th>
                                    <th>Categoria</th>
                                    <th>Situação</th>
                                    <th class="text-right">Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr id="<?php echo "configuracao-{$valor->id_ativo_configuracao}"?>">
                                    <td>
                                        <a  href="<?php echo base_url('ativo_configuracao'); ?>/editar/<?php echo $valor->id_ativo_configuracao; ?>">
                                            <?php echo $valor->id_ativo_configuracao; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $valor->titulo; ?></td>
                                    <td><?php echo ($valor->id_ativo_configuracao_vinculo=='') ? "Configuração Principal" : $valor->id_ativo_configuracao_vinculo; ?></td>
                                    <td>
                                      <?php $situacao = $this->get_situacao($valor->situacao, 'DESCARTADO', 'secondary');?>
                                      <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                    </td>
                                    <td class="text-right">
                                        <!-- <a href="<?php echo base_url('ativo_configuracao'); ?>/editar/<?php echo $valor->id_ativo_configuracao; ?>"><i class="fas fa-edit"></i></a>
                                        <?php if ($valor->id_ativo_configuracao > 15) {?>
                                        <a href="javascript:void(0)" data-href="<?php echo base_url('ativo_configuracao'); ?>/deletar/<?php echo $valor->id_ativo_configuracao; ?>" data-registro="<?php echo $valor->id_ativo_configuracao;?>" data-tabela="ativo_configuracao" class="deletar_registro"><i class="fas fa-trash"></i></a>
                                        <?php } ?> -->

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
                                                <a 
                                                    class="dropdown-item " 
                                                    href="<?php echo base_url('ativo_configuracao'); ?>/editar/<?php echo $valor->id_ativo_configuracao; ?>"
                                                >
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                                <?php if ($this->ativo_configuracao_model->permit_delete_configuracao($valor->id_ativo_configuracao)) {?>
                                                <div class="dropdown-divider"></div>
                                                <a 
                                                    class="dropdown-item  deletar_registro" 
                                                    href="javascript:void(0)" 
                                                    data-href="<?php echo base_url('ativo_configuracao'); ?>/deletar/<?php echo $valor->id_ativo_configuracao; ?>" 
                                                    data-registro="<?php echo $valor->id_ativo_configuracao;?>" 
                                                    data-tabela="ativo_configuracao"
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
