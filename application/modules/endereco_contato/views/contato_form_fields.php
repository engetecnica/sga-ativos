<hr>
<div class="row form-group">
    <div class="col col-md-1">
        <label for="telefone" class="form-control-label">Telefone</label>
    </div>
    <div class="col-12 col-md-3">
        <input type="text" id="telefone" name="telefone" placeholder="Telefone" class="telefone form-control" value="<?php if(isset($detalhes) && isset($detalhes->telefone)){ echo $detalhes->telefone; } ?>">
    </div>

    <div class="col col-md-1">
        <label for="celular" class="form-control-label">Celular</label>
    </div>
    <div class="col-12 col-md-3">
        <input type="text" id="celular" name="celular" placeholder="Celular" class="celular form-control" value="<?php if(isset($detalhes) && isset($detalhes->celular)){ echo $detalhes->celular; } ?>" required="required">
    </div>

    <div class="col col-md-1">
        <label for="email" class="form-control-label">Email</label>
    </div>
    <div class="col-12 col-md-3">
        <input type="email" id="email" name="email" placeholder="E-mail" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->email)){ echo $detalhes->email; } ?>">
    </div> 
</div>