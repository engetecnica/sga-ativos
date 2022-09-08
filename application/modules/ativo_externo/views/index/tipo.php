<?php if($row->tipo == 1) { ?>
    <button class="badge badge-primary badge-sm">Kit</button>
<?php } elseif($row->tipo == 0) { ?>
    <button class="badge badge-secondary badge-sm">Unidade</button>
<?php } elseif($row->tipo == 2) { ?>
    <button class="badge badge-secondary badge-sm">Metro</button>
<?php } else { ?>
    <button class="badge badge-secondary badge-sm">Conjunto</button>
<?php } ?>