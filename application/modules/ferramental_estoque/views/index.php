<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php if($this->permitido($permissoes, 13, 'adicionar')){ ?>
                        <div class="overview-wrap"> <a href="<?php echo base_url('ferramental_estoque/adicionar'); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                            <i class="zmdi zmdi-plus"></i>Nova Retirada</button></a>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                <h2 class="title-1 m-b-20">Estoque de Ferramentas</h2>
                    <div class="table table--no-card table-responsive table--no- m-b-40">
                        <h3 class="title-1 m-b-25">Retiradas</h3>
                        <table 
                            class="table dataTable table-borderless table-striped table-earning" 
                            id="ferramental_estoque_index"
                        > 
                        </table>
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
            title: 'Retirada ID',
            name: 'id_retirada',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.id_retirada_html
            }
        },
        { 
            title: 'Obra' ,
            name: 'ob.codigo_obra',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.obra
            }
        },
        { 
            title: 'Funcionário',
            name: 'fn.nome',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.funcionario
            }
        },
        { 
            title: 'Data',
            data: 'data_inclusao',
            sortable: true,
            searchable: true,
            render(value, type, row, settings){
                return row.data_inclusao
            }
        },
        { 
            title: 'Devolução Prevista',
            data: 'devolucao_prevista',
            sortable: true,
            searchable: true,
            render(value, type, row, settings){
                return row.devolucao_prevista
            }
        },
        { 
            title: 'Status',
            name: 'st.slug',
            sortable: true,
            searchable: true,
            render(value, type, row, settings){
                return row.status_html
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
        url: `ferramental_estoque`,
        method: 'post',
        order: [0, 'desc']
    }

    $(window).ready(() => loadDataTable('ferramental_estoque_index', options))
    $(window).resize(() => loadDataTable('ferramental_estoque_index', options))
</script>