<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Painel do Seminário</title>
    
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo asset_url(); ?>css/vendor/bootstrap.min.css" rel="stylesheet">
    
    <!-- Awesome Font -->
    <link href="<?php echo asset_url(); ?>css/vendor/font-awesome.min.css" rel="stylesheet">
    
    <!-- MetisMenu CSS -->
    <link href="<?php echo asset_url(); ?>css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet" />
    <!-- Datatable -->
        <link href="<?php echo asset_url(); ?>css/vendor/jquery.dataTables.min.css" rel="stylesheet">
        <link href="<?php echo asset_url(); ?>css/vendor/dataTables.bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo asset_url(); ?>css/sb-admin-2.css" rel="stylesheet" />
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>

<body>
    
    <div id="wrapper">

        <input style="display:none;" id="geral-base-url" value="<?php echo base_url(); ?>" />

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url('dashboard'); ?>" >Painel do Seminário de Pesquisa do CCSA / UFRN</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="menu-link">
                    <a href="<?php echo base_url('dashboard/myInformation'); ?>">
                        <i class="fa fa-edit fa-fw"></i> Meus dados 
                    </a>
                </li>
                <!-- /.menu-link -->
                <li class="menu-link">
                    <a href="<?php echo base_url('logout'); ?>">
                        <i class="fa fa-close fa-fw"></i> Sair 
                    </a>
                </li>
                <!-- /.menu-link -->
            </ul>
            <!-- /.navbar-top-links -->

            