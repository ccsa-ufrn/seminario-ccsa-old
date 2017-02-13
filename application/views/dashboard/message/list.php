            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Mensagens</h1>

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
                    
                    <div class="row">
                        <div class="col-md-12">
                            <p><b>Ver: </b> <a href="<?php echo base_url('dashboard/message'); ?>" >Todas as mensagens</a> | <a href="<?php echo base_url('dashboard/message/noanswered'); ?>">Somente não respondidas</a> | <a href="<?php echo base_url('dashboard/message/answered'); ?>" >Somente respondidas</a>   </p>
                            <table id="tableArea" class="table table-striped table-bordered table-condensed">

                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Assunto</th>
                                        <th>Respondida?</th>
                                        <th>Detalhes/Responder</th>
                                    </tr>
                                </thead>
                                <?php foreach ($msgs as $m): ?>
                                    <tr>
                                        <td><?php echo $m->name; ?></td>
                                        <td><?php echo $m->subject; ?></td>
                                        <?php if($m->answered=='yes'): ?>
                                            <td style="background-color:green;">Sim</td>
                                        <?php else: ?>
                                            <td style="background-color:red;">Não</td>
                                        <?php endif; ?>
                                        <td><button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalDetailsMessage" data-data="<?php echo $m->id; ?>"><i class="fa fa-share"></i></button></td>
                                    </tr>
                                <?php endforeach ?>

                            </table>
                        </div>
                    </div>
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>

<!-- Modal -->
    <div class="modal fade" id="modalDetailsMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Messagem</h4>
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

     <input id="daarda" value="<?php echo base_url('dashboard/message/retrievedetails'); ?>" style="display:none;" />