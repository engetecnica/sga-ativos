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
                        <table 
                            class="table table-borderless table-striped table-earning" 
                            id="ativo_veiculo_manutencao_index"
                        ></table>
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
            title: 'KM Atual Na Execução do Serviço' ,
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
            title: 'Horimetro Atual Na Execução do Serviço' ,
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
        url: `ativo_veiculo/manutencao/${veiculo.id_ativo_veiculo}/paginate`,
        method: 'get',
        order: [0, 'desc'],
    }

    $(window).ready(() => loadDataTable('ativo_veiculo_manutencao_index', options))
    $(window).resize(() => loadDataTable('ativo_veiculo_manutencao_index', options))
</script>
