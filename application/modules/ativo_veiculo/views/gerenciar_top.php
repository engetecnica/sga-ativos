<div class="row">
    <div class="col-lg-12">
        <h2 class="title-1 m-b-25"><?php echo $veiculo->veiculo_descricao; ?></h2>
        <div class="table-responsive-md table--no-card m-b-40" style="margin-bottom: 100px; ">
            <table class="table table-borderless table-striped table-earning dataTable">
                <thead>
                    <tr>
                        <th>Placa/Id Interno Máquina</th>
                        <th>Km Atual</th>
                        <th>Horímetro Atual</th>
                        <th>Valor De Aquisição</th>
                        <th>Valor Atual</th>
                        <th>Data de Inclusão</th>
                        <th>Gerenciar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $veiculo->veiculo_identificacao; ?></td>
                        <td><?php echo ($veiculo->veiculo_km_atual ?? 0) . " Km"; ?></td>
                        <td><?php echo ($veiculo->veiculo_horimetro_atual ?? 0) . " horas"; ?></td>
                        <td><?php echo $this->formata_moeda($veiculo->valor_fipe ?? 0); ?></td>
                        <td><?php echo $this->formata_moeda($veiculo->veiculo_valor_atual ?? $veiculo->valor_fipe); ?></td>
                        <td><?php echo $this->formata_data_hora($veiculo->data); ?></td>
                        <td> 
                            <?php 
                                echo $this->load->view('index/actions', [
                                    "btn_text" => "Gerenciar Veículo",
                                    "permissoes" => $permissoes,
                                    "row" => $veiculo
                                ], true); 
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>