<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">Inscrever-se em Mesas-redondas</h1>

        <?php if($success!=null): ?>
            <div class='alert alert-success text-center'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <strong><?php echo $success; ?></strong>
            </div>
        <?php endif; ?>

        <?php if($error!=null): ?>
            <div class='alert alert-danger text-center'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <strong><?php echo $error; ?></strong>
            </div>
        <?php endif; ?>
        
        <h4 class="page-header">Minhas Mesas-Redondas</h4>
        
        <table class="table table-hover">

        <?php foreach($user->sharedRoundtableList as $mc): ?>
            
            <tr class="active" style="text-align:center;" >
                <td style="width:40%">
                    <b><?php echo $mc->title; ?></b>
                </td>
                <td style="width:20%">
                    <?php echo date("d/m/Y", strtotime($mc->roundtabledayshift->date));  ?>
                </td>
                <td>
                    <a style="cursor:pointer;" data-target="#modalEnrollRoundtableDetails" data-toggle="modal" data-data="<?php echo  $mc->id;?>">Detalhes</a>
                </td>
                <td>
                    <?php if($date_limit['open']): ?>
                    <a style="cursor:pointer;"  class="roundtable-registration-button-unroll" data-data="<?php echo $mc->id; ?>" >Desfazer Inscrição</a>
                    <?php echo form_open(base_url('dashboard/roundtable/unroll'), array('id' => 'formUnrollRoundtable-'.$mc->id,'novalidate' => '','class' => 'waiting','style' => 'display:inline-block;')); ?>
                        <input style="display:none;" name="id" value="<?php echo $mc->id; ?>" />
                    </form> 
                    <?php endif; ?>
                </td>
            </tr>
        
        <?php endforeach; ?>    
           
        <?php if(!count($user->sharedRoundtableList)): ?>
            <tr class="danger">
                <td>Você ainda não se inscreveu em nenhuma mesa-redonda.</td>
            </tr>
        <?php endif; ?>  
            
        </table>
        
        <h4 class="page-header">Inscrever-se em Mesas-Redondas</h4>

        <p class="text-danger">Período de Inscrição: <?php echo date("d/m/Y", strtotime($date_limit['inscriptionStart']->value));  ?> - <?php echo date("d/m/Y", strtotime($date_limit['inscriptionEnd']->value));  ?></p>

        <?php if($date_limit['open']): ?>
        
        <div id="rm-days" class="row" style="margin-top:20px;">
                
            <?php function print_day($date,$list,$user){ ?> <!-- BEGIN FUNCTION -->

                <div class="row">

                    <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">

                        <div class="row">
                            <div class="col-lg-12">
                                <h4 style="margin:0px;margin-top:20px;"><b><?php echo date("d/m/Y", strtotime($date));  ?></b></h4>
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
                                                <i class="fa fa-sun-o"></i> Manhã
                                            <?php elseif($e['shift']=='vespertino'): ?>
                                                <i class="fa fa-cloud"></i> Tarde
                                            <?php elseif($e['shift']=='noturno'): ?>
                                                <i class="fa fa-moon-o"></i> Noite
                                            <?php endif; ?>
                                        </h5>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-lg-12">

                                        <table class="table table-hover">
                                        
                                        <?php if(count($e['roundtables'])==0): ?>
                                            <tr class="danger">
                                                <td>Ainda não há minicursos cadastrados para este dia/turno.</td>
                                            </tr>
                                        <?php else: ?>
                                        
                                            <?php foreach($e['roundtables'] as $m): ?>
                                            
                                                <?php 
                                                  
                                                    $status = 'vacancies';
                                                  
                                                    if($m->consolidatedvacanciesfilled==$m->consolidatedvacancies)
                                                        $status = 'novacancies';
                                                        
                                                    if(R::count('roundtableUser','user_id=? AND roundtable_id=?',array($user->id,$m->id)))
                                                        $status = 'enrolled';
                                                
                                                ?>
                                                
                                                <tr 
                                                    <?php if($status=='vacancies'): ?> class="success" <?php endif; ?> 
                                                    <?php if($status=='novacancies'): ?> class="danger" <?php endif; ?> 
                                                    <?php if($status=='enrolled'): ?> class="info" <?php endif; ?> 
                                                style="text-align:center;">
                                                    <td style="width:40%">
                                                        <b><?php echo $m->title; ?></b>
                                                    </td>
                                                    <td style="width:20%">
                                                        <?php echo $m->consolidatedvacancies - $m->consolidatedvacanciesfilled ?> Vagas
                                                    </td>
                                                    <td>
                                                        <a style="cursor:pointer;" data-target="#modalEnrollRoundtableDetails" data-toggle="modal" data-data="<?php echo $m->id; ?>">Detalhes</a>
                                                    </td>
                                                    <?php if($status=='vacancies'): ?> 
                                                        <td>
                                                            <a style="cursor:pointer;" class="roundtable-registration-button" data-data="<?php echo $m->id; ?>" >Inscrever-se</a>
                                                        </td>
                                                        <?php echo form_open(base_url('dashboard/roundtable/enrolla'), array('id' => 'formEnrollRoundtable-'.$m->id,'novalidate' => '','class' => 'waiting','style' => 'display:inline-block;')); ?>
                                                            <input style="display:none;" name="id" value="<?php echo $m->id; ?>" />
                                                        </form> 
                                                    <?php elseif($status=='novacancies'): ?> 
                                                        <td>
                                                            Sem vagas
                                                        </td>
                                                    <?php elseif($status=='enrolled'): ?> 
                                                        <td>
                                                            Inscrito
                                                        </td>
                                                    <?php endif; ?> 
                                                </tr>
                                            <?php endforeach; ?>

                                        <?php endif; ?>
                                        </table>
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
                    $list[] = array("id" => $cds["id"], "date" => $cds["date"], "shift" => $cds["shift"], "roundtables" => $cds["ownRoundtableList"]);
                    continue;
                }else if($date==$cds["date"]){
                    $list[] = array("id" => $cds["id"], "date" => $cds["date"], "shift" => $cds["shift"], "roundtables" => $cds["ownRoundtableList"]);
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
                    $list[] = array("id" => $cds["id"], "date" => $cds["date"], "shift" => $cds["shift"], "roundtables" => $cds["ownRoundtableList"]);
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

</div>

    <?php else: ?>
        <p class="bg-danger" style="padding:20px;text-align:center;font-weight:bold;">
        <?php if( $user->paid=='pendent' ): ?>
            O seu pagamento ainda está sendo avaliado. Em breve você será notificado.
        <?php elseif( $user->paid!='accepted' && $user->paid!='free' ): ?>
            Você precisa realizar <a href="<?php echo base_url('dashboard/user/payment'); ?>">o pagamento</a> para se inscrever em uma mesa-redonda.
        <?php elseif(!datebeq(mdate('%Y-%m-%d'),$date_limit['inscriptionStart']->value)): ?>
            O período de inscrições ainda não foi iniciado. O período é de <?php echo date("d/m/Y", strtotime($date_limit['inscriptionStart']->value));  ?> - <?php echo date("d/m/Y", strtotime($date_limit['inscriptionEnd']->value));  ?>.
        <?php elseif(!dateleq(mdate('%Y-%m-%d'),$date_limit['inscriptionEnd']->value)): ?>
            O período de inscrições está encerrado. O período era de <?php echo date("d/m/Y", strtotime($date_limit['inscriptionStart']->value));  ?> - <?php echo date("d/m/Y", strtotime($date_limit['inscriptionEnd']->value));  ?>.
        <?php endif; ?>
        
        </p>
    <?php endif; ?> 
        
        
    </div>
</div>

<!-- Modal -->
    <div class="modal fade" id="modalEnrollRoundtableDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Detalhes</h4>
                </div>
                <div class="modal-body">
                    
                    Carregando...
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <input id="rtrd" style="display:none;" value="<?php echo base_url('dashboard/roundtable/retrieveenrolldetails'); ?>" />