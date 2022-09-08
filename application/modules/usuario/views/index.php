<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                       
                        <a href="<?php echo base_url('usuario/adicionar'); ?>">
                        <button class="au-btn au-btn-icon au-btn--blue">
                        <i class="zmdi zmdi-plus"></i>Adicionar</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Usuários</h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <table 
                            class="table table-borderless table-striped table-earning" 
                            id="usuario_index"
                        ></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT -->
<!-- END PAGE CONTAINER -->
<script>
    function solicitar_confirmacao_email(event) {
        event.preventDefault();
        const id_usuario = $(event.currentTarget).data('id')

        const options = {
            title: "Enviar email de Confirmação",
            text: "Um email de verificação será enviado ao usuário para confirmar sua veracidade e acesso, Deseja continuar?",
            confirmButtonText: 'Sim, Enviar!'
        }

        show_comfirm_msg(options, (result) => {
            if(result.isConfirmed) {
                $.ajax({
                    method: "GET",
                    url: `${base_url}usuario/solicitar_confirmacao_email/${id_usuario}`,
                })
                .always((response) => {
                    if (response.success == true) {
                        Swal.fire({
                            title: 'Enviado!',
                            text: 'Email de confirmação enviado com Sucesso!',
                            icon: 'success',
                            confirmButtonText: 'Ok, Fechar.'
                        })
                        return
                    }

                    Swal.fire({
                        title: 'Erro ao enviar!',
                        text: 'Ocorreu um erro ao tentar enviar o Email de confirmação, Favor verificar endereço de email cadastrado.',
                        icon: 'error',
                        confirmButtonText: 'Ok, Fechar.'
                    }) 
                })
            }

        })

        // Swal.fire({
        //     title: "Enviar email de Confirmação",
        //     text: "Um email de verificação será enviado ao usuário para confirmar sua veracidade e acesso, Deseja continuar?",
        //     icon: "info",
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     confirmButtonText: 'Sim, Enviar!'
        // }).then((result) => {
        //     if(result.isConfirmed) {
        //         $.ajax({
        //             method: "GET",
        //             url: `${base_url}usuario/solicitar_confirmacao_email/${id_usuario}`,
        //         })
        //         .always((response) => {
        //             if (response.success == true) {
        //                 Swal.fire({
        //                     title: 'Enviado!',
        //                     text: 'Email de confirmação enviado com Sucesso!',
        //                     icon: 'success',
        //                     confirmButtonText: 'Ok, Fechar.'
        //                 })
        //                 return
        //             }

        //             Swal.fire({
        //                 title: 'Erro ao enviar!',
        //                 text: 'Ocorreu um erro ao tentar enviar o Email de confirmação, Favor verificar endereço de email cadastrado.',
        //                 icon: 'error',
        //                 confirmButtonText: 'Ok, Fechar.'
        //             }) 
        //         })
        //     }

        // });
    }

    function permit_notification_email(event){
        const input_permit = $(event.currentTarget);
        const id_usuario = input_permit?.data('id')

        if (id_usuario) {
            axios.get(`${base_url}usuario/permit_notification_email/${id_usuario}`)
        }
    }

    const data_table_columns = [
        {
            title: '#',
            name: 'id_usuario',
            render: function(value, type, row, settings){
                return row.avatar
            }
        },
        {
            title: 'ID',
            name: 'id_usuario',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.id_link
            }
        },
        { 
            title: 'Nome' ,
            name: 'nome',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.nome_link
            }
        },
        { 
            title: 'Usuário' ,
            name: 'usuario',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.usuario
            }
        },
        { 
            title: 'Empresa' ,
            name: 'ep.nome_fantasia',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.empresa
            }
        },
        { 
            title: 'Obra' ,
            name: 'ob.codigo_obra',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.obra
            }
        },
        { 
            title: 'Nível',
            name: 'un.nivel',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.nivel_html
            }
        },
        { 
            title: 'Email',
            name: 'email',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.email
            }
        },
        { 
            title: 'Email Confirmado',
            name: 'email_confirmado_em',
            sortable: true,
            searchable: true,
            render: function(value, type, row, settings){
                return row.email_confirmado_em_html
            }
        },
        { 
            title: 'Recebe Notificações Email',
            render: function(value, type, row, settings){
                return row.permit_notification_email_html
            }
        },
        { 
            title: 'Increveu-se para receber Notificações',
            render: function(value, type, row, settings){
                return row.permit_notification_push_html
            }
        },
        { 
            title: 'Situação',
            render: function(value, type, row, settings){
                return row.situacao_html
            }
        },
        { 
            title: 'Criação',
            name: 'usuario.data_criacao',
            sortable: true,
            searchable: true,   
            render(value, type, row, settings){
                return row.data_criacao
            }
        },
        { 
            title: 'Gerenciar' ,
            render(value, type, row, settings){
                return row.actions
            },
        },
    ]

    const options = {
        columns: data_table_columns,
        url: `usuario`,
        method: 'post',
        order: [1, 'asc'],
        ajaxOnSuccess: () => {
            $('.solicitar_confirmacao_email').click(e => solicitar_confirmacao_email(e))
            $('.permit_notification_email_switch').change(e => permit_notification_email(e))
        }
    }

    $(window).ready(() => loadDataTable('usuario_index', options))
    $(window).resize(() => loadDataTable('usuario_index', options))
</script>