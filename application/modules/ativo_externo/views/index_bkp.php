<!-- MAIN CONTENT-->
<!-- MAIN CONTENT-->
<style>
    .btn-contagem {
        width: 50px;
        height: 30px;
        font-weight: bold;
    }
    .btn-codigo {
        width: 100%;
        font-weight: bold;
    }
</style>
<div class="main-content"id="ativo_externo">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <?php if($this->permitido($permissoes, 11, 'adicionar')){ ?>
                            <a href="<?php echo base_url('ativo_externo/adicionar'); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                            <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row col-12">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Ativos Externo</h2>
                </div>
                <div class="col-12">
                    <ul class="col-12 col-md-6 col-lg-4 nav nav-pills mb-4" role="tablist">
                        <li><a class="nav-link" :class="{'active': tab == 'ativos'}" data-toggle="pill" href="#ativos">Itens</a></li>
                        <li><a class="nav-link" :class="{'active': tab == 'grupos'}" data-toggle="pill" href="#grupos">Grupos</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-12">
                <div class="table-responsive table--no-card m-b-40">
                    <h3 class="title-1 m-b-25">{{tab_text[tab]}}</h3>

                    <div v-if="tab == 'ativos'" class="col-12">
                        <div class="table-responsive m-b-25"> 
                            <div class="row col-12 search-filter">
                                <div class="col-12 col-md-4 col-lg-3">
                                    <div class="search-filter-select">
                                        <label for="filter.necessita_calibracao">Necessita Calibração</label>
                                        <select v-model="filters.necessita_calibracao.value" id="filter.necessita_calibracao" class="form-control">
                                            <option :value="'*'">Sem Filtro</option>
                                            <option :value="1">Sim</option>
                                            <option :value="0">Não</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-3">
                                    <div class="search-filter-select">
                                        <label for="filter.obra">Obra</label>
                                        <select v-model="filters.obra.value" id="filter.obra" class="form-control">
                                            <option :value="'*'">Sem Filtro</option>
                                            <option v-for="obra in obras" :value="obra.id_obra">{{obra.codigo_obra}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table 
                        class="table table-borderless table-striped table-earning" 
                        id="ativo_externo_datatable"
                    ></table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
<script>
    var datatable = null
    var datatables_id = `ativo_externo_datatable`
    var datatables_options = {}

    const ativo_externo = new Vue({
        el: "#ativo_externo",
        data(){
            return {
                datatable: null,
                tab: 'ativos',
                tab_text: {
                    ativos: 'Itens',
                    grupos: 'Grupos'
                },
                filters: {
                    necessita_calibracao: {
                        column: "atv.necessita_calibracao",
                        value: "*"
                    },
                    obra: {
                        column: "atv.id_obra",
                        value: []
                    },
                }
            }
        },
        watch: {
            tab(new_tab, old_tab){
                if(this.tab) localStorage.setItem("tab", this.tab)
                else localStorage.removeItem("tab")
            },
            filters: {
                deep: true,
                handler(){
                    if(
                        Object.values(this.filters)
                        .map(v => v.value !== null || v.value !== undefined || v.value !== '*' || v.value.length > 0)
                        .every(v => v)
                    ) {
                        localStorage.setItem("filters", JSON.stringify(this.filters))
                        datatable?.clear()?.destroy()
                        loadDataTableAtivoExterno(this.tab)
                    } else localStorage.removeItem("filters")
                }
            }
        },
        beforeMount(){
            this.tab = localStorage.getItem("tab") || 'ativos'
            this.filters = JSON.parse(localStorage.getItem("filters")) || this.filters
        },
    })

    const ativos_data_table_columns = [
        {
            title: 'Código',
            name: 'atv.codigo',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.codigo_link
            }
        },
        { 
            title: 'Nome do Ativo' ,
            name: 'atv.nome',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.nome_link
            }
        },
        { 
            title: 'Obra' ,
            name: 'ob.codigo_obra',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.codigo_obra
            }
        },
        { 
            title: 'Situação',
            sortable: true,
            name: 'atv.situacao',
            render: function(value, type, row, settings){
                return row.situacao_html
            }
        },
        { 
            title: 'Tipo' ,
            name: 'atv.tipo',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.tipo_html
            }
        },
        { 
            title: 'Incluso no Kit' ,
            name: 'id_ativo_externo_item',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.kit_html
            }
        },
        { 
            title: 'Valor Atribuído' ,
            name: 'atv.valor',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.valor
            }
        },
        { 
            title: 'Necessita Calibração' ,
            sortable: true,
            render: function(value, type, row, settings){
                return row.calibracao_html
            }
        },
        { 
            title: 'Inclusão' ,
            name: 'atv.data_inclusao',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.data_inclusao
            }
        },
        { 
            title: 'Descarte' ,
            name: 'atv.data_descarte',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.data_descarte
            }
        },
        { 
            title: 'Gerenciar' ,
            render(value, type, row, settings){
                return row.actions
            },
        },
    ]

    const ativos_options = {
        columns: ativos_data_table_columns,
        url: `ativo_externo/index/ativos`,
        order: [1, 'asc']
    }

    const grupos_data_table_columns = [
        { 
            title: 'GID' ,
            name: 'atv.id_ativo_externo_grupo',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.gid_link
            }
        },
        { 
            title: 'Nome do Grupo' ,
            name: 'atv.nome',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.nome_link
            }
        },
        { 
            title: 'Total em Estoque',
            sortable: true,
            searchable: false,
            render(value, type, row, settings){
                return row.estoque
            }
        },
        { 
            title: 'Tipo',
            name: 'atv.tipo',
            sortable: false,
            searchable: false,
            render(value, type, row, settings){
                return row.tipo_html
            }
        },
        { 
            title: 'Gerenciar',
            sortable: false,
            searchable: false,
            render(value, type, row, settings){ 
                return row.actions
            },
        },
    ]

    const grupos_options = {
        columns: grupos_data_table_columns,
        url: `ativo_externo/index/grupos`,
        order: [1, 'asc'],
    }

    const loadDataTableAtivoExterno = function(tab = null) {
        ativo_externo.tab = tab || ativo_externo.tab
        datatables_options = eval(`${tab}_options`)
        if(tab == 'ativos') datatables_options.filters = ativo_externo.filters
        if(!datatable) {
            try {
                datatable = window.loadDataTable(datatables_id, datatables_options)
            } catch(e){
                setTimeout(() => datatable = window.loadDataTable(datatables_id, datatables_options), 3000)
            }
        } else window.location.href = '/ativo_externo'
    }

    $(window).ready(() => {
        $('.nav-link').click((e) => loadDataTableAtivoExterno($(e.target).attr('href').replace('#','')))
        $('.nav-link.active').click()
    })
</script>
