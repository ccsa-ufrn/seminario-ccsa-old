                    <h3>Detalhes do Administrador</h3>

                    <p class="name"><?php echo $administrator->name; ?></p>
                    <p class="email"><?php echo $administrator->email; ?></p>
                    
                    <h3>Editar Administrador</h3>
                    
                    <?php echo form_open(base_url('dashboard/administrator/update'), array('id' => 'formUpdateAdministrator','novalidate' => '','class' => 'waiting')); ?>
                        
                        <input style="display:none;" type="text" name="id" value="<?php echo $administrator->id; ?>">
                        <div class="row">

                            <div class="col-md-12 col-xs-12 col-sm-12">

                                <div class="form-group">
                                    <label>Nova Senha *</label>
                                    <input type="password" class="form-control" placeholder="Nova Senha" name="password" />
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
                
                    <h3>Remover Administrador</h3>
                
                    <?php echo form_open(base_url('dashboard/administrator/delete'), array('id' => 'formDeleteAdministrator','novalidate' => '','class' => 'waiting')); ?>

                        <input style="display:none;" type="text" name="id" value="<?php echo $administrator->id; ?>">
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