
            <input style="display:none;" id="rm-retrieveCalendar" value="<?php echo base_url('dashboard/minicourse/retrievecalendar');?>" />
            <div class="row">
                <div class="col-md-12">
                    <h3>Calendário dos Minicursos</h3>

                    <h4 class="page-header">Adicionar dia/turno</h4>
                    <?php echo form_open(base_url('formError'), array('id' => 'formCreateMinicourseDayShift','novalidate' => '')); ?>
                        <input style="display:none;" class="formAction" value="<?php echo base_url('dashboard/minicourse/createdayshift');?>" />
                        <input style="display:none;" class="formRedirect" value="<?php echo base_url('dashboard/minicourse/registered');?>" />
                        <div class="row">
                            <div class="col-md-3 col-xs-3 col-sm-3">

                                <div class="form-group">
                                    <label>Dia *</label>
                                    <input type="number" class="form-control" placeholder="Dia" name="day" required>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-4 col-sm-4">

                                <div class="form-group">
                                    <label>Mês *</label>
                                    <select class="form-control" name="month" >
                                        <option value="1">Janeiro</option>
                                        <option value="2">Fevereiro</option>
                                        <option value="3">Março</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Maio</option>
                                        <option value="6">Junho</option>
                                        <option value="7">Julho</option>
                                        <option value="8">Agosto</option>
                                        <option value="9">Setembro</option>
                                        <option value="10">Outubro</option>
                                        <option value="11">Novembro</option>
                                        <option value="12">Dezembro</option>
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-5 col-sm-5">

                                <div class="form-group">
                                    <label>Ano *</label>
                                    <input type="number" class="form-control" placeholder="Ano" name="year" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-12 col-xs-12 col-sm-12">

                                <div class="form-group">
                                    <label>Turno *</label>
                                    <select class="form-control" name="shift">
                                        <option value="matutino">Matutino</option>
                                        <option value="vespertino">Vespertino</option>
                                        <option value="noturno">Noturno</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <button type="submit" class="btn btn-block btn-large btn-success"> <i class="fa fa-plus-circle"></i> Adicionar Dia/Turno</button>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 success-container text-center">
                                <div class="success"></div>
                            </div>

                        </div>
                    </form>

                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h4 class="page-header">Calendário</h4>
                </div>
            </div>

            <div id="rm-days" class="row" style="margin-top:20px;">
                
                <!-- Calendar content goes here (AJAX) -->
                
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h3>Minicursos Cadastrados</h3>
                    <table id="tableMinicourse" class="table table-striped table-bordered table-condensed">

                        <!-- Content goes here (AJAX) -->

                    </table>
                </div>
                <input id="rm-ctm" value="<?php echo base_url('dashboard/minicourse/retrieveconsolidatetable'); ?>" style="display:none" />
                <input id="rm-dm" value="<?php echo base_url('dashboard/minicourse/deallocate'); ?>" style="display:none" />
                <!-- /.col-lg-12 -->
            </div>

    <!-- Modal -->
    <div class="modal fade" id="modalMinicourseDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Detalhes</h4>
                </div>
                <div class="modal-body">
                    
                    <!-- Content (AJAX) -->
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <input id="rm-details" style="display:none;" value="<?php echo base_url('dashboard/minicourse/details') ?>" />

    <!-- Modal Consolidate Minicourse -->
    <div class="modal fade" id="modalConsolidateMinicourse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Consolidar Minicurso</h4>
                </div>
                <div class="modal-body">
                    
                    <!-- Content goes here (AJAX) -->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <button id="openModalConsolidateMinicourse" type="button" style="display:none;" data-toggle="modal" data-target="#modalConsolidateMinicourse"></button>
    <input id="rm-consolidateMinicourse" value="<?php echo base_url('dashboard/minicourse/retrieveconsolidate'); ?>" style="display:none" />