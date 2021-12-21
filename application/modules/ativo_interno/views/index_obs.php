<?php if(!isset($no_permit_edit))$no_permit_edit = false; ?>

<div class="row m-b-25 m-t-40">
    <div class="col-12 col-md-6">
        <h2 class="title-1 m-b-10">Observações</h2>
    </div>
    <?php if (!$no_permit_edit) { ?>
        <div class="col-12 col-md-6">
            <button class="pull-right btn btn-secondary" onclick="addObs()">
                <i class="fa fa-comments" aria-hidden="true"></i>&nbsp; Adicionar Observação
            </button>
        </div>
    <?php } ?>
</div>

<div class="table-responsive table--no-card m-b-40">
    <table class="table table-borderless table-striped table-earning" id="lista2">
        <thead>
            <tr>
                <th width="7%">Id</th>
                <th width="10%">Usuário</th>
                <th>Texto</th>
                <th width="10%">Data de Inclusão</th>
                <th width="10%">Data da Edição</th>
                <th width="10%" class="text-right">Opções</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($obs as $valor){ ?>
            <tr width="100%">
                <td><?php echo $valor->id_obs; ?></td>
                <td><?php echo ucwords($valor->usuario); ?></td>
                <td><?php echo ucwords($valor->texto); ?></td>
                <td><?php echo $this->formata_data($valor->data_inclusao); ?></td>
                <td><?php echo $this->formata_data($valor->data_edicao); ?></td>
                <td class="text-right">
                <?php if(!$no_permit_edit && ($valor->id_usuario == $user->id_usuario)){ ?>
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
                            <a class="dropdown-item" onclick="editObs('<?php echo $valor->id_obs; ?>', '<?php echo $valor->texto; ?>')" >
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <div class="dropdown-divider"></div>
                            <a 
                                href="javascript:void(0)#obs" 
                                data-href="<?php echo base_url("ativo_interno/manutencao_obs_remover/{$manutencao->id_manutencao}/{$valor->id_obs}"); ?>" 
                                data-registro="<?php echo $valor->id_obs;?>" 
                                data-tabela="ativo_interno/manutencao_editar/<?php echo "{$ativo->id_ativo_interno}/{$manutencao->id_manutencao}";?>" 
                                class="dropdown-item deletar_registro"
                            >
                                <i class="fas fa-trash"></i> Excluir
                            </a>
                        </div>
                    </div>
                    <?php } else { ?>
                        -
                    <?php } ?>
                </td>
            </tr>
           <?php } ?>
        </tbody>
    </table>
</div>