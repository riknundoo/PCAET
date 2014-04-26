'use strict';

/* Controllers */

angular.module('myApp.controllers.action', [])
.controller('action', [ '$scope', '$http', '$routeParams','$modal','proConnexion', function($scope,$http,$routeParams,$modal,proConnexion) {
	$scope.thematiques = [];
	/**
	 * Partie du controller gérant les sous pages
	 */
	// Début configuration du menu
	$scope.onglets = [{
			display:"Action",
			path:"consulter"
		},{
			display:"Avancement",
			path:"gestion"
		}
//	,{
//		display:"Visualisation graphique",
//		path:"graphique"
//	},
		,{
			display:"Historique des modifications",
			path:"historique"
		}
	];
	
	$scope.idbd = $routeParams.id+'/'+$routeParams.modif; // ID de l'action appraet en fin d'url
	$scope.starturl = "action"; // le début de l'url doit être spécifier
	//
	// Permet de spécifier l'onglet selectionner par rapport à l'attribut de
	// l'url
	$scope.ongletselect = $routeParams.etat;
	// Fin configuration du menu
	
	// sousPage défini l'url de la sous pages
	$scope.sousPage = 'partials/action.'+$routeParams.etat+'.html';
	$scope.propritaire=$routeParams.modif;

	/**
	 * Onglet Action (consulter)
	 */
	
	if ($routeParams.etat == 'consulter') {
	  	$scope.action_id = $routeParams.id;
		$scope.actionData;
		
		
		/** DEBUT Associer action **/
		$scope.associerlieraction = function() {
			var modaleInstance = $modal.open({
				templateUrl : "partials/modal.associer.lieraction.html",
				controller : "ModalLierAction",
				resolve : {
					data : function() {
						return $scope.actionDetails;
					}
				}
			});
			modaleInstance.result.then(function() {
				$scope.getInfoAction();
			});
		};
		$scope.delieraction=function(actionlier){
			$http.put('/serveurpcet/index.php/action/delier/'+$scope.actionDetails.id+'/'+actionlier.id).success(function(data, status, headers) {
				$scope.getInfoAction();
			}); 
		};
		/** FIN Associer action **/
		
		/** DEBUT Sauvegarder action **/
		$scope.sauvegarderAction=function(){
			$scope.actionData = $scope.actionData;
			if(angular.isUndefined($scope.actionDetails.nom_objectif_strategique)){
				$scope.actionDetails.nom_objectif_strategique = "";
			}
					
			$http.put('/serveurpcet/index.php/pcaet/actions/',$scope.actionDetails).success(function(res) {
				
				$scope.getInfoAction();
		    }).error(function(e){
		    	
		    });
		};
		/** FIN Sauvegarder action **/
		
		/** DEBUT afficher action **/
		$scope.transformeServeurBool = function(b){
			if(angular.isString(b)){
				if(b=="1"){
					b = true;
				}else{
					b = false;
				};
			};
			return b;
		};
		
		$scope.getInfoAction = function(){
		  	$http.get('/serveurpcet/index.php/pcaet/action/'+$scope.action_id).success(function(data, status, headers) {
		  		$scope.actionDetails = data;
		  		$scope.actionDetails.est_adaptation = $scope.transformeServeurBool($scope.actionDetails.est_adaptation);
		  		$scope.actionDetails.est_attenuation = $scope.transformeServeurBool($scope.actionDetails.est_attenuation);
		  		$scope.actionDetails.est_communication = $scope.transformeServeurBool($scope.actionDetails.est_communication);
		  		$scope.actionDetails.est_formation = $scope.transformeServeurBool($scope.actionDetails.est_formation);
		  		$scope.actionDetails.est_appui_technique = $scope.transformeServeurBool($scope.actionDetails.est_appui_technique);
		  		$scope.actionDetails.est_appui_financier = $scope.transformeServeurBool($scope.actionDetails.est_appui_financier);
		  		/**
		  		 * Correction CSS
		  		 */
		  		$(".formEditable").css("padding-top","0px");
		  	});
		};
		$scope.getInfoAction();
		/** FIN Sauvegarder action **/
		
		/** DEBUT lier mot clé action **/
		
		$scope.motclefnonliers = [];
		$scope.motclefliers = [];
		
		$scope.motclefnonlier = function(){
			$http.get('/serveurpcet/index.php/action/motclef/lier/'+$scope.action_id).success(function(data, status, headers) {
				
				$scope.motclefliers = data;
			});
		};
		
		$scope.motcleflier = function(){
			$http.get('/serveurpcet/index.php/action/motclef/delier/'+$scope.action_id).success(function(data, status, headers) {
				
				$scope.motclefnonliers = data;
			});
		};
		
		$scope.motcleflier();
		$scope.motclefnonlier();
		
		$scope.addMotclef = function(nouveauxMotclef){
			$http.post('/serveurpcet/index.php/action/motclef/',{
				id:$scope.action_id,
				motclef:nouveauxMotclef
			}).success(function(data, status, headers) {
				$scope.motcleflier();
				$scope.motclefnonlier();
				$scope.nouveauxMotclef = "";
			});			
		};
		
		$scope.delierMotClef = function(motclef){
			$http.put('/serveurpcet/index.php/action/motclef/delier/'+$scope.action_id+'/'+motclef.id).success(function(){
				$scope.motcleflier();
				$scope.motclefnonlier();
			});
		};
		$scope.lierMotClef = function(motclef){
			$http.put('/serveurpcet/index.php/action/motclef/lier/'+$scope.action_id+'/'+motclef.id).success(function(){
				$scope.motcleflier();
				$scope.motclefnonlier();
			});
		};
		
		/** DEBUT lier mot clé action **/
		
		
		/** DEBUT list thematique concernee **/
		$scope.thematiques = [];
		$scope.getThematiques = function(){
			$http.get('/serveurpcet/index.php/thematiques').success(function(data){
				$scope.thematiques = data;
			});
		};
		$scope.setThematiqueInData = function(thematique){
			$http.put('/serveurpcet/index.php/thematiques/lierAction/'+$scope.action_id+'/'+thematique.id).success(function(){
				$scope.getInfoAction();
			});
		};
		$scope.getThematiques();
		/** FIN list thematique concernee **/
		
		
		/** DEBUT partenaire **/
		$scope.setParametre = function(nouveauxPartenaire){
			$http.post('/serveurpcet/index.php/partenaire/',{
				id:$scope.action_id,
				nom_partenaire:nouveauxPartenaire
			}).success(function(){
				$scope.getInfoAction();
			});
		};
		
		$scope.delParametre = function(partenaire){
			$http({method: 'DELETE', url: '/serveurpcet/index.php/partenaire/'+partenaire.id}).
				success(function(data, status, headers, config) {
					$scope.getInfoAction();
		    });
		};
		/** FIN partenaire **/
	}
	

	$scope.lienAction={url:'',nom:''};
	$scope.partenaire={nom:''};
	$scope.AjouterLien=function(){
	 var lien={code_action:$scope.lienAction.url, nom_action:$scope.lienAction.nom};
	 $scope.actionDetails.lien_avec_autres_actions.push(lien);
	};

	$scope.setUrl=function(index){
		$scope.lienAction.nom=$scope.actionsGeneral[index].nom_action;
		$scope.lienAction.url=$scope.actionsGeneral[index].action_id;
	};
	$scope.donneeUtilisateur = {};

	proConnexion.getDonneeUtilisateur().then(function(data){
		$scope.donneeUtilisateur = data;
	});
} ]).controller('ModalLierAction',
		[ '$scope', '$modalInstance','$http','data', function($scope, $modalInstance,$http,data) {
			$scope.actionGeneralRequest = function (){
				  $http.get('/serveurpcet/index.php/pcaet/actions').success(function(data, status, headers) {
					  $scope.actionnonliers = data;
				  }); 
				};
			$scope.actionnonliers = $scope.actionGeneralRequest();
			$scope.ok = function(actionnonlie) {
				$http.put('/serveurpcet/index.php/action/lier/'+data.id+'/'+actionnonlie.id).success(function(data, status, headers) {
					  $scope.actionnonliers = data;
					  $modalInstance.close({b:$scope.bool,data:$scope.data});
				}); 
			};
			$scope.cancel = function() {
				$modalInstance.dismiss('cancel');
			};

} ]).run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});
