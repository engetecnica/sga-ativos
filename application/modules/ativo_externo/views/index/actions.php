<?php 
$self_obra = $row->id_obra === $user->id_obra;
if($this->permitido($permissoes, 11, 'editar') || $this->permitido($permissoes, 11, 'excluir')){ 
?>

<div class="btn-group" role="group">
    <button id="ativo_externo_item" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Gerenciar
    </button>
    <div class="dropdown-menu" aria-labelledby="ativo_externo_item">

    <?php if($this->permitido($permissoes, 11, 'editar')){ ?>

        <?php if ($self_obra) {?>
            <a class="dropdown-item " href="<?php echo base_url('ativo_externo'); ?>/editar/<?php echo $row->id_ativo_externo; ?>"><i class="fa fa-edit"></i> Editar</a>
            <div class="dropdown-divider"></div>
        <?php } ?>
        
        <a class="dropdown-item " href="<?php echo base_url("ativo_externo/manutencao/{$row->id_ativo_externo}"); ?>">
        <i class="fa fa-wrench"></i>&nbsp; Manutenção
    </a>
    <div class="dropdown-divider"></div>
    
    <?php if(((isset($row) && isset($row->id_ativo_externo)) && $row->tipo == 1) && ($self_obra)) { ?>
        <a class="dropdown-item " href="<?php echo base_url("ativo_externo/editar_items/{$row->id_ativo_externo}"); ?>">
        <i class="fa fa-th-large"></i> Editar Itens (KIT)
    </a>
    <div class="dropdown-divider"></div>
    <?php } ?> 
    
    <?php if((isset($row) && isset($row->necessita_calibracao)) && $row->necessita_calibracao == 1) { ?>
        <a class="dropdown-item " href="<?php echo base_url("ativo_externo/certificado_de_calibracao/{$row->id_ativo_externo}"); ?>">
        <i class="fa fa-balance-scale"></i>&nbsp; Cert. de Calibração
    </a>
    <div class="dropdown-divider"></div>
    <?php } ?>
    
    
    <a class="dropdown-item " href="<?php echo base_url("anexo/index/12/{$row->id_ativo_externo}"); ?>">
    <i class="fa fa-files-o"></i> Anexos
</a>
<div class="dropdown-divider"></div>


    <?php if ($row->situacao == 8 && ($self_obra)) {?>
        <a href="javascript:void(0)" 
        data-href="<?php echo base_url('ativo_externo'); ?>/descartar/<?php echo $row->id_ativo_externo; ?>"  redirect="true" 
        data-tabela="ativo_externo" class="dropdown-item confirmar_registro"><i class="fa fa-ban"></i> Descartar</a>
        <div class="dropdown-divider"></div>
    <?php } ?>

<?php } ?>


    <?php if($this->permitido($permissoes, 11, 'excluir')){ ?>
        <?php if ($row->situacao == 12 && ($self_obra)) {?>
            <a href="javascript:void(0)" 
                data-href="<?php echo base_url('ativo_externo'); ?>/deletar/<?php echo $row->id_ativo_externo; ?>" 
                data-registro="<?php echo $row->id_ativo_externo;?>" 
                data-tabela="ativo_externo" class="dropdown-item  deletar_registro"
            >
                <i class="fa fa-trash"></i> Excluir
            </a>
        <?php } ?>
    <?php } ?>
    </div>
</div>

<?php } else {  echo "-"; } ?>