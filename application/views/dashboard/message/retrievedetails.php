<h3>Mensagem</h3>
<p><b>Nome:</b> <?php echo $msg->name; ?></p>
<p><b>Email:</b> <?php echo $msg->email; ?></p>
<p><b>Assunto:</b> <?php echo $msg->subject; ?></p>
<p><b>Mensagem:</b> <?php echo $msg->message; ?></p>
<p><b>Enviada em:</b> <?php echo date("d/m/Y", strtotime($msg->createdAt));  ?></p>

<hr/>

<h3>Responder</h3>
<p class="text-danger">Ao responder esta messagem, <b><?php echo $msg->name; ?></b> enviará novas dúvidas, caso tenha alguma, para o email <b><?php echo $user->email; ?></b>. </p>

<?php echo form_open(base_url('dashboard/message/reply'), array('id' => 'formEnviarMensagem','novalidate' => '','class' => 'waiting')); ?>

    <input name="id" style="display:none;" value="<?php echo $msg->id; ?>" />

    <div class="row">

        <div class="col-md-12 col-xs-12 col-sm-12">
            
            <div class="form-group">
                <label for="name">Para *</label>
                <input readOnly class="form-control" value="<?php echo $msg->email; ?>" name="to" />
            </div>
            
            <div class="form-group">
                <label for="name">Assunto *</label>
                <input readOnly class="form-control" value="[Resposta] <?php echo $msg->subject; ?>" name="subject" />
            </div>

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