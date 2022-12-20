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
                    <h2 class="title-1 m-b-25">Insumos</h2>

                    <div class="card">
                        <div class="card-header">
                            <?php echo isset($detalhes) && isset($detalhes->id_insumo) ? 'Editar Insumo' : 'Novo Insumo' ?>
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url('insumo/salvar'); ?>" method="post"
                                enctype="multipart/form-data">

                                <?php if(isset($detalhes) && isset($detalhes->id_insumo)){?>
                                <input type="hidden" name="id_insumo" id="id_insumo"
                                    value="<?php echo $detalhes->id_insumo; ?>">
                                <?php } ?>

                                <?php if(isset($detalhes) && isset($detalhes->id_insumo)){?>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="tipo_insumo" class=" form-control-label">Estoque Atual</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <span class="btn btn-sm btn-primary">
                                            <?php

                                                $entrada_total = 0;
                                                $saida_total = 0;

                                                foreach($detalhes->entrada as $entrada){
                                                    $entrada_total += $entrada->quantidade;
                                                }

                                                foreach($detalhes->saida as $saida){
                                                    if($saida->status!=5){
                                                     $saida_total += $saida->quantidade;
                                                    }
                                                }

                                                echo $entrada_total - $saida_total;
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="tipo_insumo" class=" form-control-label">Tipo do Insumo</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select 
                                            data-placeholder="Selecione um Tipo" 
                                            id="tipo_insumo" 
                                            name="tipo_insumo"
                                            class="form-control select2"
                                        >
                                            <option value=""></option>
                                            <?php foreach($tipo_insumo as $ti){ ?>
                                            <optgroup label="<?php echo $ti->titulo; ?>">
                                                <?php foreach($ti->subitem as $sub){ ?>
                                                <option value="<?php echo $sub->id_insumo_configuracao; ?>"
                                                <?php if(isset($detalhes) && $detalhes->id_insumo_configuracao==$sub->id_insumo_configuracao) echo "selected"; ?>
                                                ><?php echo $sub->codigo_insumo; ?> - <?php echo $sub->titulo; ?></option>
                                                <?php } ?>
                                            </optgroup>
                                            <?php } ?>
                                                  
                                        </select>
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="titulo" class=" form-control-label">Titulo</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" id="titulo" name="titulo" placeholder="Titulo do Insumo"
                                            class="form-control"
                                            value="<?php if(isset($detalhes) && isset($detalhes->titulo)){ echo $detalhes->titulo; } ?>">
                                    </div>
                                </div>

                                <div class="row form-group">

                                    <div class="col col-md-2">
                                        <label for="fornecedor" class=" form-control-label">Fornecedor</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select name="fornecedor" data-placeholder="Selecione o fornecedor"
                                            id="fornecedor" class="form-control select2">
                                            <option></option>
                                            <?php foreach ($fornecedor as $fo) { ?>
                                            <option
                                                <?php echo isset($detalhes->id_fornecedor) && $fo->id_fornecedor == $detalhes->id_fornecedor ? 'selected' : ''?>
                                                value="<?php echo $fo->id_fornecedor; ?>">
                                                <?php echo $fo->razao_social; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row form-group">
                                <?php if(!isset($detalhes)){ ?>
                                    <div class="col col-md-2">
                                        <label for="valor" class=" form-control-label">Valor Unitário</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" id="valor" name="valor" placeholder="0.00"
                                            class="form-control valor"
                                            value="<?php if(isset($detalhes) && isset($detalhes->valor)){ echo $detalhes->valor; } ?>">
                                    </div>
                                
                                    <div class="col col-md-2">
                                        <label for="quantidade" class=" form-control-label">Quantidade</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="number" required="required" id="quantidade" min="1"
                                            name="quantidade" class="form-control"
                                            value="<?php if(isset($detalhes) && isset($detalhes->quantidade)){ echo $detalhes->quantidade; } else { echo "1"; } ?>"
                                            <?php if(isset($detalhes) && isset($detalhes->quantidade)){ echo "disabled"; } ?>>
                                    </div>
                                <?php } ?>

                                   
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="descricao_insumo" class=" form-control-label">Descrição</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <textarea id="descricao_insumo" name="descricao_insumo" placeholder="Descrição"
                                            class="form-control"><?php echo isset($detalhes) && isset($detalhes->descricao) ? $detalhes->descricao : '' ?></textarea>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="situacao" class=" form-control-label">Situação</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <select name="situacao" id="situacao" class="form-control">
                                            <option value="0"
                                                <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao==0){ echo "selected='selected'"; } ?>>
                                                Ativo</option>
                                            <option value="1"
                                                <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao==1){ echo "selected='selected'"; } ?>>
                                                Inativo</option>
                                        </select>
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

            <div class="row">
                <?php if(isset($detalhes) && $detalhes->entrada){ ?>
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Entradas Estoque</h2>                   
                    <div class="table table--no-card table-responsive table--no- m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="insumo_entrada">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuário</th>
                                    <th>Quantidade</th>
                                    <th>Valor Unitário</th>
                                    <th>Data</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach($detalhes->entrada as $entrada){ ?>   
                                <tr>
                                    <td><?php echo $entrada->id_insumo_estoque; ?></td>
                                    <td><?php echo $entrada->nome." - ".$entrada->usuario; ?></td>
                                    <td><?php echo $entrada->quantidade; ?></td>
                                    <td><?php echo  $this->formata_moeda($entrada->valor); ?></td>
                                    <td><?php echo  $this->formata_data_hora($entrada->created_at); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>                            
                        </table>
                    </div>   
                </div>
                <?php } ?>

                <?php if(isset($detalhes) && $detalhes->saida){ ?>
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Saídas Estoque</h2>                   
                    <div class="table table--no-card table-responsive table--no- m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="insumo_saida">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuário</th>
                                    <th>Quantidade</th>
                                    <th>Valor Unitário</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach($detalhes->saida as $saida){ ?>   
                                <tr>
                                    <td><?php echo $saida->id_insumo_estoque; ?></td>
                                    <td><?php echo $saida->nome." - ".$saida->usuario; ?></td>
                                    <td><?php echo $saida->quantidade; ?></td>
                                    <td><?php echo  $this->formata_moeda($saida->valor); ?></td>
                                    <td><?php echo  $this->formata_data_hora($saida->created_at); ?></td>
                                    <td><span class="badge badge-<?php echo ($this->get_situacao_insumo($saida->status)['class']) ?? '-'; ?>"><?php echo ($this->get_situacao_insumo($saida->status)['texto']) ?? '-'; ?></span></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>                            
                        </table>
                    </div>   
                </div>
                <?php } ?>

            </div>




        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
