<div id="index">
    <section class="welcome p-t-10">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="title-4">
                        Bem vindo
                        <span style="color: orange;"><b><?php echo ucwords(explode(' ', $this->session->userdata('logado')->nome)[0] ?: $this->session->userdata('logado')->usuario); ?>!</b></span>
                    </h1>

                    <?php if (isset($this->session->userdata('logado')->obra)) { ?>
                        <h4><?php echo $this->session->userdata('logado')->obra->codigo_obra; ?></h4>
                    <?php } ?>

                    <hr>
                    <p>Hoje é dia <?php setlocale(LC_ALL, 'pt_BR');
                                    echo date("d/m/Y"); ?> - <?php echo $_SERVER['REMOTE_ADDR']; ?></p>
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
                <?php if ($user->nivel == 1) { ?>
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

    <?php if ($user->nivel == 2) { ?>
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
</div>