<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <?php $id = isset($data) && isset($data->id_insumo) ? "#configuracao-{$data->id_insumo}" : ''?>
                        <a href="<?php echo base_url("insumo{$id}"); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Retirada de Insumo</h2>

                    <div class="card">
                        <div class="card-header">
                            <?php echo isset($detalhes) && isset($detalhes->id_insumo) ? 'Editar Retirada de Insumo' : 'Nova Retirada de Insumo' ?>
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url('insumo/retirada/salvar'); ?>" method="post"
                                enctype="multipart/form-data">

                                <?php if(isset($detalhes) && isset($detalhes->id_insumo)){?>
                                <input type="hidden" name="id_insumo" id="id_insumo"
                                    value="<?php echo $detalhes->id_insumo; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_funcionario" class=" form-control-label">Funcionário</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select data-placeholder="Selecione o Funcionário" id="id_funcionario" name="id_funcionario"
                                            class="form-control select2" required>
                                            <option></option>
                                            <?php foreach($funcionario as $f){ ?>
                                            <option
                                                value="<?php echo $f->id_funcionario ?>">
                                                <?php echo $f->matricula; ?> - <?php echo $f->nome; ?> 
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>                                   
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-12">
                                        <div class="table-sm table--no-card table-responsive table--no- m-b-40">
                                            <table class="table table-borderless table-striped table-earning ">
                                                <thead>
                                                    <tr>
                                                        <th width="15%">Código Insumo</th>
                                                        <th>Insumo</th>
                                                        <th>Estoque</th>
                                                        <th width="10%">Quantidade</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($insumo as $i){ ?>
                                                    <tr>
                                                        <td><?php echo $i->codigo_insumo; ?></td>
                                                        <td><?php echo $i->titulo; ?></td>
                                                        <td><?php echo $i->entrada - $i->saida . ' ' . $i->medicao_sigla; ?></td>
                                                        <td><input type="number" class="form-control" value="0" name="quantidade[<?php echo $i->id_insumo; ?>]" id="quantidade" max="<?php echo $i->entrada - $i->saida; ?>" min="0"></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary submit-form">
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url("insumo{$id}");?>">
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

          



        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
