<div class="row">
    <div class="col-lg-12">
        
        <h1 class="page-header">Avaliação de Artigos</h1>

        <?php if($success!=null): ?>
            <div class='alert alert-success'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <strong><?php echo $success; ?></strong>
            </div>
        <?php endif; ?>

        <?php if($error!=null): ?>
            <div class='alert alert-danger'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <strong><?php echo $error; ?></strong>
            </div>
        <?php endif; ?>
        
        <?php 
        
            $ccount = 0;
            foreach($tgs as $tg)
                $ccount += count(
                                $tg
                                ->withCondition(
                                    'evaluation=? AND user_id!=? AND avaliation1_user_id != ? ',
                                    array(
                                        'pending',
                                        $this->session->userdata('user_id'),
                                        $this->session->userdata('user_id')
                                    )
                                )
                                ->ownPaperList
                            );
                
        ?>
        
        <?php if($ccount): ?>
        
            <div class="alert alert-danger">
                <b>Atenção! </b>
                <?php if($ccount==1): ?>
                    1 artigo precisa ser avaliado.
                <?php else: ?>
                    <?php echo $ccount; ?> artigos precisam ser avaliados.
                <?php endif; ?>
            </div>
            
        <?php endif; ?>

        <h2>Avaliar</h2>
        
        <?php foreach ($tgs as $tg): ?>
            
            <h4><?php echo $tg->name; ?></h4>
        
            <table id="tablePendingPapers" class="table table-striped table-bordered table-condensed">
                
                <?php 
                
                    $papers = R::find(
                        'paper',
                        'evaluation=? AND thematicgroup_id=? AND user_id!=? AND avaliation1_user_id != ?',
                        array(
                            'pending',
                            $tg->id,
                            $this->session->userdata('user_id'),
                            $this->session->userdata('user_id')
                        )
                    );
                    
                ?>

                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Avaliar</th>
                    </tr>
                </thead>
                <?php foreach ($papers as $p): ?>
                    <tr id="tr-papers-<?php echo $p->id; ?>">
                        <td class="name"><?php echo $p->title; ?></td>
                        <td class="editar" style="width:20%;">
                            <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalEditCoordinator" data-data="<?php echo $p->id; ?>">
                                <i class="fa fa-pencil-square fa-2x"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach ?>

                <?php if(!count($papers)): ?>
                    <tr>
                        <td colspan="2">Não há nenhum artigo esperando avaliação.</td>
                    </tr>                        
                <?php endif; ?>

            </table>
        
        <?php endforeach; ?>            
        
    </div>
    <!-- /.col-lg-12 -->
</div>


<input id="ep-mr" value="<?php echo base_url('dashboard/paper/retrievepaperdetails'); ?>" style="display:none;" />
<button style="display:none;" id="openEvaluatePaperDetails" data-toggle="modal" data-target="#modalEvaluatePaper">asd</button>

<!-- Modal -->
<div class="modal fade" id="modalEvaluatePaper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Avaliar Artigo</h4>
            </div>
            <div class="modal-body">

                <!-- AJAX HERE -->
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>