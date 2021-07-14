<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
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
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th width="7%">Id</th>
                                    <th>Categoria</th>
                                    <th>Titulo</th>
                                    <th>Situação</th>
                                    <th class="text-right">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr>
                                    <td><?php echo $valor->id_ativo_configuracao; ?></td>
                                    <td><?php echo ($valor->id_ativo_configuracao_vinculo=='') ? "Configuração Principal" : $valor->id_ativo_configuracao_vinculo; ?></td>
                                    <td><?php echo $valor->titulo; ?></td>
                                    <td>
                                      <?php $situacao = $this->get_situacao($valor->situacao, 'DESCARTADO', 'secondary');?>
                                      <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?php echo base_url('ativo_configuracao'); ?>/editar/<?php echo $valor->id_ativo_configuracao; ?>"><i class="fas fa-edit"></i></a>
                                        <a href="javascript:void(0)" data-href="<?php echo base_url('ativo_configuracao'); ?>/deletar/<?php echo $valor->id_ativo_configuracao; ?>" data-registro="<?php echo $valor->id_ativo_configuracao;?>" data-tabela="ativo_configuracao" class="deletar_registro"><i class="fas fa-remove"></i></a>
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
