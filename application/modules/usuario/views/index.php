<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
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
                                    <th>Situação</th>
                                    <th>Criação</th>
                                    <th class="text-right">Opções</th>
                                </tr>
                            </thead>
                       
                            <tbody>
                                <?php foreach($lista as $valor){ ?>
                                <?php if($this->session->userdata('logado')->id_usuario != $valor->id_usuario){ ?>
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
                                        <td><?php echo $valor->id_usuario; ?></td>
                                        <td>
                                            <a href="<?php echo base_url('usuario'); ?>/editar/<?php echo $valor->id_usuario; ?>">
                                            <?php echo isset($valor->nome) ? $valor->nome : '-'; ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url('usuario'); ?>/editar/<?php echo $valor->id_usuario; ?>">
                                            <?php echo $valor->usuario; ?>
                                            </a>
                                        </td>
                                        <td><?php echo isset($valor->nome_fantasia) ? $valor->nome_fantasia: (isset($valor->razao_socia) ? $valor->razao_social : ''); ?></td>
                                        <td><?php echo $valor->codigo_obra; ?></td>
                                        <td>
                                            <?php $nivel = $this->get_usuario_nivel($valor->nivel);?>
                                            <span class="badge badge-<?php echo $nivel['class']; ?>"><?php echo $nivel['texto']; ?></span>
                                        </td>
                                        <td>
                                            <?php $situacao = $this->get_situacao($valor->situacao, 'DESCARTADO', 'secondary');?>
                                            <span class="badge badge-<?php echo $situacao['class']; ?>"><?php echo $situacao['texto']; ?></span>
                                        </td>
                                        <td><?php echo $valor->data_criacao ? date('d/m/Y H:i:s', strtotime($valor->data_criacao)) : ''; ?></td>
                                        <td class="text-right">
                                            <?php if (!isset($valor->email_confirmado_em)) {?>
                                            <a href="#" class="solicitar_confirmacao_email" data-id_usuario="<?php echo $valor->id_usuario; ?>"><i class="fa fa-envelope"></i></a>
                                            <?php } ?>

                                            <a href="<?php echo base_url('usuario'); ?>/editar/<?php echo $valor->id_usuario; ?>"><i class="fas fa-edit"></i></a>
                                            <?php if($valor->id_usuario != $user->id_usuario && $user->nivel == 1){ ?>
                                                <a href="javascript:void(0)" data-href="<?php echo base_url('usuario'); ?>/deletar/<?php echo $valor->id_usuario; ?>" data-registro="<?php echo $valor->id_usuario;?>" data-tabela="usuario" class="deletar_registro"><i class="fas fa-trash"></i></a>
                                            <?php } ?>
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

<script>
    $('.solicitar_confirmacao_email').click(function(event) {
        event.preventDefault();
        let id_usuario = $(this).attr('data-id_usuario')
  
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
                    confirmButtonText: 'Ok, fechar.'
                })
                return
            }

            Swal.fire({
                title: 'Erro!',
                text: 'Ocorreu um erro ao tentar enviar o Email de confirmação!',
                icon: 'error',
                confirmButtonText: 'Ok, fechar.'
             }) 
        })
    });
</script>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
