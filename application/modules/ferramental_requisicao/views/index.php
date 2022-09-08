<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url('ferramental_requisicao/adicionar'); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-plus"></i>&nbsp;
                                <?php if ($user->nivel == 1) { ?>
                                    Nova Transferência
                                <?php } if ($user->nivel == 2) { ?>
                                    Nova Requisição
                                <?php }  ?>
                            </button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Requisição de Ferramentas</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table 
                            class="table table-borderless table-striped table-earning border-bottom-1" 
                            id="ferramental_requisicao_index"
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
            title: 'Requisão ID',
            name: 'requisicao.id_requisicao',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.id_link
            }
        },
        { 
            title: 'Tipo' ,
            name: 'requisicao.tipo',
            sortable: true,
            searchable: false,
            render: function(value, type, row, settings){
                return row.tipo_html
            }
        },
        { 
            title: 'Status',
            sortable: true,
            searchable: true,
            name: 'status.texto',
            render: function(value, type, row, settings){
                return row.status_html
            }
        },
        { 
            title: 'Origem' ,
            name: 'origem.codigo_obra',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.origem
            }
        },
        { 
            title: 'Destino' ,
            name: 'destino.codigo_obra',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.destino
            }
        },
        { 
            title: 'Solicitante' ,
            data: 'solicitante.nome',
            name: 'solicitante.usuario',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.solicitante
            }
        },
        { 
            title: 'Despachante' ,
            data: 'despachante.nome',
            name: 'despachante.usuario',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.despachante
            }
        },
        { 
            title: 'É uma requisição complementar?' ,
            name: 'requisicao.id_requisicao_mae',
            sortable: true,
            searchable: false,
            render: function(value, type, row, settings){
                return row.complementar_html
            }
        },
        { 
            title: 'Complementa' ,
            name: 'requisicao.id_requisicao_mae',
            sortable: true,
            searchable: false,
            render: function(value, type, row, settings){
                return row.complementa_html
            }
        },
        { 
            title: 'Data' ,
            name: 'requisicao.data_inclusao',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.data_inclusao
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
        url: `ferramental_requisicao/index/paginate`,
        order: [0, 'desc'],
    }

    $(window).ready(() => loadDataTable('ferramental_requisicao_index', options))
    $(window).resize(() => loadDataTable('ferramental_requisicao_index', options))
</script>