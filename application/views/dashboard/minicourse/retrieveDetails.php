<h3>Minicurso</h3>
<hr/>
<p><b>Título:</b> <?php echo $mc->title; ?></p>
<p><b>Ementa:</b> <?php echo $mc->syllabus; ?></p>
<p><b>Recursos:</b> <?php echo $mc->resources; ?></p>
<p><b>Programa:</b> <a href="<?php echo base_url('assets/upload').'/'.$mc->program; ?>" target="_blank">Ver programa</a></p>
<p><b>Turno Preferido:</b> <?php echo $mc->shift; ?></p>
<p><b>Quantidade de Vagas Preferida:</b> <?php echo $mc->vacancies; ?></p>

<h3>Propositor</h3>
<hr/>
<p><b>Nome:</b> <?php echo $mc->user->name; ?></p>
<p><b>Email:</b> <?php echo $mc->user->email; ?></p>
<p><b>Telefone:</b> <?php echo $mc->user->phone; ?></p>