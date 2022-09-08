<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <?php if($this->permitido($permissoes, 6, 'adicionar')){ ?>
                            <a href="<?php echo base_url('obra/adicionar'); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                            <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Obras</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table 
                            class="table table-borderless table-striped table-earning" 
                            id="lista"
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
            name: 'id_obra',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.id_link
            }
        },
        { 
            title: 'Código Da Obra' ,
            name: 'codigo_obra',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.codigo_obra_link
            }
        },
        { 
            title: 'Empresa' ,
            name: 'ep.razao_social',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.empresa
            }
        },
        { 
            title: 'Razão Social' ,
            name: 'obra_razaosocial',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.obra_razaosocial
            }
        },
        { 
            title: 'CNPJ' ,
            name: 'obra_cnpj',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.obra_cnpj
            }
        },
        { 
            title: 'Responsável' ,
            name: 'obra.responsavel',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.responsavel
            }
        },
        { 
            title: 'Email' ,
            name: 'obra.responsavel_email',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.responsavel_email
            }
        },
        { 
            title: 'Celular' ,
            name: 'obra.responsavel_celular',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.responsavel_celular
            }
        },
        { 
            title: 'Obra Base' ,
            name: 'obra_base',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.obra_base_html
            }
        },
        { 
            title: 'Situação',
            sortable: true,
            searchable: true,
            name: 'obra.situacao',
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
        url: `obra`,
        method: 'post',
        order: [1, 'desc'],
    }

    $(window).ready(() => loadDataTable('obra_index', options))
    $(window).resize(() => loadDataTable('obra_index', options))
</script>
