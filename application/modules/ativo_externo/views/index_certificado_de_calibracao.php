
<?php if(false) { ?>
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

                    <?php if(isset($detalhes->certificado_de_calibracao) && isset($detalhes->data_inclusao)) { ?>
                    <div class="card">
                        <div class="card-header">Certificado Atual</div> 
              
                        <div class="card-body">
                            <table class="table table-responsive-md table-striped table-bordered" style="min-height: 180px;">
                                <tr>
                                    <th width="5%">Data Inclusão</th>
                                    <th width="5%">Data Vencimento</th>
                                    <th width="20%">Anexo</th>
                                </tr>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($detalhes->data_inclusao)); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($detalhes->data_validade)); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupCertificadoAnexo" type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar Anexo
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupCertificadoAnexo">
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " target="_black" href="<?php echo base_url("assets/uploads/certificado_de_calibracao/{$detalhes->certificado_de_calibracao}"); ?>"><i class="fa fa-eye"></i> Visualizar</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " download href="<?php echo base_url("assets/uploads/certificado_de_calibracao/{$detalhes->certificado_de_calibracao}"); ?>"><i class="fa fa-download"></i> Baixar</a>
                                                <div class="dropdown-divider"></div>
                                                <a  
                                                    class="dropdown-item  deletar_registro" 
                                                    href="javascript:void(0)" 
                                                    data-href="<?php echo base_url("ativo_externo/certificado_de_calibracao_deletar/{$detalhes->id_certificado}"); ?>" 
                                                    data-tabela="<?php echo "ativo_externo/certificado_de_calibracao/{$detalhes->id_ativo_externo}"; ?>"
                                                ><i class="fa fa-trash"></i>Excluir</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item " href="<?php echo base_url("anexo/index/12/{$detalhes->id_ativo_externo}"); ?>"><i class="fa fa-files-o"></i> Todos</a>          
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

                                <?php if (isset($detalhes) && isset($detalhes->id_certificado)) {?>
                                    <input type="hidden" id="id_certificado" name="id_certificado" value="<?php echo $detalhes->id_certificado;?>" />
                                <?php } ?>
                              
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="certificado_de_calibracao" class=" form-control-label">Anexar Certificado</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <input required="required" type="file" id="certificado_de_calibracao" name="certificado_de_calibracao" class="form-control" accept="application/pdf, image/*, application/vnd.ms-excel" style="margin-bottom: 5px;"> 
                                        <small size='2'>Formatos aceito: <strong>*.PDF, *.XLS, *.XLSx, *.JPG, *.PNG, *.JPEG, *.GIF</strong></small>
                                        <small size='2'>Tamanho Máximo: <strong><?php echo $upload_max_filesize;?></strong></small>
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="data_validade" class=" form-control-label">Validade</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input required="required" type="date" id="data_validade" name="data_validade" class="form-control" style="margin-bottom: 5px;"> 
                                    </div>
                                </div>

                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-arrow-right"></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>

                                    <a href="<?php echo base_url("ativo_externo/editar/{$detalhes->id_ativo_externo}");?>" class="m-t-10">
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

            <?php if (isset($anexos)) { ?>
            <div id="anexos" class="row">
                <div class="col-12">
                    <?php $this->load->view('anexo/index', ['show_header' => false, 'permit_delete' => true, 'permit_add_btn' => false]); ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php $this->load->view('anexo/index_form_modal', ["show_header" => false]); ?>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->

<script>
var ativo_externo_form = new Vue({
    el: "#ativo_externo_form",
    data(){
        return {
            necessita_calibracao: 0,
        }
    }
})
</script>

<?php } ?>



<!-- MAIN CONTENT-->
<div class="main-content" id="ativo_externo_form">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url("ativo_externo/editar/{$ativo->id_ativo_externo}"); ?>">
                          <button class="au-btn au-btn-icon au-btn--blue">
                            <i class="fa fa-arrow-left"></i>Voltar ao Ativo
                          </button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25"> Certificado de Calibração / Aferição | <?php echo  $ativo->nome. " - ".$ativo->codigo; ?></h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th width="7%">ID</th>
                                    <th>Certificado</th>
                                    <th>Data de Inclusão</th>
                                    <th>Data de Vencimento</th>
                                    <th>Situação</th>
                                    <th>Observação</th>
                                    <th class="text-right">Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr>
                                    <td><?php echo $valor->id_certificado; ?></td>
                                    <?php $this->load->view('anexo/anexo_preview', ['preview_content_tag' => 'td', 'anexo' => $valor->certificado_de_calibracao]); ?>
                                    <td><?php echo $this->formata_data($valor->data_inclusao); ?></td>
                                    <td><?php echo $this->formata_data($valor->data_vencimento); ?></td>
                                    <td>
                                      <?php 
                                        $text = $valor->vigencia == 1 ? "Vigente" : "Expirado";
                                        $class = $valor->vigencia == 1 ? "info" : "warning";
                                      ?>
                                      <span class="badge badge-<?php echo $class; ?>"><?php echo $text; ?></span>
                                    </td>
                                    <td><?php echo $valor->observacao ?: '-'; ?></td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <button 
                                                class="btn btn-secondary btn-sm dropdown-toggle" 
                                                type="button"
                                                data-toggle="dropdown" 
                                                aria-haspopup="true" 
                                                aria-expanded="false"
                                            >
                                                Gerenciar
                                            </button>
                                            <div class="dropdown-menu">
                                                <?php if(isset($valor->certificado_de_calibracao)) { ?>
                                                <a class="dropdown-item" target="_blank" href="<?php echo base_url("assets/uploads/{$valor->certificado_de_calibracao}"); ?>"><i class="fas fa-eye"></i> Visualizar</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" download  href="<?php echo base_url("assets/uploads/{$valor->certificado_de_calibracao}"); ?>"><i class="fas fa-download"></i> Baixar</a>
                                                <?php } ?>

                                                <?php if(isset($valor->certificado_de_calibracao) && $valor->vigencia) { ?>
                                                <div class="dropdown-divider"></div>
                                                <?php } ?>

                                                <?php if($valor->vigencia) { ?>
                                                <a class="dropdown-item " href="<?php echo base_url("ativo_externo/certificado_de_calibracao/{$ativo->id_ativo_externo}/{$valor->id_certificado}"); ?>"><i class="fas fa-edit"></i> Editar</a>
                                                <div class="dropdown-divider"></div>
                                                <a 
                                                  href="javascript:void(0)" 
                                                  data-href="<?php echo base_url("ativo_externo/certificado_de_calibracao_deletar/{$ativo->id_ativo_externo}/{$valor->id_certificado}"); ?>" 
                                                  data-registro="<?php echo $ativo->id_ativo_externo;?>" 
                                                  data-tabela="<?php echo "ativo_externo/certificado_de_calibracao/{$ativo->id_ativo_externo}";?>" class="dropdown-item  deletar_registro"
                                                >
                                                  <i class="fas fa-trash"></i> Excluir
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
                <div class="col-12 col-md-12 text-center">
                  <a href="<?php echo base_url("ativo_externo/certificado_de_calibracao/{$ativo->id_ativo_externo}/adicionar"); ?>">
                    <button class="btn-custom">
                      <i class="zmdi zmdi-plus"></i>&nbsp;Adicionar
                    </button>
                  </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
