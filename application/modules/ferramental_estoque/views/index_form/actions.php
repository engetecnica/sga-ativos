<?php if ((int) $row->estoque > 0) { ?>
    <a class="btn btn-sm btn-primary data-table-action mt-1" onclick="estoque.addItem(<?php echo $row->id_ativo_externo_grupo; ?>)">
        <i class="fas fa-plus text-light"></i>
    </a>
<?php } else { ?>
    -
<?php } ?>