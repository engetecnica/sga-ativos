    <!-- Bootstrap JS-->
    <script src="<?php echo base_url("assets/vendor/bootstrap-4.1/popper.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendor/bootstrap-4.1/bootstrap.min.js"); ?>"></script>
    <!-- Vendor JS       -->
    <script src="<?php echo base_url("assets/vendor/slick/slick.min.js"); ?>">
    </script>
    <script src="<?php echo base_url("assets/vendor/wow/wow.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendor/animsition/animsition.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/Chart.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendor/select2/select2.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/lodash.js"); ?>"></script>

    <!-- Main JS-->
    <script src="<?php echo base_url("assets/js/main.js"); ?>"></script>

    <script src="<?php echo base_url("assets/js/jquery.mask.js"); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url('assets'); ?>/vendor/circle-progress/circle-progress.min.js"></script>

    <script>
        $(document).ready(function() {
            var config_lista = {
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

            $('#lista').DataTable(config_lista);
            $('#lista1').DataTable(config_lista);
            $('#lista2').DataTable(config_lista);
            $('#lista3').DataTable(config_lista);
            $('#lista4').DataTable(config_lista);
            $('#lista5').DataTable(config_lista);
            $('#lista6').DataTable(config_lista);
            $('#lista7').DataTable(config_lista);
            $('#lista8').DataTable(config_lista);
            $('#lista9').DataTable(config_lista);
            $('#lista10').DataTable(config_lista);

            $('.paginate_button').click((e) => {
                setTimeout(() => {
                    let lista = document.querySelector(`#${$(e.target).attr('aria-controls')}`)
                    if (lista) {
                        lista.scrollIntoView({behavior: "smooth"})
                    }
                }, 100)
            })

            $('.has-sub>.list-unstyled>li').each((i, li) => {
                if ($(li).hasClass("active")) {
                    $($(li).parent()).siblings('.js-arrow').click()
                }
            })
            
            $(".select2").select2();
        }); 
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

        function loadMasks(){
            $('.litros').mask("####,## Litros", {reverse: true});
            $('.horas').mask("####### Horas", {reverse: true});
            $('.cpf').mask('000.000.000-00');
            $('.rg').mask('0.000.000-00');
            $('.cnpj').mask('00.000.000/0001-00');
            $('.valor').mask('000.000.000.000,00 R$', {reverse: true});
            $(".telefone").mask("(00) 0000-0000");
            $(".celular").mask("(00) 9 0000-0000");

            $('.veiculo_placa').mask('SSS-0A00',  {
                onKeyPress: function(placa, e, field, options) {
                    $(field)[0].value = placa.toUpperCase();
                }
            });

            $(".hora").mask("Hh:NZ:NZ", {
                translation:  {
                    'Z': {pattern: /[0-9]/, optional: true}, 
                    'N': {pattern: /[0-5]/, optional: true},
                    'H': {pattern: /[0-2]/, optional: false},
                    'h': {pattern: /[0-9]/, optional: true},
                },
                onChange: function (hora, e, field, options) {
                    let explode_hora = hora.split(':');

                    if (explode_hora.length == 1) {
                        let valor = parseInt(explode_hora[0]);
                        if (valor >= 0 && valor < 24) {
                            $(field)[0].value = explode_hora.join(":");
                            return
                        }
                    }

                    if (explode_hora.length == 2) {
                        let valor = parseInt(explode_hora[1]);
                        if(valor >= 0 && valor < 60) {
                            $(field)[0].value = explode_hora.join(":");
                            return
                        }
                    }

                    if (explode_hora.length == 3) {
                        let valor = parseInt(explode_hora[2]);
                        if(valor >= 0 && valor < 60) {
                            $(field)[0].value = explode_hora.join(":");
                            return
                        }
                    }
                    $(field)[0].value = ""
                }
            });
        }

        loadMasks()

        function formata_data_hora(data_hora){
            let formated =  moment(data_hora).format('DD/MM/YYYY H:mm:ss')
            return formated == "Invalid date" ? '-' : formated
        }

        function formata_data(data){
            let formated =  moment(data).format('DD/MM/YYYY')
            return formated == "Invalid date" ? '-' : formated
        }

        function formata_hora(hora){
            let formated =  moment(hora).format('H:mm:ss')
            return formated == "Invalid date" ? '-' : formated
        }


        async function show_comfirm_msg(options = {}, callback = (result) => {}){
            let default_options = {
                title: 'Você tem certeza?',
                text: "Esta operação não poderá ser revertida. Deseja continuar Continuar?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Continuar!'
            }
            return await Swal.fire({...default_options, ...options}).then(async (result) => {return await callback(result)})
        }

        function show_msg(title, text, type = 'success'){
            Swal.fire({
                title: title,
                text: text,
                icon: type,
                confirmButton: false,
            })
        }

        $(".deletar_registro").click(function(){
            let id_registro = $(this).attr('data-id');
            let tabela = $(this).attr('data-tabela');
            let url_post = $(this).attr('data-href');
            let redirect = $(this).attr('data-redirect') ? $(this).attr('data-redirect') : true;

            show_comfirm_msg({
                title: 'Você tem certeza?',
                text: "Esta operação não poderá ser revertida.",
                icon: 'warning',
                confirmButtonText: 'Sim, deletar!'
            }, (result) => {
                if (result.value) {
                    $.ajax({
                        url: url_post,
                        type: "post",
                        data: id_registro ,
                        success: function (response) {
                            Swal.fire(
                                'Excluido!',
                                'Registro Excluido com sucesso!',
                                'success'
                            )

                            if (redirect == true || redirect === "true") {
                                window.location = `${base_url}${tabela}`;
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                           console.log(textStatus, errorThrown);
                           Swal.fire(
                                'Erro',
                                'Ops, Ocorreu um erro ao tentar Remover os dados!',
                                'success'
                            )
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
            let beforeCallback = $(this).attr('before-callback') == '' ? () => { } : window[$(this).attr('before-callback')];
            let afterCallback = $(this).attr('after-callback') == '' ? () => { } : window[$(this).attr('after-callback')];

            if (typeof beforeCallback == 'function') {
                beforeCallback(event, $(this));
            }

            show_comfirm_msg({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, ' + acao + '!'
            }, (result) => {
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
                                    if (redirect == true || redirect === "true") {
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

                if (typeof afterCallback == 'function') {
                    afterCallback(event, $(this), result);
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
                    url: '<?php echo base_url('ativo_veiculo/fipe_get_marcas'); ?>',
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

        
        $(document).ready(function() {
            var confirm_submit = null;

            $('.confirm-submit').submit((event) => {
                if (confirm_submit == null) {
                    event.preventDefault();
                    let acao = $(event.target).attr('data-acao') || 'Confirmar';
                    let icon = $(event.target).attr('data-icon') || 'warning';
                    let title = $(event.target).attr('data-title') || 'Você tem certeza?';
                    let text =  $(event.target).attr('data-text') || "Esta operação não poderá ser revertida.";

                    show_comfirm_msg({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        canceButtonText: 'Cancelar',
                        confirmButtonText: 'Sim, ' + acao + '!'
                    }, (result) => {
                        if (result.isConfirmed) {
                            confirm_submit = result.isConfirmed
                            $(event.target).submit();
                        }
                    }).catch(() => {
                        confirm_submit = null
                    })
                }
            })

        })

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
