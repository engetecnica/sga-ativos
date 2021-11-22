<?php $this->load->view('email_top', ['ilustration' => ['schedule_meeting'], "assunto" => "Retiradas Pêndentes de Devolução", "vencimento" => $vencimento]); ?>

<?php if (count($relatorio) > 0){ ?>
    <h3 style="<?php echo $styles['title'];?>">Retiradas</h3>
    <p style="<?php echo $styles['p'];?>">Com devolução prevista até <?php echo $this->formata_data_hora($vencimento); ?></p><br>
    <table style="<?php echo $styles['table'];?>">
        <thead style="<?php echo $styles['thead'];?>">
            <tr style="<?php echo $styles['tr'];?>">
                <th style="<?php echo $styles['tr_td_th']; echo $styles['first_th'];?>" >ID Retirada</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Obra</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Funcionário</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >RG/CPF</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Data Inclusão</th>
                <th style="<?php echo $styles['tr_td_th'];?>" >Devolução Vencimento</th>
                <th style="<?php echo $styles['tr_td_th']; echo $styles['last_th'];?>" >Detalhes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($relatorio as $i => $retirada) { $i++; ?>
            <tr style="<?php echo $styles['tr']; echo ($i % 2 )== 0 ? $styles['tr']  : $styles['tr2'];?>">
                <td style="<?php echo $styles['tr_td_th'];?>" >
                    <a target="_blank" href="<?php echo base_url("ferramental_estoque/detalhes/$retirada->id_retirada") ?>"><?php echo $retirada->id_retirada; ?></a>
                </td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $retirada->obra ." - ". $retirada->obra_endereco; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $retirada->funcionario; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $retirada->funcionario_rg . " / ".$retirada->funcionario_cpf; ?></td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_data($retirada->data_inclusao);?> </td>
                <td style="<?php echo $styles['tr_td_th'];?>" ><?php echo $this->formata_data($retirada->devolucao_prevista);?> </td>
                <td style="<?php echo $styles['tr_td_th'];?>" >
                    <a target="_blank" href="<?php echo base_url("ferramental_estoque/detalhes/$retirada->id_retirada") ?>">Mais Detalhes</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

<?php } else {  ?>
  <strong  style="<?php echo $styles['strong'];?>" >Nenhum item encontrado para o período.</strong>
<?php } ?>

<br>
<p  style="<?php echo $styles['p'];?>">Relatório Informe de Retiradas Pêndentes de Devolução, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?></p>
<br> </br>

<?php $this->load->view('email_footer'); ?>