<?php if($si->type=='custom'): ?>
	<h3><?php echo $si->name; ?></h3>
<?php elseif($si->type=='minicourse'): ?>
	<h3><?php echo $si->minicourse->title; ?></h3>
<?php elseif($si->type=='conference'): ?>
	<h3><?php echo $si->conference->title; ?></h3>
<?php elseif($si->type=='workshop'): ?>
	<h3><?php echo $si->workshop->title; ?></h3>
<?php elseif($si->type=='roundtable'): ?>
	<h3><?php echo $si->roundtable->title; ?></h3>
<?php endif; ?>

<?php echo form_open(base_url('dashboard/subevent/doupdateactivity'), array('id' => '','novalidate' => '','class'=>'waiting')); ?>

	<div class="form-group">
		<label>Descrição</label>
		<textarea class="form-control" name="description" placeholder="Descrição"><?php echo $si->description; ?></textarea>
		<p class="help-block">Em descrição, adicione local, propositor, horário, data, etc.</p>
	</div>

	<input style="display:none;" name="id" value="<?php echo $si->id; ?>" >

	<div class="form-group">
		<button type="submit" class="btn btn-success">Atualizar Atividade</button>
	</div>

</form>