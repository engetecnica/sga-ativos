<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Engetecnica | <?php echo date("Y"); ?></title>

        
    <!-- Jquery JS-->
    <script src="<?php echo base_url('assets'); ?>/vendor/jquery-3.2.1.min.js"></script>

    <!-- Fontfaces CSS-->
    <link href="<?php echo base_url('assets'); ?>/css/font-face.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets'); ?>/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets'); ?>/vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets'); ?>/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="<?php echo base_url('assets'); ?>/vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="<?php echo base_url('assets'); ?>/vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets'); ?>/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets'); ?>/vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets'); ?>/vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets'); ?>/vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets'); ?>/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets'); ?>/vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="<?php echo base_url('assets'); ?>/css/theme.css" rel="stylesheet" media="all">

    <!-- Sweet Alert -->
    <link rel="stylesheet" href="<?php echo base_url('assets'); ?>/vendor/sweetalert/sweetalert2.min.css">   


    <link rel="stylesheet" href="<?php echo base_url('assets'); ?>/css/bootstrap-select.min.css">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets'); ?>/vendor/multi/multi.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets'); ?>/css/datatable.css" />
    <!--<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" />-->

    <!-- Vue.js -->
    <script src="<?php echo base_url('assets'); ?>/js/vue.js"></script>
    <script src="<?php echo base_url('assets'); ?>/js/v-mask.min.js"></script>
    <script>
        //VUE plugins
        Vue.use(VueMask.VueMaskPlugin);
    </script>

    <!-- Sweet Alert 
    <script src="<?php echo base_url('assets'); ?>/vendor/sweetalert/sweetalert2.min.js"></script>--> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- One Signal -->
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>

    <script> 
        var base_url = "<?php echo base_url('/'); ?>"
        var one_signal_appid = "<?php echo $this->config->item('one_signal_appid'); ?>";
        var user = JSON.parse('<?php echo json_encode($user); ?>') || null;
    </script>
</head>

<body class="animsition-disabled">
    <div class="page-wrapper">

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar" id="menu-sidebar">
            <button onClick="$('#menu-sidebar').hide('fast')" type="button" class="btn-close-mobile-menu">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>

            <div class="logo">
                <a href="#">
                    <img src="<?php echo base_url('assets'); ?>/images/icon/logo.png" alt="" />
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li>
                            <a href="<?php echo base_url(); ?>">
                                <i class="fas fa-chart-bar"></i>Entrada</a>
                        </li>

                        <?php foreach($modulos->modulo as $mod){ ?>
                        <li class="has-sub">
                            <a class="js-arrow open" href="<?php echo base_url($mod->rota); ?>">
                            <i class="<?php echo $mod->icone; ?>"></i><?php echo $mod->titulo; ?></a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <?php foreach($mod->submodulo as $sub){ ?>
                                <li><a href="<?php echo base_url($sub->rota); ?>"><i class="<?php echo $sub->icone; ?>"></i><?php echo $sub->titulo; ?></a></li>
                                <?php } ?>
                            </ul>
                        </li> 
                        <?php } ?> 
                        
                        <li>
                            <a href="<?php echo base_url('logout'); ?>">
                                <i class="fas fa-power-off"></i>Sair do Sistema</a>
                        </li>                        
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->


        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop" id="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <!--
                            <form class="form-header" action="" method="POST">
                                <input class="au-input au-input--xl" type="text" name="search" placeholder="Termos de Pesquisa" />
                                <button class="au-btn--submit" type="submit">
                                    <i class="zmdi zmdi-search"></i>
                                </button>
                            </form>
                            -->
                            <div></div>
                            <div class="header-button">

                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <?php if (isset($user->avatar)) {?>
                                                <img src="<?php echo base_url("assets/uploads/avatar/{$user->avatar}"); ?>" alt="Imagem do usuário" />
                                            <?php } else {?>
                                                <img src="<?php echo base_url('assets/images/icon/avatar-01.jpg'); ?>" alt="Imagem do usuário" />
                                            <?php }?>
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="#"><?php echo isset($user->nome) ? ucwords($user->nome) : $user->usuario; ?></a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <?php if (isset($user->razao_social)) { ?>
                                            <div class="info clearfix">
                                                <div class="image">
                                                    <a href="<?php echo base_url("usuario/editar/{$user->id_usuario}"); ?>">
                                                        <?php if (isset($user->avatar)) {?>
                                                            <img src="<?php echo base_url("assets/uploads/avatar/{$user->avatar}"); ?>" alt="Imagem do usuário" />
                                                        <?php } else {?>
                                                            <img src="<?php echo base_url('assets/images/icon/avatar-01.jpg'); ?>" alt="Imagem do usuário" />
                                                        <?php }?>
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h5 class="name m-b-10">
                                                        <a href="<?php echo base_url("usuario/editar/{$user->id_usuario}") ;?>">
                                                            <?php isset($user->nome) ? ucwords($user->nome) : $user->usuario; ?>
                                                        </a>
                                                    </h5>
                                                    <div class="name">
                                                       <small><b>Nível:</b><br><?php echo $user->nivel_nome; ?></small><br>
                                                       <small><b>Usuário:</b><br> <?php echo $user->usuario; ?></small><br>
                                                       <?php if (isset($user->email)) { ?>
                                                       <small><b>Email:</b><br><?php echo $user->email; ?></small><br>
                                                       <?php } ?>
                                                       <small><b>Empresa:</b><br><?php echo $user->razao_social; ?></small><br>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div class="account-dropdown__footer">
                                                <a href="<?php echo base_url('logout'); ?>">
                                                    <i class="zmdi zmdi-power"></i>Logout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button onClick="$('#menu-sidebar').show('fast')" type="button" class="btn-mobile-menu">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                            </div>

                            
                        </div>
                    </div>
                </div>
            </header>
            <!-- HEADER DESKTOP-->        