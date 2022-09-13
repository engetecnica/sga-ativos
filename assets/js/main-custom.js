var estado = false;
function marcarDesmarcarTodos(tag) {
    var checkboxes = document.querySelectorAll("input[class=" + tag + "]");
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = !estado;
    }
    estado = !estado;
}

/* Adjust Anchor */
var adjustAnchor = function (e) {
    e.preventDefault()
    var hash = $(window.location.hash)
    var header = $('#header-desktop')
    if (hash.length > 0 && header.length > 0) {
        window.scrollTo(0, hash.offset().top - (header.height() + 10));
    }
};

function loadMasks() {
    $('.litros').mask("####,## Litros", { reverse: true });
    $('.horas').mask("####### Horas", { reverse: true });
    $('.km').mask("########## KM", { reverse: true });
    $('.cpf').mask('000.000.000-00');
    $('.rg').mask('0.000.000-00');
    $('.cnpj').mask('00.000.000/0001-00');
    $('.telefone').mask('(00) 0000-0000');
    $('.celular').mask('(00) 9 0000-0000');
    $('.valor').mask('000.000.000.000,00 R$', {reverse: true});

    let placaCallback = function (placa, e, field, options) { $(field)[0].value = placa.toUpperCase().trim() }
    $('.veiculo_placa').mask('SSS-0A00', {
        onKeyPress: placaCallback,
        onBlur: placaCallback,
        onInput: placaCallback,
    });

    let idInternoCallback = function (id_interno, e, field, options) { $(field)[0].value = id_interno.toUpperCase().slice(0, 12).trim() }
    $('.id_interno_maquina').mask('AAA-AAA-####', {
        onKeyPress: idInternoCallback,
        onBlur: idInternoCallback,
        onInput: idInternoCallback,
    });

    $(".hora").mask("Hh:NZ:NZ", {
        translation: {
            'Z': { pattern: /[0-9]/, optional: true },
            'N': { pattern: /[0-5]/, optional: true },
            'H': { pattern: /[0-2]/, optional: false },
            'h': { pattern: /[0-9]/, optional: true },
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
                if (valor >= 0 && valor < 60) {
                    $(field)[0].value = explode_hora.join(":");
                    return
                }
            }
            if (explode_hora.length == 3) {
                let valor = parseInt(explode_hora[2]);
                if (valor >= 0 && valor < 60) {
                    $(field)[0].value = explode_hora.join(":");
                    return
                }
            }
            $(field)[0].value = ""
        }
    });
}

function reloadMasks() {
    setTimeout(() => { loadMasks() }, 100)
}

function formata_data_hora(data_hora) {
    let formated = moment(data_hora).format('DD/MM/YYYY H:mm:ss')
    return formated == "Invalid date" ? '-' : formated
}

function formata_data(data) {
    let formated = moment(data).format('DD/MM/YYYY')
    return formated == "Invalid date" ? '-' : formated
}

function formata_hora(hora) {
    let formated = moment(hora).format('H:mm:ss')
    return formated == "Invalid date" ? '-' : formated
}

async function show_comfirm_msg(options = {}, callback = (result) => { }) {
    let default_options = {
        title: 'Você tem certeza?',
        text: "Esta operação não poderá ser revertida. Deseja continuar Continuar?",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, Continuar!'
    }
    return await Swal.fire({ ...default_options, ...options }).then(async (result) => { return await callback(result) })
}

function show_msg(title, text, type = 'success') {
    Swal.fire({
        title: title,
        text: text,
        icon: type,
        confirmButton: false,
    })
}

function remove_acentos(str) {
    let com_acento = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝŔÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿŕ";
    let sem_acento = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYRsBaaaaaaaceeeeiiiionoooooouuuuybyr";
    novastr="";
    for(i=0; i<str.length; i++) {
        troca=false;
        for (a=0; a<com_acento.length; a++) {
            if (str.substr(i,1)==com_acento.substr(a,1)) {
                novastr+=sem_acento.substr(a,1);
                troca=true;
                break;
            }
        }
        if (troca==false) {
            novastr+=str.substr(i,1);
        }
    }
    return novastr;
  }       
  
function refresh_page(segment = null){
    window.location.href = window.location.href
    if (segment) window.location.href = window.location.href + `#${segment}`
}
  

const mainCustomReady = function () {
    $(".confirmar_registro").click(function (event) {
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
                if (url_post && tabela) {
                    $.ajax({
                        url: url_post,
                        type: "post",
                        data: id_registro,
                        success: function (response) {
                            if (redirect == true) {
                                window.location = tabela;
                                return;
                            }

                            if (msg) {
                                Swal.fire(
                                    'Enviado',
                                    'Dados Enviados com Sucesso!',
                                    'success'
                                )
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
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
    });

    $(".deletar_registro").click(function () {
        console.log('click .deletar_registro')
        let id_registro = $(this).attr('data-id');
        let tabela = $(this).attr('data-tabela');
        let url_method = $(this).attr('data-method') || 'delete';
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
                    type: url_method,
                    data: id_registro,
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
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error(textStatus, errorThrown);
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

    $(window).on('resize', (event) => {
        var sidebar = $('#menu-sidebar');
        if ($(window).width() > 991 && (sidebar.length == 1 && !sidebar.is(':visible'))) {
            sidebar.show('fast')
        }
    });

    /* Set init functions */
    $('.select-search').select2();

    /* Itens da Requisição múltipla */
    $(".listagem").append($("#item_lista").html());

    $(document).on("click", ".add_line", function () {
        $(".listagem").append($("#item_lista").html());
        $(".listagem .item-lista").last().addClass('id_ativo_externo_grupo');
    })
    $(document).on("click", ".remove_line", function () {
        if ($(".remove_line").length >= 1) {
            $(this).closest(".item-lista").remove();
        }
        if ($(".remove_line").length == 0) {
            $(".listagem").append($("#item_lista").html());
            $(".listagem .item-lista").last();
        }
    })

    var confirm_submit = null;
    $('.confirm-submit').submit((event) => {
        if (confirm_submit == null) {
            event.preventDefault();
            let acao = $(event.target).attr('data-acao') || 'Confirmar';
            let icon = $(event.target).attr('data-icon') || 'warning';
            let title = $(event.target).attr('data-title') || 'Você tem certeza?';
            let text = $(event.target).attr('data-text') || "Esta operação não poderá ser revertida.";
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

    $(window).on('hashchange load', function (e) {
        setTimeout(() => {
            adjustAnchor(e);
        }, 10);
    });

    loadMasks()
}


$(document).ready(() => mainCustomReady())
