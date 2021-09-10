<!-- MAIN CONTENT-->
<div class="main-content" id="ativo_externo_form">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url("ativo_externo/editar/{$detalhes->id_ativo_externo}"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>Voltar ao Ativo</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Certificado de Calibração</h2>

                    <div class="card">
            
                        <div class="card-header">Novo Certificado</div>     
              
                        <div class="card-body">
                            <form 
                                action="<?php echo base_url("ativo_externo/salvar_certificado_de_calibacao/{$detalhes->id_ativo_externo}"); ?>"
                                method="post" 
                                enctype="multipart/form-data"
                             >
                                <input type="hidden" id="id_ativo_externo" name="id_ativo_externo" value="<?php echo $detalhes->id_ativo_externo;?>" />
                                <input type="hidden" id="necessecitam_calibracao" name="necessecitam_calibracao" value="1" />
                                    
                                
                                <div class="row form-group">
                                    <div class="col col-md-4">
                                        <label for="certificado_de_calibacao" class=" form-control-label">Anexar Certificado de Controle</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <input required="required" type="file" id="certificado_de_calibacao" name="certificado_de_calibacao" class="form-control" accept="application/pdf, image/*, application/vnd.ms-excel" style="margin-bottom: 5px;"> 
                                        <small size='2'>Formato aceito: <strong>*.PDF, *.XLS, *.XLSx, *.JPG, *.PNG, *.JPEG, *.GIF</strong></small>
                                        <small size='2'>Tamanho Máximo: <strong><?php echo $upload_max_filesize;?></strong></small>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-4">
                                        <label for="validade_certificado" class=" form-control-label">Validade do Certificado</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <input required="required" type="date" id="validade_certificado" name="validade_certificado" class="form-control" style="margin-bottom: 5px;"> 
                                    </div>
                                </div>

                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-arrow-right"></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>

                                    <a href="<?php echo base_url("ativo_externo/editar/{$detalhes->id_ativo_externo}");?>" class="m-t-10">
                                    <button class="btn btn-info" type="button">                                                    
                                        <i class="fa fa-remove "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>                                                
                                    </a>
                                </div>

                                <div class="pull-right">
                                    <a href="<?php echo base_url("anexo/index/12/{$detalhes->id_ativo_externo}"); ?>">
                                        <button type="button" class="btn btn-outline-primary">Ver outros anexos</button>
                                    </a>
                                </div>
                            </form>

                        </div>
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

var ativo_externo_form = new Vue({
    el: "#ativo_externo_form",
    data(){
        return {
            necessecitam_calibracao: 0,
        }
    }
})
</script>