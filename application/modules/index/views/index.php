<div id="index">
                <section class="welcome p-t-10">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="title-4">
                                    Bem vindo
                                    <span style="color: orange;"><b><?php echo $this->session->userdata('logado')->usuario; ?>!</b></span>
                                </h1>
                                <p>Hoje é dia <?php echo date("d/m"); ?> - <?php echo $_SERVER['REMOTE_ADDR']; ?></p>
                                <hr class="line-seprate" />
                            </div>
                        </div>
                    </div>
                </section>

                <section class="statistic statistic2">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="statistic__item statistic__item--green">
                                    <h2 class="number"><?php echo $clientes; ?></h2>
                                    <span class="desc">Clientes</span>
                                    <div class="icon">
                                        <i class="zmdi zmdi-account-o"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="statistic__item statistic__item--orange">
                                    <h2 class="number"><?php echo $colaboradores; ?></h2>
                                    <span class="desc">Colaboradores</span>
                                    <div class="icon">
                                        <i class="zmdi zmdi-shopping-cart"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="statistic__item statistic__item--blue">
                                    <h2 class="number"><?php echo $veiculos_manutencao; ?></h2>
                                    <span class="desc">veículos na manutenção</span>
                                    <div class="icon">
                                        <i class="zmdi zmdi-calendar-note"></i>
                                    </div>
                                </div>
                            </div>
                            <?php if($user->nivel == 1){ ?>
                            <div class="col-md-6 col-lg-3">
                                <div class="statistic__item statistic__item--red">
                                    <h2 class="number"><?php echo $estoque; ?></h2>
                                    <span class="desc">Itens em Estoque</span>
                                    <div class="icon">
                                        <i class="zmdi zmdi-money"></i>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </section>

                <?php if($user->nivel == 1){ ?>
                <section class="statistic-chart">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="title-4 m-b-35">Estatísticas</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-md-6">
                                <div class="statistic-chart-1">
                                    <h3 class="title-3 m-b-30">Crescimento da Empresa</h3>
                                    <div class="chart-wrap">
                                        <canvas id="crecimento_empresa" width="400" height="400"></canvas>
                                    </div>
                                    
                                    <div class="statistic-chart-1-note">
                                        <span class="big">Taxa</span>
                                        <span>% em Porcentagem para cada mês do último ano</span>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="col col-md-6">
                                <div class="top-campaign">
                                    <h3 class="title-3">Requisições Pendentes</h3>
                                    <p>Até <?php echo $this->formata_data_hora(date('Y-m-d H:i:s', strtotime('now'))); ?></p><br>
                                    <?php if (!empty($requisicoes_pendentes)) { ?>
                                    <table class="table table-responsive table-borderless table-striped table-earning">
                                        <thead>
                                            <th scope="col" width="40%">Requisição ID / Solicitante</th> 
                                            <th scope="col" width="40%">Destino</th>
                                            <th scope="col" width="40%">Data Inclusão</th>
                                            <th scope="col" width="40%">Status</th>
                                            <th scope="col" width="40%">Detalhes</th>
                                        </thead>

                                        <tbody>
                                            <?php
                                             foreach($requisicoes_pendentes as $requisicao) {   
                                                 $solicitante = ucwords($requisicao->solicitante); 
                                                 $status = $this->status($requisicao->status);;
                                            ?>
                                            <tr>
                                                <td>
                                                    <a  href="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}");?>">
                                                        <?php echo "{$requisicao->id_requisicao} - {$solicitante}";?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php echo "{$requisicao->destino}";?>
                                                </td>
                                                <td>
                                                    <?php echo $this->formata_data($requisicao->data_inclusao); ?>
                                                </td>
                                                <td>
                                                    <span class="badge badge-<?php echo $status['class'];?>"><?php echo $status['texto'];?></span>
                                                </td>
                                                <td>
                                                    <a class="btn btn-sm btn-outline-primary" href="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}") ?>">Mais Detalhes</a>
                                                </td>
                                            </tr>
                                            <?php }  ?>
                                        </tbody>
                                    </table>
                                
                                    <div class="row">
                                        <div class="col-12 col-md-8 m-t-20"> 
                                            <?php echo count($requisicoes_pendentes); ?> 
                                            De 
                                            <?php echo $requisicoes_pendentes_total; ?> 
                                            Requisições Pendentes
                                        </div>
                                        <a class="col-12 offset-md-1 col-md-3 m-t-20 btn btn-sm btn-outline-secondary"  href="<?php echo base_url("ferramental_requisicao/"); ?>" >Ver Todas</a> 
                                    </div>
                                    <?php } else { ?>
                                        <p>Nenhuma Requisicão Pendente</p>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php $this->load->view("retiradas_pendentes"); ?>

                            <?php 
                                $this->load->view('informe_de_vencimentos', [
                                    'informe_vencimentos' => $informe_vencimentos_hoje,
                                    'dias' => 0,
                                ]);

                                $this->load->view('informe_de_vencimentos', [
                                    'informe_vencimentos' => $informe_vencimentos_5dias,
                                    'dias' => 5,
                                ]);

                                $this->load->view('informe_de_vencimentos', [
                                    'informe_vencimentos' => $informe_vencimentos_15dias,
                                    'dias' => 15,
                                ]); 

                                $this->load->view('informe_de_vencimentos', [
                                    'informe_vencimentos' => $informe_vencimentos_30dias,
                                    'dias' => 30,
                                ]);
                            ?>

                            <!--
                            <div class="col-md-6 col-lg-4">
                                <div class="chart-percent-2">
                                    <h3 class="title-3">Volume de Pedidos</h3>
                                    <div class="chart-wrap">
                                        <canvas id="percent-chart2"></canvas>
                                        <div id="chartjs-tooltip">
                                            <table></table>
                                        </div>
                                    </div>
                                    <div class="chart-info">
                                        <div class="chart-note">
                                            <span class="dot dot--blue"></span>
                                            <span>products</span>
                                        </div>
                                        <div class="chart-note">
                                            <span class="dot dot--red"></span>
                                            <span>Services</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            -->
                        </div>
                    </div>
                </section>
                <?php } ?>


                <?php if($user->nivel==2){ ?>
                <hr class="line-seprate" />                
                <section class="p-t-20">
                    <div class="container">
                        <div class="row"> 
                            <?php $this->load->view("retiradas_pendentes"); ?>
                        </div>
                    </div>
                </section>
                <?php } ?>
                

                <hr class="line-seprate" />                
                <section class="p-t-20">
                    <div class="container">
                        <div class="row">
                        <div class="col-12">
                            <div class="top-campaign">
                            <div class="col-md-12">
                                <h3 class="title-3">Amostra de Patrimônio </h3>
                                <p class="m-b-35">Ferramentas na Obra, Equipamentos na Obra, todos os veículos re Retiradas</p>
                                <?php foreach($patrimonio->obras as $obra) { ?>
                                    

                                    <div class="table-responsive m-b-40">
                                        <h4 class="title-5 m-t-10 m-l-10">Ferramentas</h4>
                                        <?php if(count($obra->ferramentas) > 0) { ?>
                                        <table class="table table-responsive-md table-borderless table-striped table-earning" id="lista">
                                            <thead>
                                                <tr>
                                                    <th scope="col" width="30%">ID</th>
                                                    <th scope="col" width="30%">Código</th>
                                                    <th scope="col" width="30%">Nome</th>
                                                    <th scope="col" width="30%">Registro</th>
                                                    <th scope="col" width="30%">Descarte</th>
                                                    <th scope="col" width="30%">Situação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($obra->ferramentas as $ferramenta) { ?>
                                                <tr>
                                                    <td><?php echo $ferramenta->id_ativo_externo; ?></td>
                                                    <td><?php echo $ferramenta->codigo; ?></td>
                                                    <td><?php echo $ferramenta->nome; ?></td>
                                                    <td><?php echo date('d/m/Y H:i:s', strtotime($ferramenta->data_inclusao)); ?></td>
                                                    <td><?php echo isset($ferramenta->data_descarte) ? date('d/m/Y H:i:s', strtotime($ferramenta->data_descarte)) : '-'; ?></td>
                                                    <td>
                                                    <?php $situacao = $this->status($ferramenta->situacao);?>
                                                        <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php } else { ?>
                                            <p class="m-b-10 m-t-10 m-l-10">Nenhuma Ferramenta registrada no Local</p>
                                        <?php } ?>
                                    </div>

                                    <div class="table-responsive m-b-40">
                                        <h4 class="title-5 m-t-10 m-l-10">Equipamentos</h4>
                                        <?php if(count($obra->equipamentos) > 0) { ?>
                                            <table class="table table-responsive-md table-borderless table-striped table-earning" id="lista2">
                                            <thead>
                                                <tr>
                                                    <th scope="col" width="30%">ID</th>
                                                    <th scope="col" width="30%">Nome</th>
                                                    <th scope="col" width="30%">Registro</th>
                                                    <th scope="col" width="30%">Descarte</th>
                                                    <th scope="col" width="30%">Situação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($obra->equipamentos as $equipamento) { ?>
                                                <tr>
                                                    <td><?php echo $equipamento->id_ativo_interno; ?></td>
                                                    <td><?php echo $equipamento->nome; ?></td>
                                                    <td><?php echo date('d/m/Y H:i:s', strtotime($equipamento->data_inclusao)); ?></td>
                                                    <td><?php echo isset($equipamento->data_descarte) ? date('d/m/Y H:i:s', strtotime($equipamento->data_descarte)) : '-'; ?></td>
                                                    <td>
                                                    <?php $situacao = $this->get_situacao($equipamento->situacao);?>
                                                    <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php } else { ?>
                                            <p class="m-b-10 m-t-10 m-l-10">Nenhum Equipamento registrado no Local</p>
                                        <?php } ?>
                                    </div>

                                     <?php if($user->nivel == 1) { ?>                                           
                                        <div class="table-responsive m-b-40">
                                            <h4 class="title-5 m-t-10 m-l-10">Veículos</h4>
                                            <?php if(count($patrimonio->veiculos) > 0) { ?>
                                                <table class="table table-responsive-md table-borderless table-striped table-earning" id="lista3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" width="30%">ID</th>
                                                        <th scope="col" width="30%">Placa</th>
                                                        <th scope="col" width="30%">Tipo</th>
                                                        <th scope="col" width="30%">Marca/Modelo</th>
                                                        <th scope="col" width="30%">Kilometragem</th>
                                                        <th scope="col" width="30%">Situação</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($patrimonio->veiculos as $j => $veiculo) { ?>
                                                    <tr>
                                                        <td><?php echo $veiculo->id_ativo_veiculo; ?></td>
                                                        <td><?php echo $veiculo->veiculo_placa; ?></td>
                                                        <td><?php echo ucfirst($veiculo->tipo_veiculo);?> </td>
                                                        <td><?php echo $veiculo->veiculo;?> </td>
                                                        <td><?php echo $veiculo->veiculo_km; ?></td>
                                                        <td>
                                                        <?php $situacao = $this->get_situacao($veiculo->situacao);?>
                                                        <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <?php } else { ?>
                                                <p class="m-b-10 m-t-10 m-l-10">Nenhum Veículo registrado</p>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </section>


                <section class="p-t-60 p-b-20">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="copyright">
                                    <p>Copyright © <?php echo date("Y"); ?>. All rights reserved.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
</div>

<script>
    new Vue({
        el: "#index",
        data(){
            return {
                pinned: 0,
                informe_vencimentos: 0,
                informe_vencimentos_dias: {
                    'Hoje' : 0,
                    'em 5 Dias': 5,
                    'em 15 Dias': 15,
                    'em 30 Dias': 30,
                }
            }
        },
        methods: {
            setPinned(){
                this.pinned = this.informe_vencimentos
                localStorage.pinned = this.pinned
            }
        },
        created(){
            if (localStorage.pinned != undefined) {
                this.pinned = parseInt(localStorage.pinned)
            } else {
                this.pinned = 0
            }

            this.informe_vencimentos = this.pinned
        }
    })
</script>