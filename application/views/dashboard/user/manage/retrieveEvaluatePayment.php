<p class="text-center"><b>Verificar comprovante de pagamento</b></p>

<p class="text-center" ><a href="<?php echo asset_url(); ?>/upload/payment/<?php echo $user->payment; ?>" target="_blank" ><i class="fa fa-file-pdf-o fa-5x"></i> <?php echo $user->payment; ?></a></p>

<hr/>

<p>O inscrito é <?php if($user->type=='instructor') echo 'Professor'; else if($user->type=='student') echo 'Estudante'; ?> </p>

<hr/>

<?php echo form_open(base_url('dashboard/user/manage/acceptpayment'), array('id' => 'formAcceptPayment','novalidate' => '','class' => 'waiting')); ?>
    <input style="display:none;" type="text" name="id" value="<?php echo $user->id; ?>">
    <div class="row">
        <div style="margin-top:10px;" class="col-md-12 col-xs-12 col-sm-12">
            <button type="submit" class="btn btn-block btn-large btn-success">Aceitar</button>
        </div>
        <div class="clearfix"></div>
    </div>
</form>

<hr/>

<?php echo form_open(base_url('dashboard/user/manage/rejectpayment'), array('id' => 'formRejectPayment','novalidate' => '','class' => 'waiting')); ?>
    <input style="display:none;" type="text" name="id" value="<?php echo $user->id; ?>">
    <div class="row">
        <div style="margin-top:10px;" class="col-md-12 col-xs-12 col-sm-12">
            <div class="form-group">
                <label for="file">Observação *</label>
                <textarea rows="4" class="form-control" placeholder="Observação *" name="observation"></textarea>
            </div>
            <button type="submit" class="btn btn-block btn-large btn-danger">Rejeitar</button>
        </div>
        <div class="clearfix"></div>
    </div>
</form>