'use strict';

/* Controllers */

angular.module('myApp.controllers', [])
.provider("proConnexion",function(){	
	this.$get = ["$http","$q",function($http,$q){
		return {
			getDonneeUtilisateur:function(){
				var d = $q.defer();
				$http.get('/serveurpcet/index.php/compte/info').success(function(data, status, headers) {
					//authToken = headers('A-Token');
					d.resolve(data);
				}).error(function(err){
					if(_dev)
						$('#myModalConnexion').modal('hide');
					else
						$('#myModalConnexion').modal('show');
				});
				return d.promise;
			}
		};
	}];
})
.
config(function () {
}).controller('bidon', [ function() {

} ]).controller('AssocierDocuments',
		[ '$scope', '$modalInstance','d','$http', function($scope, $modalInstance, d,$http) {
			$scope.bool = true;
			
			$scope.loadActionNonlier = function(){
				$http.get('/serveurpcet/index.php/document/actionnonlie/'+d.id).success(function(data, status, headers) {
					$scope.actionnonlies = [];
					$scope.actionnonlies = data;
				});
			};
			
			$scope.loadActionlier = function(){
				$http.get('/serveurpcet/index.php/document/actionlie/'+d.id).success(function(data, status, headers) {
					$scope.actionlies = [];
					$scope.actionlies = data;
				});
			};
			
			$scope.loadActionNonlier();
			$scope.loadActionlier();
			
			$scope.lierAction = function(action){
				$http.put('/serveurpcet/index.php/document/lier/'+action.id+'/'+d.id).success(function(data, status, headers) {
					$scope.loadActionNonlier();
					$scope.loadActionlier();
				});
			};
			
			$scope.delierAction = function(action){
				$http.put('/serveurpcet/index.php/document/delier/'+action.id+'/'+d.id).success(function(data, status, headers) {
					$scope.loadActionNonlier();
					$scope.loadActionlier();
				});
			};
			
			
			
			$scope.ok = function() {
				$modalInstance.close($scope.bool);
			}
			$scope.cancel = function() {
				$modalInstance.dismiss('cancel');
			}

} ]).controller('menu', ['$scope','$location','proConnexion',function($scope,$location,proConnexion) {
	$scope.onglets = [{
		display:"Accueil",
		path:"/accueil"
	},{
		display:"Actions",
		path:"/actions",
		otherPath:["action"] 
	},{
		display:"Dépôt de documents",
		path:"/gestiondedoc"
	}
//	},{
//		display:"Tableau de bord",
//	},{
//		display:"Planning",
		
	];
	
	$scope.donneeUtilisateur = {};

	proConnexion.getDonneeUtilisateur().then(function(data){
		$scope.donneeUtilisateur = data;
		if($scope.donneeUtilisateur.role_utilisateur==1)
		{
			$scope.onglets.push({
		display:"Utilisateurs",
		path:"/utilisateurs",
		otherPath:["utilisateurs"]
	});
			$scope.onglets.push({
		display:"Notifications",
		path:"/notification"});

		}
		console.log("Role "+$scope.donneeUtilisateur.role_utilisateur)
	});
	$scope.isActive = function(onglet){
		//
		//var deferred = $q.defer();
		var b = true;
		if(onglet.path == $location.path()){
			b=true;
		}else{
			b=false;
		}
		if(angular.isArray(onglet.otherPath)){
			angular.forEach(onglet.otherPath,function(otherpath){
				var path = $location.path();
				if(path.indexOf(otherpath)!=-1){
					b=true;
				}else{
					b=false;
				}
			});
		}
		return b;
	};

	
} ]);
