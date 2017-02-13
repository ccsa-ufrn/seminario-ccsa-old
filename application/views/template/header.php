<!DOCTYPE html>
<html lang="pt" ng-app="externalApp">
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        
        <title><?php ?>XXII Seminário de pesquisa do CCSA</title>

        <!-- Bootstrap Core CSS -->
        <link href="<?php echo asset_url(); ?>css/vendor/bootstrap.min.css" rel="stylesheet">
        <!-- Awesome Font -->
        <link href="<?php echo asset_url(); ?>css/vendor/font-awesome.min.css" rel="stylesheet">
        <!-- Normalize -->
        <link href="<?php echo asset_url(); ?>css/vendor/normalize.css" rel="stylesheet">
        <!-- Normalize -->
        <link href="<?php echo asset_url(); ?>css/vendor/sweetalert.css" rel="stylesheet">
        <!-- Datatable -->
        <link href="<?php echo asset_url(); ?>css/vendor/jquery.dataTables.min.css" rel="stylesheet">
        <link href="<?php echo asset_url(); ?>css/vendor/dataTables.bootstrap.min.css" rel="stylesheet">
        
        <!-- Custom CSS -->
        <link href="<?php echo asset_url(); ?>css/external.base.min.css" rel="stylesheet">
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        
    </head>
    <body>
        
        <input style="display: none;" id="base-url" value="<?php echo base_url(); ?>" >
        
        <div class="container">
			
            <div class="row">
                <div class="col-xs-12">
                    <nav class="upper"> 
                        <a class="nav-contact" href="<?php echo base_url(); ?>#contact"><i class="fa fa-envelope"></i></a>
                        <a href="http://www.ccsa.ufrn.br/portal" target="_blank"><i class="fa fa-wordpress"></i></a>
                        <a href="https://www.facebook.com/ccsa.ufrn" target="_blank"><i class="fa fa-facebook"></i></a>
                        <a 
                            <?php if($this->session->userdata('user_logged_in')): ?>
                                href="<?php echo base_url('dashboard'); ?>"
                            <?php else: ?>
                                href="" data-toggle="modal" data-target=".index-login-modal"
                            <?php endif; ?>
                         >
                            <i class="fa fa-sign-in"></i>
                        </a>
                    </nav>
                </div>
            </div>
            
			<section class="cover">
				<div class="row">
					<div class="col-xs-12">
						
							
							<h1>XXII SEMINÁRIO DE PESQUISA DO CCSA</h1>
							<h2>Desigualdades sociais e cidadania no Brasil: o debate atual</h2>
                            <h2><span>08 a 12 de maio de 2017</span></h2>
							<!-- <img src="<?php echo asset_url(); ?>img/logo.png" width="200px;"> -->
                            
                            <a class="btn-mobile-nav" href="javascript:void(0);"> <i class="fa fa-bars"></i>  MENU</a>
                            
                            <nav>
                                <a href="<?php echo base_url(); ?>">Inicio</a>
                                <a href="<?php echo base_url(); ?>#inscription">Inscrição</a>
                                <a>Atividades</a>
                                <a href="<?php echo base_url(); ?>schedule" >Programação</a>
                                <a class="signin" 
                                    <?php if($this->session->userdata('user_logged_in')): ?>
                                        href="<?php echo base_url('dashboard'); ?>"
                                    <?php else: ?>
                                        href="" data-toggle="modal" data-target=".index-login-modal"
                                    <?php endif; ?>
                                 >Entrar no Sistema</a> 
                                 <!--  <a class="signin">Sistema em Manutenção</a> -->
                            </nav>

							<hr>

					</div>				
				</div>
			</section>