            <?php /*echo count(R::find('conferencedayshift','id=1')->ownConferenceList);*/ ?>

            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">Gerenciar Conferências</h1>
                    
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
                    
                    <h3 class="page-header">Calendário das Conferências</h3>

                    <h4 class="page-header">Adicionar dia/turno</h4>
                    <?php echo form_open(base_url('dashboard/conference/createdayshift'), array('id' => 'formCreateConferenceDayShift','novalidate' => '','class' => 'waiting')); ?>
                    
                        <div class="row">
                            <div class="col-md-3 col-xs-3 col-sm-3">

                                <div class="form-group">
                                    <label>Dia *</label>
                                    <input type="number" class="form-control" placeholder="Dia" name="day" value="<?php if(isset($popform['day'])) echo $popform['day']; ?>">
                                    <p class="text-danger"><?php if($validation!=null && array_key_exists("day",$validation)): ?> <?php echo $validation['day']; ?> <?php endif; ?></p>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-4 col-sm-4">

                                <div class="form-group">
                                    <label>Mês *</label>
                                    <select class="form-control" name="month" >
                                        <option <?php if(isset($popform['month']) && $popform['month']==1): ?> selected <?php endif; ?> value="1">Janeiro</option>
                                        <option <?php if(isset($popform['month']) && $popform['month']==2): ?> selected <?php endif; ?> value="2">Fevereiro</option>
                                        <option <?php if(isset($popform['month']) && $popform['month']==3): ?> selected <?php endif; ?> value="3">Março</option>
                                        <option <?php if(isset($popform['month']) && $popform['month']==4): ?> selected <?php endif; ?> value="4">Abril</option>
                                        <option <?php if(isset($popform['month']) && $popform['month']==5): ?> selected <?php endif; ?> value="5">Maio</option>
                                        <option <?php if(isset($popform['month']) && $popform['month']==6): ?> selected <?php endif; ?> value="6">Junho</option>
                                        <option <?php if(isset($popform['month']) && $popform['month']==7): ?> selected <?php endif; ?> value="7">Julho</option>
                                        <option <?php if(isset($popform['month']) && $popform['month']==8): ?> selected <?php endif; ?> value="8">Agosto</option>
                                        <option <?php if(isset($popform['month']) && $popform['month']==9): ?> selected <?php endif; ?> value="9">Setembro</option>
                                        <option <?php if(isset($popform['month']) && $popform['month']==10): ?> selected <?php endif; ?> value="10">Outubro</option>
                                        <option <?php if(isset($popform['month']) && $popform['month']==11): ?> selected <?php endif; ?> value="11">Novembro</option>
                                        <option <?php if(isset($popform['month']) && $popform['month']==12): ?> selected <?php endif; ?> value="12">Dezembro</option>
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-5 col-sm-5">

                                <div class="form-group">
                                    <label>Ano *</label>
                                    <input type="number" class="form-control" placeholder="Ano" name="year" value="<?php if(isset($popform['year'])) echo $popform['year']; ?>">
                                    <p class="text-danger"><?php if($validation!=null && array_key_exists("year",$validation)): ?> <?php echo $validation['year']; ?> <?php endif; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-12 col-xs-12 col-sm-12">

                                <div class="form-group">
                                    <label>Turno *</label>
                                    <select class="form-control" name="shift">
                                        <option <?php if(isset($popform['shift']) && $popform['shift']=='matutino'): ?> selected <?php endif; ?> value="matutino">Matutino</option>
                                        <option <?php if(isset($popform['shift']) && $popform['shift']=='vespertino'): ?> selected <?php endif; ?> value="vespertino">Vespertino</option>
                                        <option <?php if(isset($popform['shift']) && $popform['shift']=='noturno'): ?> selected <?php endif; ?> value="noturno">Noturno</option>
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
                    <h4 style="margin-bottom:5px;" class="page-header">Calendário</h4>
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
                                                    <?php echo form_open(base_url('dashboard/conference/deletedayshift'), array('id' => 'formDeleteDayShift','novalidate' => '','class' => 'waiting','style' => 'display:inline-block;')); ?>
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
                                                        
                                                        <?php if(count($e['conferences'])==0): ?>
                                                            <tr class="text-center active">
                                                                <td class="text-danger">Ainda não há conferências cadastradas para este dia/turno.</td>         
                                                            </tr>
                                                        <?php else: ?>
                                                        
                                                            <?php foreach($e['conferences'] as $m): ?>
                                                            <tr class="text-center active">
                                                                <td style="width:40%;"><b><?php echo $m->title; ?></b></td>
                                                                <td><a style="cursor:pointer;" data-toggle="modal" data-target=".modal-conference-retrieve-details" data-data="<?php echo $m->id; ?>"> Mais Informações</a></td>
                                                                <td><a style="cursor:pointer;" data-toggle="modal" data-target=".modal-conference-edit" data-data="<?php echo $m->id; ?>"> Editar</a></td>
                                                                <td><a href="javascript:void(0);" class="button-remove-conference" data-value="<?php echo $m->id; ?>">Remover Conferência</a></td>
                                                                <?php echo form_open(base_url('dashboard/conference/delete'), array('id' => 'formDeleteConference-'.$m->id,'novalidate' => '','class' => 'waiting','style' => 'display:inline-block;')); ?>
                                                                    <input style="display:none;" name="id" value="<?php echo $m->id; ?>" />
                                                                </form> 
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

                        <?php print_day($tempdate,$templist); ?>

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

                    <?php print_day($date,$listfinalordered); // Print the last one ?>

                    </div> <!-- ./ Close Row -->

                <?php endif; ?>
                
            </div>

            <!-- ./ END CALENDAR -->

            <div class="row">
                <div class="col-md-12">
                    <h3 style="margin-top:40px;">Cadastrar Conferência</h3>
                    
                    <hr/>
                    
                    <?php echo form_open(base_url('dashboard/conference/create'), array('id' => 'formCreateConference','novalidate' => '','class' => 'waiting')); ?>
                    
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                	<label for="file">Título *</label>
                                    <input type="text" class="form-control" placeholder="Título" name="title" value="<?php if(isset($popform['title'])) echo $popform['title']; ?>" />
                                    <p class="text-danger"><?php if($validation!=null && isset($validation['title'])): ?> <?php echo $validation['title']; ?> <?php endif; ?></p>
                                </div>
                                
                                <div class="form-group">
                                	<label for="file">Conferencista *</label>
                                    <br/>
                                    <button class="btn btn-default" data-toggle="modal" data-target=".modal-conference-add-lecturer" type="button" style="margin-bottom:10px;">Adicionar Conferencista</button>
                                    <input readOnly type="text" class="form-control" placeholder="Conferencista não adicionado" name="lecturer" value="<?php if(isset($popform['lecturer'])) echo $popform['lecturer']; ?>" />
                                    <a class="reset-lecturer" href="javascript:void(0);">Resetar</a>
                                    <p class="text-danger"><?php if($validation!=null && isset($validation['lecturer'])): ?> <?php echo $validation['lecturer']; ?> <?php endif; ?></p>
                                </div>

                                <div class="form-group">
                                    <label for="file">Dia/Turno *</label>
                                    <select class="form-control" name="dayshift">
                                        
                                        <?php foreach ($cdss as $cds): ?>
                                                <option <?php if(isset($popform['dayshift']) && $popform['dayshift']==$cds->id): ?> selected <?php endif; ?> value="<?php echo $cds->id; ?>"><?php echo date("d/m/Y", strtotime($cds->date)); ?> - <?php echo $cds->shift ?> </option>
                                        <?php endforeach ?>
                                        
                                        <?php if(!count($cds)): ?>
                                            <option value="-1">Não dias/turnos disponíveis</option>
                                        <?php endif; ?>
                                        
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                	<label for="file">Quantidade de Vagas *</label>
                                    <input type="number" class="form-control" placeholder="Quantidade de Vagas" name="vacancies" value="<?php if(isset($popform['vacancies'])) echo $popform['vacancies']; ?>" />
                                    <p class="text-danger"><?php if($validation!=null && isset($validation['vacancies'])): ?> <?php echo $validation['vacancies']; ?> <?php endif; ?></p>
                                </div>

                            </div>
                            <div class="col-md-6">
                                
                                <div class="form-group">
                                	<label for="file">Local *</label>
                                    <input type="text" class="form-control" placeholder="Local" name="local" value="<?php if(isset($popform['local'])) echo $popform['local']; ?>" />
                                    <p class="text-danger"><?php if($validation!=null && isset($validation['local'])): ?> <?php echo $validation['local']; ?> <?php endif; ?></p>
                                </div>
                                
                                <div class="form-group">
                                	<label for="file">Proposta *</label>
                                    <textarea rows="11" class="form-control" placeholder="Proposta" name="proposal"><?php if(isset($popform['proposal'])) echo $popform['proposal']; ?></textarea>
                                    <p class="text-danger"><?php if($validation!=null && isset($validation['proposal'])): ?> <?php echo $validation['proposal']; ?> <?php endif; ?></p>
                                </div>
                                
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center success-container">
                                <div class="success"></div>
                                <button type="submit" class="btn btn-lg btn-success">Criar Conferência</button>
                            </div>
                            
                        </div>
                    </form>
                    
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

    <div class="modal fade modal-conference-add-lecturer" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-body">
                <div class="form-group">
                    <label for="file">Nome Completo*</label>
                    <input type="text" class="form-control" placeholder="Nome *" name="name" />
                </div>
                <div class="form-group">
                    <label for="file">Instituição *</label>
                    <input type="text" class="form-control" placeholder="Instituição *" name="institution"/>
                </div>
                <button class="btn btn-default" >Adicionar Conferencista</button>
            </div>

        </div>
      </div>
    </div>

    <!-- Modal Consolidate Minicourse -->
    <div class="modal fade modal-conference-retrieve-details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Detalhes da Conferência</h4>
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

    <input id="rc-details" style="display:none;" value="<?php echo base_url('dashboard/conference/retrievedetails') ?>" />

    <!-- Modal Consolidate Minicourse -->
    <div class="modal fade modal-conference-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Detalhes da Conferência</h4>
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

    <input id="rc-edit-t" style="display:none;" value="<?php echo base_url('dashboard/conference/retrieveedit') ?>" />