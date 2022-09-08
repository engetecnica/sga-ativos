<?php 
    $text = isset($row->permit_notification_push) && $row->permit_notification_push == 1 ?  'Sim' : 'Não';
    $class = isset($row->permit_notification_push) && $row->permit_notification_push == 1 ?  'success' : 'danger';
?>
<span class="badge badge-<?php echo $class;?>"><?php echo $text;?></span>
                   