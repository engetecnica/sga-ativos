<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                    <?php if($this->permitido($permissoes, 3, 'adicionar')){ ?>
                        <a href="<?php echo base_url('funcionario/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                    <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Funcionários</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th width="10%">Matrícula</th>
                                    <th>Nome Completo</th>
                                    <th>E-mail</th>
                                    <th>Celular</th>
                                    <th>Empresa</th>
                                    <th>Obra</th>
                                    <th>Situação</th>
                                    <th class="text-right">Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr id="<?php echo $valor->matricula; ?>">
                                    <td>
                                        <?php if($this->permitido($permissoes, 3, 'editar')){ ?>
                                        <a href="<?php echo base_url('funcionario'); ?>/editar/<?php echo $valor->id_funcionario; ?>">
                                            <?php echo $valor->matricula; ?>
                                        </a>
                                        <?php } else { ?>
                                            <?php echo $valor->matricula; ?>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if($this->permitido($permissoes, 3, 'editar')){ ?>
                                        <a href="<?php echo base_url('funcionario'); ?>/editar/<?php echo $valor->id_funcionario; ?>">
                                            <?php echo $valor->nome; ?>
                                        </a>
                                        <?php } else { ?>
                                            <?php echo $valor->nome; ?>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $valor->email; ?></td>
                                    <td><?php echo $valor->celular; ?></td>
                                    <td><?php echo $valor->empresa_social; ?></td>
                                    <td><?php echo $valor->codigo_obra; ?></td>
                                    <td>
                                      <?php $situacao = $this->get_situacao($valor->situacao, 'DESCARTADO', 'secondary');?>
                                      <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                    </td>
                                    <td class="text-right">
                                        <?php if($this->permitido($permissoes, 3, 'editar') || $this->permitido($permissoes, 3, 'excluir')){ ?>
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
                                                <?php if($this->permitido($permissoes, 6, 'editar')){ ?>
                                                    <a class="dropdown-item " href="<?php echo base_url('funcionario'); ?>/editar/<?php echo $valor->id_funcionario; ?>"><i class="fas fa-edit"></i> Editar</a>
                                                    <?php } ?>
                                                    
                                                <?php if($this->permitido($permissoes, 6, 'excluir')){ ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item  deletar_registro" href="javascript:void(0)" data-href="<?php echo base_url('funcionario'); ?>/deletar/<?php echo $valor->id_funcionario; ?>" data-registro="<?php echo $valor->id_funcionario;?>" data-tabela="funcionario"><i class="fas fa-trash"></i> Excluir</a>
                                                <?php } ?>

                                            </div>
                                        </div>
                                        <?php } else { echo "-"; } ?>
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
