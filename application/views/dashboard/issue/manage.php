            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Chamados</h1>

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
                            <p><b>Ver: </b> <a href="<?php echo base_url('dashboard/issue/manage'); ?>" >Todos os Chamados</a> | <a href="<?php echo base_url('dashboard/issue/manage/open'); ?>">Chamados Abertos</a> | <a href="<?php echo base_url('dashboard/issue/manage/closed'); ?>" >Chamados Fechados</a></p>
                            <table id="tableArea" class="table table-striped table-bordered table-condensed">

                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Status</th>
                                        <th>Detalhes/Responder</th>
                                    </tr>
                                </thead>
                                <?php foreach ($issues as $i): ?>
                                    <tr>
                                        <td><?php echo $i->title; ?></td>
                                        <?php if($i->status=='closed'): ?>
                                            <td style="background-color:Green;">Fechado</td>
                                        <?php else: ?>
                                            <td style="background-color:red;">Aberto</td>
                                        <?php endif; ?>
                                        <td><button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalDetailsIssue" data-data="<?php echo $i->id; ?>"><i class="fa fa-share"></i></button></td>
                                    </tr>
                                <?php endforeach ?>

                            </table>
                        </div>
                    </div>
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>

<!-- Modal -->
    <div class="modal fade" id="modalDetailsIssue" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Chamado</h4>
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

     <input id="issue-retrieve-details" value="<?php echo base_url('dashboard/issue/retrievedetails'); ?>" style="display:none;" />