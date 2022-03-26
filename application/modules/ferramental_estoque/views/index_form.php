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
                                <!-- Detalhes da Retirada -->
                                <table class="table table-responsive-md table--no-card table-borderless table-striped table-earning  m-b-25" id="lista">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="85%">Nome</th>
                                            <th scope="col" width="35%">CPF</th>
                                            <th scope="col" width="35%">RG</th>
                                            <th scope="col" width="20%">Selecionar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="funcionario in funcionarios" :key="funcionario.id_funcionario" >
                                            <td>{{funcionario.nome}}</td>
                                            <td>{{funcionario.cpf}}</td>
                                            <td>{{funcionario.rg}}</td>
                                            <td>
                                                <a v-if="id_funcionario == funcionario.id_funcionario" class="btn btn-sm btn-primary" @click="seleciona_funcionario()" >
                                                   <i class="fas fa-check text-light"></i>
                                                </a>
                                                <a v-else class="btn btn-sm btn-secondary" @click="seleciona_funcionario(funcionario.id_funcionario)" >
                                                   <i class="fas fa-check text-light"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <h3 class="title-2 m-b-20 m-t-25">Itens da Retirada</h3>
                                <!-- Itens da Retirada -->
                                <table class="table table-responsive-md table--no-card table-borderless table-striped table-earning  m-b-25" id="lista2">
                                    <thead>
                                        <tr>
                                            <th scope="col" width="80%">Nome</th>
                                            <th scope="col" width="10%">Qtd. em Estoque</th>
                                            <th scope="col" width="10%">Selecionar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="grupo in grupos_filted" :key="grupo.id_ativo_externo_grupo" >
                                            <td>{{grupo.nome}}</td>
                                            <td>{{grupo.estoque}}</td>
                                            <td>
                                                <a v-if="grupo.estoque > 0" class="btn btn-sm btn-primary" @click="add_item(grupo)" >
                                                   <i class="fas fa-plus text-light"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <tr v-if="grupos_filted.length == 0" >
                                            <td>Nemhum grupo diponível</td>
                                            <td>0</td>
                                            <td>
                                                <a class="btn btn-sm btn-secondary" disabled="disabled">
                                                   <i class="fas fa-plus text-light"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            
                                <?php if(isset($retirada) && isset($retirada->id_retirada)){?>
                                <input type="hidden" name="id_retirada" id="id_retirada" value="<?php echo $retirada->id_retirada; ?>">
                                <?php } ?> 
                                <input type="hidden" id="id_obra" name="id_obra" :value="id_obra" />
                                <input type="hidden" id="id_funcionario" name="id_funcionario" :value="id_funcionario" />
                                <input type="hidden" id="solicitar_autorizacao" name="solicitar_autorizacao" :value="solicitar_autorizacao">
                             

                                <div class="row">
                                    <div class="col-md-4"><label for="">Item</label></div>
                                    <div class="col-md-2"><label for="">Quantidade</label></div>
                                    <div class="col-md-2"><label for=""></label></div>
                                </div>
                                <div class="listagem">

                                    <p v-if="grupos_selecionados.length == 0" class="text-center">Nenhum iten na lista </p>

                                    <div 
                                        v-for="(sgrupo, sg) in grupos_selecionados" :key="sg" 
                                        class="row item-lista" style="margin-bottom: 10px;"
                                    >
                                        <input 
                                            v-if="grupos_selecionados[sg].id_retirada_item"
                                            name="id_retirada_item[]" id="id_retirada_item[]"  type="hidden"
                                            :value="grupos_selecionados[sg].id_retirada_item"
                                        >
                                        <input 
                                            name="id_ativo_externo_grupo[]" id="id_ativo_externo_grupo[]"  type="hidden"
                                            :value="grupos_selecionados[sg].id_ativo_externo_grupo"
                                        >

                                        <div class="col-md-4">
                                            <div class="exchange1">
                                                <input
                                                    type="text" readonly
                                                    class="form-control"
                                                    :value="sgrupo.nome"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <input 
                                                name="quantidade[]" type="number" placeholder="0" 
                                                class="form-control quantidade"
                                                v-model="grupos_selecionados[sg].quantidade"
                                                :value="grupos_selecionados[sg].quantidade"
                                                min="1" :max="sgrupo.estoque"
                                            >
                                        </div>

                                        <div class="col-md-2" nowrap>
                                            <p>
                                                <button @click="remove_item(sg, sgrupo)" type="button" class="btn btn-sm btn-danger"><i class="fa fa-minus"></i></button>
                                            </p>
                                        </div>
                                    </div>

                                </div>

                                <br><br>

                                <div class="row form-group">
                                    <div class="col-12 col-md-3">
                                        <label>Devolução Pevista</label>
                                    </div>                                   
                                    <div class="col-12 col-md-3">
                                        <input require="required" type="date" class="form-control" name="devolucao_prevista_data" id="devolucao_prevista_data" :value="devolucao_prevista_data" v-model="devolucao_prevista_data">
                                    </div> 
                                    <div class="col-12 col-md-2">
                                        <input require="required" type="text" class="form-control hora" name="devolucao_prevista_hora" id="devolucao_prevista_hora" :value="devolucao_prevista_hora" v-model="devolucao_prevista_hora" placeholder="00:00:00">
                                    </div>                                             
                                </div>

                                <div class="row form-group">
                                    <div class="col-12 col-md-3">
                                        <label>Observações</label>
                                    </div>                                    
                                    <div class="col-12 col-md-5">
                                        <textarea type="text" class="form-control" name="observacoes" id="observacoes" placeholder="Alguma observação?">
                                        </textarea>
                                    </div>                                    
                                </div>

                                <hr>
                                <div class="pull-left">
                                    <button  type="submit" form="form-retirada" class="btn btn-primary" :disabled="!permite_form">                                                    
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

            <div class="row">
                <div class="col-md-12">
                    <div class="copyright">
                        <p>Copyright © <?php echo date("Y"); ?>. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var retirada = `<?php echo isset($retirada)  ? json_encode($retirada) : ''; ?>`
    var grupos = `<?php echo isset($grupos) ? json_encode($grupos) : json_encode([]); ?>`
    var funcionarios = `<?php echo isset($funcionarios) ? json_encode($funcionarios) : json_encode([]); ?>`
    var id_obra = "<?php echo isset($id_obra) ? $id_obra : $user->id_obra; ?>"

    var estoque = new Vue({
        el: "#ferramental_estoque_form",
        computed: {
            grupos_filted(){
                return this.grupos.filter((gp) => {return !this.in_grupos_selecionados(gp.id_ativo_externo_grupo)});  
            },
            permite_form() {
                return (this.devolucao_prevista_data && this.devolucao_prevista_hora) && (this.id_funcionario && this.grupos_selecionados.length > 0);
            }
        },
        data() {
            return {
                grupos: [],
                grupos_selecionados: [],
                funcionarios: [],
                retirada: null,
                id_obra: null,
                id_funcionario: null,
                retiradas: [],
                solicitar_autorizacao: false,
                user: window.user,
                devolucao_prevista_data: null,
                devolucao_prevista_hora: null,
            }
        },
        methods: {
            in_grupos_selecionados(id_grupo){
                return this.grupos_selecionados.find((gp) => {return gp.id_ativo_externo_grupo == id_grupo});
            },
            lista_grupos(){
                window.$.ajax({
                    method: "GET",
                    url: base_url + "ferramental_estoque/lista_ativos_grupos_json",
                })
                .done(function(grupos) {
                    estoque.grupos = JSON.parse(grupos)
                });
            },
            seleciona_funcionario(id_funcionario = null, solilicitar = true){
                if(id_funcionario && (id_funcionario != this.id_funcionario)) {
                    return this.lista_retiradas(id_funcionario, solilicitar)
                }
                this.id_funcionario = null
                this.solicitar_autorizacao  = false
                this.retiradas = []
            },
            add_item(item = null){
                if(!item){
                    this.grupos_selecionados.push({
                        id_ativo_externo_grupo: null,
                        nome: 'Buscar Item Test',
                        quantidade: 1,
                        estoque: 0
                    })
                    return
                }
                this.grupos_selecionados.push({...item, quantidade: 1})
            },
            remove_item(index, item){
                if (this.retirada && this.retirada.items.length > 0) {
                    window.$.ajax({
                        method: "POST",
                        url: base_url + `ferramental_estoque/remove_item/${item.id_retirada_item}`
                    })
                    .done(function(status) {
                        if(status == 'true') {
                            estoque.grupos_selecionados.splice(index, 1)
                        }
                    })
                    return
                }
                estoque.grupos_selecionados.splice(index, 1)
            },
            lista_retiradas(id_funcionario, solilicitar = true){
                window.$.ajax({
                    method: "GET",
                    url: base_url + `ferramental_estoque/lista_retiradas/${estoque.id_obra}/${id_funcionario}`,
                    data: {
                        status: [1, 2, 4, 14] //in status
                    }
                })
                .done(function(lista) {
                    let retiradas = JSON.parse(lista)
                    let isConfirmed = false
                    let msg = estoque.user.nivel == 1 ? "Deseja continuar e autorizar retirada?" : "Deseja continuar aguardando confirmação de um Administrador?"

                    if ((retiradas.length > 0) && solilicitar) {
                        Swal.fire({
                            title: 'Funcionário Bloqueado!',
                            text: `Existe retirada pendênte no sistema para o funcionário selecionado. ${msg}`,
                            icon: 'warning',
                            confirmButtonText: 'Sim, Continuar!',
                            showConfirmButton: true,
                            cancelButtonText: 'Não',
                            showCancelButton: true
                        })
                        .then(function(confirm) {
                            if (confirm.isConfirmed) {
                                estoque.solicitar_autorizacao = true
                                estoque.retiradas = retiradas
                                estoque.id_funcionario = id_funcionario 
                                estoque.permite_form = true
                                return 
                            }
                            estoque.seleciona_funcionario(null)
                        })
                        return
                    }

                    estoque.solicitar_autorizacao = false
                    if (!solilicitar) {
                        estoque.solicitar_autorizacao = true
                    }

                    estoque.retiradas = retiradas
                    estoque.id_funcionario = id_funcionario 
                });
            },
            
        },
        mounted(){
            if(!!grupos) this.grupos = JSON.parse(grupos)
            if(!!funcionarios) this.funcionarios = JSON.parse(funcionarios)
            if(!!id_obra) this.id_obra = id_obra

            if(!!retirada) {
                this.retirada = JSON.parse(retirada)
                this.id_funcionario = this.retirada.id_funcionario
                this.id_obra = this.retirada.id_obra
                if (this.retirada.devolucao_prevista) { 
                    let explode = this.retirada.devolucao_prevista.split(" ")
                    this.devolucao_prevista_data = explode[0]
                    this.devolucao_prevista_hora = explode[1]
                }

                this.grupos_selecionados = this.retirada.items.map((item) => {
                    return {
                        id_retirada_item: item.id_retirada_item,
                        estoque: (this.grupos.find((gp) => gp.id_ativo_externo_grupo == item.id_ativo_externo_grupo)).estoque || 0,
                        id_ativo_externo_grupo: item.id_ativo_externo_grupo,
                        nome: item.nome,
                        quantidade: item.quantidade 
                    }
                })
            }
        },
        watch: {
            devolucao_prevista_hora(){
                setTimeout( () => {
                    if (this.devolucao_prevista_hora === "") {
                        this.devolucao_prevista_hora = null
                    }
                }, 10)
            }
        }
    })
</script>