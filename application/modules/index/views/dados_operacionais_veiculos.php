<!-- col-md-6 -->
<div class="col-12" id="dados_operacionais">
    <div class="top-campaign">
        <h3 class="title-3">Dados Operacionais de Veículos e Máquinas</h3>
        <p class="m-b-30">Lançamentos e Extratos de Quilometragem e/ou Tempo de Operação de veiculos e máquinas</p>

        <div class="row">
           <div class="col col-12">
                <?php 
                    echo $this->view("dados_operacionais_veiculos_form");
                    echo $this->view("dados_operacionais_veiculos_dados");
                    echo $this->view("dados_operacionais_veiculos_historico");
                ?>

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
                id_interno_maquina: null,
                veiculo_km: null,
                veiculo_horimetro: null,
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
            formIsValid(){
                switch (this.tipo_transacao) {
                    case "extrato": 
                        return this.veiculo_dados
                    break;
                    case "lancamento": 
                        let horimetro_atual = false
                        if (this.veiculo_dados ) horimetro_atual = this.veiculo_dados && this.veiculo_dados.veiculo_horimetro_atual ? (parseInt(this.veiculo_dados.veiculo_horimetro_atual) + 1) : (parseInt(this.veiculo_dados.veiculo_horimetro) + 1)
                        let operacao = (this.veiculo_horimetro && horimetro_atual) && parseInt(this.veiculo_horimetro) >= horimetro_atual

                        let km_atual = false
                        if (this.veiculo_dados ) km_atual = this.veiculo_dados && this.veiculo_dados.veiculo_km_atual ? (parseInt(this.veiculo_dados.veiculo_km_atual) + 1) : (parseInt(this.veiculo_dados.veiculo_km) + 1)
                        let km = (this.veiculo_km && km_atual) && parseInt(this.veiculo_km) >= km_atual

                        return this.veiculo_dados && (km != null || operacao != null)
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
                this.id_interno_maquina = null,
                this.veiculo_km = null
                this.veiculo_dados = null
                this.veiculo_extrato = null
                this.veiculo_historico = null
                this.veiculo_dados_find = null
                this.veiculo_horimetro = null
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
                            if (this.tipo_operacao == 'km' ) form = {veiculo_km: this.veiculo_km}
                            if (this.tipo_operacao == 'operacao' )  form = {veiculo_horimetro: this.veiculo_horimetro}

                            let status = await axios
                            .post(`${window.base_url}/ativo_veiculo/lancar_operacao/${this.tipo_operacao}/${this.veiculo_dados.id_ativo_veiculo}`, JSON.stringify(form))
                            .then(async (response) => {
                                let status = !response.data ? response.statusText == "OK" : response.data.success
                                if (!status) {
                                    window.show_msg('Erro ao Salvar', response.data.message, 'error')
                                    this.load_veiculo_dados()
                                }
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
                var regex_id_interno_maquina = '[A-Z]{3}-[A-Z]{3}-[0-9]{4}'
                if (
                    (coluna == "veiculo_placa" && valor.replace('-', '').match(regex_placa)) || 
                    (coluna == "id_interno_maquina" && valor.match(regex_id_interno_maquina)?.length) || 
                    (coluna == "id_ativo_veiculo" && valor > 0) 
                ) {
                    this.loading = true
                    this.veiculo_dados = await axios
                    .get(`${window.base_url}/ativo_veiculo/buscar_veiculo/${coluna}/${valor}`)
                    .then(function(response) { return (response.status == 200) ? response.data : null})

                    this.veiculo_dados_find = false
                    if (this.veiculo_dados) {
                        this.veiculo_dados_find = true
                        this.veiculo_extrato = null
                        if (coluna == 'id_ativo_veiculo'){
                            this.veiculo_placa = null
                            this.id_interno_maquina = null
                        }
                        if (coluna == 'veiculo_placa'){
                            this.veiculo_id = null
                            this.id_interno_maquina = null
                        }
                        if (coluna == 'id_interno_maquina'){
                            this.veiculo_id = null
                            this.veiculo_placa = null
                        }
                        if (this.tipo_transacao == 'lancamento' && this.tipo_operacao == 'km') {
                            this.veiculo_km = this.veiculo_dados.veiculo_km_atual ? this.veiculo_dados.veiculo_km_atual : this.veiculo_dados.veiculo_km
                        }

                        this.load_veiculo_dados()
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
                        let form = {}; 
                        form[coluna] = valor
                        let status = await axios
                        .post(`${window.base_url}/ativo_veiculo/deletar_operacao/${tipo}/${id_ativo_veiculo}`, JSON.stringify(form))
                        .then(function(response) {return (response.status == 200) ? response.data : null})

                        if (status.success) this.consultar()
                        else window.show_msg('Erro ao Excluir', response.message, 'error')
                    }
                })

             },
             resetVeiculo(){
                if (!this.veiculo_placa || !this.veiculo_id || !this.id_interno_maquina) {
                    this.veiculo_dados = null
                    this.veiculo_extrato = null
                    this.veiculo_historico = null
                }
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
                if (!this.veiculo_dados && this.id_interno_maquina) this.buscarVeiculo('id_interno_maquina', this.id_interno_maquina)
                if (!this.veiculo_dados && this.veiculo_id) this.buscarVeiculo('id_ativo_veiculo', this.veiculo_id)
             },
             load_veiculo_dados(){
                if (this.veiculo_dados && this.tipo_transacao == 'lancamento') {
                    if (this.tipo_operacao == 'km') {
                        this.veiculo_km = 
                            this.veiculo_dados.veiculo_km_atual ? 
                            this.veiculo_dados.veiculo_km_atual : 
                            this.veiculo_dados.veiculo_km
                    }
                    if (this.tipo_operacao == 'operacao') {
                        this.veiculo_horimetro = 
                            this.veiculo_dados.veiculo_horimetro_atual ? 
                            this.veiculo_dados.veiculo_horimetro_atual : 
                            this.veiculo_dados.veiculo_horimetro
                    }
                }
            },
         },
         watch: {
            tipo_transacao(){
                window.reloadMasks()
                this.resetVeiculoExtrato()
            },
            tipo_operacao(){
                window.reloadMasks()
                this.resetVeiculoExtrato()
            },
            veiculo_placa(){
                this.resetVeiculo()
                if (this.veiculo_placa) this.buscarVeiculo('veiculo_placa', this.veiculo_placa)
            },
            id_interno_maquina(){
                this.resetVeiculo()
                if (this.id_interno_maquina) this.buscarVeiculo('id_interno_maquina', this.id_interno_maquina)
            },
            veiculo_id(){
                this.resetVeiculo()
                if (this.veiculo_id) this.buscarVeiculo('id_ativo_veiculo', this.veiculo_id)
            },
            veiculo_historico(){
                if (this.veiculo_historico != null) $('#historicoModal').modal(this.modal_options)            
            },
            veiculo_dados(){
                this.load_veiculo_dados()
            },
         },
         mounted(){
            this.$on("consultar", () => this.consultar())
            this.buscarVeiculo('id_ativo_veiculo', this.veiculo_id)
         },
     })
     

 </script>