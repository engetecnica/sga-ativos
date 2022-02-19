<?php $this->load->view('email_top', ["ilustration" => true, "assunto" => "Verificação de Email"]); ?>

<strong style="<?php echo $styles['strong'];?>" >Olá <b style="<?php echo $styles['strong > b'];?>">
    <?php echo isset($usuario->nome) ? ucfirst($usuario->nome) : ucfirst($usuario->usuario); ?>!</b><br><br>
</strong>

<p style="<?php echo $styles['p'];?>">
Essa é uma messagem de confirmação válida até <b style="<?php echo $styles['p > b'];?>"><?php echo $validade; ?></b>, 
clique no link abaixo para validar sua conta.
</p><br>

<a style="<?php echo $styles['btn'];?>" target="_blank" href="<?php echo base_url("login/confirmar_email/{$codigo}");?>">Clique aqui para Confirmar!</a>

<?php $this->load->view('email_footer'); ?>