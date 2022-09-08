<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                    <?php if($this->permitido($permissoes, 3, 'adicionar')){ ?>
                        <a href="<?php echo base_url('funcionario/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                    <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Funcionários</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table 
                            class="table table-borderless table-striped table-earning" 
                            id="funcionario_index"
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
            name: 'id_funcionario',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.id_link
            }
        },
        { 
            title: 'Nome' ,
            name: 'nome',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.nome_link
            }
        },
        { 
            title: 'Email' ,
            name: 'email',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.email
            }
        },
        { 
            title: 'Celular' ,
            name: 'celular',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.celular
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
            title: 'Obra' ,
            name: 'ob.codigo_obra',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.obra
            }
        },
        { 
            title: 'Situação',
            sortable: true,
            searchable: true,
            name: 'fn.situacao',
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
        url: `funcionario`,
        method: 'post',
        order: [1, 'desc'],
    }

    $(window).ready(() => loadDataTable('funcionario_index', options))
    $(window).resize(() => loadDataTable('funcionario_index', options))
</script>
