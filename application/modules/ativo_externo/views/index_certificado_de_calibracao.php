<!-- MAIN CONTENT-->
<div class="main-content" id="ativo_externo_form">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url("ativo_externo/editar/{$detalhes->id_ativo_externo}"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>Voltar ao Ativo</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Certificado de Calibração</h2>

                    <?php if(isset($detalhes->certificado_de_calibracao) && isset($detalhes->inclusao_certificado)) { ?>
                    <div class="card">
                        <div class="card-header">Certificado Atual</div> 
              
                        <div class="card-body">
                            
                                <table class="table table-responsiv table-striped table-bordered table-s">
                                    <tr>
                                        <th width="5%">Data Inclusão</th>
                                        <th width="5%">Data Vencimento</th>
                                        <th width="20%">Anexo</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($detalhes->inclusao_certificado)); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($detalhes->validade_certificado)); ?></td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupCertificadoAnexo" type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Gerenciar Anexo
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupCertificadoAnexo">
                                                    <a class="dropdown-item" target="_black" href="<?php echo base_url("assets/uploads/certificado_de_calibracao/{$detalhes->certificado_de_calibracao}"); ?>">Visualizar</a>
                                                    <a class="dropdown-item" download href="<?php echo base_url("assets/uploads/certificado_de_calibracao/{$detalhes->certificado_de_calibracao}"); ?>">Baixar</a>
                                                    <a  
                                                        class="dropdown-item deletar_registro" 
                                                        href="javascript:void(0)" 
                                                        data-href="<?php echo base_url("ativo_externo/deletar_certificado_de_calibracao/{$detalhes->id_ativo_externo}"); ?>" 
                                                        data-tabela="<?php echo "ativo_externo/certificado_de_calibracao/{$detalhes->id_ativo_externo}"; ?>"
                                                    >Excluir</a>
                                                    <a class="dropdown-item" href="<?php echo base_url("anexo/index/12/{$detalhes->id_ativo_externo}"); ?>">Ver todos os anexos</a>          
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                        </div>
                    </div>

                    <hr>
                    <?php } ?>

                    <div class="card">
                        <div class="card-header">Novo Certificado</div> 
              
                        <div class="card-body">

                            <form 
                                id="form_certificado_de_calibracao"
                                class="confirm-submit"
                                action="<?php echo base_url("ativo_externo/salvar_certificado_de_calibracao/{$detalhes->id_ativo_externo}"); ?>"
                                method="post" 
                                enctype="multipart/form-data"
                             >
                                <input type="hidden" id="id_ativo_externo" name="id_ativo_externo" value="<?php echo $detalhes->id_ativo_externo;?>" />
                              
                                <div class="row form-group">
                                    <div class="col col-md-4">
                                        <label for="certificado_de_calibracao" class=" form-control-label">Anexar Certificado de Controle</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <input required="required" type="file" id="certificado_de_calibracao" name="certificado_de_calibracao" class="form-control" accept="application/pdf, image/*, application/vnd.ms-excel" style="margin-bottom: 5px;"> 
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
            necessecita_calibracao: 0,
        }
    }
})
</script>