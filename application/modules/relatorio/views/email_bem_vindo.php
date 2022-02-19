<?php $this->load->view('email_top', ['ilustration' => true, "assunto" => "Boas Vindas! :)"]); ?>

<h2 style="<?php echo $styles['h2'];?>" >Olá <b>
    <?php echo isset($usuario->nome) ? ucfirst($usuario->nome) : ucfirst($usuario->usuario); ?>!</b>
</h2>

<p style="<?php echo $styles['p'];?>">
Queremos desejar boas vindas,
Essa é uma messagem de confirmação válida até <b style="<?php echo $styles['p > b'];?>"><?php echo $validade; ?></b>.<br>
Clique no link abaixo para confirmar que podemos nos comunicar por aqui.
</p><br>
<a style="<?php echo $styles['btn'];?>" target="_blank" href="<?php echo base_url("login/confirmar_email/{$codigo}");?>">Clique aqui para Confirmar!</a>


<?php $this->load->view('email_footer'); ?>