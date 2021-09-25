<?php $this->view("top", ['title' => "Redefinir Senha"]); ?>

<div class="login-content">
    <div class="login-logo">
        <h3 class="m-b-20 col text-center">Redefinir Senha</h3>
        <p class="col col-md-10 offset-md-1">Digite a nova senha e confirme para redefinir</p>
    </div>                           
    <div class="login-form">
        <form action="<?php echo base_url('login/redefinir_senha');?>" method="post" enctype="multipart/form-data">
            <div class="row form-group col col-md-10 offset-md-1">
                <input  type="hidden" name="codigo" id="codigo" value="<?php echo $codigo; ?>">
                <input  type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $usuario->id_usuario; ?>">
                <label for="senha">Senha</label>
                <input class="au-input au-input--full" type="password" name="senha" id="senha" required="required" autofocus >
            </div>

            <div class="row form-group col col-md-10 offset-md-1">
                <label for="confirmar_senha">Confirmar Senha</label>
                <input class="au-input au-input--full" type="password" name="confirmar_senha" id="confirmar_senha" required="required">
            </div>

            <div class="row form-group col col-md-10 offset-md-1 m-t-40 text-center">
                <button class="au-btn au-btn--block au-btn--green m-b-20 m-t-20" type="submit">Salvar Senha</button>
                <a class="au-link--blue" href="<?php echo base_url("login")?>" >Voltar ao Início</a>
            </div>
        </form>
    </div>
</div>

<?php $this->view("footer"); ?>
