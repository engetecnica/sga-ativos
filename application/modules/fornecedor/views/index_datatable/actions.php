<?php //if($this->permitido($permissoes, 6, 'editar') || $this->permitido($permissoes, 6, 'excluir')){ ?>
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
            <a 
                class="dropdown-item " 
                href="<?php echo base_url('fornecedor'); ?>/editar/<?php echo $row->id_fornecedor; ?>"
            >
                <i class="fas fa-edit"></i> Editar
            </a>
            <div class="dropdown-divider"></div>
            <a 
                class="dropdown-item  deletar_registro" href="javascript:void(0)" 
                data-href="<?php echo base_url('fornecedor'); ?>/deletar/<?php echo $row->id_fornecedor; ?>" 
                data-registro="<?php echo $row->id_fornecedor;?>" data-tabela="fornecedor"
            >
                <i class="fas fa-trash"></i> Excluir
            </a>
        </div>
    </div>
<?php //} else {  echo "-"; } ?>