<?php if ((int) $row->estoque > 0) { ?>
    <select class="js-example-basic-multiple cadMultiple<?php echo $row->id_ativo_externo_grupo; ?>" name="patrimonios[]" multiple="multiple">
        <?php for ($i = 0; $i < (int) $row->estoque; $i++) { ?>
            <option value="<?php echo $row->ativos[$i]->codigo; ?>"><?php echo $row->ativos[$i]->codigo; ?></option>
        <?php } ?>
    </select>
<?php } else { ?>
    -
<?php } ?>
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
</script>