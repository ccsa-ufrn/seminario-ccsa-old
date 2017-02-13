

			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Submeter Pôster</h1>
                    
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
                    
                    <h4 class="page-header">Pôsteres Submetidos</h4>
                    
                    <table class="table table-hover">
                    <!-- Falta centralizar verticalmente o texto -->
                        
                    <?php foreach($posters as $p): ?>
                    
                        <tr class="active" style="text-align:center;" >
                            <td style="width:40%;"><b><?php echo $p->title; ?></b></td>
                            <td>Enviado em: <?php echo date("d/m/Y", strtotime($p->createdAt));  ?> </td>
                            
                            <?php if($p->evaluation=='pending'): ?>
                            
                                <td class="text-warning">Esperando Avaliação</td>
                                <td><a style="cursor:pointer;" class="poster-cancel-submission" data-data="<?php echo $p->id;  ?>">Cancelar Submissão</a></td>
                            
                            <?php echo form_open(base_url('dashboard/poster/cancelsubmission'), array('id' => 'formPosterCancelSubmission-'.$p->id,'novalidate' => '','class' => 'waiting','style' => 'display:inline-block;')); ?>
                                  <input style="display:none;" name="id" value="<?php echo $p->id; ?>" />
                                </form> 
                            <?php elseif($p->evaluation=='accepted'): ?>
                            
                                <td  colspan="2" class="text-success" >Aceito</td>
                                
                                <?php if($p->poster != "") :  ?>
                                
                                    <td class="text-success">O pôster já foi enviado, parabéns!</td>
                                
                                <?php elseif (! dateleq(date('Y-m-d'), '2016-04-08')) : ?>
                                
                                    <td class="text-success">Já passou do prazo de envio do pôster.</td>
                                
                                <?php elseif ($p->poster == "") : ?>
                                
                                    <td>
                                        <a id="later-upload-poster-btn" class="btn btn-danger">Enviar Pôster</a>
                                         <?php echo form_open_multipart(base_url('dashboard/poster/upload-later-do'), array('id' => 'form-later-upload-poster', 'style' => 'display : none;')); ?>
                                            
                                            <input type="text" name="id-poster" value="<?php echo $p->id; ?>" >
                                            <input type="file" id="later-upload-poster" name="poster" >
                                            
                                        </form>
                                        
                                    </td>
                                
                                <?php endif; ?>
                                
                            <?php else: ?>
                                <td class="text-danger">Rejeitado</td>
                                <td colspan="2"><b>Motivo:</b> <?php if(isset($p->evaluationobservation)) echo $p->evaluationobservation; ?></td>
                            <?php endif;?>
                            
                        </tr>
                    
                    <?php endforeach; ?>
                
                    <?php if(!count($posters)): ?>
                    <tr class="text-center active">
                        <td><span class="text-danger">Você ainda não submeteu nenhum pôster.</span></td>
                    </tr>
                    <?php endif; ?>
                        
                    </table>

                    <h4 class="page-header">Submeter Pôster</h4>
                    
                    <?php if (!$paid) : ?>
                    
                        <p class="text-danger">Você precisa realizar o <b>pagamento</b> da inscrição para submeter um trabalho.</p>
                        
                    <?php elseif($date_limit['open']): ?>
                    <p class="text-danger">Data limite de submissão: <?php echo date("d/M/Y", strtotime($date_limit['config']->value));  ?></p>
                    <?php echo form_open(base_url('dashboard/poster/create'), array('id' => 'formCreatePoster','novalidate' => '','class' => 'waiting')); ?>
    
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                	<label for="file">Título *</label>
                                    <input type="text" class="form-control" placeholder="Título *" name="title" value="<?php echo $popform['title']; ?>" />
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['title']; ?> <?php endif; ?></p>
                                </div>

                                <div class="form-group">
                                	<label for="file">Grupo Temático *</label>
                                    <select name="thematicgroup" class="form-control">
								  		<?php foreach ($tgs as $tg): ?>
                                            <option <?php if($popform['thematicgroup']==$tg->id): ?> selected="true" <?php endif; ?> value="<?php echo $tg->id; ?>"><?php echo $tg->name; ?></option>
                                        <?php endforeach ?>
									</select>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['thematicgroup']; ?> <?php endif; ?></p>
                                </div>

                                <div class="form-group">
                                    <label>Autores *</label>
                                    <br/>
                                    <button class="btn btn-default" data-toggle="modal" data-target=".modal-poster-add-author" type="button" style="margin-bottom:10px;">Adicionar Autor</button>
                                    <textarea readOnly rows="4" class="form-control" placeholder="Nenhum autor adicionado" name="authors" ><?php echo $popform['authors']; ?></textarea>
                                    <a class="reset-authors" href="javascript:void(0);">Resetar</a>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['authors']; ?> <?php endif; ?></p>
                                </div>

                            </div>
                            <div class="col-md-6">
                                
                                <div class="form-group">
                                	<label for="file">Resumo *</label>
                                    <textarea rows="4" class="form-control" placeholder="Resumo *" name="abstract" ><?php echo $popform['abstract']; ?></textarea>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['abstract']; ?> <?php endif; ?></p>
                                </div>
                                
                                <div class="form-group">
                                	<label for="file">Palavras-chave *</label>
                                    <textarea rows="4" class="form-control" placeholder="Palavras-chave *" name="keywords" ><?php echo $popform['keywords']; ?></textarea>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['keywords']; ?> <?php endif; ?></p>
                                </div>
                                
                                <div class="form-group">
                                    <label for="file">Pôster ( Pode ser enviado posteriormente, até 03/04, quando aprovado )</label>
    								<input id="posterupload" type="file" name="userfile" data-url="<?php echo base_url(); ?>dashboard/poster/uploadposter" />
                                    <figure class="loading" style="display:none;font-size:12;margin-top:0px;"><img  src="<?php echo asset_url(); ?>img/loading.gif" /> Carregando, aguarde... </figure>
                                    <!-- There was a problem with the jqBootstrapValidation, so i used an ugly hack to solve the problem -->
                                     <input style="width:0px;height:0px;border:none;position:absolute;top:-200px;" type="text" name="poster" value="" />
                                    <p class="file-desc text-success"></p>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['poster']; ?> <?php endif; ?></p>
                                </div>
                                
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center success-container">
                                <div class="success"></div>
                                <button type="submit" class="btn btn-lg btn-success">Submeter Pôster</button>
                            </div>
                        </div>
                    </form>
                
                    <?php else: ?>
                
                    <p class="text-danger">A data limite de submissão (<?php echo date("d/M/Y", strtotime($date_limit['config']->value));  ?>) foi atingida, não há possibilidades de envio de trabalho.</p>
                
                    <?php endif; ?>    
                
                </div> <!-- /.col-lg-12 -->

                <div class="modal fade modal-poster-add-author" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-sm">
                    <div class="modal-content">

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="file">Nome Completo*</label>
                                <input type="text" class="form-control" placeholder="Nome *" name="name" />
                            </div>
                            <div class="form-group">
                                <label for="file">Instituição *</label>
                                <input type="text" class="form-control" placeholder="Instituição *" name="institution"/>
                            </div>
                            <button class="btn btn-default" >Adicionar Autor</button>
                        </div>
                        
                    </div>
                  </div>
                </div>

            </div>