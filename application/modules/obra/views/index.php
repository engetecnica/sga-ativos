<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('obra/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
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
                                    <th>Responsável</th>
                                    <th>E-mail</th>
                                    <th>Celular</th>
                                    <th>Situação</th>
                                    <th>Obra Base</th>
                                    <th class="text-right">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr>
                                    <td><?php echo $valor->id_obra; ?></td>
                                    <td><?php echo $valor->codigo_obra; ?></td>
                                    <td><?php echo $valor->empresa; ?></td>
                                    <td><?php echo $valor->responsavel; ?></td>
                                    <td><?php echo $valor->responsavel_email; ?></td>
                                    <td><?php echo $valor->responsavel_celular; ?></td>
                                    <td>
                                      <?php $situacao = $this->get_situacao($valor->situacao, 'DESCARTADO', 'secondary');?>
                                      <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                    </td>
                                    <td><?php echo $this->get_obra_base($valor->obra_base); ?></td>
                                    <td class="text-right">
                                        <a href="<?php echo base_url('obra'); ?>/editar/<?php echo $valor->id_obra; ?>"><i class="fas fa-edit"></i></a>
                                        <?php if (!$valor->obra_base) { ?>
                                        <a href="javascript:void(0)" data-href="<?php echo base_url('obra'); ?>/deletar/<?php echo $valor->id_obra; ?>" data-registro="<?php echo $valor->id_obra;?>" data-tabela="obra" class="deletar_registro"><i class="fas fa-remove"></i></a>
                                        <?php } ?>
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
