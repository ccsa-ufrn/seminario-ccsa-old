<header class="logo">
    
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-8 col-sm-offset-2">
            <image class="logo" width="100%" src="" />
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">
            <h1>CONFERÊNCIAS</h1>
        </div>
    </div>

</header>

<section class="roundtables-body">

    <?php function print_day($date,$list){ ?> <!-- BEGIN FUNCTION -->

        <div class="row">

            <div class="col-lg-12">

                <div class="row">
                    <div class="col-lg-12">
                        <h4 style="margin:0px;margin-top:20px;color:#c1494b;"><b><?php echo date("d/m/Y", strtotime($date));  ?></b></h4>
                        <hr  style="margin:5px 0px;" />
                    </div>
                </div>

                <?php foreach($list as $e): ?>

                <div class="row">
                    <div class="col-lg-12">

                        <div class="row">

                            <div class="col-lg-12">

                                <h5 style="display:inline-block;color:#c1494b;font-weight:bold;text-transform:uppercase;">
                                    <?php if($e['shift']=='matutino'): ?>
                                        <i class="fa fa-sun-o"></i> Manhã (7:30h às 9:10h) 
                                    <?php elseif($e['shift']=='vespertino'): ?>
                                        <i class="fa fa-cloud"></i> Tarde (13:30h às 15:10h)
                                    <?php elseif($e['shift']=='noturno'): ?>
                                        <i class="fa fa-moon-o"></i> Noite (18:30h às 20:10h)
                                    <?php endif; ?>
                                </h5>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-12">

                                
                                <?php if(count($e['conferences'])==0): ?>
                                    Ainda não há conferências cadastrados para este dia/turno.
                                <?php else: ?>
                                
                                    <?php foreach($e['conferences'] as $m): ?>

                                        <?php 

                                            $f = "";

                                            $fa = explode('||',$m->lecturer);

                                            for ($i=0; $i < count($fa); $i++) { 
                                                if($i!=0) $f .= " , ";
                                                $f .= $fa[$i];
                                            }

                                        ?>

                                        <p style="text-transform:uppercase;"><b><?php echo $m->title; ?></b> (<?php echo $f; ?>)</p>
                                    
                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </div>
                        </div>


                    </div>
                </div>   

                <?php endforeach; ?>

            </div>

        </div>

    <?php } ?> <!-- ./ END FUNCTION PRINT_DAY-->

    <?php if (!count($cdss)): ?>

            <div class="col-md-12">
                <p class="alert-danger">Ainda não há dias/turnos cadastrados para o calendário.</p>
            </div>

    <?php else: ?>

        <?php 

            $date = ""; 
            $list = array();
            $tempdate = "";
            $templist = array();
            $count = 0;

        ?>

        <div class="row">

        <?php foreach($cdss as $cds): ?>

            <?php

                if($date==""){ // Just first time
                    $date = $cds["date"];
                    $list[] = array("id" => $cds["id"], "date" => $cds["date"], "shift" => $cds["shift"], "conferences" => $cds["ownConferenceList"]);
                    continue;
                }else if($date==$cds["date"]){
                    $list[] = array("id" => $cds["id"], "date" => $cds["date"], "shift" => $cds["shift"], "conferences" => $cds["ownConferenceList"]);
                    continue;
                }else{

                    $templist = array();
                    $tempdate = $date;

                    // Passing elements from $list to $templist and ordering $templist by shift

                    $shiftordered = array('matutino','vespertino','noturno');

                    for( $i=0, $j=0 ; ; ++$i ){

                        if($j==count($shiftordered))
                            break;

                        if($i==count($list) && $j<count($shiftordered)){
                            ++$j;
                            $i=-1;
                            continue;
                        }

                        if($list[$i]['shift']==$shiftordered[$j]){
                            $templist[] = $list[$i];
                            ++$j;
                            $i=-1;
                        }

                    }

                    // Cleaning $list array, and put new elements of date, and def new date
                    $list = array();
                    $list[] = array("id" => $cds["id"], "date" => $cds["date"], "shift" => $cds["shift"], "conferences" => $cds["ownConferenceList"]);
                    $date  = $cds["date"];

                }
            ?>

            <?php print_day($tempdate,$templist,$user); ?>

            <?php if( $count++ > 2 ): ?>

                </div>
                <div class="row">

                <?php $count = 0; ?>

            <?php endif; ?>   

        <?php endforeach; ?>

        <?php 

            $listfinalordered = array();

            // Passing elements from $list to $templist and ordering $listfinalordered by shift

            $shiftordered = array('matutino','vespertino','noturno');

            for( $i=0, $j=0 ; ; ++$i ){

                if($j==count($shiftordered))
                    break;

                if($i==count($list) && $j<count($shiftordered)){
                    ++$j;
                    $i=-1;
                    continue;
                }

                if($list[$i]['shift']==$shiftordered[$j]){
                    $listfinalordered[] = $list[$i];
                    ++$j;
                    $i=-1;
                }

            }

        ?>   

        <?php print_day($date,$listfinalordered,$user); // Print the last one ?>

        </div> <!-- ./ Close Row -->

    <?php endif; ?>




</section>


