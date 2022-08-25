<?php if($this->permitido($permissoes, 13, 'editar')){ ?>
    <a class="" href="<?php echo base_url("ferramental_estoque/detalhes/{$row->id_retirada}"); ?>">    
        <?php echo $row->id_retirada; ?>
    </a>
<?php } else { ?>
    <?php echo $row->id_retirada; ?>
<?php } ?>