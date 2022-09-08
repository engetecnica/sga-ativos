<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url("ativo_veiculo/depreciacao/{$id_ativo_veiculo}/adicionar"); ?>">
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
                    <h2 class="title-1 m-b-25">Gerenciar Depreciação</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table 
                            class="table table-borderless table-striped table-earning" 
                            id="ativo_veiculo_depreciacao_index"
                        ></table>
                    </div>
                </div>
                <small style="text-align: center; text-justify: justify-all; width: 80%; margin: 0 auto;"> 
                    * Valor depreciado em relação ao mês anterior caso haja registro. senão ouver registro, 
                    o valor será calculado a partir do valor de aquisição do bem.
                    Esses valores podem ser positivos ou negativos de acordo com a direção de depreciação do bem.
                    Se o valor do bem cai, teremos um valor negativo em relação ao registro anterior.
                </small>
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
            name: 'depreciacao.id_ativo_veiculo_depreciacao',
            searchable: true,
            render: function(value, type, row, settings){
                return row.id_link
            }
        },
        {
            title: 'Mês Referência',
            name: 'depreciacao.fipe_mes_referencia',
            searchable: true,
            render: function(value, type, row, settings){
                return row.fipe_mes_referencia_html
            }
        },
        {
            title: 'Valor Fipe',
            name: 'depreciacao.fipe_valor',
            searchable: true,
            render: function(value, type, row, settings){
                return row.fipe_valor_html
            }
        },
        {
            title: 'Depreciação em % *',
            name: 'depreciacao.id_ativo_veiculo_depreciacao',
            searchable: true,
            render: function(value, type, row, settings){
                return row.depreciacao_porcentagem_html
            }
        },
        {
            title: 'Depreciação em R$ *',
            name: 'depreciacao.id_ativo_veiculo_depreciacao',
            searchable: true,
            render: function(value, type, row, settings){
                return row.depreciacao_valor_html
            }
        },
        { 
            title: 'Data' ,
            name: 'depreciacao.data',
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
        url: `ativo_veiculo/depreciacao/${veiculo.id_ativo_veiculo}/paginate`,
        method: 'get',
        order: [0, 'desc'],
    }

    $(window).ready(() => loadDataTable('ativo_veiculo_depreciacao_index', options))
    $(window).resize(() => loadDataTable('ativo_veiculo_depreciacao_index', options))
</script>
