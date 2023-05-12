<?php if ($this->permitido($permissoes, 10, 'editar') || $this->permitido($permissoes, 10, 'excluir')) { ?>

    <div class="btn-group">
        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Gerenciar
        </button>
        <div class="dropdown-menu">
            <?php if ($this->permitido($permissoes, 10, 'editar')) { ?>
                <a class="dropdown-item " href="<?php echo base_url("ativo_interno/marca/editar/{$row->id_ativo_interno_marca}"); ?>">
                    <i class="fas fa-edit"></i> Editar
                </a>
            <?php } ?>

            <?php if ($this->permitido($permissoes, 10, 'excluir')) { ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item  deletar_registro" href="javascript:void(0)" data-href="<?php echo base_url('ativo_interno'); ?>/marca/deletar/<?php echo $row->id_ativo_interno_marca; ?>" data-registro="<?php echo $row->id_ativo_interno_marca; ?>" data-tabela="ativo_interno/marca">
                    <i class="fas fa-trash"></i> Remover
                </a>
            <?php } ?>

        </div>
    </div>

<?php } else {
    echo "-";
} ?>