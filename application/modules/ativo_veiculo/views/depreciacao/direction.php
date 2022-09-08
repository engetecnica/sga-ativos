<span style="<?php echo $row->valores->direcao === 'up' ? "color: green;" : "color: red;" ;?>">
    <?php echo $row->valores->direcao === 'up' ? "+ " : "- " ; echo $value ?? 0; ?>
</span>