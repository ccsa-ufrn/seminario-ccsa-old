var app = angular.module(
		"installerApp", 
		[
			"databaseForm",
			"ui.bootstrap"
		]
	);

app.factory('installerInfoFac', [ function(){
	
	var database = {

		host : '127.0.0.1',
		user : '',
		password : ''

	}

	return {

		getDatabase : function(){
			return database;
		},
		setDatabase : function(db){
			database = db;
		}

	}

}]);

app.controller('appCtl', [ '$scope' , function($scope){

	$scope.hide = {

		databaseForm : false,
		createDatabaseForm : true

	}

}]);

app.controller('ModalInstanceCtrl', function ($scope, $modalInstance) {

  $scope.ok = function () {
    $modalInstance.dismiss('ok');
  };

});