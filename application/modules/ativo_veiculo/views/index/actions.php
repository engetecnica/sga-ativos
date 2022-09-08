<?php 
    $btn_text = isset($btn_text) ? ucwords($btn_text) : 'Gerenciar';
    $show_edit =  isset($show_edit) ? $show_edit : true;
    $show_delete =  isset($show_delete) ? $show_delete : true;
?>
<?php if($this->permitido($permissoes, 9, 'editar') || $this->permitido($permissoes, 9, 'excluir')){ ?>
    <div class="btn-group" role="group">
        <button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo $btn_text;?>
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
            <?php if($this->permitido($permissoes, 9, 'editar')){ ?>
                <?php if($show_edit) { ?>
                    <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/editar/'.$row->id_ativo_veiculo); ?>"><i class="fa fa-edit"></i> Editar</a>
                    <div class="dropdown-divider"></div>
                <?php } ?>
                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/quilometragem/'.$row->id_ativo_veiculo); ?>"><i class="fa fa-road"></i>&nbsp; Quilometragem</a>
                <div class="dropdown-divider"></div>
                <?php if (strtolower($row->tipo_veiculo) == "maquina") {?>
                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/operacao/'.$row->id_ativo_veiculo); ?>"><i class="fa fa-clock-o"></i>&nbsp;Operação</a>
                <div class="dropdown-divider"></div>
                <?php } ?>
                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/abastecimento/'.$row->id_ativo_veiculo); ?>"><i class="fas fa-gas-pump"></i>&nbsp; Abastecimento</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/manutencao/'.$row->id_ativo_veiculo); ?>"><i class="fas fa-wrench"></i> Manutenção</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/ipva/'.$row->id_ativo_veiculo); ?>"><i class="fa fa-id-card"></i> IPVA</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/seguro/'.$row->id_ativo_veiculo); ?>"><i class="fa fa-lock"></i> Seguro</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item " href="<?php echo base_url('ativo_veiculo/depreciacao/'.$row->id_ativo_veiculo); ?>"><i class="fa fa-sort-amount-asc"></i> Depreciação</a>
                <?php if ($show_edit) {?>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item " href="<?php echo base_url("ativo_veiculo/editar/{$row->id_ativo_veiculo}#anexos"); ?>"><i class="fa fa-files-o"></i> Anexos</a>
                <?php } ?>
            <?php } ?>
            <?php if($this->permitido($permissoes, 9, 'excluir')){ ?>
                <?php if ($this->ativo_veiculo_model->permit_delete($row->id_ativo_veiculo) && $show_delete) {?>
                    <div class="dropdown-divider"></div>
                    <a href="javascript:void(0)" data-href="<?php echo base_url('ativo_veiculo'); ?>/deletar/<?php echo $row->id_ativo_veiculo; ?>" data-registro="<?php echo $row->id_ativo_veiculo;?>" 
                    data-tabela="ativo_veiculo" class="dropdown-item  deletar_registro"><i class="fas fa-trash"></i>  Excluir</a>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
<?php } else {  echo "-"; } ?>