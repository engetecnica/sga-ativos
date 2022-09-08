<?php if($this->permitido($permissoes, 4, 'editar') || $this->permitido($permissoes, 4, 'excluir')){ ?>
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
        <?php if($this->permitido($permissoes, 4, 'editar')){ ?>
        <a 
            class="dropdown-item " 
            href="<?php echo base_url('empresa'); ?>/editar/<?php echo $row->id_empresa; ?>"
        >
            <i class="fas fa-edit"></i> Editar
        </a>
        <?php } ?>
        <?php if($this->permitido($permissoes, 4, 'excluir')){ ?>
        <div class="dropdown-divider"></div>
        <a 
            class="dropdown-item deletar_registro" 
            href="javascript:void(0)" 
            data-href="<?php echo base_url('empresa'); ?>/deletar/<?php echo $row->id_empresa; ?>" 
            data-registro="<?php echo $row->id_empresa;?>" 
            data-tabela="empresa"
        >
            <i class="fas fa-trash"></i> Excluir
        </a>
        <?php } ?>
    </div>
</div>
<?php } ?>