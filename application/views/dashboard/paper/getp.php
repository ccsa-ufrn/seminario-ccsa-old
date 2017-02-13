<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Baixar Artigo</h1>
        
        <table class="datatable table table-striped table-bordered ">

            <thead>
                <tr>
                    <th>
                        Nome
                    </th>
                    <th style="width: 100px;">
                        
                    </th>
                </tr>
            </thead>
            
            <tbody>
                
                <?php foreach ($papers as $p): ?>
                    <tr>
                        <td>
                            <?php echo titleCase($p->title); ?>
                        </td>
                        <td class="text-center">
                            <a href="<?php echo asset_url().'upload/papers/'.$p->paper; ?>" target="_blank">Baixar Artigo</a>
                        </td>
                    </tr>
                <?php endforeach ?>
                
            </tbody>

        </table>
        
    </div>
</div>