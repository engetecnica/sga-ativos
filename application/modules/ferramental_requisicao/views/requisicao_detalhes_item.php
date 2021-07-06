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
                        <h2 class="title-1"></h2>
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

                        <table class="table table-borderless table-striped table-earning" id="itens">
                            <thead>
                                <tr class="active">
                                    <th scope="col" width="5%">Item</th>
                                    <th scope="col" width="40%">Nome</th>
                                    <th scope="col">Condição Atual</th>
                                    <th scope="col">#</th>
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
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
<style>
/* This code is generated by: https://webdesign-assistant.com */
#liberar_requisicao_btn {
    text-decoration: none;
    font-size: 16px;
    color: #FFFFFF;
    font-family: arial;
    background: linear-gradient(to bottom, #FF480E, #D02718);
    border: solid #FF4B18 1px;
    border-radius: 5px;
    padding:10px;
    text-shadow: 0px 1px 2px #000000;
    *box-shadow: 0px 1px 5px #0D2444;
    -webkit-transition: all 0.15s ease;
    -moz-transition: all 0.15s ease;
    -o-transition: all 0.15s ease;
    transition: all 0.15s ease;
    width: 240px;
}
#liberar_requisicao_btn:hover{
    opacity: 0.9;
    background: linear-gradient(to bottom, #C02028, #D02718);
    border: 1px solid #c02028;
    *box-shadow: 0px 1px 2px #000000;
}
</style>

<script src="<?php echo base_url('assets'); ?>/vendor/jquery-3.2.1.min.js"></script>
<script>


        $(".modificar_condicao").click(function(){

            var id_ativo_externo = $(this).attr('data-id_ativo_externo');
            var acao = $(this).attr('data-acao');

            /* Tipos de Ação Disponíveis */
            /*
                Transferir
                Com Defeito
                Devolução
                Em Operação
                Fora de Operação
            */

            if(acao=='transferir')
            {


                        Swal.fire({
                            html: 'Aguarde enquanto o sistema processa sua requisição.',

                            didOpen: () => {
                                Swal.showLoading()
                                timerInterval = setInterval(() => {
                                    const content = Swal.getContent()
                                    if (content) {
                                        const b = content.querySelector('b')
                                        if (b) {
                                            b.textContent = Swal.getTimerLeft()
                                        }
                                    }
                                }, 100);



                               

                                

                                var timer = setTimeout(function() {

                                    $.ajax({
                                        url: '<?php echo base_url('ferramental_requisicao/acao/transferir'); ?>',
                                        type: "post",
                                        data: 
                                            {
                                                id_ativo_externo: id_ativo_externo, 
                                                acao:acao
                                            },
                                        success: function (response) {
                                           var conteudo = response;  

                                                                                       Swal.fire({
                                      title: 'Transferência de Item',
                                      html: response,
                                      showConfirmButton: false
                                    })
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) {
                                           console.log(textStatus, errorThrown);
                                        }
                                    }) 



 
                                }, 5000);

                                var timer = setTimeout(function() {
                                    //Swal.close();
                                    //location.reload();
                                }, 8000);

                            }
                        });
            }


            //alert(id_ativo_externo)
            //alert(acao)
        }); 
</script>
