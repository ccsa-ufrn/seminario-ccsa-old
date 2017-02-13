<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">Inscrever-se em Minicursos</h1>

        <?php if($success!=null): ?>
            <div class='alert alert-success text-center'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <strong><?php echo $success; ?></strong>
            </div>
        <?php endif; ?>

        <?php if($error!=null): ?>
            <div class='alert alert-danger text-center'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <strong><?php echo $error; ?></strong>
            </div>
        <?php endif; ?>
        
        <h4 class="page-header">Meus Minicursos</h4>
        
        <table class="table table-hover">

        <?php foreach($user->sharedMinicourseList as $mc): ?>
            
            <tr class="active" style="text-align:center;" >
                <td style="width:40%;vertical-align: middle;">
                    <b><?php echo $mc->title; ?></b>
                </td>
                <td style="width:20%;vertical-align: middle;">
                    <?php $i = 0; ?>
                    <?php foreach($mc->sharedMinicoursedayshiftList as $mds): ?>
                        <?php if($i!=0) echo ','; ?>
                        <?php echo date("d/m/y", strtotime($mds->date))." (".$mds->shift.") "; ++$i; ?>
                    <?php endforeach; ?>
                </td>
                <td style="vertical-align: middle;">
                    <a style="cursor:pointer;" data-target="#modalEnrollMinicourseDetails" data-toggle="modal" data-data="<?php echo  $mc->id;?>">Detalhes</a>
                </td>
                <td style="vertical-align: middle;">
                    <?php if($date_limit['open']): ?>
                    <a style="cursor:pointer;"  class="minicourse-registration-button-unroll" data-data="<?php echo $mc->id; ?>" >Desfazer Inscrição</a>
                    <?php echo form_open(base_url('dashboard/minicourse/unroll'), array('id' => 'formUnrollMinicourse-'.$mc->id,'novalidate' => '','class' => 'waiting','style' => 'display:inline-block;')); ?>
                        <input style="display:none;" name="id" value="<?php echo $mc->id; ?>" />
                    </form> 
                    <?php endif; ?>
                </td>
            </tr>
        
        <?php endforeach; ?>    
           
        <?php if(!count($user->sharedMinicourseList)): ?>
            <tr class="danger">
                <td>Você ainda não se inscreveu em nenhum minicurso.</td>
            </tr>
        <?php endif; ?>  
            
        </table>
        
        <h4 class="page-header">Inscrever-se em Minicursos</h4>

        <p class="text-danger">Período de Inscrição: <?php echo date("d/m/Y", strtotime($date_limit['inscriptionStart']->value));  ?> - <?php echo date("d/m/Y", strtotime($date_limit['inscriptionEnd']->value));  ?></p>

        <?php if($date_limit['open']): ?>
        
            <div id="rm-days" class="row" style="margin-top:20px;">
                <div class="row">
                    <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">

                        <?php foreach ($cs as $item): ?>

                            <table class="table table-hover">
                                
                                <?php 
                                  
                                    $status = 'vacancies';
                                  
                                    if($item->consolidatedvacanciesfilled==$item->consolidatedvacancies)
                                        $status = 'novacancies';
                                        
                                    if(R::count('minicourseUser','user_id=? AND minicourse_id=?',array($user->id,$item->id)))
                                        $status = 'enrolled';
                                
                                ?>
                                
                                <tr 
                                    <?php if($status=='vacancies'): ?> class="success" <?php endif; ?> 
                                    <?php if($status=='novacancies'): ?> class="danger" <?php endif; ?> 
                                    <?php if($status=='enrolled'): ?> class="info" <?php endif; ?> 
                                style="text-align:center;">
                                    <td style="width:40%;text-transform:uppercase;vertical-align: middle;">
                                        <b><?php echo $item->title; ?></b>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <?php $i = 0; ?>
                                        <?php foreach($item->sharedMinicoursedayshiftList as $mds): ?>
                                            <?php if($i!=0) echo ','; ?>
                                            <?php echo date("d/m/y", strtotime($mds->date))." (".$mds->shift.") "; ++$i; ?>
                                        <?php endforeach; ?>
                                    </td>
                                    <td style="vertical-align: middle;padding:0px 20px;">
                                        <?php echo $item->consolidatedvacancies - $item->consolidatedvacanciesfilled; ?> Vagas
                                    </td>
                                    <td style="vertical-align: middle;padding:0px 20px;">
                                        <a style="cursor:pointer;" data-target="#modalEnrollMinicourseDetails" data-toggle="modal" data-data="<?php echo $item->id; ?>">Detalhes</a>
                                    </td>
                                    <?php if($status=='vacancies'): ?> 
                                        <td style="vertical-align: middle;padding:0px 20px; min-width:170px;">
                                            <a style="cursor:pointer;" class="minicourse-registration-button" data-data="<?php echo $item->id; ?>" >Inscrever-se</a>
                                        </td>
                                        <?php echo form_open(base_url('dashboard/minicourse/enrolla'), array('id' => 'formEnrollMinicourse-'.$item->id,'novalidate' => '','class' => 'waiting','style' => 'display:inline-block;')); ?>
                                            <input style="display:none;" name="id" value="<?php echo $item->id; ?>" />
                                        </form> 
                                    <?php elseif($status=='novacancies'): ?> 
                                        <td style="padding:0px 20px;vertical-align: middle;">
                                            Sem vagas
                                        </td>
                                    <?php elseif($status=='enrolled'): ?> 
                                        <td style="padding:0px 20px;vertical-align: middle;">
                                            Inscrito
                                        </td>
                                    <?php endif; ?> 
                                </tr>

                            </table>

                        <?php endforeach; ?>

                    </div>
                </div>

            </div>

    <?php else: ?>
        <p class="bg-danger" style="padding:20px;text-align:center;font-weight:bold;">
        <?php if( $user->paid=='pendent' ): ?>
            O seu pagamento ainda está sendo avaliado. Em breve você será notificado.
        <?php elseif( $user->paid!='accepted' && $user->paid!='free' ): ?>
            Você precisa realizar <a href="<?php echo base_url('dashboard/user/payment'); ?>">o pagamento</a> para se inscrever em um minicurso.
        <?php elseif(!datebeq(mdate('%Y-%m-%d'),$date_limit['inscriptionStart']->value)): ?>
            O período de inscrições ainda não foi iniciado. O período é de <?php echo date("d/m/Y", strtotime($date_limit['inscriptionStart']->value));  ?> - <?php echo date("d/m/Y", strtotime($date_limit['inscriptionEnd']->value));  ?>.
        <?php elseif(!dateleq(mdate('%Y-%m-%d'),$date_limit['inscriptionEnd']->value)): ?>
            O período de inscrições está encerrado. O período era de <?php echo date("d/m/Y", strtotime($date_limit['inscriptionStart']->value));  ?> - <?php echo date("d/m/Y", strtotime($date_limit['inscriptionEnd']->value));  ?>.
        <?php endif; ?>
        
        </p>
    <?php endif; ?> 

    </div>
</div>

<!-- Modal -->
    <div class="modal fade" id="modalEnrollMinicourseDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Detalhes</h4>
                </div>
                <div class="modal-body">
                    
                    Carregando...
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <input id="mmmrd" style="display:none;" value="<?php echo base_url('dashboard/minicourse/retrieveenrolldetails'); ?>" />