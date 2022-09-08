<?php if($row->id_requisicao_mae != null) { ?>
    <a href="<?php echo base_url("ferramental_requisicao/detalhes/{$row->id_requisicao_mae}");?>">
        <?php echo $row->id_requisicao_mae; ?>
    </a>
<?php } else { echo '-';} ?>