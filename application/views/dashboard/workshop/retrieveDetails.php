<h3>Oficina</h3>
<hr/>
<p><b>Título:</b> <?php echo $ws->title; ?></p>
<p><b>Ementa:</b> <?php echo $ws->syllabus; ?></p>
<p><b>Recursos:</b> <?php echo $ws->resources; ?></p>
<p><b>Programa:</b> <a href="<?php echo base_url('assets/upload/workshop').'/'.$ws->program; ?>" target="_blank">Ver programa</a></p>
<p><b>Turno Preferido:</b> <?php echo $ws->shift; ?></p>
<p><b>Quantidade de Vagas Preferida:</b> <?php echo $ws->vacancies; ?></p>

<h3>Propositor</h3>
<hr/>
<p><b>Nome:</b> <?php echo $ws->user->name; ?></p>
<p><b>Email:</b> <?php echo $ws->user->email; ?></p>
<p><b>Telefone:</b> <?php echo $ws->user->phone; ?></p>