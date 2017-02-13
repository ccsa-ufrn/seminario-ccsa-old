<?php echo form_open(base_url('dashboard/subevent/doexeaddactivity'), array('id' => 'formDoExeAddCustomActivity','novalidate' => '','class'=>'waiting')); ?>

	<div class="form-group">
		<label>Nome</label>
		<p><?php echo $ac->title; ?></p>
	</div>

	<div class="form-group">
		<label>Descrição</label>
		<textarea class="form-control" name="description" placeholder="Descrição"></textarea>
		<p class="help-block">Em descrição, adicione local, propositor, horário, data, etc.</p>
	</div>

	<input style="display:none;" name="acid" value="<?php echo $ac->id; ?>" >
	<input style="display:none;" name="subid" value="<?php echo $subevent->id; ?>" >
	<input style="display:none;" name="type" value="<?php echo $type; ?>" >

	<div class="form-group">
		<button type="submit" class="btn btn-success">Adicionar Atividade</button>
	</div>

</form>