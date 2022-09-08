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
        <?php if (isset($row->email) && !isset($row->email_confirmado_em)) {?>
            <a  
                href="#" class="dropdown-item solicitar_confirmacao_email" 
                data-redirect="true"
                data-id="<?php echo $row->id_usuario; ?>"
            >
                <i class="fa fa-envelope"></i>&nbsp; Enviar Email de Verificação
            </a>
            <div class="dropdown-divider"></div>
        <?php } ?>
        
        <a class="dropdown-item " href="<?php echo base_url('usuario'); ?>/editar/<?php echo $row->id_usuario; ?>"><i class="fas fa-edit"></i> Editar</a>
        
        <?php if($row->id_usuario != $user->id_usuario && $user->nivel == 1){ ?>
            <div class="dropdown-divider"></div>
            <a 
                href="javascript:void(0)" 
                data-href="<?php echo base_url('usuario'); ?>/deletar/<?php echo $row->id_usuario; ?>" 
                data-registro="<?php echo $row->id_usuario;?>" data-tabela="usuario" 
                class="dropdown-item  deletar_registro"
            >
                <i class="fas fa-trash"></i> Excluir
            </a>
        <?php } ?>
    </div>
</div>