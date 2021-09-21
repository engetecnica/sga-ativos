<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
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
                                    <th width="7%">Id</th>
                                    <th>Nome Completo</th>
                                    <th>E-mail</th>
                                    <th>Celular</th>
                                    <th>Situação</th>
                                    <th class="text-right">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr id="<?php echo $valor->id_funcionario; ?>">
                                    <td><?php echo $valor->id_funcionario; ?></td>
                                    <td><?php echo $valor->nome; ?></td>
                                    <td><?php echo $valor->email; ?></td>
                                    <td><?php echo $valor->celular; ?></td>
                                    <td>
                                      <?php $situacao = $this->get_situacao($valor->situacao, 'DESCARTADO', 'secondary');?>
                                      <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                    </td>
                                    <td class="text-right">
                                        <!--<a href="<?php echo base_url('documento'); ?>/tipo/funcionario/<?php echo $valor->id_funcionario; ?>"><i class="fas fa-id-card"></i></a>-->
                                        <a href="<?php echo base_url('funcionario'); ?>/editar/<?php echo $valor->id_funcionario; ?>"><i class="fas fa-edit"></i></a>
                                        <a href="javascript:void(0)" data-href="<?php echo base_url('funcionario'); ?>/deletar/<?php echo $valor->id_funcionario; ?>" data-registro="<?php echo $valor->id_funcionario;?>" data-tabela="funcionario" class="deletar_registro"><i class="fas fa-trash"></i></a>
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
