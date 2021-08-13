<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <?php $id = isset($detalhes) && isset($detalhes->id_usuario) ? "#".$detalhes->id_usuario : '';?>
                        <a href="<?php echo base_url("usuario$id"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Usuários</h2>
                    <div class="card">
                        <?php if(isset($detalhes) && isset($detalhes->id_usuario)){?>
                            <div class="card-header">Editar Usuário</div>
                        <?php }?>

                         <?php if(isset($detalhes) && !isset($detalhes->id_usuario)) {?>
                            <div class="card-header">Novo Usuário</div>
                         <?php } ?>
                        <div class="card-body">

                            <form action="<?php echo base_url('usuario/salvar'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">

                                <?php if(isset($detalhes) && isset($detalhes->id_usuario)){?>
                                    <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $detalhes->id_usuario; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="nivel" class=" form-control-label">Nível de Permissão</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                
                                        <select 
                                            class="form-control" 
                                            name="nivel" 
                                            id="nivel"
                                            required 
                                        >
                                        
                                            <option value="">Nenhuma permissão selecionada</option>
                                            <?php foreach ($detalhes->niveis as $value) { ?>
                                                <option 
                                                    <?php echo isset($detalhes->nivel) && ($detalhes->nivel == $value->id_usuario_nivel) ? 'selected' : ''?>
                                                    value="<?php echo $value->id_usuario_nivel; ?>"
                                                >
                                                <?php echo "{$value->id_usuario_nivel} - {$value->nivel}"; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_empresa" class=" form-control-label">Empresa</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <select 
                                            class="form-control" 
                                            name="id_empresa" 
                                            id="id_empresa"
                                            required
                                        >
                                            <option value="">Nenhuma empresa selecionada</option>
                                            <?php foreach ($detalhes->empresas as $value) { ?>
                                                <option 
                                                   <?php echo isset($detalhes->id_empresa) && $value->id_empresa == $detalhes->id_empresa ? 'selected' : ''?> 
                                                    value="<?php echo $value->id_empresa; ?>"
                                                >
                                                    <?php echo "{$value->id_empresa} - {$value->nome_fantasia}"; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_obra" class=" form-control-label">Obra</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <select 
                                            class="form-control" 
                                            name="id_obra" 
                                            id="id_obra"
                                            required
                                        >
                                            <option value="" >Nenhuma obra selecionada</option>
                                            <?php foreach ($detalhes->obras as $value) { ?>
                                                <option 
                                                    <?php echo isset($detalhes->id_obra) && $value->id_obra == $detalhes->id_obra ? 'selected' : ''?>
                                                    value="<?php echo $value->id_obra; ?>"
                                                >
                                                    <?php echo "{$value->id_obra} - {$value->codigo_obra}"; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>    

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="usuario" class=" form-control-label">Nome de Usuário</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <input required type="text" id="usuario" name="usuario" placeholder="seu_username" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->usuario)){ echo $detalhes->usuario; } ?>">
                                    </div>
                                </div>
                            
                                <!--
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="email" class=" form-control-label">Email</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <input type="email" id="email" name="email" placeholder="seu_email@exemplo.com" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->email)){ echo $detalhes->email; } ?>">
                                    </div>
                                </div>
                                -->

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label  for="senha" class=" form-control-label">Senha</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <input type="password" id="senha" name="senha" placeholder="********" class="form-control" value="">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="confirmar_senha" class=" form-control-label">Confirmar Senha</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="********" class="form-control" value="">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="situacao" class=" form-control-label">Situação</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select name="situacao" id="situacao" class="form-control">
                                            <option value="1" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao=='1'){ echo "selected='selected'"; } ?>>Inativo</option>
                                            <option value="0" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao=='0'){ echo "selected='selected'"; } ?>>Ativo</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url('usuario');?>">
                                    <button class="btn btn-info" type="button">                                                    
                                        <i class="fa fa-remove "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>                                                
                                    </a>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="copyright">
                        <p>Copyright © <?php echo date("Y"); ?>. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
