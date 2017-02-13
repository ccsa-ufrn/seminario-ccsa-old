<?php echo form_open(base_url('configuration/update'), array('id' => '','novalidate' => '', 'class' => 'waiting')); ?>

	<input style="display:none;" name="id" value="<?php echo $config->id; ?>" >

	<div class="form-group">
    	<label for="name"><?php echo $config->nickname; ?> *</label>

		<?php if($config->type=='text'): ?>

		
	        <input type="text" class="form-control" placeholder="<?php echo $config->nickname; ?>" name="value" value="<?php echo $config->value; ?>"/>

	    <?php elseif($config->type=='date'): ?>

	    	<input type="text" class="form-control input-date-format" placeholder="<?php echo $config->nickname; ?>" name="value" value="<?php echo date("d/m/Y", strtotime($config->value));  ?>"/>

		<?php elseif($config->type=='boolean'): ?>

	    	<input type="checkbox" name="value" <?php if($config->value=='true'): ?> checked <?php endif; ?> >

		<?php endif; ?>

		<script type="text/javascript">

			jQuery(function($){
    
			   $(".input-date-format").mask("99/99/9999");
			    
			});

		</script>

		<br>

		<button class="btn btn-default btn-success">Salvar</button>
		<button type="button" class="btn btn-default btn-danger" data-dismiss="modal">Cancelar</button>

	</div>

</form>