<!-- MAIN CONTENT-->
<style>
    .btn-contagem {
        width: 50px;
        height: 30px;
        font-weight: bold;
    }
    .btn-codigo {
        width: 100%;
        font-weight: bold;
    }
</style>
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
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

            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive table--no-card m-b-40">
                        <h3 class="title-1 m-b-25">Itens</h3>
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Item</th>
                                    <th>Obra</th>
                                    <th>Inclusão</th>
                                    <th>Liberação</th>
                                    <th>Tipo</th>
                                    <th>Situação</th>
                                    <th>Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr>
                                    <td>
                                        <a class="btn btn-sm btn-outline-success btn-codigo" href="<?php echo base_url('ativo_externo'); ?>/editar/<?php echo $valor->id_ativo_externo; ?>">    
                                            <?php echo $valor->codigo; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $valor->nome; ?></td>
                                    <td><?php echo $valor->codigo_obra." - ".$valor->endereco; ?></td>
                                    <td><?php echo date("d/m/Y H:i", strtotime($valor->data_inclusao)); ?></td>
                                    <td><?php if($valor->data_liberacao=="0000-00-00 00:00:00"){ echo "-"; } else { echo date("d/m/Y H:i", strtotime($valor->data_liberacao)); } ?></td>
                                    <td>
                                        <?php if($valor->tipo == 1) { ?>
                                            <button class="btn btn-outline-primary btn-sm">Kit</button>
                                        <?php } else { ?>
                                            <button class="btn btn-outline-secondary btn-sm">Unidade</button>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm">Estoque</button>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('ativo_externo'); ?>/editar/<?php echo $valor->id_ativo_externo; ?>"><i class="fas fa-edit"></i></a>
                                        <a href="javascript:void(0)" data-href="<?php echo base_url('ativo_externo'); ?>/deletar/<?php echo $valor->id_ativo_externo; ?>" data-registro="<?php echo $valor->id_ativo_externo;?>" data-tabela="ativo_externo" class="deletar_registro"><i class="fas fa-remove"></i></a>
                                    </td>
                                </tr>
                               <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive table--no-card m-b-40">
                        <h3 class="title-1 m-b-25">Grupos</h3>
                        <table class="table table-borderless table-striped table-earning" id="lista2">
                            <thead>
                                <tr>
                                    <th>GID</th>
                                    <th>Grupo</th>
                                    <th>Tipo</th>
                                    <th>Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($grupos as $valor){ ?>
                                <tr>
                                    <td><?php echo $valor->id_ativo_externo_grupo; ?></td>
                                    <td><?php echo $valor->nome; ?></td>
                                    <td>
                                        <?php if($valor->tipo == 1) { ?>
                                            <button class="btn btn-outline-primary btn-sm">Kit</button>
                                        <?php } else { ?>
                                            <button class="btn btn-outline-secondary btn-sm">Unidade</button>
                                        <?php } ?>
                                    </td>
                                    <td>
                                    <a href="<?php echo base_url('ativo_externo/adicionar'); ?>/<?php echo $valor->id_ativo_externo_grupo; ?>">
                                        <button class="btn btn-sm btn-secondary">
                                            <i class="fa fa-plus"></i>Adicionar ao Grupo
                                        </button>
                                    </a>
                                        <a href="<?php echo base_url('ativo_externo'); ?>/editar_grupo/<?php echo $valor->id_ativo_externo_grupo; ?>"><i class="fas fa-edit"></i></a>
                                        <a href="javascript:void(0)" data-href="<?php echo base_url('ativo_externo'); ?>/deletar_grupo/<?php echo $valor->id_ativo_externo_grupo; ?>" data-registro="<?php echo $valor->id_ativo_externo_grupo;?>" data-tabela="ativo_externo" class="deletar_registro"><i class="fas fa-remove"></i></a>
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


