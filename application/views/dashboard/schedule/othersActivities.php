<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Programação de Outras Atividades</h1>

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

        <?php echo form_open(base_url('dashboard/schedule/othersactivities/newrecord'), array('id' => 'formScheduleOthersActivitiesNewRecord', 'novalidate' => '','class'=>'waiting form-add-record-behavior')); ?>

            <div class="row">
                <div class="col-md-12"><h3>Adicionar 'Outra Atividade' à Programação</h3></div>
                <div class="col-md-12">

                    <div class="form-group">
                        <label>Título *</label>
                        <input type="text" class="form-control" placeholder="Título" name="title" value="<?php if(isset($popform['title'])) echo $popform['title']; ?>" />
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['title']; ?> <?php endif; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Descrição *</label>
                        <textarea rows="5" class="form-control" placeholder="Descrição *" name="description"><?php if(isset($popform['description'])) echo $popform['description']; ?></textarea>
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['description']; ?> <?php endif; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Data *</label>
                        <div class="row">
                            <div class="col-md-3 col-xs-3 col-sm-3">
                                <input type="number" class="form-control" placeholder="Dia" name="dateday" value="<?php if(isset($popform['dateday'])) echo $popform['dateday']; ?>">
                            </div>
                            <div class="col-md-5 col-xs-5 col-sm-5" >
                                <select class="form-control" name="datemonth" >
                                    <option <?php if(isset($popform['datemonth']) && $popform['datemonth']==1): ?> selected <?php endif; ?> value="1">Janeiro</option>
                                    <option <?php if(isset($popform['datemonth']) && $popform['datemonth']==2): ?> selected <?php endif; ?> value="2">Fevereiro</option>
                                    <option <?php if(isset($popform['datemonth']) && $popform['datemonth']==3): ?> selected <?php endif; ?> value="3">Março</option>
                                    <option <?php if(isset($popform['datemonth']) && $popform['datemonth']==4): ?> selected <?php endif; ?> value="4">Abril</option>
                                    <option <?php if(isset($popform['datemonth']) && $popform['datemonth']==5): ?> selected <?php endif; ?> value="5">Maio</option>
                                    <option <?php if(isset($popform['datemonth']) && $popform['datemonth']==6): ?> selected <?php endif; ?> value="6">Junho</option>
                                    <option <?php if(isset($popform['datemonth']) && $popform['datemonth']==7): ?> selected <?php endif; ?> value="7">Julho</option>
                                    <option <?php if(isset($popform['datemonth']) && $popform['datemonth']==8): ?> selected <?php endif; ?> value="8">Agosto</option>
                                    <option <?php if(isset($popform['datemonth']) && $popform['datemonth']==9): ?> selected <?php endif; ?> value="9">Setembro</option>
                                    <option <?php if(isset($popform['datemonth']) && $popform['datemonth']==10): ?> selected <?php endif; ?> value="10">Outubro</option>
                                    <option <?php if(isset($popform['datemonth']) && $popform['datemonth']==11): ?> selected <?php endif; ?> value="11">Novembro</option>
                                    <option <?php if(isset($popform['datemonth']) && $popform['datemonth']==12): ?> selected <?php endif; ?> value="12">Dezembro</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-xs-4 col-sm-4">
                                <input type="number" class="form-control" placeholder="Ano" name="dateyear" value="<?php if(isset($popform['dateyear'])) echo $popform['dateyear']; ?>">
                            </div>
                        </div>
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['dateday']; ?> <?php echo $validation['datemonth']; ?> <?php echo $validation['dateyear']; ?> <?php endif; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Horário de Início *</label>
                        <input type="text" id="schedule-paper-form-start-hour" class="form-control" placeholder="Horário de Início" name="starthour" value="<?php if(isset($popform['starthour'])) echo $popform['starthour']; ?>" />
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['starthour']; ?> <?php endif; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Horário de Término *</label>
                        <input type="text" id="schedule-paper-form-end-hour" class="form-control" placeholder="Horário de Término" name="endhour" value="<?php if(isset($popform['endhour'])) echo $popform['endhour']; ?>"/>
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['endhour']; ?> <?php endif; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Local *</label>
                        <input type="text" class="form-control" placeholder="Local" name="local" value="<?php if(isset($popform['local'])) echo $popform['local']; ?>"/>
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['local']; ?> <?php endif; ?></p>
                    </div>

                </div>
                <div class="col-md-12 col-xs-12 col-sm-12">
                    <button type="submit" class="btn btn-block btn-large btn-success"> <i class="fa fa-plus-circle"></i> Adicionar Registro</button>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12 success-container text-center">
                    <div class="success"></div>
                </div>

            </div>
        </form>
        
		

    </div>
</div>

<div style="margin-top:20px;" class="row">
	<div class="col-lg-12">
		<h3>Registros de 'Outras Atividades' na Programação</h3>

        <table class="table table-hover">

        <?php foreach ($records as $rec): ?>

            <tr>
                <td>
                    <b><?php echo $rec->title; ?></b>
                </td>
                <td><b><?php echo date("d/m/Y",strtotime($rec->date)); ?></b></td>
                <td><b><?php echo $rec->starthour; ?> - <?php echo $rec->endhour; ?> </b></td>
                <td>
                    <a style="cursor:pointer;" class="schedule-record-remove-button" data-data="<?php echo $rec->id;  ?>">Remover</a></td>
                    <?php echo form_open(base_url('dashboard/schedule/removerecord'), array('id' => 'formScheduleRecordRemove-'.$rec->id,'novalidate' => '','class' => 'waiting','style' => 'display:inline-block;')); ?>
                        <input style="display:none;" name="id" value="<?php echo $rec->id; ?>" />
                    </form> 
                </td>
            </tr>

        <?php endforeach; ?>

        </table>

	</div>
</div>