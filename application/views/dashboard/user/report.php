<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Inscritos - Gerar Relatório</h1>
        
        <?php echo form_open(base_url('dashboard/user/createreport'), array('id' => 'formCreateReport','novalidate' => '', 'target' => '_blank')); ?>
        
            <div class="form-group">
                <label for="file">Filtro 1 *</label>
                <select name="filter1" class="form-control">
                    <option value="all">Todos</option>
                    <option value="no">Somente os que não realizaram o pagamento</option>
                    <option value="accepted">Somente os que já estão com pagamento confirmado</option>
                    <option value="free">Somente os isentos</option>
                    <option value="enrolled">Somente os confirmados no evento (Isentos + Pagamentos)</option>
                </select>
            </div>
        
            <div class="form-group">
                <label for="file">Filtro 2 *</label>
                <select name="filter2" class="form-control">
                    <option value="all">Todos</option>
                    <option value="student">Discente</option>
                    <option value="instructor">Docente/Técnico-Administrativo</option>
                </select>
            </div>
        
            <div class="form-group">
                <label for="file">Ordem *</label>
                <select name="order" class="form-control">
                    <option value="nameasc">Nome Crescente</option>
                    <option value="namedesc">Nome Decrescente</option>
                </select>
            </div>
        
            <div class="form-group">
                <label for="file">Campos do Relatório *</label>
                <br/>
                <?php foreach($fields as $key => $value): ?>
                    <label style="display:inline-block; margin-right:20px;">
                        <input type="checkbox" name="fields[]" value="<?php echo $key; ?>">
                        <?php echo $value; ?>   
                    </label>
                <?php endforeach; ?>
            </div>
        
            <div class="form-group">
                <label for="file">Separador entre registros</label>
                <select name="sepreg" class="form-control">
                    <option value="1">Quebra de Linha</option>
                </select>
            </div>
        
            <div class="form-group">
                <label for="file">Separador entre campos do registro</label>
                <input name="sepfields" class="form-control" type="text" placeholder="Padrão: Espaço"/>
            </div>
        
            <div class="form-group">
                <label for="file">Geração de Relatório</label>
                <input type="radio" name="generate" value="html" checked> Tabela HTML
                <input type="radio" name="generate" value="text" > Texto puro
            </div>
        
            <div class="col-lg-12 text-center success-container">
                <div class="success"></div>
                <button type="submit" class="btn btn-lg btn-success">Gerar Relatório</button>
            </div>
        
        </form>
        
    </div>
</div>