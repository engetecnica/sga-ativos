<page>
    <style media="print"><?php echo $css;?></style>
    <header>
        <img src="<?php echo $header;?>">
    </header>
    <section class="termo" id="content">
        <h1>Termo de Retirada de Insumo #<?php echo $detalhes->id_insumo_retirada; ?></h1>
        <p>Termo de responsabilidade para retirada de insumos da obra, gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?></p>
        <h2><?php echo $razaosocial; ?></h2>
        <h2>Dados</h2>

        <?php //echo "<pre>"; print_r($detalhes); echo "</pre>"; ?>
        <p>
            Texto 
        </p>

        <table class="tabela">
			<thead>
					<tr>
						<th>Item</th>
						<th>Código</th>
						<th>Quantidade</th>
						<th>Data da Retirada</th>
						<th>Visto do Comodatário na retirada</th>
					</tr>
			</thead>
			<tbody>
                <?php foreach($detalhes->insumos as $insumo){ ?>
				<tr>
                    <td><?php echo $insumo->id_insumo; ?></td>
                    <td><?php echo $insumo->id_insumo; ?></td>
                    <td><?php echo $insumo->quantidade; ?></td>
                    <td><?php echo $insumo->created_at; ?></td>
                    <td> ____/____/______ </td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="6" style="font-size:11px">Escreva comentários abaixo:</td>
                </tr>
                <tr>
                    <td colspan="6" style="height: 120px; background-color: #EDEDED !important"></td>
                </tr>
			</tbody>
	</table>

	<div class="assinaturas">
			<div class="assinatura">
				<label>COMODANTE</label>
				<br><span>__________________________________________________________________</span><br>
				<small><b>ENGETÉCNICA ENGENHARIA E CONSTRUÇÃO LTDA</b></small><br>
				<small>Representante:</small> <small><b><?php echo $this->session->userdata('logado')->nome; ?></b></small><br>
				<small>E-mail:</small> <small><b><?php echo $this->session->userdata('logado')->email; ?></b></small><br><br><br>
			</div>

			<div class="assinatura">
				<label>COMODATÁRIO</label>
				<br><span>__________________________________________________________________</span><br>
				<small><b><?php echo $detalhes->id_funcionario; ?></b></small><br>
			</div>
	</div>
</section>

<footer>
			<img src="<?php echo $footer; ?>"><br>
			<small> 
                Retirada <?php echo "#{$detalhes->id_insumo_retirada}";?> em <?php echo $data_hora; ?></small> <small>, 
                Gerado em <?php echo date('d/m/Y H:i:s', strtotime('now')); ?>
            </small> 
</footer>
</page>