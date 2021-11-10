<?php 
    if (!isset($prefix)) {
        $prefix = "";
    } else {
        $prefix .= "_";
    }

    if (!isset($requireds)) {
        $requireds = [];
    }
?>

<div class="row form-group m-t-40 m-b-40">
    <?php 
        $telefone = "{$prefix}telefone"; 
        $celular = "{$prefix}celular";
        $email = "{$prefix}email";
    ?>
    <div class="col col-md-2">
        <label for="<?php echo $telefone; ?>" class="form-control-label">Telefone</label>
    </div>
    <div class="col-12 col-md-4">
        <input <?php echo in_array('telefone', $requireds) ? 'required="required"' : '';?> type="text" id="<?php echo $telefone; ?>" name="<?php echo $telefone; ?>" placeholder="(00) 0000-0000" class="telefone form-control" value="<?php if(isset($detalhes) && isset($detalhes->$telefone)){ echo $detalhes->$telefone; } ?>">
    </div>

    <div class="col col-md-2">
        <label for="<?php echo $celular; ?>" class="form-control-label">Celular</label>
    </div>
    <div class="col-12 col-md-4">
        <input <?php echo in_array('celular', $requireds) ? 'required="required"' : '';?> type="text" id="<?php echo $celular; ?>" name="<?php echo $celular; ?>" placeholder="(00) 9 0000-0000" class="celular form-control" value="<?php if(isset($detalhes) && isset($detalhes->$celular)){ echo $detalhes->$celular; } ?>">
    </div>
</div>

<div class="row form-group m-t-40 m-b-40">
    <div class="col col-md-2">
        <label for="<?php echo $email; ?>" class="form-control-label">Email</label>
    </div>
    <div class="col-12 col-md-4">
        <input <?php echo in_array('email', $requireds) ? 'required="required"' : '';?> type="email" id="<?php echo $email; ?>" name="<?php echo $email; ?>" placeholder="seuemail@exemplo.com.br" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->$email)){ echo $detalhes->$email; } ?>">
    </div> 
</div>