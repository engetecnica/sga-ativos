<!-- col-md-6 -->
<div class="col-12" id="dados_operacionais">
    <div class="top-campaign">
        <h3 class="title-3">Dados Operacionais de Veículos e Máquinas</h3>
        <p class="m-b-30">Lançamentos e Extratos de Quilometragem e/ou Tempo de Operação de veiculos e máquinas</p>

        <div class="row">
           <div class="col col-12">
                <div class="row m-b-10">
                    <div class="col col-3">
                        <label>Tipo de Transação</label>
                    </div>
                    <div class="col col-3">
                        <select v-model="tipo_transacao" class="form-control">
                            <option :value="null">Selecione um Tipo</option>
                            <option :value="'lancamento'">Lançamento</option>
                            <option :value="'extrato'">Extrato</option>
                        </select>
                    </div>
         
                    <div class="col col-3">
                        <label>Tipo de Operação</label>
                    </div>
                    <div class="col col-3">
                        <select v-model="tipo_operacao" class="form-control">
                            <option :value="null">Selecione um Tipo</option>
                            <option :value="'km'">Quilometragem</option>
                            <option :value="'operacao'">Tempo de Operação (Máquinas)</option>
                        </select>
                    </div>
                </div>

                <div v-if="enableTransacao" class="row">
                    <div class="col col-3">
                        <label>Veículo ID</label>
                    </div>
                    <div class="col col-3">
                        <input v-model="veiculo_id" type="number" min="1" class="form-control">
                    </div>

                    <div class="col col-3">
                        <label>Veículo Placa</label>
                    </div>
                    <div class="col col-3">
                        <input v-model="veiculo_placa" type="text" class="form-control veiculo_placa">
                    </div>
                </div>

                <div v-if="veiculo_dados" class="row m-b-30 m-t-30">
                    <div class="col">
                        <p class="m-b-10"><strong>DADOS DO VEICULO</strong></p>    
                        <table class="table table-responsive-md table-striped table-bordered">
                            <tr>
                                <th>Tipo</th>
                                <th>Placa</th>
                                <th>Modelo</th>
                                <th>Ano</th>
                                <th>Data de Inclusão</th>
                            </tr>
                            <tr>
                                <td>{{ window._.capitalize(veiculo_dados.tipo_veiculo) }}</td>
                                <td>{{ veiculo_dados.veiculo_placa ? veiculo_dados.veiculo_placa : '-' }}</td>
                                <td>{{ veiculo_dados.descricao }}</td>
                                <td>{{ veiculo_dados.ano }}</td>
                                <td>{{ formata_data_hora(veiculo_dados.data) }}</td>
                            </tr>
                        </table>

                        <table v-if="veiculo_extrato" class="table table-responsive-md table-striped table-bordered m-t-20">
                            <tr>
                                <th class="text-info">Crédito</th>
                                <th class="text-danger">Débito</th>
                                <th class="text-success">Saldo em {{veiculo_extrato.tipo}}</th>
                                <th class="text-secondary" v-if="veiculo_extrato.tipo == 'KM'"> {{veiculo_extrato.tipo}} Atual</th>
                            </tr>
                            <tr>
                                <td>{{ veiculo_extrato.credito ? veiculo_extrato.credito : 0 }} {{veiculo_extrato.tipo}}</td>
                                <td>{{ veiculo_extrato.debito ? veiculo_extrato.debito : 0 }} {{veiculo_extrato.tipo}}</td>
                                <td>{{ veiculo_extrato.saldo ? veiculo_extrato.saldo : 0 }} {{veiculo_extrato.tipo}}</td>
                                <td v-if="veiculo_extrato.tipo == 'KM'">
                                    {{ veiculo_extrato.veiculo_km_atual }} {{veiculo_extrato.tipo}}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <small class="text-danger text-sm small-m-t" v-if="(!veiculo_dados && veiculo_dados_find === false) && !loading" >Veículo não encontrado!</small>
                <small v-if="loading" class="text-secondary text-sm small-m-t">Buscando dados do Veículo ...</small>
                
 
                <div v-if="veiculo_dados" class="m-t-20"> 
                    <div v-if="tipo_transacao == 'lancamento' && tipo_operacao == 'km'" class="row">
                        <div class="col col-3">
                            <label>Quilometragem Atual</label>
                        </div>
                        <div class="col col-3">
                            <input v-model="veiculo_km" :min="veiculo_dados.veiculo_km_atual ? parseInt(veiculo_dados.veiculo_km_atual) + 1 : parseInt(veiculo_dados.veiculo_km) + 1" type="number" class="form-control">
                        </div>
                    </div>

                    <div v-if="tipo_transacao == 'lancamento' && tipo_operacao == 'operacao'" class="row m-b-20">
                        <div class="col col-2">
                            <label>Tempo de Operação</label>
                        </div>
                        <div class="col col-3">
                            <input v-model="veiculo_operacao.tempo" type="number" class="form-control">
                        </div>
                    </div>


                    <div v-if="tipo_transacao == 'lancamento' && tipo_operacao == 'operacao'" class="row">
                        <div class="col col-2">
                            <label>Início do Período</label>
                        </div>
                        <div class="col col-3">
                            <input v-model="veiculo_operacao.periodo.inicio" type="date" class="form-control">
                        </div>
                        <div class="col col-2">
                            <input v-model="veiculo_operacao.periodo.inicio_hora" type="text" v-mask="'##:##:##'" placeholder="hh:mm:ss" class="form-control">
                        </div>
                    </div>

                    <div v-if="tipo_transacao == 'lancamento' && tipo_operacao == 'operacao'" class="row m-t-10">
                        <div class="col col-2">
                            <label>Final do Período</label>
                        </div>
                        <div class="col col-3">
                            <input v-model="veiculo_operacao.periodo.fim" type="date" class="form-control">
                        </div>
                        <div class="col col-2">
                            <input v-model="veiculo_operacao.periodo.fim_hora" v-mask="'##:##:##'" placeholder="hh:mm:ss" type="text" class="form-control">
                        </div>
                    </div>
                </div>


                <div class="m-t-30 pull-left row">
                    <a  v-if="veiculo_historico" class="btn btn-outline-info" data-toggle="modal" href="" data-target="#historicoModal">
                        <i class="fa fa-history"></i>&nbsp; Ver Histórico de {{ veiculo_historico.title }}
                    </a>
                </div>


                <div class="m-t-30 pull-right row">
                    <div class="col" v-if="enableTransacao">
                        <a v-if="formIsModified" class="btn btn-secondary text-white" @click="limpar()" >Limpar</a>
                        <button 
                            type="button" :disabled="!formIsValid" class="btn btn-primary text-white"
                            @click.prevent="tipo_transacao == 'lancamento' ? lancar() : consultar()" 
                        >
                            {{ tipo_transacao == 'lancamento' ? 'Lançar' : 'Consultar'}}
                        </button>
                    </div>
                </div>
           </div>
        </div>

    </div>

    <div v-if="veiculo_historico" class="modal" tabindex="100" role="dialog" id="historicoModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-history"></i>&nbsp; Histórico de {{ veiculo_historico.title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div v-if="tipo_operacao == 'operacao'" class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive table--no-card m-b-40">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tempo de Operação</th>
                                        <th>Período Início</th>
                                        <th>Período Fim</th>
                                        <th>Data de Inclusão</th>
                                        <th>Excluir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(historico, i) in veiculo_historico.data" >
                                        <td>{{ historico.id_ativo_veiculo_operacao }}</td>
                                        <td>{{ `${historico.operacao_tempo} Horas` }}</td>
                                        <td>{{ formata_data_hora(historico.operacao_periodo_inicio) }}</td>
                                        <td>{{formata_data_hora(historico.operacao_periodo_fim) }}</td>
                                        <td>{{ formata_data_hora(historico.data_inclusao) }}</td>
                                        <td>
                                            <a v-if="i === 0" class="btn btn-danger text-white" @click="deletar('operacao', historico.id_ativo_veiculo, 'id_ativo_veiculo_operacao', historico.id_ativo_veiculo_operacao)"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div v-if="window.user.nivel == 1" class="col-12 text-right">
                        <a  class="btn btn-secondary" :href="`${window.base_url}ativo_veiculo/gerenciar/operacao/${veiculo_dados.id_ativo_veiculo}`">
                            <i class="fa fa-list"></i>&nbsp; Ver Todos
                        </a>
                    </div>
                </div>

                <div v-if="tipo_operacao == 'km'" class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive table--no-card m-b-40">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                    <tr>
                                        <th width="10%">ID</th>
                                        <th width="40%">KM Atual</th>
                                        <th width="40%">Data de Inclusão</th>
                                        <th>Excluir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(historico, i) in veiculo_historico.data" >
                                        <td>{{ historico.id_ativo_veiculo_quilometragem }}</td>
                                        <td>{{ `${historico.veiculo_km} KM` }}</td>
                                        <td>{{ formata_data(historico.data) }}</td>
                                        <td>
                                            <a v-if="i === 0" class="btn btn-danger text-white" @click="deletar('km', historico.id_ativo_veiculo, 'id_ativo_veiculo_quilometragem', historico.id_ativo_veiculo_quilometragem,)"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div v-if="window.user.nivel == 1" class="col-12 text-right">
                        <a  class="btn btn-secondary" :href="`${window.base_url}ativo_veiculo/gerenciar/quilometragem/${veiculo_dados.id_ativo_veiculo}`">
                            <i class="fa fa-list"></i> Ver Todos
                        </a>
                    </div>
                </div>

            </div>
            </div>
        </div>
    </div>
    
 </div>

 <script>
     var dados_operacionais = new Vue({
         el: "#dados_operacionais",
         data(){
            return {
                loading: false,
                modal_options: {backdrop: true, keyboard: false, show: false},
                tipo_transacao: null,
                tipo_operacao: null,
                veiculo_id: null,
                veiculo_placa: null,
                veiculo_km: null,
                veiculo_operacao: {
                    tempo: null,
                    periodo: {
                        inicio: null,
                        fim: null,
                        inicio_hora: null,
                        fim_hora: null,
                    }
                },
                veiculo_dados: null,
                veiculo_dados_find: null,
                veiculo_extrato: null,
                veiculo_historico: null,
                edit_id: null,
            }
         },
         computed: {
            formIsModified(){
                return Object.values(this.$data).some((element) => {
                    return (element != null && element != undefined) && element !== false;
                })
            },
            formTimeIsValid(){
                let inicio = new Date(`${this.veiculo_operacao.periodo.inicio} ${this.veiculo_operacao.periodo.inicio_hora}`)
                let fim = new Date(`${this.veiculo_operacao.periodo.fim} ${this.veiculo_operacao.periodo.fim_hora}`)
                let now = new Date()
                return [
                    inicio && inicio != 'Invalid Date',
                    fim && fim != 'Invalid Date',
                    inicio < now,
                    fim > inicio,
                    fim < now
                ].every((i) => i)    
            },
            formIsValid(){
                switch (this.tipo_transacao) {
                    case "extrato": 
                        return this.veiculo_dados
                    break;
                    case "lancamento": 
                        if (this.veiculo_operacao.tempo > 0)
                            this.veiculo_operacao.periodo = {
                                inicio: null,
                                fim: null,
                                inicio_hora: null,
                                fim_hora: null,
                            }

                        if (this.formTimeIsValid) this.veiculo_operacao.tempo = null 

                        let operacao = (this.veiculo_operacao.tempo > 0 || this.formTimeIsValid)

                        let km_atual = false
                        if (this.veiculo_dados ) km_atual = this.veiculo_dados && this.veiculo_dados.veiculo_km_atual ? (parseInt(this.veiculo_dados.veiculo_km_atual) + 1) : (parseInt(this.veiculo_dados.veiculo_km) + 1)
                
                        let km = (this.veiculo_km && km_atual) && parseInt(this.veiculo_km) >= km_atual
                        return this.veiculo_dados && (km || operacao)
                    break;
                }
                return false
            },
            enableTransacao(){
                return this.tipo_transacao && this.tipo_operacao
            }
         },
         methods:{
            limpar(){
                this.tipo_transacao = null
                this.tipo_operacao = null
                this.veiculo_id = null
                this.veiculo_placa = null
                this.veiculo_km = null
                this.veiculo_dados = null
                this.veiculo_extrato = null
                this.veiculo_historico = null
                this.veiculo_dados_find = null
                this.veiculo_operacao =  {
                    tempo: null,
                    periodo: {
                        inicio: null,
                        fim: null,
                        inicio_hora: null,
                        fim_hora: null,
                    }
                }
                this.edit_id = null
             },
             async lancar(){
                if (this.formIsValid && this.tipo_transacao == 'lancamento') {
                    window.show_comfirm_msg({
                        title: 'Você tem certeza?',
                        text: "Esta operação não poderá ser revertida a menos que seja um administrador.",
                        icon: 'warning',
                        confirmButtonText: 'Sim, Lançar!'
                    }, async (result) => {
                        if (result.value) {
                            this.loading = true
                            let form = {}

                            if (this.tipo_operacao == 'km' ) {
                                form = {veiculo_km: this.veiculo_km}
                            }
                            
                            if (this.tipo_operacao == 'operacao' ) {
                                form = {
                                    operacao_tempo:  this.veiculo_operacao.tempo,
                                    operacao_periodo_inicio: this.veiculo_operacao.periodo.inicio,
                                    operacao_periodo_inicio_hora: this.veiculo_operacao.periodo.inicio_hora,
                                    operacao_periodo_fim: this.veiculo_operacao.periodo.fim,
                                    operacao_periodo_fim_hora: this.veiculo_operacao.periodo.fim_hora,
                                }
                            }
                                
                            let status = await axios
                            .post(`${window.base_url}/ativo_veiculo/lancar_operacao/${this.tipo_operacao}/${this.veiculo_dados.id_ativo_veiculo}`, JSON.stringify(form))
                            .then(async (response) => {
                                let status = !response.data ? response.statusText == "OK" : response.data.success
                                if (!status) window.show_msg('Erro ao Salvar', response.data.message, 'error')
                                return status
                            })

                            this.loading = false
                            if (status) {
                                window.show_msg('Dados salvos', 'Dados salvo com Sucesso!', 'success')
                                this.tipo_transacao = 'extrato'
                                return this.consultar()
                            }
                        }
                    })
                }
            },
            async consultar(){
                if (this.veiculo_dados && this.tipo_transacao == 'extrato') {
                    this.loading = true
                    let data = await axios
                    .get(`${window.base_url}/ativo_veiculo/consultar_extrato/${this.tipo_operacao}/${this.veiculo_dados.id_ativo_veiculo}`)
                    .then((response) => { return (response.status == 200) ? response.data : null })
                    this.veiculo_extrato = data.extrato
                    this.veiculo_historico = data.historico
                    this.loading = false
                }
             },
             async buscarVeiculo(coluna, valor){
                var regex_placa = '[A-Z]{3}[0-9][0-9A-Z][0-9]{2}'
                if ((coluna == "veiculo_placa" && valor.replace('-', '').match(regex_placa)) || (coluna == "id_ativo_veiculo" && valor > 0) ) {
                    this.loading = true
                    this.veiculo_dados = await axios
                    .get(`${window.base_url}/ativo_veiculo/buscar_veiculo/${coluna}/${valor}`)
                    .then(function(response) { return (response.status == 200) ? response.data : null})

                    this.veiculo_dados_find = false
                    if (this.veiculo_dados) {
                        this.veiculo_dados_find = true
                        this.veiculo_extrato = null
                        if (coluna == 'id_ativo_veiculo'){this.veiculo_placa = null}
                        if (coluna == 'veiculo_placa'){this.veiculo_id = null}
                        if (this.tipo_transacao == 'lancamento' && this.tipo_operacao == 'km') this.veiculo_km = this.veiculo_dados.veiculo_km_atual ? this.veiculo_dados.veiculo_km_atual : this.veiculo_dados.veiculo_km
                    }
                    this.loading = false
                }
             },
             async deletar(tipo, id_ativo_veiculo, coluna, valor){
                window.show_comfirm_msg({
                        title: 'Você tem certeza?',
                        text: "Esta operação não poderá ser revertida.",
                        icon: 'warning',
                        confirmButtonText: 'Sim, Excluir!'
                }, async (result) => {
                    if (result.value) {
                        let form = {}; form[coluna] = valor
                        let status = await axios
                        .post(`${window.base_url}/ativo_veiculo/deletar_operacao/${tipo}/${id_ativo_veiculo}`, JSON.stringify(form))
                        .then(function(response) {
                            return (response.status == 200) ? response.data : null
                        })

                        if (status.success)this.consultar()
                        else window.show_msg('Erro ao Excluir', response.message, 'error')
                    }
                })

             },
             resetVeiculo(){
                if (!this.veiculo_placa || !this.veiculo_id) 
                    this.veiculo_dados = null
                    this.veiculo_extrato = null
                    this.veiculo_historico = null
             },
             resetVeiculoExtrato(){
                if (this.tipo_transacao == 'lancamento' && this.veiculo_extrato) {
                    this.veiculo_extrato = null
                    this.veiculo_historico = null
                }

                if (this.tipo_transacao == 'extrato' && (this.veiculo_extrato && this.veiculo_extrato.id_ativo_veiculo != this.veiculo_dados.id_ativo_veiculo)) {
                    this.veiculo_extrato = null
                    this.veiculo_historico = null
                }

                if (!this.veiculo_dados && this.veiculo_placa) this.buscarVeiculo('veiculo_placa', this.veiculo_placa)
                if (!this.veiculo_dados && this.veiculo_id) this.buscarVeiculo('id_ativo_veiculo', this.veiculo_id)
             },
             reloadMasks(){
                setTimeout(() => {if (this.enableTransacao) window.loadMasks() }, 100)
             },
         },
         watch: {
            tipo_transacao(){
               this.reloadMasks()
               this.resetVeiculoExtrato()
            },
            tipo_operacao(){
               this.reloadMasks()
               this.resetVeiculoExtrato()
            },
            veiculo_placa(){
                this.resetVeiculo()
                if (this.veiculo_placa) this.buscarVeiculo('veiculo_placa', this.veiculo_placa)
            },
            veiculo_id(){
                this.resetVeiculo()
                if (this.veiculo_id) this.buscarVeiculo('id_ativo_veiculo', this.veiculo_id)
            },
            veiculo_historico(){
                if (this.veiculo_historico != null)
                    $('#historicoModal').modal(this.modal_options)            
            },
            veiculo_dados(){
                if (this.veiculo_dados && (this.tipo_transacao == 'lancamento' && this.tipo_operacao == 'km')) this.veiculo_km = this.veiculo_dados.veiculo_km_atual ? this.veiculo_dados.veiculo_km_atual : this.veiculo_dados.veiculo_km
            },
         },
         mounted(){
            this.$on("consultar", () => this.consultar())
            this.buscarVeiculo('id_ativo_veiculo', this.veiculo_id)
         },
     })
     

 </script>