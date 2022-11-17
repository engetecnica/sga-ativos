<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <?php 
                            if (!$is_self) {
                            $id = isset($detalhes) && isset($detalhes->id_usuario) ? "#".$detalhes->id_usuario : '';
                        ?>
                        <a href="<?php echo base_url("usuario$id"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Usuário</h2>
                    <div class="card">
                    <div class="card-header">
                            <?php echo isset($detalhes) && isset($detalhes->id_usuario) ? 'Editar Usuário' : 'Novo Usuário' ?>
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url('usuario/salvar'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">

                                <?php 
                                    $permissoes = null;
                
                                    if(isset($detalhes) && isset($detalhes->id_usuario)){

                                        $permissoes = json_decode($detalhes->permissoes) ?? [];

                                      
                                ?>
                                    <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $detalhes->id_usuario; ?>">
                                <?php } ?>
                                
                                
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="nivel" class=" form-control-label">Tipo</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                
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
                       
                                    <div class="col col-md-1">
                                        <label for="id_obra" class=" form-control-label">Obra</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select 
                                            class="form-control select2" 
                                            name="id_obra" 
                                            id="id_obra"
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

                                <div class="row form-group m-b-40">
                                    <div class="col col-md-2">
                                        <label for="id_permissao" class=" form-control-label">Permissões</label>
                                    </div>

                                   

                                    <div class="col-12 col-md-10">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th width="5%">Rota</th>
                                                <th>Módulo</th>
                                                <th width="30%">Permissão</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($modulos->modulo as $mod){ ?>

                                                <tr style="background-color: #EDEDED">
                                                    <td>
                                                        <?php echo $mod->rota; ?>
                                                    </td>
                                                    <td><?php echo $mod->titulo; ?></td>
                                                    <td>
                                                        <p><input type="checkbox" class="modulo_<?php echo $mod->id_modulo; ?>" onclick="marcarDesmarcarTodos('modulo_<?=$mod->id_modulo;?>')"> Todos</p>
                                                    </td>
                                                </tr>
                                                <?php
                                                    if($mod->rota=='relatorio'){
                                                ?>
                                                    <?php 
                                                        foreach($relatorios as $key=>$r){ 
                                                    ?>
                                                    <tr>
                                                        <td>

                                                        </td>
                                                        <td><?php echo $r['titulo']; ?></td>
                                                        <td>

                                                            <?php
                                                            $visualizar = "off";
                                                            if(isset($permissoes)){ 
                                                                foreach($permissoes as $modulo => $permissao){
                                                                    if($modulo=="relatorio_".$key){
                                                                        if(isset($permissao->visualizar) && $permissao->visualizar=='on'){
                                                                            $visualizar = "on";
                                                                        }                                                                    
                                                                    }
                                                                }     
                                                            }                                                       
                                                            ?>
                                                        
                                                            <p><input type="checkbox" class="modulo_<?php echo $mod->id_modulo; ?>" name="permissoes[relatorio_<?php echo $key; ?>][visualizar]" <?php if($visualizar =="on"){ echo "checked"; } ?>> Visualizar</p>
                                                            
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php foreach($mod->submodulo as $sub){ ?>
                                                <tr>
                                                    <td><?php echo $sub->rota; ?></td>
                                                    <td><?php echo $sub->titulo; ?></td>
                                                    <td>


                                                        <?php
                                                            
                                                            $visualizar = "off";
                                                            $adicionar = "off";
                                                            $editar  = "off";
                                                            $excluir  = "off";
                                                            

                                                            if(isset($permissoes)){
                                                                foreach($permissoes as $modulo => $permissao){

                                                                    

                                                                    if($modulo==$sub->id_modulo){

                                                                        if(isset($permissao->visualizar) && $permissao->visualizar=='on'){
                                                                            $visualizar = "on";
                                                                        }

                                                                        if(isset($permissao->adicionar) && $permissao->adicionar=='on'){
                                                                            $adicionar = "on";
                                                                        }
                                                                        
                                                                        if(isset($permissao->editar) && $permissao->editar=='on'){
                                                                            $editar = "on";
                                                                        }
                                                                        
                                                                        if(isset($permissao->excluir) && $permissao->excluir=='on'){
                                                                            $excluir= "on";
                                                                        }     

                                                                    }
                                                                }
                                                            }

                                                            
                                                            ?>
                                                
                                                
                                                        <p><input type="checkbox" class="modulo_<?php echo $mod->id_modulo; ?>" name="permissoes[<?php echo $sub->id_modulo; ?>][visualizar]" <?php if($visualizar =="on"){ echo "checked"; } ?>> Visualizar</p>
                                                        <p><input type="checkbox" class="modulo_<?php echo $mod->id_modulo; ?>" name="permissoes[<?php echo $sub->id_modulo; ?>][adicionar]" <?php if($adicionar =="on"){ echo "checked"; } ?>> Adicionar</p>
                                                        <p><input type="checkbox" class="modulo_<?php echo $mod->id_modulo; ?>" name="permissoes[<?php echo $sub->id_modulo; ?>][editar]" <?php if($editar =="on"){ echo "checked"; } ?>> Editar</p>
                                                        <p><input type="checkbox" class="modulo_<?php echo $mod->id_modulo; ?>" name="permissoes[<?php echo $sub->id_modulo; ?>][excluir]" <?php if($excluir =="on"){ echo "checked"; } ?>> Excluir</p>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>  
                                

                                <div class="row form-group m-b-40">
                                    <div class="col col-md-2">
                                        <label for="id_empresa" class=" form-control-label">Empresa</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select 
                                            class="form-control select2" 
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
                                
                                <?php if ($is_self) { ?>
                                    <input type="hidden" name="nivel" id="nivel" value="<?php echo $detalhes->nivel; ?>">
                                    <input type="hidden" name="id_empresa" id="id_empresa" value="<?php echo $detalhes->id_empresa; ?>">
                                    <input type="hidden" name="id_obra" id="id_obra" value="<?php echo $detalhes->id_obra; ?>">
                                <?php } ?>
                    

                                <?php if ($form_type == "adicionar" || ($form_type == 'editar' && $is_self)) {?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="nome" class=" form-control-label">Nome</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" id="nome" name="nome" id="nome" placeholder="Seu Nome" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->nome)){ echo $detalhes->nome; } ?>">
                                    </div>

                                    <?php if ($is_self && $user->nivel == 1) {?>
                                        <div class="col-12 col-md-3">
                                            <label for="permit_notification_email" class=" form-control-label">Receber Emails de Notificações</label>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <select name="permit_notification_email" id="permit_notification_email" class="form-control">
                                                <option value="0" <?php if(isset($detalhes) && isset($detalhes->permit_notification_email) && $detalhes->permit_notification_email=='0'){ echo "selected='selected'"; } ?>>Não</option>
                                                <option value="1" <?php if(isset($detalhes) && isset($detalhes->permit_notification_email) && $detalhes->permit_notification_email=='1'){ echo "selected='selected'"; } ?>>Sim</option>
                                            </select>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="usuario" class=" form-control-label">Usuário</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input required type="text" id="usuario" name="usuario" placeholder="username" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->usuario)){ echo $detalhes->usuario; } ?>">
                                    </div>
                                
                                    <div class="col col-md-2">
                                        <label for="email" class=" form-control-label">Email</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="email" id="email" name="email" placeholder="seuemail@exemplo.com" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->email)){ echo $detalhes->email; } ?>">
                                    </div>
                                </div>
                                <?php } else {?>
                                    <div class="row form-group">
                                        <input type="hidden" name="usuario" id="usuario" value="<?php echo $detalhes->usuario; ?>">
                                        
                                        <div class="col col-md-2">
                                        <label for="nome" class=" form-control-label">Nome</label>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <input type="text" id="nome" name="nome" placeholder="Seu Nome" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->nome)){ echo $detalhes->nome; } ?>">
                                        </div>

                                        <?php if (!isset($detalhes->email)) {?>
                                            <div class="col col-md-2">
                                                <label for="email" class=" form-control-label">Email</label>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <input type="email" id="email" name="email" placeholder="seuemail@exemplo.com" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->email)){ echo $detalhes->email; } ?>">
                                            </div>
                                        <?php } else {?>
                                            <input type="hidden" name="email" id="email" value="<?php echo isset($detalhes->email) ? $detalhes->email : ''; ?>">
                                        <?php } ?>
                                    </div>
                                <?php }?>
                                
                                <?php if ($form_type == "adicionar" || ($form_type == 'editar')) {?>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label  for="senha" class=" form-control-label">Senha</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="password" id="senha" name="senha" placeholder="********" class="form-control" value="">
                                    </div>
                                
                                    <div class="col col-md-2">
                                        <label for="confirmar_senha" class=" form-control-label">Confirmar Senha</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="********" class="form-control" value="">
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if ($is_self) {?>
                                <div class="row form-group">
                                    <div class="col-12 col-md-2">
                                        <label for="avatar" class=" form-control-label">Imagem do Usuário</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*" style="margin-bottom: 5px;"> 
                                        <small size='2'>Formatos aceito: <strong>*.JPG, *.PNG, *.JPEG, *.GIF</strong> 
                                        Tamanho Máximo: <strong><?php echo $upload_max_filesize;?></strong></small>
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if (!$is_self) {?>
                                <div class="row form-group">
                                    <div class="col-12 col-md-2">
                                        <label for="situacao" class=" form-control-label">Situação</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <select name="situacao" id="situacao" class="form-control">
                                            <option value="0" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao=='0'){ echo "selected='selected'"; } ?>>Ativo</option>
                                            <option value="1" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao=='1'){ echo "selected='selected'"; } ?>>Inativo</option>
                                        </select>
                                    </div>
                                </div>
                                <?php } else { ?>
                                    <input type="hidden" name="situacao" id="situacao" value="<?php echo $detalhes->situacao; ?>">
                                <?php } ?>
                                
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>

                                    <?php  if ($is_self) { ?>
                                    <a href="<?php echo base_url($this->getRef());?>">
                                    <button class="btn btn-secondary" type="button">                                   
                                        <i class="fa fa-ban "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>                              
                                    </a>
                                    <?php  } else { ?>
                                    <a href="<?php echo base_url('usuario');?>">
                                    <button class="btn btn-secondary" type="button">                                   
                                        <i class="fa fa-ban "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>                              
                                    </a>
                                    <?php } ?>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
