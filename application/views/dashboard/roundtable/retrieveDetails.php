<h3>Mesa-Redonda</h3>
<hr/>
<p><b>Título:</b> <?php echo $rt->title; ?></p>
<p><b>Proposta:</b> <?php echo $rt->proposal; ?></p>
<p><b>Coordenador:</b> <?php echo $rt->coordinator; ?></p>
<p><b>Debatedores:</b> <?php echo $rt->debaters; ?></p>
<p><b>Proposta:</b> <?php echo $rt->proposal; ?></p>
<p><b>Turno Preferido:</b> <?php echo $rt->shift; ?></p>

<h3>Propositor</h3>
<hr/>
<p><b>Nome:</b> <?php echo $rt->user->name; ?></p>
<p><b>Email:</b> <?php echo $rt->user->email; ?></p>
<p><b>Telefone:</b> <?php echo $rt->user->phone; ?></p>