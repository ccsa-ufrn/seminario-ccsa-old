            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">Gerenciar Minicursos</h1>
                    
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
                    
                    <h3 class="page-header">Calendário dos Minicursos</h3>

                    <h4 class="page-header">Adicionar dia/turno</h4>
                    <?php echo form_open(base_url('dashboard/minicourse/createdayshift'), array('id' => 'formCreateMinicourseDayShift','novalidate' => '','class' => 'waiting')); ?>
                    
                        <div class="row">
                            <div class="col-md-3 col-xs-3 col-sm-3">

                                <div class="form-group">
                                    <label>Dia *</label>
                                    <input type="number" class="form-control" placeholder="Dia" name="day" value="<?php echo $popform['day']; ?>">
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['day']; ?> <?php endif; ?></p>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-4 col-sm-4">

                                <div class="form-group">
                                    <label>Mês *</label>
                                    <select class="form-control" name="month" >
                                        <option value="1">Janeiro</option>
                                        <option value="2">Fevereiro</option>
                                        <option value="3">Março</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Maio</option>
                                        <option value="6">Junho</option>
                                        <option value="7">Julho</option>
                                        <option value="8">Agosto</option>
                                        <option value="9">Setembro</option>
                                        <option value="10">Outubro</option>
                                        <option value="11">Novembro</option>
                                        <option value="12">Dezembro</option>
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-5 col-sm-5">

                                <div class="form-group">
                                    <label>Ano *</label>
                                    <input type="number" class="form-control" placeholder="Ano" name="year" value="<?php echo $popform['year']; ?>">
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['year']; ?> <?php endif; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-12 col-xs-12 col-sm-12">

                                <div class="form-group">
                                    <label>Turno *</label>
                                    <select class="form-control" name="shift">
                                        <option value="matutino">Matutino</option>
                                        <option value="vespertino">Vespertino</option>
                                        <option value="noturno">Noturno</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <button type="submit" class="btn btn-block btn-large btn-success"> <i class="fa fa-plus-circle"></i> Adicionar Dia/Turno</button>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 success-container text-center">
                                <div class="success"></div>
                            </div>

                        </div>
                    </form>

                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!-- BEGIN CALENDAR -->

            <div class="row">
                <div class="col-xs-12">
                    <h4 class="page-header">Calendário</h4>
                </div>
            </div>

            <div id="rm-days" class="row" style="margin-top:20px;">
                
                        <?php function print_day($date,$list){ ?> <!-- BEGIN FUNCTION -->

                        	<div class="row">
                                    
                                <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
                                    
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h4 style="margin:0px;margin-top:20px;"><?php echo date("d/m/Y", strtotime($date));  ?></h4>
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
                                                    <?php echo form_open(base_url('dashboard/minicourse/deletedayshift'), array('id' => 'formDeleteDayShift','novalidate' => '','class' => 'waiting','style' => 'display:inline-block;')); ?>
                                                        <input style="display:none;" name="id" value="<?php echo $e['id']; ?>" />
                                                        <button type="submit" style="display:inline-block;margin-left:10px; padding:0px;border:none;background-color:white;color:blue;font-size:12px;">
                                                            Remover Turno
                                                        </button>
                                                    </form>
                                                
                                                </div>
                                                
                                            </div>
                                            
                                            <div class="row">
                                                
                                                <div class="col-lg-12">
                                                    
                                                    <table class="table table-hover">
                                                        
                                                        <?php if(count($e['minicourses'])==0): ?>
                                                            <tr class="text-center active">
                                                                <td class="text-danger">Ainda não há minicursos cadastrados para este dia/turno.</td>         
                                                            </tr>
                                                        <?php else: ?>
                                                        
                                                            <?php foreach($e['minicourses'] as $m): ?>
                                                            <tr class="text-center active">
                                                                <td style="width:40%;text-transform:uppercase;"><b><?php echo $m->title; ?></b></td>
                                                                <td style="vertical-align:middle;"><a style="cursor:pointer;" data-toggle="modal" data-target="#modalMinicourseDetails" data-data="<?php echo $m->id; ?>"> Mais Informações</a></td>
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
                                $list[] = array("id" => $mcds["id"], "date" => $mcds["date"], "shift" => $mcds["shift"], "minicourses" => $mcds["sharedMinicourseList"]);
                                continue;
                            }else if($date==$mcds["date"]){
                                $list[] = array("id" => $mcds["id"], "date" => $mcds["date"], "shift" => $mcds["shift"], "minicourses" => $mcds["sharedMinicourseList"]);
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
                                $list[] = array("id" => $mcds["id"], "date" => $mcds["date"], "shift" => $mcds["shift"], "minicourses" => $mcds["sharedMinicourseList"]);
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
                
            </div>

            <!-- ./ END CALENDAR -->

            <div class="row">
                <div class="col-md-12">
                    <h3>Minicursos Cadastrados</h3>
                    <p> <a href="<?php echo base_url('dashboard/minicourse/manage'); ?>" >Todos os mincursos</a> | <a href="<?php echo base_url('dashboard/minicourse/manage/consolidated'); ?>">Minicursos consolidados</a> | <a href="<?php echo base_url('dashboard/minicourse/manage/noconsolidated'); ?>">Minicursos não consolidados</a> </p>
                    
                    <table id="tableMinicourse" class="table table-striped table-bordered table-condensed">

                        <thead>
                            <tr>
                                <th>Consolidar?</th>
                                <th>Título </th>
                                <th>Turno</th>
                                <th>Editar</th>
                                <th>Detalhes</th>
                            </tr>
                        </thead>
                        <?php foreach ($mcs as $mc): ?>
                            <tr>

                                <td class="check text-center">
                                    
                                    <?php if($mc->consolidated=='yes'): ?>

                                        <a class="btn btn-block btn-danger" data-target=".modal-minicourse-confirm-operation" data-toggle="modal" data-id="<?php echo $mc->id; ?>">Desconsolidar</a>

                                    <?php else: ?>

                                        <button type="button" class="btn btn-block btn-success" data-target="#modalConsolidateMinicourse" data-toggle="modal" data-data="<?php echo $mc->id; ?>">
                                            Consolidar
                                        </button>

                                    <?php endif; ?>
                                </td>
                                <td class="title"><?php echo $mc->title; ?></td>
                                <td class="shift"><?php echo $mc->shift; ?></td>
                                <td style="width:20%;text-align:center;">
                                    <?php if($mc->consolidated=='no'): ?>
                                        O minicurso não está consolidado.
                                    <?php else: ?>
                                        <a class="btn btn-block btn-success" style="cursor:pointer;" data-toggle="modal" data-target=".modal-minicourse-edit-info" data-data="<?php echo $mc->id; ?>">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td class="view">
                                    <button id="openMinicourseDetails" data-target="#modalMinicourseDetails" style="display:none;"></button>
                                    <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalMinicourseDetails" data-data="<?php echo $mc->id; ?>">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach ?>

                    </table>
                </div>
                <!-- /.col-lg-12 -->
            </div>

    <!-- Modal -->
    <div class="modal fade" id="modalMinicourseDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

    <input id="rm-details" style="display:none;" value="<?php echo base_url('dashboard/minicourse/retrievedetails') ?>" />

    <!-- Modal Consolidate Minicourse -->
    <div class="modal fade" id="modalConsolidateMinicourse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Consolidar Minicurso</h4>
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

    <input id="rm-consolidateMinicourse" value="<?php echo base_url('dashboard/minicourse/retrieveconsolidation'); ?>" style="display:none" />

    <!-- MODAL CONFIRM OPERATIONS -->
    <div class="modal fade modal-minicourse-confirm-operation" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content" style="padding:20px;">

                <!-- HERE GOES CONTENT -->
                
            </div>
        </div>
    </div>

    <!-- MODAL CONFIRM OPERATIONS -->
    <div class="modal fade modal-minicourse-edit-info" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content" style="padding:20px;">

                <!-- HERE GOES CONTENT -->
                
            </div>
        </div>
    </div>