<style type="text/css">
    .texto-historico { font-size: 12px; font-family: Tahoma; padding: 5px !important; }
</style>

<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url("ativo_externo"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>Voltar a Lista</button></a>
                    </div>

                    <div class="overview-wrap m-t-10">
                       
                        <a href="<?php echo base_url("ativo_externo/editar/{$detalhes->id_ativo_externo}"); ?>">
                        <button class="">
                        <i class="zmdi zmdi-arrow-left"></i>Voltar ao Ativo</button></a>
                    </div>
                </div>
            </div>

            <h2 class="title-1 m-b-25">Detalhes do Kit</h2>

            <div class="">
              <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="title-1 m-b-25">Adicionar</h3>
                        <div class="card">

                            <table class="table table-responsive-md table-borderless table-striped table-earning" id="lista2">
                                <thead>
                                    <tr class="active">
                                        <th width="25%" scope="col">Código</th>
                                        <th width="75%" scope="col">Nome</th>
                                        <th width="10%" scope="col">Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $i = 1; 
                                        foreach($lista as $item){ 
                                    ?>
                                    <tr>
                                        <td width="30%">
                                          <button class="badge badge-sm badge-success" width="100%" >   
                                              <?php echo $item->codigo; ?>
                                          </button>
                                        </td>
                                        <td><?php echo $item->nome; ?></td>
                                        <td width="7%">
                                          <a title="Adicionar ao Kit"  href="<?php echo base_url('ativo_externo'); ?>/adicionar_item_kit/<?php echo $detalhes->id_ativo_externo; ?>/<?php echo $item->id_ativo_externo; ?>">
                                            <button class="btn btn-sm btn-primary" type=""button>
                                              <i class="fa fa-plus"></i> <?php echo ''; ?>
                                            </button>
                                         </a>
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
                        <h3 class="title-1 m-b-25">Todos</h3>
                        <div class="card">

                            <table class="table table-responsive-md table-borderless table-striped table-earning" id="lista">
                                <thead>
                                    <tr class="active">
                                        <th width="25%" scope="col">Código</th>
                                        <th width="75%" scope="col">Nome</th>
                                        <th width="10%" scope="col">Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $i = 1; 
                                        foreach($items as $item){ 
                                    ?>
                                    <tr>
                                        <td width="30%">
                                          <button class="badge badge-sm badge-success" width="100%" >   
                                              <?php echo $item->codigo; ?>
                                          </button>
                                        </td>
                                        <td><?php echo $item->nome; ?></td>
                                        <td width="7%">
                                          <a title="Remover do Kit"  href="<?php echo base_url('ativo_externo'); ?>/remover_item_kit/<?php echo $detalhes->id_ativo_externo; ?>/<?php echo $item->id_ativo_externo; ?>">
                                            <button class="btn btn-sm btn-danger" type=""button>
                                              <i class="fa fa-minus"></i> <?php echo ''; ?>
                                            </button>
                                         </a>
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
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->