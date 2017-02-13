<p><b>Título:</b> <?php echo $c->title; ?></p>
<p>
    <b>Data: </b>
    <?php echo date("d/m/Y", strtotime($c->conferencedayshift->date));  ?>
     - 
    <?php if($c->conferencedayshift->shift=='matutino'): ?>
        Manhã
    <?php elseif($c->conferencedayshift->shift=='vespertino'): ?>
        Tarde
    <?php else: ?>
        Noite
    <?php endif; ?>
</p>
<p><b>Conferencista:</b> <?php echo $c->lecturer; ?></p>
<p><b>Quantidade de Vagas:</b> <?php echo $c->vacancies; ?></p>
<p><b>Local:</b> <?php echo $c->local; ?></p>
<p><b>Proposta:</b> <?php echo $c->proposal; ?></p>