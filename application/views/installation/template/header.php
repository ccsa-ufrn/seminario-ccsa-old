<!DOCTYPE html>

<html lang="pt" ng-app="installerApp">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Instalação do Sistema do Seminário</title>

        <!-- Bootstrap Core CSS -->
        <link href="<?php echo asset_url(); ?>css/vendor/bootstrap.min.css" rel="stylesheet">
        
        <!-- Awesome Font -->
        <link href="<?php echo asset_url(); ?>css/vendor/font-awesome.min.css" rel="stylesheet">
        
        <!-- Normalize -->
        <link href="<?php echo asset_url(); ?>css/vendor/normalize.css" rel="stylesheet">
        
        <!-- SweetAlert -->
        <link href="<?php echo asset_url(); ?>css/vendor/sweetalert.css" rel="stylesheet">

        <!-- JAVASCRIPT -->
        <!-- <script src="<?php echo asset_url(); ?>/js/base.install.min.js"></script> -->
        
        <!-- JAVASCRIPT DEVELOPMENT -->
        <script src="<?php echo asset_url(); ?>/js/vendor/angular.min.js"></script>
        <script src="<?php echo asset_url(); ?>/js/vendor/ui-bootstrap.min.js"></script>
        <script src="<?php echo asset_url(); ?>/js/vendor/html5shiv.min.js"></script>
        <script src="<?php echo asset_url(); ?>/js/vendor/jquery.min.js"></script>
        <script src="<?php echo asset_url(); ?>/js/vendor/respond.min.js"></script>
        
        <script src="<?php echo asset_url(); ?>/js/vendor/sweetalert.min.js"></script>
        <script src="<?php echo asset_url(); ?>/js/vendor/ngSweetAlert.min.js"></script>
        
        <script src="<?php echo asset_url(); ?>/js/custom/angular.app.js"></script>
        
    </head>
    <body>
        <input type="text" id="base-url" value="<?php echo base_url(); ?>" style="display:none;" >