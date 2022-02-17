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
                        <table class="table table-borderless table-striped table-earning" id="lista">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th width="7%">Id</th>
                                    <th>Nome</th>
                                    <th>Usuário</th>
                                    <th>Empresa</th>
                                    <th>Obra</th>
                                    <th>Nível</th>
                                    <th>Email</th>
                                    <th>Email Confirmado</th>
                                    <th>Recebe Notificações Email</th>
                                    <th>Increveu-se para receber Notificações</th>
                                    <th>Situação</th>
                                    <th>Criação</th>
                                    <th class="text-right">Gerenciar</th>
                                </tr>
                            </thead>
                       
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <?php  if($this->session->userdata('logado')->id_usuario != $valor->id_usuario){ ?>
                                    <tr id="<?php echo $valor->id_usuario; ?>">
                                        <td>
                                            <a class="avatar" href="<?php echo base_url("usuario/editar/{$valor->id_usuario}"); ?>">
                                                <?php if (isset($valor->avatar)) {?>
                                                    <img src="<?php echo base_url("assets/uploads/avatar/{$valor->avatar}"); ?>" alt="Imagem do usuário" />
                                                <?php } else {?>
                                                    <img src="<?php echo base_url('assets/images/icon/avatar-01.jpg'); ?>" alt="Imagem do usuário" />
                                                <?php }?>
                                            </a>
                                        </td>
                                        <td>
                                        <a href="<?php echo base_url('usuario'); ?>/editar/<?php echo $valor->id_usuario; ?>">
                                            <?php echo $valor->id_usuario; ?>
                                        </a>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url('usuario'); ?>/editar/<?php echo $valor->id_usuario; ?>">
                                            <?php echo isset($valor->nome) ? $valor->nome : '-'; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $valor->usuario; ?></td>
                                        <td><?php echo isset($valor->nome_fantasia) ? $valor->nome_fantasia: (isset($valor->razao_socia) ? $valor->razao_social : ''); ?></td>
                                        <td><?php echo $valor->codigo_obra ?: '-'; ?></td>
                                        <td>
                                            <?php $nivel = $this->get_usuario_nivel($valor->nivel);?>
                                            <span class="badge badge-<?php echo $nivel['class']; ?>"><?php echo $nivel['texto']; ?></span>
                                        </td>
                                        <td><?php echo $valor->email ?: '-'; ?></td>
                                        <td>
                                            <?php 
                                                $text = isset($valor->email_confirmado_em) ?  'Sim' : 'Não';
                                                $class = isset($valor->email_confirmado_em) ?  'success' : 'danger';
                                            ?>
                                            <span class="badge badge-<?php echo $class;?>"><?php echo $text;?></span>
                                        </td>
                               
                                        <?php $no_edit_notification_email = !((isset($valor->email) && isset($valor->email_confirmado_em)) && $valor->nivel == 1); ?>
                                        <td>
                                            <a class="form-check" onclick="permit_notification_email('<?php echo $valor->id_usuario; ?>')" >
                                                <input class="form-check-input" type="checkbox" role="switch" id="<?php echo $valor->id_usuario; ?>_permit_notification_email_switch" 
                                                    <?php 
                                                        echo isset($valor->permit_notification_email) && $valor->permit_notification_email == 1 ? 'checked' : ''; 
                                                        echo $no_edit_notification_email ? "readonly disabled" : "";
                                                    ?>
                                                >
                                            </a>
                                        </td>

                                        <td>
                                            <?php 
                                                $text = isset($valor->permit_notification_push) && $valor->permit_notification_push == '1' ?  'Sim' : 'Não';
                                                $class = isset($valor->permit_notification_push) && $valor->permit_notification_push == '1' ?  'success' : 'danger';
                                            ?>
                                            <span class="badge badge-<?php echo $class;?>"><?php echo $text;?></span>
                                        </td>
                        
                                        <td>
                                            <?php $situacao = $this->get_situacao($valor->situacao, 'DESCARTADO', 'secondary');?>
                                            <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                        </td>
                                        <td><?php echo $valor->data_criacao ? date('d/m/Y H:i:s', strtotime($valor->data_criacao)) : ''; ?></td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button 
                                                    class="btn btn-secondary btn-sm dropdown-toggle" 
                                                    type="button"
                                                    data-toggle="dropdown" 
                                                    aria-haspopup="true" 
                                                    aria-expanded="false"
                                                >
                                                    Gerenciar
                                                </button>
                                                <div class="dropdown-menu">
                                                    <?php if (isset($valor->email) && !isset($valor->email_confirmado_em)) {?>
                                                    <a  
                                                        href="#" class="dropdown-item  solicitar_confirmacao_email" 
                                                        data-redirect="false"
                                                        data-id_usuario="<?php echo $valor->id_usuario; ?>"
                                                    >
                                                        <i class="fa fa-envelope"></i>&nbsp; Enviar Email de Verificação
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <?php } ?>

                                                    <a class="dropdown-item " href="<?php echo base_url('usuario'); ?>/editar/<?php echo $valor->id_usuario; ?>"><i class="fas fa-edit"></i> Editar</a>
                                                    <?php if($valor->id_usuario != $user->id_usuario && $user->nivel == 1){ ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a 
                                                        href="javascript:void(0)" 
                                                        data-href="<?php echo base_url('usuario'); ?>/deletar/<?php echo $valor->id_usuario; ?>" 
                                                        data-registro="<?php echo $valor->id_usuario;?>" data-tabela="usuario" 
                                                        class="dropdown-item  deletar_registro"
                                                    >
                                                        <i class="fas fa-trash"></i> Excluir
                                                    </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                               <?php } ?>
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
<!-- END MAIN CONTENT -->
<!-- END PAGE CONTAINER -->

<script>
    $('.solicitar_confirmacao_email').click(function(event) {
        event.preventDefault();
        let id_usuario = $(this).attr('data-id_usuario')

        Swal.fire({
            title: "Enviar email de Confirmação",
            text: "Um email de verificação será enviado ao usuário para confirmar sua veracidade e acesso, Deseja continuar?",
            icon: "info",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, Enviar!'
        }).then((result) => {
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

        });
    });

    function permit_notification_email(id_usuario){
        let input_permit = $(`#${id_usuario}_permit_notification_email_switch`);
        permit = input_permit.attr('checked') == 'checked' ? 2 : 1
        axios.get(`${base_url}usuario/permit_notification_email/${id_usuario}/${permit}`)
        .then((response) => {if(response.data.success) input_permit.attr('checked', !input_permit.attr('checked') !== 'checked')})
    }
</script>