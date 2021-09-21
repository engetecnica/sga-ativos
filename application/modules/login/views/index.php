<?php $this->view("top"); ?>

<div class="login-content">
    <div class="login-logo">
        <a href="#">
            <img src="<?php echo base_url('assets'); ?>/images/icon/logo.png" alt="DEF Express">
        </a>
    </div>                           
    <div class="login-form">
        <form action="<?php echo base_url('login/acessar');?>" method="post" enctype="multipart/form-data">
            <div class="row form-group col-md-10 offset-1">
                <label for="username">Usuário ou Email</label>
                <input class="au-input au-input--full" type="text" name="usuario" id="usuario" placeholder="Usuário" required="" onfocus="">
            </div>
            <div class="row form-group col-md-10 offset-1">
                <label for="senha">Senha</label>
                <input class="au-input au-input--full" type="password" name="senha" id="senha" placeholder="Senha" required="">
            </div>
            <div class="col col-md-10 offset-1 m-t-40 text-center">
                <button class="au-btn au-btn--block au-btn--green" type="submit">Acessar Sistema</button>
                <a class="au-link--blue m-t-20" href="<?php echo base_url("login/recuperar_senha")?>" >Esqueci Minha Senha</a>
            </div>
        </form>
    </div>
</div>

<?php $this->view("footer"); ?>

