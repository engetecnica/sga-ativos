<?php 

$this->load->view('email_top', ['ilustration' => ['forgot_password'], "assunto" => "Recuperar Senha"]); 
?>

<strong style="font-size: 25px;" >Olá <b style="color: #fd9e0f;">
    <?php echo isset($usuario->nome) ? ucfirst($usuario->nome) : ucfirst($usuario->usuario); ?>!</b><br><br>
    Seu código de recuperação é: <b style="color: #fd9e0f;"><?php echo $codigo;?></b>
</strong>
<p>
Essa é uma messagem de confirmação válida até <b style="color: #fd9e0f;"><?php echo date("d/m/Y H:i:s", strtotime($validade));?></b>,
clique no botão abaixo para Redefinir sua Senha.
</p>

<br>
<br>
<br>
<br>

<a style="background: #fd9e0f; color: #FFFFFF; font-weight: 400; font-size: 25px; padding: 20px 35px;
    text-decoration:none; border-radius: 5px; margin: 10px; cursor: pointer;"
     target="_blank" href="<?php echo base_url("login/nova_senha/{$codigo}")?>">Clique aqui para Redefinir sua Senha!</a>


<?php $this->load->view('email_footer'); ?>