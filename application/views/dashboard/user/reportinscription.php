<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Relatório de Inscritos</h1>
        
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

        <p style="text-align:center;">
            <b>Por nome:</b>
            <input type="text" id="searchbyname"/>
            <input style="display:none;" value="" id="searchbyname-value" />
            <input style="display:none;" value="<?php echo base_url(); ?>" id="searchbyname-baselink" />
            <input style="display:none;" value="<?php echo base_url('dashboard/user/reportinscription/retrieveLinkSearchByName'); ?>" id="searchbyname-retrieve-link" />
            <a class="btn btn-default" id="searchbyname-btn">Pesquisar</a>
        </p>
        
        <table class="table table-hover">
            
            <?php foreach($users as $user): ?>
                <tr>
                    <td style="text-transform:uppercase;width:60%;"><b><?php echo $user->name; ?></b></td>
                    <td style="text-transform:uppercase;width:40%;text-align:right;"><a href="<?php echo base_url('dashboard/user/createreportinscription/id/'.$user->id); ?>" target="_blank">Visualizar Relatório</a></td>  
                </tr>
            <?php endforeach; ?>
        
        </table>
        
            <p class="text-center" style="margin-top:20px;margin-bottom:0px;"><b><?php echo $pagination['records']; ?></b> registros encontrados.</p>
        
        <!-- PAGINATION -->
        <nav style="text-align:center;">
            <ul class="pagination">
                
                <?php if($pagination['page']<=1): ?>  
                    <li class="disabled" >
                      <a href="javascript:void(0);" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>
                <?php else: ?>
                    <li <?php if($pagination['page']<=1): ?> class="disabled" <?php endif; ?> >
                    <a href="<?php echo base_url().insertGetParameter(uri_string(),'page',$pagination['page']-1); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <?php for($i=0;$i<$pagination['pages'];++$i): ?>
                
                    <li <?php if($i+1==$pagination['page']): ?> class="active" <?php endif; ?> >
                        <a href="<?php echo base_url().insertGetParameter(uri_string(),'page',$i+1); ?>">
                            <?php echo $i+1; ?>
                        </a>
                    </li>
                
                <?php endfor; ?>
                
                <?php if($pagination['page']>=$pagination['pages']): ?>  
                    <li class="disabled" >
                      <a href="javascript:void(0);" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                <?php else: ?>
                    <li>
                      <a href="<?php echo base_url().insertGetParameter(uri_string(),'page',$pagination['page']+1); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                <?php endif; ?>
                
          </ul>
        </nav>
        <!-- ./PAGINATION -->
        
    </div>
</div>