<div class="btn-group" role="group">
    <button id="<?php echo "requisicao_group{$row->id_requisicao}";?>" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Gerenciar
    </button>
    <div class="dropdown-menu" aria-labelledby="<?php echo "requisicao_group{$row->id_requisicao}";?>">
    <a 
        class="dropdown-item" 
        href="<?php echo base_url("ferramental_requisicao/detalhes/{$row->id_requisicao}");?>"
    >
        <i class="fas fa-list"></i> Detalhes 
    </a>
    <?php if ($row->status == 3) { ?>
        <div class="dropdown-divider" ></div>
        <a 
            class="dropdown-item"
            href="<?php echo base_url("ferramental_requisicao/gerar_romaneio/{$row->id_requisicao}");?>"
        >
            <i class="fa fa-table"></i>&nbsp; Gerar Romaneio 
        </a>
    <?php } ?>
    <?php if ($row->status == 4) { ?>
        <div class="dropdown-divider" ></div>
        <a 
            class="dropdown-item" target="_blank"
            href="<?php echo base_url("assets/uploads/{$row->romaneio}");?>"
        >
            <i class="fa fa-eye"></i>&nbsp; Visualizar Romaneio 
        </a>
        <div class="dropdown-divider" ></div>
        <a 
            class="dropdown-item" download
            href="<?php echo base_url("assets/uploads/{$row->romaneio}");?>"
        >
            <i class="fa fa-download"></i>&nbsp; Baixar Romaneio 
        </a>
    <?php } ?>
    </div>
</div>