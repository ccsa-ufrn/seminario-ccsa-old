<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Subeventos</h1>
        
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
        
        <h4 class="page-header">Criar Subevento</h4>

        <?php echo form_open(base_url('dashboard/subevent/create'), array('id' => 'formCreateSubevent','novalidate' => '','class' => 'waiting')); ?>
            <div class="row">
                <div class="col-md-12">

                    <div class="form-group">
                        <label for="file">Título *</label>
                        <input type="text" class="form-control" placeholder="Título *" name="title" value="<?php echo $popform['']; ?>" />
                        <p class="text-danger"><?php if($validation!=null): ?> <?php echo $validation['title']; ?> <?php endif; ?></p>
                    </div>

                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12 text-center success-container">
                    <div class="success"></div>
                    <button type="submit" class="btn btn-lg btn-success">Criar Subevento</button>
                </div>
            </div>
        </form>

        <h4 class="page-header">Gerenciar Subeventos</h4>

        <table class="table table-hover">

        <?php foreach($subs as $s): ?>
            
            <tr class="active" style="text-align:center;" >
                <td style="width:40%">
                    <b><?php echo $s->title; ?></b>
                </td>
                <td style="width:20%">
                    <a style="cursor:pointer;" data-id="<?php echo $s->id; ?>" data-toggle="modal" data-target=".modal-subevent-add-activity">Acrescentar Atividade</a>
                </td>
                <td style="width:20%">
                    <a style="cursor:pointer;" data-id="<?php echo $s->id; ?>" data-toggle="modal" data-target=".modal-subevent-edit" >Gerenciar Atividades</a>
                </td>
                <td style="width:20%">
                    <a class="btn-subevent-remove" style="cursor:pointer;" data-id="<?php echo $s->id; ?>" >Remover Subevento</a>
                    <?php echo form_open(base_url('dashboard/subevent/remove'), array('id' => 'formSubeventRemove-'.$s->id,'novalidate' => '','class' => 'waiting','style' => 'display:inline-block;')); ?>
                        <input style="display:none;" name="id" value="<?php echo $s->id; ?>" />
                    </form> 
                </td>
            </tr>
        
        <?php endforeach; ?>    
           
        <?php if(!count($subs)): ?>
            <tr class="danger">
                <td>Nenhum subvento cadastrado.</td>
            </tr>
        <?php endif; ?>  
            
        </table>
    </div>
</div>

<div class="modal fade modal-subevent-add-activity" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content" style="padding:20px;">

            <!-- HERE GOES CONTENT -->
            
        </div>
    </div>
</div>

<div class="modal fade modal-subevent-add-activity-exe" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content" style="padding:20px;">

            <!-- HERE GOES CONTENT -->
            
        </div>
    </div>
</div>

<div class="modal fade modal-subevent-edit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content" style="padding:20px;">

            <!-- HERE GOES CONTENT -->
            
        </div>
    </div>
</div>

<div class="modal fade modal-subevent-activity-edit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content" style="padding:20px;">

            <!-- HERE GOES CONTENT -->
            
        </div>
    </div>
</div>