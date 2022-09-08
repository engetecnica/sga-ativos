<div class="btn-group" role="group">
    <button id="btnGroupGerenciarIpva" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Gerenciar
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupGerenciarIpva">
        <?php 
            $permit_edit = $this->ativo_veiculo_model->permit_edit_ipva($row->id_ativo_veiculo, $row->id_ativo_veiculo_ipva);
            if($permit_edit){ 
        ?>
            <a class="dropdown-item " href="<?php echo base_url("ativo_veiculo/ipva/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_ipva}");?>">
            <i class="fa fa-edit"></i>Editar
            </a>
        <?php } ?>
        <?php if(isset($row->comprovante) && $row->comprovante != null){ ?>
            <?php if($permit_edit){ ?> <div class="dropdown-divider"></div> <?php } ?>
            <a class="dropdown-item" href="<?php echo base_url("ativo_veiculo/ipva/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_ipva}#anexos"); ?>">
                <i class="fa fa-files-o"></i>&nbsp; Anexos
            </a>
        <?php } ?>
        <?php if($this->ativo_veiculo_model->permit_delete_ipva($row->id_ativo_veiculo, $row->id_ativo_veiculo_ipva)){ ?>
            <div class="dropdown-divider"></div>
            <a href="javascript:void(0)" data-href="<?php echo base_url("ativo_veiculo/ipva/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_ipva}"); ?>" 
                data-registro="<?php echo $row->id_ativo_veiculo;?>" data-redirect="true"
                data-tabela="ativo_veiculo/ipva/<?php echo $row->id_ativo_veiculo; ?>" class="dropdown-item deletar_registro">
                <i class="fa fa-trash"></i> Excluir</a>
        <?php } ?>
    </div>
</div>