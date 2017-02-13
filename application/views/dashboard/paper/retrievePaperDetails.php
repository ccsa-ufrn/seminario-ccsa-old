<h3>Detalhes do Artigo</h3>

    <p class="title"><b>Título:</b> <?php echo $paper->title; ?></p>
    <p class="abstract"><b>Resumo:</b> <?php echo $paper->abstract; ?></p>
    <p class="keywords"><b>Palavras-Chave:</b> <?php echo $paper->keywords; ?></p>
    <p class="program"><b>Programa:</b> <a href="<?php echo asset_url(); ?>/upload/papers/<?php echo $paper->paper; ?>" target="_blank">programa</a></p>

    <h3>Avaliar</h3>

    <?php echo form_open(base_url('dashboard/paper/accept'), array('id' => 'formAcceptPaper','novalidate' => '','class' => 'waiting')); ?>
        <input style="display:none;" class="formRedirect" value="<?php echo base_url('dashboard/paper/evaluate');?>" />
        <input style="display:none;" type="text" name="id" value="<?php echo $paper->id; ?>">
        <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12">
                <button type="submit" class="btn btn-block btn-large btn-success">Aceitar</button>
            </div>
            <div class="clearfix"></div>
        </div>
    </form>

    <hr />

    <?php echo form_open(base_url('dashboard/paper/reject'), array('id' => 'formRejectPaper','novalidate' => '','class' => 'waiting')); ?>
        <input style="display:none;" class="formRedirect" value="<?php echo base_url('dashboard/paper/evaluate');?>" />
        <input style="display:none;" type="text" name="id" value="<?php echo $paper->id; ?>">
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