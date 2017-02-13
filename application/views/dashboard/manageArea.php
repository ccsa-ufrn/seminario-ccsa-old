

			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Gerenciar Áreas</h1>
                    <?php echo form_open(base_url('formError'), array('id' => 'formCreateArea','novalidate' => '')); ?>
                        <input style="display:none;" class="formAction" value="<?php echo base_url('dashboard/area/doCreate');?>" />
                        <input style="display:none;" class="formRedirect" value="<?php echo base_url('dashboard/area/manage');?>" />
                        <div class="row">
                            <div class="col-md-12"><h3>Adicionar área</h3></div>
                            <div class="col-md-8 col-xs-8 col-sm-8">

                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Nome" name="name" required data-validation-required-message="Por favor, insira o nome da área.">
                                    <p class="help-block text-danger"></p>
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
                                        <td class="editar"><button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalEditarArea" data-data="<?php echo $a->id; ?>,<?php echo $a->name; ?>"><i class="fa fa-wrench"></i></button></td>
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
                    
                    <h3>Editar Área</h3>
                    
                    <?php echo form_open(base_url('formError'), array('id' => 'formUpdateArea','novalidate' => '')); ?>
                        <input style="display:none;" class="formAction" value="<?php echo base_url('dashboard/area/doUpdate');?>" />
                        <input style="display:none;" class="formRedirect" value="<?php echo base_url('dashboard/area/manage');?>" />
                        <input style="display:none;" type="text" name="id">
                        <div class="row">

                            <div class="col-md-12 col-xs-12 col-sm-12">

                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Nome" name="name" required data-validation-required-message="Por favor, insira o nome da área.">
                                    <p class="help-block text-danger"></p>
                                </div>

                            </div>
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <button type="submit" class="btn btn-block btn-large btn-success">Salvar</button>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 success-container text-center">
                                <div class="success"></div>
                            </div>

                        </div>
                    </form>
                
                    <h3>Remover Área</h3>
                
                    <?php echo form_open(base_url('formError'), array('id' => 'formDeleteArea','novalidate' => '')); ?>
                        <input style="display:none;" class="formAction" value="<?php echo base_url('dashboard/area/delete');?>" />
                        <input style="display:none;" class="formRedirect" value="<?php echo base_url('dashboard/area/manage');?>" />
                        <input style="display:none;" type="text" name="id">
                        <div class="row">

                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <button type="submit" class="btn btn-block btn-large btn-danger">Remover</button>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 success-container text-center">
                                <div class="success"></div>
                            </div>

                        </div>
                    </form>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>