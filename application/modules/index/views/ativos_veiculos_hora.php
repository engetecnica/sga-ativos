<div class="col-12">
    <div class="top-campaign">
        <h3 class="title-3">Manutenções De Veículos por Hora</h3>
        <p>Acompanhamento de manutenções em ativos internos </p><br>
        <?php if (count($manutencao_veiculos_hora) > 0){ ?>
        <table class="table table-borderless table-striped table-earning">
            <thead>
                <tr>
                    <th>ID Veículo</th>
                    <th>Tipo de Veículo</th>
                    <th width="30%">Veículo</th>
                    <th>Revisadas</th>
                    <th>Previsão</th>
                    <th>Restante</th>
                    <th>Última Revisão</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($manutencao_veiculos_hora as $manutencao){ 

                        $total_horas_livre = ($manutencao->veiculo_horimetro_proxima_revisao - $manutencao->veiculo_horimetro_atual);

                        if($total_horas_livre <= 100){
                ?>
                <tr>
                    <th><?php echo $manutencao->id_ativo_veiculo; ?></th>
                    <th><?php echo $manutencao->tipo_veiculo; ?></th>
                    <th width="30%"><?php echo $manutencao->modelo; ?></th>
                    <th><?php echo $manutencao->veiculo_horimetro_atual; ?></th>
                    <th><?php echo $manutencao->veiculo_horimetro_proxima_revisao ?></th>
                    <th><?php echo $total_horas_livre; ?></th>
                    <th><?php echo date("d/m/Y", strtotime($manutencao->data_entrada)); ?></th>
                </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
        <?php } else {  ?>
            <p>Nenhuma manutenção de Equipamento em andamento ou pendente</p>
        <?php } ?>
    </div>
</div>
