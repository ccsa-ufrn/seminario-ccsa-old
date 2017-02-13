//var baseUrl = "http://"+window.location.href.split('/')[2]+"/"+window.location.href.split('/')[3]+"/index.php/";
var baseUrl = "https://"+window.location.href.split('/')[2]+"/"+window.location.href.split('/')[3]+"index.php/";

var app = angular.module('externalApp',['oitozero.ngSweetAlert']);

app.controller('inscriptionCtl',[ '$scope', '$http', 'SweetAlert',  function(s,h,sa){
	
	s.name = '';
	s.email = '';
	s.category = 'student';
	s.institution = '';
	s.phone = '';
	s.password = '';
	s.repassword = '';
	
	s.formError = {
		'name' : {
			'invalid' : false,
			'message' : ''
		},
		'email' : {
			'invalid' : false,
			'message' : ''
		},
		'cpf' : {
			'invalid' : false,
			'message' : ''
		},
		'category' : {
			'invalid' : false,
			'message' : ''
		},
		'institution' : {
			'invalid' : false,
			'message' : ''
		},
		'phone' : {
			'invalid' : false,
			'message' : ''
		},
		'password' : {
			'invalid' : false,
			'message' : ''
		},
		'repassword' : {
			'invalid' : false,
			'message' : ''
		}
	}
	
	s.isLoading = false;
	s.registerComplete = false;
	
	s.register = function(isValid,fCreateUser){
		
		if(isValid) {
			
			s.isLoading = true;
			
			h({
				method : 'post',
				url: baseUrl+'createUser',
				data: $.param({ 
					'name' : s.name,
					'cpf' : s.cpf,
					'email' : s.email,
					'category' : s.category,
					'institution' : s.institution,
					'phone' : s.phone,
					'password' : s.password,
					'rpassword' : s.repassword,
					'csrf_test_name' : $("input:hidden[name='csrf_test_name']").val()
				}),
				headers: {'Content-Type' : 'application/x-www-form-urlencoded;'}
			}).then(function(response) {
				
				s.isLoading = false;
				
				if( response.data.status == 'error' ) {
					
					sa.swal("Erro", response.data.msg, "error");
					
				}else if( response.data.status == 'success' ) {
					
					s.name = '';
					s.cpf = '';	
					s.email = '';
					s.category = 'student';
					s.institution = '';
					s.phone = '';
					s.password = '';
					s.repassword = '';
					
					fCreateUser.$setPristine();
					fCreateUser.$setUntouched();
					
					s.registerComplete = true;
					
				}
	
			});
			
		}
		
	}; /* NED - S.REGISTER */
	
}]);

app.controller('loginCtl',[ '$scope','$http', 'SweetAlert', function(s,h,sa){
	
	s.loginEmail = "";
	s.loginPassword = "";
	s.rpEmail = "";
	
	s.isLoading = false;
	
	s.title = "Entrar no Sistema";
	
	s.mode = {
		
		'login' : true,
		'retrievePassword' : false
		
	}
	
	s.changeMode = function(str) {
		
		s.mode.login = false;
		s.mode.retrievePassword = false;
		
		if( str === "retrievePassword" ){
			
			s.mode.retrievePassword = true;
			s.title = "Recuperar Senha";
			
		}else if( str === "login" ){
			
			s.mode.login = true;
			s.title = "Entrar no Sistema";
			
		}
		
	};
	
	s.signin = function(){
		
		if( s.loginEmail !== "" && s.loginPassword !== "" ) {
			
			s.isLoading = true;
			
			h({
				method : 'post',
				url: baseUrl+'login',
				data: $.param({ 
					'email' : s.loginEmail,
					'password' : s.loginPassword,
					'csrf_test_name' : $(".login input:hidden[name='csrf_test_name']").val()
				}),
				headers: {'Content-Type' : 'application/x-www-form-urlencoded;'}
			}).then(function(response) {
				
				if( response.data.status == 'error' ) {
					
					sa.swal("Erro", response.data.msg, "error");
					
				}else if( response.data.status == 'success' ) {
					
					window.location = baseUrl+'dashboard';
					
				}
				
				s.isLoading = false;

			});
			
			
		} else {
			
			sa.swal("Erro no Preenchimento", "Você precisa inserir o Email e Senha.", "error");
			
		}

		
	};
	
	s.retrievePassword = function(){
		
		if( s.rpEmail !== "" ) {
			
			s.isLoading = true;
			
			h({
				method : 'post',
				url: baseUrl+'resetp',
				data: $.param({ 
					'email' : s.rpEmail,
					'csrf_test_name' : $(".retrieve-password input:hidden[name='csrf_test_name']").val()
				}),
				headers: {'Content-Type' : 'application/x-www-form-urlencoded;'}
			}).then(function(response) {
				
				if( response.data.status == 'error' ) {
					
					sa.swal("Erro", response.data.msg, "error");
					
				}else if( response.data.status == 'success' ) {
					
					sa.swal("Recuperação de Senha", response.data.msg, "success");
					s.mode.retrievePassword = false;
					s.mode.login = true;
					s.title = "Entrar no Sistema";
					
				}
				
				s.isLoading = false;

			});
			
			
		} else {
			
			sa.swal("Erro no Preenchimento", "Você precisa inserir o Email e Senha.", "error");
			
		}
		
	};
	
}]);


app.controller('contactCtl',[ '$scope', '$http', 'SweetAlert',  function(s,h,sa){
	
	s.name = '';
	s.email = '';
	s.subject = '';
	s.msg = '';
	
	s.sendMessage = function(isValid, form){
		
		
		
		if( isValid ) {
			
			h({
				method : 'post',
				url: baseUrl+'submitMessage',
				data: $.param({ 
					'email' : s.email,
                    'name' : s.name,
                    'subject' : s.subject,
                    'message' : s.msg,
					'csrf_test_name' : $(".retrieve-password input:hidden[name='csrf_test_name']").val()
				}),
				headers: {'Content-Type' : 'application/x-www-form-urlencoded;'}
			}).then(function(response) {
				
				if( response.data.status == 'error' ) {
					
					sa.swal("Erro", response.data.msg, "error");
					
				}else if( response.data.status == 'success' ) {
					
					sa.swal("Sucesso", response.data.msg, "success");
					
                    s.name = '';
                    s.email = '';
                    s.subject = '';
                    s.msg = '';
                    
                    form.$setPristine();
					form.$setUntouched();
					
                    
				}
				
				s.isLoading = false;

			});

			
		}
		
		
		
	};
	
	
}]);


app.directive('getCertificate', function() {
	
	html = '<div class="get-certificate-box">';
		html += '<div class="header">';
			html += '<div class="search-input">';
				html += '<input placeholder="Buscar certificado (Nome da atividade/trabalho)..." ng-model="search" autofocus ng-model-options="{ debounce: 600 }">';
			html += '</div>';
			html += '<div class="select-type">';
				html += '<select ng-options="x for x in selectOptions" ng-model="selectedOption"></select>';
			html += '</div>';
		html += '</div>'
		html += '<div class="table">';
			html += '<table>';
				html += '<thead>';
					html += '<tr>';
						html += '<th>Nome</th>';
						html += '<th>Autores</th>';
						html += '<th></th>';
					html += '</tr>';
				html += '</thead>';
				html += '<tbody>';
				
					html += '<tr ng-repeat="s in source" ng-show="!isLoading" >';
						html += '<td style="width: 40%;"><b>{{ s.title | uppercase }}</b></td>';
						html += '<td style="width: 45%;">{{ s.authors | authors | uppercase }}</td>';
						html += '<td><a href="{{ baseUrl }}dashboard/certificate/generate?id={{ s.id }}&type={{ type }}" target="_blank">Acessar Certificado</a></td>';
					html += '</tr>';
					
				html += '</tbody>';
			html += '</table>';
			
			html += '<div class="sk-circle" ng-show="isLoading" >';
				html += '<div class="sk-circle1 sk-child"></div>';
				html += '<div class="sk-circle2 sk-child"></div>';
				html += '<div class="sk-circle3 sk-child"></div>';
				html += '<div class="sk-circle4 sk-child"></div>';
				html += '<div class="sk-circle5 sk-child"></div>';
				html += '<div class="sk-circle6 sk-child"></div>';
				html += '<div class="sk-circle7 sk-child"></div>';
				html += '<div class="sk-circle8 sk-child"></div>';
				html += '<div class="sk-circle9 sk-child"></div>';
				html += '<div class="sk-circle10 sk-child"></div>';
				html += '<div class="sk-circle11 sk-child"></div>';
				html += '<div class="sk-circle12 sk-child"></div>';
			html += '</div>';
			
			html += '<ul ng-show="!isLoading">';
				html += '<li ng-repeat="p in pages" ><a ng-click="changePage(p)" style="cursor:pointer;" ng-class="{ active: p == page}">{{ p }}</a></li>';
			html += '</ul>';
						
		html += '</div>';
	html += '</div>';
	
	return {
		restrict: 'E',
		template: html,
		controller: ['$scope', '$http', function($scope, $http) {
			
			$scope.isLoading = false;
			
			$scope.type = 'paper';

			$scope.baseUrl = $('#base-url').val();

			$scope.selectOptions = ['Artigo', 'Pôster'];
			$scope.selectedOption = $scope.selectOptions[0];

			$scope.source = [];
			
			$scope.search = '';
			$scope.numberResults = 0;
			$scope.page = 1;
			$scope.pages = [1];
			
			$scope.$watch('selectedOption', function(newValue, oldValue) {
				if(newValue === oldValue) { return; };
				
				if($scope.selectedOption === 'Artigo') {
					$scope.type = 'paper';
				} else if($scope.selectedOption === 'Pôster') {
					$scope.type = 'poster';
				}
				
				$scope.getSource();
				
			});
			
			$scope.$watch('search', function(newValue, oldValue) {
				if(newValue === oldValue) { return; };
				
				$scope.getSource();
				$scope.page = 1;
			});
			
			$scope.$watch('page', function(newValue, oldValue) {
				if(newValue === oldValue) { return; };
				
				$scope.getSource();
			});
			
			$scope.getSource = function() {
				
				var url = '';
				
				if($scope.selectedOption === 'Artigo') {
					url = $('#base-url').val()+'paper/get-source?page='+$scope.page+'&search='+$scope.search
				} else if($scope.selectedOption === 'Pôster') {
					url = $('#base-url').val()+'poster/get-source?page='+$scope.page+'&search='+$scope.search
				}
			
				$scope.isLoading = true;
			
				$http({
					method: 'GET',
					url: url
				}).then(function successCallback(response) {
					
					$scope.source = response.data.data;
					
					$scope.pages = [];
					for(var i = 0; i < parseInt(response.data.numberResults/10)+1; ++i ) {
						$scope.pages[i] = i+1;
					}
					
					$scope.isLoading = false;
				});
				
			};
			
			$scope.changePage = function(p) {
				$scope.page = p;
			};
			
			$scope.getSource();
			
		}]
	}
	
	
});

app.filter('authors', function() {
	
	return function(input) {
		
		return input.replace('||', ' , ');
		
	}
	
});