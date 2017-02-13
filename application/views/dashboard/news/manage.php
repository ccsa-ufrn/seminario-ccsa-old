

			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Gerenciar Notícias</h1>
                    
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
                    
                    <?php echo form_open(base_url('dashboard/news/create'), array('id' => 'formCreateNews','novalidate' => '','class' => 'waiting')); ?>
                        <div class="row">
                            <div class="col-md-12"><h3>Adicionar Notícia</h3></div>
                            <div class="col-md-12 col-xs-12 col-sm-12">

                                <div class="form-group">
                                    <label>Título *</label>
                                    <input type="text" class="form-control" placeholder="Titulo" name="title" value="<?php echo $popform['title']; ?>"/>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['title']; ?> <?php endif; ?></p>
                                </div>
                                
                                <div class="form-group">
                                    <label >Destacar Notícia? *</label>
                                    <br>
                                    <input type="checkbox" name="isFixed" value="isFixed"> Destacar
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['isFixed']; ?> <?php endif; ?></p>
                                </div>

                                <div class="form-group">
                                    <label >Texto *</label>
                                    <textarea rows="5" class="form-control" placeholder="Texto" name="text"><?php echo $popform['text']; ?></textarea>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['text']; ?> <?php endif; ?></p>
                                </div>
                                
                            </div>
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <button type="submit" class="btn btn-block btn-large btn-success"> <i class="fa fa-plus-circle"></i> Criar Notícia</button>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 success-container text-center">
                                <div class="success"></div>
                            </div>

                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Notícias Cadastradas</h3>
                            <table id="tableNews" class="table table-striped table-bordered table-condensed">

                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Título</th>
                                        <th>Texto</th>
                                        <th>Detalhes/Ações</th>
                                    </tr>
                                </thead>
                                <?php foreach ($news as $n): ?>
                                    <tr id="tr-news-<?php echo $n->id; ?>">
                                        <td class="id"><?php echo $n->id; ?></td>
                                        <td class="title"><?php echo $n->title; ?></td>
                                        <td class="text"><?php echo character_limiter($n->text,100); ?></td>
                                        <td class="editar"><button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalEditNews" data-data="<?php echo $n->id; ?>"><i class="fa fa-wrench"></i></button></td>
                                    </tr>
                                <?php endforeach ?>

                            </table>
                        </div>
                    </div>
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>

    <!-- Modal -->
    <div class="modal fade" id="modalEditNews" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Detalhes/Ações para Notícia</h4>
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

<input id="dnrd" value="<?php echo base_url('dashboard/news/retrievedetails'); ?>" style="display:none;" />