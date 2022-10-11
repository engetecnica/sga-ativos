<!-- main-custom.js -->
<script src="<?php echo base_url('assets'); ?>/js/main-custom.js"></script>
<script src="https://cdn.tiny.cloud/1/hkq7y97zk2i8cowh0mu8x2zm2v2sapzl4i9otqk6w6pbiopd/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<?php if ($this->session->userdata('logado') == true) { ?>

<script>
  $('#veiculo_observacoes').tinymce({
    height: 500,
    plugins: "powerpaste emoticons hr image link lists charmap table",
/* other settings... */ });
</script>

<script>
    <?php if ($this->session->flashdata('msg_success') == true) { ?>
        Swal.fire({
            title: 'Sucesso!',
            text: '<?php echo $this->session->flashdata('msg_success'); ?>',
            icon: 'success',
            confirmButtonText: 'Ok, fechar.'
        })
    <?php } ?>

    <?php if ($this->session->flashdata('msg_info') == true) { ?>
        Swal.fire({
            title: 'Informação',
            text: '<?php echo $this->session->flashdata('msg_info'); ?>',
            icon: 'info',
            confirmButtonText: 'Ok, fechar.'
        })
    <?php } ?>

    <?php if ($this->session->flashdata('msg_erro') == true) { ?>
        Swal.fire({
            title: 'Erro!',
            text: '<?php echo $this->session->flashdata('msg_erro'); ?>',
            icon: 'error',
            confirmButtonText: 'Ok, fechar.'
        })
    <?php } ?>

    // Renovação de items
    var confirmar_renovacao = function(event) {
        let id_registro = $(this).attr('data-id');
        let tabela = $(this).attr('data-tabela');
        var url_post = $(this).attr('url-post');
        let acao = $(this).attr('data-acao') || 'Confirmar';
        let title = $(this).attr('data-title') || 'Você tem certeza?';
        let text = $(this).attr('data-text') || "Esta operação não poderá ser revertida.";
        let redirect = $(this).attr('data-redirect') ? true : false;
        let icon = $(this).attr('data-icon') || 'warning';
        let msg = $(this).attr('data-message') == 'false' ? false : true;
        let beforeCallback = $(this).attr('before-callback') == '' ? () => {} : window[$(this).attr('before-callback')];
        let afterCallback = $(this).attr('after-callback') == '' ? () => {} : window[$(this).attr('after-callback')];

        if (typeof beforeCallback == 'function') {
            beforeCallback(event, $(this));
        }

        show_comfirm_msg({
            title: title,
            text: text,
            html: text + "<br><input type='datetime-local' name='data_renovacao' id='data_renovacao' style='border: solid 1px #CCC; padding: 10px; border-radius: 10px;' value='<?php echo date("Y-m-d\TH:i", strtotime("+1 day")); ?>'>",
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: `Sim, ${acao}!`,
            preConfirm: function() {
                in1 = $('#data_renovacao').val();
                url_post = `${url_post}/${in1}`;
            },

        }, (result) => {
            if (result.value) {
                if (url_post && tabela) {
                    $.ajax({
                        url: url_post,
                        type: "post",
                        data: {
                            id_registro,
                            in1
                        },
                        success: function(response) {
                            if (redirect == true) {
                                window.location = tabela;
                                return;
                            }

                            if (msg) {
                                Swal.fire(
                                    'Enviado',
                                    'Retirada renovada com sucesso!',
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

    $(".confirmar_renovacao").click(confirmar_renovacao);


    // Trabalhado items da tabela fipe.
    $("#tipo_veiculo").on('change', function() {
        var tipo_veiculo = $(this).val();
        if (tipo_veiculo == '0') {
            $("#id_marca").html("<option>...</option>");
        } else {
            $.ajax({
                url: '<?php echo base_url('ativo_veiculo/fipe_get_marcas'); ?>',
                type: "post",
                data: {
                    tipo_veiculo: tipo_veiculo
                },
                success: function(response) {
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
                    console.error(textStatus, errorThrown);
                }
            });
        }
    });

    $("#id_marca").change(function() {
        var tipo_veiculo = $("#tipo_veiculo").val();
        var id_marca = $(this).val();
        if (id_marca == '0') {
            $("#id_modelo").html("<option>...</option>");
        } else {
            $.ajax({
                url: '<?php echo base_url('ativo_veiculo/fipe_get_modelos'); ?>',
                type: "post",
                data: {
                    tipo_veiculo: tipo_veiculo,
                    id_marca: id_marca
                },
                success: function(response) {
                    $("#id_modelo").html(response);
                    $("#id_modelo").attr('title', 'Selecione um Modelo');
                    $('#id_modelo').selectpicker('refresh');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus, errorThrown);
                }
            });
        }
    });

    $("#id_modelo").change(function() {
        var tipo_veiculo = $("#tipo_veiculo").val();
        var id_marca = $("#id_marca").val();
        var id_modelo = $(this).val();
        if (id_modelo == '0') {
            $("#ano").html("<option>...</option>");
        } else {
            $.ajax({
                url: '<?php echo base_url('ativo_veiculo/fipe_get_anos'); ?>',
                type: "post",
                data: {
                    tipo_veiculo: tipo_veiculo,
                    id_marca: id_marca,
                    id_modelo: id_modelo
                },
                success: function(response) {
                    $("#ano").html(response);
                    $("#ano").attr('title', 'Selecione o Ano');
                    $('#ano').selectpicker('refresh');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus, errorThrown);
                }
            });
        }
    });

    $("#ano").change(function() {
        var tipo_veiculo = $("#tipo_veiculo").val();
        var id_marca = $("#id_marca").val();
        var id_modelo = $("#id_modelo").val();
        var ano = $(this).val();
        if (ano == '0') {
            $("#veiculo").html("<option>...</option>");
        } else {
            $.ajax({
                url: '<?php echo base_url('ativo_veiculo/fipe_get_veiculos'); ?>',
                type: "post",
                data: {
                    tipo_veiculo: tipo_veiculo,
                    id_marca: id_marca,
                    id_modelo: id_modelo,
                    ano: ano
                },
                success: function(response) {
                    var json_resp = jQuery.parseJSON(response)
                    $("#veiculo").html("<option " + json_resp.CodigoFipe + ">" + json_resp.Modelo + "</option>");
                    $("#valor_fipe").val(json_resp.Valor);
                    $("#codigo_fipe").val(json_resp.CodigoFipe);
                    $("#fipe_mes_referencia").val(json_resp.MesReferencia);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus, errorThrown);
                }
            });
        }
    });

     /* Histórico de Veículos nas Obras */
    $(document).on("click", ".historico-veiculo", function() {
        let id_ativo_veiculo = $(this).attr('data-id_ativo_veiculo');
        let url = "<?php echo base_url('ativo_veiculo/historico/'); ?>" + id_ativo_veiculo;
        $("#historico-veiculo").modal("show");
        $(".historico-veiculo-lista").load(url);
    });

    function HistoricoExcluir(id_veiculo_obra, id_ativo_veiculo) {
        if (id_veiculo_obra) {
            Swal.fire({
                title: 'Atenção!',
                text: 'Você tem certeza que deseja excluir este histórico?',
                icon: 'info',
                confirmButtonText: 'Sim, Excluir.',
                showCancelButton: true,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?php echo base_url('ativo_veiculo/excluir_historico/'); ?>' + id_veiculo_obra,
                        type: 'GET',
                        success: function(res) {

                            if (res == 'ok') {
                                Swal.fire('Excluído!', '', 'success')
                                let url = "<?php echo base_url('ativo_veiculo/historico/'); ?>" + id_ativo_veiculo;
                                $(".historico-veiculo-lista").load(url);
                            } else {
                                Swal.fire({
                                    title: 'Erro!',
                                    text: 'Não foi possível realizar essa operação.',
                                    icon: 'error',
                                    confirmButtonText: 'Ok, fechar.'
                                })
                            }

                        }
                    });
                }
            })
        } else {
            Swal.fire({
                title: 'Erro!',
                text: 'Não foi possível realizar essa operação.',
                icon: 'error',
                confirmButtonText: 'Ok, fechar.'
            })
        }
    }

    /* Filter - Ativo Externo */
    $(".select-item").on('change', function(e) {
        let id_ativo_externo = $("#id_ativo_externo")?.val();
        let calibracao = $("#calibracao").val();
        let url = `?filter_items=item/${id_ativo_externo}/calibracao/${calibracao}`;
        window.location = '<?php echo base_url('ativo_externo'); ?>'+url
    })

    /*
    $(window).ready(function() {
        if (app_env === 'production') {
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/service-worker.js', {scope: '/'})
                .then(function(registration) {
                  if (registration.installing) console.log('ServiceWorker installing...')
                })
                .catch(function(error) {
                  console.error('Service worker registration failed:', error)
                })
            } else {
                console.error('Service workers are not supported.')
            }
        }
    })
    */
</script>

<?php } ?>
