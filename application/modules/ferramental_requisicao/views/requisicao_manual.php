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
                       
                        <a href="<?php echo base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-arrow-left"></i>Voltar</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">

                    <?php 
                        $url = "ferramental_requisicao/receber_item_manual/{$requisicao->id_requisicao}";
                        if (isset($requisicao->item)) {
                            $url .= "/{$requisicao->item->id_requisicao_item}";
                        }
                    ?>
                    <form 
                        class="confirm-submit" action="<?php echo base_url($url); ?>" method="post" enctype="multipart/form-data"
                        data-acao="Receber" data-icon="warning" data-message="false"
                        data-title="Receber Transferência" data-redirect="true"
                        data-text="Clique 'Sim, Receber!' para confirmar a recepção/devolução dos itens solicitados.
                        Ao atestar que os items estão todos funcionando, a responsabilidade é inteiramente sua, por isso,
                        confira a situação de cada item antes de aceitá-los."> 

                        <input type="hidden" name="id_requisicao" id="id_requisicao" value="<?php echo $requisicao->id_requisicao; ?>">
                        <h2 class="title-1 m-b-25">Detalhar Itens da Requisição</h2>

                        <div class="card">
                            <div class="card-body">

                                <!-- Detalhes da Requisição -->
                                <table class="table table-responsive-md table--no-card table-borderless table-striped table-earning">
                                    <thead>
                                        <tr class="active">
                                            <th scope="col" width="10%">Cód. Ativo</th>
                                            <th scope="col" width="20%">Nome do Item</th>
                                            <th scope="col" width="">Observações</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($requisicao->ativos as $manual){ ?>
                                        <tr>
                                            <td><?php echo $manual->codigo; ?></td>
                                            <td><?php echo $manual->nome; ?></td>
                                            <td><input type="" class="form-control" name="observacoes[]" id="observacoes[]" placeholder="<?php echo $manual->codigo; ?> - Observações" value="<?php if($manual->observacao) echo $manual->observacao; ?>" disabled="disabled"></td>
                                            <td>
                                                <input type="hidden" name="id_requisicao_ativo[]" id="id_requisicao_ativo[]" value="<?php echo $manual->id_requisicao_ativo; ?>">
                                                <select 
                                                    <?php echo (isset($no_aceite) && $no_aceite == true )? 'disabled' : ''; ?>
                                                    class="form-control" name="status[]" id="status[]" required="required"
                                                >
                                                    <?php if (!in_array($requisicao->status, [3, 13]) || $no_aceite == true) {?>
                                                    <option readonly="readonly" value="" <?php if($manual->situacao && $manual->situacao==12) echo "selected='selected'"; ?>>Em estoque</option>
                                                    <option readonly="readonly" value="" <?php if($manual->situacao && $manual->situacao==2) echo "selected='selected'"; ?>>Liberado</option>
                                                    <?php } ?>
                                                    <option value="4" <?php if($manual->situacao && $manual->situacao==4) echo "selected='selected'"; ?>>Recebido</option>
                                                     <!--<option value="5" <?php if($manual->situacao && $manual->situacao==5) echo "selected='selected'"; ?>>Em Operação</option>-->
                                                    <option value="8" <?php if($manual->situacao && $manual->situacao==8) echo "selected='selected'"; ?>>Com Defeito </option>
                                                    <option value="9" <?php if($manual->situacao && $manual->situacao==9) echo "selected='selected'"; ?>>Devolvido</option>
                                                    <!--<option value="10" <?php if($manual->situacao && $manual->situacao==10) echo "selected='selected'"; ?>>Fora de Operação</option>-->
                                                </select>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <?php if(in_array($requisicao->status, [3, 13]) && (!isset($no_aceite) || isset($no_aceite) && $no_aceite == false)){ ?>
                                    <hr>
                                    <div class="text-center">
                                        <p class="text-center" style="padding: 25px;"><b>Atenção:</b> Todos os items acima descritos foram transferidos e estão sendo recebidos por você. <br>Ao atestar que os items estão todos funcionando, a responsabilidade é inteiramente sua, por isso,<br> <font color='red'>confira a situação de cada item antes de aceitá-los.</font> </p>
                                        <hr>
                                        <button class="btn btn-success" type="submit">
                                            <i class="fa fa-check "></i>&nbsp;
                                            <b class="d-none d-lg-block">Estou de acordo, Aceitar os items!</b>
                                            <b class="d-lg-none" >Sim, Aceitar items!</b>
                                        </button>
                                    </div>   
                                <?php } ?>                             

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>