<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="<?php echo base_url('ferramental_requisicao'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Detalhes da Requisição - Item</h2>
                    <div class="card">

                        <table class="table table-responsive-md table--no-card table-borderless table-striped table-earning" id="items">
                            <thead>
                                <tr class="active">
                                    <th scope="col" width="30%">Item</th>
                                    <th scope="col" width="30%">Nome</th>
                                    <th scope="col" width="30%">Condição Atual</th>
                                    <th scope="col" width=5%">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i = 1; 
                                    foreach($requisicao_detalhes_item as $item){ 
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $item->nome; ?></td>
                                    <td width="7%"><button class="btn btn-sm btn-secondary" type=""button><i class="fa fa-lightbulb-o"></i> <?php echo $item->condicao; ?></button></td>
                                    <td width="7%">
                                        
                                        <div class="input-group">                                                        
                                            <div class="input-group-btn">
                                                <div class="btn-group">
                                                    <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn btn-outline-danger btn-sm"><i class="fa fa-map-marker"></i> Modificar Condição</button>
                                                    <div 
                                                        tabindex="-1" 
                                                        aria-hidden="true"
                                                        role="menu" 
                                                        class="dropdown-menu" 
                                                        x-placement="bottom-start" 
                                                        style="
                                                            position: absolute; 
                                                            transform: translate3d(0px, 38px, 0px); 
                                                            top: 0px; left: 0px; 
                                                            will-change: transform;
                                                    ">

                                                        <button 
                                                            type="button" 
                                                            tabindex="0" 
                                                            class="dropdown-item modificar_condicao" 
                                                            data-acao="transferir" 
                                                            data-id_ativo_externo="<?php echo $item->id_ativo_externo; ?>"
                                                        >Transferir
                                                        </button>
                                                        
                                                        <button 
                                                            type="button" 
                                                            tabindex="0" 
                                                            class="dropdown-item modificar_condicao"
                                                            data-acao="comdefeito" 
                                                            data-id_ativo_externo="<?php echo $item->id_ativo_externo; ?>"
                                                        >Com Defeito
                                                        </button>

                                                        <button 
                                                            type="button" 
                                                            tabindex="0" 
                                                            class="dropdown-item modificar_condicao"
                                                            data-acao="devolucao" 
                                                            data-id_ativo_externo="<?php echo $item->id_ativo_externo; ?>"
                                                        >Devolução
                                                        </button>

                                                        <button 
                                                            type="button" 
                                                            tabindex="0" 
                                                            class="dropdown-item modificar_condicao"
                                                            data-acao="emoperacao" 
                                                            data-id_ativo_externo="<?php echo $item->id_ativo_externo; ?>"
                                                        >Em Operação
                                                        </button>

                                                        <button 
                                                            type="button" 
                                                            tabindex="0" 
                                                            class="dropdown-item modificar_condicao"
                                                            data-acao="foradeoperacao" 
                                                            data-id_ativo_externo="<?php echo $item->id_ativo_externo; ?>"
                                                        >Fora de Operação
                                                        </button>                                
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                <?php $i++; } ?>
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