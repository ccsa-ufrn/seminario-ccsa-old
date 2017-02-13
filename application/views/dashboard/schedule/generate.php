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

        div.print{
            display:none;
        }

        @media print {

            div.print{
                display:block;
            }


            div.no-print{
                display:none;
            }

            footer.force-break-page{
                page-break-after: always;
            }

        }

    </style>

</head>

<body>

    <div class="no-print">
        <h1 style="text-align:center;font-weight:bold;">PROGRAMAÇÃO DO SEMINÁRIO</h1>
        <p style="text-align:center;">Imprima utilizando o <b>Google Chrome</b> e desmarcando a opção "Cabeçalhos e Rodapés", em "Opções".<p>
        <p style="text-align:center;">Ou utilize o <b>Firefox</b>.<p>
        <p style="text-align:center;">
            <a style="cursor:pointer;" onclick="print();" class="btn btn-primary btn-lg">Imprimir Programação</a>    
        </p>
    </div>

    <div class="print">

        <!-- FIRST PAGE - COVER -->
        <section class="text-center">
            
            <h1 style="font-size:18px; font-weight:700;">UNIVERSIDADE FERDERAL DO RIO GRANDE DO NORTE</h1>
            <h1 style="font-size:18px; font-weight:400;">CENTRO DE CIÊNCIAS SOCIAS APLICADAS</h2>

            <h1 style="margin-top:10cm; font-size:26px; font-weight:700;">PROGRAMAÇÃO</h1>

            <h1 style="margin-top:-5px; font-size:18px; font-weight:700;">XX SERMINÁRIO DE PESQUISA DO CCSA</h1>
            <h2 style="margin-top:-5px;font-size:18px; font-weight:400;"  >4 a 8 de Maio de 2015</h2>

            <h2 style="margin-top:11cm;font-size:18px; font-weight:400;"  >NATAL/RN, 2015</h2>

        </section>

        <footer class="force-break-page"></footer>

        <section class="section-others-activities">

            <h1 style="margin-bottom:1.5cm;font-size:16px; font-weight:700;" class="text-center">PÔSTERES</h1>
            
            <?php $i=0; $date=""; $shift=""; foreach($recordsPO as $rpo): ?>

                <!-- DIV 1S --> <div style="page-break-inside: avoid; font-size:10px; <?php if($i++!=0): ?> padding-top:5px; <?php endif; ?>">
                    
                    <?php $kkk = 0; if($date!=$rpo->date): ?>
                        <?php $date = $rpo->date; ?>
                        <?php $shift = ""; $kkk = 0; ?>
                        <h1 style="<?php if($i!=0): ?> padding-top: 0.50cm; <?php endif; ?> font-size:14px;font-weight:700;text-transform:uppercase;"><?php echo date("d/m/Y", strtotime($rpo->date));  ?></h1>
                    
                        <?php if($shift!=$rpo->shift): ?>
                            <?php $shift = $rpo->shift; ?>
                            <h1 style="font-size:14px;font-weight:700;text-transform:uppercase;"><?php echo $rpo->shift;  ?></h1>
                        <?php endif; ?>

                    <?php else: ?>

                        <?php if($shift!=$rpo->shift): ?>
                            <?php $shift = $rpo->shift; ?>
                            <h1 style="<?php if($kkk++!=0): ?> padding-top: 0.50cm; <?php endif; ?> font-size:14px;font-weight:700;text-transform:uppercase;"><?php echo $rpo->shift;  ?></h1>
                        <?php endif; ?>

                    <?php endif; ?>

                    

                    <h2 style="font-size:12px;font-weight:700;text-transform:uppercase;padding-left:0.75cm;"><?php echo $rpo->thematicgroup->name; ?> - <?php echo $rpo->local; ?>, <?php echo $rpo->starthour; ?> - <?php echo $rpo->endhour; ?></h2>
                    <?php $ij=0; foreach ($rpo->ownPosterList as $p): ?>

                        <div style=" <?php if(count($rpo->ownPosterList)-1==$ij): ?> margin-bottom:-0.50cm; <?php endif; ?> page-break-inside: avoid;padding-left:0.75cm;padding-right:0.75cm;">
                            <?php

                                $at = explode('||',$p->authors);
                                $a = implode(', ',$at);

                            ?>
                            <p style="text-transform:uppercase;font-size:9px;padding-left:0.8cm;padding-right:0.8cm;text-align: justify; text-justify: inter-word;"><b><?php echo $p->title; ?></b> <span><?php echo $a; ?></span></p>  
                        </div>

                <?php if($ij++==0): ?> 
                </div><!-- ./ DIV 1S - Isto é importante para não permitir quebrar a página e ficar o título sozinho em outra. Isto faz com que pelo menos o título e o primeiro registro apareçam juntos. -->
                <?php endif; ?>

                    <?php endforeach; ?>      

            <?php endforeach; ?>
 
        </section>

        <footer class="force-break-page"></footer>

        <section class="section-papers"> <!-- ARTIGOS -->

            <h1 style="margin-bottom:1.5cm;font-size:16px; font-weight:700;" class="text-center">ARTIGOS</h1>
            
            <?php $i=0; $date=""; $shift=""; foreach($recordsPA as $rpo): ?>

                <!-- DIV 1S --> <div style="page-break-inside: avoid; font-size:10px; <?php if($i++!=0): ?> padding-top:5px; <?php endif; ?>">
                    
                    <?php $kkk = 0; if($date!=$rpo->date): ?>
                        <?php $date = $rpo->date; ?>
                        <?php $shift = ""; $kkk = 0; ?>
                        <h1 style="<?php if($i!=0): ?> padding-top: 0.50cm; <?php endif; ?> font-size:14px;font-weight:700;text-transform:uppercase;"><?php echo date("d/m/Y", strtotime($rpo->date));  ?></h1>
                    
                        <?php if($shift!=$rpo->shift): ?>
                            <?php $shift = $rpo->shift; ?>
                            <h1 style="font-size:14px;font-weight:700;text-transform:uppercase;"><?php echo $rpo->shift;  ?></h1>
                        <?php endif; ?>

                    <?php else: ?>

                        <?php if($shift!=$rpo->shift): ?>
                            <?php $shift = $rpo->shift; ?>
                            <h1 style="<?php if($kkk++!=0): ?> padding-top: 0.50cm; <?php endif; ?> font-size:14px;font-weight:700;text-transform:uppercase;"><?php echo $rpo->shift;  ?></h1>
                        <?php endif; ?>

                    <?php endif; ?>

                    

                    <h2 style="font-size:12px;font-weight:700;text-transform:uppercase;padding-left:0.75cm;"><?php echo $rpo->thematicgroup->name; ?> - <?php echo $rpo->local; ?>, <?php echo $rpo->starthour; ?> - <?php echo $rpo->endhour; ?></h2>
                    <?php $ij=0; foreach ($rpo->ownPaperList as $p): ?>

                        <div style=" <?php if(count($rpo->ownPaperList)-1==$ij): ?> margin-bottom:-0.50cm; <?php endif; ?> page-break-inside: avoid;padding-left:0.75cm;padding-right:0.75cm;">
                            <?php

                                $at = explode('||',$p->authors);
                                $a = implode(', ',$at);

                            ?>
                            <p style="text-transform:uppercase;font-size:9px;padding-left:0.8cm;padding-right:0.8cm;text-align: justify; text-justify: inter-word;"><b><?php echo $p->title; ?></b> <span><?php echo $a; ?></span></p>  
                        </div>

                <?php if($ij++==0): ?> 
                </div><!-- ./ DIV 1S - Isto é importante para não permitir quebrar a página e ficar o título sozinho em outra. Isto faz com que pelo menos o título e o primeiro registro apareçam juntos. -->
                <?php endif; ?>

                    <?php endforeach; ?>      

            <?php endforeach; ?>
 
        </section>

        <footer class="force-break-page"></footer>

        <section class="section-minicourses"> <!-- MINICOURSES -->

            <h1 style="margin-bottom:1.5cm;font-size:16px; font-weight:700;" class="text-center">MINICURSOS</h1>
            
            <?php $i=0; $shift=""; foreach($recordsMC as $rpo): ?>

                <!-- DIV 1S --> <div style="page-break-inside: avoid; font-size:10px; <?php if($i++!=0): ?> padding-top:5px; <?php endif; ?>">

                    <?php if($shift!=$rpo->shift): ?>
                        <?php $shift = $rpo->shift; ?>
                        <h1 style="font-size:14px;font-weight:700;text-transform:uppercase;"><?php echo $rpo->shift;  ?></h1>
                    <?php endif; ?>

                    <h2 style="font-size:12px;font-weight:700;text-transform:uppercase;padding-left:0.75cm;"><?php echo $rpo->minicourse->title; ?></h2>
                    <div style="page-break-inside: avoid;padding-left:0.75cm;padding-right:0.75cm;">
                        <?php

                            $at = explode('||',$rpo->minicourse->expositor);
                            $a = implode(', ',$at);

                        ?>
                        <p style="text-transform:uppercase;font-size:9px;padding-left:0.8cm;padding-right:0.8cm;text-align: justify; text-justify: inter-word;">
                            <?php echo $a; ?>
                        </p>  
                        <p style="text-transform:uppercase;font-size:9px;padding-left:0.8cm;padding-right:0.8cm;text-align: justify; text-justify: inter-word;">
                            <?php echo $rpo->local; ?>, <?php echo $rpo->starthour; ?> - <?php echo $rpo->endhour; ?>
                        </p> 
                        <p style="text-transform:uppercase;font-size:9px;padding-left:0.8cm;padding-right:0.8cm;text-align: justify; text-justify: inter-word;">
                            Datas: 
                            <?php $i=0;foreach($rpo->minicourse->sharedMinicoursedayshiftList as $mds): ?>
                                <?php if($i++!=0) echo ','; ?>
                                <?php echo date("d/m/y", strtotime($mds->date)); ?>
                            <?php endforeach; ?>
                        </p> 
                    </div>



                <?php //if($ij++==0): ?> 
                </div><!-- ./ DIV 1S - Isto é importante para não permitir quebrar a página e ficar o título sozinho em outra. Isto faz com que pelo menos o título e o primeiro registro apareçam juntos. -->
                <?php //endif; ?>

            <?php endforeach; ?>
 
        </section>

        <footer class="force-break-page"></footer>

        <section class="section-conferences"> <!-- CONFERENCES -->

            <h1 style="margin-bottom:1.5cm;font-size:16px; font-weight:700;" class="text-center">CONFERÊNCIAS</h1>
            
            <?php $i=0; foreach($recordsCF as $rpo): ?>

                <!-- DIV 1S --> <div style="page-break-inside: avoid; font-size:10px; <?php if($i++!=0): ?> padding-top:5px; <?php endif; ?>">

                    <h2 style="font-size:12px;font-weight:700;text-transform:uppercase;padding-left:0.75cm;"><?php echo $rpo->conference->title; ?> - <?php echo date("d/m/y", strtotime($rpo->conference->conferencedayshift->date)); ?></h2>
                    <div style="page-break-inside: avoid;padding-left:0.75cm;padding-right:0.75cm;">
                        <?php

                            $at = explode('||',$rpo->conference->lecturer);
                            $a = implode(', ',$at);

                        ?>
                        <p style="text-transform:uppercase;font-size:9px;padding-right:0.8cm;text-align: justify; text-justify: inter-word;">
                            <?php echo $a; ?>
                        </p>  
                        <p style="text-transform:uppercase;font-size:9px;padding-right:0.8cm;text-align: justify; text-justify: inter-word;">
                            <?php echo $rpo->local; ?>, <?php echo $rpo->starthour; ?> - <?php echo $rpo->endhour; ?>
                        </p> 
                    </div>

                </div><!-- ./ DIV 1S - Isto é importante para não permitir quebrar a página e ficar o título sozinho em outra. Isto faz com que pelo menos o título e o primeiro registro apareçam juntos. -->

            <?php endforeach; ?>
 
        </section>

        <footer class="force-break-page"></footer>

        <section class="section-others-activities">

            <h1 style="margin-bottom:1.5cm;font-size:16px; font-weight:700;" class="text-center">OUTRAS ATIVIDADES</h1>
            
            <?php $i=0; $date=""; foreach($recordsOA as $roa): ?>

                <div style="page-break-inside: avoid; font-size:10px; <?php if($i++!=0): ?> padding-top:5px; <?php endif; ?>">
                    <?php if($date!=$roa->date): ?>
                        <?php $date = $roa->date; ?>
                        <h1 style="font-size:14px;font-weight:700;text-transform:uppercase;"><?php echo date("d/m/Y", strtotime($roa->date));  ?></h1>
                    <?php endif; ?>
                    <h2 style="font-size:12px;font-weight:700;text-transform:uppercase;"><?php echo $roa->title; ?></h2>
                    <p><?php echo strip_tags($roa->description); ?></p>
                    <p style="font-weight:700;"><?php echo $roa->local; ?>, <?php echo $roa->starthour; ?> - <?php echo $roa->endhour; ?></p>
                </div>        

            <?php endforeach; ?>
 
        </section>

        <footer class="force-break-page"></footer>

        <p>Olá mundo!</p>

        <footer class="force-break-page"></footer>

    </div>
    
</body>