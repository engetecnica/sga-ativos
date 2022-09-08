<span class="badge badge-<?php echo ($row->obra) ? 'success' : 'info' ?> historico-veiculo" >
    <?php if($row->obra){ ?>
        <?php echo $row->obra; ?>
    <?php } else { ?>
        Desconhecido
    <?php } ?>
</span>    