<section class="activities">

	<h1 class="like">ATIVIDADES DO SEMINÁRIO DE PESQUISA</h1>

	<a href="<?php echo base_url('workshops'); ?>">Oficinas</a>
	<a href="<?php echo base_url('conferences'); ?>">Conferências</a>
	<a href="<?php echo base_url('minicourses'); ?>">Minicursos</a>
	<a href="<?php echo base_url('roundtables'); ?>">Mesas Redondas</a>

	<h1 class="like">SUBEVENTOS</h1>

	<?php foreach ($sbvs as $s): ?>
		<a href="<?php $sa = 'subevent/general/'.$s->id; echo base_url($sa); ?>"><?php echo $s->title; ?></a></li>
	<?php endforeach; ?>
	
</section>