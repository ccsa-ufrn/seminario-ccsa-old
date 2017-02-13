

			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Gerenciar Áreas</h1>

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

                    <?php echo form_open(base_url('dashboard/area/create'), array('id' => 'formCreateArea','novalidate' => '','class' => 'waiting')); ?>

                        <div class="row">
                            <div class="col-md-12"><h3>Adicionar área</h3></div>
                            <div class="col-md-8 col-xs-8 col-sm-8">

                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Nome" name="name" value="<?php echo $popform['name']; ?>">
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['name']; ?> <?php endif; ?></p>
                                </div>

                            </div>
                            <div class="col-md-4 col-xs-4 col-sm-4">
                                <button type="submit" class="btn btn-block btn-large btn-success"> <i class="fa fa-plus-circle"></i> Adicionar Área</button>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 success-container text-center">
                                <div class="success"></div>
                            </div>

                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Áreas cadastradas</h3>
                            <table id="tableArea" class="table table-striped table-bordered table-condensed">

                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <?php foreach ($areas as $a): ?>
                                    <tr id="tr-area-<?php echo $a->id; ?>">
                                        <td class="id"><?php echo $a->id; ?></td>
                                        <td class="name"><?php echo $a->name; ?></td>
                                        <td class="editar"><button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalEditarArea" data-data="<?php echo $a->id; ?>"><i class="fa fa-wrench"></i></button></td>
                                    </tr>
                                <?php endforeach ?>

                            </table>
                        </div>
                    </div>
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>

    <!-- Modal -->
    <div class="modal fade" id="modalEditarArea" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Ações para Área</h4>
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

     <input id="daard" value="<?php echo base_url('dashboard/area/retrievedetails'); ?>" style="display:none;" />