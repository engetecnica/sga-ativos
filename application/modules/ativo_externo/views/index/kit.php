<?php if($row->id_ativo_externo_kit) { ?>
    <a 
        href="<?php echo base_url("ativo_externo/editar_items/{$row->id_ativo_externo_kit}"); ?>"
    >
        <?php echo $row->kit_codigo; ?>
    </a>
<?php } else {echo "-";} ?>