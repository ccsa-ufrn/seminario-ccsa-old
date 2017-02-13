

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Submeter Oficina</h1>

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
        
        <h4 class="page-header">Oficinas Submetidos</h4>
        
        <table class="table table-hover">
        <!-- Falta centralizar verticalmente o texto -->    
        <?php foreach($workshops as $ws): ?>
            
            <tr class="active text-center">
                
            <td style="width:40%;"><b><?php echo $ws->title; ?></b></td>
            <td>Enviado em: <?php echo date("d/m/Y", strtotime($ws->createdAt));  ?> </td>
            
            <?php if($ws->consolidated=='no'): ?>
                <td class="text-warning">Esperando Avaliação</td>
                <?php $limit = R::findOne('configuration','name="max_date_workshop_submission"'); ?>
                <?php if(dateleq(mdate('%Y-%m-%d'),$limit->value)): ?>
                <td><a style="cursor:pointer;" class="workshop-cancel-submission" data-data="<?php echo $ws->id;  ?>">Cancelar Submissão</a></td>
                <?php echo form_open(base_url('dashboard/workshop/cancelsubmission'), array('id' => 'formWorkshopCancelSubmission-'.$ws->id,'novalidate' => '','class' => 'waiting','style' => 'display:inline-block;')); ?>
                    <input style="display:none;" name="id" value="<?php echo $ws->id; ?>" />
                </form> 
                <?php endif; ?>
            <?php elseif($ws->consolidated=='yes'): ?>
                <td class="text-success">Oficina Consolidada/Aceita</td>
                <td></td>
            <?php endif; ?>
        
            </tr>        
            
        <?php endforeach; ?>
    
        <?php if(!count($workshops)): ?>
        <tr class="text-center active">
            <td><span class="text-danger">Você ainda não submeteu nenhuma oficina.</span></td>
        </tr>
        <?php endif; ?>
        
        </table>

        <h4 class="page-header">Submeter Oficina</h4>
        

        <?php if($date_limit['open']): ?>
        <p class="text-danger">Data limite de submissão: <?php echo date("d/M/Y", strtotime($date_limit['config']->value));  ?></p>
        <?php echo form_open(base_url('dashboard/workshop/create'), array('id' => 'formCreateWorkshop','novalidate' => '','class' => 'waiting')); ?>

            <div class="row">
                <div class="col-md-6">

                    <div class="form-group">
                    	<label for="file">Título *</label>
                        <input type="text" class="form-control" placeholder="Título" name="title" value="<?php echo $popform['title']; ?>" />
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['title']; ?> <?php endif; ?></p>
                    </div>

                    <div class="form-group">
                    	<label for="file">Turno *</label>
                        <select class="form-control" name="shift">
					  		<option <?php if($popform['shift']=='matutino'): ?> selected="true" <?php endif; ?> value="matutino">Matutino</option>
					  		<option <?php if($popform['shift']=='vespertino'): ?> selected="true" <?php endif; ?> value="vespertino">Vespertino</option>
					  		<option <?php if($popform['shift']=='noturno'): ?> selected="true" <?php endif; ?> value="noturno">Noturno</option>
						</select>
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['shift']; ?> <?php endif; ?></p>
                    </div>
                    
                    <div class="form-group">
                        <label for="file">Ementa *</label>
                        <textarea rows="5" class="form-control" placeholder="Ementa" name="syllabus" ><?php echo $popform['syllabus']; ?></textarea>
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['syllabus']; ?> <?php endif; ?></p>
                    </div>

                    <div class="form-group">
                        <label for="file">Objetivos *</label>
                        <textarea rows="5" class="form-control" placeholder="Objetivos" name="objectives" ><?php echo $popform['objectives']; ?></textarea>
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['objectives']; ?> <?php endif; ?></p>
                    </div>

                </div>
                <div class="col-md-6">
                    
                    <div class="form-group">
                        <label for="file">Quantidade de Vagas *</label>
                        <input type="number" class="form-control" placeholder="Quantidade de Vagas" name="vacancies" value="<?php echo $popform['vacancies']; ?>" />
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['vacancies']; ?> <?php endif; ?></p>
                    </div>

                    <div class="form-group">
                        <label for="file">Recursos *</label>
                        <textarea rows="5" class="form-control" placeholder="Recursos" name="resources" ><?php echo $popform['resources']; ?></textarea>
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['resources']; ?> <?php endif; ?></p>
                    </div>
                    
                    <div class="form-group">
                        <label for="file">Programa *</label>
						<input id="programuploaderworkshop" type="file" name="userfile" data-url="<?php echo base_url(); ?>dashboard/workshop/uploadprogram" />
                        <figure class="loading" style="display:none;font-size:12;margin-top:0px;"><img  src="<?php echo asset_url(); ?>img/loading.gif" /> Carregando, aguarde... </figure>
                        <!-- There was a problem, so i used an ugly hack to solve it -->
                         <input style="width:0px;height:0px;border:none;position:absolute;top:-200px;" type="text" name="program" value="" />
                        <p class="file-desc text-success"></p>
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['program']; ?> <?php endif; ?></p>
                    </div>
                    
                    <div class="form-group">
                        <label>Expositor(es) *</label>
                        <br/>
                        <button class="btn btn-default" data-toggle="modal" data-target=".modal-workshop-add-author" type="button" style="margin-bottom:10px;">Adicionar Expositor</button>
                        <textarea readOnly rows="4" class="form-control" placeholder="Nenhum autor adicionado" name="authors" ><?php echo $popform['authors']; ?></textarea>
                        <a class="reset-authors" href="javascript:void(0);">Resetar</a>
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['authors']; ?> <?php endif; ?></p>
                    </div>
                    
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12 text-center success-container">
                    <div class="success"></div>
                    <button type="submit" class="btn btn-lg btn-success">Submeter Oficina</button>
                </div>
            </div>

        </form>
        
        <?php else: ?>
    
        <p class="text-danger">A data limite de submissão (<?php echo date("d/M/Y", strtotime($date_limit['config']->value));  ?>) foi atingida, não há possibilidades de submissão.</p>
    
        <?php endif; ?>
    
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="modal fade modal-workshop-add-author" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
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
            <button class="btn btn-default" >Adicionar Expositor</button>
        </div>
        
    </div>
  </div>
</div>