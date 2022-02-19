<?php $this->load->view('email_top', ["assunto" => "Recuperar Senha", "ilustration" => true]); ?>

<strong style="<?php echo $styles['strong'];?>" >Olá <b style="<?php echo $styles['strong > b'];?>">
    <?php echo isset($usuario->nome) ? ucfirst($usuario->nome) : ucfirst($usuario->usuario); ?>!</b><br><br>
    Seu código de recuperação é: <b style="<?php echo $styles['strong > b'];?>"><?php echo $codigo;?></b>
</strong>

<p style="<?php echo $styles['p'];?>">
Essa é uma messagem de confirmação válida até <b style="<?php echo $styles['p > b'];?>"><?php echo $validade;?></b>,
clique no botão abaixo para Redefinir sua Senha.
</p><br>

<a style="<?php echo $styles['btn'];?>" target="_blank" href="<?php echo base_url("login/nova_senha/{$codigo}")?>">Clique aqui para Redefinir sua Senha!</a>

<?php $this->load->view('email_footer'); ?>