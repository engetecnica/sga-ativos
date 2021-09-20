<hr>
<?php 
    if (!isset($prefix)) {
        $prefix = "";
    } else {
        $prefix .= "_";
    }
?>

<div class="row form-group">
    <?php 
        $telefone = "{$prefix}telefone"; 
        $celular = "{$prefix}celular";
        $email = "{$prefix}email";
    ?>
    <div class="col col-md-1">
        <label for="<?php echo $telefone; ?>" class="form-control-label">Telefone</label>
    </div>
    <div class="col-12 col-md-3">
        <input type="text" id="<?php echo $telefone; ?>" name="<?php echo $telefone; ?>" placeholder="Telefone" class="telefone form-control" value="<?php if(isset($detalhes) && isset($detalhes->$telefone)){ echo $detalhes->$telefone; } ?>">
    </div>

    <div class="col col-md-1">
        <label for="<?php echo $celular; ?>" class="form-control-label">Celular</label>
    </div>
    <div class="col-12 col-md-3">
        <input type="text" id="<?php echo $celular; ?>" name="<?php echo $celular; ?>" placeholder="Celular" class="celular form-control" value="<?php if(isset($detalhes) && isset($detalhes->$celular)){ echo $detalhes->$celular; } ?>" required="required">
    </div>

    <div class="col col-md-1">
        <label for="<?php echo $email; ?>" class="form-control-label">Email</label>
    </div>
    <div class="col-12 col-md-3">
        <input type="email" id="<?php echo $email; ?>" name="<?php echo $email; ?>" placeholder="Email" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->$email)){ echo $detalhes->$email; } ?>">
    </div> 
</div>