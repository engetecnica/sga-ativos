<?php if($this->permitido($permissoes, 3, 'editar') || $this->permitido($permissoes, 3, 'excluir')){ ?>
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
        <?php if($this->permitido($permissoes, 6, 'editar')){ ?>
            <a class="dropdown-item " href="<?php echo base_url('funcionario'); ?>/editar/<?php echo $row->id_funcionario; ?>"><i class="fas fa-edit"></i> Editar</a>
            <?php } ?>
            
        <?php if($this->permitido($permissoes, 6, 'excluir')){ ?>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item  deletar_registro" href="javascript:void(0)" data-href="<?php echo base_url('funcionario'); ?>/deletar/<?php echo $row->id_funcionario; ?>" data-registro="<?php echo $row->id_funcionario;?>" data-tabela="funcionario"><i class="fas fa-trash"></i> Excluir</a>
        <?php } ?>
    </div>
</div>
<?php } else { echo "-"; } ?>