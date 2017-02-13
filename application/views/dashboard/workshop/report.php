<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Inscritos em Oficina - Gerar Relatório</h1>
        
        <?php echo form_open(base_url('dashboard/workshop/createreport'), array('id' => 'formWorkshopCreateReport','novalidate' => '', 'target' => '_blank')); ?>
        
            <div class="form-group">
                <label for="file">Oficina *</label>
                <select name="workshop" class="form-control" style="text-transform:uppercase;">
                    <?php foreach ($workshops as $m): ?>
                        <option value="<?php echo $m->id; ?>" ><?php echo $m->title; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <input type="checkbox" name="list" value="list"> Em formato de lista de presença
            </div>
        
            <div class="col-lg-12 text-center success-container">
                <div class="success"></div>
                <button type="submit" class="btn btn-lg btn-success">Gerar Relatório</button>
            </div>
        
        </form>
        
    </div>
</div>