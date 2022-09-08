<?php 
    $text = isset($row->email_confirmado_em) ?  'Sim' : 'Não';
    $class = isset($row->email_confirmado_em) ?  'success' : 'danger';
?>
<span class="badge badge-<?php echo $class;?>"><?php echo $text;?></span>