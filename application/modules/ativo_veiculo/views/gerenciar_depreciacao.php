<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
                        <a href="<?php echo base_url('ativo_veiculo/gerenciar/depreciacao/adicionar/'.$id_ativo_veiculo); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Gerenciar Depreciação</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th width="15%">Veículo</th>
                                    <th width="15%">Placa</th>
                                    <th width="15%">Cód Fipe</th>
                                    <th width="20%">Renavam</th>
                                    <th>Ano</th>
                                    <th>Inclusão</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $dados_veiculo->veiculo; ?></td>
                                    <td><?php echo $dados_veiculo->veiculo_placa; ?></td>
                                    <td><?php echo $dados_veiculo->codigo_fipe; ?></td>
                                    <td><?php echo $dados_veiculo->veiculo_renavam; ?></td>
                                    <td><?php echo $dados_veiculo->ano; ?></td>
                                    <td><?php echo date("d/m/Y H:i:s", strtotime($dados_veiculo->data)); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="section__content section__content--p30">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive table--no-card m-b-40">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th width="15%">Data</th>
                                    <th width="15%">Kilometragem</th>
                                    <th width="15%">Valor</th>
                                    <th width="20%">Referência</th>
                                    <th>Observações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach($lista as $valor){ 
                                ?>
                                <tr>
                                    <td><?php echo date("d/m/Y H:i:s", strtotime($valor->veiculo_data)); ?></td>
                                    <td><?php echo $valor->veiculo_km; ?></td>
                                    <td>R$ <?php echo number_format($valor->valor_fipe, 2, ',', '.'); ?></td>
                                    <td><?php echo $valor->fipe_mes_referencia; ?></td>
                                    <td><?php echo $valor->veiculo_observacoes; ?></td>
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

