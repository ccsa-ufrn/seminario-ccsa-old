

			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Submeter Mesa Redonda</h1>
                    
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
                    
                    <h4 class="page-header">Mesas Redondas Submetidas</h4>
                    
                    <table class="table table-hover">
                    <!-- Falta centralizar verticalmente o texto -->   
                    <?php foreach($roundtables as $rt): ?>
                        
                        <tr class="active text-center">
                            
                        <td style="width:40%;"><b><?php echo $rt->title; ?></b></td>
                        <td>Enviado em: <?php echo date("d/m/Y", strtotime($rt->createdAt));  ?> </td>
                        
                        <?php if($rt->consolidated=='no'): ?>
                            <td class="text-warning">Esperando Avaliação</td>
                            <?php $limit = R::findOne('configuration','name="max_date_roundtable_submission"'); ?>
                            <?php if(dateleq(mdate('%Y-%m-%d'),$limit->value)): ?>
                            <td><a style="cursor:pointer;" class="roundtable-cancel-submission" data-data="<?php echo $rt->id;  ?>">Cancelar Submissão</a></td>
                            <?php echo form_open(base_url('dashboard/roundtable/cancelsubmission'), array('id' => 'formRoundtableCancelSubmission-'.$rt->id,'novalidate' => '','class' => 'waiting','style' => 'display:inline-block;')); ?>
                                <input style="display:none;" name="id" value="<?php echo $rt->id; ?>" />
                            </form> 
                            <?php endif; ?>
                        <?php elseif($rt->consolidated=='yes'): ?>
                            <td class="text-success">Mesa-redonda Consolidada/Aceita</td>
                            <td></td>
                        <?php endif; ?>
                    
                        </tr> 
                
                    <?php endforeach; ?>

                    <?php if(!count($roundtables)): ?>
                    <tr class="text-center active">
                        <td><span class="text-danger">Você ainda não submeteu nenhuma mesa-redonda.</span></td>
                    </tr>
                    <?php endif; ?>
                        
                    </table>
                    
                    <h4 class="page-header">Submeter Mesa Redonda</h4>
                    
                    <?php if($date_limit['open']): ?>
                    <p class="text-danger">Data limite de submissão: <?php echo date("d/M/Y", strtotime($date_limit['config']->value));  ?></p>
                    <?php echo form_open(base_url('dashboard/roundtable/create'), array('id' => 'formCreateRoundTable','novalidate' => '','class' => 'waiting')); ?>
                    
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                	<label for="file">Título *</label>
                                    <input type="text" class="form-control" placeholder="Título" name="title" value="<?php echo $popform['title']; ?>" />
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['title']; ?> <?php endif; ?></p>
                                </div>
                                
                                <div class="form-group">
                                	<label for="file">Coordenador *</label>
                                    <br/>
                                    <button class="btn btn-default" data-toggle="modal" data-target=".modal-roundtable-add-coordinator" type="button" style="margin-bottom:10px;">Adicionar Coordenador</button>
                                    <input readOnly type="text" class="form-control" placeholder="Coordenador não adicionado" name="coordinator" value="<?php echo $popform['coordinator']; ?>" />
                                    <a class="reset-coordinator" href="javascript:void(0);">Resetar</a>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['coordinator']; ?> <?php endif; ?></p>
                                </div>

                                <div class="form-group">
                                    <label>Debatedores *</label>
                                    <br/>
                                    <button class="btn btn-default" data-toggle="modal" data-target=".modal-roundtable-add-debater" type="button" style="margin-bottom:10px;">Adicionar Debatedor</button>
                                    <textarea readOnly rows="4" class="form-control" placeholder="Nenhum debatedor adicionado" name="debaters" ><?php echo $popform['debaters']; ?></textarea>
                                    <a class="reset-authors" href="javascript:void(0);">Resetar</a>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['debaters']; ?> <?php endif; ?></p>
                                </div>

                            </div>
                            <div class="col-md-6">
                                
                                <div class="form-group">
                                	<label for="file">Turno *</label>
                                    <select class="form-control" name="shift">
								  		<option <?php if($popform['shift']=='matutino'): ?> selected="true" <?php endif; ?> value="matutino">Matutino</option>
								  		<option <?php if($popform['shift']=='vespertino'): ?> selected="true" <?php endif; ?> value="vespertino">Vespertino</option>
								  		<option <?php if($popform['shift']=='noturno'): ?> selected="true" <?php endif; ?> value="noturno">Noturno</option>
									</select>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['shift']; ?> <?php endif; ?></p>
                                </div>
                                
                                <div class="form-group">
                                	<label for="file">Proposta *</label>
                                    <textarea rows="13" class="form-control" placeholder="Proposta" name="proposal"><?php echo $popform['proposal']; ?></textarea>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['proposal']; ?> <?php endif; ?></p>
                                </div>
                                
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center success-container">
                                <div class="success"></div>
                                <button type="submit" class="btn btn-lg btn-success">Submeter Mesa Redonda</button>
                            </div>
                            
                        </div>
                    </form>
                
                    <?php else: ?>
                
                    <p class="text-danger">A data limite de submissão (<?php echo date("d/M/Y", strtotime($date_limit['config']->value));  ?>) foi atingida, não há possibilidades de envio de trabalho.</p>
                
                    <?php endif; ?>
                
                </div>
                <!-- /.col-lg-12 -->

                <div class="modal fade modal-roundtable-add-debater" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
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
                            <button class="btn btn-default" >Adicionar Debatedor</button>
                        </div>
                        
                    </div>
                  </div>
                </div>


                <div class="modal fade modal-roundtable-add-coordinator" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
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
                            <button class="btn btn-default" >Adicionar Coordenador</button>
                        </div>
                        
                    </div>
                  </div>
                </div>

            </div>