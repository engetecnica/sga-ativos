<?php
    $no_edit_notification_email = !((isset($row->email) && isset($row->email_confirmado_em)) && $row->nivel == 1); 
?>

<a 
    class="form-check" 
    for="permit_notification_email_switch_<?php echo $row->id_usuario; ?>"
>
    <input 
        class="form-check-input permit_notification_email_switch" 
        type="checkbox" 
        role="switch" 
        id="permit_notification_email_switch_<?php echo $row->id_usuario; ?>" 
        data-id="<?php echo $row->id_usuario; ?>"
        <?php 
            echo isset($row->permit_notification_email) && $row->permit_notification_email == 1 ? ' checked="true" ' : ' '; 
            echo $no_edit_notification_email ? " readonly disabled" : "";
        ?>
    >
</a>