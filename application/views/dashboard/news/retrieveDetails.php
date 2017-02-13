    <h3>Editar Notícia</h3>
                    
                    <?php echo form_open(base_url('dashboard/news/update'), array('id' => 'formUpdateNews','novalidate' => '','class' => 'waiting')); ?>
                        <input style="display:none;" type="text" name="id" value="<?php echo $news->id; ?>">
                        <div class="row">

                            <div class="col-md-12 col-xs-12 col-sm-12">

                                <div class="form-group">
                                    <label>Título *</label>
                                    <input type="text" class="form-control" placeholder="Titulo" name="title" value="<?php echo $news->title; ?>" />
                                </div>
                                
                                <div class="form-group">
                                    <label >Destacar Notícia? *</label>
                                    <br>
                                    <input <?php if ( $news->isFixed=='Y' ) : ?> checked  <?php endif; ?>  type="checkbox" name="isFixed" value="isFixed"> Destacar
                                </div>

                                <div class="form-group">
                                    <label >Texto *</label>
                                    <textarea rows="5" class="form-control" placeholder="Texto" name="text"><?php echo strip_tags ($news->text); ?></textarea>
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
                
                    <h3>Remover Notícia</h3>
                
                    <?php echo form_open(base_url('dashboard/news/delete'), array('id' => 'formDeleteNews','novalidate' => '','class' => 'waiting')); ?>

                        <input style="display:none;" type="text" name="id" value="<?php echo $news->id; ?>">
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