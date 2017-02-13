

			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Gerenciar Coordenadores</h1>
                    
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
                    
                    <?php echo form_open(base_url('dashboard/coordinator/create'), array('id' => 'formCreateCoordinator','novalidate' => '','class'=>'waiting')); ?>

                        <div class="row">
                            <div class="col-md-12"><h3>Adicionar Coordenador</h3></div>
                            <div class="col-md-6 col-xs-6 col-sm-6">

                                <div class="form-group">
                                    <label>Nome *</label>
                                    <input type="text" class="form-control" placeholder="Nome" name="name" value="<?php echo $popform['name']; ?>"/>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['name']; ?> <?php endif; ?></p>
                                </div>

                                <div class="form-group">
                                    <label>Email *</label>
                                    <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo $popform['email']; ?>"/>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['email']; ?> <?php endif; ?></p>
                                </div>

                                <div class="form-group">
                                    <label>Senha *</label>
                                    <input type="password" class="form-control" placeholder="Senha" name="password" />
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['password']; ?> <?php endif; ?></p>
                                </div>

                            </div>
                            <div class="col-md-6 col-xs-6 col-sm-6">

                                <div class="form-group">
                                    <label>Grupos Temáticos *</label>
                                    <select class="form-control" multiple size="9" name="thematicGroups[]">
                                        <?php foreach ($thematicGroups as $tg): ?>
                                            <option <?php if(isset($popform['thematicGroups']) && in_array($tg->id,$popform['thematicGroups'])): ?> selected="true" <?php endif; ?> value="<?php echo $tg->id; ?>"><?php echo $tg->name; ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <p class="help-block">Segure o Ctrl ao selecionar mais de um Grupo Temático.</p>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['thematicGroups']; ?> <?php endif; ?></p>
                                </div>

                            </div>
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <button type="submit" class="btn btn-block btn-large btn-success"> <i class="fa fa-plus-circle"></i> Adicionar Coordenador</button>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 success-container text-center">
                                <div class="success"></div>
                            </div>

                        </div>
                    </form>
                
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Coordenadores Cadastrados</h3>
                            <table id="tableCoordinators" class="table table-striped table-bordered table-condensed">

                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <?php 
                                            $link = base_url('dashboard/coordinator/name/ASC');
                                            $asc = false;   
                                            if($order[0]=='name') 
                                                if($order[1]=='ASC'){
                                                    $link = base_url('dashboard/coordinator/name/DESC');
                                                    $asc = true;
                                                }     
                                        ?>
                                        <th>
                                            <a href="<?php echo $link; ?>">
                                                Nome
                                                <?php if($order[0]=='name'): if($asc): ?> <i class="fa fa-long-arrow-up"></i> <?php else: ?> <i class="fa fa-long-arrow-down"></i> <?php endif; endif; ?>
                                            </a>
                                        </th>
                                        <th>Email</th>
                                        <th>Detalhes/Ações</th>
                                    </tr>
                                </thead>
                                <?php foreach ($coordinators as $c): ?>
                                    <tr id="tr-coordinator-<?php echo $c->id; ?>">
                                        <td class="id"><?php echo $c->id; ?></td>
                                        <td class="name"><?php echo $c->name; ?></td>
                                        <td class="email"><?php echo $c->email; ?></td>
                                        <td class="editar"><button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalEditCoordinator" data-data="<?php echo $c->id; ?>"><i class="fa fa-wrench"></i></button></td>
                                    </tr>
                                <?php endforeach ?>

                            </table>
                        </div>
                    </div>
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>

    <!-- Modal -->
    <div class="modal fade" id="modalEditCoordinator" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Detalhes/Ações para Coordenador</h4>
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

    <input id="dcrd" value="<?php echo base_url('dashboard/coordinator/retrievedetails'); ?>" style="display:none;" />