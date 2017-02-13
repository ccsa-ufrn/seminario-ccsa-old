<h1><?php echo $gt->name; ?></h1>
<?php $coords = $gt->sharedUserList; ?>
<p class="coordinators">
    <?php $i = 0; ?>
    <?php foreach($gt->sharedUserList as $g): ?>
        <?php if($i++!=0) echo ", "; ?> 
        <?php echo $g->name; ?>
    <?php endforeach; ?>
</p>
<p class="syllabus"><?php echo $gt->syllabus; ?></p>