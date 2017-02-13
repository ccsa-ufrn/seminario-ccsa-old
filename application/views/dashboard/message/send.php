<div class="row">
	<div class="col-lg-12">
    	<h1 class="page-header">Mensagens</h1>

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

			<?php echo form_open(base_url('dashboard/message/dosend'), array('id' => 'formSendMessage','novalidate' => '','class' => 'waiting')); ?>

			    <div class="row">

			        <div class="col-md-12 col-xs-12 col-sm-12">

						<div class="form-group">
			                <label for="name">Filtro *</label>
			                <select class="form-control" name="filter">
							  <option value="insall">Todos os Inscritos</option>
							  <option value="insnopay">Todos que não enviaram ainda comprovante de pagamento</option>
							</select>
			            </div>

			            <div class="form-group">
			                <label for="name">Mensagem *</label>
			                <textarea class="form-control" placeholder="Mensagem" name="message" rows="8"></textarea>
			            </div>

			        </div>
			        <div class="col-md-12 col-xs-12 col-sm-12">
			            <button type="submit" class="btn btn-block btn-large btn-success" >Enviar</button>
			        </div>
			        <div class="clearfix"></div>
			        <div class="col-lg-12 success-container text-center">
			            <div class="success"></div>
			        </div>

			    </div>

			</form>

	</div>
</div>