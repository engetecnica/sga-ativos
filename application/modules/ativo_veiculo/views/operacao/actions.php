<?php
    $permit_edit = $this
            ->ativo_veiculo_model
            ->permit_edit_operacao($row->id_ativo_veiculo, $row->id_ativo_veiculo_operacao);

    if($permit_edit){ 
?>

<div class="btn-group" role="group">
    <button id="btnGroupGerenciarOperacao" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Gerenciar
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupGerenciarOperacao">
        <a class="dropdown-item " href="<?php echo base_url("ativo_veiculo/operacao/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_operacao}");?>">
            <i class="fa fa-edit"></i> Editar
        </a>
        <?php if(isset($anexos) && count($anexos) > 0){ ?>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item " href="<?php echo base_url("ativo_veiculo/operacao/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_operacao}#anexos"); ?>">
                <i class="fa fa-files-o"></i>&nbsp; Anexos
            </a>
        <?php } ?>
        <?php if($this->ativo_veiculo_model->permit_delete_operacao($row->id_ativo_veiculo, $row->id_ativo_veiculo_operacao)){ ?>
            <div class="dropdown-divider"></div>
            <a href="javascript:void(0)" data-href="<?php echo base_url("ativo_veiculo/operacao/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_operacao}"); ?>" 
                data-registro="<?php echo $row->id_ativo_veiculo;?>" data-redirect="true"
                data-tabela="ativo_veiculo/operacao/<?php echo $row->id_ativo_veiculo; ?>" 
                class="dropdown-item deletar_registro"
            >
                <i class="fa fa-trash"></i> Excluir
            </a>
        <?php } ?>
    </div>
</div>

<?php } else { echo '-'; } ?>