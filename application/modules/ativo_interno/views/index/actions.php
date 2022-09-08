<?php if($this->permitido($permissoes, 10, 'editar') || $this->permitido($permissoes, 10 , 'excluir')){ ?>

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
        <?php if($this->permitido($permissoes, 10, 'editar')){ ?>
            <a class="dropdown-item " href="<?php echo base_url("ativo_interno/editar/{$row->id_ativo_interno}"); ?>"> 
                <i class="fas fa-edit"></i> Editar
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item " href="<?php echo base_url("ativo_interno/manutencao/{$row->id_ativo_interno}"); ?>">
                <i class="fa fa-wrench"></i>&nbsp; Manutenção
            </a>
            <?php if((int) $row->situacao < 2){?>
            <div class="dropdown-divider"></div>
            <a 
                class="dropdown-item  confirmar_registro"
                href="javascript:void(0)"
                class="confirmar_registro"
                data-registro="<?php echo $row->id_ativo_interno;?>"
                data-href="<?php echo base_url("ativo_interno/descartar/{$row->id_ativo_interno}");?>"
                data-tabela="<?php echo base_url("ativo_interno");?>"
                data-icon="info" data-message="false"
                data-acao="Descartar"
                data-title="Confirmar descarte do ativo" data-redirect="true"
                data-text="Clique 'Sim, Confirmar!' para confirmar o descarte do ativo."

            >                                                
                <i class="fas fa-ban"></i> Descartar                                              
            </a>
            <?php } ?>

            <?php if((int) $row->situacao == 2 && $user->nivel == 1){?>
            <div class="dropdown-divider"></div>
            <a 
                class="dropdown-item  confirmar_registro"
                href="javascript:void(0)"
                class="confirmar_registro"
                data-registro="<?php echo $row->id_ativo_interno;?>"
                data-href="<?php echo base_url("ativo_interno/desfazer_descarte/{$row->id_ativo_interno}");?>"
                data-tabela="<?php echo base_url("ativo_interno");?>"
                data-icon="info" data-message="false"
                data-acao="Defazer"
                data-title="Defazer descarte do ativo" data-redirect="true"
                data-text="Clique 'Sim, Defazer!' para defazer o descarte do ativo."

            >                                                
                <i class="fas fa-undo"></i>&nbsp; Defazer descarte                                              
            </a>
            <?php } ?>
        <?php } ?>

        <?php if($this->permitido($permissoes, 10, 'excluir')){ ?>                                               
            <?php if((int) $row->situacao == 2 && !isset($row->data_descarte)){?>
                <div class="dropdown-divider"></div>
                <a 
                    class="dropdown-item  deletar_registro" 
                    href="javascript:void(0)" 
                    data-href="<?php echo base_url('ativo_interno'); ?>/deletar/<?php echo $row->id_ativo_interno; ?>" 
                    data-registro="<?php echo $row->id_ativo_interno;?>" 
                    data-tabela="ativo_interno" 
                > 
                    <i class="fas fa-trash"></i> Remover
                </a>
            <?php } ?>
        <?php } ?>

    </div>
</div>

<?php } else {  echo "-"; } ?>