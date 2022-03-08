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
            <option :value="'km'">Quilometragem (Veículos)</option>
            <option :value="'operacao'">Horimetro (Tempo de Operação Máquinas)</option>
        </select>
    </div>
</div>

<div v-if="enableTransacao" class="row m-b-10">
    <div class="col col-3">
        <label>Veículo ID</label>
    </div>
    <div class="col col-3">
        <input v-model="veiculo_id" type="number" min="1" class="form-control">
    </div>
</div>
    
<div v-if="enableTransacao" class="row m-b-10">
    <div class="col col-3">
        <label>Veículo Placa</label>
    </div>
    <div class="col col-3">
        <input v-model="veiculo_placa" type="text" class="form-control veiculo_placa">
    </div>
    <div class="col col-3">
        <label>ID Interno (Máquinas)</label>
    </div>
    <div class="col col-3">
        <input v-model="id_interno_maquina" type="text" class="form-control id_interno_maquina">
    </div>
</div>