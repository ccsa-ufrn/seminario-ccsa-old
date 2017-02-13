<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Casos para Ensino - Certificação e Anais</h1>

                    <?php if($success!=null): ?>
                        <div class='alert alert-success'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                            <strong><?php echo $success; ?></strong>
                        </div>
                    <?php endif; ?>

                    <?php if($error!=null): ?>
                        <div class='alert alert-danger'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                            <strong><?php echo $error; ?></strong>
                        </div>
                    <?php endif; ?>

                    <h2>Avaliação de Casos para Ensino para Anais e Certificação</h2>
                    
                    <table id="tablePendingPapers" class="table table-striped table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th colspan="2" style="width:40%;">Certificação e Anais</th>
                            </tr>
                        </thead>
                        <tbody>
                    
                            <?php foreach ($teachingcases as $tc): ?>
                                
                                    <tr id="tr-teachingcases-<?php echo $tc->id; ?>">
                                        <td class="name"><?php echo $tc->title; ?></td>
                                        <td class="editar" style="width:20%;text-align:center;">
                                            <a class="certificate-teachingcase-accept" style="color:green;cursor:pointer;" data-value="<?php echo $tc->id; ?>">Aceitar</a>
                                            <?php echo form_open(base_url('dashboard/certificate/acceptteachingcase'), array('id' => 'formAcceptCertificate-'.$tc->id,'novalidate' => '','class' => 'waiting')); ?>
                                                <input style="display:none;" type="text" name="id" value="<?php echo $tc->id; ?>">
                                            </form>
                                        </td>
                                        <td class="editar" style="width:20%;text-align:center;">
                                            <a class="certificate-teachingcase-reject" style="color:red;cursor:pointer;" data-value="<?php echo $tc->id; ?>">Rejeitar</a>
                                            <?php echo form_open(base_url('dashboard/certificate/rejectteachingcase'), array('id' => 'formRejectCertificate-'.$tc->id,'novalidate' => '','class' => 'waiting')); ?>
                                                <input style="display:none;" type="text" name="id" value="<?php echo $tc->id; ?>">
                                            </form>
                                        </td>
                                    </tr>

                                    <?php if(!count($teachingcases)): ?>
                                        <tr>
                                            <td colspan="2">Não há nenhum caso para ensino esperando avaliação.</td>
                                        </tr>                        
                                    <?php endif; ?> 
                            
                            <?php endforeach; ?>        
                            
                        </tbody>
                    </table>
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <input id="ep-mr" value="<?php echo base_url('dashboard/paper/retrievepaperdetails'); ?>" style="display:none;" />
            <button style="display:none;" id="openEvaluatePaperDetails" data-toggle="modal" data-target="#modalEvaluatePaper">asd</button>






<!-- Modal -->
    <div class="modal fade" id="modalEvaluatePaper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Avaliar Artigo</h4>
                </div>
                <div class="modal-body">

                    <!-- AJAX HERE -->
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>