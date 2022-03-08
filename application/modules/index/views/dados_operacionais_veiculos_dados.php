<template v-if="veiculo_dados" >
    <div class="row m-b-30 m-t-30">
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
                    <td>{{ veiculo_dados.veiculo_placa ? veiculo_dados.veiculo_placa : veiculo_dados.id_interno_maquina }}</td>
                    <td>{{ veiculo_dados.descricao ? veiculo_dados.descricao : veiculo_dados.modelo }}</td>
                    <td>{{ veiculo_dados.ano }}</td>
                    <td>{{ formata_data_hora(veiculo_dados.data) }}</td>
                </tr>
            </table>
            <table v-if="veiculo_extrato" class="table table-responsive-md table-striped table-bordered m-t-20">
                <tr>
                    <th class="text-info">Crédito</th>
                    <th class="text-danger">Débito</th>
                    <th class="text-success">Saldo em {{veiculo_extrato.tipo}}</th>
                    <th class="text-secondary"> {{veiculo_extrato.tipo == 'KM' ? veiculo_extrato.tipo : 'Horimetro' }} Atual</th>
                </tr>
                <tr>
                    <td>{{ veiculo_extrato.credito ? veiculo_extrato.credito : 0 }} {{veiculo_extrato.tipo}}</td>
                    <td>{{ veiculo_extrato.debito ? veiculo_extrato.debito : 0 }} {{veiculo_extrato.tipo}}</td>
                    <td>{{ veiculo_extrato.saldo ? veiculo_extrato.saldo : 0 }} {{veiculo_extrato.tipo}}</td>
                    <td>{{ veiculo_extrato.tipo == 'KM' ? (veiculo_extrato.veiculo_km_atual || 0) : (veiculo_extrato.veiculo_horimetro_atual || 0)}} {{veiculo_extrato.tipo}}</td>
                </tr>
            </table>
        </div>
    </div>
            
    <div class="m-t-20"> 
        <div v-if="tipo_transacao == 'lancamento' && tipo_operacao == 'km'" class="row">
            <div class="col col-3">
                <label>Quilometragem Atual</label>
            </div>
            <div class="col col-3">
                <input v-model="veiculo_km" :min="veiculo_dados.veiculo_km_atual ? parseInt(veiculo_dados.veiculo_km_atual) + 1 : parseInt(veiculo_dados.veiculo_km) + 1" type="number" class="form-control">
            </div>
        </div>
        <div v-if="tipo_transacao == 'lancamento' && tipo_operacao == 'operacao' && veiculo_dados" class="row m-b-20">
            <div class="col col-3">
                <label>Horimetro/Tempo de Operação</label>
            </div>
            <div class="col col-3">
                <input v-model="veiculo_horimetro" :min="veiculo_dados.veiculo_horimetro_atual ? parseInt(veiculo_dados.veiculo_horimetro_atual) + 1 : parseInt(veiculo_dados.veiculo_horimetro) + 1" type="number" class="form-control">
            </div>
        </div>
    </div>

    <div class="m-t-30 pull-left row">
        <a  v-if="veiculo_historico" class="btn btn-outline-info" data-toggle="modal" href="" data-target="#historicoModal">
            <i class="fa fa-history"></i>&nbsp; Ver Histórico de {{ veiculo_historico.title }}
        </a>
    </div>
</template>
      
<small class="text-danger text-sm small-m-t" v-if="(!veiculo_dados && veiculo_dados_find === false) && !loading" >Veículo não encontrado!</small>
<small v-if="loading" class="text-secondary text-sm small-m-t">Buscando dados do Veículo ...</small>