<?php if($this->permitido($permissoes, 13, 'editar') || $this->permitido($permissoes, 13, 'excluir')){ ?>
<div class="btn-group" role="group">
    <button id="ferramental_requisicao_detalhes" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Gerenciar
    </button>
    <div class="dropdown-menu" aria-labelledby="ferramental_requisicao_detalhes">
        <?php if($this->permitido($permissoes, 13, 'visualizar')){ ?>
            <a class="dropdown-item btn" href="<?php echo base_url("ferramental_estoque/detalhes/{$row->id_retirada}"); ?>">
                <i class="fas fa-list"></i> Detalhes
            </a>
            
            <?php if(isset($row->termo_de_responsabilidade)) { ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item btn" target="_blank" href="<?php echo base_url("assets/uploads/{$row->termo_de_responsabilidade}"); ?>">
                <i class="fa fa-print"></i>&nbsp;Ver Termo de Resp.
            </a>
            <?php } ?>
        <?php } ?>
        <?php if($row->status == 1) {?>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item btn" href="<?php echo base_url("ferramental_estoque/editar/{$row->id_retirada}"); ?>">
                <i class="fas fa-edit"></i> Editar  
            </a>
            <?php if($this->permitido($permissoes, 13, 'excluir')){ ?>
            <div class="dropdown-divider"></div>
            <a 
                class="dropdown-item btn confirmar_registro"  data-tabela="<?php echo base_url("ferramental_estoque");?>" 
                href="javascript:void(0)" data-registro="<?php echo $row->id_retirada;?>"
                data-acao="Remover Retirada"  data-redirect="true"
                data-href="<?php echo base_url("ferramental_estoque/remove_retirada/{$row->id_retirada}");?>"
            >                                                   
                <i class="fas fa-trash"></i> Excluir                                               
            </a>
            <?php } ?>
        <?php } ?>
        <?php if($this->permitido($permissoes, 13, 'editar')){ ?>
            <?php if($row->status == 9) {?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item btn" href="<?php echo base_url("ferramental_estoque/renovar_retirada/{$row->id_retirada}"); ?>">
                    <i class="fas fa-clone 4x"></i> Renovar Retirada 
                </a>                                                        
            <?php } ?>                                                
        <?php } ?>                                                
    </div>
</div>
<?php } else { ?>
    -
<?php } ?>