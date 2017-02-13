var dc = angular.module("databaseForm",[]);

dc.controller( "databaseFormCtl" , [ '$scope', '$http', 'installerInfoFac', '$modal', function( $scope, $http, iifac, $modal){

	$scope.database = iifac.getDatabase();
	$scope.continueIsDisabled = true;

	$scope.testConn = function(){

		$http.get(

			document.getElementById('base-url').value+"install/testmysql",
			{ 
				params: {
					'host' : $scope.database.host,
					'username' : $scope.database.user,
					'password' : $scope.database.password
				}
			 }

		).success(function(data) {

     		if(data==="ok"){

     			$scope.continueIsDisabled = false;

     			$modal.open({
			      animation: true,
			      templateUrl: document.getElementById('base-url').value+'install/modaltemplate',
			      controller: 'ModalInstanceCtrl',
			      resolve: {
			      	msg : function(){ 
			      		return 'A conexão estabeleceu-se com <b>sucesso</b>, agora você pode continuar para o próximo passo.'; 
			      	}
			      }
			    });

     		}else{

     		}
     		
    	});

	}

}]);

dc.directive('dfcBtnTestConn', function(){

	return{

		link: function(scope, element, attrs){

			scope.$watch('continueIsDisabled',function(newValue, oldValue){
				//if(scope.continueIsDisabled===false)
					//alert("Ai pai, tá na hora de seguir...");
			});

		}

	}

});