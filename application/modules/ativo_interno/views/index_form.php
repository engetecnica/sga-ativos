<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <?php $id = isset($ativo) && isset($ativo->id_ativo_interno) ? "#" . $ativo->id_ativo_interno : ''; ?>
                        <a href="<?php echo base_url("ativo_interno$id"); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Ativo Interno</h2>

                    <div class="card">
                        <div class="card-header">
                            <?php echo isset($ativo) && isset($ativo->id_ativo_interno) ? 'Editar Equipamento' : 'Novo Equipamento' ?>
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ativo_interno/salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if (isset($ativo) && isset($ativo->id_ativo_interno)) { ?>
                                    <input type="hidden" name="id_ativo_interno" id="id_ativo_interno" value="<?php echo $ativo->id_ativo_interno; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_obra" class=" form-control-label">Obra</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select class="form-control select2" name="id_obra" id="id_obra" required="required" <?php echo (isset($ativo) && isset($ativo->situacao)) && ($ativo->situacao > 1) ? 'readonly' : '';  ?>>
                                            <option value="">Nenhuma obra selecionada</option>
                                            <?php foreach ($obras as $value) { ?>
                                                <option <?php echo isset($ativo->id_obra) && $value->id_obra == $ativo->id_obra ? 'selected' : '' ?> value="<?php echo $value->id_obra; ?>">
                                                    <?php echo "{$value->id_obra} - {$value->codigo_obra}"; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="serie" class=" form-control-label">Série</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input required="required" type="text" id="serie" name="serie" placeholder="Número de Série" class="form-control" value="<?php if (isset($ativo) && isset($ativo->serie)) {
                                                                                                                                                                        echo $ativo->serie;
                                                                                                                                                                    } ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="nome" class=" form-control-label">Nome do Ativo</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input <?php echo (isset($ativo) && isset($ativo->situacao)) && ($ativo->situacao > 1) ? 'readonly' : '';  ?> required="required" type="text" id="nome" name="nome" placeholder="Nome do Ativo" class="form-control" value="<?php if (isset($ativo) && isset($ativo->nome)) {
                                                                                                                                                                                                                                                                        echo $ativo->nome;
                                                                                                                                                                                                                                                                    } ?>">
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="marca" class=" form-control-label">Marca</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select name="marca" class="form-control select2">
                                            <option value="0">Selecione uma Marca</option>
                                            <?php foreach ($marcas as $marca) { ?>
                                                <option <?php echo isset($ativo->marca) && $marca->id_ativo_interno_marca == $ativo->marca ? 'selected' : '' ?> value="<?php echo $marca->id_ativo_interno_marca; ?>"><?php echo $marca->titulo; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="valor" class=" form-control-label">Valor Atribuído</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input <?php echo (isset($ativo) && isset($ativo->situacao)) && ($ativo->situacao > 1) ? 'readonly' : '';  ?> required="required" type="text" id="valor" name="valor" placeholder="0.00" class="form-control valor" value="<?php if (isset($ativo) && isset($ativo->valor)) {
                                                                                                                                                                                                                                                                        echo $ativo->valor;
                                                                                                                                                                                                                                                                    } ?>">
                                    </div>
                                    <div class="col col-md-2">
                                        <label for="patrimonio" class=" form-control-label">Patrimônio</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input readonly type="text" id="patrimonio" name="patrimonio" class="form-control" value="<?php if (isset($ativo) && isset($ativo->patrimonio)) {
                                                                                                                                        echo $ativo->patrimonio;
                                                                                                                                    } else {
                                                                                                                                        echo "ENG" . $patrimonio;
                                                                                                                                    } ?>">
                                    </div>
                                    <input type="hidden" required="required" id="quantidade" name="quantidade" value="1" />
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="observacao" class=" form-control-label">Observações</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <textarea <?php echo (isset($ativo) && isset($ativo->situacao)) && ($ativo->situacao > 1) ? 'readonly' : '';  ?> name="observacao" id="observacao" rows="9" placeholder="Observações..." class="form-control">
                                        <?php if (isset($ativo) && isset($ativo->observacao)) {
                                            echo $ativo->observacao;
                                        } ?>
                                        </textarea>
                                    </div>
                                </div>
                                <?php if ((isset($ativo) && isset($ativo->situacao)) && $ativo->situacao <= 1) { ?>
                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="situacao" class=" form-control-label">Situação</label>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <select name="situacao" id="situacao" class="form-control">
                                                <option value="1" <?php if (isset($ativo) && isset($ativo->situacao) && $ativo->situacao == 1) {
                                                                        echo "selected='selected'";
                                                                    } ?>>Inativo</option>
                                                <option value="0" <?php if (isset($ativo) && isset($ativo->situacao) && $ativo->situacao == 0) {
                                                                        echo "selected='selected'";
                                                                    } ?>>Ativo</option>
                                            </select>
                                        </div>
                                    </div>
                                <?php } ?>

                                <hr>
                                <div class="pull-left">
                                    <?php if (((isset($ativo) && isset($ativo->situacao)) && $ativo->situacao <= 1) || !isset($ativo) || isset($ativo) && !isset($ativo->id_ativo_interno)) { ?>
                                        <button class="btn btn-primary">
                                            <i class="fa fa-send "></i>&nbsp;
                                            <span id="submit-form">Salvar</span>
                                        </button>
                                    <?php } ?>
                                    <a href="<?php echo base_url("ativo_interno$id"); ?>">
                                        <button class="btn btn-secondary" type="button">
                                            <i class="fa fa-ban "></i>&nbsp;
                                            <span id="cancelar-form">Cancelar</span>
                                        </button>
                                    </a>
                                </div>

                                <?php if (isset($ativo) && isset($ativo->id_ativo_interno)) { ?>
                                    <div class="pull-right">
                                        <div class="btn-group" role="group">
                                            <button id="ativo_interno_item" type="button" class="btn btn-outline-info btn-md dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar Ativo
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="ativo_interno_item">
                                                <a class="dropdown-item" href="<?php echo base_url("ativo_interno/manutencao/{$ativo->id_ativo_interno}"); ?>">
                                                    <i class="fa fa-wrench"></i>&nbsp; Manutenção
                                                </a>

                                                <?php if ((int) $ativo->situacao < 2) { ?>
                                                    <a href="javascript:void(0)" class="dropdown-item confirmar_registro" data-registro="<?php echo $ativo->id_ativo_interno; ?>" data-href="<?php echo base_url("ativo_interno/descartar/{$ativo->id_ativo_interno}"); ?>" data-tabela="<?php echo base_url("ativo_interno/editar/{$ativo->id_ativo_interno}"); ?>" data-icon="info" data-message="false" data-acao="Descartar" data-title="Confirmar descarte do ativo" data-redirect="true" data-text="Clique 'Sim, Confirmar!' para confirmar o descarte do ativo.">
                                                        <i class="fa fa-ban"></i>&nbsp; Descartar Ativo
                                                    </a>
                                                <?php } ?>

                                                <?php if ((int) $ativo->situacao == 2 && $user->nivel == 1) { ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item  confirmar_registro" href="javascript:void(0)" class="confirmar_registro" data-registro="<?php echo $ativo->id_ativo_interno; ?>" data-href="<?php echo base_url("ativo_interno/desfazer_descarte/{$ativo->id_ativo_interno}"); ?>" data-tabela="<?php echo base_url("ativo_interno/editar/{$ativo->id_ativo_interno}"); ?>" data-icon="info" data-message="false" data-acao="Defazer" data-title="Defazer descarte do ativo" data-redirect="true" data-text="Clique 'Sim, Defazer!' para defazer o descarte do ativo.">
                                                        <i class="fas fa-undo"></i>&nbsp; Defazer descarte
                                                    </a>
                                                <?php } ?>

                                            </div>
                                        </div>

                                    </div>
                                <?php } ?>
                            </form>

                        </div>
                    </div>

                </div>
            </div>

            <?php if ((isset($ativo) && isset($ativo->id_ativo_interno)) && isset($anexos)) { ?>
                <div id="anexos" class="row">
                    <div class="col-12">
                        <?php $this->load->view('anexo/index', ['show_header' => false, 'permit_delete' => true]); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php
if ((isset($ativo) && isset($ativo->id_ativo_interno)) && isset($anexos)) {
    $this->load->view('anexo/index_form_modal', ["show_header" => false]);
}
?>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->