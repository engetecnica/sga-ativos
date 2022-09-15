<?php if ($row->permit_edit || $row->permit_delete){ ?>
<a href="<?php echo $link; ?>">
    <?php echo $text; ?>
</a>
<?php } else { ?>
    <?php echo $text; ?>
<?php } ?>