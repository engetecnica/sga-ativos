

                <section class="welcome p-t-10" style="margin-top: 100px">
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

                <?php

                    echo "<pre>";
                    print($this->session->userdata('logado'));
                    echo "</pre>";
                ?>




                <section class="statistic statistic2">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="statistic__item statistic__item--green">
                                    <h2 class="number">0</h2>
                                    <span class="desc">Clientes</span>
                                    <div class="icon">
                                        <i class="zmdi zmdi-account-o"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="statistic__item statistic__item--orange">
                                    <h2 class="number">0</h2>
                                    <span class="desc">Colaboradores</span>
                                    <div class="icon">
                                        <i class="zmdi zmdi-shopping-cart"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="statistic__item statistic__item--blue">
                                    <h2 class="number">0</h2>
                                    <span class="desc">veículos na manutenção</span>
                                    <div class="icon">
                                        <i class="zmdi zmdi-calendar-note"></i>
                                    </div>
                                </div>
                            </div>
                            <?php if($this->session->userdata('logado')->nivel==1){ ?>
                            <div class="col-md-6 col-lg-3">
                                <div class="statistic__item statistic__item--red">
                                    <h2 class="number">0</h2>
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

                <?php if($this->session->userdata('logado')->nivel==1){ ?>
                <section class="statistic-chart">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="title-5 m-b-35">Estatísticas</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="statistic-chart-1">
                                    <h3 class="title-3 m-b-30">Crescimento da Empresa</h3>
                                    <div class="chart-wrap">
                                        <canvas id="widgetChart5"></canvas>
                                    </div>
                                    <div class="statistic-chart-1-note">
                                        <span class="big">10,368</span>
                                        <span>/ 16220 items sold</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="top-campaign">
                                    <h3 class="title-3 m-b-30">Requisições Pendentes</h3>
                                    <div class="table-responsive">
                                        <table class="table table-top-campaign">
                                            <tbody>
                                                <tr>
                                                    <td>1. Australia</td>
                                                    <td>$70,261.65</td>
                                                </tr>
                                                <tr>
                                                    <td>2. United Kingdom</td>
                                                    <td>$46,399.22</td>
                                                </tr>
                                                <tr>
                                                    <td>3. Turkey</td>
                                                    <td>$35,364.90</td>
                                                </tr>
                                                <tr>
                                                    <td>4. Germany</td>
                                                    <td>$20,366.96</td>
                                                </tr>
                                                <tr>
                                                    <td>5. France</td>
                                                    <td>$10,366.96</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="chart-percent-2">
                                    <h3 class="title-3 m-b-30">Volume de Pedidos</h3>
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
                        </div>
                    </div>
                </section>
                <?php } ?>

                <hr class="line-seprate" />                
                <section class="p-t-20">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="title-5 m-b-35">Amostra de Patrimônio </h3>
                                <div class="table-responsive table-responsive-data2">
                                    <table class="table table-data2">
                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>#</th>
                                                <th>#</th>
                                                <th>#</th>
                                                <th>#</th>
                                                <th>#</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tr-shadow">
                                                <td>23/03/2021 12:00</td>
                                                <td>1221</td>
                                                <td class="desc">R$ 0,00</td>
                                                <td>R$ 0,00</td>
                                                <td>R$ 0,00</td>
                                                <td>R$ 0,00</td>
                                                <td>R$ 0,00</td>
                                            </tr>
                                            <tr class="spacer"></tr>
                                        </tbody>
                                    </table>
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
