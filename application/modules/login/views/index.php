<?php $this->view("top", ['title' => "Entrar"]); ?>

<div class="login-content">
    <div class="login-logo">
        <a href="#">
            <img src="<?php echo base_url('assets'); ?>/images/icon/logo.png?version=<?=date("dmYHis");?>" alt="Sistema de Gestão de Ativos">
        </a>
    </div>                           
    <div class="login-form">
        <form id="login-form" action="<?php echo base_url('login/acessar');?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="redirect_to" id="redirect_to" value="<?php echo $redirect_to ? $redirect_to : null; ?>"/>
            <div class="row form-group col col-md-10 offset-md-1">
                <label for="usuario">Usuário ou Email</label>
                <input v-model="selecionado.usuario" class="au-input au-input--full" type="text" name="usuario" id="usuario" placeholder="Usuário" required="" onfocus="">
            </div>
            <div class="row form-group col col-md-10 offset-md-1">
                <label for="senha">Senha</label>
                <input v-model="selecionado.senha" class="au-input au-input--full" type="password" name="senha" id="senha" placeholder="Senha" required="">
            </div>
            <div class="row form-group col col-md-10 offset-md-1 m-t-40 text-center">
                <button class="au-btn au-btn--block au-btn--green m-b-20 m-t-20" type="submit">Acessar Sistema</button>
                <a class="au-link--blue" href="<?php echo base_url("login/recuperar_senha")?>" >Esqueci Minha Senha</a>
            </div>
        </form>
    </div>
</div>

<?php $this->view("footer"); ?>




