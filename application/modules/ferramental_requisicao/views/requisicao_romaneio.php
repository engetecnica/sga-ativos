<page>
    <style media="print"><?php echo $css;?></style>
    <header>
        <img src="<?php echo $header;?>">
    </header>
    <section class="termo" id="content">
        <h1>Romaneio Requisição #<?php echo $requisicao->id_requisicao; ?></h1>
        <p>Romaneio de requisição de ferramentas, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?></p>
        <h2>Dados</h2>
        <table class="tabela">
            <thead>
                <tr>
                    <th>ID da Requisição</th>
                    <th>Data Inclusão</th>
                    <th>Data Liberação</th>
                    <th>Data Transferência</th>
                    <th>Tipo</th>
                    <th>Situação</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $requisicao->id_requisicao; ?></td>
                    <td><?php echo $this->formata_data_hora($requisicao->data_inclusao);?> </td>
                    <td><?php echo $this->formata_data_hora($requisicao->data_liberado); ?></td>
                    <td><?php echo $this->formata_data_hora($requisicao->data_transferido); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $requisicao->tipo == 1 ? 'primary': 'secondary';?>"><?php echo $requisicao->tipo == 1 ? 'Requisição': 'Devolução';?></span>
                    </td>
                    <td>
                        <?php $status = $this->status($requisicao->status); ?>
                        <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                    </td>
                </tr>
                <tr>
                    <th>Solicitada por</th>
                    <th>Despachada por</th>
                    <th>Obra Origem</th>
                    <th>Origem Endereço</th>
                    <th>Obra Destido</th>
                    <th>Destido Endereço</th>
                </tr>
                <tr>
                    <td><?php echo ucfirst($requisicao->solicitante); ?> </td>
                    <td><?php echo ucfirst($requisicao->despachante); ?> </td>
                    <td><?php echo $requisicao->origem; ?></td>
                    <td><?php echo $requisicao->origem_endereco. " " . $requisicao->origem_endereco_numero; ?></td>
                    <td><?php echo $requisicao->destino;?> </td>
                    <td><?php echo $requisicao->destino_endereco. " " . $requisicao->destino_endereco_numero; ;?> </td>
                </tr>
                <tr>
                    <th>Origem Responsavel </th>
                    <th>Origem Responsavel Celular</th>
                    <th>Origem Responsavel Email</th>
                    <th>Destino Responsavel</th>
                    <th>Destino Responsavel Celular</th>
                    <th>Destino Responsavel Email</th>
                </tr>
                <tr>
                    <td><?php echo ucfirst($requisicao->origem_responsavel ?: '-'); ?> </td>
                    <td><?php echo $requisicao->origem_responsavel_celular ?: '-'; ?></td>
                    <td><?php echo $requisicao->origem_responsavel_email ?: '-' ?></td>
                    <td><?php echo ucfirst($requisicao->destino_responsavel ?: '-'); ?> </td>
                    <td><?php echo $requisicao->destino_responsavel_celular ?: '-';?> </td>
                    <td><?php echo $requisicao->destino_responsavel_email ?: '-';?> </td>
                </tr>
                <tr>
                    <th>É uma Requisição Complementar?</th>
                    <th>Requisição Mãe</th>
                    <th>Possui uma Requisição Complementar?</th>
                    <th>Requisição Filha</th>
                    <th>Qnt. Items Transferidos</th>
                    <th>Qnt. Ativos Transferidos</th>
                </tr>
                <tr>
                    <td><?php echo $requisicao->id_requisicao_mae != null ? 'Sim' : 'Não';?> </td>
                    <td><?php echo $requisicao->id_requisicao_mae ?: '-';?> </td>
                    <td><?php echo $requisicao->id_requisicao_filha != null ? 'Sim' : 'Não';?> </td>
                    <td><?php echo $requisicao->id_requisicao_filha ?: '-';?> </td>
                    <?php 
                        $total_items = 0; $total_ativos = 0;
                        foreach($requisicao->items as $item) {
                            if($item->status == 3) {
                                $total_items++;
                                foreach($item->ativos as $ativo) if($ativo->situacao == 3) $total_ativos++;
                            }
                        }
                        ?>
                    <td><?php echo $total_items; ?> </td>
                    <td><?php echo $total_ativos; ?></td>
                </tr>
            </tbody>
        </table>
        <h2>Itens e Ativos</h2>
        <?php 
            foreach($requisicao->items as $item) { 
                //if ($item->status == 3) { 
            ?>
            <p style="background-color:#CCC">Item: <?php echo $item->id_requisicao_item;?></p>
            <table class="tabela" style="width:100%">
                <thead>
                    <tr>
                        <th>ID Item</th>
                        <th>Nome</th>
                        <th>Qtde. Solcitada</th>
                        <th>Qtde. Liberada</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $item->id_requisicao_item;?> </td>
                        <td><?php echo $item->nome ?: '-';?> </td>
                        <td><?php echo $item->quantidade;?> </td>
                        <td><?php echo $item->quantidade_liberada;?> </td>
                    </tr>
                </tbody>
            </table>
            
            <?php if($item->ativos){ ?>
            <table class="tabela" style="width:100%">
                <thead>
                    <tr>
                        <th>ID Ativo</th>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                    </tr>
                </thead>
                <?php 
                    foreach($item->ativos as $ativo) {
                        if ($ativo->status == 3) { 
                    ?>
                <tbody>
                    <tr>
                        <td><?php echo $ativo->id_requisicao_ativo; ?> </td>
                        <td><?php echo $ativo->codigo;?> </td>
                        <td><?php echo $ativo->nome;?> </td>
                        <td><?php echo $ativo->categoria ?: '-';?> </td>
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
        <?php } } ?>

        <div class="assinaturas">
            <div class="assinatura">
                <label>Responsável pelo Recebimento</label><br>
                <br><span>__________________________________________________________________</span> <small>&nbsp; Data: </small> <span>___/___/______</small><br>
                <!-- To-do: até resolver questão de vincular funcionário a usuario -->
            </div>
        </div>
    </section>
    <footer>
        <img src="<?php echo $footer; ?>">
    </footer>
</page>