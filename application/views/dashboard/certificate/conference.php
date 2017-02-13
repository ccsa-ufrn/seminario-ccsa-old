<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Conferências - Certificação e Anais</h1>

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

        <h2>Avaliação de Conferências para Anais e Certificação</h2>
        
        <table class="table table-striped table-bordered table-condensed">
            
            <thead>
                <tr>
                    <th>Título</th>
                    <th style="width:20%;">Acrescentar Participante</th>
                    <th colspan="2" style="width:20%;">Certificação e Anais</th>
                </tr>
            </thead>
            <?php foreach ($mcs as $mc): ?>
                <tr id="tr-papers-<?php echo $mc->id; ?>">
                    <td class="name" style="text-transform:uppercase;"><?php echo $mc->title; ?></td>
                    <td class="editar" style="width:20%;text-align:center;">
                        <a style="cursor:pointer;" data-target="#modalAddParticipant" data-toggle="modal" data-type="conference" data-id="<?php echo $mc->id; ?>" >Acrescentar Participante</a>
                    </td>
                    <td class="editar" style="width:10%;text-align:center;">
                        <a style="color:green;cursor:pointer;" data-target="#modalCertificateConference" data-toggle="modal" data-data="<?php echo $mc->id; ?>">Aceitar</a>
                    </td>
                    <td class="editar" style="width:10%;text-align:center;">
                        <a class="certificate-conference-reject" style="color:red;cursor:pointer;" data-value="<?php echo $mc->id; ?>">Rejeitar</a>
                        <?php echo form_open(base_url('dashboard/certificate/rejectconference'), array('id' => 'formRejectCertificate-'.$mc->id,'novalidate' => '','class' => 'waiting')); ?>
                            <input style="display:none;" type="text" name="id" value="<?php echo $mc->id; ?>">
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>

            <?php if(!count($mcs)): ?>
                <tr>
                    <td colspan="3">Não há nenhuma conferência esperando avaliação.</td>
                </tr>                        
            <?php endif; ?>

        </table>           
        
    </div>
    <!-- /.col-lg-12 -->

</div>

<div class="row">
    <div class="col-lg-12">
        <h2>Mesas-Redondas Avaliados</h2>

        <table id="tablePendingPapers" class="table table-striped table-bordered table-condensed">
            
            <thead>
                <tr>
                    <th>Título</th>
                    <th style="width:20%;">Reverter Avaliação</th>
                </tr>
            </thead>
            <?php foreach ($mcsa as $mc): ?>
                <tr id="tr-papers-<?php echo $mc->id; ?>">
                    <td class="name" style="text-transform:uppercase;"><?php echo $mc->title; ?></td>
                    <td class="editar" style="width:20%;text-align:center;">
                        <a class="certificate-conference-revert" data-value="<?php echo $mc->id; ?>" style="cursor:pointer;" >Reverter</a>
                        <?php echo form_open(base_url('dashboard/certificate/revertconference'), array('id' => 'formRevertCertificate-'.$mc->id,'novalidate' => '','class' => 'waiting')); ?>
                            <input style="display:none;" type="text" name="id" value="<?php echo $mc->id; ?>">
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>

            <?php if(!count($mcsa)): ?>
                <tr>
                    <td colspan="3">Não há nenhuma conferência esperando avaliação.</td>
                </tr>                        
            <?php endif; ?>

        </table>

    </div>
    <!-- /.col-lg-12 -->
</div>

<input id="ep-mr" value="<?php echo base_url('dashboard/paper/retrievepaperdetails'); ?>" style="display:none;" />
<button style="display:none;" id="openEvaluatePaperDetails" data-toggle="modal" data-target="#modalEvaluatePaper">asd</button>






<!-- Modal -->
    <div class="modal fade" id="modalCertificateConference" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Aceitar conferência para anais e certificação</h4>
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


    <div class="modal fade" id="modalAddParticipant" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Acrescentar Participante a conferência</h4>
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