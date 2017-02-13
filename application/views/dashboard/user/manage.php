<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Gerenciamento de Inscritos</h1>
        
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
            <b>Filtro:</b>       
            <select id="filter-select">
                
                <option 
                    value="<?php echo base_url().insertTwoGetParameters(uri_string(),'filter','all','page','1'); ?>"
                    <?php if(getValueParameter(uri_string(),'filter')=='all'): ?> selected <?php endif; ?>
                >
                    Todos
                </option>
                
                <option 
                    value="<?php echo base_url().insertTwoGetParameters(uri_string(),'filter','no','page','1'); ?>"
                    <?php if(getValueParameter(uri_string(),'filter')=='no'): ?> selected <?php endif; ?>
                >
                    Não enviaram o comprovante
                </option>
                
                <option 
                    value="<?php echo base_url().insertTwoGetParameters(uri_string(),'filter','pendent','page','1'); ?>"
                    <?php if(getValueParameter(uri_string(),'filter')=='pendent'): ?> selected <?php endif; ?>
                >
                    Enviaram o comprovante que ainda não foi avaliado
                </option>
                
                <option 
                    value="<?php echo base_url().insertTwoGetParameters(uri_string(),'filter','free','page','1'); ?>"
                    <?php if(getValueParameter(uri_string(),'filter')=='free'): ?> selected <?php endif; ?>
                >
                    Isentos
                </option>
                
                <option 
                    value="<?php echo base_url().insertTwoGetParameters(uri_string(),'filter','accepted','page','1'); ?>"
                    <?php if(getValueParameter(uri_string(),'filter')=='accepted'): ?> selected <?php endif; ?>
                >
                    Realizaram o pagamento
                </option>
                
                <option 
                    value="<?php echo base_url().insertTwoGetParameters(uri_string(),'filter','enrolled','page','1'); ?>"
                    <?php if(getValueParameter(uri_string(),'filter')=='enrolled'): ?> selected <?php endif; ?>
                >
                    Confirmados no evento
                </option>
                
            </select>
        </p>
        
        <p style="text-align:center;">
            <b>Ordenar:</b>       
            <select id="order-select" >
                
                <option 
                    value="<?php echo base_url().insertGetParameter(uri_string(),'order','no'); ?>"
                    <?php if(getValueParameter(uri_string(),'order')=='no'): ?> selected <?php endif; ?>
                >
                    Sem ordem
                </option>
                
                <option 
                    value="<?php echo base_url().insertGetParameter(uri_string(),'order','cresc'); ?>"
                    <?php if(getValueParameter(uri_string(),'order')=='cresc'): ?> selected <?php endif; ?>
                >
                    Nome crescente
                </option>
                
                <option 
                    value="<?php echo base_url().insertGetParameter(uri_string(),'order','desc'); ?>"
                    <?php if(getValueParameter(uri_string(),'order')=='desc'): ?> selected <?php endif; ?>
                >
                    Nome descrescente
                </option>
            
            </select>
        </p>

        <p style="text-align:center;">
            <b>Por nome:</b>
            <input type="text" id="searchbyname"/>
            <input style="display:none;" value="" id="searchbyname-value" />
            <input style="display:none;" value="<?php echo base_url(); ?>" id="searchbyname-baselink" />
            <input style="display:none;" value="<?php echo base_url('dashboard/user/manage/retrieveLinkSearchByName'); ?>" id="searchbyname-retrieve-link" />
            <a class="btn btn-default" id="searchbyname-btn">Pesquisar</a>
        </p>
        
        <table class="table table-hover">
            
            <?php foreach($users as $user): ?>
                <tr>
                    <td style="text-transform:uppercase;width:40%;"><b><?php echo $user->name; ?></b></td>
                <?php if($user->paid=='pendent'): ?>
                        <td class="text-warning">Comprovante esperando avaliação</td>
                        <td><a href="javascript:void(0);" data-toggle="modal" data-target=".modal-details-enrroled" data-data="<?php echo $user->id; ?>" >Detalhes do inscrito</a></td>
                        <td><a href="javascript:void(0);" data-toggle="modal" data-target=".modal-evaluate-payment" data-data="<?php echo $user->id; ?>" ><b>Avaliar comprovante</b></a></td>
                <?php elseif($user->paid=='accepted'): ?>
                        <td class="text-success">Comprovante avaliado</td>
                        <td><a href="javascript:void(0);" data-toggle="modal" data-target=".modal-details-enrroled" data-data="<?php echo $user->id; ?>" >Detalhes do inscrito</a></td>
                        <td></td>
                <?php elseif($user->paid=='free'): ?>
                        <td class="text-success">Isento</td>
                        <td><a href="javascript:void(0);" data-toggle="modal" data-target=".modal-details-enrroled" data-data="<?php echo $user->id; ?>" >Detalhes do inscrito</a></td>
                        <td></td>
                <?php else: ?>
                        <td class="text-danger">Não enviou o comprovante ainda</td>
                        <td><a href="javascript:void(0);" data-toggle="modal" data-target=".modal-details-enrroled" data-data="<?php echo $user->id; ?>">Detalhes do inscrito</a></td>
                        <td><b><a class="button-user-free-payment" style="cursor:pointer;" data-data="<?php echo $user->id; ?>">Isenção</a></b></td>
                        <?php echo form_open(base_url('dashboard/user/manage/freepayment'), array('id' => 'formFreePayment-'.$user->id,'novalidate' => '','class' => 'waiting')); ?>
                            <input style="display:none;" type="text" name="id" value="<?php echo $user->id; ?>">
                        </form>
                <?php endif; ?>    
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

<input id="eide" style="display:none;" value="<?php echo base_url('dashboard/user/manage/retrievedetails'); ?>" />

<div class="modal fade modal-details-enrroled" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

        <div class="modal-body">
            
            Carregando...
            
        </div>

    </div>
  </div>
</div>

<input id="eiep" style="display:none;" value="<?php echo base_url('dashboard/user/manage/retrieveevaluatepayment'); ?>" />

<div class="modal fade modal-evaluate-payment" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

        <div class="modal-body">
            
            Carregando...
            
        </div>

    </div>
  </div>
</div>