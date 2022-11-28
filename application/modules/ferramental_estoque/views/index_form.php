<!-- MAIN CONTENT-->
<div id="ferramental_estoque_form" class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <?php if(isset($retirada))  { ?>
                        <a href="<?php echo base_url("ferramental_estoque/detalhes/{$retirada->id_retirada}"); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-arrow-left"></i>Detalhes</button></a>
                        <?php } else { ?>
                        <a href="<?php echo base_url('ferramental_estoque'); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Retirada de Ferramentas</h2>

                    <div class="card">
                        <div class="card-header">Nova Retirada</div>
                        <div class="card-body">

                            <form action="<?php echo base_url('ferramental_estoque/salvar'); ?>"
                                enctype="multipart/form-data" method="post">
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_funcionario" class=" form-control-label">Funcionário</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select required="required" class="form-control select2" name="id_funcionario"
                                            id="id_funcionario">
                                            <option value="">Nenhum funcionário selecionado</option>
                                            <?php foreach($lista_funcionario as $funcionario){ ?>
                                            <option value="<?php echo $funcionario->id_funcionario;?>">
                                                <?php echo $funcionario->matricula." - ".$funcionario->nome; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_funcionario" class=" form-control-label">Pesquisar</label>
                                    </div>
                                    <div class="col-10 col-md-6">
                                        <input id="filtro-nome" class="form-control" placeholder="Busque um item"/>
                                    </div>
                                </div>        
                                <hr>

                                <div class="row form-group">
                                    <div class="col-12 col-md-12">
                                        <table class="table table-bordered table-sm table-striped" id="retirada-itens">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Item/Ferramenta</th>
                                                    <th>Patrimônio</th>
                                                    <th>Opção</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($lista_ferramental as $ferramental){ ?>
                                                <tr>
                                                    <td><?php echo $ferramental->id_ativo_externo; ?></td>
                                                    <td><?php echo $ferramental->nome; ?></td>
                                                    <td><?php echo $ferramental->codigo; ?></td>
                                                    <td>
                                                        <!-- Rounded switch -->
                                                        <label class="switch">
                                                            <input type="checkbox" name="ativo[]"
                                                                value="<?php echo $ferramental->id_ativo_externo; ?>">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <hr>
                                <div class="row form-group m-t-40">
                                    <div class="col-12 col-md-2">
                                        <label>Devolução Pevista</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input required="required" type="datetime-local" class="form-control"
                                            name="devolucao_prevista" id="devolucao_prevista">
                                    </div>
                                </div>

                                <div class="row form-group">

                                    <div class="col-12 col-md-2">
                                        <label>Observações</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <textarea rows="5" class="form-control" name="observacoes" id="observacoes"
                                            placeholder="Alguma observação?">
                                        </textarea>
                                    </div>
                                </div>

                                <hr>
                                <div class="pull-left">
                                    <button type="submit" class="btn btn-primary form-salvar-requisicao">
                                        <i class="fa fa-save "></i>&nbsp;
                                        <span id="submit-form">Salvar Requisição</span>
                                    </button>
                                    <a href="<?php echo base_url('ferramental_estoque');?>">
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

<script>
    $(document).ready(function() {
        $(document).ready(function() {
            $("#filtro-nome").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#retirada-itens tr").filter(function() {
                    $(this).toggle($(this).text()
                        .toLowerCase().indexOf(value) > -1)
                });
            });
        });
    });
</script>