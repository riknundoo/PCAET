'use strict';

/* Controllers */

angular
.module('myApp.controllers.historique', []).controller('historique',['$scope','$http','$filter','$routeParams',function($scope, $http, $filter,  $routeParams) {
							
	$scope.historique = [];


		$http.get('/serveurpcet/index.php/pcaet/actions/historique/'+$routeParams.id).success(function(data, status, headers) {
	  		$scope.historique = data;
	  	});

	} ]);