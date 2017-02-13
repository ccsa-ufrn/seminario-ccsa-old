<div class="home-participant">
    <div class="row" style="padding-top:20px;">
        <div class="col-lg-12">

            <div class="row">
                
                <div class="col-lg-12">
                    
                    <section class="news">
                        <h1>Últimas notícias</h1>
                        <?php if(!count($news)): ?>
                            <p class="text-danger">Não há notícias recentes.</p>
                        <?php endif; ?>
                        
                        <?php foreach($news as $n): ?>
                            <article>
                                        <h1><a href="<?php echo base_url('news#'.$n->id); ?>" target="_blank"><i class="fa fa-external-link"></i> <?php echo $n->title; ?></a></h1>
                                        <p><a href="<?php echo base_url('news#'.$n->id); ?>" target="_blank"><?php echo character_limiter(strip_tags($n->text),400); ?></a></p>
                            </article>
                        <?php endforeach; ?>
                    </section>
                    
                </div>
                
            </div>

        </div>
    </div>
</div>