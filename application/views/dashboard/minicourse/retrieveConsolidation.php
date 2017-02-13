
                    
                    <?php echo form_open(base_url('dashboard/minicourse/consolidate'), array('id' => 'formConsolidateMinicourse','novalidate' => '','class' => 'waiting')); ?>
                        <input style="display:none;" type="text" name="id" value="<?php echo $mc['id']; ?>">
                        <div class="row">

                            <div class="col-md-12 col-xs-12 col-sm-12">
                            	
                                <div class="form-group">
                                    <label for="file">Quantidade de Vagas *</label>
                                    <p class="text-danger">O propositor tem <b>preferência</b> por <b><?php echo $mc['vacancies']; ?></b> vagas.</p>
                                    <input type="number" class="form-control" placeholder="Vagas" value="<?php echo $mc['vacancies']; ?>" name="vacancies" />
                                </div>

                                <div class="form-group">
                                    <label for="file">Local do Minicurso *</label>
                                    <input type="text" class="form-control" placeholder="Local" name="local" />
                                </div>

                                <div class="form-group">
                                    <label for="file">Dias/Turnos *</label>
                                    <p class="text-danger">O propositor tem <b>preferência</b> pelo turno <b><?php echo $mc['shift']; ?></b>.</p>
                                    <select class="form-control" name="dayshifts[]" multiple="multiple" size="8">
                                        
                                        <?php foreach ($dss as $ds): ?>
                                            <?php if($ds['minicourse']==null || !isset($ds['minicourse'])): ?>
                                                <option value="<?php echo $ds->id; ?>"><?php echo date("d/m/Y", strtotime($ds->date)); ?> - <?php echo $ds->shift ?> </option>
                                            <?php endif; ?>
                                        <?php endforeach ?>
                                        
                                        <?php if(!count($dss)): ?>
                                            <option value="-1">Não há dias/turnos disponíveis</option>
                                        <?php endif; ?>
                                        
                                    </select>
                                    <span id="helpBlock" class="help-block">Utilize o Ctrl para selecionar mais de um dia/turno.</span>
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

