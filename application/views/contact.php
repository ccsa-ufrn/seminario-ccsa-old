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

<div class="contact-form">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">

            <h1>XX SEMINÁRIO DE PESQUISA DO CCSA/UFRN</h1>
            <div class='form'>
                <h2>Contato</h2>
                <?php echo form_open(base_url('submitMessage'), array('id' => '','novalidate' => '')); ?>
                
                    <div class="row">
                        <div class="col-lg-12">
                            
                            <div class="form-group">
                                	<label for="name">Nome *</label>
                                    <input type="text" class="form-control" placeholder="Nome" name="name" value="<?php echo $popform['name']; ?>" required autofocus/>
                                    <p class="text-validation"><?php if($validation!=null): ?> <?php echo $validation['name']; ?> <?php endif; ?></p>
                            </div>
                            
                            <div class="form-group">
                                	<label for="name">Email *</label>
                                    <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo $popform['email']; ?>" required />
                                    <p class="text-validation"><?php if($validation!=null): ?> <?php echo $validation['email']; ?> <?php endif; ?></p>
                            </div>
                            
                            <div class="form-group">
                                	<label for="name">Assunto *</label>
                                    <input type="text" class="form-control" placeholder="Assunto" name="subject" value="<?php echo $popform['subject']; ?>" required />
                                    <p class="text-validation"><?php if($validation!=null): ?> <?php echo $validation['subject']; ?> <?php endif; ?></p>
                            </div>
                            
                            <div class="form-group">
                                	<label for="name">Mensagem *</label>
                                <textarea class="form-control" placeholder="Mensagem" name="message" style="min-height:100px;" required><?php echo $popform['message']; ?></textarea>
                                    <p class="text-validation"><?php if($validation!=null): ?> <?php echo $validation['message']; ?> <?php endif; ?></p>
                            </div>
                            
                            <button type="submit">Enviar</button>
                            
                        </div>
                    </div>
                
                </form>
            </div>

        </div>
    </div>
</div>