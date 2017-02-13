

			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Gerenciar Grupos Temáticos</h1>
                    
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
                    
                    <?php echo form_open(base_url('thematicgroup/create'), array('id' => 'formCreateThematicGroup','novalidate' => '','class' => 'waiting')); ?>
                        <div class="row">
                            <div class="col-md-12"><h3>Adicionar Grupo Temático</h3></div>
                            <div class="col-md-6 col-xs-6 col-sm-6">

                                <div class="form-group">
                                    <label>Nome *</label>
                                    <input type="text" class="form-control" placeholder="Nome *" name="name" value="<?php echo $popform['name']; ?>"/>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['name']; ?> <?php endif; ?></p>
                                </div>

                            </div>
                            <div class="col-md-6 col-xs-6 col-sm-6">

                                <div class="form-group">
                                    <label>Área *</label>
                                    <select class="form-control" name="area">
                                        <?php foreach ($areas as $a): ?>
                                            <option <?php if($popform['area']==$a->id): ?> selected="true" <?php endif; ?> value="<?php echo $a->id; ?>"><?php echo $a->name; ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['area']; ?> <?php endif; ?></p>
                                </div>

                            </div>
                            <div class="col-md-12 col-xs-12 col-sm-12">

                                <div class="form-group">
                                    <label >Ementa *</label>
                                    <textarea rows="5" class="form-control" placeholder="Ementa *" name="syllabus"><?php echo $popform['syllabus']; ?></textarea>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['syllabus']; ?> <?php endif; ?></p>
                                </div>
                                
                            </div>

                        </div>
                        
                        <div class="row">
                            
                            <div class="col-lg-12">
                                
                                <div class="form-group">
                                    
                                    <input name="isNotListable" id="isNotListable" type="checkbox" > <label for="isNotListable" >Não é listável nos GT's</label>
                                    
                                </div> <!-- end - div.form-group -->
                                
                            </div> <!-- end - div.col-lg-12-->
                            
                        </div> <!-- end - div.row -->
                        
                        <div class="row">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <button type="submit" class="btn btn-block btn-large btn-success"> <i class="fa fa-plus-circle"></i> Adicionar Grupo Temático</button>
                            </div>
                        </div>
                        
                    </form> <!-- end - form -->
                
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Grupos Temáticos Cadastrados</h3>
                            <table id="tableThematicGroups" class="datatable table table-striped table-bordered ">

                                <thead>
                                    <tr>
                                        <th>
                                            Nome
                                        </th>
                                        <th>
                                            Área
                                        </th>
                                        <th>
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <?php foreach ($thematicgroups as $t): ?>
                                    <tr id="tr-thematicgroup-<?php echo $t->id; ?>">
                                        <td class="name"><?php echo $t->name; ?></td>
                                        <td class="area"><?php echo $t->area->name; ?></td>
                                        <td class="editar"><button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalEditThematicGroup" data-data="<?php echo $t->id; ?>"><i class="fa fa-wrench"></i></button></td>
                                    </tr>
                                <?php endforeach ?>

                            </table>
                        </div>
                    </div>
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>

    <!-- Modal -->
    <div class="modal fade" id="modalEditThematicGroup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Ações para Grupo Temático</h4>
                </div>
                <div class="modal-body">
                    
                    <!-- CONTENT GOES HERE -->
                    Carregando...
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <input id="dtgrd" value="<?php echo base_url('dashboard/thematicgroup/retrievedetails'); ?>" style="display:none;" />