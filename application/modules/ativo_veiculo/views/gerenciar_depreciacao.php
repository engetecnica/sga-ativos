<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <?php if ($this->ativo_veiculo_model->permit_add_depreciacao($id_ativo_veiculo)) {?>
                            <a href="<?php echo base_url("ativo_veiculo/gerenciar/depreciacao/adicionar/{$id_ativo_veiculo}"); ?>">
                                <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-plus"></i>Adicionar</button>
                            </a>
                        <?php } else {?>
                            <a href="<?php echo base_url("ativo_veiculo/depreciacao_atualizar/{$id_ativo_veiculo}"); ?>">
                                <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-swap-vertical"></i>Atualizar</button>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="overview-wrap m-t-10">
                        <a href="<?php echo base_url("ativo_veiculo"); ?>">
                        <button class="">
                        <i class="zmdi zmdi-arrow-left"></i>&nbsp;Listar Todos os Veículos</button></a>
                    </div>
                    <div class="overview-wrap m-t-10">
                        <a href="<?php echo base_url("ativo_veiculo/editar/{$id_ativo_veiculo}"); ?>">
                        <button class="">
                        <i class="zmdi zmdi-arrow-left"></i>&nbsp;Editar Veículo</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Depreciação</h2>
                    <div  class="table-responsive m-b-40">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th width="15%">Veículo</th>
                                    <th width="15%">Placa/ ID Interno (Máquina)</th>
                                    <th width="15%">Código Fipe</th>
                                    <th width="15%">Ano</th>
                                    <th>Valor de Aquisição</th>
                                    <th>Valor Depeciado</th>
                                    <th>Inclusão</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $veiculo->veiculo; ?></td>
                                    <td><?php echo $veiculo->veiculo_placa ?: $veiculo->id_interno_maquina; ?></td>
                                    <td><?php echo $veiculo->codigo_fipe; ?></td>
                                    <td><?php echo $veiculo->ano; ?></td>
                                    <td><?php echo $this->formata_moeda($veiculo->valor_fipe); ?></td>
                                    <td><?php echo $this->formata_moeda(isset($lista->total) ? $lista->total : 0); ?></td>
                                    <td><?php echo $this->formata_data($veiculo->data); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="section__content section__content--p30">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th width="15%">ID Depreciação</th>
                                    <th width="20%">Mês Referência</th>
                                    <th width="20%">Valor Fipe</th>
                                    <th width="15%">Data de Inclusão</th>
                                    <th width="15%">Depreciação em % *</th>
                                    <th width="15%">Depreciação em R$ *</th>
                                    <th>Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista->data as $l => $valor) {?>

                                <tr>
                                    <td><?php echo $valor->id_ativo_veiculo_depreciacao; ?></td>
                                    <td><?php echo $this->formata_mes_referecia($valor->fipe_mes_referencia, $valor->fipe_ano_referencia); ?></td>
                                    <td><?php echo $this->formata_moeda($valor->fipe_valor); ?></td>
                                    <td><?php echo $this->formata_data_hora($valor->data); ?></td>
                                    <td style="<?php echo $valor->direcao === 'up' ? "color: green;" : "color: red;" ;?>">
                                        <?php echo $valor->direcao === 'up' ? "+ " : "- " ; echo "{$valor->depreciacao_porcentagem} %"; ?>
                                    </td>
                                    <td style="<?php echo $valor->direcao === 'up' ? "color: green;" : "color: red;" ;?>">
                                        <?php echo $valor->direcao === 'up' ? "+ " : "- " ; echo $this->formata_moeda($valor->depreciacao_valor); ?>
                                    </td>
                                    <?php if($valor->permit_edit || $valor->permit_delete) { ?>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupGerenciarDepreciacao" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupGerenciarDepreciacao">
                                                <?php if($valor->permit_edit){ ?>
                                                    <a class="dropdown-item btn" href="<?php echo base_url("ativo_veiculo/gerenciar/depreciacao/editar/{$valor->id_ativo_veiculo}/{$valor->id_ativo_veiculo_depreciacao}");?>">
                                                    <i class="fa fa-edit"></i>Editar
                                                    </a>
                                                <?php } ?>

                                                <?php if($valor->permit_delete){ ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a href="javascript:void(0)" data-href="<?php echo base_url("ativo_veiculo/depreciacao_deletar/{$valor->id_ativo_veiculo}/{$valor->id_ativo_veiculo_depreciacao}"); ?>" 
                                                        data-registro="<?php echo $valor->id_ativo_veiculo;?>" data-redirect="true"
                                                        data-tabela="ativo_veiculo/gerenciar/depreciacao/<?php echo $valor->id_ativo_veiculo; ?>" class="dropdown-item btn deletar_registro">
                                                        <i class="fa fa-trash"></i> Excluir</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </td>
                                    <?php } else { ?>
                                    <td> - </td>
                                    <?php } ?>
                                </tr>
                               <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <small style="text-align: center; text-justify: justify-all; width: 80%; margin: 0 auto;"> 
                    * Valor depreciado em relação ao mês anterior caso haja registro. senão ouver registro, 
                    o valor será calculado a partir do valor de aquisição do bem.
                    Esses valores podem ser positivos ou negativos de acordo com a direção de depreciação do bem.
                    Se o valor do bem cai, teremos um valor negativo em relação ao registro anterior.
                </small>
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

