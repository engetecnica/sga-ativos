<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($this->permitido($permissoes, 13, 'adicionar')) { ?>
                        <div class="overview-wrap"> 
                            <a href="<?php echo base_url('insumo/adicionar'); ?>">
                                <button class="au-btn au-btn-icon au-btn--blue2">
                                    <i class="zmdi zmdi-plus"></i>Novo Insumo
                                </button>
                            </a>
                         
                            <a href="<?php echo base_url('insumo/retirada'); ?>">
                                <button class="au-btn au-btn-icon au-btn--blue" style="margin-left: 10px;">
                                    <i class="zmdi zmdi-plus"></i>Nova Retirada
                                </button>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-20">Insumos</h2>
                    <div class="table table--no-card table-responsive table--no- m-b-40">
                        
                        <table 
                            class="table table-borderless table-striped table-earning" 
                            id="insumo_index"
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
            name: 'id_insumo',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.id_link
            }
        },
        { 
            title: 'Titulo' ,
            name: 'titulo',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.titulo_link
            }
        },
        { 
            title: 'Código insumo' ,
            name: 'codigo_insumo',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.codigo
            }
        },
        { 
            title: 'Quantidade' ,
            name: 'quantidade',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.quantidade
            }
        },
        { 
            title: 'Valor' ,
            name: 'valor',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.valor
            }
        },
        { 
            title: 'Função' ,
            name: 'funcao',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.funcao
            }
        },
        { 
            title: 'Composição' ,
            name: 'composicao',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.composicao
            }
        },
        { 
            title: 'Situação',
            sortable: true,
            searchable: true,
            name: 'situacao',
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
        url: `insumo`,
        method: 'post',
        order: [1, 'desc'],
    }

    $(window).ready(() => loadDataTable('insumo_index', options))
    $(window).resize(() => loadDataTable('insumo_index', options))
</script>