<div class="col-12">
    <div class="top-campaign">
        <h3 class="title-3">Retiradas Pendentes</h3>
        <p>Com devolução prevista até <?php echo $this->formata_data_hora($informe_retiradas_pendentes['vencimento']); ?></p><br>
        <?php if (count($informe_retiradas_pendentes['relatorio']) > 0){ ?>
        <table class="table table-responsive table-borderless table-striped table-earning">
            <thead>
                <tr>
                    <th>Retirada ID</th>
                    <th>Obra</th>
                    <th>Funcionário</th>
                    <th>RG/CPF</th>
                    <th>Inclusão</th>
                    <th>Vencimento</th>
                    <th>Detalhes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($informe_retiradas_pendentes['relatorio'] as $i => $retirada) { $i++; ?>
                <tr>
                    <td>
                        <?php if ($retirada->id_obra === $user->id_obra) {?>
                            <a href="<?php echo base_url("ferramental_estoque/detalhes/$retirada->id_retirada") ?>"><?php echo $retirada->id_retirada; ?></a>
                        <?php } else {?>
                            <?php echo $retirada->id_retirada; ?>
                        <?php } ?>
                    </td>
                    <td><?php echo $retirada->obra ." - ". $retirada->obra_endereco; ?></td>
                    <td><?php echo $retirada->funcionario; ?></td>
                    <td><?php echo $retirada->funcionario_rg . " / ".$retirada->funcionario_cpf; ?></td>
                    <td><?php echo $this->formata_data_hora($retirada->data_inclusao);?> </td>
                    <td><?php echo $this->formata_data_hora($retirada->devolucao_prevista);?> </td>
                    <td>
                        <?php if ($retirada->id_obra === $user->id_obra) {?>
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo base_url("ferramental_estoque/detalhes/$retirada->id_retirada") ?>">Mais Detalhes</a>
                        <?php } else {?>
                            -
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else {  ?>
            <p>Nenhuma retirada pendênte</p>
        <?php } ?>
    </div>
</div>