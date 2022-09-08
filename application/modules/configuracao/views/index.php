<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Configuração</h2>

                    <div class="card">
                        <div class="card-header">Configuração Geral</div>
                        <div class="card-body">

                            <form action="<?php echo base_url('configuracao/salvar'); ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id_configuracao" id="id_configuracao" value="1">

                                <h5 class="m-b-20">Email e Notificações</h5>
                                <div class="row form-group">
                                    <div class="col col-lg-3">
                                        <label for="app_descricao" class=" form-control-label">Descrição do Aplicativo</label>
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <input type="text" id="app_descricao" name="app_descricao" placeholder="Engetecnica App" class="form-control" value="<?php if(isset($configuracao) && isset($configuracao->app_descricao)){ echo $configuracao->app_descricao; } ?>">
                                    </div>

                                    <div class="col col-lg-3">
                                        <label for="origem_email" class=" form-control-label">Email Origem de Notificações</label>
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <input type="text" id="origem_email" name="origem_email" placeholder="app@engetecnica.ex" class="form-control" value="<?php if(isset($configuracao) && isset($configuracao->origem_email)){ echo $configuracao->origem_email; } ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-lg-3">
                                        <label for="permit_notificacoes" class=" form-control-label">Enviar Notificações e Emails</label>
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <select name="permit_notificacoes" id="permit_notificacoes" class="form-control">
                                            <option value="1" <?php if(isset($configuracao) && isset($configuracao->permit_notificacoes) && $configuracao->permit_notificacoes==1){ echo "selected='selected'"; } ?>>Ativo</option>
                                            <option value="0" <?php if(isset($configuracao) && isset($configuracao->permit_notificacoes) && $configuracao->permit_notificacoes==0){ echo "selected='selected'"; } ?>>Inativo</option>
                                        </select>
                                    </div>
                                </div>


                                <h6 class="m-b-20 m-t-40"><a target="_blank" href="https://onesignal.com/">One Signal</a> - Gerenciador de Notificações Push</h6>
                                <div class="row form-group">
                                    <div class="col col-lg-1">
                                        <label for="one_signal_appid" class=" form-control-label">APP ID</label>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <input type="text" id="one_signal_appid" name="one_signal_appid" placeholder="825788da-a801-4c3e-9a05-8d683c5af4e7" class="form-control" value="<?php if(isset($configuracao) && isset($configuracao->one_signal_appid)){ echo $configuracao->one_signal_appid; } ?>">
                                    </div>

                                    <div class="col col-lg-1">
                                        <label for="one_signal_apikey" class=" form-control-label">API Key</label>
                                    </div>
                                    <div class="col-12 col-lg-5">
                                        <input type="text" id="one_signal_apikey" name="one_signal_apikey" placeholder="MjhiZGU9ZjItZThhNy00X*I3LTk2ZjctMmFmNzY2Mzg5MDIz" class="form-control" value="<?php if(isset($configuracao) && isset($configuracao->one_signal_apikey)){ echo $configuracao->one_signal_apikey; } ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-lg-1">
                                        <label for="one_signal_apiurl" class=" form-control-label">API Url</label>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <input type="text" id="one_signal_apiurl" name="one_signal_apiurl" placeholder="https://onesignal.com" class="form-control" value="<?php if(isset($configuracao) && isset($configuracao->one_signal_apiurl)){ echo $configuracao->one_signal_apiurl; } ?>">
                                    </div>


                                    <div class="col col-lg-1">
                                        <label for="one_signal_safari_web_id" class=" form-control-label">Safari Web ID</label>
                                    </div>
                                    <div class="col-12 col-lg-5">
                                        <input type="text" id="one_signal_safari_web_id" name="one_signal_safari_web_id" placeholder="web.onesignal.auto.0ff8adab-4968-437b-ay06-36c9d15991be" class="form-control" value="<?php if(isset($configuracao) && isset($configuracao->one_signal_safari_web_id)){ echo $configuracao->one_signal_safari_web_id; } ?>">
                                    </div>
                                </div>

                                <h5 class="m-t-60 m-b-20">Veículos e Alertas</h5>
                                <div class="row form-group">
                                    <div class="col col-lg-3">
                                        <label for="km_alerta" class=" form-control-label">Alerta de próxima Revisão em KM</label>
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        <input type="text" id="km_alerta" name="km_alerta" placeholder="1000 KM" class="form-control km" value="<?php if(isset($configuracao) && isset($configuracao->km_alerta)){ echo $configuracao->km_alerta; } ?>">
                                    </div>

                                    <div class="col col-lg-3">
                                        <label for="operacao_alerta" class=" form-control-label">Alerta de próxima Revisão em Horas</label>
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        <input type="text" id="operacao_alerta" name="operacao_alerta" placeholder="1000 Horas" class="form-control horas" value="<?php if(isset($configuracao) && isset($configuracao->operacao_alerta)){ echo $configuracao->operacao_alerta; } ?>">
                                    </div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-lg-3">
                                        <label for="valor_medio_diesel" class=" form-control-label">Valor médio Diesel</label>
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        <input type="text" id="valor_medio_diesel" name="valor_medio_diesel" placeholder="10,00 R$" class="form-control valor" value="<?php if(isset($configuracao) && isset($configuracao->valor_medio_diesel)){ echo $configuracao->valor_medio_diesel; } ?>">
                                    </div>

                                    <div class="col col-lg-3">
                                        <label for="valor_medio_gasolina" class=" form-control-label">Valor médio Gasolina</label>
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        <input type="text" id="valor_medio_gasolina" name="valor_medio_gasolina" placeholder="10,00 R$" class="form-control valor" value="<?php if(isset($configuracao) && isset($configuracao->valor_medio_gasolina)){ echo $configuracao->valor_medio_gasolina; } ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-lg-3">
                                        <label for="valor_medio_etanol" class=" form-control-label">Valor médio Etanol(Álcool) </label>
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        <input type="text" id="valor_medio_etanol" name="valor_medio_etanol" placeholder="10,00 R$" class="form-control valor" value="<?php if(isset($configuracao) && isset($configuracao->valor_medio_etanol)){ echo $configuracao->valor_medio_etanol; } ?>">
                                    </div>

                                    <div class="col col-lg-3">
                                        <label for="valor_medio_gnv" class=" form-control-label">Valor médio Gás Natual (GNV) <b class="text-danger">*</b></label>
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        <input type="text" id="valor_medio_gnv" name="valor_medio_gnv" placeholder="10,00 R$" class="form-control valor" value="<?php if(isset($configuracao) && isset($configuracao->valor_medio_gnv)){ echo $configuracao->valor_medio_gnv; } ?>">
                                    </div>
                                </div>    
                                
                                <small><b class="text-danger">*</b> Valor médio por litro, execeto GNV, para esse o valor representa o custo médio por metro cúbico usado como base de cálculos relacionados na aplicação.</small>

                                <hr>
                                <div class="pull-left">
                                    <button class="btn btn-primary">                                                    
                                        <i class="fa fa-send "></i>&nbsp;
                                        <span id="submit-form">Salvar</span>
                                    </button>
                                    <a href="<?php echo base_url("configuracao");?>">
                                    <button class="btn btn-secondary" type="button">                                   
                                        <i class="fa fa-ban "></i>&nbsp;
                                        <span id="cancelar-form">Cancelar</span>
                                    </button>                              
                                    </a>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
