<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($this->permitido($permissoes, 13, 'adicionar')) { ?>
                    <div class="overview-wrap">
                        <a href="<?php echo base_url('insumo/adicionar'); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue2">
                                <i class="zmdi zmdi-plus"></i>Novo Insumo
                            </button>
                        </a>

                        <a href="<?php echo base_url('insumo/retirada'); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue" style="margin-left: 10px;">
                                <i class="zmdi zmdi-plus"></i>Retiradas
                            </button>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-20">Insumos</h2>
                    <div class="table table--no-card table-responsive table--no- m-b-40">

                        <table class="table table-borderless table-striped table-earning" id="insumo_index">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Categoria</th>
                                    <th>Titulo</th>
                                    <th>Código do Insumo</th>
                                    <th>Estoque Atual</th>
                                    <th>Situação</th>
                                    <th>Gerenciar</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($insumos as $insumo){ ?>
                                <tr>
                                    <td><?php echo $insumo->id_insumo; ?></td>
                                    <td><?php echo $insumo->id_insumo_configuracao; ?></td>
                                    <td><?php echo $insumo->titulo; ?></td>
                                    <td><?php echo $insumo->codigo_insumo; ?></td>
                                    <td><?php echo $insumo->entrada - $insumo->saida." ".$insumo->medicao_sigla; ?></td>
                                    <td>
                                        <?php if($insumo->situacao == 0) { ?>
                                        <button class="badge badge-primary">Ativo</button>
                                        <?php } else { ?>
                                        <button class="badge badge-secondary">Inativo</button>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button id="insumo" type="button"
                                                class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                Gerenciar
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="insumo">
                                                <a class="dropdown-item adicionar-estoque" data-toggle="modal"
                                                    data-target="#novo_estoque"
                                                    data-item="<?php echo $insumo->titulo; ?>"
                                                    data-id_insumo="<?php echo $insumo->id_insumo; ?>">
                                                    <i class="fa fa-truck"></i> Incluir</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item "
                                                    href="<?php echo base_url('insumo'); ?>/editar/<?php echo $insumo->id_insumo; ?>">
                                                    <i class="fa fa-edit"></i> Editar</a>
                                                <div class="dropdown-divider"></div>
                                                <a href="javascript:void(0)"
                                                    data-href="<?php echo base_url('insumo'); ?>/deletar/<?php echo $insumo->id_insumo; ?>"
                                                    data-registro="<?php echo $insumo->id_insumo;?>"
                                                    data-tabela="insumo" class="dropdown-item  deletar_registro"><i
                                                        class="fa fa-trash"></i>&nbsp; Excluir</a>
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
        </div>
    </div>
</div>

<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->



<?php $this->load->view('modal_novo_estoque'); ?>

<script>
    $('#novo_estoque').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var item_titulo = button.data('item')
        var id_insumo = button.data('id_insumo')
        $('#item-titulo').val(item_titulo)
        $('#id_insumo').val(id_insumo)
    })

    const options = {
        serverSide: false,
        searchable: true,
    }

    $(window).ready(() => loadDataTable('insumo_index', options))
    $(window).resize(() => loadDataTable('insumo_index', options))
</script>