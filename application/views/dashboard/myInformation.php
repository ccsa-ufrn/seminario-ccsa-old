

            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Minhas informações</h1>
                    <?php echo form_open(base_url('formError'), array('id' => 'formMyInformation','novalidate' => '')); ?>
                        <input style="display:none;" class="formAction" value="<?php echo base_url('dashboard/updateUser');?>" />
                        <input style="display:none;" class="formRedirect" value="<?php echo base_url('dashboard/myInformation');?>" />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Nome completo *" <?php if(isset($user->certgen)): ?> readOnly <?php endif; ?> name="name" value="<?php print_r($user->name); ?>" >
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Seu email *" name="email"  value="<?php print_r($user->email); ?>" disabled>
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" placeholder="Seu telefone *" name="phone" value="<?php print_r($user->phone); ?>" required data-validation-required-message="Por favor, insira seu telefone.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="CPF *" name="cpf"<?php if(isset($user->certgen)): ?> readOnly <?php endif; ?>  value="<?php print_r($user->cpf); ?>" required>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Senha antiga" name="oldPass">
                                    <p class="help-block text-danger"></p>
                                </div>
                                
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Nova senha" name="newPass" />
                                    <p class="help-block text-danger"></p>
                                </div>
                                
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Repita a nova senha" name="newRpass" />
                                    <p class="help-block text-danger"></p>
                                </div>
                                
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center success-container">
                                <div class="success"></div>
                                <button type="submit" class="btn btn-lg btn-success">Atualizar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.col-lg-12 -->
            </div>

