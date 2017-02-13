<?php echo form_open(base_url('dashboard/conference/update'), array('id' => 'formUpdateConference','novalidate' => '','class' => 'waiting')); ?>

	<input style="display:none;" value="<?php echo $c->id; ?>" name="id" />

    <div class="row">
        <div class="col-md-12">

            <div class="form-group">
            	<label for="file">Título *</label>
                <input type="text" class="form-control" placeholder="Título" name="title" value="<?php echo $c->title; ?>" />
            </div>
            
            <div class="form-group">
            	<label for="file">Conferencista *</label>
                <br/>
                <a class="button-edit-conference-add-lecturer btn btn-default" style="margin-bottom:10px;">Adicionar Conferencista</a>
                
                <div class="add-edit-lecturer-container" style="display:none;margin:20px;padding:20px;border:1px solid grey;">
                	<label for="file">Nome *</label> 
                	<input class="conference-edit-input-name form-control" placeholder="Nome do Conferencista">
					<br>
                	<label for="file">Instituição *</label> 
                	<input class="conference-edit-input-institution form-control" placeholder="Nome da Instituição">
                	<br>
                	<a class="button-edit-add-lecturer-conference btn btn-default">Adicionar</a>
                </div>

                <input readOnly type="text" class="form-control" placeholder="Conferencista não adicionado" name="lecturer" value="<?php echo $c->lecturer; ?>" />
                <a class="reset-edit-lecturer" href="javascript:void(0);">Resetar</a>
            </div>

            <div class="form-group">
                <label for="file">Dia/Turno *</label>
                <select class="form-control" name="dayshift">
                    
                    <?php foreach ($cdss as $cds): ?>
                            <option <?php if($c->conferencedayshift->id==$cds->id): ?> selected <?php endif; ?> value="<?php echo $cds->id; ?>"><?php echo date("d/m/Y", strtotime($cds->date)); ?> - <?php echo $cds->shift ?> </option>
                    <?php endforeach ?>
                    
                    <?php if(!count($cds)): ?>
                        <option value="-1">Não dias/turnos disponíveis</option>
                    <?php endif; ?>
                    
                </select>
            </div>
            
            <div class="form-group">
            	<label for="file">Quantidade de Vagas *</label>
                <input type="number" class="form-control" placeholder="Quantidade de Vagas" name="vacancies" value="<?php echo $c->vacancies; ?>" />
            </div>

        </div>
        <div class="col-md-12">
            
            <div class="form-group">
            	<label for="file">Local *</label>
                <input type="text" class="form-control" placeholder="Local" name="local" value="<?php echo $c->local; ?>" />
            </div>
            
            <div class="form-group">
            	<label for="file">Proposta *</label>
                <textarea rows="11" class="form-control" placeholder="Proposta" name="proposal"><?php echo $c->proposal; ?></textarea>
            </div>
            
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12 text-center success-container">
            <div class="success"></div>
            <a class="button-edit-conference-submit btn btn-lg btn-success">Atualizar Conferência</a>
        </div>
        
    </div>
</form>