<div class="btn-group" role="group">
    <button id="btnGroupGerenciarQuilometragem" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Gerenciar
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupGerenciarQuilometragem">
    <?php $permit_edit = $this->ativo_veiculo_model->permit_edit_km($row->id_ativo_veiculo, $row->id_ativo_veiculo_quilometragem); ?>
        <?php if($permit_edit){ ?>
            <a class="dropdown-item " href="<?php echo base_url("ativo_veiculo/quilometragem/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_quilometragem}");?>">
            <i class="fa fa-edit"></i> Editar
            </a>
        <?php } ?>
        <?php if(isset($row->comprovante) && $row->comprovante != null){ ?>
            <?php if($permit_edit){ ?> <div class="dropdown-divider"></div> <?php } ?>
            <a class="dropdown-item" href="<?php echo base_url("ativo_veiculo/quilometragem/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_quilometragem}#anexos"); ?>">
                <i class="fa fa-files-o"></i>&nbsp; Anexos
            </a>
        <?php } ?>
        <?php if($this->ativo_veiculo_model->permit_delete_km($row->id_ativo_veiculo, $row->id_ativo_veiculo_quilometragem)){ ?>
            <div class="dropdown-divider"></div>
            <a href="javascript:void(0)" data-href="<?php echo base_url("ativo_veiculo/quilometragem/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_quilometragem}"); ?>" 
                data-registro="<?php echo $row->id_ativo_veiculo;?>" data-redirect="true" data-method="delete"
                data-tabela="ativo_veiculo/quilometragem/<?php echo $row->id_ativo_veiculo; ?>" 
                class="dropdown-item deletar_registro"
            >
            <i class="fa fa-trash"></i> Excluir
            </a>
        <?php } ?>
    </div>
</div>