<h5 style="font-weight:bold;">MINICURSOS</h5>

<table class="table table-hover">

    <?php foreach($minicoursesResult as $mr): ?>
        
        <tr class="active" style="text-align:center;" >
            <td style="width:40%">
                <b><?php echo $mr->title; ?></b>
            </td>
            <td style="width:60%">
                <a style="cursor:pointer;" data-id="<?php echo $mr->id; ?>" data-subid="<?php echo $subevent->id; ?>" data-type="minicourse" data-target=".modal-subevent-add-activity-exe" data-toggle="modal" >Acrescentar Atividade</a>
            </td>
        </tr>
    
    <?php endforeach; ?>    
       
    <?php if(!count($minicoursesResult)): ?>
        <tr class="danger">
            <td>Nenhum resultado.</td>
        </tr>
    <?php endif; ?>

</table>

<h5 style="font-weight:bold;">CONFERÊNCIAS</h5>

<table class="table table-hover">

    <?php foreach($conferencesResult as $cr): ?>
        
        <tr class="active" style="text-align:center;" >
            <td style="width:40%">
                <b><?php echo $cr->title; ?></b>
            </td>
            <td style="width:60%">
                <a style="cursor:pointer;" data-subid="<?php echo $subevent->id; ?>" data-id="<?php echo $cr->id; ?>" data-type="conference" data-target=".modal-subevent-add-activity-exe" data-toggle="modal" >Acrescentar Atividade</a>
            </td>
        </tr>
    
    <?php endforeach; ?>    
       
    <?php if(!count($conferencesResult)): ?>
        <tr class="danger">
            <td>Nenhum resultado.</td>
        </tr>
    <?php endif; ?>

</table>

<h5 style="font-weight:bold;">OFICINAS</h5>

<table class="table table-hover">

    <?php foreach($workshopsResult as $wr): ?>
        
        <tr class="active" style="text-align:center;" >
            <td style="width:40%">
                <b><?php echo $wr->title; ?></b>
            </td>
            <td style="width:60%">
                <a style="cursor:pointer;" data-subid="<?php echo $subevent->id; ?>" data-id="<?php echo $wr->id; ?>" data-type="workshop" data-target=".modal-subevent-add-activity-exe" data-toggle="modal" >Acrescentar Atividade</a>
            </td>
        </tr>
    
    <?php endforeach; ?>    
       
    <?php if(!count($conferencesResult)): ?>
        <tr class="danger">
            <td>Nenhum resultado.</td>
        </tr>
    <?php endif; ?>

</table>

<h5 style="font-weight:bold;">MESAS-REDONDAS</h5>

<table class="table table-hover">

    <?php foreach($roundtablesResult as $rr): ?>
        
        <tr class="active" style="text-align:center;" >
            <td style="width:40%">
                <b><?php echo $rr->title; ?></b>
            </td>
            <td style="width:60%">
                <a style="cursor:pointer;" data-subid="<?php echo $subevent->id; ?>" data-id="<?php echo $rr->id; ?>" data-type="roundtable" data-target=".modal-subevent-add-activity-exe" data-toggle="modal" >Acrescentar Atividade</a>
            </td>
        </tr>
    
    <?php endforeach; ?>    
       
    <?php if(!count($roundtablesResult)): ?>
        <tr class="danger">
            <td>Nenhum resultado.</td>
        </tr>
    <?php endif; ?>

</table>

<script>

    $(document).ready(function(){

        /*$('.modal-subevent-add-activity-exe').click(function(){
            alert();
        });*/

    });

    
</script>