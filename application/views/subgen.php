<section class="gen-subevent">

	<h1 class="like"><?php echo $sbv->title; ?></h1>

	<?php foreach ($sbv->ownSubeventitemList as $l): ?>
		<h2>
			<?php if($l->type=='custom'): ?>
				<?php echo $l->name; ?>
			<?php elseif($l->type=='minicourse'): ?>
				<?php echo $l->minicourse->title; ?>
			<?php elseif($l->type=='conference'): ?>
				<?php echo $l->conference->title; ?>
			<?php elseif($l->type=='roundtable'): ?>
				<?php echo $l->roundtable->title; ?>
			<?php elseif($l->type=='workshop'): ?>
				<?php echo $l->workshop->title; ?>
			<?php endif; ?>
		</h2>
		<p><?php echo $l->description; ?></p>
	<?php endforeach; ?>
	
</section>