<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <?php $id = isset($detalhes) ? "#".$detalhes->id_empresa : '';?>
                        <a href="<?php echo base_url("empresa$id"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Empresas</h2>

                    <div class="card">
                        <div class="card-header">Nova Empresa</div>
                        <div class="card-body">

                            <form action="<?php echo base_url('empresa/salvar'); ?>" method="post" enctype="multipart/form-data">

                                <?php if(isset($detalhes) && isset($detalhes->id_empresa)){?>
                                <input type="hidden" name="id_empresa" id="id_empresa" value="<?php echo $detalhes->id_empresa; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="razao_social" class=" form-control-label">Razão Social</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input required="required" type="text" id="razao_social" name="razao_social" placeholder="Razão Social" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->razao_social)){ echo $detalhes->razao_social; } ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="nome_fantasia" class=" form-control-label">Nome Fantasia</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="text" id="nome_fantasia" name="nome_fantasia" placeholder="Nome Fantasia" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->nome_fantasia)){ echo $detalhes->nome_fantasia; } ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="cnpj" class=" form-control-label">CPF/CNPJ</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input required="required" type="text" id="cnpj" name="cnpj" placeholder="CPF/CNPJ" class="form-control cnpj" value="<?php if(isset($detalhes) && isset($detalhes->cnpj)){ echo $detalhes->cnpj; } ?>">
                                    </div>

                                    <div class="col col-md-1">
                                        <label for="inscricao_estadual" class=" form-control-label">Insc. Est.</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="inscricao_estadual" name="inscricao_estadual" placeholder="Inscrição Estadual" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->inscricao_estadual)){ echo $detalhes->inscricao_estadual; } ?>">
                                    </div>

                                    <div class="col col-md-1">
                                        <label for="inscricao_municipal" class=" form-control-label">Insc. Mun.</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="inscricao_municipal" name="inscricao_municipal" placeholder="Inscrição Municipal" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->inscricao_municipal)){ echo $detalhes->inscricao_municipal; } ?>">
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
                                        <label for="responsavel" class=" form-control-label">Responsável</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="text" id="responsavel" name="responsavel" placeholder="Responsável" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->responsavel)){ echo $detalhes->responsavel; } ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="responsavel_telefone" class=" form-control-label">Telefone</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="responsavel_telefone" name="responsavel_telefone" placeholder="Telefone" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->responsavel_telefone)){ echo $detalhes->responsavel_telefone; } ?>">
                                    </div>

                                    <div class="col col-md-1">
                                        <label for="responsavel_celular" class=" form-control-label">Celular</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input type="text" id="responsavel_celular" name="responsavel_celular" placeholder="Celular" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->responsavel_celular)){ echo $detalhes->responsavel_celular; } ?>">
                                    </div>

                                    <div class="col col-md-1">
                                        <label for="responsavel_email" class=" form-control-label">E-mail</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="email" id="responsavel_email" name="responsavel_email" placeholder="E-mail" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->responsavel_email)){ echo $detalhes->responsavel_email; } ?>">
                                    </div>      

                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="observacao" class=" form-control-label">Observações</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <textarea name="observacao" id="observacao" rows="9" placeholder="Observações..." class="form-control"><?php if(isset($detalhes) && isset($detalhes->observacao)){ echo $detalhes->observacao; } ?></textarea>
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
                                    <a href="<?php echo base_url('empresa');?>">
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
