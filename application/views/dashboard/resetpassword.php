
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Entrar no painel do usuário - Recuperar senha </title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo asset_url(); ?>css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <style href="signin.css" rel="stylesheet">

      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #eee;
      }

      .form-signin {
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
      }

      .form-signin .form-signin-heading {
        margin-bottom: 10px;
        font-weight:bold;
      }

      .form-signin .form-control {
        position: relative;
        height: auto;
        -webkit-box-sizing: border-box;
           -moz-box-sizing: border-box;
                box-sizing: border-box;
        padding: 10px;
        font-size: 16px;
      }
      .form-signin .form-control:focus {
        z-index: 2;
      }
      .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
      }
      .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
      }

    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <div class="container">

      <?php echo form_open(base_url('formError'), array('id' => 'dashboardResetPasswordForm','class' => 'form-signin', 'novalidate' => '')); ?>
          <input style="display:none;" class="formAction" value="<?php echo base_url('doResetPassword');?>" />
          <div class="row">

              <div class="col-lg-12 text-center">
                <h1 class="form-signin-heading">Esqueci minha senha</h1>
              </div>

              <div class="col-md-12">
                  <div class="form-group">
                      <input type="email" class="form-control" placeholder="Seu email"name="email" required data-validation-required-message="Por favor, insira seu email." autofocus>
                      <p class="help-block text-danger"></p>
                  </div>
              </div>

              <div class="col-lg-12 text-center success-container">
                  <div class="success"></div>
                  <button type="submit" class="btn btn-lg btn-success">Recuperar senha</button>
              </div>

              <div class="col-lg-12 text-center resetp" style="margin-top:10px;">
                <p class="resetp"> <a href="<?php echo base_url('/dashboard/login')?>">Voltar</a></p>
              </div>

          </div>
      </form>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script type="text/javascript">
      /*!
       * IE10 viewport hack for Surface/desktop Windows 8 bug
       * Copyright 2014 Twitter, Inc.
       * Licensed under the Creative Commons Attribution 3.0 Unported License. For
       * details, see http://creativecommons.org/licenses/by/3.0/.
       */

      // See the Getting Started docs for more information:
      // http://getbootstrap.com/getting-started/#support-ie10-width

      (function () {
        'use strict';
        if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
          var msViewportStyle = document.createElement('style')
          msViewportStyle.appendChild(
            document.createTextNode(
              '@-ms-viewport{width:auto!important}'
            )
          )
          document.querySelector('head').appendChild(msViewportStyle)
        }
      })();

    </script>

    <!-- jQuery -->
    <script src="<?php echo asset_url(); ?>js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo asset_url(); ?>js/bootstrap.min.js"></script>

    <!-- Form validation -->
    <script src="<?php echo asset_url(); ?>js/jqBootstrapValidation.js"></script>
    <script src="<?php echo asset_url(); ?>js/validation.js"></script>



  </body>
</html>