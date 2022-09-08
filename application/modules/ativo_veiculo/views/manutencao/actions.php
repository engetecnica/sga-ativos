<?php if (!isset($row->data_saida) || $user->nivel == 1) { ?>
<div class="btn-group" role="group">
    <button id="btnGroupGerenciarManutencao" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Gerenciar
    </button>

    <div class="dropdown-menu" aria-labelledby="btnGroupGerenciarManutencao">
        <a class="dropdown-item " href="<?php echo base_url("ativo_veiculo/manutencao/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_manutencao}");?>">
        <i class="fa fa-edit"></i> Editar
        </a>
        <?php if(!$row->data_saida && isset($row->ordem_de_servico)){ ?>
            <div class="dropdown-divider"></div>
            <a
                class="dropdown-item  confirmar_registro" data-tabela="<?php echo base_url("ativo_veiculo/manutencao/{$row->id_ativo_veiculo}");?>" 
                href="javascript:void(0)" data-registro=""
                data-acao="Marca como Finalizada"  data-redirect="true"
                data-href="<?php echo base_url("ativo_veiculo/manutencao_saida/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_manutencao}");?>"
            >
            <i class="fa fa-check"></i>&nbsp; Marca como Finalizada
            </a>
        <?php } ?>
        <?php if(isset($row->comprovante) && $row->comprovante != null){ ?>
            <?php if($permit_edit){ ?> <div class="dropdown-divider"></div> <?php } ?>
            <a class="dropdown-item" href="<?php echo base_url("ativo_veiculo/manutencao/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_manutencao}#anexos"); ?>">
                <i class="fa fa-files-o"></i>&nbsp; Anexos
            </a>
        <?php } ?>
        <?php if(!$row->data_saida){ ?>
            <div class="dropdown-divider"></div>
            <a href="javascript:void(0)" data-href="<?php echo base_url("ativo_veiculo/manutencao/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_manutencao}"); ?>" 
                data-registro="<?php echo $row->id_ativo_veiculo;?>" data-redirect="true"
                data-tabela="ativo_veiculo/manutencao/<?php echo $row->id_ativo_veiculo; ?>" class="dropdown-item  deletar_registro"><i class="fa fa-trash"></i> Excluir</a>
        <?php } ?>
    </div>
</div>
<?php } else { ?>
    -
<?php } ?>