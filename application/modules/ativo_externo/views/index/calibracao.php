<?php 
    $text = isset($row->necessita_calibracao) && $row->necessita_calibracao === '1' ?  'Sim' : 'Não';
    $class = isset($row->necessita_calibracao) && $row->necessita_calibracao === '1' ?  'success' : 'danger';
?>
<span class="badge badge-<?php echo $class;?>"><?php echo $text;?></span>