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

<div class="register-form">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">

            <h1>XX SEMINÁRIO DE PESQUISA DO CCSA/UFRN</h1>
            <div class='form'>
                <h2>Cadastre-se</h2>
                <?php echo form_open(base_url('user/createUser'), array('id' => '','novalidate' => '', 'class' => 'waiting')); ?>
                
                    <div class="row">
                        <div class="col-lg-12">
                            
                            <div class="form-group">
                                	<label for="name">Nome Completo *</label>
                                    <input type="text" class="form-control" placeholder="Nome" name="name" value="<?php echo $popform['name']; ?>" required autofocus/>
                                    <p class="text-validation"><?php if($validation!=null): ?> <?php echo $validation['name']; ?> <?php endif; ?></p>
                            </div>
                            
                            <div class="form-group">
                                	<label for="name">Email *</label>
                                    <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo $popform['email']; ?>" required />
                                    <p class="text-validation"><?php if($validation!=null): ?> <?php echo $validation['email']; ?> <?php endif; ?></p>
                            </div>
                            
                            <div class="form-group">
                                	<label for="file">Categoria *</label>
                                    <select class="form-control" name="category">
                                        <option <?php if($popform['category']=='student'): ?> selected="true" <?php endif; ?> value="student">Discente</option>
								  		<option <?php if($popform['category']=='instructor'): ?> selected="true" <?php endif; ?> value="instructor">Docente/Técnico-Administrativo</option>
									</select>
                                    <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['category']; ?> <?php endif; ?></p>
                                    <p style="color:#DDDDDD;" class="help-block">Caso não tenha mais nenhum vínculo acadêmico, inscreva-se como 'Discente'.</p>
                            </div>
                            
                            <div class="form-group">
                                	<label for="name">Instituição de origem *</label>
                                    <input type="email" class="form-control" placeholder="Instituição de origem" name="institution" value="<?php echo $popform['institution']; ?>" required />
                                    <p class="text-validation"><?php if($validation!=null): ?> <?php echo $validation['institution']; ?> <?php endif; ?></p>
                                    <p style="color:#DDDDDD;" class="help-block">Caso não tenha mais nenhum vínculo acadêmico, coloque 'nenhuma'.</p>
                            </div>
                            
                            <div class="form-group">
                                	<label for="name">Telefone *</label>
                                    <input id="register-masked-phone" type="tel" class="form-control" placeholder="Telefone" name="phone" value="<?php echo $popform['phone']; ?>" required />
                                    <p class="text-validation"><?php if($validation!=null): ?> <?php echo $validation['phone']; ?> <?php endif; ?></p>
                            </div>
                            
                            <div class="form-group">
                                	<label for="name">Senha *</label>
                                    <input type="password" class="form-control" placeholder="Senha" name="password" required />
                                    <p class="text-validation"><?php if($validation!=null): ?> <?php echo $validation['password']; ?> <?php endif; ?></p>
                            </div>
                            
                            <div class="form-group">
                                	<label for="name">Repetir Senha *</label>
                                    <input type="password" class="form-control" placeholder="Repetir Senha" name="rpassword" required />
                                    <p class="text-validation"><?php if($validation!=null): ?> <?php echo $validation['rpassword']; ?> <?php endif; ?></p>
                            </div>
                            
                            <button type="submit">Registrar</button>
                            
                        </div>
                    </div>
                
                </form>
            </div>

        </div>
    </div>
</div>