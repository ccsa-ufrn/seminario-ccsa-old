

            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Configurações do sistema</h1>
                    
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
                    
                    <p class="text-danger">Muito cuidado ao manipular estas informações.</p>
                    
                    <?php foreach($configs as $c): ?>
                
                    <div class="form-group">
                        <label for="file"><?php echo $c->nickname; ?></label>
                        
                        <?php if($c->type=='text'): ?>
                            <input readOnly type="text" class="form-control" name="<?php echo $c->name; ?>" value="<?php echo $c->value; ?>" />
                            <a class="edit-config" href="javascript:void(0);" data-toggle="modal" data-target=".modal-config-edit" data-id="<?php echo $c->id; ?>" >Editar</a>
                        <?php elseif($c->type=='date'): ?>
                            <input readOnly type="text" class="form-control input-date-format" name="<?php echo $c->name; ?>" value="<?php echo date("d/m/Y", strtotime($c->value));  ?>" />
                            <a class="edit-config" href="javascript:void(0);" data-toggle="modal" data-target=".modal-config-edit" data-id="<?php echo $c->id; ?>" >Editar</a>
                        <?php elseif($c->type=='boolean'): ?>
                            <input readOnly disabled type="checkbox" name="<?php echo $c->name; ?>" <?php if($c->value=='true'): ?> checked <?php endif; ?> >
                            <br> <a class="edit-config" href="javascript:void(0);" data-toggle="modal" data-target=".modal-config-edit" data-id="<?php echo $c->id; ?>" >Editar</a>
                        <?php endif; ?>
                    
                    </div>
                
                    <?php endforeach; ?>

                    <script type="text/javascript">

                        jQuery(function($){
                
                           $(".input-date-format").mask("99/99/9999");
                            
                        });

                    </script>
                    
                </div>
            </div>

            <div class="modal fade modal-config-edit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm" >
                    <div class="modal-content" style="padding:20px;">

                        <!-- HERE GOES CONTENT -->
                        
                    </div>
                </div>
            </div>