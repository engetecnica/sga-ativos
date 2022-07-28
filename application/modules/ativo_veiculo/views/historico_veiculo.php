

<table class="table table-bordered table-striped">
    <thead>
        <tr class="active">
            <th>Veículo</th>
            <th>Obra</th>
            <th>Período</th>
            <th>Data</th>
            <th width="5%">#</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($historico_veiculo as $veiculo){ ?>
        <tr>
            <td><?php echo $veiculo->veiculo." - ".$veiculo->marca." - ".$veiculo->modelo; ?></td>
            <td><?php echo $veiculo->codigo_obra; ?></td>
            <td>
                <?php 
                    echo ($veiculo->periodo_inicial) ? $this->formata_data($veiculo->periodo_inicial) : '-'; 
                ?> 
                    à 
                <?php 
                    echo ($veiculo->periodo_final) ? $this->formata_data($veiculo->periodo_final) : '-'; 
                ?> 
            </td>
            <td><?php echo $this->formata_data_hora($veiculo->created_at); ?></td>
            <td style="text-align: center">
                <a href="javascript:void(0)" onclick="HistoricoExcluir('<?=$veiculo->id_veiculo_obra;?>', '<?=$veiculo->id_veiculo;?>')">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php } ?>
</tbody>
</table>