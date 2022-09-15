<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url('ativo_configuracao/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Configurações de Ativos</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table 
                            class="table table-borderless table-striped table-earning" 
                            id="ativo_configuracao_index"
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
            name: 'configuracao.id_ativo_configuracao',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.id_link
            }
        },
        { 
            title: 'Título' ,
            name: 'configuracao.titulo',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.titulo_link
            }
        },
        { 
            title: 'Categoria' ,
            name: 'ac.titulo',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.categoria_html
            }
        },
        { 
            title: 'Situação',
            sortable: true,
            searchable: true,
            name: 'configuracao.situacao',
            render: function(value, type, row, settings){
                return row.situacao_html
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
        url: `ativo_configuracao`,
        method: 'post',
        order: [1, 'desc'],
    }

    $(window).ready(() => loadDataTable('ativo_configuracao_index', options))
    $(window).resize(() => loadDataTable('ativo_configuracao_index', options))
</script>