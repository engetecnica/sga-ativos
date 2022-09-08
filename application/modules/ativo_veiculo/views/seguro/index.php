<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url("ativo_veiculo/seguro/{$id_ativo_veiculo}/adicionar"); ?>">
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
                    <h2 class="title-1 m-b-25">Gerenciar Seguro</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table 
                            class="table table-borderless table-striped table-earning" 
                            id="ativo_veiculo_seguro_index"
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
    const data_table_columns = [
        {
            title: 'ID',
            name: 'seguro.id_ativo_veiculo_seguro',
            searchable: true,
            render: function(value, type, row, settings){
                return row.id_link
            }
        },
        { 
            title: 'Custo' ,
            name: 'seguro.seguro_data',
            render: function(value, type, row, settings){
                return row.seguro_custo_html
            }
        },
        { 
            title: 'Carência Inicio' ,
            name: 'seguro.carencia_inicio',
            render: function(value, type, row, settings){
                return row.carencia_inicio_html
            }
        },
        { 
            title: 'Carência Final' ,
            name: 'seguro.carencia_fim',
            render: function(value, type, row, settings){
                return row.carencia_fim_html
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
        url: `ativo_veiculo/seguro/${veiculo.id_ativo_veiculo}/paginate`,
        method: 'get',
        order: [0, 'desc'],
    }

    $(window).ready(() => loadDataTable('ativo_veiculo_seguro_index', options))
    $(window).resize(() => loadDataTable('ativo_veiculo_seguro_index', options))
</script>
