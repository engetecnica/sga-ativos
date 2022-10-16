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

                        <div class="table-responsive-md table--no-card">
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
                                    <?php foreach($manutencao_lista as $lista){ ?>
                                    <tr>
                                        <td><?php echo $lista->id_ativo_veiculo_manutencao; ?></td>
                                        <td><?php echo $lista->id_fornecedor; ?></td>
                                        <td><?php echo $lista->id_ativo_configuracao; ?></td>
                                        <td><?php echo $this->formata_moeda($lista->veiculo_custo ?? 0); ?></td>
                                        <td><?php echo $lista->veiculo_km_atual; ?></td>
                                        <td><?php echo $lista->veiculo_km_proxima_revisao; ?></td>
                                        <td><?php echo $lista->veiculo_horimetro_atual; ?></td>
                                        <td><?php echo $lista->veiculo_horimetro_proxima_revisao; ?></td>
                                        <td><?php echo $this->formata_data_hora($lista->data_entrada); ?></td>
                                        <td><?php echo $this->formata_data_hora($lista->data_saida); ?></td>
                                        <td><?php echo $this->formata_data_hora($lista->data_vencimento); ?></td>
                                        <td>
                                        <?php if (!isset($lista->data_saida) || $user->nivel == 1) { ?>
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupGerenciarManutencao" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Gerenciar
                                                </button>

                                                <div class="dropdown-menu" aria-labelledby="btnGroupGerenciarManutencao">
                                                    <a class="dropdown-item " href="<?php echo base_url("ativo_veiculo/manutencao/{$lista->id_ativo_veiculo}/{$lista->id_ativo_veiculo_manutencao}");?>">
                                                    <i class="fa fa-edit"></i> Editar
                                                    </a>
                                                    <?php if(!$lista->data_saida && isset($lista->ordem_de_servico)){ ?>
                                                        <div class="dropdown-divider"></div>
                                                        <a
                                                            class="dropdown-item  confirmar_registro" data-tabela="<?php echo base_url("ativo_veiculo/manutencao/{$lista->id_ativo_veiculo}");?>" 
                                                            href="javascript:void(0)" data-registro=""
                                                            data-acao="Marca como Finalizada"  data-redirect="true"
                                                            data-href="<?php echo base_url("ativo_veiculo/manutencao_saida/{$lista->id_ativo_veiculo}/{$lista->id_ativo_veiculo_manutencao}");?>"
                                                        >
                                                        <i class="fa fa-check"></i>&nbsp; Marca como Finalizada
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(isset($lista->comprovante) && $lista->comprovante != null){ ?>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="<?php echo base_url("ativo_veiculo/manutencao/{$lista->id_ativo_veiculo}/{$lista->id_ativo_veiculo_manutencao}#anexos"); ?>">
                                                            <i class="fa fa-files-o"></i>&nbsp; Anexos
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!$lista->data_saida){ ?>
                                                        <div class="dropdown-divider"></div>
                                                        <a href="javascript:void(0)" data-href="<?php echo base_url("ativo_veiculo/manutencao/{$lista->id_ativo_veiculo}/{$lista->id_ativo_veiculo_manutencao}"); ?>" 
                                                            data-registro="<?php echo $lista->id_ativo_veiculo;?>" data-redirect="true"
                                                            data-tabela="ativo_veiculo/manutencao/<?php echo $lista->id_ativo_veiculo; ?>" class="dropdown-item  deletar_registro"><i class="fa fa-trash"></i> Excluir</a>
                                                    <?php } ?>
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
<!-- END PAGE CONTAINER-->
<script>
    const veiculo = JSON.parse(`<?php echo json_encode($veiculo); ?>`)

    console.log(veiculo)
    
    const data_table_columns = [
        {
            title: 'ID',
            name: 'manutencao.id_ativo_veiculo_manutencao',
            searchable: true,
            render: function(value, type, row, settings){
                return row.id_link
            }
        },
        { 
            title: 'Fornecedor' ,
            name: 'fnc.razao_social',
            render: function(value, type, row, settings){
                return row.fornecedor
            }
        },
        { 
            title: 'Serviço' ,
            name: 'config.titulo',
            render: function(value, type, row, settings){
                return row.servico
            }
        },
        { 
            title: 'Custo' ,
            name: 'manutencao.veiculo_custo',
            render: function(value, type, row, settings){
                return row.veiculo_custo_html
            }
        },
        { 
            title: 'KM Atual' ,
            name: 'manutencao.veiculo_km_atual',
            searchable: true,
            render: function(value, type, row, settings){
                return row.veiculo_km_atual || 0
            }
        },
        { 
            title: 'KM Próxima Revisão' ,
            name: 'manutencao.veiculo_km_proxima_revisao',
            searchable: true,
            render: function(value, type, row, settings){
                return row.veiculo_km_proxima_revisao || 0
            }
        },
        { 
            title: 'Horimetro Atual' ,
            name: 'manutencao.veiculo_horimetro_atual',
            searchable: true,
            render: function(value, type, row, settings){
                return row.veiculo_horimetro_atual || 0
            }
        },
        { 
            title: 'Horimetro Próxima Revisão' ,
            name: 'manutencao.veiculo_horimetro_proxima_revisao',
            searchable: true,
            render: function(value, type, row, settings){
                return row.veiculo_horimetro_proxima_revisao || 0
            }
        },
        { 
            title: 'Data Entrada' ,
            name: 'manutencao.data_entrada',
            render: function(value, type, row, settings){
                return row.manutencao_data_entrada_html
            }
        },
        { 
            title: 'Data Saída' ,
            name: 'manutencao.data_saida',
            render: function(value, type, row, settings){
                return row.manutencao_data_saida_html
            }
        },
        { 
            title: 'Data Vencimento' ,
            name: 'manutencao.data_vencimento',
            render: function(value, type, row, settings){
                return row.manutencao_data_vencimento_html
            }
        },
        { 
            title: 'Gerenciar' ,
            render(value, type, row, settings){
                return row.actions
            },
        },
    ]

    const options = {
        columns: data_table_columns,
        url: `ativo_veiculo/manutencao/${veiculo.id_ativo_veiculo}/listagem`,
        method: 'get',
        order: [0, 'desc'],
    }

    $(window).ready(() => loadDataTable('ativo_veiculo_manutencao_index', options))
    $(window).resize(() => loadDataTable('ativo_veiculo_manutencao_index', options))
</script>
