<!-- MAIN CONTENT-->
<?php 
    $show_add_btn = isset($permit_add_btn) ? $permit_add_btn : true;
    $show_delete_btn = isset($permit_delete_btn) ? $permit_delete_btn : true;
    $redirect_to = isset($back_url) ? "?redirect_to={$back_url}" : "";
    $url = "";
    if ($modulo) {
        $url .= "/{$modulo->rota}";
        if (isset($id_item)) 
            $url .= "/{$id_item}";
            if (isset($tipo)) $url .= "/{$tipo}";
            if (isset($id_subitem)) $url .= "/{$id_subitem}";
    }
?>

<?php if (!isset($show_header) || $show_header === true) { ?>
<div id="anexos" class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap"> 
                        <?php if (!$modulo && !isset($back_url)) {?>
                            <a href="<?php echo base_url("anexo/adicionar{$url}"); ?>">
                                <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-plus"></i>Adicionar</button>
                            </a>
                        <?php } if(isset($back_url)) { ?>
                            <a href="<?php echo base_url($back_url); ?>">
                                <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-arrow-left"></i>Voltar</button>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
<?php } ?>

    <div class="row m-b-25 m-t-40">
        <div class="col-12 col-md-6">
            <h2 class="title-1 m-b-10 col-10">Anexos</h2> 
        </div>
        <div class="col-12 col-md-6">
            <?php if (isset($show_header) && $show_header === false && $show_add_btn === true) { ?>
                <button class="pull-right btn btn-secondary" onclick="addAnexo()">
                    <i class="fa fa-files-o"></i>&nbsp;Adicionar Anexo
                </button>
            <?php } ?>
        </div>
    </div>
            
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive table--no-card m-b-40">
                <table class="table table-borderless table-striped table-earning" id="lista7">
                    <thead>
                        <tr>
                            <th>Prévia</th>
                            <?php if (!isset($show_header) || $show_header === true) { ?>
                            <th>ID</th>
                            <th>Modulo</th>
                            <?php } ?>
                            <th>Histórico</th>
                            <th>Titulo</th>
                            <th>Descrição</th>
                            <th>Tipo</th>
                            <th>Data de Inclusão</th>
                            <th>Gerenciar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($anexos as $anexo) {?>
                        <tr id="<?php echo "anexo-{$anexo->id_anexo}"; ?>">
                            <?php 
                                $this->load->view('anexo_preview', ['preview_content_tag' => 'td', 'anexo' => $anexo->anexo]);
                                if (!isset($show_header) || $show_header === true) { 
                            ?>
                                <td><?php echo $anexo->id_anexo; ?></td>
                                <td><?php echo $anexo->modulo_titulo; ?></td>
                            <?php } ?>
                            <td>
                                <?php if(count($anexo->historico) > 0) { ?>
                                <a href="<?php echo base_url('anexo/historico/'.$anexo->id_anexo); ?>"><button class="badge badge-success" type="button">Mostrar Todos</button></a>
                                <?php } else { ?>
                                <button class="badge badge-info" type="button">Desconhecido</button>
                                <?php } ?>
                            </td>
                            <td><?php echo $anexo->titulo; ?></td>
                            <td><?php echo $anexo->descricao; ?></td>
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
                                        <?php if (isset($show_header) && $show_header === false) { ?>
                                        <a class="dropdown-item" onclick="editAnexo({
                                            'id': '<?php echo $anexo->id_anexo; ?>', 
                                            'titulo': '<?php echo $anexo->titulo; ?>', 
                                            'descricao': '<?php echo $anexo->descricao; ?>', 
                                            'tipo': '<?php echo $anexo->tipo; ?>'})" 
                                        ><i class="fa fa-edit"></i> Editar</a>
                                        <?php 
                                            $startWithAnexo = strripos($anexo->anexo, 'anexo') > -1 && strripos($anexo->anexo, 'anexo') !== false || isset($permit_detete) && $permit_detete == true;
                                            if ($show_delete_btn && (($anexo->id_usuario == $user->id_usuario) || $startWithAnexo)) {
                                        ?>
                                            <a  
                                                class="dropdown-item deletar_registro" 
                                                href="javascript:void(0)" 
                                                data-href="<?php echo base_url("anexo/deletar/{$anexo->id_anexo}"); ?>" 
                                                data-registro="<?php echo $anexo->id_anexo;?>" 
                                                data-tabela="<?php echo isset($back_url) ? $back_url : 'anexo' ?>"
                                            ><i class="fa fa-trash"></i> Excluir</a>
                                        <?php } } ?>
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

<?php if (!isset($show_header) || $show_header === true) { ?>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
<?php } ?>



    <!-- Modal Template Histórico de Veículo --> 
    <div id="historico-anexos" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Histórico de Anexo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body historico-anexos-lista">
                    
                </div>
            </div>
        </div>
    </div>

<script>
    $(".historico-anexos").on('click', function(){
        $("#historico-anexos").modal("show");
    })
</script>