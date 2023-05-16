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
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Ativos Externo</h2>
                </div>
            </div>

            <form class="row col-12 search-filter" action="/ativo_externo">
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="search-filter-select">
                        <label for="filter.necessita_calibracao">Necessita Calibração</label>
                        <select name="filter.necessita_calibracao" id="filter.necessita_calibracao" class="form-control filter">
                            <option <?php echo $calibracao == 'sem-filtro' ? 'selected' : ''; ?> value="sem-filtro">Sem Filtro</option>
                            <option <?php echo $calibracao == '1' ? 'selected' : ''; ?> value="1">Sim</option>
                            <option <?php echo $calibracao == '0' ? 'selected' : ''; ?> value="0">Não</option>
                        </select>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive table--no-card m-b-40">
                        <h3 class="title-1 m-b-25">Grupos</h3>
                        <table class="table table-borderless table-striped table-earning" id="ativo_externo_grupos_index">
                            <thead>
                                <tr>
                                    <th>GID</th>
                                    <th>Grupo</th>
                                    <th>Total</th>
                                    <th>Em Estoque</th>
                                    <th>Tipo</th>
                                    <th>Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($grupos as $valor) { ?>
                                    <tr id="<?php echo "grupo-" . $valor->id_ativo_externo; ?>">
                                        <td><?php echo $valor->id_ativo_externo_grupo; ?></td>
                                        <td><?php echo $valor->nome; ?></td>
                                        <td><?php echo $valor->total; ?></td>
                                        <td><?php echo $valor->estoque; ?></td>
                                        <td>
                                            <?php if ($valor->tipo == 1) { ?>
                                                <button class="badge badge-primary">Kit</button>
                                            <?php } else { ?>
                                                <button class="badge badge-secondary">Unidade</button>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button id="ativo_externo_group" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Gerenciar
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="ativo_externo_group">
                                                    <a class="dropdown-item " href="<?php echo base_url('ativo_externo/adicionar'); ?>/<?php echo $valor->id_ativo_externo_grupo; ?>">
                                                        <i class="fa fa-plus"></i> Adicionar
                                                    </a>

                                                    <?php if ($valor->estoque > $valor->foradeoperacao) { ?>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item " href="<?php echo base_url('ativo_externo'); ?>/editar_grupo/<?php echo $valor->id_ativo_externo_grupo; ?>">
                                                            <i class="fa fa-edit"></i> Editar</a>
                                                    <?php } ?>

                                                    <?php if ($valor->estoque == $valor->total && $this->ativo_externo_model->permit_delete_grupo($valor->id_ativo_externo_grupo)) { ?>
                                                        <div class="dropdown-divider"></div>
                                                        <a href="javascript:void(0)" data-href="<?php echo base_url('ativo_externo'); ?>/deletar_grupo/<?php echo $valor->id_ativo_externo_grupo; ?>" data-registro="<?php echo $valor->id_ativo_externo_grupo; ?>" data-tabela="ativo_externo" class="dropdown-item  deletar_registro"><i class="fa fa-trash"></i>&nbsp; Excluir Grupo</a>
                                                    <?php } ?>

                                                    <?php if (
                                                        $this->ativo_externo_model->permit_descarte_grupo($valor->id_ativo_externo_grupo, $user->id_obra) &&
                                                        !$this->ativo_externo_model->verifica_descarte_grupo($valor->id_ativo_externo_grupo, $user->id_obra)
                                                    ) { ?>
                                                        <div class="dropdown-divider"></div>
                                                        <a href="javascript:void(0)" data-href="<?php echo base_url('ativo_externo'); ?>/descartar_grupo/<?php echo $valor->id_ativo_externo_grupo; ?>" data-registro="<?php echo $valor->id_ativo_externo_grupo; ?>" redirect="true" data-tabela="ativo_externo" class="dropdown-item  confirmar_registro"><i class="fa fa-ban"></i>&nbsp; Descartar Grupo</a>
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