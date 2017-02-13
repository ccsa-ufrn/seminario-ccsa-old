var baseUrl = "http://"+window.location.href.split('/')[2]+"ssapp-v1/";

var app = angular.module(
	"installerApp", 
	[
		"ui.bootstrap",
		"oitozero.ngSweetAlert"
	]
);

app.controller('appCtl', [ '$scope', '$http', 'SweetAlert' , function(s, h, sa){
	
	/* GENERAL DEFINITIONS */
	
	s.hide = {

		databaseForm : false,
		createDatabaseForm : true,
		systemConfig : true,
		createAdminUser : true,
		verifyDateHour : true,
		verifyEmail : true

	}
	
	
	/* DATABASE  - FORM */
	
	s.host = '127.0.0.1';
	s.username = '';
	s.password = '';
	s.continueIsDisabled = true;

	s.testConn = function(){

		h({
			method : 'post',
			url: baseUrl+'install/testmysql',
			data: $.param({
				'host' : s.host,
				'username' : s.user,
				'password' : s.password,
				'csrf_test_name' : document.getElementsByName("csrf_test_name")[0].value
			}),
			headers: {'Content-Type' : 'application/x-www-form-urlencoded;'}
		}).then(function(response) {

			if(response.data.status.code=="error"){
				sa.swal("Erro!", response.data.status.msg, "error");
			}else{
				sa.swal("Sucesso", response.data.status.msg+". Clique no botão 'continuar' para prosseguir.", "success");
				s.continueIsDisabled = false;
			}
			
		});

	};
	
	s.databaseContinue = function(){
		
		s.hide.databaseForm = true;
		s.hide.createDatabaseForm = false;
		
	};
	
	/* CREATING DATABASE  - FORM */

	s.databasename = "";
	
	s.creatingDatabaseContinue = function(){
		
		s.hide.createDatabaseForm = true;
		s.hide.systemConfig = false;
	
	};
	
	/* CONFIGURING SYSTEM */
	
	s.configuringSystemContinue = function(){
		
		s.hide.createDatabaseForm = true;
		s.hide.createAdminUser = false;
	
	};
	
	/* CREATE ADMIN USER */
	
	s.creatingAdminUserContinue = function(){
		
		s.hide.createAdminUser = true;
		s.hide.systemConfig = false;
	
	};
	
	/* VERIFY DATE HOUR */
	
	s.verifyDateHourContinue = function(){
		
		s.hide.createDatabaseForm = true;
		s.hide.verifyDateHour = false;
	
	};
	
	/* VERIFY EMAIL */
	
	s.verifyEmailContinue = function(){
		
		s.hide.verifyDateHour = true;
		s.hide.verifyEmail = false;
	
	};

}]);