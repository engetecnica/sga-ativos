<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <?php if($this->permitido($permissoes, 6, 'adicionar')){ ?>
                            <a href="<?php echo base_url('obra/adicionar'); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                            <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Obras</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th width="7%">Id</th>
                                    <th>Código da Obra</th>
                                    <th>Empresa</th>
                                    <th>Razão Social</th>
                                    <th>CNPJ</th>
                                    <th>Responsável</th>
                                    <th>E-mail</th>
                                    <th>Celular</th>
                                    <th>Situação</th>
                                    <th>Obra Base</th>
                                    <th class="text-right">Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr id="<?php echo $valor->id_obra; ?>">
                                    <td>
                                        <?php if($this->permitido($permissoes, 6, 'editar')){ ?>
                                            <a href="<?php echo base_url('obra'); ?>/editar/<?php echo $valor->id_obra; ?>">
                                                <?php echo $valor->id_obra; ?>
                                            </a>
                                        <?php } else { ?>
                                            <?php echo $valor->id_obra; ?>
                                        <?php } ?>                                        
                                    </td>
                                    <td>
                                        <?php if($this->permitido($permissoes, 6, 'editar')){ ?>
                                            <a href="<?php echo base_url('obra'); ?>/editar/<?php echo $valor->id_obra; ?>">
                                                <?php echo $valor->codigo_obra; ?>
                                            </a>
                                        <?php } else { ?>
                                            <?php echo $valor->codigo_obra; ?>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $valor->empresa; ?></td>
                                    <td><?php echo $valor->obra_razaosocial ?: '-'; ?></td>
                                    <td><?php echo $valor->obra_cnpj ?: '-'; ?></td>
                                    <td><?php echo $valor->responsavel; ?></td>
                                    <td><?php echo $valor->responsavel_email; ?></td>
                                    <td><?php echo $valor->responsavel_celular; ?></td>
                                    <td>
                                      <?php $situacao = $this->get_situacao($valor->situacao, 'DESCARTADO', 'secondary');?>
                                      <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                    </td>
                                    <td><?php echo $this->get_obra_base($valor->obra_base); ?></td>
                                    <td class="text-right">
                                        <?php if($this->permitido($permissoes, 6, 'editar') || $this->permitido($permissoes, 6, 'excluir')){ ?>
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
                                                    <a class="dropdown-item " href="<?php echo base_url('obra'); ?>/editar/<?php echo $valor->id_obra; ?>"><i class="fas fa-edit"></i> Editar</a>
                                                <?php } ?>

                                                <?php if (!$valor->obra_base) { ?>
                                                    <?php if($this->permitido($permissoes, 6, 'excluir')){ ?>
                                                        <div class="dropdown-divider"></div>
                                                        <a 
                                                            class="dropdown-item  deletar_registro" 
                                                            href="javascript:void(0)" 
                                                            data-href="<?php echo base_url('obra'); ?>/deletar/<?php echo $valor->id_obra; ?>" 
                                                            data-registro="<?php echo $valor->id_obra;?>" 
                                                            data-tabela="obra"
                                                        >
                                                        <i class="fas fa-trash"></i> Excluir</a>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php } else {  echo "-"; } ?>
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
