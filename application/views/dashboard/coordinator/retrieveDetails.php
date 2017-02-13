<h3>Detalhes do Coordenador</h3>

<p class="name"><?php echo $coordinator->name; ?></p>
<p class="email"><?php echo $coordinator->email; ?></p>

<h3>Editar Coordenador</h3>

<?php echo form_open(base_url('dashboard/coordinator/update'), array('id' => 'formUpdateCoordinator','novalidate' => '','class'=>'waiting')); ?>
    <input style="display:none;" type="text" name="id" value="<?php echo $coordinator->id; ?>">
    <div class="row">

        <div class="col-md-12 col-xs-12 col-sm-12">

            <div class="form-group">
                <label>Nova Senha *</label>
                <input type="password" class="form-control" placeholder="Nova Senha" name="password" />
            </div>

            <?php 
                $listtgs = array();
                foreach($coordinator->sharedThematicgroupList as $t)
                    $listtgs[] = $t->id;
            ?>

            <div class="form-group">
                <label>Grupos Temáticos *</label>
                <select class="form-control" multiple size="9" name="thematicGroups[]">
                    <?php foreach ($thematicgroups as $tg): ?>
                        <option <?php if(in_array($tg->id,$listtgs)): ?> selected="true" <?php endif; ?> value="<?php echo $tg->id; ?>"><?php echo $tg->name; ?></option>
                    <?php endforeach ?>
                </select>
            </div>

        </div>
        
        <div class="col-md-12 col-xs-12 col-sm-12">
            <button type="submit" class="btn btn-block btn-large btn-success">Salvar</button>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12 success-container text-center">
            <div class="success"></div>
        </div>

    </div>
</form>

<h3>Remover Área</h3>

<?php echo form_open(base_url('dashboard/coordinator/delete'), array('id' => 'formDeleteCoordinator','novalidate' => '','class'=>'waiting')); ?>
    <input style="display:none;" type="text" name="id" value="<?php echo $coordinator->id; ?>">
    <div class="row">

        <div class="col-md-12 col-xs-12 col-sm-12">
            <button type="submit" class="btn btn-block btn-large btn-danger">Remover</button>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12 success-container text-center">
            <div class="success"></div>
        </div>

    </div>
</form>