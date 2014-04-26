'use strict';

/* Controllers */

angular.module('myApp.controllers.actions', []).provider("proConnexion",function(){	
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
}).controller('actions', [ '$scope','$http','$location','$anchorScroll' ,'proConnexion',function($scope, $http,$location,$anchorScroll,proConnexion) {
$scope.actionsGeneral = [];
$scope.mots_clefs = [];
$scope.objectifs_strategiques = [];
$scope.engagement_thematique = [];

$scope.annulerFiltreEtat=true;
$scope.formulaireCollapse=true;

$scope.actionGeneralRequest = function (){
  $http.get('/serveurpcet/index.php/pcaet/actions').success(function(data, status, headers) {
	  $scope.actionsGeneral = data;
	  //TODO: DSL ces lignes pose problème pour le merge coté serveur 
	  //$scope.actionsGeneral.date_debut = Date.parse(date_debut);
	  //$scope.actionsGeneral.date_fin = Date.parse(date_fin);
  }); 
}; 
$scope.actionGeneralRequest();


$scope.actionUtilisateurRequest = function (utilisateur){
	$http.get('/serveurpcet/index.php/pcaet/actions/'+utilisateur.id).success(function(data, status, headers) {
		$scope.actionsUtilisateur = data;
	});
};

$scope.donneeUtilisateur = {};
proConnexion.getDonneeUtilisateur().then(function(data){
	$scope.donneeUtilisateur = data;
	$scope.actionUtilisateurRequest(data);
	console.log(data);
	$scope.actionUtilisateurClique = function(action) {
		   $location.path("/action/consulter/"+action.id+"/true");
	};
	$scope.actionNomClique = function(action) {
		if(data.role_utilisateur == 1){
			$location.path("/action/consulter/"+action.id+"/true");
		}else{
			$location.path("/action/consulter/"+action.id+"/false");
		}
	};
});


$scope.sort_mes_actions = "code_action";
$scope.sort_toutes_actions = "code_action";
$scope.reverse = false;
$scope.reverse_toutes_actions = false;

$scope.changerTri = function (value){ 
	if ($scope.sort_mes_actions == value){
	      $scope.reverse = !$scope.reverse;
	      return;
	}

	    $scope.sort_mes_actions = value;
	    $scope.reverse = false;
	    
};

$scope.changerTriToutesActions = function (value){ 
	if ($scope.sort_toutes_actions == value){
	      $scope.reverse_toutes_actions = !$scope.reverse_toutes_actions;
	      return;
	}

	    $scope.sort_toutes_actions = value;
	    $scope.reverse_toutes_actions = false;
	    
};
$scope.filtre_avance="toutesactions";

$scope.RAZfiltre = function(){
	$scope.mesactions = "";
	$scope.toutesactions= "";
	$scope.filtre_avance="toutesactions";
	$scope.sort_mes_actions = "code_action";
	$scope.sort_toutes_actions = "code_action";
	$scope.reverse = false;
	$scope.reverse_toutes_actions = false;
	$scope.annulerFiltreEtat=true;
};

$scope.enableFiltreButton=function(){
	if($scope.formulaireCollapse==true) $scope.formulaireCollapse=false;
	else $scope.formulaireCollapse=true;
	$scope.annulerFiltreEtat=$scope.formulaireCollapse;
}; 



	

	
	
	/*
	 * Recherche des actions
	 */
 
  $scope.getObjectifsStrategiques = function(){
	  $http.get('/serveurpcet/index.php/pcaet/actions/objectifs_strategiques/').success(function(data, status, headers) {
		    $scope.objectifs_strategiques = data;
	  });
  };
  
	
  $scope.getmots_clefs = function(){
	  $http.get('/serveurpcet/index.php/pcaet/mots-clefs').success(function(data, status, headers) {
		    $scope.mots_clefs = data;
	  });
  };
  
  
 
  $scope.getThematiques = function(){
	  $http.get('/serveurpcet/index.php/pcaet/actions/thematiques/').success(function(data, status, headers) {
		    $scope.engagement_thematique = data;
	  });
	  
 
 
  };
}
  
]);
