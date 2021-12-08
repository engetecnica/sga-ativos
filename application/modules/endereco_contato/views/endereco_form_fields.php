
<div id="endereco_form_fields" class="m-t-40 m-b-40">
    <div class="row form-group">
        <div class="col col-md-2">
            <label for="endereco_cep" class=" form-control-label">CEP</label>
        </div>
        <div class="col-12 col-md-4">
            <input :class="{'invalid-input': msg != null}" @blur="getEnderecoBycep" 
                type="text" id="endereco_cep" name="endereco_cep"  v-mask="['#####-###']"
                v-model="endereco_cep" placeholder="00000-000" class="form-control cep">
            <div  class="invalid-text">{{msg}}</div>
        </div>
        <div class="col col-md-2">
            <label for="endereco" class=" form-control-label">Endereço</label>
        </div>
        <div class="col-12 col-md-4">
            <input type="text" id="endereco" name="endereco" v-model="endereco" placeholder="Av 13 de Maio" class="form-control" >
        </div>
    </div>

    <div class="row form-group">
        <div class="col col-md-2">
            <label for="endereco_numero" class=" form-control-label">Número</label>
        </div>
        <div class="col-12 col-md-4">
            <input type="text" id="endereco_numero" name="endereco_numero" v-model="endereco_numero" placeholder="9009" class="form-control">
        </div>
        <div class="col col-md-2">
            <label for="endereco_bairro" class=" form-control-label">Bairro</label>
        </div>
        <div class="col-12 col-md-4">
            <input type="text" id="endereco_bairro" name="endereco_bairro" v-model="endereco_bairro" placeholder="Bairro" class="form-control">
        </div>                                                                                              
    </div>

    <div class="row form-group">
        <div class="col col-md-2">
            <label for="endereco_cidade" class=" form-control-label">Cidade</label>
        </div>
        <div class="col-12 col-md-4">
            <input type="text" id="endereco_cidade" name="endereco_cidade" v-model="endereco_cidade" placeholder="Cidade" class="form-control">
        </div>
        <div class="col col-md-2">
            <label for="endereco_estado" class=" form-control-label">Estado</label>
        </div>
        <div class="col-12 col-md-4">
            <select id="endereco_estado" name="endereco_estado" v-model="endereco_estado" class="form-control select2">
                <option value="null">Selecione o Estado</option>
                <option v-for="estado in estados" :value="estado.id_estado">{{estado.estado}}</option>
            </select>
        </div>
    </div>

    <div class="row form-group">
        <div class="col col-md-2">
            <label for="endereco_complemento" class=" form-control-label">Complemento</label>
        </div>
        <div class="col-12 col-md-4">
            <input type="text" id="endereco_complemento" name="endereco_complemento" v-model="endereco_complemento" placeholder="Bloco L, Prox a Padaria" class="form-control" >
        </div>
    </div>
</div>

<script>
    var detalhes = `<?php echo isset($detalhes) ? json_encode($detalhes) : null; ?>`;
    if (detalhes.length > 0) {
        detalhes = JSON.parse(detalhes)
    } else {
        detalhes = null
    }

    var estados = JSON.parse('<?php echo json_encode($estados); ?>');

    var endereco_form_fields  = new Vue({
        el: "#endereco_form_fields",
        data(){
            return {
                msg: null,
                estados: window.estados,
                endereco: null,
                endereco_numero: null,
                endereco_cep: null,
                endereco_bairro: null,
                endereco_cidade: null,
                endereco_estado: null,
                endereco_complemento: null,
            }
        },
        methods: {
            getEnderecoBycep(){
                if (this.endereco_cep && this.endereco_cep.length >= 8) {
                    window.$.ajax({
                        method: "GET",
                        url: `https://viacep.com.br/ws/${this.endereco_cep.replace('-', '')}/json`,
                    })
                    .done(function(response) {
                        if (!response.erro) {
                            endereco_form_fields.msg = null
                            endereco_form_fields.endereco = response.logradouro
                            endereco_form_fields.endereco_bairro = response.bairro
                            endereco_form_fields.endereco_complemento = response.complemento
                            endereco_form_fields.endereco_cidade = response.localidade
                            endereco_form_fields.endereco_cep = response.cep
                            endereco_form_fields.endereco_estado = parseInt((endereco_form_fields.estados.find((estado) => {return estado.uf.toLowerCase() === response.uf.toLowerCase()})).id_estado)
                            setTimeout(() => {$(".select2").select2()})
                            return
                        }
                        endereco_form_fields.msg = "CEP não localizado!"
                    })
                    .fail(() => {
                        endereco_form_fields.msg = "CEP não localizado!"
                    })
            
                }    
            },

        },
        watch: {
            endereco_cep(){
                if (this.endereco_cep && this.endereco_cep.length >= 8) {
                    this.getEnderecoBycep()
                }
            }
        },
        mounted(){
            if (window.detalhes) { 
                this.endereco = window.detalhes.endereco
                this.endereco_estado = window.detalhes.endereco_estado
                this.endereco_bairro = window.detalhes.endereco_bairro
                this.endereco_numero = window.detalhes.endereco_numero
                this.endereco_complemento = window.detalhes.endereco_complemento
                this.endereco_cidade = window.detalhes.endereco_cidade
                this.endereco_cep = window.detalhes.endereco_cep
            }
        }
    })
</script>