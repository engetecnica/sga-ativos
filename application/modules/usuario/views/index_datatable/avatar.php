<a class="avatar" href="<?php echo base_url("usuario/editar/{$row->id_usuario}"); ?>">
    <?php if (isset($row->avatar)) {?>
        <img src="<?php echo base_url("assets/uploads/avatar/{$row->avatar}"); ?>" alt="Imagem do usuário" />
    <?php } else {?>
        <img src="<?php echo base_url('assets/images/icon/avatar-01.jpg'); ?>" alt="Imagem do usuário" />
    <?php }?>
</a>