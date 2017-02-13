$(function() {

    /* ======================================
        GENERAL FUNCTIONS
    ======================================= */
    
    function unexpectedBehavior(form){ // form -> jquery selector element
        $(form+' .success').html("<div class='alert alert-danger'>");
        $(form+' .success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
            .append("</button>");
        $(form+' .success > .alert-danger').append("<strong>Parece que algum problema inesperado aconteceu, entre em contato conosco.");
        $(form+' .success > .alert-danger').append('</div>');
    }
    
    function message(form,type,msg){ // form -> jquery selector element, type -> 'success' or 'danger', msg -> message
        var html = "<div class='alert alert-"+type+"'>";
        html += "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
        html += "<strong>"+msg+"</strong></div>";
        $(form+' div.success-container > div.success').html(html);
    }

    function prevent(form){
        $('body').on('submit',form,function(e){
            e.preventDefault(); // Prevent to submit forms
        });
    }

    /* ======================================
        DASHBOARD MYINFORMATION FORM
    ======================================= */
    
    $("#formMyInformation").submit(function(e){
        
        var form = "#formMyInformation";

        prevent(form);
        
        $(form+" button[type=submit]").attr('disabled','true');
        $(form+" button[type=submit]").text("Carregando...");

        // getting values from form
        var phone = $(form+" input[name=phone]").val();
        var oldPass = $(form+" input[name=oldPass]").val();
        var newPass = $(form+" input[name=newPass]").val();
        var newRpass = $(form+" input[name=newRpass]").val();
        var name = $(form+" input[name=name]").val();
        var cpf = $(form+" input[name=cpf]").val();
        var csrf = $(form+" input[name=csrf_test_name]").val();

        $.ajax({
            url: $(form+" input.formAction").val(),
            type: "POST",
            data: {
                phone: phone,
                oldPass : oldPass,
                newPass : newPass,
                newRpass : newRpass,
                name : name,
                cpf: cpf,
                csrf_test_name: csrf
            },
            cache: false,
            success: function(result) {

                $('#formMyInformation div.success-container > div.success').html(result);

                var arr = result.split(",");
                
                if(arr[0]=="0"){
                    message(form,'danger',arr[1]);
                }else if(arr[0]=="1"){
                    message(form,'danger','A senha antiga está errada.');
                    $(form+' input[name=oldPass]').focus();
                }else if(arr[0]=="2"){
                    message(form,'danger','A senha nova e sua confirmação não combinam.');
                    $(form+' input[name=newPass]').focus();
                }else if(arr[0]=="3"){
                    message(form,'success','Os dados foram atualizados.');
                    $(form+' input[name=oldPass]').val("");
                    $(form+' input[name=newPass]').val("");
                    $(form+' input[name=newRpass]').val("");
                }
                
                $(form+" button[type=submit]").text("Atualizar");
                $(form+" button[type=submit]").removeAttr('disabled');

            },
            error: function() {
                unexpectedBehavior(form);
            },
        });
        
    });

   

});


/*When clicking on Full hide fail/success boxes */
$('#name').focus(function() {
    $('#success').html('');
});
