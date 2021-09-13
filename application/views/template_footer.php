    <!-- Bootstrap JS-->
    <script src="<?php echo base_url("assets/vendor/bootstrap-4.1/popper.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendor/bootstrap-4.1/bootstrap.min.js"); ?>"></script>
    <!-- Vendor JS       -->
    <script src="<?php echo base_url("assets/vendor/slick/slick.min.js"); ?>">
    </script>
    <script src="<?php echo base_url("assets/vendor/wow/wow.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendor/animsition/animsition.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js"); ?>">
    </script>

    <script src="<?php echo base_url("assets/js/Chart.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendor/select2/select2.min.js"); ?>"></script>

    <!-- Main JS-->
    <script src="<?php echo base_url("assets/js/main.js"); ?>"></script>

    <script src="<?php echo base_url("assets/js/jquery.mask.js"); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $config_lista = {
                "aLengthMenu": [
                    [10, 20, 30, 50, 100, -1],
                    [10, 20, 30, 50, 100, "Todos"]
                ],
                "order": false,
                "iDisplayLength": 10,
                "language" : {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_ resultados por página",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    }
                }
            };

            $('#lista').DataTable($config_lista);
            $('#lista2').DataTable($config_lista);
            $('#lista3').DataTable($config_lista);
            $('#lista4').DataTable($config_lista);

            $('.paginate_button').click((e) => {
                setTimeout(() => {
                    let lista = document.querySelector(`#${$(e.target).attr('aria-controls')}`)
                    if (lista) {
                        lista.scrollIntoView({behavior: "smooth"})
                    }
                }, 100)
            })
        } ); 
    </script>

    <?php if($this->session->userdata('logado') == true){ ?>
        
    <script>

        /* Itens da Requisição múltipla */    
        $(".listagem").append($("#item_lista").html());
        
        $(document).on("click", ".add_line", function(){
            $(".listagem").append($("#item_lista").html());
            $(".listagem .item-lista").last().addClass('id_ativo_externo_grupo');
        })
        $(document).on("click", ".remove_line", function(){
            if($(".remove_line").length >= 1) {
                $(this).closest(".item-lista").remove();
            }
            if($(".remove_line").length == 0) {
                $(".listagem").append($("#item_lista").html());
                $(".listagem .item-lista").last();
            }
        })
    

        /* Set init functions */
        $('.select-search').select2();

        /* Adjust Anchor */
        var adjustAnchor = function(e) {
            e.preventDefault()
            var hash = $(window.location.hash)
            var header = $('#header-desktop')
            if(hash.length > 0 && header.length > 0) {
                window.scrollTo(0, hash.offset().top - (header.height() + 10));
            }
        };

        $(window).on('hashchange load', function(e) {
            setTimeout(() => {
                adjustAnchor(e);
            }, 10);
        });      

        $(document).ready(function () {
            $(window).on('resize',(event) => {
                var sidebar = $('#menu-sidebar');
                if ($(window).width() > 991 && (sidebar.length == 1 && !sidebar.is(':visible'))) {
                    sidebar.show('fast')
                }
            });
        });

        <?php if($this->session->flashdata('msg_success')==true){ ?>
        Swal.fire({
            title: 'Sucesso!',
            text: '<?php echo $this->session->flashdata('msg_success'); ?>',
            icon: 'success',
            confirmButtonText: 'Ok, fechar.'
        })
        <?php } ?>

        <?php if($this->session->flashdata('msg_info')==true){ ?>
        Swal.fire({
            title: 'Informação',
            text: '<?php echo $this->session->flashdata('msg_info'); ?>',
            icon: 'info',
            confirmButtonText: 'Ok, fechar.'
        })
        <?php } ?>

        <?php if($this->session->flashdata('msg_erro')==true){ ?>
        Swal.fire({
            title: 'Erro!',
            text: '<?php echo $this->session->flashdata('msg_erro'); ?>',
            icon: 'error',
            confirmButtonText: 'Ok, fechar.'
        })
        <?php } ?>

        // Transferência de Ferramenta - Preenchimento da Modal
        $(".transferir_ferramenta").click(function(){
            var id_obra = $(this).attr("data-id_obra");
            var id_ferramental_obra = $(this).attr("data-id_ferramental_obra");
            var id_ativo_externo = $(this).attr("data-id_ativo_externo");
            var quantidade = $(this).attr("data-quantidade");
            var item = $(this).attr("data-item");

            $("#item").val(item);
            $("#quantidade").val(quantidade);
            $("#id_ativo_externo").val(id_ativo_externo);
            $("#id_ferramental_obra").val(id_ferramental_obra);
            $("#id_obra").val(id_obra);
            $("#quantidade").attr({
                "max" : quantidade,
                "min" : '1'
            });
        });   

        $('.litros').mask("##0,0", {reverse: true});
        $('.cpf').mask('000.000.000-00');
        $('.valor').mask('0.000,00', {reverse: true});
        $(".telefone").mask("(99) 9999-9999");
        $(".celular").mask("(99) 9 9999-9999");


        $(".deletar_registro").click(function(){
            var id_registro = $(this).attr('data-id');
            var tabela = $(this).attr('data-tabela');
            var url_post = $(this).attr('data-href');

            Swal.fire({
                title: 'Você tem certeza?',
                text: "Esta operação não poderá ser revertida.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, deletar!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: url_post,
                        type: "post",
                        data: id_registro ,
                        success: function (response) {
                            Swal.fire(
                                'Deletado!',
                                'Registro removido com sucesso.',
                                'success'
                            )
                            window.location = "<?php echo base_url()?>" + tabela; 
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                           console.log(textStatus, errorThrown);
                        }
                    });
                }
            })
        });
        
        
        var confirmar_registro = function(event){
            let id_registro = $(this).attr('data-id');
            let tabela = $(this).attr('data-tabela');
            let url_post = $(this).attr('data-href');
            let acao = $(this).attr('data-acao') || 'Confirmar';
            let title = $(this).attr('data-title') || 'Você tem certeza?';
            let text = $(this).attr('data-text') || "Esta operação não poderá ser revertida.";
            let redirect = $(this).attr('data-redirect') ? true : false;
            let icon = $(this).attr('data-icon') || 'warning';
            let msg = $(this).attr('data-message') == 'false' ? false : true;

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, ' + acao + '!'
            }).then((result) => {
                if (result.value) {
                    if(url_post && tabela) {
                        $.ajax({
                                url: url_post,
                                type: "post",
                                data: id_registro,
                                success: function (response) {
                                    if (redirect == true) {
                                        window.location = tabela;
                                        return;
                                    }

                                    if(msg) {
                                        Swal.fire(
                                            'Enviado',
                                            'Dados Enviados com Sucesso!',
                                            'success'
                                        )
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    if (redirect == true) {
                                        window.location = tabela;
                                        return;
                                    }
                                    Swal.fire(
                                        'Erro',
                                        'Ops, Ocorreu um erro ao tentar Enviar os dados!',
                                        'success'
                                    )
                                }
                        });
                    } else { 
                        window.location = url_post;
                    }
                }
            })
        }

        $(".confirmar_registro").click(confirmar_registro);

        // Trabalhado items da tabela fipe.
        $("#tipo_veiculo").change(function(){
            var tipo_veiculo = $(this).val();
            if(tipo_veiculo=='0'){
                $("#id_marca").html("<option>...</option>");
            } else {    
                $.ajax({
                    url: '<?php echo base_url('ativo_veiculo/fipe_get_marca'); ?>',
                    type: "post",
                    data: {tipo_veiculo: tipo_veiculo} ,
                    success: function (response) {
                        $("#id_marca").html(response);
                        $("#id_marca").attr('title', 'Selecione uma Marca');                        

                        $("#id_modelo").attr('title', 'Selecione um Modelo'); 
                        $("#ano").attr('title', 'Selecione o Ano'); 
                        $("#veiculo").html("<option>...</option>"); 
                        $("#valor_fipe").val('');                 
                        $("#codigo_fipe").val('');                 
                        $("#fipe_mes_referencia").val(''); 
                        $('.selectpicker').selectpicker('refresh');  
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                       console.log(textStatus, errorThrown);
                    }
                });
            }
        });

        $("#id_marca").change(function(){
            var tipo_veiculo = $("#tipo_veiculo").val();
            var id_marca = $(this).val();
            if(id_marca=='0'){
                $("#id_modelo").html("<option>...</option>");
            } else {    
                $.ajax({
                    url: '<?php echo base_url('ativo_veiculo/fipe_get_modelos'); ?>',
                    type: "post",
                    data: {tipo_veiculo: tipo_veiculo, id_marca: id_marca} ,
                    success: function (response) {
                        $("#id_modelo").html(response);
                        $("#id_modelo").attr('title', 'Selecione um Modelo');                        
                        $('#id_modelo').selectpicker('refresh');                     
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                       console.log(textStatus, errorThrown);
                    }
                });
            }
        });

        $("#id_modelo").change(function(){
            var tipo_veiculo = $("#tipo_veiculo").val();
            var id_marca = $("#id_marca").val();
            var id_modelo = $(this).val();
            if(id_modelo=='0'){
                $("#ano").html("<option>...</option>");
            } else {    
                $.ajax({
                    url: '<?php echo base_url('ativo_veiculo/fipe_get_anos'); ?>',
                    type: "post",
                    data: {tipo_veiculo: tipo_veiculo, id_marca:id_marca, id_modelo: id_modelo} ,
                    success: function (response) {
                        $("#ano").html(response);
                        $("#ano").attr('title', 'Selecione o Ano');                        
                        $('#ano').selectpicker('refresh');                     
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                       console.log(textStatus, errorThrown);
                    }
                });
            }
        });

        $("#ano").change(function(){
            var tipo_veiculo = $("#tipo_veiculo").val();
            var id_marca = $("#id_marca").val();
            var id_modelo = $("#id_modelo").val();
            var ano = $(this).val();
            if(ano=='0'){
                $("#veiculo").html("<option>...</option>");
            } else {    
                $.ajax({
                    url: '<?php echo base_url('ativo_veiculo/fipe_get_veiculos'); ?>',
                    type: "post",
                    data: {tipo_veiculo: tipo_veiculo, id_marca:id_marca, id_modelo: id_modelo, ano:ano} ,
                    success: function (response) {
                        var json_resp = jQuery.parseJSON(response)
                        $("#veiculo").html("<option "+ json_resp.CodigoFipe +">" + json_resp.Modelo +"</option>"); 
                        $("#valor_fipe").val(json_resp.Valor);                 
                        $("#codigo_fipe").val(json_resp.CodigoFipe);                 
                        $("#fipe_mes_referencia").val(json_resp.MesReferencia);                 
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                       console.log(textStatus, errorThrown);
                    }
                });
            }
        });        

        $(document).ready(function () {
            maskMercosul('.placa');
        });
        
        function maskMercosul(selector) {
            var MercoSulMaskBehavior = function (val) {
                var myMask = 'AAA0A00';
                var mercosul = /([A-Za-z]{3}[0-9]{1}[A-Za-z]{1})/;
                var normal = /([A-Za-z]{3}[0-9]{2})/;
                var replaced = val.replace(/[^\w]/g, '');
                if (normal.exec(replaced)) {
                    myMask = 'AAA-0000';
                } else if (mercosul.exec(replaced)) {
                    myMask = 'AAA0A00';
                }
                return myMask;
            },
            mercoSulOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(MercoSulMaskBehavior.apply({}, arguments), options);
                }
            };
            $(function() {
                $(selector).bind('paste', function(e) {
                    $(this).unmask();
                });
                $(selector).bind('input', function(e) {
                    $(selector).mask(MercoSulMaskBehavior, mercoSulOptions);
                });
            });
        }   
    </script>

    <?php } ?>

    <script>
        window.OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: window.one_signal_appid,
                notifyButton: {
                    enable: true,
                },
            });

            if(user){
                OneSignal.setExternalUserId(user.id_usuario)
                OneSignal.sendTag("id_empresa", user.id_empresa)
                OneSignal.sendTag("id_obra", user.id_obra)
                OneSignal.sendTag("usuario", user.usuario)
                OneSignal.sendTag("situacao", user.situacao)
                OneSignal.sendTag("situacao_nome", user.situacao_nome == "0" ? 'Ativo' : 'Inativo')
                OneSignal.sendTag("nivel", user.nivel)
                OneSignal.sendTag("nivel_nome", user.nivel_nome)
                OneSignal.sendTag("empresa", user.empresa)
                OneSignal.sendTag("empresa_razao", user.empresa_razao)
                OneSignal.sendTag("codigo_obra", user.codigo_obra)
                OneSignal.sendTag("data_criacao", user.data_criacao)
            }
        });
    </script>

</body>

</html>
<!-- end document-->
