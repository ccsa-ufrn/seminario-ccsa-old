
<?php if($status=='ok'): ?>

	<p style="font-size:20px;text-align:justify;">Você tem <b>certeza</b> que deseja desconsolidar?</p>

	<?php echo form_open(base_url('dashboard/roundtable/deallocate'), array('id' => 'formDeallocateaRoundtable','novalidate' => '','class' => 'waiting')); ?>
	    <input name="id" style="display:none;" value="<?php echo $mc->id; ?>" />
	    <button type="submit" class="btn btn-block btn-success" data-toggle="modal" data-data="<?php echo $mc->id; ?>">
	        CONTINUAR
	    </button>
	</form>

	<a class="btn btn-block btn-danger" style="margin-top:10px;" data-dismiss="modal">CANCELAR</a>

<?php elseif($status=='warning'): ?>

<p style="font-size:20px;text-align:justify;">O período de inscrições já se iniciou, se você <b>desconsolidar</b> 
esta mesa-redonda, você PERDERÁ TODOS os registros de inscrições realizados até este momento. Você deseja realmente 
continuar?</p>

<a class="btn btn-block btn-success" style="margin-bottom:10px;" data-dismiss="modal">CANCELAR</a>

<?php echo form_open(base_url('dashboard/roundtable/deallocate'), array('id' => 'formDeallocateaRoundtable','novalidate' => '','class' => 'waiting')); ?>
    <input name="id" style="display:none;" value="<?php echo $mc->id; ?>" />
    <button type="submit" class="btn btn-block btn-danger" data-toggle="modal" data-data="<?php echo $mc->id; ?>">
        DESEJO CONTINUAR
    </button>
</form>

<?php endif; ?>