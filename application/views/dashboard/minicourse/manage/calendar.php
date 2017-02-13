        <?php function print_day($date,$list){ ?>

                <?php $datesep = explode('-',$date);?>

                <div class="col-lg-3 col-md-6">
                    <div class="day">
                        <div class="day-heading">
                            <div class="row">
                                <div class="dday col-xs-5">
                                    <?php echo $datesep[2]; ?>
                                </div>
                                <div class="col-xs-7 text-right ">
                                    <div class="dmonth text-uppercase"><?php echo monthName($datesep[1]); ?></div>
                                    <div class="dyear"><?php echo $datesep[0]; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="day-footer">
                            <div class="row">
                                
                                <?php foreach($list as $e): ?>
                                
                                <div class="shift <?php if(!isset($e['minicoursefinal']) || $e['minicoursefinal']==null) echo 'no-shift'; ?> col-xs-12">
                                    <a <?php if(isset($e['minicoursefinal']) || $e['minicoursefinal']!=null): ?> href="#" <?php endif; ?>>
                                        
                                        <span class="pull-left">
                                            
                                            <?php if($e['shift']=='matutino'): ?>
                                                <i class="fa fa-sun-o"></i> Manhã
                                            <?php elseif($e['shift']=='vespertino'): ?>
                                                <i class="fa fa-cloud"></i> Tarde
                                            <?php elseif($e['shift']=='noturno'): ?>
                                                <i class="fa fa-moon-o"></i> Noite
                                            <?php endif; ?>
                                            
                                        </span>
                                        
                                        <span class="pull-right">
                                            
                                            <?php if(!isset($e['minicoursefinal']) || $e['minicoursefinal']==null): ?>
                                                Alocação Livre
                                            <?php else: ?>
                                                <?php echo $e['minicoursefinal']->minicourse->title; ?>
                                            <?php endif; ?>
                                            
                                        </span>
                                    </a>
                                </div>
                                
                                <?php endforeach; ?>
                                
                                <!-- <div class="shift no-shift col-xs-12">
                                    <a>
                                        <span class="pull-left"><i class="fa fa-moon-o"></i> Noite</span>
                                        <span class="pull-right">Sem minicurso</span>
                                    </a>
                                </div> -->
                                
                            </div>
                            
                        </div>
                    </div>
                </div><!-- /.end-day -->

        <?php } ?> <!-- ./ end function print_day -->
        

        <?php if (!count($mcdss)): ?>
                
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

            <?php foreach($mcdss as $mcds): ?>

                <?php

                    if($date==""){ // Just first time
                        $date = $mcds["date"];
                        $list[] = array("id" => $mcds["id"], "date" => $mcds["date"], "shift" => $mcds["shift"], "minicoursefinal" => $mcds["minicoursefinal"]);
                        continue;
                    }else if($date==$mcds["date"]){
                        $list[] = array("id" => $mcds["id"], "date" => $mcds["date"], "shift" => $mcds["shift"], "minicoursefinal" => $mcds["minicoursefinal"]);
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
                        $list[] = array("id" => $mcds["id"], "date" => $mcds["date"], "shift" => $mcds["shift"], "minicoursefinal" => $mcds["minicoursefinal"]);
                        $date  = $mcds["date"];
                        
                    }
                ?>

                <?php print_day($tempdate,$templist); ?>

                <?php if( $count++ > 2 ): ?>
                
                    </div>
                    <div class="row">

                    <?php $count = 0; ?>

                <?php endif; ?>   

            <?php endforeach; ?>

            <?php print_day($date,$list); // Print the last one ?>

            </div> <!-- ./ Close Row -->

        <?php endif; ?>