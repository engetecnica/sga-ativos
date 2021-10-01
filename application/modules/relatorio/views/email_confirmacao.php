<?php 

$this->load->view('email_top', ['ilustration' => ['order_confirmed'], "assunto" => "Verificação de Email"]); 
?>

<strong style="font-size: 25px;" >Olá <b style="color: #fd9e0f;">
    <?php echo isset($usuario->nome) ? ucfirst($usuario->nome) : ucfirst($usuario->usuario); ?>!</b><br><br>
</strong>
<p>
Essa é uma messagem de confirmação válida até <b style="color: #fd9e0f;"><?php echo date("d/m/Y", strtotime($validade))?></b>, 
clique no link abaixo para validar sua conta.
</p>

<br>
<br>
<br>
<br>

<a style="background: #fd9e0f; color: #FFFFFF; font-weight: 400; font-size: 25px; padding: 20px 35px;
    text-decoration:none; border-radius: 5px; margin: 10px; cursor: pointer;"
     target="_blank" href="<?php echo base_url("login/confirmar_email/{$codigo}")?>">Clique aqui para Confirmar!</a>


<?php $this->load->view('email_footer'); ?>