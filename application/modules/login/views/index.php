<?php $this->view("top", ['title' => "Entrar"]); ?>
<!-- 
<div class="login-content">
    <div class="login-logo">
        <a href="#">
            <img src="" alt="Sistema de Gestão de Ativos">
        </a>
    </div>                           
    <div class="login-form">
        
            
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
                <a class="au-link--blue" href="" >Esqueci Minha Senha</a>
            </div>
        </form>
    </div>
</div> -->


<div class="row vh-100">
                <div class="col-md-6 text-center py-5 d-flex flex-column justify-content-center auth-bg-section text-white" style="background-image: url(<?php echo base_url('assets'); ?>/vendor/login/images/login-bg.jpg)">
                    <h1 class="text-reset">Seja bem vindo ao <br> ENGEATIVOS</h1>
                    <p class="font-weight-bold text-reset">Sistema de Gestão de Ativos Engetécnica.</p>
                </div>
                <div class="col-md-6 text-center d-flex flex-column py-5 justify-content-center">
                    <div class="auth-form-section">
                        <div class="logo"><img src="<?php echo base_url('assets'); ?>/images/icon/logo.png?version=<?=date("dmYHis");?>" class="img-fluid" alt="SGA" width="200"></div>
                        <h2>Acesso</h2>
                        <p>Preencha para acessar o sistema</p>
                        <form id="auth-form" action="<?php echo base_url('login/acessar');?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="redirect_to" id="redirect_to" value="<?php echo $redirect_to ? $redirect_to : null; ?>"/>
                            <div class="form-group">
                                <label for="usuario" class="sr-only">Nome de Usuário / E-mail</label>
                                <input v-model="selecionado.usuario" class="form-control" type="text" name="usuario" id="usuario" placeholder="Usuário" required="" onfocus="">
                            </div>
                            <div class="form-group">
                            <label for="senha" class="sr-only">Senha</label>
                            <input v-model="selecionado.senha" class="form-control" type="password" name="senha" id="senha" placeholder="Senha" required="">
                            </div>
                            <button class="btn btn-success btn-block mb-3" type="submit">Acessar Sistema</button>
                            <div class="d-md-flex justify-content-between">
                
                                <a href="<?php echo base_url("login/recuperar_senha")?>" class="text-info">Esqueceu a senha?</a>
                            </div>

                        </form>
                        
                    </div>
                </div>
            </div>

<?php $this->view("footer"); ?>




