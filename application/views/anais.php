<div class="row">
	<div class="col-lg-12">

		<h2 style="font-weight:bold;color:#c1494b;">ANAIS DO XXI SEMINÁRIO DE PESQUISA DO CCSA</h2>

		<!-- <span class="text-center" style="display:block;margin-top:40px;">
			<a  class="btn btn-lg btn-info" href="<?php echo base_url('anaisworks'); ?>" > Visualizar as Atividades</a>
		</span> -->

		<h3 style="font-weight:bold;color:#c1494b;margin:40px 0px;">ARTIGOS</h3>

		<?php foreach ($tgs as $tg): ?>

			<?php if(count($tg->withCondition(' evaluation = "accepted" AND cernn = "yes" ORDER BY title ASC')->ownPaperList)==0) continue; ?>

			<div class="row">
				<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1" style="color:white;font-weight:bold;padding:10px;background-color:#d45153;text-align:center;" >
					<?php echo $tg->name; ?>
				</div>
			</div>

			<?php $papers = $tg->withCondition(' evaluation = "accepted" AND cernn = "yes" ORDER BY title ASC')->ownPaperList; ?>

			<?php foreach ($papers as $p): ?>

				<div class="row" >
					<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1" style="background-color:white;padding:20px 20px;">
						<div class="row">
							<div class="col-lg-9 col-md-9">
								<h3 style="text-transform:uppercase;font-weight:bold;text-align:justify;"><?php echo $p->title; ?></h3>
								<p>

									<?php 
										$at = explode('||',$p->authors);
                                		$a = implode(', ',$at); 
                                		echo $a;
                                	?>



                                </p>
								<p style="text-align:justify;"><?php echo $p->abstract; ?></p>
							</div>
							<div class="col-lg-3 col-md-3" style="text-align:center;padding-top:20px;">
								<a style="font-size:18px;color:#d45153;" target="_blank" href="<?php echo asset_url(); ?>/upload/papers/<?php echo $p->paper; ?>"><i class="fa fa-download fa-5x"></i><br><span style="font-size:20px;">Baixar Documento</span></a>
							</div>
						</div>
					</div>
				</div>

			<?php endforeach; ?>

			
		<?php endforeach; ?>

		<h3 style="font-weight:bold;color:#87a060;margin:40px 0px;">PÔSTERES</h3>		

		<?php foreach ($tgs as $tg): ?>

			<?php if(count($tg->withCondition(' evaluation = "accepted" AND cernn = "yes" ORDER BY title ASC')->ownPosterList) + count($tg->withCondition(' evaluation = "asPoster" AND asposter = "accepted" AND cernn = "yes" ORDER BY title ASC')->ownPaperList) ==0) continue; ?>

			<div class="row">
				<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1" style="color:white;font-weight:bold;padding:10px;background-color:#87a060;text-align:center;" >
					<?php echo $tg->name; ?>
				</div>
			</div>

			<?php $posters2 = $tg->withCondition(' evaluation = "asPoster" AND asposter = "accepted" AND cernn = "yes" ORDER BY title ASC ')->ownPaperList; ?>

			<?php foreach ($posters2 as $p): ?>

				<div class="row" >
					<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1" style="background-color:white;padding:20px 20px;">
						<div class="row">
							<div class="col-lg-12">
								<h3 style="text-transform:uppercase;font-weight:bold;text-align:justify;"><?php echo $p->title; ?></h3>
								<p>
								<?php 
										$at = explode('||',$p->authors);
                                		$a = implode(', ',$at); 
                                		echo $a;
                                ?>
                                </p>
								<p style="text-align:justify;"><?php echo $p->abstract; ?></p>
							</div>
						</div>
					</div>
				</div>

			<?php endforeach; ?>

			<?php $posters = $tg->withCondition(' evaluation = "accepted" AND cernn = "yes" ORDER BY title ASC')->ownPosterList; ?>

			<?php foreach ($posters as $p): ?>

				<div class="row" >
					<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1" style="background-color:white;padding:20px 20px;">
						<div class="row">
							<div class="col-lg-12">
								<h3 style="text-transform:uppercase;font-weight:bold;text-align:justify;"><?php echo $p->title; ?></h3>
								<p>
								<?php 
										$at = explode('||',$p->authors);
                                		$a = implode(', ',$at); 
                                		echo $a;
                                ?>
                                </p>
								<p style="text-align:justify;"><?php echo $p->abstract; ?></p>
							</div>
						</div>
					</div>
				</div>

			<?php endforeach; ?>

			
		<?php endforeach; ?>


	</div>
</div>
