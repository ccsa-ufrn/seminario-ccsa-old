<div class="row">
    <div class="col-lg-12">
        <h1 class="like">Validação de Certificado</h1>
        
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


        <?php echo form_open(base_url('dashboard/certificate/validate'), array('target' => '_blank','novalidate' => '','class' => 'waiting')); ?>
            
            <div class="form-group">
                <label for="name">Código *</label>
                <input class="form-control" placeholder="Código" type="text" name="code" >
            </div>

            <div class="form-group">
                <input class="btn btn-lg btn-success" type="submit" value="Validar">
            </div>
            
        </form>

    </div>
</div>      