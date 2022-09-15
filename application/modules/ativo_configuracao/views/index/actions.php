<?php if ($row->permit_edit || $row->permit_delete) {?>
<div class="btn-group">
    <button 
        class="btn btn-secondary btn-sm dropdown-toggle" 
        type="button"
        data-toggle="dropdown" 
        aria-haspopup="true" 
        aria-expanded="false"
    >
        Gerenciar
    </button>
    <div class="dropdown-menu">
        <?php if ($row->permit_edit == 1) {?>
        <a 
            class="dropdown-item " 
            href="<?php echo base_url("ativo_configuracao/editar/{$row->id_ativo_configuracao}"); ?>"
        >
            <i class="fas fa-edit"></i> Editar
        </a>
        <?php } ?>
        <?php if ($row->permit_delete == 1) {?>
        <div class="dropdown-divider"></div>
        <a 
            class="dropdown-item deletar_registro" 
            href="javascript:void(0)" 
            data-href="<?php echo base_url("ativo_configuracao/deletar/{$row->id_ativo_configuracao}"); ?>" 
            data-registro="<?php echo $row->id_ativo_configuracao;?>" 
            data-tabela="ativo_configuracao"
        >
            <i class="fas fa-trash"></i> Excluir
        </a>
        <?php } ?>
    </div>
</div>
<?php } else { ?>
    -
<?php } ?>