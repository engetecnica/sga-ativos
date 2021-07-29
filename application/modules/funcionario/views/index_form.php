<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('funcionario'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Funcionário</h2>

                    <div class="card">
                        <?php if(isset($detalhes) && isset($detalhes->id_funcionario)){?>
                            <div class="card-header">Editar Funcionário</div>
                        <?php }?>

                         <?php if(isset($detalhes) && !isset($detalhes->id_funcionario)) {?>
                            <div class="card-header">Novo Funcionário</div>
                         <?php } ?>
                        <div class="card-body">

                            <form action="<?php echo base_url('funcionario/salvar'); ?>" method="post" enctype="multipart/form-data" id="vendedores">

                                <?php if(isset($detalhes) && isset($detalhes->id_funcionario)){?>
                                <input type="hidden" name="id_funcionario" id="id_funcionario" value="<?php echo $detalhes->id_funcionario; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_empresa" class=" form-control-label">Empresa</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <select 
                                            class="form-control" 
                                            name="id_empresa" 
                                            id="id_empresa"
                                            required="required"
                                        >
                                            <option value="">Nenhuma empresa selecionada</option>
                                            <?php foreach ($empresas as $value) { ?>
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
                                            required="required"
                                        >
                                            <option value="">Nenhuma obra selecionada</option>
                                            <?php foreach ($obras as $value) { ?>
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
                                        <label for="nome" class=" form-control-label">Nome Completo</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="text" id="nome" name="nome" placeholder="Nome Completo" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->nome)){ echo $detalhes->nome; } ?>" required="required">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="rg" class=" form-control-label">RG</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="rg" name="rg" placeholder="RG" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->cpf)){ echo $detalhes->rg; } ?>" required="required">
                                    </div>
                                          
                                    <div class="col col-md-1">
                                        <label for="cpf" class=" form-control-label">CPF</label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input type="text" id="cpf" name="cpf" placeholder="CPF" class="form-control cpf" value="<?php if(isset($detalhes) && isset($detalhes->cpf)){ echo $detalhes->cpf; } ?>" required="required">
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="data_nascimento" class=" form-control-label">Data de Nascimento</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->data_nascimento)){ echo $detalhes->data_nascimento; } ?>" required="required">
                                    </div>                                    
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="endereco" class=" form-control-label">Endereço</label>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <input type="text" id="endereco" name="endereco" placeholder="Endereço" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->endereco)){ echo $detalhes->endereco; } ?>">
                                    </div>

                                    <div class="col col-md-1">
                                        <label for="endereco_numero" class=" form-control-label">Número</label>
                                    </div>
                                    <div class="col-12 col-md-1">
                                        <input type="text" id="endereco_numero" name="endereco_numero" placeholder="Número" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->endereco_numero)){ echo $detalhes->endereco_numero; } ?>">
                                    </div>

                                    <div class="col col-md-1">
                                        <label for="endereco_complemento" class=" form-control-label">Complemento</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="endereco_complemento" name="endereco_complemento" placeholder="Complemento" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->endereco_complemento)){ echo $detalhes->endereco_complemento; } ?>">
                                    </div>                                                                                                
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="endereco_bairro" class=" form-control-label">Bairro</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="endereco_bairro" name="endereco_bairro" placeholder="Bairro" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->endereco_bairro)){ echo $detalhes->endereco_bairro; } ?>">
                                    </div>

                                    <div class="col col-md-1">
                                        <label for="endereco_cep" class=" form-control-label">CEP</label>
                                    </div>
                                    <div class="col-12 col-md-1">
                                        <input type="text" id="endereco_cep" name="endereco_cep" placeholder="CEP" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->endereco_cep)){ echo $detalhes->endereco_cep; } ?>">
                                    </div>

                                    <div class="col col-md-1">
                                        <label for="endereco_cidade" class=" form-control-label">Cidade</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="endereco_cidade" name="endereco_cidade" placeholder="Cidade" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->endereco_cidade)){ echo $detalhes->endereco_cidade; } ?>">
                                    </div>      

                                    <div class="col col-md-1">
                                        <label for="endereco_estado" class=" form-control-label">Estado</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <select id="endereco_estado" name="endereco_estado" class="form-control">
                                            <option value="0">Selecione o Estado</option>
                                        <?php foreach($estados as $estado){ ?>
                                            <option value="<?php echo $estado->id_estado; ?>" <?php if(isset($detalhes) && isset($detalhes->endereco_estado) && $detalhes->endereco_estado==$estado->id_estado){ echo "selected='selected'"; } ?>><?php echo $estado->estado; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="telefone" class=" form-control-label">Telefone</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="telefone" name="telefone" placeholder="Telefone" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->telefone)){ echo $detalhes->telefone; } ?>">
                                    </div>

                                    <div class="col col-md-1">
                                        <label for="celular" class=" form-control-label">Celular</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="celular" name="celular" placeholder="Celular" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->celular)){ echo $detalhes->celular; } ?>" required="required">
                                    </div>

                                    <div class="col col-md-1">
                                        <label for="email" class=" form-control-label">E-mail</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="email" id="email" name="email" placeholder="E-mail" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->email)){ echo $detalhes->email; } ?>" required="required">
                                    </div> 
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="observacao" class=" form-control-label">Observações</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <textarea name="observacao" id="observacao" rows="4" placeholder="Observações..." class="form-control"><?php if(isset($detalhes) && isset($detalhes->observacao)){ echo $detalhes->observacao; } ?></textarea>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="situacao" class=" form-control-label">Situação</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select name="situacao" id="situacao" class="form-control">
                                            <option value="1" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao==1){ echo "selected='selected'"; } ?>>Inativo</option>
                                            <option value="0" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao==0){ echo "selected='selected'"; } ?>>Ativo</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url('funcionario');?>">
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