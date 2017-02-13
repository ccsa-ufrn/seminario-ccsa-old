<?php if($success!=null): ?>
    <div class='alert alert-success text-center'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
        <strong><?php echo $success; ?></strong>
    </div>
<?php endif; ?>

<?php if($error!=null): ?>
    <div class='alert alert-danger text-center'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
        <strong><?php echo $error; ?></strong>
    </div>
<?php endif; ?>

<div class="resetpassword-form">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">

            <h1>XX SEMINÁRIO DE PESQUISA DO CCSA/UFRN</h1>
            <div class='form'>
                <h2>RECUPERAR SENHA</h2>
                <?php echo form_open(base_url('resetp'), array('id' => 'resetPasswordForm','novalidate' => '')); ?>
                
                    <div class="row">
                        <div class="col-lg-12">
                            
                            <div class="form-group">
                                	<label for="name">Email *</label>
                                    <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo $popform['email']; ?>" required autofocus />
                                    <p class="text-validation"><?php if($validation!=null): ?> <?php echo $validation['email']; ?> <?php endif; ?></p>
                            </div>
                            
                            <button type="submit">Recuperar Senha</button>
                            
                            <figure class="loading" style="display:none;font-size:12;margin-top:20px;color:white;"><img  src="<?php echo asset_url(); ?>img/loading.gif" /> Carregando, aguarde... </figure>
                               
                        </div>
                    </div>
                
                </form>
            </div>

        </div>
    </div>
</div>