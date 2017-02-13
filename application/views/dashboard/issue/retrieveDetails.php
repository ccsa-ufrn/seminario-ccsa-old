<h3>Detalhes do Chamado</h3>

<p><b>Título: </b><?php echo $issue->title; ?></p>
<p><b>Descrição: </b><?php echo $issue->description; ?></p>
<?php if(isset($issue->image) && $issue->image!='' ): ?>
<p><b>Anexo: </b> <a href="<?php echo asset_url(); ?>upload/issues/<?php echo $issue->image; ?>" target="_blank">Anexo</a></p>
<?php endif; ?>
<p><b>Inscrito:</b> <?php echo $issue->user->name; ?> - <?php echo $issue->user->email; ?></p>

<hr>

<h3>Últimas Respostas</h3>

<?php $i = 0; ?>
<?php foreach($issue_records as $ir): ?>


<?php echo $ir->reply; ?>
<b><?php echo $ir->user->name; ?> - <?php echo date("d/m/Y", strtotime($ir->createdAt));  ?></b>
<?php  if($i++!=count($issue_records)-1): ?><hr/> <?php endif; ?>

<?php endforeach; ?>

<?php if(!count($issue_records)): ?>
    <p class="text-danger">Nenhuma resposta cadastrada ainda.</p>
<?php endif; ?>

<hr>

<h3>Nova Resposta</h3>

<?php echo form_open(base_url('dashboard/issue/reply'), array('id' => 'formIssueResponse','novalidate' => '','class' => 'waiting')); ?>

    <input name="id" style="display:none;" value="<?php echo $issue->id; ?>" />

    <div class="row">

        <div class="col-md-12 col-xs-12 col-sm-12">

            <div class="form-group">
                <label for="name">Resposta *</label>
                <textarea class="form-control" placeholder="Resposta" name="reply" rows="5"></textarea>
            </div>

        </div>
        <div class="col-md-12 col-xs-12 col-sm-12">
            <button type="submit" class="btn btn-block btn-large btn-success" >Responder</button>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12 success-container text-center">
            <div class="success"></div>
        </div>

    </div>

</form>

<hr>

<?php echo form_open(base_url('dashboard/issue/close'), array('id' => 'formCloseIssue','novalidate' => '','class' => 'waiting')); ?>

    <input style="display:none;" type="text" name="id" value="<?php echo $issue->id; ?>">
    <div class="row">

        <div class="col-md-12 col-xs-12 col-sm-12">
            <button type="submit" class="btn btn-block btn-large btn-danger">Fechar Chamado</button>
        </div>

    </div>
</form>