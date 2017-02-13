<h3>Detalhes do Caso para Ensino</h3>

    <p class="title"><b>Título:</b> <?php echo $tc->title; ?></p>
    <p class="abstract"><b>Resumo:</b> <?php echo $tc->abstract; ?></p>
    <p class="keywords"><b>Palavras-Chave:</b> <?php echo $tc->keywords; ?></p>
    <p class="program"><b>Caso para Ensino:</b> <a href="<?php echo asset_url(); ?>/upload/teachingcases/<?php echo $tc->teachingcase; ?>" target="_blank">caso de ensino</a></p>

    <h3>Avaliar</h3>

    <?php echo form_open(base_url('dashboard/teachingcases/accept'), array('id' => 'formAcceptTeachingCase','novalidate' => '','class' => 'waiting')); ?>
        <input style="display:none;" class="formRedirect" value="<?php echo base_url('dashboard/teachingcases/evaluate');?>" />
        <input style="display:none;" type="text" name="id" value="<?php echo $tc->id; ?>">
        <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12">
                <button type="submit" class="btn btn-block btn-large btn-success">Aceitar</button>
            </div>
            <div class="clearfix"></div>
        </div>
    </form>

    <hr />

    <?php echo form_open(base_url('dashboard/teachingcases/reject'), array('id' => 'formRejectTeachingCase','novalidate' => '','class' => 'waiting')); ?>
        <input style="display:none;" class="formRedirect" value="<?php echo base_url('dashboard/teachingcases/evaluate');?>" />
        <input style="display:none;" type="text" name="id" value="<?php echo $tc->id; ?>">
        <div class="row">
            <div style="margin-top:10px;" class="col-md-12 col-xs-12 col-sm-12">
                <div class="form-group">
                    <label>Observação (Obrigatório)</label>
                    <textarea rows="5" class="form-control" placeholder="Observação" name="observation" ></textarea>
                </div>
                <button type="submit" class="btn btn-block btn-large btn-danger">Rejeitar</button>
            </div>
            <div class="clearfix"></div>
        </div>
    </form>