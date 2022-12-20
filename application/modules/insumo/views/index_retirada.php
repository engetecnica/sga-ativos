<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($this->permitido($permissoes, 23, 'adicionar')) { ?>
                    <div class="overview-wrap">
                        <a href="<?php echo base_url('insumo/retirada/adicionar'); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue" style="margin-left: 10px;">
                                <i class="zmdi zmdi-plus"></i>Nova Retirada
                            </button>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-20">Retirada de Insumos</h2>
                    <div class="table table--no-card table-responsive table--no- m-b-40">

                        <table class="table table-borderless table-striped table-earning" id="insumo_index">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuário</th>
                                    <th>Usuário Retirou</th>
                                    <th>Data Retirada</th>
                                    <th>Resumo</th>
                                    <th>Gerenciar</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach($retirada as $r){ ?>
                                <tr>
                                    <td><a
                                            href="<?php echo base_url('insumo/retirada/detalhes/'.$r->id_insumo_retirada); ?>"><?php echo $r->id_insumo_retirada; ?></a>
                                    </td>
                                    <td><?php echo $r->id_usuario; ?></td>
                                    <td><?php echo $r->id_funcionario; ?></td>
                                    <td><?php echo $this->formata_data_hora($r->created_at); ?></td>
                                    <td>
                                        <table class="table table-striped">
                                            <?php foreach($r->insumos as $ins){ ?>
                                            <tr>
                                                <td><?php echo $ins->id_insumo; ?></td>
                                                <td><?php echo $ins->quantidade; ?></td>
                                                <td>
                                                    <span
                                                        class="badge badge-<?php echo ($this->get_situacao_insumo($ins->status)['class']) ?? '-'; ?>"><?php echo ($this->get_situacao_insumo($ins->status)['texto']) ?? '-'; ?></span>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </table>
                                    </td>
                                    <td>

                                    <?php

                                                            // echo "<pre>";
                                                            // print_r($ins);
                                                            // echo "</pre>";

                                                            /*
                                                                Classificação:
                                                                    Pendente {
                                                                        Cancelar Retirada
                                                                        Marcar como Entregue
                                                                        Gerar Termo
                                                                        Detalhes da Retirada
                                                                    }
                                                                    Entregue {
                                                                        Devolver Itens
                                                                        Baixar termo de retirada
                                                                        Detalhes da Retirada
                                                                    }
                                                                    Devolvido {
                                                                        Detalhes da Retirada
                                                                    }
                                                                    Devolvido Parcialmente {
                                                                        Detalhes da Retirada
                                                                    }
                                                                    Cancelado {
                                                                        Detalhes da Retirada
                                                                    }

                                                                Itens de Menu
                                                                    Cancelar Retirada
                                                                    Marcar como Entregue
                                                                    Devolver Itens
                                                                    Detalhes da Retirada
                                                                    Baixar termo de retirada
                                                                    Gerar termo
                                                            */      
                                        ?>

                                        <div class="btn-group">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Gerenciar
                                            </button>
                                            <div class="dropdown-menu">

                                                <?php if($r->status==0){ ?>
                                                <a class="dropdown-item confirmar_registro" href="javascript:void(0)"
                                                    data-acao="Cancelar" data-icon="info" data-message="false"
                                                    data-title="Cancelar Retirada" data-redirect="true"
                                                    data-text="Para cancelar a entrega ao funcionário, clique em 'Sim, Cancelar.'"
                                                    data-href="<?php echo base_url("insumo/retirada/cancelar/".$r->id_insumo_retirada);?>">
                                                    <i class="fa fa-trash"></i> Cancelar Retirada
                                                </a>

                                                <div class="dropdown-divider"></div>
                                                <?php } ?>

                                                <?php if($r->status==0){ ?>
                                                <a class="dropdown-item confirmar_registro" href="javascript:void(0)"
                                                    data-acao="Entregar" data-icon="info" data-message="false"
                                                    data-title="Marcar como Entregue" data-redirect="true"
                                                    data-text="Para confirmar a entrega ao funcionário, clique em 'Sim, Entregar.'"
                                                    data-href="<?php echo base_url("insumo/retirada/entregar/".$r->id_insumo_retirada);?>"><i
                                                        class="fa fa-list"></i>&nbsp; Marcar como Entregue
                                                </a>
                                                <?php } ?>
                                              
                                                
                                                <?php if($r->status==1){ ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item"
                                                    href="<?php echo base_url('insumo/retirada/devolver/'.$r->id_insumo_retirada); ?>"
                                                    data-title="Devolver Item da Retirada" data-redirect="true">
                                                    <i class="fa fa-history item-menu-interno"></i>&nbsp; Devolver Itens
                                                </a>
                                                <?php } ?>
                                               
                                                
                                                <!-- Todos os Status -->
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item"
                                                    href="<?php echo base_url('insumo/retirada/detalhes/'.$r->id_insumo_retirada); ?>"
                                                    data-title="Detalhes da Retirada" data-redirect="true">
                                                    <i class="fa fa-truck item-menu-interno"></i>&nbsp; Detalhes da
                                                    Retirada
                                                </a>
                                                

                                                
                                                <?php if($r->status==1 && $r->anexo){ ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item"
                                                    href="<?php echo base_url('assets/uploads/'.$r->anexo); ?>"
                                                    data-title="Baixar Termo" data-redirect="true" target="_blank">
                                                    <i class="fa fa-download item-menu-interno"></i>&nbsp; Baixar Termo de Retirada
                                                </a>
                                               
                                                <?php } ?>


                                                <?php if(!$r->anexo){ ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item confirmar_registro"
                                                    href="javascript:void(0)"
                                                    data-href="<?php echo base_url('insumo/retirada/termo/'.$r->id_insumo_retirada); ?>"
                                                    data-acao="Gerar Termo" data-icon="info" data-message="false"
                                                    data-title="Gerar Agora" data-redirect="true"
                                                    data-text="Tem certeza que deseja gerar o termo de responsabilidade de retirada?"
                                                    data-title="Gerar Termo" data-redirect="true">
                                                    <i class="fa fa-print item-menu-interno"></i>&nbsp; Gerar Termo de Retirada
                                                </a>
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
        </div>
    </div>
</div>

<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->



<?php $this->load->view('modal_novo_estoque'); ?>

<script>
$('#novo_estoque').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget)
    var item_titulo = button.data('item')
    var id_insumo = button.data('id_insumo')
    $('#item-titulo').val(item_titulo)
    $('#id_insumo').val(id_insumo)
})

const options = {
    serverSide: false,
    searchable: true,
}

$(window).ready(() => loadDataTable('insumo_index', options))
$(window).resize(() => loadDataTable('insumo_index', options))
</script>