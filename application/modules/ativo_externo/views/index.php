<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">

                        <a href="<?php echo base_url('ativo_externo/adicionar'); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-plus"></i>Adicionar</button></a>

                        <a href="<?php echo base_url('ativo_externo/grupos'); ?>">
                            <button class="au-btn au-btn-icon btn-dark ml-3">
                                <i class="zmdi zmdi-list"></i>Grupos de Ativos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Ativos Externos</h2>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive table--no-card m-b-40">
                        <h3 class="title-1 m-b-25">Itens</h3>
                        <table class="table table-borderless table-striped table-earning" id="ativo_externo_ativos_index">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Item</th>
                                    <th>Obra</th>
                                    <th>Inclusão</th>
                                    <th>Descarte</th>
                                    <th>Tipo</th>
                                    <th>Incluso no Kit</th>
                                    <th>Situação</th>
                                    <th>Necessita Calibração</th>
                                    <th>valor</th>
                                    <th>Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($lista as $valor) {
                                    $self_obra = $valor->id_obra === $user->id_obra;
                                ?>
                                    <tr id="<?php echo "ativo-" . $valor->id_ativo_externo; ?>">
                                        <td>
                                            <?php if ($self_obra) { ?>
                                                <a class="" href="<?php echo base_url('ativo_externo'); ?>/editar/<?php echo $valor->id_ativo_externo; ?>">
                                                    <?php echo $valor->codigo; ?>
                                                </a>
                                            <?php } else {
                                                echo $valor->codigo;
                                            } ?>
                                        </td>
                                        <td><?php echo $valor->nome; ?></td>
                                        <td><?php echo $valor->obra; ?></td>
                                        <td><?php echo date("d/m/Y H:i", strtotime($valor->data_inclusao)); ?></td>
                                        <td><?php echo isset($valor->data_descarte) ? date("d/m/Y H:i", strtotime($valor->data_descarte)) : "-"; ?></td>
                                        <td>
                                            <?php if ($valor->tipo == 1) { ?>
                                                <button class="badge badge-primary badge-sm">Kit</button>
                                            <?php } elseif ($valor->tipo == 0) { ?>
                                                <button class="badge badge-secondary badge-sm">Unidade</button>
                                            <?php } elseif ($valor->tipo == 2) { ?>
                                                <button class="badge badge-secondary badge-sm">Metro</button>
                                            <?php } else { ?>
                                                <button class="badge badge-secondary badge-sm">Conjunto</button>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php $kit = $this->get_ativo_externo_on_lista($lista, $valor->id_ativo_externo_kit); ?>
                                            <?php if ($kit) { ?>
                                                <a href="<?php echo base_url('ativo_externo'); ?>/editar_items/<?php echo $kit->id_ativo_externo; ?>">
                                                    <?php echo $kit->codigo; ?>
                                                </a>
                                            <?php } else {
                                                echo "-";
                                            } ?>
                                        </td>
                                        <td>
                                            <?php $status = $this->status($valor->situacao); ?>
                                            <span class="badge badge-<?php echo $status['class']; ?>"><?php echo $status['texto']; ?></span>
                                        </td>

                                        <td>
                                            <?php
                                            $text = isset($valor->necessita_calibracao) && $valor->necessita_calibracao == '1' ?  'Sim' : 'Não';
                                            $class = isset($valor->necessita_calibracao) && $valor->necessita_calibracao == '1' ?  'success' : 'danger';
                                            ?>
                                            <span class="badge badge-<?php echo $class; ?>"><?php echo $text; ?></span>
                                        </td>
                                        <td><?php echo $this->formata_moeda($valor->valor); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button id="ativo_externo_item" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Gerenciar
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="ativo_externo_item">
                                                    <?php if ($self_obra) { ?>
                                                        <a class="dropdown-item " href="<?php echo base_url('ativo_externo'); ?>/editar/<?php echo $valor->id_ativo_externo; ?>"><i class="fa fa-edit"></i> Editar</a>
                                                        <div class="dropdown-divider"></div>
                                                    <?php } ?>

                                                    <a class="dropdown-item" href="<?php echo base_url("ativo_externo/qrcode/{$valor->id_ativo_externo}"); ?>">
                                                        <i class="fa fa-qrcode"></i>&nbsp; Gerar Etiqueta
                                                    </a>
                                                    <div class="dropdown-divider"></div>

                                                    <a class="dropdown-item " href="<?php echo base_url("ativo_externo/manutencao/{$valor->id_ativo_externo}"); ?>">
                                                        <i class="fa fa-wrench"></i>&nbsp; Manutenção
                                                    </a>
                                                    <div class="dropdown-divider"></div>

                                                    <?php if (((isset($valor) && isset($valor->id_ativo_externo)) && $valor->tipo == 1) && ($self_obra)) { ?>
                                                        <a class="dropdown-item " href="<?php echo base_url("ativo_externo/editar_items/{$valor->id_ativo_externo}"); ?>">
                                                            <i class="fa fa-th-large"></i> Editar Itens (KIT)
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                    <?php } ?>

                                                    <?php if ((isset($valor) && isset($valor->necessita_calibracao)) && $valor->necessita_calibracao == 1) { ?>
                                                        <a class="dropdown-item " href="<?php echo base_url("ativo_externo/certificado_de_calibracao/{$valor->id_ativo_externo}"); ?>">
                                                            <i class="fa fa-balance-scale"></i>&nbsp; Cert. de Calibração
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                    <?php } ?>


                                                    <a class="dropdown-item " href="<?php echo base_url("anexo/index/12/{$valor->id_ativo_externo}"); ?>">
                                                        <i class="fa fa-files-o"></i> Anexos
                                                    </a>
                                                    <div class="dropdown-divider"></div>


                                                    <?php if ($valor->situacao == 8 && ($self_obra)) { ?>
                                                        <a href="javascript:void(0)" data-href="<?php echo base_url('ativo_externo'); ?>/descartar/<?php echo $valor->id_ativo_externo; ?>" redirect="true" data-tabela="ativo_externo" class="dropdown-item confirmar_registro"><i class="fa fa-ban"></i> Descartar</a>
                                                        <div class="dropdown-divider"></div>
                                                    <?php } ?>



                                                    <?php if ($valor->situacao == 12 && ($self_obra)) { ?>
                                                        <a href="javascript:void(0)" data-href="<?php echo base_url('ativo_externo'); ?>/deletar/<?php echo $valor->id_ativo_externo; ?>" data-registro="<?php echo $valor->id_ativo_externo; ?>" data-tabela="ativo_externo" class="dropdown-item  deletar_registro">
                                                            <i class="fa fa-trash"></i> Excluir
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
<script>
    $(document).ready(() => {
        $('.form-control.filter').change((e) => {
            const form = $(e.target.parentNode.parentNode.parentElement)
            form.submit()
        })
    })

    const options = {
        serverSide: false,
        searchable: true,
    }

    $(window).ready(() => {
        loadDataTable('ativo_externo_grupos_index', options)
        loadDataTable('ativo_externo_ativos_index', options)
    })
    $(window).resize(() => {
        loadDataTable('ativo_externo_grupos_index', options)
        loadDataTable('ativo_externo_ativos_index', options)
    })
</script>