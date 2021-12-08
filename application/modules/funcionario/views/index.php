<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="<?php echo base_url('funcionario/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
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
                                        <a href="<?php echo base_url('funcionario'); ?>/editar/<?php echo $valor->id_funcionario; ?>">
                                            <?php echo $valor->matricula; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $valor->nome; ?></td>
                                    <td><?php echo $valor->email; ?></td>
                                    <td><?php echo $valor->celular; ?></td>
                                    <td><?php echo $valor->empresa_social; ?></td>
                                    <td><?php echo $valor->codigo_obra; ?></td>
                                    <td>
                                      <?php $situacao = $this->get_situacao($valor->situacao, 'DESCARTADO', 'secondary');?>
                                      <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                    </td>
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
                                                <a class="dropdown-item " href="<?php echo base_url('funcionario'); ?>/editar/<?php echo $valor->id_funcionario; ?>"><i class="fas fa-edit"></i> Editar</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item  deletar_registro" href="javascript:void(0)" data-href="<?php echo base_url('funcionario'); ?>/deletar/<?php echo $valor->id_funcionario; ?>" data-registro="<?php echo $valor->id_funcionario;?>" data-tabela="funcionario"><i class="fas fa-trash"></i> Excluir</a>
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
