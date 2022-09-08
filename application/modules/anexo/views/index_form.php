<!-- MAIN CONTENT-->
<div id="anexo_form" class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap"> <?php $id = isset($anexo) ? "#".$anexo->id_anexo : '';?>
                        <a href="<?php echo base_url("anexo$id"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Anexos</h2>

                    <div class="card">
                        <div class="card-header">Novo Anexo</div>

                        <div class="card-body">
                            <form  action="<?php echo base_url("anexo/salvar"); ?>" method="post" enctype="multipart/form-data">
                                <?php if ($back_url){; ?>
                                    <input  type="hidden"  id="back_url" name="back_url" value="<?php echo $back_url; ?>" />
                                <?php } ?>
                                
                                <input  type="hidden"  id="modulo" name="modulo" v-model="modulo" />
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="razao_social" class=" form-control-label">Modulo</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select  class="form-control" required="required" type="text" id="anexo_modulo" name="anexo_modulo" v-model="anexo_modulo">
                                            <option :value="null">Selecione um Modulo</option>
                                            <option v-for="md in modulos" :value="md">{{md.nome}}</option>
                                        </select>
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="anexo_tipo" class=" form-control-label">Tipo de Anexo</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select :readonly="!modulo"  class="form-control" required="required" type="text" id="anexo_tipo" name="anexo_tipo" v-model="anexo_tipo">
                                            <option :value="null">Selecione um Tipo de Anexo</option>
                                            <option v-for="tipo in tiposFiltred" :value="tipo">{{tipo.nome}}</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <input type="hidden" id="tipo" name="tipo" v-model="tipo" />

                                <div v-if="tipo == 'manutencao' && modulo === 'ativo_veiculo'" class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="anexo_servico" class=" form-control-label">Serviços</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select :readonly="subitems.lenght == 0"  class="form-control" required="required" type="text" id="servico" name="servico" v-model="servico">
                                            <option :value="null">Selecione um Serviço</option>
                                            <option v-for="serv in servicos" :value="serv.id_ativo_configuracao">{{serv.titulo}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div v-if="tipo" class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="item" class=" form-control-label">Buscar Item</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input @keyup.enter、="getItems()" @input="getItems()" @blur="getItems()" :readonly="items.lenght == 0" type="text" class="form-control" v-model="search_items" placeholder="Buscar" />
                                    </div>
                            
                                
                                    <div v-if="items.length > 0" class="col col-md-2">
                                        <label for="item" class=" form-control-label">Modulo Item</label>
                                    </div>
                                    <div v-if="items.length > 0" class="col-12 col-md-4">
                                        <select :readonly="items.length == 0"  class="form-control" type="text" id="item" name="item" v-model="item">
                                            <option :value="null">Selecione um Item</option>
                                            <option v-for="it in items" :value="it.id">{{it.descricao}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div v-if="subitems.length > 0" class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="subitem" class=" form-control-label">Modulo Subitem</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select class="form-control" type="text" id="subitem" name="subitem" v-model="subitem">
                                            <option :value="null">Selecione um Subitem</option>
                                            <option v-for="it in subitems" :value="it.id">{{it.descricao}}</option>
                                        </select>
                                    </div>
                                </div>

                                <hr/>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="titulo" class=" form-control-label">Titulo do Anexo</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" id="titulo" name="titulo" placeholder="Titulo do Anexo" class="form-control"/>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="descricao" class=" form-control-label">Descrição</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <textarea id="descricao" name="descricao" placeholder="Descreva seu anexo aqui" class="form-control"></textarea>
                                    </div>
                                </div>


                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="anexo" class=" form-control-label">Anexo</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <input required="required" type="file" id="anexo" name="anexo" placeholder="Seu Anexo Aqui" class="form-control">
                                    </div>
                                </div>

                                
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <?php $id = isset($anexo) ? "#".$anexo->id_anexo : '';?>
                                    <a href="<?php echo base_url("anexo$id"); ?>">
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
var anexo_modulos = JSON.parse(`<?php echo json_encode($anexo_modulos); ?>`) || []
var anexo_tipos = JSON.parse(`<?php echo json_encode($anexo_tipos); ?>`) || []
var veiculo_manutencao_servicos =  JSON.parse(`<?php echo isset($veiculo_manutencao_servicos) ? json_encode($veiculo_manutencao_servicos) : []; ?>`) || []
var modulo = JSON.parse(`<?php echo json_encode($modulo); ?>`) || null
var tipo = `<?php echo $tipo; ?>`|| null
var item = `<?php echo $id_item; ?>`|| null
var subitem = `<?php echo $id_subitem; ?>`|| null

var anexo_form = new Vue({
    el: "#anexo_form",
    data() {
      return {
        base_url: window.base_url,
        user: window.user,
        titulo: null,
        modulo: window.modulo,
        anexo_modulo: null, 
        modulos: window.anexo_modulos,
        tipo: null,
        anexo_tipo: null,
        tipos:  window.anexo_tipos,
        item: null,
        search_items: null,
        items: [],
        subitem:  null,
        search_subitems: null,
        subitems: [],
        anexo_servico: null,
        servico: null,
        servicos: window.veiculo_manutencao_servicos
      }
    },
    computed: {
        tiposFiltred() {
            return this.anexo_modulo ? this.tipos.filter((tipo) => tipo.modulos.includes(this.anexo_modulo.rota)) : []
        },
        subitemsFiltred(){
            return this.search_subitems ? this.subitems.filter((sitem) => {return sitem.descricao.toLowerCase().includes(this.search_subitems.toLowerCase())}) : this.subitems
        }
    },
    methods: {
        getItems(){
            url = `${base_url}anexo/items/${this.anexo_modulo.rota}`
            if (this.search_items) {
                url += `/${this.search_items}`
            }

            if (this.anexo_modulo && this.anexo_tipo) {
                window.$.ajax({
                    method: "GET",
                    url: url,
                })
                .then((response) => {
                    this.items = response
                    this.subitems = []
                })
                .catch(() => {
                    this.items = []
                    this.subitems = []
                })
            }
        },
        getSubItems(){
            if (this.anexo_modulo && this.anexo_tipo && this.item) {
                window.$.ajax({
                    method: "GET",
                    url: `${base_url}anexo/subitems/${this.anexo_modulo.rota}/${this.anexo_tipo.slug}/${this.item}`,
                })
                .then((response) => {
                    this.subitems = response
                })
                .catch(() => {
                    this.subitems = []
                })
            }
        },
        getServicos(){
            if (this.item) {
                window.$.ajax({
                    method: "GET",
                    url: `${base_url}anexo/items/${this.anexo_modulo.rota}/${this.anexo_tipo.slug}/${this.search_items}`,
                })
                .then((response) => {
                    this.servicos_tipos = response.servicos_tipos
                })
                .catch(() => {
                    this.servicos_tipos = []
                })
            }
        }
    },
    watch: {
        anexo_modulo(){
            if(this.anexo_modulo) {
                this.modulo = this.anexo_modulo.rota
                this.anexo_tipo = null
                return this.getItems()
            }
            this.anexo_tipo = null
            this.modulo = null
        },
        anexo_tipo(){
            if (this.anexo_tipo) {
                this.tipo = this.anexo_tipo.slug
                return this.getItems()
            }
            this.tipo = null
            this.anexo_servico = null
        },
        anexo_servico(){
            if (this.anexo_servico) {
                this.servico = this.anexo_servico.id_ativo_configuracao
            }
        },
        item(){
            if(this.item) {
                this.getSubItems()
            }
        }
    },
})

</script>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
