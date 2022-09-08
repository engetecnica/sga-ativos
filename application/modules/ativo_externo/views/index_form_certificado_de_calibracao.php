<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo isset($ativo) ? base_url("ativo_externo/certificado_de_calibracao/{$ativo->id_ativo_externo}") : ''; ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>Voltar a Lista</button></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25"><?php echo $ativo->nome ." - ".$ativo->codigo; ?></h2>

                    <div class="card">
                      <div class="card-header">
                          <?php 
                            if(isset($certificado) && isset($certificado->id_certificado)){
                              echo  'Editar Registro de Certificado de Calibração';
                            } else {
                              echo  'Novo Registro de Certificado de Calibração';
                            } 
                          ?>
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url("ativo_externo/certificado_de_calibracao_salvar/{$ativo->id_ativo_externo}"); ?>" method="post" enctype="multipart/form-data">

                                <input type="hidden" id="id_ativo_externo" name="id_ativo_externo" value="<?php echo $ativo->id_ativo_externo;?>" />

                                <?php if (isset($certificado) && isset($certificado->id_certificado)) {?>
                                    <input type="hidden" id="id_certificado" name="id_certificado" value="<?php echo $certificado->id_certificado;?>" />
                                <?php } ?>

                                <div class="row form-group">
                                    <?php if (!isset($certificado) || !isset($certificado->id_certificado)) {?>
                                        <div class="col col-md-2">
                                            <label for="certificado_de_calibracao" class=" form-control-label">Anexar Certificado</label>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input required="required" type="file" id="certificado_de_calibracao" name="certificado_de_calibracao" class="form-control" accept="application/pdf, image/*, application/vnd.ms-excel" style="margin-bottom: 5px;"> 
                                            <small size='2'>Formatos aceito: <strong>*.PDF, *.XLS, *.XLSx, *.JPG, *.PNG, *.JPEG, *.GIF</strong></small>
                                            <small size='2'>Tamanho Máximo: <strong><?php echo $upload_max_filesize;?></strong></small>
                                        </div>
                                    <?php } ?>

                                    <div class="col col-md-2">
                                        <label for="data_vencimento" class=" form-control-label">Data de Vencimento</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input required="required" type="date" id="data_vencimento" name="data_vencimento" class="form-control" style="margin-bottom: 5px;"
                                        value="<?php echo isset($certificado) && isset($certificado->data_vencimento) ? $certificado->data_vencimento : '' ?>" > 
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col col-md-2">
                                        <label for="observacao" class=" form-control-label">Observação</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <textarea name="observacao" id="observacao" placeholder="Sua Observação aqui" class="form-control"><?php echo isset($certificado) && isset($certificado->observacao) ? $certificado->observacao : '' ?></textarea>
                                    </div>
                                </div>

                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url("ativo_externo/certificado_de_calibracao/{$ativo->id_ativo_externo}");?>">
                                    <button class="btn btn-secondary" type="button">                                   
                                        <i class="fa fa-ban "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>                              
                                    </a>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>

            <?php if (isset($certificado) && isset($anexos)) { ?>
            <div id="anexos" class="row">
                <div class="col-12">
                    <?php 
                        if (isset($certificado) && isset($certificado->id_certificado)) $back_url .= "/{$certificado->id_certificado}";
                        $this->load->view('anexo/index', [
                            'show_header' => false, 
                            'permit_add_btn' => false,
                            'permit_delete_btn' => false, 
                            'back_url'=> $back_url
                        ]); 
                    ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php $this->load->view('anexo/index_form_modal', ["show_header" => false]); ?>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
