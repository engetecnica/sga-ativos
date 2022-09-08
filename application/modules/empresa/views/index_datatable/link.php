<?php if($this->permitido($permissoes, 4, 'editar')){ ?>
<a href="<?php echo $link; ?>">
    <?php echo $text; ?>
</a>
<?php } else { ?>
    <?php echo $text; ?>
<?php } ?>