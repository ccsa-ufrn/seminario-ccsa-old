

<?php echo form_open(base_url('dashboard/certificate/acceptworkshop'), array('id' => 'nothing','novalidate' => '','class' => 'waiting')); ?>

<p>Selecione os participantes da atividade que irão receber o certificado.</p>

<table class="table table-striped table-bordered table-condensed" style="text-align:center;">

<tr>
	<th>Certificação?</th>
	<th>Nome</th>
</tr>

<?php foreach($mc->with('ORDER BY name ASC')->sharedUserList as $user): ?>
	<tr>
		<td><input type="checkbox" name="users[]" checked value="<?php echo $user->id; ?>"></td>
		<td style="text-transform:uppercase;"><?php echo $user->name; ?></td>
	</tr>
<?php endforeach; ?>

</table>

<input style="display:none;" name="id" value="<?php echo $mc->id; ?>" >

<button class="btn btn-success" type="submit">Finalizar Seleção</button>

</form>