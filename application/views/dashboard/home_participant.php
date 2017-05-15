<div class="home-participant">
    <div class="row" style="padding-top:20px;">
        <div class="col-lg-12">
            <div class="row">
              <div class="col-lg-12">
                <div id='calendar'></div>
              </div>
            </div>

            <div id="fullCalModal" class="modal fade">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                    <h4 id="modalTitle" class="modal-title"></h4>
                    <h6 id="modalShift"></h5>
                  </div>
                  <div class="modal-body">
                    <p id="modalBody"></p>
                    <p id="modalLocal"></p>
                    <p id="modalAuthors"></p>
                    <p id="modalCoordinator"></p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

            <script type="text/javascript">
              var events = [

                <?php

                /** CARREGANDO OS MINICURSOS */
                foreach($user->sharedMinicourseList as $mc) {

                  foreach($mc->sharedMinicoursedayshiftList as $mds) {
                    echo "{";
                    echo "title:'".$mc->title."',";
                    echo "description:'".str_replace(array("\r", "\n"), '', $mc->syllabus)."',";
                    echo "shift:'".$mds->shift."',";
                    echo "local:'".str_replace(array("\r", "\n"), '', $mc->consolidatedlocal)."',";
                    echo "expositor:'".str_replace(array("\r", "\n"), '', $mc->expositor)."',";
                    echo "start:'".$mds->date."',";
                    echo "},";
                  }

                }

                /** CARREGANDO OS CONFERÊNCIAS */
                foreach($user->sharedConferenceList as $mc) {

                  $cds = $mc->conferencedayshift;

                  echo "{";
                  echo "title:'".$mc->title."',";
                  echo "local:'".str_replace(array("\r", "\n"), '', $mc->local)."',";
                  echo "shift:'".str_replace(array("\r", "\n"), '', $cds->shift)."',";
                  echo "expositor:'".str_replace(array("\r", "\n"), '', $mc->lecturer)."',";
                  echo "description:'".str_replace(array("\r", "\n"), '', $mc->proposal)."',";
                  echo "start:'".$cds->date."',";
                  echo "},";

                }

                /** CARREGANDO OS OFICINAS */
                foreach($user->sharedWorkshopList as $mc) {

                  foreach($mc->sharedWorkshopdayshiftList as $mds) {
                    echo "{";
                    echo "title:'".$mc->title."',";
                    echo "shift:'".$mds->shift."',";
                    echo "local:'".str_replace(array("\r", "\n"), '', $mc->consolidatedlocal)."',";
                    echo "expositor:'".str_replace(array("\r", "\n"), '', $mc->expositor)."',";
                    echo "description:'".str_replace(array("\r", "\n"), '', $mc->syllabus)."',";
                    echo "start:'".$mds->date."',";
                    echo "},";
                  }

                }

                /** CARREGANDO OS MESAS REDONDAS */
                foreach($user->sharedRoundtableList as $mc) {

                  $cds = $mc->roundtabledayshift;

                  echo "{";
                  echo "title:'".$mc->title."',";
                  echo "description:'".str_replace(array("\r", "\n"), '', $mc->proposal)."',";
                  echo "local:'".str_replace(array("\r", "\n"), '', $mc->consolidatedlocal)."',";
                  echo "shift:'".str_replace(array("\r", "\n"), '', $cds->shift)."',";
                  echo "authors:'".str_replace(array("\r", "\n"), '', $mc->authors)."',";
                  echo "coordinator:'".str_replace(array("\r", "\n"), '', $mc->authors)."',";
                  echo "start:'".$cds->date."',";
                  echo "},";

                }

              ?>

              ]
            </script>

            <div class="row">
              <div class="col-lg-12">
                asdas
              </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 pull-right">
                    <?php if($user->paid=='no'): ?>
                        <div class="notification" style="background-color:#FFA319;border-color:#FFBF5E;">
                            <i class="fa fa-exclamation-triangle"></i>
                            <h1>ATENÇÃO</h1>
                            <p>Você ainda não realizou o <b>pagamento.</b></p>
                        </div>
                    <?php elseif($user->paid=='pendent'): ?>
                        <div class="notification" style="background-color:#FFA319;border-color:#FFBF5E;">
                            <i class="fa fa-exclamation-triangle"></i>
                            <h1>ATENÇÃO</h1>
                            <p>Seu <b>pagamento</b> está esperando a avaliação.</p>
                        </div>
                    <?php elseif($user->paid=='accepted'): ?>
                        <div class="notification" style="background-color:#2EB82E;border-color:#259325;">
                            <i class="fa fa-check-circle"></i>
                            <h1>ATENÇÃO</h1>
                            <p>Seu <b>pagamento</b> foi aceito.</p>
                        </div>
                    <?php elseif($user->paid=='free'): ?>
                        <div class="notification" style="background-color:#2EB82E;border-color:#259325;">
                            <i class="fa fa-check-circle"></i>
                            <h1>ATENÇÃO</h1>
                            <p>Você foi <b>isento</b> do pagamento.</p>
                        </div>
                    <?php elseif($user->paid=='rejected'): ?>
                        <div class="notification" style="background-color:#E60000;border-color:#B80000;">
                            <i class="fa fa-exclamation-triangle"></i>
                            <h1>ATENÇÃO</h1>
                            <p>Seu <b>comprovante de pagamento</b> foi rejeitado, envie um comprovante válido.</p>
                        </div>
                    <?php endif;
?>
                    <?php if($user->retrievepass=='yes'): ?>
                        <div class="notification" style="background-color:#FFA319;border-color:#FFBF5E;">
                            <i class="fa fa-exclamation-triangle"></i>
                            <h1>ATENÇÃO</h1>
                            <p>É importante que você modifique a senha recentemente recuperada.</p>
                        </div>
                    <?php endif;
?>
                </div>
                <div class="col-lg-9 col-md-8 pull-left">
                    <section class="news">
                        <h1>Últimas notícias</h1>
                        <?php if(!count($news)): ?>
                            <p class="text-danger">Não há notícias recentes.</p>
                        <?php endif;
?>
                        <?php foreach($news as $n): ?>
                            <article>
                                        <h1><a href="<?php echo base_url('news#'.$n->id);
?>" target="_blank"><i class="fa fa-external-link"></i> <?php echo $n->title;
?></a></h1>
                                        <p><a href="<?php echo base_url('news#'.$n->id);
?>" target="_blank"><?php echo character_limiter(strip_tags($n->text),400);
?></a></p>
                            </article>
                        <?php endforeach;
?>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
