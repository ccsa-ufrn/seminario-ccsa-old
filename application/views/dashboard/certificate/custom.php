<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Gerar Certificado Customizado</h1>
        
        <?php echo form_open(base_url('dashboard/certificate/createcustom'), array('target' => '_blank' ,'id' => 'formCreateCustom','novalidate' => '', 'target' => '_blank')); ?>
        
            <div class="form-group">
                <label for="file">Texto Customizado</label>
                <textarea name="text" rows="5" class="form-control"></textarea>
            </div>

            <input class="btn btn-success" type="submit" value="Gerar Certificado" >
        
        </form>
        
    </div>
</div>