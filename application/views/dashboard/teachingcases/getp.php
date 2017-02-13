<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Baixar Caso para Ensino</h1>
        
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
                
                <?php foreach ($teachingcases as $p): ?>
                    <tr>
                        <td>
                            <?php echo titleCase($p->title); ?>
                        </td>
                        <td class="text-center">
                            <a href="<?php echo asset_url().'upload/teachingcases/'.$p->teachingcase; ?>" target="_blank">Baixar Caso para Ensino</a>
                        </td>
                    </tr>
                <?php endforeach ?>
                
            </tbody>

        </table>
        
    </div>
</div>