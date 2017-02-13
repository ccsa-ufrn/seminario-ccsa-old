
                    
                    <?php echo form_open(base_url('formError'), array('id' => 'formConsolidateMinicourse','novalidate' => '')); ?>
                        <input style="display:none;" class="formAction" value="<?php echo base_url('dashboard/minicourse/consolidate');?>" />
                        <input style="display:none;" class="formRedirect" value="<?php echo base_url('dashboard/minicourse/registered');?>" />
                        <input style="display:none;" type="text" name="id" value="<?php echo $mc['id']; ?>">
                        <div class="row">

                            <div class="col-md-12 col-xs-12 col-sm-12">
                            	
                                <div class="form-group">
                                    <label for="file">Quantidade de Vagas *</label>
                                    <input type="number" class="form-control" placeholder="Vagas" name="vacancies" required>
                                    <p class="help-block text-danger"></p>
                                </div>

                                <div class="form-group">
                                    <label for="file">Local do Minicurso *</label>
                                    <input type="text" class="form-control" placeholder="Local" name="local" required>
                                    <p class="help-block text-danger"></p>
                                </div>

                                <div class="form-group">
                                    <label for="file">Dia *</label>
                                    <select class="form-control" name="dayshift" required>
                                        <?php foreach ($dss as $ds): ?>
                                            <?php if($ds['minicoursefinal']==null || !isset($ds['minicoursefinal'])): ?>
                                                <option value="<?php echo $ds->id; ?>"><?php echo $ds->date; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach ?>
                                        
                                        <?php if(!count($dss)): ?>
                                            <option value="-1">Não dias/turnos disponíveis</option>
                                        <?php endif; ?>
                                        
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <button type="submit" class="btn btn-block btn-large btn-success">Consolidar</button>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 success-container text-center">
                                <div class="success"></div>
                            </div>

                        </div>
                    </form>

