<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url("ativo_veiculo/quilometragem/{$id_ativo_veiculo}/adicionar"); ?>">
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
                    <h2 class="title-1 m-b-25">Gerenciar Quilometragem</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table 
                            class="table table-borderless table-striped table-earning" 
                            id="ativo_veiculo_quilometragem_index"
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
            name: 'km.id_ativo_veiculo_quilometragem',
            searchable: true,
            render: function(value, type, row, settings){
                return row.id_link
            }
        },
        { 
            title: 'Km Atual' ,
            name: 'km.veiculo_km',
            render: function(value, type, row, settings){
                return row.veiculo_km_link
            }
        },
        { 
            title: 'Data' ,
            name: 'km.data',
            searchable: true,
            render: function(value, type, row, settings){
                return row.data_html
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
        url: `ativo_veiculo/quilometragem/${veiculo.id_ativo_veiculo}/paginate`,
        method: 'get',
        order: [0, 'desc'],
    }

    $(window).ready(() => loadDataTable('ativo_veiculo_quilometragem_index', options))
    $(window).resize(() => loadDataTable('ativo_veiculo_quilometragem_index', options))
</script>
