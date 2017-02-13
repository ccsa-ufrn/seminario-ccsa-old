<p><b>Título:</b> <?php echo $rt->title; ?></p>
<p><b>Proposta:</b> <?php echo $rt->proposal; ?></p>
<p><b>Coordenador:</b> <?php echo $rt->coordinator; ?></p>
<p><b>Debatedores:</b> <?php echo $rt->debaters; ?></p>
<p><b>Proposta:</b> <?php echo $rt->proposal; ?></p>
<p><b>Turno:</b> <?php echo $rt->roundtabledayshift->shift; ?></p>
<p><b>Quantidade de Vagas:</b> <?php echo $rt->consolidatedvacancies; ?></p>
<p><b>Data:</b> <?php echo date("d/m/Y", strtotime($rt->roundtabledayshift->date));  ?></p>
<p><b>Local:</b> <?php echo $rt->consolidatedlocal; ?></p>