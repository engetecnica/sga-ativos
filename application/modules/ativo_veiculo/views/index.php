<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <?php if($this->permitido($permissoes, 9, 'adicionar')){ ?>
                            <a href="<?php echo base_url('ativo_veiculo/adicionar'); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                            <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Veículos</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table 
                            class="table table-borderless table-striped table-earning" 
                            id="ativo_veiculo_index" 
                            style="min-height: 450px !important;"
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
    const data_table_columns = [
        {
            title: 'ID',
            name: 'id_ativo_veiculo',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.id_link
            }
        },
        { 
            title: 'Obra' ,
            name: 'ob.codigo_obra',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.obra_html
            }
        },
        { 
            title: 'Placa / ID Interna' ,
            name: 'veiculo_placa',
            data: 'id_interno_maquina',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.veiculo_identificacao_link
            }
        },
        { 
            title: 'Veículo' ,
            name: 'modelo',
            data: 'marca',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.veiculo_descricao
            }
        },
        { 
            title: 'Tipo' ,
            name: 'tipo_veiculo',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.tipo_veiculo
            }
        },
        { 
            title: 'Situação',
            sortable: true,
            searchable: true,
            name: 'ativo_veiculo.situacao',
            render: function(value, type, row, settings){
                console.log(row.situacao_html)
                return row.situacao_html
            }
        },
        { 
            title: 'Tabela Fipe' ,
            name: 'valor_fipe',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.valor_fipe_html
            }
        },
        { 
            title: 'Referência' ,
            name: 'fipe_mes_referencia',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.fipe_mes_referencia
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
        url: `ativo_veiculo`,
        method: 'post',
        order: [2, 'asc'],
    }

    $(window).ready(() => loadDataTable('ativo_veiculo_index', options))
    $(window).resize(() => loadDataTable('ativo_veiculo_index', options))
</script>
