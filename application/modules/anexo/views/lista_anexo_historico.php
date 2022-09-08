<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="javascript:void(0)" onclick="history.back()">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-minus"></i>Listar todos os Anexos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Histórico de Anexo</h2>

                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th>Data de Inclusão</th>
                                    <th>ID</th>
                                    <th>Usuário</th>
                                    <th>Prévia</th>
                                    <th>Modulo</th>
                                    <th>Titulo</th>
                                    <th>Descrição</th>
                                    <th>Tipo</th>

                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach($historico->principal as $anexo){ ?>
                                    <tr id="<?php echo "anexo-{$anexo->id_anexo}"; ?>">
                                        <td><?php echo date('d/m/Y H:i:s', strtotime($anexo->data_inclusao)); ?></td>
                                        <td style="text-align:left;">
                                            <button class="badge badge-info"><?php echo $anexo->id_anexo; ?></button>
                                            <button class="badge badge-danger">Principal</button>
                                        </td>
                                        <td><?php echo $anexo->usuario; ?></td>
                                        <?php $this->load->view('anexo_preview', ['preview_content_tag' => 'td', 'anexo' => $anexo->anexo]); ?>
                                        <td><?php echo $anexo->modulo; ?></td>
                                        <td><?php echo $anexo->titulo; ?></td>
                                        <td><?php echo $anexo->descricao; ?></td>
                                        <td><?php echo $this->anexo_model->get_anexo_tipo($anexo->tipo)['nome'];?></td>
                                    </tr>
                                <?php } ?>

                                <?php foreach($historico->historico as $anexo){ ?>
                                    <tr id="<?php echo "anexo-{$anexo->id_anexo}"; ?>">
                                        <td><?php echo date('d/m/Y H:i:s', strtotime($anexo->data_inclusao)); ?></td>
                                        <td style="text-align:left;">
                                            <button class="badge badge-info"><?php echo $anexo->id_anexo; ?></button>
                                            <button class="badge badge-danger">Atualização</button>
                                        </td>
                                        <td><?php echo $anexo->usuario; ?></td>
                                        <?php $this->load->view('anexo_preview', ['preview_content_tag' => 'td', 'anexo' => $anexo->anexo]); ?>
                                        <td><?php echo $anexo->modulo; ?></td>
                                        <td><?php echo $anexo->titulo; ?></td>
                                        <td><?php echo $anexo->descricao; ?></td>
                                        <td><?php echo $this->anexo_model->get_anexo_tipo($anexo->tipo)['nome'];?></td>
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
