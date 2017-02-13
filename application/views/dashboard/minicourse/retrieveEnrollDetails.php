<p><b>Título:</b> <?php echo $mc->title; ?></p>
<p><b>Ementa:</b> <?php echo $mc->syllabus; ?></p>
<p><b>Programa:</b> <a href="<?php echo base_url('assets/upload').'/'.$mc->program; ?>" target="_blank">Ver programa</a></p>
<?php 
	$shift = '';
	foreach($mc->sharedMinicoursedayshiftList as $mds){
		$shift = $mds->shift;
		break;
	} 
?>
<p><b>Turno:</b> <?php echo $shift; ?></p>
<p><b>Quantidade de Vagas:</b> <?php echo $mc->consolidatedvacancies; ?></p>
<p><b>Local:</b> <?php echo $mc->consolidatedlocal; ?></p>
<p><b>Data:</b>
<?php $i = 0; foreach($mc->sharedMinicoursedayshiftList as $mds): ?>
	<?php if($i) echo ', '; ?>
	 <?php echo date("d/m/Y", strtotime($mds->date)); ++$i; ?>
<?php endforeach; ?>
</p>
<p><b>Propositor:</b> <?php echo $mc->user->name; ?></p>