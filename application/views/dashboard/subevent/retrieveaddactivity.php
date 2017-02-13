<h4>Adicionar Atividade</h4>
<hr>

<div class="modal-activity-choose-type" style="text-align:center;">
	<button type="button" class="btn-add-activity-exe btn btn-primary btn-lg" > Adicionar Atividade Existente</button>
	<button type="button" class="btn-add-activity-custom btn btn-primary btn-lg" > Adicionar Atividade Customizada</button>
</div>

<div class="modal-activity-add-exe" style="display:none;">

	<label>Pesquisar</label>
	<input class="form-control search" type="text" placeholder="Pesquisar" >
	<br>

	<input type="radio" name="filter" class="filter" value="all" checked> Todos 
	<input type="radio" name="filter" class="filter" value="minicourses" > Minicursos
	<input type="radio" name="filter" class="filter" value="conferences" > Conferências
	<input type="radio" name="filter" class="filter" value="workshops" > Oficinas
	<input type="radio" name="filter" class="filter" value="roundtables" > Mesas Redondas

	<input class="form-control subevent" style="display:none;" type="text" value="<?php echo $subevent->id; ?>" >

	<br>

	<hr>

	<div class="retrieve-subevent-activities-results">
		
		<table class="table table-hover">

            <tr class="danger">
                <td>Realize uma pesquisa para retornar os resultados.</td>
            </tr>

        </table>

	</div>
		
</div>

<div class="modal-activity-add-custom" style="display:none;" >

	<?php echo form_open(base_url('dashboard/subevent/addcustomactivity'), array('id' => 'formAddCustomActivity','novalidate' => '','class'=>'waiting')); ?>

		<div class="form-group">
			<label>Nome</label>
			<input class="form-control" name="name" placeholder="Nome" type="text" >
		</div>

		<div class="form-group">
			<label>Descrição</label>
			<textarea class="form-control" name="description" placeholder="Descrição"></textarea>
			<p class="help-block">Em descrição, adicione local, propositor, horário, data, etc.</p>
		</div>

		<input style="display:none;" name="id" value="<?php echo $subevent->id; ?>" >

		<div class="form-group">
			<button type="submit" class="btn btn-success">Adicionar Atividade</button>
		</div>

	</form>
		
</div>

<script type="text/javascript">

	$('.btn-add-activity-exe').click(function(){
		$('.modal-activity-choose-type').fadeOut(0);
		$('.modal-activity-add-exe').fadeIn(400);
	});

	$('.btn-add-activity-custom').click(function(){
		$('.modal-activity-choose-type').fadeOut(0);
		$('.modal-activity-add-custom').fadeIn(400);
	});

</script>
