<?php 

$this->load->view('email_top', ['ilustration' => ['welcome'], "assunto" => "Boas Vindas! :)"]); 
?>

<strong style="<?php echo $styles['strong'];?>" >Olá <b style="<?php echo $styles['strong > b'];?>">
    <?php echo isset($usuario->nome) ? ucfirst($usuario->nome) : ucfirst($usuario->usuario); ?>!</b><br><br>
</strong>
<p style="<?php echo $styles['p'];?>">
Queremos desejar boas vindas,
Essa é uma messagem de confirmação válida até <b style="<?php echo $styles['p > b'];?>"><?php echo date("d/m/Y", strtotime($validade))?></b>.<br>
Clique no link abaixo para confirmar que podemos nos comunicar por aqui.
</p>

<br>
<br>
<br>
<br>

<a style="<?php echo $styles['btn'];?>" target="_blank" href="<?php echo base_url("login/confirmar_email/{$codigo}");?>">Clique aqui para Confirmar!</a>


<?php $this->load->view('email_footer'); ?>