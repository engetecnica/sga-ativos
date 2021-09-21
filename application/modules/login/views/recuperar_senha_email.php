<?php $this->view("top", ['title' => "Recuperar Senha"]); ?>

<div class="login-content">
    <div class="login-logo">
        <h3 class="m-b-20 col text-center">Recupera Senha</h3>
        <p class="col col-md-10 offset-1">Digite seu endereço de email vinculado a uma conta, e confime o código de recuperação de sehna de acesso que será enviado.</p>
    </div>                           
    <div class="login-form">
        <form action="<?php echo base_url('login/enviar_codigo');?>" method="post" enctype="multipart/form-data">
            <div class="row form-group form-group col-md-10 offset-1">
                <label for="email">Email</label>
                <input class="au-input au-input--full" type="text" name="email" id="email" placeholder="seuemail@exemplo.com.br" required="" onfocus="">
            </div>
            <div class="col col-md-10 offset-1 m-t-40 text-center">
                <button class="au-btn au-btn--block au-btn--blue m-b-20" type="submit">Enviar Código de Recuperação</button>
                <a class="au-link--blue" href="<?php echo base_url("login/recuperar_senha")?>" >Já possue um código?</a>
            </div>
        </form>
    </div>
</div>

<?php $this->view("footer"); ?>
