<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Pôsteres - Certificação e Anais</h1>

                    <?php if($success!=null): ?>
                        <div class='alert alert-success'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                            <strong><?php echo $success; ?></strong>
                        </div>
                    <?php endif; ?>

                    <?php if($error!=null): ?>
                        <div class='alert alert-danger'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                            <strong><?php echo $error; ?></strong>
                        </div>
                    <?php endif; ?>

                    <h2>Avaliação de Pôsteres para Anais e Certificação</h2>
                    
                    <?php foreach ($tgs as $tg): ?>
                        
                        <h4><?php echo $tg->name; ?></h4>
                    
                        <table id="tablePendingPosters" class="table table-striped table-bordered table-condensed">
                            
                            <?php 
                                $posters = R::find('poster',' evaluation = "accepted" AND thematicgroup_id=? AND ( cernn IS NULL OR cernn = "pending" )',array($tg->id));
                                $paperposters = R::find('paper','evaluation = "asPoster" AND asposter = "accepted" AND thematicgroup_id=? AND ( cernn IS NULL OR cernn = "pending" )',array($tg->id));
                            ?>

                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th colspan="2" style="width:40%;">Certificação e Anais</th>
                                </tr>
                            </thead>
                            <?php foreach ($posters as $p): ?>
                                <tr id="tr-papers-<?php echo $p->id; ?>">
                                    <td class="name"><?php echo $p->title; ?></td>
                                    <td class="editar" style="width:20%;text-align:center;">
                                        <a class="certificate-poster-accept" style="color:green;cursor:pointer;" data-value="poster-<?php echo $p->id; ?>">Aceitar</a>
                                        <?php echo form_open(base_url('dashboard/certificate/acceptposter'), array('id' => 'formAcceptCertificate-poster-'.$p->id,'novalidate' => '','class' => 'waiting')); ?>
                                            <input style="display:none;" type="text" name="id" value="<?php echo $p->id; ?>">
                                            <input style="display:none;" type="text" name="type" value="poster">
                                        </form>
                                    </td>
                                    <td class="editar" style="width:20%;text-align:center;">
                                        <a class="certificate-poster-reject" style="color:red;cursor:pointer;" data-value="poster-<?php echo $p->id; ?>">Rejeitar</a>
                                        <?php echo form_open(base_url('dashboard/certificate/rejectposter'), array('id' => 'formRejectCertificate-poster-'.$p->id,'novalidate' => '','class' => 'waiting')); ?>
                                            <input style="display:none;" type="text" name="id" value="<?php echo $p->id; ?>">
                                            <input style="display:none;" type="text" name="type" value="poster">
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach ?>

                            <?php foreach ($paperposters as $p): ?>
                                <tr id="tr-papers-<?php echo $p->id; ?>">
                                    <td class="name"><?php echo $p->title; ?></td>
                                    <td class="editar" style="width:20%;text-align:center;">
                                        <a class="certificate-poster-accept" style="color:green;cursor:pointer;" data-value="paper-<?php echo $p->id; ?>">Aceitar</a>
                                        <?php echo form_open(base_url('dashboard/certificate/acceptposter'), array('id' => 'formAcceptCertificate-paper-'.$p->id,'novalidate' => '','class' => 'waiting')); ?>
                                            <input style="display:none;" type="text" name="id" value="<?php echo $p->id; ?>">
                                            <input style="display:none;" type="text" name="type" value="paper">
                                        </form>
                                    </td>
                                    <td class="editar" style="width:20%;text-align:center;">
                                        <a class="certificate-poster-reject" style="color:red;cursor:pointer;" data-value="paper-<?php echo $p->id; ?>">Rejeitar</a>
                                        <?php echo form_open(base_url('dashboard/certificate/rejectposter'), array('id' => 'formRejectCertificate-paper-'.$p->id,'novalidate' => '','class' => 'waiting')); ?>
                                            <input style="display:none;" type="text" name="id" value="<?php echo $p->id; ?>">
                                            <input style="display:none;" type="text" name="type" value="paper">
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach ?>

                            <?php if(!( count($posters) + count($paperposters) ) ): ?>
                                <tr>
                                    <td colspan="2">Não há nenhum pôster esperando avaliação.</td>
                                </tr>                        
                            <?php endif; ?>

                        </table>
                    
                    <?php endforeach; ?>            
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>