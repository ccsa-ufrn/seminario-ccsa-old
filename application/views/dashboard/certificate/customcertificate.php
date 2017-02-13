<!DOCTYPE html>
<html lang="en" moznomarginboxes mozdisallowselectionprint>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Programação do Seminário</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo asset_url(); ?>css/bootstrap.min.css" rel="stylesheet" />

    <!-- Custom Fonts -->
    <link href="<?php echo asset_url(); ?>font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>

        @import url(http://fonts.googleapis.com/css?family=Nunito:400,700,300);

        *{
            font-family: 'Nunito', sans-serif;
        }


        @page 
        {
            size: auto;   /* auto is the initial value */
        }

        @media print{

        	@page {size: landscape}

        	h1{
        		-webkit-print-color-adjust: exact; 
        	}

        	#btn-print, .warning-cert{
        		display:none;
        	}

        }

    </style>

</head>

<body>

    <?php if(isset($validated) && $validated=="yes"): ?>
        <div class="warning-cert" style="margin-top:20px;width:80%;margin-left:10%;background-color:#66FF99;padding:20px; text-align:center;">
            Este certificado é <b>válido</b>. Abaixo é exibido o certificado com suas <b>informações originais</b>.
        </div>
    <?php endif; ?>

	<div style="overflow:hidden;width:27.7cm;margin-top:0.8cm;" >
		<h1 style="margin-top:0.5cm;margin-left:5%;width:90%;height:5px;border-top:5px solid #b5171f;"></h1>
	    <div style="display:block;margin-left:5%;width:90%;height:15cm;">
	    	<div style="float:left;width:30%;">
	    		<img height="530px" style="margin-top:0.3cm" src="<?php echo asset_url(); ?>/img/logo595.png" >
	    	</div>
	    	<div style="float:right;width:70%;">
	    		<h1 style="color:#b5171f !important; text-align:center;margin-top:0.0cm; margin-bottom: 2cm; font-size:46px;">CERTIFICADO</h1>
	    		
                <?php 

                    $qnt = mb_strlen(trim(strip_tags($text)),'utf8');
                    
                    $fontsize = "21px";

                    if($qnt>281 && $qnt<381){
                        $fontsize = "18px";
                    }else if($qnt>380 && $qnt<494){
                        $fontsize = "16px";
                    }else if($qnt>493 && $qnt<574){
                        $fontsize = "15px";
                    }else if($qnt>573){
                        $fontsize = "13px";
                    }

                ?>

                <p style="margin-left:1.5cm;font-family: Courier New,Courier,Lucida Sans Typewriter,Lucida Typewriter,monospace;;margin-right:1.5cm;height:5.5cm;text-align:justify;font-size:<?php echo $fontsize; ?>;margin-top:1cm;overflow:hidden;">
	    		    <?php echo $text; ?>
                </p>

                <?php if(isset($validated) && $validated=="yes"): ?>
                    <p style="text-align:right;font-size:21px;margin-right:1.5cm;margin-top:0.5cm;font-family: Courier New,Courier,Lucida Sans Typewriter,Lucida Typewriter,monospace;">Natal, (DATA*).</p>
                <?php else: ?>
	    		    <p style="text-align:right;font-size:21px;margin-right:1.5cm;margin-top:0.5cm;font-family: Courier New,Courier,Lucida Sans Typewriter,Lucida Typewriter,monospace;">Natal, <?php echo date("d"); ?> de <?php echo monthName(date("m")); ?> de <?php echo date("Y"); ?>.</p>
	    		<?php endif; ?>

                <div style="margin-top:1cm;text-align:center;">
	    			<!--<img height="35px" src="<?php echo asset_url(); ?>/signature.jpg" >-->
	    		</div>
	    		<p style="text-align:center;font-size:21px;font-family: Courier New,Courier,Lucida Sans Typewriter,Lucida Typewriter,monospace;">Maria Arlete Duarte de Araújo</p>
	    		<p style="text-align:center;font-size:21px;margin-top:-0.3cm;font-family: Courier New,Courier,Lucida Sans Typewriter,Lucida Typewriter,monospace;">Coordenadora Geral</p>
	    	</div>
	    </div>
	    <h1 style="margin-top:0.5cm;margin-left:5%;width:90%;border-bottom:5px solid #b5171f;font-size:10px;">Para verificar a validade do certificado entre em <b>www.seminario2016.ccsa.ufrn.br/validarcertificado</b> e digite o seguinte código: <b><?php echo $certgen; ?></b></h1>
	</div>
	<div style="text-align:center;width:27.7cm;">
		<a id="btn-print" style="cursor:pointer;font-size:22px;font-weight:bold;" onclick="window.print();">IMPRIMIR CERTIFICADO</a>
	</div>
	
</body>
</html>