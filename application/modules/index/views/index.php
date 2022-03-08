<div id="index">
    <section class="welcome p-t-10">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="title-4">
                        Bem vindo
                        <span style="color: orange;"><b><?php echo ucwords(explode(' ', $this->session->userdata('logado')->nome)[0] ?: $this->session->userdata('logado')->usuario); ?>!</b></span>
                    </h1>
                    <p>Hoje é dia <?php setlocale(LC_ALL, 'pt_BR'); echo date("d/m/Y"); ?> - <?php echo $_SERVER['REMOTE_ADDR']; ?></p>
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
                <div class="col-12">
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
                <!-- <div class="col-12 col-md-6">
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
                </div> -->
                <?php 
                    $this->load->view("dados_operacionais_veiculos");
                    $this->load->view('informe_de_vencimentos', ['informe_vencimentos' => $informe_vencimentos]);
                    $this->load->view("requisicoes_pendentes");  
                    $this->load->view("retiradas_pendentes");
                    $this->load->view("ativos_manutencoes");
                ?>
            </div>
        </div>
    </section>
    <?php } ?>


    <?php if($user->nivel==2){ ?>
    <hr class="line-seprate" />                
    <section class="p-t-20">
        <div class="container">
            <div class="row"> 
                <?php 
                    $this->load->view("dados_operacionais_veiculos"); 
                    $this->load->view("requisicoes_pendentes");  
                    $this->load->view("retiradas_pendentes");
                    $this->load->view("ativos_manutencoes"); 
                ?>
            </div>
        </div>
    </section>
    <?php } ?>
                
    <hr class="line-seprate" />                
    <section class="p-t-20">
        <div class="container">
            <div class="row">
            <?php $this->load->view("amostra_de_patrimonio"); ?>
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