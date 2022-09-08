<?php if($this->permitido($permissoes, 11, 'editar') || $this->permitido($permissoes, 11, 'excluir')){ ?>
    <div class="btn-group" role="group">
        <button id="ativo_externo_group" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Gerenciar
        </button>
        <div class="dropdown-menu" aria-labelledby="ativo_externo_group">
            <?php if($this->permitido($permissoes, 11, 'adicionar')){ ?>
                <a class="dropdown-item " href="<?php echo base_url('ativo_externo/adicionar'); ?>/<?php echo $row->id_ativo_externo_grupo; ?>">
                    <i class="fa fa-plus"></i> Adicionar
                </a>
            <?php } ?>
            <?php if($this->permitido($permissoes, 11, 'editar')){ ?>
                <?php if ($row->estoque > $row->foradeoperacao) {?>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item " href="<?php echo base_url('ativo_externo'); ?>/editar_grupo/<?php echo $row->id_ativo_externo_grupo; ?>">
                    <i class="fa fa-edit"></i> Editar</a>
                <?php } ?>
            <?php } ?>
            <?php if($this->permitido($permissoes, 11, 'excluir')){ ?>
                <?php if ($row->estoque == $row->total && $this->ativo_externo_model->permit_delete_grupo($row->id_ativo_externo_grupo)) {?>
                    <div class="dropdown-divider"></div>
                    <a href="javascript:void(0)" data-href="<?php echo base_url('ativo_externo'); ?>/deletar_grupo/<?php echo $row->id_ativo_externo_grupo; ?>" data-registro="<?php echo $row->id_ativo_externo_grupo;?>" 
                    data-tabela="ativo_externo" class="dropdown-item  deletar_registro"><i class="fa fa-trash"></i>&nbsp; Excluir Grupo</a>
                <?php } ?>
                <?php if ($this->ativo_externo_model->permit_descarte_grupo($row->id_ativo_externo_grupo, $user->id_obra) && 
                    !$this->ativo_externo_model->verifica_descarte_grupo($row->id_ativo_externo_grupo, $user->id_obra)) {?>
                    <div class="dropdown-divider"></div>
                    <a href="javascript:void(0)" data-href="<?php echo base_url('ativo_externo'); ?>/descartar_grupo/<?php echo $row->id_ativo_externo_grupo; ?>" data-registro="<?php echo $row->id_ativo_externo_grupo;?>" 
                    redirect="true" data-tabela="ativo_externo" class="dropdown-item  confirmar_registro"><i class="fa fa-ban"></i>&nbsp;  Descartar Grupo</a>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
<?php } else {  echo "-"; } ?>