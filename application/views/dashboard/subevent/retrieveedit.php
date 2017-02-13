<h5 style="font-weight:bold;">ATIVIDADES CADASTRADAS</h5>

<table class="table table-hover">

    <?php foreach($subevent->ownSubeventitemList as $si): ?>
        
        <tr class="active" style="text-align:center;" >
            <td style="width:40%">
            	<?php if($si->type=='custom'): ?>
            		<b><?php echo $si->name; ?></b>
            	<?php elseif ($si->type=='minicourse'): ?>
            		<b><?php echo $si->minicourse->title; ?></b>
            	<?php elseif ($si->type=='conference'): ?>
            		<b><?php echo $si->conference->title; ?></b>
            	<?php elseif ($si->type=='roundtable'): ?>
            		<b><?php echo $si->roundtable->title; ?></b>
            	<?php elseif ($si->type=='workshop'): ?>
            		<b><?php echo $si->workshop->title; ?></b>
            	<?php endif; ?>
                
            </td>
            <td style="width:30%">
            	<a style="cursor:pointer;" data-id="<?php echo $si->id; ?>" data-toggle="modal" data-target=".modal-subevent-activity-edit" >Editar Atividade</a>
            </td>
            <td style="width:30%">
                <a class="btn-remove-activity-c" style="cursor:pointer;" data-id="<?php echo $si->id; ?>" >Remover Atividade</a>

                <?php echo form_open(base_url('dashboard/subevent/removesubeventactivity'), array('id' => 'formRemoveSubeventActivity-'.$si->id,'novalidate' => '','class' => 'waiting','style' => 'display:inline-block;')); ?>
                    <input style="display:none;" name="id" value="<?php echo $si->id; ?>" />
                </form> 

            </td>
        </tr>
    
    <?php endforeach; ?>    
       
    <?php if(!count($subevent->ownSubeventitemList)): ?>
        <tr class="danger">
            <td>Nenhuma atividade cadastrada.</td>
        </tr>
    <?php endif; ?>

</table>