<?php if($row->permit_edit || $row->permit_delete) { ?>
    <div class="btn-group" role="group">
        <button id="btnGroupGerenciarDepreciacao" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Gerenciar
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupGerenciarDepreciacao">
            <?php if($row->permit_edit){ ?>
                <a class="dropdown-item btn" href="<?php echo base_url("ativo_veiculo/depreciacao/editar/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_depreciacao}");?>">
                <i class="fa fa-edit"></i>Editar
                </a>
            <?php } ?>
            <?php if($row->permit_delete){ ?>
                <?php if($row->permit_edit){ ?> <div class="dropdown-divider"></div> <?php } ?>
                <a href="javascript:void(0)" data-href="<?php echo base_url("ativo_veiculo/depreciacao/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_depreciacao}"); ?>" 
                    data-registro="<?php echo $row->id_ativo_veiculo;?>" data-redirect="true"
                    data-tabela="ativo_veiculo/depreciacao/<?php echo $row->id_ativo_veiculo; ?>" class="dropdown-item btn deletar_registro">
                    <i class="fa fa-trash"></i> Excluir</a>
            <?php } ?>
        </div>
    </div>

<?php } else { ?>
    -
<?php } ?>