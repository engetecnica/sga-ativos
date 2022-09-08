<?php 
if($this->permitido($permissoes, 11, 'editar')){ 
    $self_obra = $row->id_obra === $user->id_obra;
?>
    <?php if ($self_obra) {?>
        <a class="" href="<?php echo $link; ?>">    
            <?php echo $text; ?>
        </a>
    <?php } else { echo $text; } ?>                                        
<?php } else { echo $text; } ?> 