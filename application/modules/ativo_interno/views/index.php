<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('ativo_interno/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Ativo Interno</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th width="7%">Id</th>
                                    <th width="20%">Título</th>
                                    <th width="15%">Valor Atribuído</th>
                                    <th>Quantidade</th>
                                    <th>Inclusão</th>
                                    <th>Descarte</th>
                                    <th>Situação</th>
                                    <th>Obra</th>
                                    <th class="text-right">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <tr id="<?php echo $valor->id_ativo_interno; ?>">
                                    <td><?php echo $valor->id_ativo_interno; ?></td>
                                    <td><?php echo $valor->nome; ?></td>
                                    <td>R$ <?php echo number_format($valor->valor, 2, ',', '.'); ?></td>
                                    <td><?php echo $valor->quantidade; ?></td>
                                    <td><?php echo date("d/m/Y H:i:s", strtotime($valor->data_inclusao)); ?></td>
                                    <td><?php echo $valor->data_descarte ?  date("d/m/Y H:i:s", strtotime($valor->data_descarte)) : '-'; ?></td>
                                    <td>
                                      <?php $situacao = $this->get_situacao($valor->situacao, 'DESCARTADO', 'secondary');?>
                                      <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                    </td>
                                    <td><?php echo $valor->obra; ?></td>
                                    <td class="text-right">
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar Equipamento
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <a class="dropdown-item" href="<?php echo base_url("ativo_interno/manutencao/{$valor->id_ativo_interno}"); ?>">Histórico de Manutenção</a>
                                                <a class="dropdown-item" href="<?php echo base_url('ativo_interno'); ?>/editar/<?php echo $valor->id_ativo_interno; ?>"> Editar</a>
                                                <?php if((int) $valor->situacao < 2){?>
                                                <a 
                                        
                                                    class="dropdown-item confirmar_registro"
                                                    href="javascript:void(0)"
                                                    class="confirmar_registro"
                                                    data-registro="<?php echo $valor->id_ativo_interno;?>"
                                                    data-href="<?php echo base_url("ativo_interno/descartar/{$valor->id_ativo_interno}");?>"
                                                    data-tabela="<?php echo base_url("ativo_interno");?>"
                                                    data-redirect="true" data-icon="info" data-message="false"
                                                    data-acao="Descartar"
                                                    data-title="Confirmar descarte do ativo" data-redirect="true"
                                                    data-text="Clique 'Sim, Confirmar!' para confirmar o descarte do ativo."

                                                >                                                
                                                   Descartar Ativo                                              
                                                </a>
                                                <?php } ?>
                                               
                                                <?php if((int) $valor->situacao == 2 && !isset($valor->data_descarte)){?>
                                                <a class="dropdown-item" href="javascript:void(0)" data-href="<?php echo base_url('ativo_interno'); ?>/deletar/<?php echo $valor->id_ativo_interno; ?>" data-registro="<?php echo $valor->id_ativo_interno;?>" data-tabela="ativo_interno" class="deletar_registro">Remover</a>
                                                <?php } ?>
                                            </div>
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
