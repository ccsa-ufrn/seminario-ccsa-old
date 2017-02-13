

			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Chamados - Suporte</h1>

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

                    <h4 class="page-header">Chamados Abertos</h4>
                    <?php foreach($issues as $i): ?>
                        <a href="#" data-toggle="modal" data-target="#user-modalDetailsIssue" data-data="<?php echo $i->id; ?>"><i class="fa fa-external-link"></i> <?php echo $i->title; ?></a>
                    <?php endforeach; ?>

                    <?php if(!count($issues)): ?>

                        <p class="text-danger">Nenhum chamado aberto.</p>

                    <?php endif; ?>

                    <h4 class="page-header">Abrir Chamado</h4>

                    <?php echo form_open(base_url('dashboard/issue/open'), array('id' => 'issueCreateForm','novalidate' => '','class' => 'waiting')); ?>
    
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                	<label for="file">Título *</label>
                                    <input type="text" class="form-control" placeholder="Título" name="title" value="<?php echo $popform['title']; ?>" />
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['title']; ?> <?php endif; ?></p>
                                </div>


                                <div class="form-group">
                                    <label for="file">Descrição *</label>
                                    <textarea rows="8" class="form-control" placeholder="Descrição" name="description" ><?php echo $popform['description']; ?></textarea>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['description']; ?> <?php endif; ?></p>
                                </div>
                                
                                <div class="form-group">
                                    <label for="file">Imagem (Opcional)</label>
    								<input id="issueimgupload" type="file" name="userfile" data-url="<?php echo base_url(); ?>dashboard/issue/uploadimage" />
                                    <figure class="loading" style="display:none;font-size:12;margin-top:0px;"><img  src="<?php echo asset_url(); ?>img/loading.gif" /> Carregando, aguarde... </figure>
                                    <!-- There was a problem, so i used an ugly hack to solve the problem -->
                                     <input style="width:0px;height:0px;border:none;position:absolute;top:-200px;" type="text" name="image" value="" />
                                    <p class="file-desc text-success"></p>
                                </div>
                                
                               
                                
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center success-container">
                                <div class="success"></div>
                                <button type="submit" class="btn btn-lg btn-success">Abrir Chamado</button>
                            </div>
                        </div>

                    </form>
                </div>
                <!-- /.col-lg-12 -->
            </div>

    <!-- Modal -->
    <div class="modal fade" id="user-modalDetailsIssue" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Chamado</h4>
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

     <input id="user-issue-retrieve-details" value="<?php echo base_url('dashboard/issue/userretrievedetails'); ?>" style="display:none;" />