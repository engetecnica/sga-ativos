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
                                    <th>Horimetro</th>
                                    <th>Data</th>
                                    <th>Excluir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(historico, i) in veiculo_historico.data" >
                                    <td>{{ historico.id_ativo_veiculo_operacao }}</td>
                                    <td>{{ `${historico.veiculo_horimetro} Horas` }}</td>
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
                    <a  class="btn btn-secondary" :href="`${window.base_url}ativo_veiculo/operacao/${veiculo_dados.id_ativo_veiculo}`">
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
                                    <th width="40%">Quilometragem</th>
                                    <th width="40%">Data</th>
                                    <th>Excluir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(historico, i) in veiculo_historico.data" >
                                    <td>{{ historico.id_ativo_veiculo_quilometragem }}</td>
                                    <td>{{ `${historico.veiculo_km} KM` }}</td>
                                    <td>{{ formata_data_hora(historico.data) }}</td>
                                    <td>
                                        <a v-if="i === 0" class="btn btn-danger text-white" @click="deletar('km', historico.id_ativo_veiculo, 'id_ativo_veiculo_quilometragem', historico.id_ativo_veiculo_quilometragem,)"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-if="window.user.nivel == 1" class="col-12 text-right">
                    <a  class="btn btn-secondary" :href="`${window.base_url}ativo_veiculo/quilometragem/${veiculo_dados.id_ativo_veiculo}`">
                        <i class="fa fa-list"></i> Ver Todos
                    </a>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>