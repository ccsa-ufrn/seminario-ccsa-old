<div class="page-news">
    
    <section class="news-list">
    
        <?php foreach($news as $n): ?>
        
            <div class="news">
                <h1>
                    <a name="<?php echo $n->id; ?>">
                        <?php echo $n->title; ?>
                        <?php if ( $n->wasFixed == 'Y' ) : ?>
                            <i style="color:red;" class="fa fa-star"></i>
                        <?php endif; ?>
                    </a> 
                    <span>
                        <?php echo date("d/m/Y", strtotime($n->createdAt));  ?>
                    </span>
                </h1>
                <?php echo $n->text; ?>
            </div>
            
        <?php endforeach; ?>
        
    </section>
    
</div>

</div>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-74320903-1', 'auto');
  ga('send', 'pageview');

</script>