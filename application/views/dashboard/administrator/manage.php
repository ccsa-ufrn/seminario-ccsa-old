

			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Gerenciar Administradores</h1>

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

                    <?php echo form_open(base_url('dashboard/administrator/create'), array('id' => 'formCreateAdministrator','novalidate' => '','class' => 'waiting')); ?>
                                            
                        <div class="row">
                            <div class="col-md-12"><h3>Adicionar Administrador</h3></div>
                            <div class="col-md-6 col-xs-6 col-sm-6">

                                <div class="form-group">
                                    <label>Nome *</label>
                                    <input type="text" class="form-control" placeholder="Nome" name="name" value="<?php echo $popform['name']; ?>"/>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['name']; ?> <?php endif; ?></p>
                                </div>

                                <div class="form-group">
                                    <label>Email *</label>
                                    <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo $popform['email']; ?>" />
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['email']; ?> <?php endif; ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-6 col-sm-6">

                            	<div class="form-group">
                                    <label>Telefone *</label>
                                    <input type="tel" class="form-control" placeholder="Telefone" name="phone" value="<?php echo $popform['phone']; ?>" />
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['phone']; ?> <?php endif; ?></p>
                                </div>

                                <div class="form-group">
                                    <label>Senha *</label>
                                    <input type="password" class="form-control" placeholder="Senha" name="password" />
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['password']; ?> <?php endif; ?></p>
                                </div>

                            </div>
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <button type="submit" class="btn btn-block btn-large btn-success"> <i class="fa fa-plus-circle"></i> Adicionar Administrador</button>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 success-container text-center">
                                <div class="success"></div>
                            </div>

                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Administradores Cadastrados</h3>
                            <table id="tableAdministrators" class="table table-striped table-bordered table-condensed">

                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Detalhes/Ações</th>
                                    </tr>
                                </thead>
                                <?php foreach ($adms as $a): ?>
                                    <tr id="tr-administrator-<?php echo $a->id; ?>">
                                        <td class="id"><?php echo $a->id; ?></td>
                                        <td class="name"><?php echo $a->name; ?></td>
                                        <td class="email"><?php echo $a->email; ?></td>
                                        <td class="editar"><button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalEditAdministrator" data-data="<?php echo $a->id; ?>"><i class="fa fa-wrench"></i></button></td>
                                    </tr>
                                <?php endforeach ?>

                            </table>
                        </div>
                    </div>
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>

    <!-- Modal -->
    <div class="modal fade" id="modalEditAdministrator" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Detalhes/Ações para Administrador</h4>
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

    <input id="dard" value="<?php echo base_url('dashboard/administrator/retrievedetails'); ?>" style="display:none;" />