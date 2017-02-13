<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Trabalhos</h1>
        
        <h3 style="margin-bottom: 20px;">Minicursos</h3>
        
        <?php foreach($minicourses as $m) : ?>
            
            <h5>
                <b><?php echo $m->title; ?></b> (<?php echo $c = count($m->sharedUserList); ?> inscritos)
            </h5>
            
            <p>
                
                <?php $i = 0; foreach($m->with('ORDER BY name ASC')->sharedUserList as $u) : ?>
                
                    <?php echo $u->name; ?> [<?php echo $u->email; ?>] 
                    
                    <?php if($i++ != $c-1) : ?>
                        <?php echo ', '; ?>
                    <?php endif; ?>
                    
                <?php endforeach; ?>
            
            </p>
            
        <?php endforeach; ?>
        
        <h3 style="margin-top: 40px; margin-bottom: 20px;">Oficinas</h3>
        
        <?php foreach($workshops as $w) : ?>
        
            <h5>
                <b><?php echo $w->title; ?></b> (<?php echo $c = count($w->sharedUserList); ?> inscritos)
            </h5>
            
            <p>
                
                <?php $i = 0; foreach($w->with('ORDER BY name ASC')->sharedUserList as $u) : ?>
                
                    <?php echo $u->name; ?> [<?php echo $u->email; ?>] 
                    
                    <?php if($i++ != $c-1) : ?>
                        <?php echo ', '; ?>
                    <?php endif; ?>
                    
                <?php endforeach; ?>
            
            </p>
            
        <?php endforeach; ?>
        
        <h3 style="margin-top: 40px; margin-bottom: 20px;">Mesas-Redondas</h3>
        
        <?php foreach($roundtables as $r) : ?>
            
            <h5>
                <b><?php echo $r->title; ?></b> (<?php echo $c = count($r->sharedUserList); ?> inscritos)
            </h5>
            
            <p>
                
                <?php $i = 0; foreach($m->with('ORDER BY name ASC')->sharedUserList as $u) : ?>
                
                    <?php echo $u->name; ?> [<?php echo $u->email; ?>] 
                    
                    <?php if($i++ != $c-1) : ?>
                        <?php echo ', '; ?>
                    <?php endif; ?>
                    
                <?php endforeach; ?>
            
            </p>
            
        <?php endforeach; ?>
        
    </div>
</div>