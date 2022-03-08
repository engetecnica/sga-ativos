<div class="col-12">
    <div class="top-campaign">
        <div class="col-md-12">
            <h3 class="title-3">Amostra de Patrimônio</h3>
            <p class="m-b-35">Ferramentas na Obra, Equipamentos na Obra, todos os veículos e Retiradas</p>
            <?php foreach($patrimonio->obras as $obra) { ?>
                
                <div class="table-responsive m-b-40">
                    <h4 class="title-5 m-t-10 m-l-10">Ferramentas</h4>
                    <?php if(count($obra->ferramentas) > 0) { ?>
                    <table class="table table-responsive-md table-borderless table-striped table-earning" id="lista">
                        <thead>
                            <tr>
                                <th scope="col" width="30%">ID</th>
                                <th scope="col" width="30%">Código</th>
                                <th scope="col" width="30%">Nome</th>
                                <th scope="col" width="30%">Registro</th>
                                <th scope="col" width="30%">Descarte</th>
                                <th scope="col" width="30%">Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($obra->ferramentas as $ferramenta) { ?>
                            <tr>
                                <td><?php echo $ferramenta->id_ativo_externo; ?></td>
                                <td><?php echo $ferramenta->codigo; ?></td>
                                <td><?php echo $ferramenta->nome; ?></td>
                                <td><?php echo date('d/m/Y H:i:s', strtotime($ferramenta->data_inclusao)); ?></td>
                                <td><?php echo isset($ferramenta->data_descarte) ? date('d/m/Y H:i:s', strtotime($ferramenta->data_descarte)) : '-'; ?></td>
                                <td>
                                <?php $situacao = $this->status($ferramenta->situacao);?>
                                    <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php } else { ?>
                        <p class="m-b-10 m-t-10 m-l-10">Nenhuma Ferramenta registrada no Local</p>
                    <?php } ?>
                </div>
                <div class="table-responsive m-b-40">
                    <h4 class="title-5 m-t-10 m-l-10">Equipamentos</h4>
                    <?php if(count($obra->equipamentos) > 0) { ?>
                        <table class="table table-responsive-md table-borderless table-striped table-earning" id="lista2">
                        <thead>
                            <tr>
                                <th scope="col" width="30%">ID</th>
                                <th scope="col" width="30%">Nome</th>
                                <th scope="col" width="30%">Registro</th>
                                <th scope="col" width="30%">Descarte</th>
                                <th scope="col" width="30%">Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($obra->equipamentos as $equipamento) { ?>
                            <tr>
                                <td><?php echo $equipamento->id_ativo_interno; ?></td>
                                <td><?php echo $equipamento->nome; ?></td>
                                <td><?php echo date('d/m/Y H:i:s', strtotime($equipamento->data_inclusao)); ?></td>
                                <td><?php echo isset($equipamento->data_descarte) ? date('d/m/Y H:i:s', strtotime($equipamento->data_descarte)) : '-'; ?></td>
                                <td>
                                <?php $situacao = $this->get_situacao($equipamento->situacao);?>
                                <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php } else { ?>
                        <p class="m-b-10 m-t-10 m-l-10">Nenhum Equipamento registrado no Local</p>
                    <?php } ?>
                </div>
                 <?php if($user->nivel == 1) { ?>                                           
                    <div class="table-responsive m-b-40">
                        <h4 class="title-5 m-t-10 m-l-10">Veículos</h4>
                        <?php if(count($patrimonio->veiculos) > 0) { ?>
                            <table class="table table-responsive-md table-borderless table-striped table-earning" id="lista3">
                            <thead>
                                <tr>
                                    <th scope="col" width="30%">ID</th>
                                    <th scope="col" width="30%">Placa / ID Interna</th>
                                    <th scope="col" width="30%">Tipo</th>
                                    <th scope="col" width="30%">Marca/Modelo</th>
                                    <th scope="col" width="30%">Kilometragem Inicial</th>
                                    <th scope="col" width="30%">Kilometragem Atual</th>
                                    <th scope="col" width="30%">Horimetro Inicial</th>
                                    <th scope="col" width="30%">Horimetro Atual</th>
                                    <th scope="col" width="30%">Situação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($patrimonio->veiculos as $j => $veiculo) { ?>
                                <tr>
                                    <td><?php echo $veiculo->id_ativo_veiculo; ?></td>
                                    <td><?php echo $veiculo->veiculo_placa ?: $veiculo->id_interno_maquina; ?></td>
                                    <td><?php echo ucfirst($veiculo->tipo_veiculo);?> </td>
                                    <td><?php echo isset($veiculo->marca) ? "{$veiculo->marca} - {$veiculo->modelo}" : '-';?> </td>
                                    <td><?php echo $veiculo->veiculo_km; ?></td>
                                    <td><?php echo $veiculo->veiculo_km_atual; ?></td>
                                    <td><?php echo $veiculo->veiculo_horimetro; ?></td>
                                    <td><?php echo $veiculo->veiculo_horimetro_atual; ?></td>
                                    <td>
                                    <?php $situacao = $this->get_situacao($veiculo->situacao);?>
                                    <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php } else { ?>
                            <p class="m-b-10 m-t-10 m-l-10">Nenhum Veículo registrado</p>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>