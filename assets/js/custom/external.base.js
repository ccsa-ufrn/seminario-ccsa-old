$(document).ready(function(){
	
	$('input, textarea').placeholder();
	
	$('#form-contact button').click(function(){
		$(this).prop('disabled','true');
	});
	
	$('ul.second-level > li > a').click(function(){
		
		var id = $(this).data('id');
		
		$('ul.ul-'+id).toggle(300);
		
	});
	
	$('a.btn-mobile-nav').click(function(){

        if( $('section.cover nav').hasClass('show-mobile')  ) {
           
            $('section.cover nav').removeClass('show-mobile'); 
            
        } else {
            
            $('section.cover nav').addClass('show-mobile'); 
            
        }
        
		
	});
	
	$('input, textarea').placeholder();
	
	$('.index-login-modal .login input').keypress(function(e) {
		if(e.which == 13) {

			$('.index-login-modal .login button').click();
			
		}
	});
	
	$('.index-login-modal .retrieve-password input').keypress(function(e) {
		if(e.which == 13) {

			$('.index-login-modal .retrieve-password button').click();
			
		}
	});
	
	$('.index-login-modal').on('shown.bs.modal', function () {

		setTimeout(function (){
			$('.index-login-modal .login input[name=email]').focus();
		}, 150);
	
	});
	
});	