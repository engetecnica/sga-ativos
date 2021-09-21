<?php $this->view("top", ['title' => "Redefinir Senha"]); ?>

<div class="login-content">
    <div class="login-logo">
        <h3 class="m-b-20 col text-center">Redefinir Senha</h3>
        <p class="col col-md-10 offset-1">Digite a nova senha e confirme para redefinir</p>
    </div>                           
    <div class="login-form">
        <form action="<?php echo base_url('login/redefinir_senha');?>" method="post" enctype="multipart/form-data">
            <input  type="hidden" name="codigo" id="codigo" value="<?php echo $codigo; ?>">
            <input  type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $usuario->id_usuario; ?>">

            <div class="row form-group form-group col-md-10 offset-1">
                <label for="senha">Senha</label>
                <input class="au-input au-input--full" type="password" name="senha" id="senha" required="required" autofocus >
            </div>

            <div class="row form-group form-group col-md-10 offset-1">
                <label for="confirmar_senha">Confirmar Senha</label>
                <input class="au-input au-input--full" type="password" name="confirmar_senha" id="confirmar_senha" required="required">
            </div>

            <div class="col col-md-10 offset-1 m-t-40 text-center">
                <button class="au-btn au-btn--block au-btn--blue m-b-20" type="submit">Salvar Nova Senha</button>
            </div>
        </form>
    </div>
</div>

<?php $this->view("footer"); ?>
