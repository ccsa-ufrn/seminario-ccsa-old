<h3>Oficina</h3>
<hr/>
<p><b>Título:</b> <?php echo $ws->title; ?></p>
<p><b>Ementa:</b> <?php echo $ws->syllabus; ?></p>
<p><b>Programa:</b> <a href="<?php echo base_url('assets/upload/workshop').'/'.$ws->program; ?>" target="_blank">Ver programa</a></p>
<p><b>Dias:</b> <?php $i = 0; foreach($ws->sharedWorkshopdayshiftList as $mds): ?>
<?php if($i!=0) echo ','; ?>
<?php echo date("d/m/y", strtotime($mds->date))." (".$mds->shift.") "; ++$i; ?>
<?php endforeach; ?></p>