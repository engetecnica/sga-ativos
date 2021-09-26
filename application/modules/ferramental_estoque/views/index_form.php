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
                            <form action="<?php echo base_url('ferramental_estoque/salvar'); ?>" method="post" enctype="multipart/form-data">
                               
                                <h3 class="title-2 m-b-20 m-t-25">Funcionário Solicitante</h3>
                                <!-- Detalhes da Retirada -->
                                <table class="table table-responsive table--no-card table-borderless table-striped table-earning  m-b-25">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="30%">Nome</th>
                                            <th scope="col" width="30%">CPF</th>
                                            <th scope="col" width="30%">RG</th>
                                            <th scope="col" width="20%">Selecionar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="funcionario in funcionarios" :key="funcionario.id_funcionario" >
                                            <td>{{funcionario.nome}}</td>
                                            <td>{{funcionario.cpf}}</td>
                                            <td>{{funcionario.rg}}</td>
                                            <td width="10%">
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
                                <table class="table table-responsive table--no-card table-borderless table-striped table-earning  m-b-25" >
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="40%">Nome</th>
                                            <th scope="col" width="40%">Qtd. em Estoque</th>
                                            <th scope="col" width="10%">Selecionar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="grupo in grupos.filter((gp) => {return !in_grupos_selecionados(gp.id_ativo_externo_grupo)})" :key="grupo.id_ativo_externo_grupo" >
                                            <td>{{grupo.nome}}</td>
                                            <td>{{grupo.estoque}}</td>
                                            <td width="10%">
                                                <a v-if="grupo.estoque > 0" class="btn btn-sm btn-primary" @click="add_item(grupo)" >
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
                                    <div class="col-md-8"><label for="">Item</label></div>
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

                                        <div class="col-md-8">
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
                                
                                <hr>

                                <div class="row form-group">                                   
                                    <div class="col-12 col-md-12">
                                        <input type="text" class="form-control" name="observacoes" id="observacoes" placeholder="Alguma observação?">
                                    </div>                                    
                                </div>

                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary" :disabled="!permite_form">                                                    
                                        <i class="fa fa-save "></i>&nbsp;
                                        <span id="submit-form">Salvar Requisição</span>
                                    </button>
                                    <a href="<?php echo base_url('ferramental_estoque');?>">
                                    <button class="btn btn-info" type="button">                                                    
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
    var retirada = `<?php echo isset($retirada)  ? json_encode($retirada) : 'null'; ?>`;
    var estoque = new Vue({
        el: "#ferramental_estoque_form",
        data() {
            return {
                grupos: JSON.parse(`<?php echo json_encode($grupos); ?>`),
                grupos_selecionados: [],
                funcionarios: JSON.parse(`<?php echo json_encode($funcionarios); ?>`),
                retirada: null,
                id_obra: "<?php echo $id_obra; ?>",
                id_funcionario: null,
                retiradas: [],
                solicitar_autorizacao: false,
                permite_form: false,
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
                this.permite_form = false
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
                if (this.retirada) {
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

                    if ((retiradas.length > 0) && solilicitar) {
                        Swal.fire({
                            title: 'Usuário bloqueado!',
                            text: 'Usuário comtém retiradas pendêntes no sistema. Continuar aguardando confirmação de um Administrador?',
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
                    estoque.permite_form = true
                });
            },
            
        },
        mounted(){
            if(retirada) {
                this.retirada = retirada
                this.id_funcionario = retirada.id_funcionario
                this.id_obra = retirada.id_obra

                this.grupos_selecionados = this.retirada.items.map((item) => {
                    return {
                        id_retirada_item: item.id_retirada_item,
                        estoque: (this.grupos.find((gp) => gp.id_ativo_externo_grupo == item.id_ativo_externo_grupo)).estoque || 0,
                        id_ativo_externo_grupo: item.id_ativo_externo_grupo,
                        nome: item.nome,
                        quantidade: item.quantidade 
                    }
                })
                this.permite_form = true
            }
        }
    })
</script>