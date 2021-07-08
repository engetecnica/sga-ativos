<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('usuario/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Usuários</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th width="7%">Id</th>
                                    <th>Nome de Usuário</th>
                                    <th>Empresa</th>
                                    <th>Obra</th>
                                    <th>Nível</th>
                                    <th>Ciação</th>
                                    <th class="text-right">Opções</th>
                                </tr>
                            </thead>
                       
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <?php if($this->session->userdata('logado')->id_usuario != $valor->id_usuario){ ?>
                                    <tr>
                                        <td><?php echo $valor->id_usuario; ?></td>
                                        <td>
                                            <a href="<?php echo base_url('usuario'); ?>/editar/<?php echo $valor->id_usuario; ?>">
                                            <?php echo $valor->usuario; ?>
                                            </a>
                                        </td>
                                        <td><?php echo isset($valor->nome_fantasia) ? $valor->nome_fantasia: (isset($valor->razao_socia) ? $valor->razao_social : ''); ?></td>
                                        <td><?php echo $valor->codigo_obra; ?></td>
                                        <td><?php echo $valor->nivel; ?></td>
                                        <td><?php echo $valor->data_criacao ? date('d/m/Y H:i:s', strtotime($valor->data_criacao)) : ''; ?></td>
                                        <td class="text-right">
                                            <a href="<?php echo base_url('usuario'); ?>/editar/<?php echo $valor->id_usuario; ?>"><i class="fas fa-edit"></i></a>
                                            <?php if($valor->id_usuario>1){ ?>
                                            <a href="javascript:void(0)" data-href="<?php echo base_url('usuario'); ?>/deletar/<?php echo $valor->id_usuario; ?>" data-registro="<?php echo $valor->id_usuario;?>" data-tabela="usuario" class="deletar_registro"><i class="fas fa-remove"></i></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
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
