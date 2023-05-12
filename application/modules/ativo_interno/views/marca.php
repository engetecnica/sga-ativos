<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <?php if ($this->permitido($permissoes, 10, 'adicionar')) { ?>
                            <a href="<?php echo base_url('ativo_interno/marca/adicionar'); ?>">
                                <button class="au-btn au-btn-icon au-btn--blue">
                                    <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                        <?php } ?>

                        <?php if ($this->permitido($permissoes, 10, 'adicionar')) { ?>
                            <a href="<?php echo base_url('ativo_interno'); ?>">
                                <button class="au-btn au-btn-icon btn-danger ml-2">
                                    <i class="zmdi zmdi-list"></i>Listar Ativos Internos</button></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Marcas - Ativo Interno</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning dataTable" id="lista8">
                            <thead>
                                <tr>
                                    <th style="width: 5% !important;">ID</th>
                                    <th>Titulo</th>
                                    <th></th>
                                    <th></th>
                                    <th width="10%">Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lista as $l) { ?>
                                    <tr>
                                        <td><span class="badge badge-dark"><?php echo $l->id_ativo_interno_marca; ?></span></td>
                                        <td><?php echo $l->titulo; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <?php
                                            echo $this->load->view('index/actions-marca', [
                                                "btn_text" => "Gerenciar Marca",
                                                "permissoes" => $permissoes,
                                                "row" => $l
                                            ], true);
                                            ?>
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