<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('ativo_interno/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Ativo Interno</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th width="7%">Id</th>
                                    <th width="20%">Título</th>
                                    <th width="15%">Valor Atribuído</th>
                                    <th>Quantidade</th>
                                    <th>Inclusão</th>
                                    <th>Situação</th>
                                    <th class="text-right">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr>
                                    <td><?php echo $valor->id_ativo_interno; ?></td>
                                    <td><?php echo $valor->nome; ?></td>
                                    <td>R$ <?php echo number_format($valor->valor, 2, ',', '.'); ?></td>
                                    <td><?php echo $valor->quantidade; ?></td>
                                    <td><?php echo date("d/m/Y H:i:s", strtotime($valor->data_inclusao)); ?></td>
                                    <td><?php echo $this->get_situacao($valor->situacao); ?></td>
                                    <td class="text-right">
                                        <a href="<?php echo base_url('ativo_interno'); ?>/editar/<?php echo $valor->id_ativo_interno; ?>"><i class="fas fa-edit"></i></a>
                                        <a href="javascript:void(0)" data-href="<?php echo base_url('ativo_interno'); ?>/deletar/<?php echo $valor->id_ativo_interno; ?>" data-registro="<?php echo $valor->id_ativo_interno;?>" data-tabela="ativo_interno" class="deletar_registro"><i class="fas fa-remove"></i></a>
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
