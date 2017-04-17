/* =====================================
    MANAGE AREA PAGE
======================================== */

$(document).ready(function() {

    $('#later-upload-art-poster-btn').click(function(){
     var id = $(this).data('data');
      $('#form-later-upload-poster2-'+id+' #later-upload-art-poster').click();
    });

    $('#later-upload-art-poster').change(function(){
      var id = $(this).data('data');
        $('#form-later-upload-poster2-'+id).submit();
    });

    /** POSTER */
    $('#later-upload-poster-btn').click(function(){
      var id = $(this).data('data');
      $('#form-later-upload-poster-'+id+' #later-upload-poster').click();
    });

    $('#later-upload-poster').change(function(){
      var id = $(this).data('data');
      $('#form-later-upload-poster-'+id).submit();
    });

    /*
     * Language Instance
    */
    var langobj = {

        "decimal":        "",
        "emptyTable":     "Não há registros",
        "info":           "Mostrando _START_ até _END_ de _TOTAL_ registros",
        "infoEmpty":      "Mostrando 0 até 0 de 0 registros",
        "infoFiltered":   "(filtrado de um total de _MAX_ registros)",
        "infoPostFix":    "",
        "thousands":      ",",
        "lengthMenu":     "Mostrando _MENU_ registros",
        "loadingRecords": "Carregando...",
        "processing":     "Processando...",
        "search":         "Procurar:",
        "zeroRecords":    "Não foram encontrados resultados",
        "paginate": {
            "first":      "Primeiro",
            "last":       "Último",
            "next":       "Próximo",
            "previous":   "Anterior"
        },
        "aria": {
            "sortAscending":  ": activate to sort column ascending",
            "sortDescending": ": activate to sort column descending"
        }

    };


    /*
     * General Data Table - .table-group
    */
    $('.datatable').DataTable({

        'info' : false,
        'lengthChange' : false,
        'language' : langobj

    });

});

$('#modalEditarArea').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#daard").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalEditarArea div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalEditarArea').on('hidden.bs.modal', function (e) {
	$("#modalEditarArea div.modal-body").html("Carregando... ");
});

/* =====================================
    MANAGE AREA PAGE
======================================== */

$('#user-modalDetailsIssue').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#user-issue-retrieve-details").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#user-modalDetailsIssue div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#user-modalDetailsIssue').on('hidden.bs.modal', function (e) {
	$("#user-modalDetailsIssue div.modal-body").html("Carregando... ");
});

/* =====================================
    MANAGE CONFERENCE PAGE
======================================== */

$('.modal-conference-retrieve-details').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#rc-details").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $(".modal-conference-retrieve-details div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('.certificate-teachingcase-accept').click(function(){

    var id = $(this).data('value');

    if (confirm("Confirmar este Caso para Ensino nos Anais e na Certificação?") == true) {
        $('#formAcceptCertificate-'+id).submit();
    }

});

$('.certificate-teachingcase-reject').click(function(){

    var id = $(this).data('value');

    if (confirm("Confirmar a rejeição deste Caso para Ensino nos Anais e na Certificação?") == true) {
        $('#formRejectCertificate-'+id).submit();
    }

});

$('.certificate-paper-accept').click(function(){

    var id = $(this).data('value');

    if (confirm("Confirmar este artigo nos Anais e na Certificação?") == true) {
        $('#formAcceptCertificate-'+id).submit();
    }

});

$('.certificate-paper-reject').click(function(){

    var id = $(this).data('value');

    if (confirm("Confirmar a rejeição deste artigo nos Anais e na Certificação?") == true) {
        $('#formRejectCertificate-'+id).submit();
    }

});

$('.certificate-poster-accept').click(function(){

    var id = $(this).data('value');

    if (confirm("Confirmar este pôster nos Anais e na Certificação?") == true) {
        $('#formAcceptCertificate-'+id).submit();
    }

});

$('.certificate-poster-reject').click(function(){

    var id = $(this).data('value');

    if (confirm("Confirmar a rejeição deste pôster nos Anais e na Certificação?") == true) {
        $('#formRejectCertificate-'+id).submit();
    }

});

/** HERE **/

$('#modalCertificateMinicourse').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal
    var data = button.data('data');

    $.ajax({
        url: $("#geral-base-url").val()+"dashboard/certificate/retrieveacceptminicourse",
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalCertificateMinicourse div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalCertificateMinicourse').on('hidden.bs.modal', function (e) {
    $("#modalCertificateMinicourse div.modal-body").html("Carregando... ");
});

$('.certificate-minicourse-reject').click(function(){

    var id = $(this).data('value');

    if (confirm("Confirmar a rejeição deste minicurso nos Anais e na Certificação?") == true) {
        $('#formRejectCertificate-'+id).submit();
    }

});

$('#modalAddParticipant').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal
    var id = button.data('id');
    var type = button.data('type');

    $.ajax({
        url: $("#geral-base-url").val()+"dashboard/certificate/retrieveaddparticipant",
        data : { id : button.data('id'), type : button.data('type') }
    }).done(function( html ) {

        $("#modalAddParticipant div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalAddParticipant').on('hidden.bs.modal', function (e) {
    $("#modalAddParticipant div.modal-body").html("Carregando... ");
});

$('#modalAddParticipant').on('keyup','input.name-input',function(){

    if($(this).val().length>2){

        $.ajax({
            url: $("#geral-base-url").val()+"dashboard/certificate/retrieveparticipantslist",
            data : {
                id : $("#modalAddParticipant .id-input").val(),
                type : $("#modalAddParticipant .type-input").val(),
                name : $("#modalAddParticipant .name-input").val()
            }
        }).done(function( html ) {

            $("#modalAddParticipant .result-participants").html(html);

            setTimeout(function() {
                $(window).resize();
            }, 250);


        });

    }

});

$('#modalAddParticipant').on('click','#certificate-add-participant-button',function(){

    var button = $(this) // Button that triggered the modal
    var userid = button.data('value');

    $('#formCertificateAddParticipant-'+userid).submit();

});

$('.certificate-minicourse-revert').click(function(){

    var id = $(this).data('value');

    if (confirm("Reverter a avaliação deste minicurso nos Anais e na Certificação?") == true) {
        $('#formRevertCertificate-'+id).submit();
    }

});

$('#modalCertificateRoundtable').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal
    var data = button.data('data');

    $.ajax({
        url: $("#geral-base-url").val()+"dashboard/certificate/retrieveacceptroundtable",
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalCertificateRoundtable div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalCertificateRoundtable').on('hidden.bs.modal', function (e) {
    $("#modalCertificateRoundtable div.modal-body").html("Carregando... ");
});

$('.certificate-roundtable-revert').click(function(){

    var id = $(this).data('value');

    if (confirm("Reverter a avaliação desta mesa-redonda nos Anais e na Certificação?") == true) {
        $('#formRevertCertificate-'+id).submit();
    }

});

$('.certificate-roundtable-reject').click(function(){

    var id = $(this).data('value');

    if (confirm("Confirmar a rejeição desta mesa-redonda nos Anais e na Certificação?") == true) {
        $('#formRejectCertificate-'+id).submit();
    }

});

$('#modalCertificateConference').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal
    var data = button.data('data');

    $.ajax({
        url: $("#geral-base-url").val()+"dashboard/certificate/retrieveacceptconference",
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalCertificateConference div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalCertificateConference').on('hidden.bs.modal', function (e) {
    $("#modalCertificateConference div.modal-body").html("Carregando... ");
});

$('.certificate-conference-revert').click(function(){

    var id = $(this).data('value');

    if (confirm("Reverter a avaliação desta conferência nos Anais e na Certificação?") == true) {
        $('#formRevertCertificate-'+id).submit();
    }

});

$('.certificate-conference-reject').click(function(){

    var id = $(this).data('value');

    if (confirm("Confirmar a rejeição desta conferência nos Anais e na Certificação?") == true) {
        $('#formRejectCertificate-'+id).submit();
    }

});

$('#modalCertificateWorkshop').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal
    var data = button.data('data');

    $.ajax({
        url: $("#geral-base-url").val()+"dashboard/certificate/retrieveacceptworkshop",
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalCertificateWorkshop div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalCertificateWorkshop').on('hidden.bs.modal', function (e) {
    $("#modalCertificateWorkshop div.modal-body").html("Carregando... ");
});

$('.certificate-workshop-revert').click(function(){

    var id = $(this).data('value');

    if (confirm("Reverter a avaliação desta oficina nos Anais e na Certificação?") == true) {
        $('#formRevertCertificate-'+id).submit();
    }

});

$('.certificate-workshop-reject').click(function(){

    var id = $(this).data('value');


    if (confirm("Confirmar a rejeição desta oficina nos Anais e na Certificação?") == true) {
        $('#formRejectCertificate-'+id).submit();
    }

});






$('.btn-generate-certificate').click(function(){

    var id = $(this).data('id');
    var type = $(this).data('type');
    var first =  $(this).data('first');

    if(first==="yes"){
        if (confirm("Se você continuar, não poderá mais modificar seu nome. Antes de continuar verifique se você preencheu seu nome completo corretamente no momento da inscrição. Para verificar, vá em 'Meus Dados', no canto superior direito. Deseja continuar?") == true) {
            $('#formGenerate-'+type+"-"+id).submit();
        }
    }else{
        $('#formGenerate-'+type+"-"+id).submit();
    }

});

$('.btn-generate-minicourse-certificate').click(function(){

    var id = $(this).data('id');
    var type = $(this).data('type');

    $('#formGenerate-'+type+"-"+id).submit();

});

$('.btn-generate-roundtable-certificate').click(function(){

    var id = $(this).data('id');
    var type = $(this).data('type');

    $('#formGenerate-'+type+"-"+id).submit();

});

$('.btn-generate-conference-certificate').click(function(){

    var id = $(this).data('id');
    var type = $(this).data('type');

    $('#formGenerate-'+type+"-"+id).submit();

});

$('.btn-generate-workshop-certificate').click(function(){

    var id = $(this).data('id');
    var type = $(this).data('type');

    $('#formGenerate-'+type+"-"+id).submit();

});

$('.btn-generate-paper-certificate').click(function(){

    var id = $(this).data('id');
    var type = $(this).data('type');

    $('#formGenerate-'+type+"-"+id).submit();

});

$('.btn-generate-poster-certificate').click(function(){

    var id = $(this).data('id');
    var type = $(this).data('type');

    $('#formGenerate-'+type+"-"+id).submit();

});

$('.btn-generate-pposter-certificate').click(function(){

    var id = $(this).data('id');
    var type = $(this).data('type');

    $('#formGenerate-'+type+"-"+id).submit();

});












$('.modal-conference-retrieve-details').on('hidden.bs.modal', function (e) {
	$(".modal-conference-retrieve-details div.modal-body").html("Carregando... ");
});

$('.button-remove-conference').click(function(){

    var id = $(this).data('value');

    if (confirm("Você realmente deseja remover esta conferência?") == true) {
        $('#formDeleteConference-'+id).submit();
    }

});

$('.modal-conference-edit').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#rc-edit-t").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $(".modal-conference-edit div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('.modal-conference-edit').on('hidden.bs.modal', function (e) {
	$(".modal-conference-edit div.modal-body").html("Carregando... ");
});

/* =====================================
    MANAGE THEMATIC GROUP PAGE
======================================== */

$('#modalEditThematicGroup').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#dtgrd").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalEditThematicGroup div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalEditThematicGroup').on('hidden.bs.modal', function (e) {
	$("#modalEditThematicGroup div.modal-body").html("Carregando... ");
});


/* =====================================
    MANAGE COORDINATOR PAGE
======================================== */

$('#modalEditCoordinator').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#dcrd").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalEditCoordinator div.modal-body").html( html );


        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalEditCoordinator').on('hidden.bs.modal', function (e) {
	$("#modalEditThematicGroup div.modal-body").html("Carregando... ");
});

/* =====================================
    MANAGE ADMINISTRATOR PAGE
======================================== */

$('#modalEditAdministrator').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#dard").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalEditAdministrator div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalEditAdministrator').on('hidden.bs.modal', function (e) {
	$("#modalEditAdministrator div.modal-body").html("Carregando... ");
});

/* =====================================
    MANAGE NEWS PAGE
======================================== */

$('#modalEditNews').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#dnrd").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalEditNews div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalEditNews').on('hidden.bs.modal', function (e) {
	$("#modalEditNews div.modal-body").html("Carregando...");
});

/* =====================================
    MESSAGE PAGE
======================================== */

$('#modalDetailsMessage').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#daarda").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalDetailsMessage div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalDetailsMessage').on('hidden.bs.modal', function (e) {
	$("#modalDetailsMessage div.modal-body").html("Carregando... ");
});

/* =====================================
    ISSUE PAGE DETAILS
======================================== */

$('#modalDetailsIssue').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#issue-retrieve-details").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalDetailsIssue div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalDetailsIssue').on('hidden.bs.modal', function (e) {
	$("#modalDetailsIssue div.modal-body").html("Carregando... ");
});

/* =====================================
    MINICOURSE ADMINISTRATOR PAGE - CONSOLIDATE
======================================== */

$('#modalConsolidateMinicourse').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#rm-consolidateMinicourse").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalConsolidateMinicourse div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalConsolidateMinicourse').on('hidden.bs.modal', function (e) {
	$("#modalConsolidateMinicourse div.modal-body").html("Carregando... ");
});

/* ==================================================
    MINICOURSE ADMINISTRATOR PAGE - DETAILS
===================================================== */

$('#modalMinicourseDetails').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#rm-details").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalMinicourseDetails div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalConsolidateMinicourse').on('hidden.bs.modal', function (e) {
	$("#modalConsolidateMinicourse div.modal-body").html("Carregando... ");
});

/* =====================================
    MINICOURSE ADMINISTRATOR PAGE - DELETE DAY SHIFT
======================================== */

$('#formDeleteDayShift').submit(function(e){

    var text = $('#formDeleteDayShift button[type=submit]').html();

    if (confirm("Você realmente deseja remover este turno?") == true) {

    } else {
        e.preventDefault();
        setTimeout(function() {
            $('#formDeleteDayShift button[type=submit]').text(text);
            $('#formDeleteDayShift button[type=submit]').removeAttr('disabled');
            $('#formDeleteDayShift button[type=submit]').css('opacity','1');
        }, 250);


    }
});

/* =====================================
    ROUNDTABLE ADMINISTRATOR PAGE - CONSOLIDATE
======================================== */

$('#modalConsolidateRoundTable').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#rt-consolidateRoundTable").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalConsolidateRoundTable div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalConsolidateRoundTable').on('hidden.bs.modal', function (e) {
	$("#modalConsolidateRoundTable div.modal-body").html("Carregando... ");
});

/* =====================================
    USER-ENRROLED PAGE - RETRIEVE DETAILS
======================================== */

$('.modal-details-enrroled').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#eide").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $(".modal-details-enrroled div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('.modal-details-enrroled').on('hidden.bs.modal', function (e) {
	$(".modal-details-enrroled div.modal-body").html("Carregando... ");
});

/* =====================================
    USER-ENRROLED PAGE - EVALUATE PAYMENT
======================================== */

$('.modal-evaluate-payment').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#eiep").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $(".modal-evaluate-payment div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('.modal-evaluate-payment').on('hidden.bs.modal', function (e) {
	$(".modal-evaluate-payment div.modal-body").html("Carregando... ");
});

/* =====================================
   CONFERENCE ENROLL PAGE - RETRIEVE DETAILS CONFERENCE
======================================== */

$('#modalEnrollConferenceDetails').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#cmrd").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalEnrollConferenceDetails div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalEnrollConferenceDetails').on('hidden.bs.modal', function (e) {
	$("#modalEnrollConferenceDetails div.modal-body").html("Carregando... ");
});

/* =====================================
   MINICOURSE ENROLL PAGE - RETRIEVE DETAILS CONFERENCE
======================================== */

$('#modalEnrollMinicourseDetails').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#mmmrd").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalEnrollMinicourseDetails div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalEnrollWorkshopDetails').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#mmmssrd").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalEnrollWorkshopDetails div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalEnrollMinicourseDetails').on('hidden.bs.modal', function (e) {
	$("#modalEnrollMinicourseDetails div.modal-body").html("Carregando... ");
});

/* =====================================
   MINICOURSE ENROLL PAGE - RETRIEVE DETAILS CONFERENCE
======================================== */

$('#modalEnrollRoundtableDetails').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#rtrd").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalEnrollRoundtableDetails div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalEnrollRoundtableDetails').on('hidden.bs.modal', function (e) {
	$("#modalEnrollRoundtableDetails div.modal-body").html("Carregando... ");
});



/* ==================================================
    ROUNDTABLE ADMINISTRATOR PAGE - DETAILS
===================================================== */

$('#modalRoundTableDetails').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal

    $.ajax({
        url: $("#rt-details").val(),
        data : { id : button.data('data') }
    }).done(function( html ) {

        $("#modalRoundTableDetails div.modal-body").html( html );

        setTimeout(function() {
            $(window).resize();
        }, 250);

    });

});

$('#modalRoundTableDetails').on('hidden.bs.modal', function (e) {
	$("#modalRoundTableDetails div.modal-body").html("Carregando... ");
});

/* =====================================
    ROUNDTABLE ADMINISTRATOR PAGE - DELETE DAY SHIFT
======================================== */

$('#formDeleteDayShiftRoundTable').submit(function(e){

    if (confirm("Você realmente deseja remover este turno?") == true) {

    } else {
        e.preventDefault();
        setTimeout(function() {
            $('#formDeleteDayShiftRoundTable button[type=submit]').text("Remover");
            $('#formDeleteDayShiftRoundTable button[type=submit]').removeAttr('disabled');
            $('#formDeleteDayShiftRoundTable button[type=submit]').css('opacity','1');
        }, 250);


    }
});



/* =====================================
    SUBMIT MINICOURSE UPLOAD PROGRAM
======================================== */

$(function () {

    $('#programuploader').fileupload({
        dataType: 'json',
        done: function (e, data) {

            if(data.result.file.error==true){
                alert(data.result.file.message);
                $("#formCreateMinicourse input[name=program]").val(null);
                $('#formCreateMinicourse p.file-desc').html("");
                $("#formCreateMinicourse figure.loading").css("display","none");
            }else{
                $("#formCreateMinicourse input[name=program]").val(data.result.file.name);
                $('#formCreateMinicourse p.file-desc').html(data.result.file.name);
                $("#formCreateMinicourse figure.loading").css("display","none");
            }

        },
        submit: function (e, data) {
            $("#formCreateMinicourse figure.loading").css("display","inline");
        }
    });

    $('#programuploaderworkshop').fileupload({
        dataType: 'json',
        done: function (e, data) {

            if(data.result.file.error==true){
                alert(data.result.file.message);
                $("#formCreateWorkshop input[name=program]").val(null);
                $('#formCreateWorkshop p.file-desc').html("");
                $("#formCreateWorkshop figure.loading").css("display","none");
            }else{
                $("#formCreateWorkshop input[name=program]").val(data.result.file.name);
                $('#formCreateWorkshop p.file-desc').html(data.result.file.name);
                $("#formCreateWorkshop figure.loading").css("display","none");
            }

        },
        submit: function (e, data) {
            $("#formCreateWorkshop figure.loading").css("display","inline");
        }
    });

});

/* =====================================
    SUBMIT PAYMENT UPLOAD
======================================== */

$(function () {

    $('#paymentupload').fileupload({
        dataType: 'json',
        done: function (e, data) {

            if(data.result.file.error==true){
                alert(data.result.file.message);
                $("#formSubmitPayment input[name=payment]").val(null);
                $('#formSubmitPayment p.file-desc').html("");
                $("#formSubmitPayment figure.loading").css("display","none");
            }else{
                $("#formSubmitPayment input[name=payment]").val(data.result.file.name);
                $('#formSubmitPayment p.file-desc').html(data.result.file.name);
                $("#formSubmitPayment figure.loading").css("display","none");
            }

        },
        submit: function (e, data) {
            $("#formSubmitPayment figure.loading").css("display","inline");
        }
    });

});

/* =====================================
    SUBMIT TEACHING CASE UPLOAD PROGRAM
======================================== */

$(function () {

    var form = "#formCreateTeachingCases";

    $('#teachingcaseupload').fileupload({
        dataType: 'json',
        done: function (e, data) {

            if(data.result.file.error==true){
                alert(data.result.file.message);
                $(form+" input[name=teachingcase]").val(null);
                $(form+" p.file-desc").html("");
                $(form+" figure.loading").css("display","none");
            }else{
                $(form+" input[name=teachingcase]").val(data.result.file.name);
                $(form+" p.file-desc").html(data.result.file.name);
                $(form+" figure.loading").css("display","none");
            }

        },
        submit: function (e, data) {
            $(form+" figure.loading").css("display","inline");
        }
    });

});

/* =====================================
    SUBMIT POSTER UPLOADER
======================================== */

$(function () {

    var form = "#formCreatePoster";

    $('#posterupload').fileupload({
        dataType: 'json',
        done: function (e, data) {

            if(data.result.file.error==true){
                alert(data.result.file.message);
                $(form+" input[name=poster]").val(null);
                $(form+" p.file-desc").html("");
                $(form+" figure.loading").css("display","none");
            }else{
                $(form+" input[name=poster]").val(data.result.file.name);
                $(form+" p.file-desc").html(data.result.file.name);
                $(form+" figure.loading").css("display","none");
            }

        },
        submit: function (e, data) {
            $(form+" figure.loading").css("display","inline");
        }
    });

});

/* =====================================
    SUBMIT PAPER UPLOAD PROGRAM
======================================== */

$(function () {

    var form = "#formCreatePaper";

    $('#paperupload').fileupload({
        dataType: 'json',
        done: function (e, data) {

            if(data.result.file.error==true){
                alert(data.result.file.message);
                $(form+" input[name=paper]").val(null);
                $(form+" p.file-desc").html("");
                $(form+" figure.loading").css("display","none");
            }else{
                $(form+" input[name=paper]").val(data.result.file.name);
                $(form+" p.file-desc").html(data.result.file.name);
                $(form+" figure.loading").css("display","none");
            }

        },
        submit: function (e, data) {
            $(form+" figure.loading").css("display","inline");
        }
    });

});

/* =====================================
    SUBMIT ISSUE IMAGE UPLOAD
======================================== */

$(function () {

    $('#issueimgupload').fileupload({
        dataType: 'json',
        done: function (e, data) {

            if(data.result.file.error==true){
                alert(data.result.file.message);
                $("#issueCreateForm input[name=image]").val(null);
                $('#issueCreateForm p.file-desc').html("");
                $("#issueCreateForm figure.loading").css("display","none");
            }else{
                $("#issueCreateForm input[name=image]").val(data.result.file.name);
                $('#issueCreateForm p.file-desc').html(data.result.file.name);
                $("#issueCreateForm figure.loading").css("display","none");
            }

        },
        submit: function (e, data) {
            $("#issueCreateForm figure.loading").css("display","inline");
        }
    });

});


/* =====================================
    PAPERS EVALUATION
======================================== */

$(function () {

    /* ====================================
        RETRIEVE PAPER DETAILS MODAL
    ===================================== */

    $("#tablePendingPapers td button").click(function(){

        $.ajax({
            url: $("#ep-mr").val(),
            data : { id : $(this).data('data') }
        }).done(function( html ) {

            $("#modalEvaluatePaper div.modal-body").html( html );
            $("#openEvaluatePaperDetails").click();

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });

});


/* =====================================
    TEACHING CASES EVALUATION
======================================== */

$(function () {

    /* ====================================
        RETRIEVE TEACHING CASES DETAILS MODAL
    ===================================== */

    $("#tablePendingTeachingCases td button").click(function(){

        $.ajax({
            url: $("#ep-mr2").val(),
            data : { id : $(this).data('data') }
        }).done(function( html ) {

            $("#modalEvaluateTeachingCase div.modal-body").html( html );
            $("#openEvaluateTeachingCaseDetails").click();

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });

});

/* =====================================
    POSTERS EVALUATION
======================================== */

$(function () {

    /* ====================================
        RETRIEVE POSTER DETAILS MODAL
    ===================================== */

    $("#tablePendingPosters td button").click(function(){

        $.ajax({
            url: $("#epr-mr").val(),
            data : { id : $(this).data('data') }
        }).done(function( html ) {

            $("#modalEvaluatePoster div.modal-body").html( html );
            $("#openEvaluatePosterDetails").click();

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });

});

/* ========================================
    ADD AUTHORS - TEACHING CASES
======================================== */

$(function () {

    var modal = ".modal-teaching-cases-add-author";

    $("#formCreateTeachingCases a.reset-authors").click(function(){
        $("#formCreateTeachingCases textarea[name=authors]").val("");
    });

    $(modal+" button").click(function(){

        var test = $("#formCreateTeachingCases textarea[name=authors]").val().split('||');

        if($(modal+" input[name=name]").val()=="")
            alert("Você precisa digitar o nome completo do autor.");
        else if($(modal+" input[name=institution]").val()=="")
            alert("Você precisa digitar a instituição do autor.");
        else if(test.length>=5)
            alert("Só é possível adicionar 5 autores.");
        else{

            var namet = $(modal+" input[name=name]").val();
            var instt = $(modal+" input[name=institution]").val();
            var name = namet.replace(/[\[\]|]/g,'');
            var inst = instt.replace(/[\[\]|]/g,'');

            if($("#formCreateTeachingCases textarea[name=authors]").val()=="")
                $("#formCreateTeachingCases textarea[name=authors]").val(
                        $("#formCreateTeachingCases textarea[name=authors]").val()
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );
            else
                $("#formCreateTeachingCases textarea[name=authors]").val(
                        $("#formCreateTeachingCases textarea[name=authors]").val()
                        +
                        " || "
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );

            $(modal+" input[name=name]").val("");
            $(modal+" input[name=institution]").val("");

            $(modal).modal('hide');
        }

    });

});


/* ========================================
    ADD AUTHORS - PAPER
======================================== */

$(function () {

    var modal = ".modal-paper-add-author";

    $("#formCreatePaper a.reset-authors").click(function(){
        $("#formCreatePaper textarea[name=authors]").val("");
    });

    $(modal+" button").click(function(){

        var test = $("#formCreatePaper textarea[name=authors]").val().split('||');

        if($(modal+" input[name=name]").val()=="")
            alert("Você precisa digitar o nome completo do autor.");
        else if($(modal+" input[name=institution]").val()=="")
            alert("Você precisa digitar a instituição do autor.");
        else if(test.length>=5)
            alert("Só é possível adicionar 5 autores.");
        else{

            var namet = $(modal+" input[name=name]").val();
            var instt = $(modal+" input[name=institution]").val();
            var name = namet.replace(/[\[\]|]/g,'');
            var inst = instt.replace(/[\[\]|]/g,'');

            if($("#formCreatePaper textarea[name=authors]").val()=="")
                $("#formCreatePaper textarea[name=authors]").val(
                        $("#formCreatePaper textarea[name=authors]").val()
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );
            else
                $("#formCreatePaper textarea[name=authors]").val(
                        $("#formCreatePaper textarea[name=authors]").val()
                        +
                        " || "
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );

            $(modal+" input[name=name]").val("");
            $(modal+" input[name=institution]").val("");

            $(modal).modal('hide');
        }

    });

});

/* ========================================
    ADD EXPOSITOR - MINICOURSE
======================================== */

$(function () {

    var modal = ".modal-minicourse-add-author";

    $("#formCreateMinicourse a.reset-authors").click(function(){
        $("#formCreateMinicourse textarea[name=authors]").val("");
    });

    $(modal+" button").click(function(){

        var test = $("#formCreateMinicourse textarea[name=authors]").val().split('||');

        if($(modal+" input[name=name]").val()=="")
            alert("Você precisa digitar o nome completo do autor.");
        else if($(modal+" input[name=institution]").val()=="")
            alert("Você precisa digitar a instituição do autor.");
        else{

            var namet = $(modal+" input[name=name]").val();
            var instt = $(modal+" input[name=institution]").val();
            var name = namet.replace(/[\[\]|]/g,'');
            var inst = instt.replace(/[\[\]|]/g,'');

            if($("#formCreateMinicourse textarea[name=authors]").val()=="")
                $("#formCreateMinicourse textarea[name=authors]").val(
                        $("#formCreateMinicourse textarea[name=authors]").val()
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );
            else
                $("#formCreateMinicourse textarea[name=authors]").val(
                        $("#formCreateMinicourse textarea[name=authors]").val()
                        +
                        " || "
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );

            $(modal+" input[name=name]").val("");
            $(modal+" input[name=institution]").val("");

            $(modal).modal('hide');
        }

    });

});

/* ========================================
    ADD EXPOSITOR - WORKSHOP
======================================== */

$(function () {

    var modal = ".modal-workshop-add-author";

    $("#formCreateWorkshop a.reset-authors").click(function(){
        $("#formCreateWorkshop textarea[name=authors]").val("");
    });

    $(modal+" button").click(function(){

        var test = $("#formCreateWorkshop textarea[name=authors]").val().split('||');

        if($(modal+" input[name=name]").val()=="")
            alert("Você precisa digitar o nome completo do autor.");
        else if($(modal+" input[name=institution]").val()=="")
            alert("Você precisa digitar a instituição do autor.");
        else{

            var namet = $(modal+" input[name=name]").val();
            var instt = $(modal+" input[name=institution]").val();
            var name = namet.replace(/[\[\]|]/g,'');
            var inst = instt.replace(/[\[\]|]/g,'');

            if($("#formCreateWorkshop textarea[name=authors]").val()=="")
                $("#formCreateWorkshop textarea[name=authors]").val(
                        $("#formCreateWorkshop textarea[name=authors]").val()
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );
            else
                $("#formCreateWorkshop textarea[name=authors]").val(
                        $("#formCreateWorkshop textarea[name=authors]").val()
                        +
                        " || "
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );

            $(modal+" input[name=name]").val("");
            $(modal+" input[name=institution]").val("");

            $(modal).modal('hide');
        }

    });

});

/* ========================================
    ADD LECTURER - CONFERENCE
======================================== */

$(function () {

    var modal = ".modal-conference-add-lecturer";

    $("#formCreateConference a.reset-lecturer").click(function(){
        $("#formCreateConference input[name=lecturer]").val("");
    });

    $(modal+" button").click(function(){

        var test = $("#formCreateConference input[name=lecturer]").val().split('||');

        if($(modal+" input[name=name]").val()=="")
            alert("Você precisa digitar o nome completo do conferencista.");
        else if($(modal+" input[name=institution]").val()=="")
            alert("Você precisa digitar a instituição do conferencista.");
        else if(test.length>=1 && $("#formCreateConference input[name=lecturer]").val() != "")
            alert("Só é possível adicionar 1 conferencista.");
        else{

            var namet = $(modal+" input[name=name]").val();
            var instt = $(modal+" input[name=institution]").val();
            var name = namet.replace(/[\[\]|]/g,'');
            var inst = instt.replace(/[\[\]|]/g,'');

            if($("#formCreateConference input[name=lecturer]").val()=="")
                $("#formCreateConference input[name=lecturer]").val(
                        $("#formCreateConference input[name=lecturer]").val()
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );
            else
                $("#formCreateConference input[name=lecturer]").val(
                        $("#formCreateConference input[name=lecturer]").val()
                        +
                        " || "
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );

            $(modal+" input[name=name]").val("");
            $(modal+" input[name=institution]").val("");

            $(modal).modal('hide');
        }

    });

});

/* ========================================
    ADD AUTHORS - POSTER
======================================== */

$(function () {

    var modal = ".modal-poster-add-author";

    $("#formCreatePoster a.reset-authors").click(function(){
        $("#formCreatePoster textarea[name=authors]").val("");
    });

    $(modal+" button").click(function(){

        var test = $("#formCreatePoster textarea[name=authors]").val().split('||');

        if($(modal+" input[name=name]").val()=="")
            alert("Você precisa digitar o nome completo do autor.");
        else if($(modal+" input[name=institution]").val()=="")
            alert("Você precisa digitar a instituição do autor.");
        else if(test.length>=5)
            alert("Só é possível adicionar 5 autores.");
        else{

            var namet = $(modal+" input[name=name]").val();
            var instt = $(modal+" input[name=institution]").val();
            var name = namet.replace(/[\[\]|]/g,'');
            var inst = instt.replace(/[\[\]|]/g,'');

            if($("#formCreatePoster textarea[name=authors]").val()=="")
                $("#formCreatePoster textarea[name=authors]").val(
                        $("#formCreatePoster textarea[name=authors]").val()
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );
            else
                $("#formCreatePoster textarea[name=authors]").val(
                        $("#formCreatePoster textarea[name=authors]").val()
                        +
                        " || "
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );

            $(modal+" input[name=name]").val("");
            $(modal+" input[name=institution]").val("");

            $(modal).modal('hide');
        }

    });

});

/* ========================================
    ADD DEBATERS - ROUNDTABLE
======================================== */

$(function () {

    var modal = ".modal-roundtable-add-debater";

    $("#formCreateRoundTable a.reset-authors").click(function(){
        $("#formCreateRoundTable textarea[name=debaters]").val("");
    });

    $(modal+" button").click(function(){

        var test = $("#formCreateRoundTable textarea[name=debaters]").val().split('||');

        if($(modal+" input[name=name]").val()=="")
            alert("Você precisa digitar o nome completo do debatedor.");
        else if($(modal+" input[name=institution]").val()=="")
            alert("Você precisa digitar a instituição do debatedor.");
        else if(test.length>=3)
            alert("Só é possível adicionar 3 debatedores.");
        else{

            var namet = $(modal+" input[name=name]").val();
            var instt = $(modal+" input[name=institution]").val();
            var name = namet.replace(/[\[\]|]/g,'');
            var inst = instt.replace(/[\[\]|]/g,'');

            if($("#formCreateRoundTable textarea[name=debaters]").val()=="")
                $("#formCreateRoundTable textarea[name=debaters]").val(
                        $("#formCreateRoundTable textarea[name=debaters]").val()
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );
            else
                $("#formCreateRoundTable textarea[name=debaters]").val(
                        $("#formCreateRoundTable textarea[name=debaters]").val()
                        +
                        " || "
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );

            $(modal+" input[name=name]").val("");
            $(modal+" input[name=institution]").val("");

            $(modal).modal('hide');
        }

    });

});

/* ========================================
    ADD COORDINATOR - ROUNDTABLE
======================================== */

$(function () {

    var modal = ".modal-roundtable-add-coordinator";

    $("#formCreateRoundTable a.reset-coordinator").click(function(){
        $("#formCreateRoundTable input[name=coordinator]").val("");
    });

    $(modal+" button").click(function(){

        var coordval = $("#formCreateRoundTable input[name=coordinator]").val();
        var test = $("#formCreateRoundTable input[name=coordinator]").val().split('||');

        if($(modal+" input[name=name]").val()=="")
            alert("Você precisa digitar o nome completo do coordenador.");
        else if($(modal+" input[name=institution]").val()=="")
            alert("Você precisa digitar a instituição do coordenador.");
        else if(test.length>=1 && coordval!='')
            alert("Só é possível adicionar 1 coordenador.");
        else{

            var namet = $(modal+" input[name=name]").val();
            var instt = $(modal+" input[name=institution]").val();
            var name = namet.replace(/[\[\]|]/g,'');
            var inst = instt.replace(/[\[\]|]/g,'');

            if($("#formCreateRoundTable input[name=coordinator]").val()=="")
                $("#formCreateRoundTable input[name=coordinator]").val(
                        $("#formCreateRoundTable input[name=coordinator]").val()
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );
            else
                $("#formCreateRoundTable input[name=coordinator]").val(
                        $("#formCreateRoundTable input[name=coordinator]").val()
                        +
                        " || "
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );

            $(modal+" input[name=name]").val("");
            $(modal+" input[name=institution]").val("");

            $(modal).modal('hide');
        }

    });

});

$(function () {

    $('body').on('submit',"form.waiting",function(event){
        var index = $(this).attr('id');
        $("form#"+index+" button[type=submit]").html("Processando...");
        $("form#"+index+" button[type=submit]").css("opacity","0.6");
        $("form#"+index+" button[type=submit]").attr("disabled", "disabled");
    });


    $('a.paper-as-poster-accept').click(function(){
        $('#userpaperacceptasposter').submit();
    });

    $('a.paper-as-poster-reject').click(function(){
        $('#userpaperrejectasposter').submit();
    });

});


/* MANAGE INSCRITOS PAGE */

$(function () {

    $('#filter-select').change(function(){
        window.location.href = $('#filter-select').val();
    });

    $('#order-select').change(function(){
        window.location.href = $('#order-select').val();
    });

    $('#searchbyname-btn').click(function(){

        $.ajax({
            url: $("#searchbyname-retrieve-link").val(),
            data : { value : $('#searchbyname').val(), link : $('#searchbyname-baselink').val() }
        }).done(function( html ) {

            window.location.href = html;

        });

    });

});

$(function () {

    $('.conference-registration-button').click(function(event){
        var id = $(this).data('data');
        $('#formEnrollConference-'+id).submit();
        //alert('#formEnrollConference-'+id);
    });

    $('.conference-registration-button-unroll').click(function(event){
        var id = $(this).data('data');
        $('#formUnrollConference-'+id).submit();
    });

    $('.minicourse-registration-button').click(function(event){
        var id = $(this).data('data');
        $('#formEnrollMinicourse-'+id).submit();
        //alert('#formEnrollConference-'+id);
    });

    $('.minicourse-registration-button-unroll').click(function(event){
        var id = $(this).data('data');
        $('#formUnrollMinicourse-'+id).submit();
    });

    $('.workshop-registration-button').click(function(event){
        var id = $(this).data('data');
        $('#formEnrollWorkshop-'+id).submit();
        //alert('#formEnrollConference-'+id);
    });

    $('.workshop-registration-button-unroll').click(function(event){
        var id = $(this).data('data');
        $('#formUnrollWorkshop-'+id).submit();
    });

    $('.roundtable-registration-button').click(function(event){
        var id = $(this).data('data');
        $('#formEnrollRoundtable-'+id).submit();
        //alert('#formEnrollConference-'+id);
    });

    $('.roundtable-registration-button-unroll').click(function(event){
        var id = $(this).data('data');
        $('#formUnrollRoundtable-'+id).submit();
    });

    $('.button-user-free-payment').click(function(){

        var id = $(this).data('data');

        if (confirm("Você realmente deseja isentar este inscrito?") == true) {
            $('#formFreePayment-'+id).submit();
        }

    });

    $('.poster-cancel-submission').click(function(){

        var id = $(this).data('data');

        if (confirm("Você realmente deseja cancelar esta submissão?") == true) {
            $('#formPosterCancelSubmission-'+id).submit();
        }

    });

    $('.paper-cancel-submission').click(function(){

        var id = $(this).data('data');

        if (confirm("Você realmente deseja cancelar esta submissão?") == true) {
            $('#formPaperCancelSubmission-'+id).submit();
        }

    });

    $('.teaching-case-cancel-submission').click(function(){

        var id = $(this).data('data');

        if (confirm("Você realmente deseja cancelar esta submissão?") == true) {
            $('#formTeachingCasesCancelSubmission-'+id).submit();
        }

    });



    $('.minicourse-cancel-submission').click(function(){

        var id = $(this).data('data');

        if (confirm("Você realmente deseja cancelar esta submissão?") == true) {
            $('#formMinicourseCancelSubmission-'+id).submit();
        }

    });

    $('.workshop-cancel-submission').click(function(){

        var id = $(this).data('data');

        if (confirm("Você realmente deseja cancelar esta submissão?") == true) {
            $('#formWorkshopCancelSubmission-'+id).submit();
        }

    });

    $('.roundtable-cancel-submission').click(function(){

        var id = $(this).data('data');

        if (confirm("Você realmente deseja cancelar esta submissão?") == true) {
            $('#formRoundtableCancelSubmission-'+id).submit();
        }

    });

    $( document ).on( "click", ".button-edit-conference-submit", function() {
		// Doing validation
		if($('#formUpdateConference input[name="title"]').val()=='')
			alert('O campo título é obrigatório.');
		else if($('#formUpdateConference input[name="lecturer"]').val()=='')
			alert('O campo conferencista é obrigatório.');
		else if($('#formUpdateConference input[name="dayshift"]').val()=='')
			alert('O campo dia/turno é obrigatório.');
		else if($('#formUpdateConference input[name="vancacies"]').val()=='')
			alert('O campo quantidade de vagas é obrigatório.');
		else if($('#formUpdateConference input[name="local"]').val()=='')
			alert('O campo local é obrigatório.');
		else if($('#formUpdateConference input[name="proposal"]').val()=='')
			alert('O campo proposta é obrigatório.');
		else
			$('#formUpdateConference').submit();
	});

	$( document ).on( 'click' , '.reset-edit-lecturer' , function(){

		$('#formUpdateConference input[name="lecturer"]').val("");

	});

	$( document ).on( 'click' , '.button-edit-conference-add-lecturer' , function(){

		$('.add-edit-lecturer-container').toggle(400);
		$('.conference-edit-input-name').focus();

	});

	$( document ).on( 'click' , '.button-edit-add-lecturer-conference' , function(){

		if($('.conference-edit-input-name').val()=="")
            alert("Você precisa digitar o nome completo do conferencista.");
        else if($('.conference-edit-input-institution').val()=="")
            alert("Você precisa digitar a instituição do conferencista.");
        else if($('#formUpdateConference input[name="lecturer"]').val()!='')
            alert("Só é possível adicionar 1 conferencista.");
        else{

            var namet = $('.conference-edit-input-name').val();
            var instt = $('.conference-edit-input-institution').val();
            var name = namet.replace(/[\[\]|]/g,'');
            var inst = instt.replace(/[\[\]|]/g,'');

            if($("#formUpdateConference input[name='lecturer']").val()=="")
                $("#formUpdateConference input[name='lecturer']").val(
                        $("#formUpdateConference input[name='lecturer']").val()
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );
            else
                $("#formUpdateConference input[name='lecturer']").val(
                        $("#formUpdateConference input[name='lecturer']").val()
                        +
                        " || "
                        +
                        name
                        +
                        " ["
                        +
                        inst
                        +
                        "]"
                    );

            $('.conference-edit-input-name').val('');
            $('.conference-edit-input-institution').val('');

            $('.add-edit-lecturer-container').toggle(400);
        }

	});


	$('.form-add-record-behavior input[name=starthour]').keydown(function(){

		var starthour = $(this).val().split(':');
		var hour = starthour[0];
		var min = starthour[1];

		if(parseInt(hour) >= 0 && parseInt(hour) <= 11){
				$('.schedule-form-shift').html('Matutino');
				$('.form-add-record-behavior input[name=shift]').val('matutino');
		}else if(parseInt(hour) >= 12 && parseInt(hour) <= 17){
			$('.schedule-form-shift').html('Vespertino');
			$('.form-add-record-behavior input[name=shift]').val('vespertino');
		}else if(parseInt(hour) >= 18 && parseInt(hour) <= 23){
			$('.schedule-form-shift').html('Noturno');
			$('.form-add-record-behavior input[name=shift]').val('noturno');
		}

	});

    $('.schedule-record-remove-button').click(function(){

        var id = $(this).data('data');

        if (confirm("Você realmente deseja remover este registro?") == true) {
            $('#formScheduleRecordRemove-'+id).submit();
        }

    });

    $('.select-schedule-tg').change(function() {
        var type = $(this).data('type');
        var selectedId = $('.select-schedule-tg option:selected').val();

        // Change select to message
        $('.select-show-types').empty();
        $('.select-show-types').prop('disabled', 'disabled');
        $('.select-show-types').append($('<option></option>').text('Carregando opções, aguarde...'));

        if(type==='paper' || type=='poster'){
            // Retrieve papers by TG

            $.get(
                $('#geral-base-url').val()+"dashboard/schedule/retrieveworksbytg",
                {
                    type: type,
                    tgid: selectedId
                },
                function( data ) {

                    $('.select-show-types').empty();
                    $('.select-show-types').prop('disabled', false);

                    if(type==='paper'){

                        $.each(data.papers, function(key,value) {

                            $('.select-show-types').append($("<option></option>")
                             .attr("value", value.id).text(value.title));
                        });

                        if(!data.papers.length){
                            $('.select-show-types').append($("<optgroup></optgroup>")
                                .attr("label", 'Não há mais trabalhos neste Grupo Temático.'));
                        }


                    }else if(type==='poster'){

                        $.each(data.posters, function(key,value) {

                        $('.select-show-types').append($("<option></option>")
                             .attr("value", value.id).text(value.title));
                        });

                        if(!data.posters.length){
                            $('.select-show-types').append($("<optgroup></optgroup>")
                                .attr("label", 'Não há mais trabalhos neste Grupo Temático.'));
                        }

                    }



                },
                "json"
            );

        }


    });

    if( $('.select-schedule-tg').length ){

        $('.select-schedule-tg').change();

    }



    $('.modal-config-edit').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var data = button.data('id');

        $.ajax({
            url: $("#geral-base-url").val()+"dashboard/configuration/retrieveedit",
            data : { id : data }
        }).done(function( html ) {

            $(".modal-config-edit div.modal-content").html( html );

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });

    $('.modal-subevent-add-activity').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var data = button.data('id');

        $(".modal-subevent-add-activity div.modal-content").html( 'Carregando...' );

        $.ajax({
            url: $("#geral-base-url").val()+"dashboard/subevent/retrieveaddactivity",
            data : { id : data }
        }).done(function( html ) {

            $(".modal-subevent-add-activity div.modal-content").html( html );

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });

    $('.modal-subevent-add-activity').on('keyup','.modal-activity-add-exe input.search',function(){

        if($(this).val().length>2){

            $.ajax({
                url: $("#geral-base-url").val()+"dashboard/subevent/retrieveactivitiesresults",
                data : {
                    search : $(".modal-subevent-add-activity .modal-activity-add-exe input.search").val(),
                    filter : $(".modal-subevent-add-activity .modal-activity-add-exe input.filter:checked").val(),
                    subevent : $(".modal-subevent-add-activity .modal-activity-add-exe input.subevent").val()
                }
            }).done(function( html ) {

                $(".modal-subevent-add-activity .modal-activity-add-exe .retrieve-subevent-activities-results").html(html);

                setTimeout(function() {
                    $(window).resize();
                }, 250);


            });

        }

    });

    $('.modal-subevent-add-activity-exe').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var data = button.data('id');
        var type = button.data('type');
        var subid = button.data('subid');

        $(".modal-subevent-add-activity-exe div.modal-content").html( 'Carregando...' );

        $.ajax({
            url: $("#geral-base-url").val()+"dashboard/subevent/retrieveaddactivityform",
            data : { id : data, type: type, subid : subid }
        }).done(function( html ) {

            $(".modal-subevent-add-activity-exe div.modal-content").html( html );

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });

    $('.btn-subevent-remove').click(function(){
        var id = $(this).data('id');
        $('#formSubeventRemove-'+id).submit();
    });

    $('.modal-subevent-edit').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var data = button.data('id');

        $(".modal-subevent-edit div.modal-content").html( 'Carregando...' );

        $.ajax({
            url: $("#geral-base-url").val()+"dashboard/subevent/retrieveedit",
            data : { id : data }
        }).done(function( html ) {

            $(".modal-subevent-edit div.modal-content").html( html );

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });

    $('.modal-subevent-activity-edit').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var data = button.data('id');

        $(".modal-subevent-activity-edit div.modal-content").html( 'Carregando...' );

        $.ajax({
            url: $("#geral-base-url").val()+"dashboard/subevent/retrieveupdateactivity",
            data : { id : data }
        }).done(function( html ) {

            $(".modal-subevent-activity-edit div.modal-content").html( html );

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });

    $('.modal-subevent-edit').on('click','a.btn-remove-activity-c',function(){

        var id = $(this).data('id');
        $('#formRemoveSubeventActivity-'+id).submit();

    });

    $('.modal-minicourse-confirm-operation').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var data = button.data('id');

        $(".modal-minicourse-confirm-operation div.modal-content").html( 'Carregando...' );

        $.ajax({
            url: $("#geral-base-url").val()+"dashboard/minicourse/retrieveconfirmoperation",
            data : { id : data }
        }).done(function( html ) {

            $(".modal-minicourse-confirm-operation div.modal-content").html( html );

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });

    $('.modal-roundtable-confirm-operation').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var data = button.data('id');

        $(".modal-roundtable-confirm-operation div.modal-content").html( 'Carregando...' );

        $.ajax({
            url: $("#geral-base-url").val()+"dashboard/roundtable/retrieveconfirmoperation",
            data : { id : data }
        }).done(function( html ) {

            $(".modal-roundtable-confirm-operation div.modal-content").html( html );

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });

    $('.modal-minicourse-edit-info').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var data = button.data('data');

        $(".modal-minicourse-edit-info div.modal-content").html( 'Carregando...' );

        $.ajax({
            url: $("#geral-base-url").val()+"dashboard/minicourse/retrieveeditinfo",
            data : { id : data }
        }).done(function( html ) {

            $(".modal-minicourse-edit-info div.modal-content").html( html );

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });

    $('.modal-roundtable-edit-info').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var data = button.data('data');

        $(".modal-roundtable-edit-info div.modal-content").html( 'Carregando...' );

        $.ajax({
            url: $("#geral-base-url").val()+"dashboard/roundtable/retrieveeditinfo",
            data : { id : data }
        }).done(function( html ) {

            $(".modal-roundtable-edit-info div.modal-content").html( html );

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });

    $('#modalConsolidateWorkshop').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var data = button.data('data');

        $("#modalConsolidateWorkshop div.modal-body").html( 'Carregando...' );

        $.ajax({
            url: $("#geral-base-url").val()+"dashboard/workshop/retrieveconsolidation",
            data : { id : data }
        }).done(function( html ) {

            $("#modalConsolidateWorkshop div.modal-body").html( html );

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });

    $('.modal-workshop-confirm-operation').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var data = button.data('id');

        $(".modal-workshop-confirm-operation div.modal-content").html( 'Carregando...' );

        $.ajax({
            url: $("#geral-base-url").val()+"dashboard/workshop/retrieveconfirmoperation",
            data : { id : data }
        }).done(function( html ) {

            $(".modal-workshop-confirm-operation div.modal-content").html( html );

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });

    $('#modalWorkshopDetails').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var data = button.data('id');

        $("#modalWorkshopDetails div.modal-body").html( 'Carregando...' );

        $.ajax({
            url: $("#geral-base-url").val()+"dashboard/workshop/retrieveworkshopdetails",
            data : { id : data }
        }).done(function( html ) {

            $("#modalWorkshopDetails div.modal-body").html( html );

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });

    $('.modal-workshop-edit-info').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var data = button.data('data');

        $(".modal-workshop-edit-info div.modal-content").html( 'Carregando...' );

        $.ajax({
            url: $("#geral-base-url").val()+"dashboard/workshop/retrieveeditinfo",
            data : { id : data }
        }).done(function( html ) {

            $(".modal-workshop-edit-info div.modal-content").html( html );

            setTimeout(function() {
                $(window).resize();
            }, 250);

        });

    });


});


jQuery(function($){

   $("#schedule-paper-form-start-hour").mask("99:99");
   $("#schedule-paper-form-end-hour").mask("99:99");

});
