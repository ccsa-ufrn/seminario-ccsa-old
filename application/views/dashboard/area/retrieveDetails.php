                    <h3>Editar Área</h3>
                    
                    <?php echo form_open(base_url('dashboard/area/update'), array('id' => 'formUpdateArea','novalidate' => '','class' => 'waiting')); ?>

                        <input style="display:none;" type="text" name="id" value="<?php echo $area->id; ?>" />
                        <div class="row">

                            <div class="col-md-12 col-xs-12 col-sm-12">

                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Nome" value="<?php echo $area->name; ?>" name="name" />
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
                
                    <?php echo form_open(base_url('dashboard/area/delete'), array('id' => 'formDeleteArea','novalidate' => '','class' => 'waiting')); ?>
                        <input style="display:none;" type="text" name="id" value="<?php echo $area->id; ?>" />
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