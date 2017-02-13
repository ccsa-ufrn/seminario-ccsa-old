/* BEGIN MASKED INPUT */

jQuery(function($){
    
   $("#register-masked-phone").mask("99999999?999");
    
});


/* END MASKED INPUT */


$( document ).ready(function() { 
    $('.datatable').DataTable();
});


$( document ).ready(function() {

    $("#resetPasswordForm").submit(function(e){
        
        var form = '#resetPasswordForm';

        $(form+' button').attr('disabled','disabled');
        $(form+' figure.loading').css('display','block');

    });
   
    $("form.waiting").submit(function(){
        $("form.waiting button[type=submit]").html("Processando...");
        $("form.waiting button[type=submit]").css("opacity","0.6");
        $("form.waiting button[type=submit]").attr("disabled", "disabled");
    });
    
    
});

/* =====================================
    WELCOME RETRIEVE STANDARDS
======================================== */

$('.modal-standards').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal
    
    $.ajax({
        url: $("#rsss").val(),
        data : { id : button.data('id') }
    }).done(function( html ) {

        $(".modal-standards div.modal-body").html( html );
        
        setTimeout(function() {
            $(window).resize();
        }, 250);

    });
    
});

$('.modal-standards').on('hidden.bs.modal', function (e) {
	$(".modal-standards div.modal-body").html("Carregando... ");
});

/* ========================================
    THEMATIC GROUP SYLLABUS - MODAL
======================================== */

$('.modal-thematic-groups-syllabus').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget) // Button that triggered the modal
    var data = button.attr('data-id'); // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this);
    
    $.ajax({
        url: $("#rtg").val(),
        data : { id : data }
    }).done(function( html ) {

        $(".modal-thematic-groups-syllabus div.modal-body").html( html );

    });

});

$('.modal-thematic-groups-syllabus').on('hidden.bs.modal', function (e) {
	$(".modal-thematic-groups-syllabus div.modal-body").html("Carregando...");
});

/* =============================================
    ENQUIRE.JS
============================================= */

enquire.register("(min-width:1200px)", {

    match : function() {

        $("section.about img.logo").attr('src','assets/img/logo945.png');
        $("header.logo img.logo").attr('src','assets/img/logo945.png');

    },       

    unmatch : function(){},

    setup : function() {} 

});


enquire.register("(min-width:992px)", {

    match : function() {
        $("section.info div.standards > div.row > div").addClass("pull-right");
        
        $("section.info div.credits div.first, section.info div.activities div.first").css("padding-right","0px");
        $("section.info div.standards div.first").css("padding-left","0px");
        
        $("section.info div.credits div.second, section.info div.activities div.second").css("padding-left","0px");
        $("section.info div.standards div.second").css("padding-right","0px");
    },       

    unmatch : function(){
        
        $("section.info div.standards > div.row > div").removeClass("pull-right");
        
        $("section.info div div.first").css("padding-right","");
        $("section.info div div.first").css("padding-left","");
        $("section.info div div.second").css("padding-left","");
        $("section.info div div.second").css("padding-right","");

    },

    setup : function() {} 

});

enquire.register("(min-width:992px) and (max-width:1199px)", {

    match : function() {
        $("section.about img.logo").attr('src','assets/img/logo778.png');
        $("header.logo img.logo").attr('src','assets/img/logo778.png');
    },       

    unmatch : function(){

    },

    setup : function() {} 

});

enquire.register("(min-width:768px) and (max-width:991px)", {

    match : function() {
        $("section.about img.logo").attr('src','assets/img/logo595.png');
        $("header.logo img.logo").attr('src','assets/img/logo716.png');
    },       

    unmatch : function(){

    },

    setup : function() {} 

});

enquire.register("(min-width:0px) and (max-width:767px)", {

    match : function() {

        // Logo 995
        $("section.about img.logo").attr('src','assets/img/logo595.png');
        $("header.logo img.logo").attr('src','assets/img/logo716.png');

    },       

    unmatch : function(){

    },

    setup : function() {} 

});
