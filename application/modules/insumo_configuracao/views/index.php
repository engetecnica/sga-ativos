<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url('insumo_configuracao/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Categorias Principais</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th width="7%">Id</th>
                                    <th class="text-center">Categoria</th>
                                    <th class="text-center">Titulo</th>
                                    <th class="text-center">Situação</th>
                                    <th class="text-center">Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista_principal as $valor){ ?>
                                <tr id="<?php echo "configuracao-{$valor->id_insumo_configuracao}"?>">
                                    <td class="text-center">
                                        <?php if ($valor->permit_edit || $valor->permit_delete) {?>
                                            <a  href="<?php echo base_url('insumo_configuracao'); ?>/editar/<?php echo $valor->id_insumo_configuracao; ?>">
                                                <?php echo $valor->id_insumo_configuracao; ?>
                                            </a>
                                        <?php } else { echo $valor->id_insumo_configuracao;  } ?>
                                    </td>
                                    <td class="text-center"><?php echo ($valor->id_insumo_configuracao_vinculo=='0') ? "Configuração Principal" : $valor->id_insumo_configuracao_vinculo; ?></td>
                                    <td class="text-center"><?php echo $valor->titulo; ?></td>
                                    <td class="text-center">
                                      <?php $situacao = $this->get_situacao($valor->situacao, 'DESCARTADO', 'secondary');?>
                                      <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                    </td>

                                    <td class="text-center">

                                    <?php if ($valor->permit_edit || $valor->permit_delete) {?>
                                        <div class="btn-group">
                                            <button 
                                                class="btn btn-secondary btn-sm dropdown-toggle" 
                                                type="button"
                                                data-toggle="dropdown" 
                                                aria-haspopup="true" 
                                                aria-expanded="false"
                                            >
                                                Gerenciar
                                            </button>
                                            <div class="dropdown-menu">
                                                <?php if ($valor->permit_edit == 1) {?>
                                                <a 
                                                    class="dropdown-item " 
                                                    href="<?php echo base_url('insumo_configuracao'); ?>/editar/<?php echo $valor->id_insumo_configuracao; ?>"
                                                >
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                                <?php } ?>
                                                <?php if ($valor->permit_delete == 1) {?>
                                                <div class="dropdown-divider"></div>
                                                <a 
                                                    class="dropdown-item  deletar_registro" 
                                                    href="javascript:void(0)" 
                                                    data-href="<?php echo base_url('insumo_configuracao'); ?>/deletar/<?php echo $valor->id_insumo_configuracao; ?>" 
                                                    data-registro="<?php echo $valor->id_insumo_configuracao;?>" 
                                                    data-tabela="insumo_configuracao"
                                                >
                                                    <i class="fas fa-trash"></i> Excluir
                                                </a>
                                                <?php } ?>
                                            </div>
                                            <?php } else { ?>
                                                -
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                               <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Subcategorias de Insumos</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th width="7%">Código</th>
                                    <th class="text-center">Categoria</th>
                                    <th class="text-center">Titulo</th>
                                    <th class="text-center">Unidade de Medida</th>
                                    <th class="text-center">Situação</th>
                                    <th class="text-center">Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr id="<?php echo "configuracao-{$valor->id_insumo_configuracao}"?>">
                                    <td class="text-center">
                                        <?php if ($valor->permit_edit || $valor->permit_delete) {?>
                                            <a  href="<?php echo base_url('insumo_configuracao'); ?>/editar/<?php echo $valor->id_insumo_configuracao; ?>">
                                                <?php echo $valor->codigo_insumo; ?>
                                            </a>
                                        <?php } else { echo $valor->codigo_insumo;  } ?>
                                    </td>
                                    <td class="text-center"><?php echo ($valor->id_insumo_configuracao_vinculo=='') ? "Configuração Principal" : $valor->id_insumo_configuracao_vinculo; ?></td>
                                    <td class="text-center"><?php echo $valor->titulo; ?></td>
                                    <td class="text-center"><span class="badge badge-primary"><?php echo $valor->medicao_titulo." (".$valor->medicao_sigla.")";  ?></span></td>
                                    <td class="text-center">
                                      <?php $situacao = $this->get_situacao($valor->situacao, 'DESCARTADO', 'secondary');?>
                                      <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                    </td>

                                    <td class="text-center">

                                    <?php if ($valor->permit_edit || $valor->permit_delete) {?>
                                        <div class="btn-group">
                                            <button 
                                                class="btn btn-secondary btn-sm dropdown-toggle" 
                                                type="button"
                                                data-toggle="dropdown" 
                                                aria-haspopup="true" 
                                                aria-expanded="false"
                                            >
                                                Gerenciar
                                            </button>
                                            <div class="dropdown-menu">
                                                <?php if ($valor->permit_edit == 1) {?>
                                                <a 
                                                    class="dropdown-item " 
                                                    href="<?php echo base_url('insumo_configuracao'); ?>/editar/<?php echo $valor->id_insumo_configuracao; ?>"
                                                >
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                                <?php } ?>
                                                <?php if ($valor->permit_delete == 1) {?>
                                                <div class="dropdown-divider"></div>
                                                <a 
                                                    class="dropdown-item  deletar_registro" 
                                                    href="javascript:void(0)" 
                                                    data-href="<?php echo base_url('insumo_configuracao'); ?>/deletar/<?php echo $valor->id_insumo_configuracao; ?>" 
                                                    data-registro="<?php echo $valor->id_insumo_configuracao;?>" 
                                                    data-tabela="insumo_configuracao"
                                                >
                                                    <i class="fas fa-trash"></i> Excluir
                                                </a>
                                                <?php } ?>
                                            </div>
                                            <?php } else { ?>
                                                -
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                               <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
