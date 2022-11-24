<!-- MAIN CONTENT-->
<div id="ferramental_estoque_form" class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <?php if(isset($retirada))  { ?>
                            <a href="<?php echo base_url("ferramental_estoque/detalhes/{$retirada->id_retirada}"); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                            <i class="zmdi zmdi-arrow-left"></i>Detalhes</button></a>
                        <?php } else { ?>
                            <a  href="<?php echo base_url('ferramental_estoque'); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                            <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Retirada de Ferramentas</h2>

                    <div class="card">
                        <div class="card-header">{{retirada ? 'Editar' : 'Incluir'}} Retirada</div>
                        <div class="card-body">
                            <form id="form-retirada" action="<?php echo base_url('ferramental_estoque/salvar'); ?>" method="post" enctype="multipart/form-data">
                               
                                <h3 class="title-2 m-b-20 m-t-25">Funcionário Solicitante</h3>
                                <!-- Detalhes da Retirada-->
                                <table  class="table dataTable table-responsive-md table--no-card table-borderles table-striped table-earning  m-b-40" >
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="60%">Matrícula | Nome</th>
                                            <th scope="col" width="10%">CPF</th>
                                            <th scope="col" width="10%">RG</th>
                                            <th scope="col" width="20%">Bloqueado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <!--@search="buscarFuncionario($event ? $event : null)"-->
                                                <v-select 
                                                    class="select form-control col-12"
                                                    :value="funcionario_seleted_value"
                                                    :options="funcionarios_filted"
                                                    :no-options="'Buscar funcionário via Nome ou Matrícula'"
                                                    @input="selecionaFuncionario($event ? $event.value : null)"
                                                >
                                                    <template v-slot:no-options="{ search, searching }">
                                                        <template v-if="searching">
                                                            Nenhum resultado encontrado para <em>{{ search }}</em>.
                                                        </template>
                                                        <em v-else style="opacity: 0.5">Buscar Funcionário via Nome, Matrícula, RG ou CPF</em>
                                                    </template>
                                                </v-select>
                                            </td>
                                            <td>{{funcionario_seleted ? funcionario_seleted.cpf : '-'}}</td>
                                            <td>{{funcionario_seleted ? funcionario_seleted.rg : '-'}}</td>
                                            <td>{{funcionario_seleted && !permit_retirada ? 'Sim' : 'Não'}}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <h3 class="title-2 m-b-20 m-t-25">Itens da Retirada</h3>
                                <!-- Buscar itens da Retirada -->
                                <table 
                                    class="table table-responsive-md table--no-card table-borderless table-striped table-earning m-b-25" 
                                    :id="data_table_id"
                                >
                                </table>
                            
                                <input v-if="retirada" type="hidden" name="id_retirada" id="id_retirada" :value="retirada.id_retirada">
                                <input type="hidden" id="id_obra" name="id_obra" :value="id_obra" />
                                <input type="hidden" id="id_funcionario" name="id_funcionario" :value="id_funcionario" />
                                <input type="hidden" id="solicitar_autorizacao" name="solicitar_autorizacao" :value="solicitar_autorizacao">
                             
                                <table class="table dataTable table-responsive-md table--no-card table-borderless table-striped table-earning  m-b-25" >
                                    <thead>
                                        <tr>
                                            <th scope="col" width="50%">Nome</th>
                                            <th scope="col" width="20%">Cod. Patrimônio</th>
                                            <!--<th scope="col" width="15%">Quantidade</th> -->
                                            <th scope="col" width="15%">Remover</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(sgrupo, sg) in grupos_selecionados" :key="sg">
                                            <input 
                                                type="hidden"
                                                v-if="grupos_selecionados[sg].id_retirada_item"
                                                name="id_retirada_item[]" id="id_retirada_item[]"  
                                                :value="grupos_selecionados[sg].id_retirada_item"
                                            >
                                            <input 
                                                type="hidden"
                                                name="id_ativo_externo_grupo[]" id="id_ativo_externo_grupo[]"  
                                                :value="grupos_selecionados[sg].id_ativo_externo_grupo"
                                            >

                                            <td scope="col" width="50%">
                                                <div class="exchange1">
                                                    <!-- <input
                                                        type="text" readonly
                                                        class="form-control"
                                                        :value="sgrupo.nome"
                                                    /> -->
                                                    {{sgrupo.nome}} 
                                                </div>
                                            </td>
                                            <td scope="col" width="20%">
                                                <div class="row justify-content-center text-align" style="display:grid">
                                                    <label class ="col-form-label" 
                                                    v-for="(cod,index) in sgrupo.array_patrimonio" 
                                                    :key="index">{{cod}}</label>
                                                </div>
                                                <!--
                                                <select v-bind:id="sgrupo.id_ativo_externo_grupo" class="js-example-basic-multiple" name="patrimonios[]" multiple="multiple">
                                                    <option v-for="cod in sgrupo.array_patrimonio" selected :key="cod" :value="cod">{{cod}}</option>
                                                </select>-->
                                            </td>
                                            <!--<td scope="col" width="15%" >
                                                <div class="row mt-1" style="display:inline-grid">
                                                    <input 
                                                        v-for="(cod,index) in sgrupo.array_patrimonio" 
                                                        :key="index"
                                                        name="quantidade[]" type="number" placeholder="0" 
                                                        class="form-control quantidade mb-2"
                                                        v-model="grupos_selecionados[sg].quantidade"
                                                        :value="grupos_selecionados[sg].quantidade"
                                                        min="1" :max="grupos_selecionados[sg].estoque"
                                                >
                                                </div>
                                                
                                            </td>-->
                                            <td scope="col" width="15%">
                                                <!-- <div class="row col-form-label mt-1"
                                                        v-for="(cod,index) in sgrupo.array_patrimonio" 
                                                        :key="index">
                                                    <button style="display:inline-grid"
                                                        @click="removeItem(grupos_selecionados[sg].id_ativo_externo_grupo,cod)" 
                                                        type="button" 
                                                        class="btn btn-sm btn-danger"
                                                    >
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </div> -->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="row form-group m-t-40">
                                    <div class="col-12 col-md-3">
                                        <label>Devolução Pevista</label>
                                    </div>                                   
                                    <div class="col-12 col-md-3">
                                        <input require="required" type="datetime-local" class="form-control" name="devolucao_prevista" id="devolucao_prevista" v-model="devolucao_prevista">
                                    </div>
                   
                                    <div class="col-12 col-md-2">
                                        <label>Observação</label>
                                    </div>                                    
                                    <div class="col-12 col-md-4">
                                        <textarea 
                                            v-model="observacoes" 
                                            class="form-control"
                                            name="observacoes" 
                                            id="observacoes" 
                                            placeholder="Alguma observação?"
                                        >
                                        </textarea>
                                    </div>                                    
                                </div>

                                <hr>
                                <div class="pull-left">
                                    <button  type="submit" form="form-retirada" class="btn btn-primary" :disabled="permite_form">                                                    
                                        <i class="fa fa-save "></i>&nbsp;
                                        <span id="submit-form">Salvar Requisição</span>
                                    </button>
                                    <a href="<?php echo base_url('ferramental_estoque');?>">
                                    <button class="btn btn-secondary" type="button">                                   
                                        <i class="fa fa-ban "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>                              
                                    </a>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var id_retirada = "<?php echo isset($id_retirada) ? $id_retirada : null; ?>"
    var estoque = new Vue({
        el: "#ferramental_estoque_form",
        computed: {
            funcionarios_filted(){
                return this.funcionarios?.map(f => { 
                    return {label: `${f.matricula} | ${f.nome}`, value: f.id_funcionario}
                })
            },
            funcionario_seleted(){
                return this.funcionarios?.find(f => f.id_funcionario == this.id_funcionario) || null
            },
            funcionario_seleted_value(){
                const funcionario = this.funcionario_seleted
                return funcionario ? `${funcionario.matricula} | ${funcionario.nome}` : 'Selecione um Funcionário'
            },
            grupos_filted(){
                return this.grupos.filter((gp) => {return !this.inGruposSelecionados(gp.id_ativo_externo_grupo)});  
            },
            permite_form() {
                return ![
                    !!this.devolucao_prevista,
                    new Date(this.devolucao_prevista).getTime() > (new Date()).getTime(),
                    this.id_funcionario && this.grupos_selecionados.length > 0
                ].includes(false)
            }
        },
        data() {
            return {
                grupos: [],
                grupos_selecionados: [],
                permit_retirada: true,
                funcionarios: [],
                retirada: null,
                id_obra: null,
                id_funcionario: null,
                observacoes: null,
                retiradas: [],
                solicitar_autorizacao: false,
                user: window.user,
                devolucao_prevista: null,
                data_table: null,
                data_table_id: "ferramental_estoque",
                data_table_columns: [
                    { 
                        title: 'Nome' ,
                        data: 'nome',
                        sortable: true,
                        searchable: true,
                        render: function(value, type, row, settings){
                            return value
                        }
                    },
                    { 
                        title: 'Qtd. em Estoque',
                        data: 'id_ativo_externo_grupo',
                        sortable: false,
                        searchable: false,
                        render(value, type, row, settings){
                            return row.estoque
                        }
                    },
                    { 
                        title: 'Cod. Patrimônio',
                        sortable: false,
                        searchable: false,
                        render(value, type, row, settings){ 
                            return row.patrimonio
                        },
                    },
                    // { 
                    //     title: 'Adicionar',
                    //     sortable: false,
                    //     searchable: false,
                    //     render(value, type, row, settings){ 
                    //         return row.actions
                    //     },
                    // },
                ],
            }
        },
        methods: {
            inGruposSelecionados(id_grupo){
                return this.grupos_selecionados.find((gp) => {return gp.id_ativo_externo_grupo == id_grupo});
            },
            filterGrupos(grupos){
                // return grupos
                // .map(g => !this.inGruposSelecionados(g.id_ativo_externo_grupo) ? g : null)
                // .filter(v => v)
                return grupos
            },
            selecionaFuncionario(id_funcionario = null, solilicitar = true){
                if(id_funcionario && this.id_funcionario != id_funcionario) {
                    this.listaRetiradasFuncionario(id_funcionario, true)
                }

                this.solicitar_autorizacao = false
                this.retiradas = []
            },
            buscarFuncionario(search = null){
                window.$.ajax({
                    method: "GET",
                    url: `${base_url}ferramental_estoque/buscar/funcionarios`,
                    data: {
                        search: search,
                        start: 0,
                        length: 1000,
                    },
                    async: true
                })
                .done(function(response) {
                    estoque.funcionarios = response.data
                });
            },
            addItem(item){
                let array_patrimonio;

                if (typeof item !== 'object'){
                    item = this.grupos.find(i => i.id_ativo_externo_grupo == item);
                    array_patrimonio = $('.cadMultiple' + item.id_ativo_externo_grupo).val();    
                }

                if(array_patrimonio.length>0){
                    $('.cadMultiple' + item.id_ativo_externo_grupo).val(null).trigger('change');
    
                    /*array_patrimonio.forEach((element, key) => {
                        $(`.cadMultiple${item.id_ativo_externo_grupo} option[value='${element}']`).remove()
                    });*/

                    const beforeIndex = this.grupos_selecionados.findIndex(i => {
                        return i.id_ativo_externo_grupo == item?.id_ativo_externo_grupo ||  i.id_ativo_externo_grupo == item
                    });

                    if(item && beforeIndex < 0)this.grupos_selecionados.push({...item, quantidade: 1, array_patrimonio});

                    if(item && beforeIndex >= 0){
                        this.grupos_selecionados[beforeIndex].array_patrimonio.push(...array_patrimonio);
                    }
                }
            },
            removeItem(item,codpat) {
                let remove_patrimonio =[];
                let index_patrimonio;

                if (typeof item !== 'object') item = this.grupos_selecionados.find(i => i.id_ativo_externo_grupo == item)

                const index = this.grupos_selecionados.findIndex(i => {
                    return i.id_ativo_externo_grupo == item?.id_ativo_externo_grupo ||  i.id_ativo_externo_grupo == item
                });
                
                /*Object.keys(item?.ativos).map(function (key) {
                    if(codpat == item.ativos[key].codigo){
                        index_patrimonio = key;
                    }
                });*/

                Object.keys(item?.array_patrimonio).map(function (key) {
                    remove_patrimonio.push(item.array_patrimonio[key]);
                    if(codpat == item.array_patrimonio[key]){
                        index_patrimonio = key;
                    }
                });

                if(remove_patrimonio.length > 1){ 
                    this.grupos_selecionados[index].array_patrimonio.splice(index_patrimonio,1);
                }else{
                    this.grupos_selecionados.splice(index, 1)
                }
                /*
                var newOption = new Option(codpat, codpat, false, false);
                $(`.cadMultiple${item.id_ativo_externo_grupo}`).append(newOption).trigger('change');]
                */
                
            },
            listaRetiradasFuncionario(id_funcionario, solilicitar = true){
                if(solilicitar) {
                    return window.$.ajax({
                        method: "GET",
                        url: `${base_url}ferramental_estoque/lista_retiradas/${estoque.id_obra}/${id_funcionario}`,
                        data: {
                            status: [1, 2, 4, 14] //in status
                        }
                    })
                    .done(function(lista) {
                        const retiradas = Object.values(JSON.parse(lista) || [])
                        .filter(r => (new Date(r.devolucao_prevista)).getTime() < (new Date()).getTime())

                        if (estoque.funcionarios.length) {
                            const index_funcionario = estoque?.funcionarios.findIndex(f => f.id_funcionario == id_funcionario) 

                            if(index_funcionario >= 0) {
                                const span = retiradas.length == 0  ? '' : ` - Bloqueado Para Retirada` 
                                estoque.funcionarios[index_funcionario] = Object.assign(
                                    estoque.funcionarios[index_funcionario],
                                    {
                                        nome: estoque.funcionarios[index_funcionario].nome += span,
                                        permit_retirada: retiradas.length === 0,
                                        retiradas: retiradas,
                                    }
                                )
                            }

                        }

                        if (retiradas.length > 0 && solilicitar === false) {
                            this.swal_is_open = false
                            estoque.solicitar_autorizacao = false
                            return
                        }

                        const isConfirmed = false
                        const msg = estoque.user.nivel == 1 ? "Deseja continuar e autorizar retirada?" : "Deseja continuar aguardando confirmação de um Administrador?"
        
                        if ((retiradas.length > 0 && solilicitar) && !this.swal_is_open) {
                            console.log('tem retirada')
                            this.swal_is_open = true
                            this.permit_retirada = false
                            return Swal.fire({
                                title: 'Funcionário Bloqueado!',
                                text: `Existe retirada pendênte no sistema para o funcionário selecionado. ${msg}`,
                                icon: 'warning',
                                confirmButtonText: 'Sim, Continuar!',
                                showConfirmButton: true,
                                cancelButtonText: 'Não',
                                showCancelButton: true
                            })
                            .then((confirm) => {
                                this.swal_is_open = false
                                if (confirm.isConfirmed) {
                                    estoque.id_funcionario = id_funcionario
                                    estoque.solicitar_autorizacao = false /* antes true */
                                    estoque.permite_form = true
                                    return 
                                }

                                estoque.id_funcionario = null
                                estoque.solicitar_autorizacao = false
                                estoque.permite_form = false
                            })
                            return
                        }
                        
                        estoque.id_funcionario = id_funcionario
                        estoque.permit_retirada = true
                    });
                }

                this.id_funcionario = id_funcionario
            },
            getDadosRetirada(id_retirada = null){
                let url = `${base_url}ferramental_estoque/dados_retirada`
                if (id_retirada) url = `${url}/${id_retirada}`

                window.$.ajax({
                    method: "GET",
                    url: url,
                    async: true
                })
                .done((data) => {
                    estoque.id_obra = data.id_obra
                    if(data.retirada) {
                        estoque.retirada = data.retirada
                        estoque.id_funcionario = estoque.retirada.id_funcionario
                        estoque.id_obra = estoque.retirada.id_obra
                        estoque.observacoes = estoque.retirada.observacoes
                        estoque.devolucao_prevista = estoque.retirada.devolucao_prevista.replace(" ","T")
                        estoque.buscarFuncionario(estoque.id_funcionario)
                        estoque.selecionaFuncionario(estoque.id_funcionario)
                        data.retirada.items.forEach((item) => estoque.addItem(item))
                        return
                    }
                    estoque.buscarFuncionario()
                })
            },
            loadDataTable(){
                this.data_table?.destroy()

                options = {
                    columns: this.data_table_columns,
                    url: `ferramental_estoque/buscar/grupos`,
                    ajaxOnSuccess: (response) => {
                        this.grupos = response.data
                    }
                }

                try {
                    this.data_table = window.loadDataTable(this.data_table_id, options)
                } catch (e) {
                    setTimeout(() => this.loadDataTable(), 500) 
                }
            }
        },
        async mounted(){
            await this.getDadosRetirada(window.id_retirada)
            this.loadDataTable()
        }
    })
</script>