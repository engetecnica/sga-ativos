<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url("ativo_veiculo/manutencao/{$id_ativo_veiculo}/adicionar"); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
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

            <?php
            $this->load->view('gerenciar_top', [
                "permissoes" => $permissoes,
                "veiculo" => $veiculo
            ]);
            ?>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Manutenções</h2>
                    <div class="table-responsive table--no-card m-b-40">

                        <div class="table-responsive-md table--no-card" style="margin-bottom: 100px;">
                            <table class="table table-borderless table-striped table-earning dataTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fornecedor</th>
                                        <th>Serviço</th>
                                        <th>Custo</th>
                                        <th>KM Atual</th>
                                        <th>KM Próxima Revisão</th>
                                        <th>Horimetro Na Revisão</th>
                                        <th>Horimetro Próxima Revisão</th>
                                        <th>Data de Entrada</th>
                                        <th>Data de Saída</th>
                                        <th>Data de Vencimento</th>
                                        <th>Gerenciar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($manutencao_lista as $lista) { ?>
                                        <tr>
                                            <td><?php echo $lista->id_ativo_veiculo_manutencao; ?></td>
                                            <td><?php echo $lista->id_fornecedor; ?></td>
                                            <td><?php echo $lista->id_ativo_configuracao; ?></td>
                                            <td><?php echo $this->formata_moeda($lista->veiculo_custo ?? 0); ?></td>
                                            <td><?php echo $lista->veiculo_km_atual; ?></td>
                                            <td><?php echo $lista->veiculo_km_proxima_revisao; ?></td>
                                            <td><?php echo $lista->veiculo_horimetro_atual; ?></td>
                                            <td><?php echo $lista->veiculo_horimetro_proxima_revisao; ?></td>
                                            <td><?php echo $this->formata_data($lista->data_entrada); ?></td>
                                            <td><?php echo $this->formata_data($lista->data_saida); ?></td>
                                            <td><?php echo $this->formata_data($lista->data_vencimento); ?></td>
                                            <td>
                                                <?php if (!isset($lista->data_saida) || $user->nivel == 1) { ?>
                                                    <div class="btn-group" role="group">
                                                        <button id="btnGroupGerenciarManutencao" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Gerenciar
                                                        </button>

                                                        <div class="dropdown-menu" aria-labelledby="btnGroupGerenciarManutencao">
                                                            <a class="dropdown-item " href="<?php echo base_url("ativo_veiculo/manutencao/{$lista->id_ativo_veiculo}/{$lista->id_ativo_veiculo_manutencao}"); ?>">
                                                                <i class="fa fa-edit"></i> Editar
                                                            </a>
                                                            <?php if (!$lista->data_saida && isset($lista->ordem_de_servico)) { ?>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item  confirmar_registro" data-tabela="<?php echo base_url("ativo_veiculo/manutencao/{$lista->id_ativo_veiculo}"); ?>" href="javascript:void(0)" data-registro="" data-acao="Marca como Finalizada" data-redirect="true" data-href="<?php echo base_url("ativo_veiculo/manutencao_saida/{$lista->id_ativo_veiculo}/{$lista->id_ativo_veiculo_manutencao}"); ?>">
                                                                    <i class="fa fa-check"></i>&nbsp; Marca como Finalizada
                                                                </a>
                                                            <?php } ?>
                                                            <?php if (isset($lista->comprovante) && $lista->comprovante != null) { ?>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="<?php echo base_url("ativo_veiculo/manutencao/{$lista->id_ativo_veiculo}/{$lista->id_ativo_veiculo_manutencao}#anexos"); ?>">
                                                                    <i class="fa fa-files-o"></i>&nbsp; Anexos
                                                                </a>
                                                            <?php } ?>
                                                            <div class="dropdown-divider"></div>
                                                            <a href="javascript:void(0)" data-href="<?php echo base_url("ativo_veiculo/manutencao/{$lista->id_ativo_veiculo}/{$lista->id_ativo_veiculo_manutencao}"); ?>" data-registro="<?php echo $lista->id_ativo_veiculo; ?>" data-redirect="true" data-tabela="ativo_veiculo/manutencao/<?php echo $lista->id_ativo_veiculo; ?>" class="dropdown-item  deletar_registro"><i class="fa fa-trash"></i> Excluir</a>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->