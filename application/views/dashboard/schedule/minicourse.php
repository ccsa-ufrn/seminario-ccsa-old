<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Programação de Minicursos</h1>

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

        <?php echo form_open(base_url('dashboard/schedule/minicourse/newrecord'), array('id' => 'formScheduleMinicourseNewRecord', 'novalidate' => '','class'=>'waiting form-add-record-behavior')); ?>

            <div class="row">
                <div class="col-md-12"><h3>Adicionar Minicurso à Programação</h3></div>
                <div class="col-md-12">

                    <div class="form-group">
                        <label>Minicurso *</label>
                        <select name="minicourse" class="form-control" style="text-transform:uppercase;">
					  		<?php foreach ($mcs as $mc): ?>
                                <option <?php if($popform['minicourse']==$mc->id): ?> selected="true" <?php endif; ?> value="<?php echo $mc->id; ?>"><?php echo $mc->title; ?></option>
                            <?php endforeach ?>
						</select>
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['minicourse']; ?> <?php endif; ?></p>
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

                    <!-- <div class="form-group">
                        <label>Local *</label>
                        <input type="text" class="form-control" placeholder="Local" name="local" value="<?php if(isset($popform['local'])) echo $popform['local']; ?>"/>
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['local']; ?> <?php endif; ?></p>
                    </div> -->

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
		<h3>Registros de Minicursos na Programação</h3>

        <table class="table table-hover">

        <?php foreach ($records as $rec): ?>

            <tr>
                <td>
                    <b><?php echo $rec->minicourse->title; ?></b>
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