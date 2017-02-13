<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Inscritos em Mesas-Redondas - Gerar Relatório</h1>
        
        <?php echo form_open(base_url('dashboard/roundtable/createreport'), array('id' => 'formRoundtableCreateReport','novalidate' => '', 'target' => '_blank')); ?>
        
            <div class="form-group">
                <label for="file">Mesa-Redonda *</label>
                <select name="roundtable" class="form-control" style="text-transform:uppercase;">
                    <?php foreach ($roundtables as $m): ?>
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