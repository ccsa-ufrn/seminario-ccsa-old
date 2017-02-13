<h3>Editar Grupo Temático</h3>
                    
                    <?php echo form_open(base_url('dashboard/thematicgroup/update'), array('id' => 'formUpdateThematicGroup','novalidate' => '','class' => 'waiting')); ?>
                        <input style="display:none;" type="text" name="id" value="<?php echo $tg->id; ?>" />
                        <div class="row">

                            <div class="col-md-12 col-xs-12 col-sm-12">

                                <div class="form-group">
                                    <label>Nome *</label>
                                    <input type="text" class="form-control" placeholder="Nome *" name="name" value="<?php echo $tg->name; ?>">
                                </div>

                            </div>
                            
                            <div class="col-md-12 col-xs-12 col-sm-12">

                                <div class="form-group">
                                    <label>Área *</label>
                                    <select class="form-control" name="area">
                                        <?php foreach ($areas as $a): ?>
                                            <option <?php if($tg->area->id==$a->id): ?> selected="true" <?php endif; ?> value="<?php echo $a->id; ?>"><?php echo $a->name; ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                            </div>
                            
                            <div class="col-md-12 col-xs-12 col-sm-12">

                                <div class="form-group">
                                    <label >Ementa *</label>
                                    <textarea rows="5" class="form-control" placeholder="Ementa" name="syllabus"><?php echo $tg->syllabus; ?></textarea>
                                </div>
                                
                            </div>
                            
                            <div class="col-lg-12">

                                <div class="form-group">
                                    
                                    <input name="isNotListable" id="isNotListable1" type="checkbox" <?php if  ( $tg->isListable == 'N') : ?> checked <?php  endif; ?> > <label for="isNotListable1" >Não é listável nos GT's</label>
                                
                                </div> <!-- end - div.form-group -->
                                
                            </div> <!-- end - div.col-lg-12 -->
                            
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
                
                    <?php echo form_open(base_url('dashboard/thematicgroup/delete'), array('id' => 'formDeleteThematicGroup','novalidate' => '','class' => 'waiting')); ?>
                        <input style="display:none;" type="text" name="id" value="<?php echo $tg->id; ?>">
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