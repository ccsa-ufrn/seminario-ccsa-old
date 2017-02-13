<h3 style="font-weight:bolder;font-size:16px;">ATUALIZAR INFORMAÇÕES DA MESA-REDONDA</h3>

<?php echo form_open(base_url('dashboard/roundtable/updateconsolidated'), array('id' => 'formUpdateConsolidateRoundtable','novalidate' => '','class' => 'waiting')); ?>
    <input style="display:none;" type="text" name="id" value="<?php echo $rt['id']; ?>">
    <div class="row">

        <div class="col-md-12 col-xs-12 col-sm-12">
        	
            <div class="form-group">
                <label for="file">Quantidade de Vagas *</label>
                <input type="number" class="form-control" placeholder="Vagas" value="<?php echo $rt->consolidatedvacancies; ?>" name="vacancies" />
            </div>

            <div class="form-group">
                <label for="file">Local do Minicurso *</label>
                <input type="text" class="form-control" placeholder="Local" name="local" value="<?php echo $rt->consolidatedlocal; ?>" />
            </div>

            <div class="form-group">
                <label for="file">Dia/Turno *</label>
                <select class="form-control" name="dayshift">
                    
                    <?php foreach ($rds as $ds): ?>
                        <?php if($ds['minicourse']==null || !isset($ds['minicourse'])): ?>

                        	<?php $compare = $rt->roundtabledayshift->id; ?>
                            <option <?php if($compare==$ds->id): ?> selected <?php endif; ?> value="<?php echo $ds->id; ?>"><?php echo date("d/m/Y", strtotime($ds->date)); ?> - <?php echo $ds->shift ?> </option>
                        <?php endif; ?>
                    <?php endforeach ?>
                    
                    <?php if(!count($rds)): ?>
                        <option value="-1">Não há dias/turnos disponíveis</option>
                    <?php endif; ?>
                    
                </select>
                <span id="helpBlock" class="help-block">Utilize o Ctrl para selecionar mais de um dia/turno.</span>
            </div>

        </div>
        <div class="col-md-12 col-xs-12 col-sm-12">
            <button type="submit" class="btn btn-block btn-large btn-success">Atualizar</button>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12 success-container text-center">
            <div class="success"></div>
        </div>

    </div>
</form>