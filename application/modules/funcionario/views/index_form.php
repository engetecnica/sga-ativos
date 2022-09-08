<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <?php $id = isset($detalhes) ? "#".$detalhes->id_funcionario : '';?>
                        <a href="<?php echo base_url("funcionario$id"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Funcionário</h2>

                    <div class="card">
                        <div class="card-header">
                            <?php echo isset($detalhes) && isset($detalhes->id_funcionario) ? 'Editar Funcionário' : 'Novo Funcionário' ?>
                        </div>
                        <div class="card-body">

                            <form action="<?php echo base_url('funcionario/salvar'); ?>" method="post" enctype="multipart/form-data" id="vendedores">

                                <?php if(isset($detalhes) && isset($detalhes->id_funcionario)){?>
                                <input type="hidden" name="id_funcionario" id="id_funcionario" value="<?php echo $detalhes->id_funcionario; ?>">
                                <?php } ?>

                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_empresa" class=" form-control-label">Empresa</label>
                                    </div>
                                    <div class="col-12 col-md-4">
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
                              
                                    <div class="col col-md-2">
                                        <label for="id_obra" class=" form-control-label">Obra</label>
                                    </div>
                                    <div class="col-12 col-md-4">
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

                                <div class="row form-group m-t-40 m-b-40">
                                    <div class="col col-md-2">
                                        <label for="matricula" class=" form-control-label">Matrícula</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" id="matricula" name="matricula" placeholder="" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->matricula)){ echo $detalhes->matricula; } ?>" required="required">
                                    </div>
                                </div>

                                <div class="row form-group m-t-40 m-b-40">
                                    <div class="col col-md-2">
                                        <label for="nome" class=" form-control-label">Nome Completo</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" id="nome" name="nome" placeholder="" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->nome)){ echo $detalhes->nome; } ?>" required="required">
                                    </div>

                                    <div class="col col-md-2">
                                        <label for="data_nascimento" class=" form-control-label">Nascimento</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" value="<?php if(isset($detalhes) && isset($detalhes->data_nascimento)){ echo $detalhes->data_nascimento; } ?>" required="required">
                                    </div>
                                </div>    

                                <div class="row form-group">
                                
                                    <div class="col col-md-2">
                                        <label for="rg" class=" form-control-label">RG</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" id="rg" name="rg" placeholder="00 000 000-00" class="form-control rg" value="<?php if(isset($detalhes) && isset($detalhes->cpf)){ echo $detalhes->rg; } ?>" required="required">
                                    </div>
                               

                                    <div class="col col-md-2">
                                        <label for="cpf" class="form-control-label">CPF</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" class="form-control cpf" value="<?php if(isset($detalhes) && isset($detalhes->cpf)){ echo $detalhes->cpf; } ?>" required="required">
                                    </div>
                                </div>

                                <?php $this->view("endereco_contato/endereco_form_fields"); ?>
                                <?php $this->view("endereco_contato/contato_form_fields"); ?>


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
                                    <div class="col-12 col-md-2">
                                        <select name="situacao" id="situacao" class="form-control">
                                            <option value="0" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao=='0'){ echo "selected='selected'"; } ?>>Ativo</option>
                                            <option value="1" <?php if(isset($detalhes) && isset($detalhes->situacao) && $detalhes->situacao=='1'){ echo "selected='selected'"; } ?>>Inativo</option>
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
                                    <button class="btn btn-secondary" type="button">                                   
                                        <i class="fa fa-ban "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>                              
                                    </a>
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