<!-- MAIN CONTENT-->
<div id="anexo_index" class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap"> <?php if (!$modulo) {
                            $url = "";
                            if (isset($id_modulo)) {
                                $url .= "/{$id_modulo}";
                                if (isset($id_modulo_item)) {
                                    $url .= "/{$id_modulo_item}";
                                    if (isset($id_modulo_subitem)) {
                                        $url .= "/{$id_modulo_subitem}";
                                    }
                                }
                            }
                        ?>
                            <a href="<?php echo base_url("anexo/adicionar{$url}"); ?>">
                                <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-plus"></i>Adicionar</button>
                            </a>
                        <?php }  
                            if($modulo && $refer) {
                                $url = "{$modulo->rota}";
                                if (isset($id_modulo)) {
                                    $url .= "/{$id_modulo}";
                                    if (isset($id_modulo_item)) {
                                        $url .= "/{$id_modulo_item}";
                                        if (isset($id_modulo_subitem)) {
                                            $url .= "/{$id_modulo_subitem}";
                                        }
                                    }
                                }    
                        ?>
                            <a href="<?php echo $refer; ?>">
                                <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-arrow-left"></i>Voltar</button>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Anexos</h2>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th>Prévia</th>
                                    <th>ID</th>
                                    <th>Modulo</th>
                                    <th>Tipo</th>
                                    <th>Data de Inclusão</th>
                                    <th>Gerenciar</th>
                                    <th>Titulo</th>
                                    <th>Descrição</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($anexos as $anexo) {?>
                                <tr id="<?php echo "anexo-{$anexo->id_anexo}"; ?>">
                                    <td class="preview"> 
                                        <a target="_black" href="<?php echo base_url("assets/uploads/{$anexo->anexo}"); ?>">
                                        <?php 
                                            $path = APPPATH."../assets/uploads/";
                                            $file = $path.$anexo->anexo;
                                            if (file_exists($file) && explode('/', mime_content_type($file))[0] == "image") { 
                                        ?>
                                            <img src="<?php echo base_url("assets/uploads/{$anexo->anexo}");?>" />
                                        <?php }  elseif(file_exists($file) && mime_content_type($file) == "application/pdf") { ?>
                                            <embed
                                                src="<?php echo base_url("assets/uploads/{$anexo->anexo}");?>"
                                                type="application/pdf"
                                                frameBorder="0"
                                                scrolling="auto"
                                                height="100%"
                                                width="110%"
                                            ></embed>
                                        <?php } else { ?>
                                            <i class="fa fa-file" aria-hidden="true"></i>
                                        <?php } ?>
                                        </a>
                                    </td>
                                    <td><?php echo $anexo->id_anexo; ?></td>
                                    <td><?php echo $anexo->modulo_titulo; ?></td>
                                    <td><?php echo $this->anexo_model->get_anexo_tipo($anexo->tipo)['nome'];?></td>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($anexo->data_inclusao)); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupCertificadoAnexo" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupCertificadoAnexo">
                                                <a class="dropdown-item" target="_black" href="<?php echo base_url("assets/uploads/{$anexo->anexo}"); ?>"><i class="fa fa-eye"></i> Visualizar</a>
                                                <a class="dropdown-item" download href="<?php echo base_url("assets/uploads/{$anexo->anexo}"); ?>"><i class="fa fa-download"></i> Baixar</a>
                                                <?php 
                                                    $startWithAnexo = strripos($anexo->anexo, 'anexo') > -1 && strripos($anexo->anexo, 'anexo') !== false;
                                                    if (($anexo->id_usuario == $user->id_usuario) && $startWithAnexo) {
                                                ?>
                                                    <a  
                                                        class="dropdown-item deletar_registro" 
                                                        href="javascript:void(0)" 
                                                        data-href="<?php echo base_url("anexo/deletar/{$anexo->id_anexo}"); ?>" 
                                                        data-registro="<?php echo $anexo->id_anexo;?>" 
                                                        data-tabela="anexo"
                                                    ><i class="fa fa-trash"></i> Excluir</a>
                                                <?php } ?> 
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo $anexo->titulo; ?></td>
                                    <td><?php echo $anexo->descricao; ?></td>
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


