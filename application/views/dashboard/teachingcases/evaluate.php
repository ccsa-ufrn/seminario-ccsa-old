            
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Avaliação de Casos para Ensino</h1>

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
                    
                    <?php 
                        $ccount = count($tcs);
                    ?>
                    
                    <?php if($ccount): ?>
                        <div class="alert alert-danger">
                            <b>Atenção! </b>
                            <?php if($ccount==1): ?>
                                1 caso para Ensino precisa ser avaliado.
                            <?php else: ?>
                                <?php echo $ccount; ?> casos para Ensino precisam ser avaliados.
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <h2>Avaliar</h2>
                    
                    <table id="tablePendingTeachingCases" class="table table-striped table-bordered table-condensed">

                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Avaliar</th>
                            </tr>
                        </thead>
                        <?php foreach ($tcs as $t): ?>
                            <tr id="tr-papers-<?php echo $t->id; ?>">
                                <td class="name"><?php echo $t->title; ?></td>
                                <td class="editar" style="width:20%;">
                                    <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalEditCoordinator" data-data="<?php echo $t->id; ?>">
                                        <i class="fa fa-pencil-square fa-2x"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach ?>

                        <?php if(!count($tcs)): ?>
                            <tr>
                                <td colspan="2">Não há nenhum caso para ensino esperando avaliação.</td>
                            </tr>                        
                        <?php endif; ?>

                    </table>           
                
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <input id="ep-mr2" value="<?php echo base_url('dashboard/teachingcases/retrieveteachingcasedetails'); ?>" style="display:none;" />
            <button style="display:none;" id="openEvaluateTeachingCaseDetails" data-toggle="modal" data-target="#modalEvaluateTeachingCase">asd</button>






<!-- Modal -->
    <div class="modal fade" id="modalEvaluateTeachingCase" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Avaliar Caso para Ensino</h4>
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