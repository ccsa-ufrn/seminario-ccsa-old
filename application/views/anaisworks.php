<div class="row">
	<div class="col-lg-12">

		<h2 style="font-weight:bold;color:#c1494b;">ATIVDADES</h2>

		<h3 style="font-weight:bold;color:#c1494b;margin:40px 0px;">MINICURSOS</h3>	

		<table class="table table-striped text-center" style="background-color:white;">
			<?php foreach ($mcs as $mc): ?>
				<tr>
					<td><b><?php echo $mc->title; ?></b></td>
					<td><?php echo $mc->expositor; ?></td>
				</tr>
			<?php endforeach; ?>
		</table>

		<h3 style="font-weight:bold;color:#c1494b;margin:40px 0px;">OFICINAS</h3>	

		<table class="table table-striped text-center" style="background-color:white;">
			<?php foreach ($wss as $ws): ?>
				<tr>
					<td><b><?php echo $ws->title; ?></b></td>
					<td><?php echo $ws->expositor; ?></td>
				</tr>
			<?php endforeach; ?>
		</table>

		<h3 style="font-weight:bold;color:#c1494b;margin:40px 0px;">CONFERÊNCIAS</h3>	

		<table class="table table-striped text-center" style="background-color:white;">
			<?php foreach ($cfs as $cf): ?>
				<tr>
					<td><b><?php echo $cf->title; ?></b></td>
					<td><?php echo $cf->lecturer; ?></td>
				</tr>
			<?php endforeach; ?>
		</table>

		<h3 style="font-weight:bold;color:#c1494b;margin:40px 0px;">MESA-REDONDA</h3>	

		<table class="table table-striped text-center" style="background-color:white;">
			<?php foreach ($rts as $rt): ?>
				<tr>
					<td><b><?php echo $rt->title; ?></b></td>
					<td><?php echo $rt->coordinator; ?></td>
				</tr>
			<?php endforeach; ?>
		</table>

	</div>
</div>