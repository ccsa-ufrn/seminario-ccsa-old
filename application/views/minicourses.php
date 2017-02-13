<header class="logo">
    
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-8 col-sm-offset-2">
            <image class="logo" width="100%" src="" />
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">
            <h1>MINICURSOS</h1>
        </div>
    </div>

</header>

<section class="minicourses-body">    

    <h1 class="like">MANHÃ (7:30h às 9:10h)</h1>

    <?php if(isset($mdsm)): ?>

        <?php if (count($mdsm->sharedMinicourseList)): ?>
            <?php foreach ($mdsm->with('ORDER BY title')->sharedMinicourseList as $o): ?>

                <?php 

                    $exps = $o->expositor;
                    $data = explode('||',$exps);
                    $ffinal = implode(', ',$data);

                ?>

                <p style="text-transform:uppercase;"><b><?php echo $o->title; ?></b> <?php echo $ffinal; ?></p>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php else: ?>

        <p class="text-danger">Ainda não temos programação definida para este turno.</p>

    <?php endif; ?>

    <h1 class="like">TARDE (13:30h às 15:10h)</h1>

    <?php if(isset($mdsv)): ?>

        <?php if (count($mdsv->sharedMinicourseList)): ?>
            <?php foreach ($mdsv->with('ORDER BY title')->sharedMinicourseList as $o): ?>

                <?php 

                    $exps = $o->expositor;
                    $data = explode('||',$exps);
                    $ffinal = implode(', ',$data);

                ?>

                <p style="text-transform:uppercase;"><b><?php echo $o->title; ?></b> <?php echo $ffinal; ?></p>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php else: ?>

        <p class="text-danger">Ainda não temos programação definida para este turno.</p>

    <?php endif; ?>
    

    <h1 class="like">NOITE (18:30h às 20:10h)</h1>

    <?php if(isset($mdsn)): ?>

        <?php if (count($mdsn->sharedMinicourseList)): ?>
            <?php foreach ($mdsn->with('ORDER BY title')->sharedMinicourseList as $o): ?>

                <?php 

                    $exps = $o->expositor;
                    $data = explode('||',$exps);
                    $ffinal = implode(', ',$data);

                ?>

                <p style="text-transform:uppercase;"><b><?php echo $o->title; ?></b> <?php echo $ffinal; ?></p>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php else: ?>

        <p class="text-danger">Ainda não temos programação definida para este turno.</p>

    <?php endif; ?>

</section>


