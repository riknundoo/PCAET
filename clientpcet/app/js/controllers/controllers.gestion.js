'use strict';

/* Controllers */

angular
.module('myApp.controllers.gestion', [ 'ui.bootstrap' ]).provider("proConnexion",function(){	
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
.controller('gestion',['$scope','$http','$filter','$modal', '$routeParams','proConnexion',function($scope, $http, $filter, $modal, $routeParams,proConnexion) {
	$scope.action_id = $routeParams.id;
	$scope.phases = [];
	$scope.indicateurs = [];
							
	$scope.verifierLigne=function(index)
	{
		if ($scope.indicateurs[index].indicateur=='')
			{
			$scope.indicateurs.splice(index, 1);
			}
	}
	$scope.ajout = false;
	$scope.propritaire=$routeParams.modif;
	// gestion des indicateurs
								
	$scope.openIndic = function(indicateur) {
		var modaleInstance = $modal
				.open({
					templateUrl : "partials/modal.suppression.html",
					controller : "ModalSuppressionIndicateur",
					resolve : {
						data : function() {
							return indicateur;
						}
					}
				});
		modaleInstance.result.then(function(b) {
			$scope.modaleResult = b;
			if (b.b) {
					$scope.suppressionIndicateur(b.data);

			}
		});
	};
	
	$scope.suppressionIndicateur = function(data) {
	    $http({method: 'DELETE', url: '/serveurpcet/index.php/pcaet/actions/indicateurs/'+data.id}).
	    success(function(data, status, headers, config) {
	    	$scope.chargerIndicateurs();
	    });
	};
	
	$scope.openCrAction = function(craction) {
		console.log(craction);
		var modaleInstance = $modal
				.open({
					templateUrl : "partials/modal.suppression.html",
					controller : "ModalSuppressionCrAction",
					resolve : {
						data : function() {
							return craction;
						}
					}
				});
		
		modaleInstance.result.then(function(b) {
			$scope.modaleResult = b;
			
			console.log(b);
			if (b.b) {
					$scope.suppressionCrAction(b.data);
			}
		});
	};
	
	$scope.suppressionCrAction = function(data) {
		console.log(data);
	    $http({method: 'DELETE', url: '/serveurpcet/index.php/pcaet/actions/cractions/'+data.id}).
	    success(function(data, status, headers, config) {
	    	$scope.refreshCompteRendu();
	    });
	};
			
	
            proConnexion.getDonneeUtilisateur().then(function(data){
          		$scope.donneeUtilisateur = data;
          		if($scope.donneeUtilisateur.role_utilisateur==1  || $scope.propritaire=='true' ){
          			$scope.annulerButton='Annuler';
          		}else{
          			$scope.annulerButton='Retour';
          		}
          	});
				            
			$scope.sauvegarderIndicateurModif = function() {
				if($scope.titleModal == "Modifier un indicateur"){
				    $http.put('/serveurpcet/index.php/pcaet/actions/indicateurs/', $scope.indicateur).
					success(function(data, status, headers, config) {
						$scope.chargerIndicateurs();
				    });
				}else {
					var b = true;
					angular.forEach($scope.indicateur, function(value, key){
					     if(value ==""){
					    	 b = false;
					     }
					     else {
					    	 b = true;
					     }
					});
					if(b){
						$scope.indicateur.code_action = $scope.action_id;
						$scope.indicateur.type_modification = "Ajout d'un nouvel indicateur";
						$http.post('/serveurpcet/index.php/pcaet/actions/indicateurs/ajout_indicateur/', $scope.indicateur).
						success(function(data, status, headers, config) {
							$scope.chargerIndicateurs();
							$('#idModalMofifierIndicateur').modal('hide');
					    });
					}else{
						alert("Veuillez renseigner tous les champs");
					}
					
				}
				$scope.cacherPopupAjouterIndicateur();
			};
							
							
				//------------------------------------
				$scope.ajouterNouveauindicateur= function() {
					$scope.phase.code_action = $scope.action_id;
					$scope.phase.type_modification = "Ajout d'une nouvelle phase";
				};
				
				$scope.indicateur = {};
				$scope.objectif_enjeu = {};
				$scope.ajouterIndicateur = function(indicateur, objectif_enjeu) {
					$('#idModalMofifierIndicateur').modal('show');
					$scope.titleModal = "Ajouter un indicateur";
					$scope.indicateur.nom_indicateur = "";
					$scope.indicateur.nom_objectif_enjeu = "";
					$scope.indicateur.valeur_actuelle = "";
					$scope.indicateur.valeur_objectif = "";
					$scope.indicateur.description_indicateur = "";
				};
				
				$scope.chargerPopupModifierIndicateur = function(indicateur, objectif_enjeu) {
					if($scope.propritaire != 'false'){	
						$('#idModalMofifierIndicateur').modal('show');
						$scope.titleModal = "Modifier un indicateur";
						$scope.indicateur.id = indicateur.id;
						$scope.indicateur.nom_indicateur = indicateur.nom_indicateur;
						$scope.indicateur.nom_objectif_enjeu = indicateur.nom_objectif_enjeu;
						$scope.indicateur.valeur_actuelle = indicateur.valeur_actuelle;
						$scope.indicateur.valeur_objectif = indicateur.valeur_objectif;
						$scope.indicateur.description_indicateur = indicateur.description_indicateur;
					}
				};
				
				$scope.cacherPopupAjouterIndicateur = function() {
					$('#idModalMofifierIndicateur').modal('hide');
				};
				
				
				// gestion des phases
				$scope.phase = {};
				$scope.chargerPopupModifier = function(phase) {
					$('#idModalMofifierPhase').modal('show');
					$scope.titleModal = "Modifier une phase";
					$scope.phase.id = phase.id;
					$scope.phase.ordre_phase = phase.ordre_phase;
					$scope.phase.nom_phase = phase.nom_phase;
					$scope.phase.date_debut_prevue = phase.date_debut_prevue;
					$scope.phase.date_fin_prevue = phase.date_fin_prevue;
					$scope.phase.date_debut_reelle = phase.date_debut_prevue;
					$scope.phase.date_fin_reelle = phase.date_fin_prevue;
					$scope.phase.description_phase = phase.description_phase;
					$scope.phase.services_porteurs = phase.services_porteurs;
					$scope.phase.avancement_phase = phase.avancement_phase;
					$scope.phase.commentaires_phase = phase.commentaires_phase;
				 };

				$scope.afficherPopupAjouter = function() {
					$('#idModalMofifierPhase').modal('show');
					$scope.titleModal = "Ajouter une nouvelle phase";
					$scope.phase.ordre_phase = "";
					$scope.phase.nom_phase = "";
					$scope.phase.date_debut_prevue = "";
					$scope.phase.date_fin_prevue = "";
					$scope.phase.date_debut_reelle = "";
					$scope.phase.date_fin_reelle = "";
					$scope.phase.description_phase = "";
					$scope.phase.services_porteurs = "";
					$scope.phase.avancement_phase = "Non démarrée";
					$scope.phase.commentaires_phase = "";
				};
			
				// ouverture pop up controlle suppression
				$scope.open = function(phase) {
					var modaleInstance = $modal
							.open({
								templateUrl : "partials/modal.suppression.html",
								controller : "ModalSuppressionPhase",
								resolve : {
									data : function() {
										return phase;
									}
								}
							});
					modaleInstance.result.then(function(b, data) {
						$scope.modaleResult = b;
						if (b.b) {
							$scope.controlleSuppressionPhase(b.data);
						}
					});
				};
				
				$scope.sort_phases = "ordre_phase";

				$scope.controlleSuppressionPhase = function(data) {
					$http({method: 'DELETE', url: '/serveurpcet/index.php/pcaet/actions/phases/'+data.id, data : data}).
				    success(function(data, status, headers, config) {
				    	$scope.chargerPhases();
				    });
				    
				};
				
				$scope.cacherPopupAjouter = function() {
					$('#idModalMofifierPhase').modal('hide');
				};
							

		 $scope.sauvegarderPhaseModif = function() {
				if($scope.titleModal == "Modifier une phase"){
					//formatage des dates
					var day_date_debut_prevue = $scope.formatDate($scope.phase.date_debut_prevue.getDate());
				    var month_date_debut_prevue = $scope.formatDate($scope.phase.date_debut_prevue.getMonth() + 1);
				    var year_date_debut_prevue = $scope.phase.date_debut_prevue.getFullYear();
				    $scope.phase.date_debut_prevue = year_date_debut_prevue+"-"+month_date_debut_prevue+"-"+day_date_debut_prevue;
				    
				    var day_date_fin_prevue = $scope.formatDate($scope.phase.date_fin_prevue.getDate());
				    var month_date_fin_prevue = $scope.formatDate($scope.phase.date_fin_prevue.getMonth() + 1);
				    var year_date_fin_prevue = $scope.phase.date_fin_prevue.getFullYear();
				    $scope.phase.date_fin_prevue = year_date_fin_prevue+"-"+month_date_fin_prevue+"-"+day_date_fin_prevue;
					
				    var day_date_debut_reelle = $scope.formatDate($scope.phase.date_debut_reelle.getDate());
				    var month_date_debut_reelle = $scope.formatDate($scope.phase.date_debut_reelle.getMonth() + 1);
				    var year_date_debut_reelle = $scope.phase.date_debut_reelle.getFullYear();
				    $scope.phase.date_debut_reelle = year_date_debut_reelle+"-"+month_date_debut_reelle+"-"+day_date_debut_reelle;
				    
				    var day_date_fin_reelle = $scope.formatDate($scope.phase.date_fin_reelle.getDate());
				    var month_date_fin_reelle = $scope.formatDate($scope.phase.date_fin_reelle.getMonth() + 1);
				    var year_date_fin_reelle = $scope.phase.date_fin_reelle.getFullYear();
				    $scope.phase.date_fin_reelle = year_date_fin_reelle+"-"+month_date_fin_reelle+"-"+day_date_fin_reelle;
				    
				    
				    $http.put('/serveurpcet/index.php/pcaet/actions/phases/', $scope.phase).
					success(function(data, status, headers, config) {
						$scope.chargerPhases();
				    });
				}else {
					var b = true;
					angular.forEach($scope.phase, function(value, key){
					     if(value ==""){
					    	 b = false;
					     }
					     else {
					    	 b = true;
					     }
					});
					if(b){
						$scope.phase.code_action = $scope.action_id;
						$scope.phase.type_modification = "Ajout d'une nouvelle phase";
						$http.post('/serveurpcet/index.php/pcaet/actions/phases/ajout_phase', $scope.phase).
						success(function(data, status, headers, config) {
							$scope.chargerPhases();
							$('#idModalMofifierPhase').modal('hide');
					    });
					}else{
						alert("Veuillez renseigner tous les champs");
					}
					
				}
				$scope.cacherPopupAjouter();
			};
			
		
			$scope.formatDate = function(date) {
				if (date<10){
					date = "0"+date;
				};
				return date;
			};
		
			// gestion du budget
			
			$scope.openBudgetConfim = function() {
				console.log('0');
				var modaleInstance = $modal
						.open({
							templateUrl : "partials/modal.suppression.html",
							controller : "ModalModifierBudget",
							resolve : {
								data : function() {
									return null;
								}
							}
						});
				modaleInstance.result.then(function() {
							$scope.sauvegarderBudget();

				});
			};
			
			$scope.sauvegarderBudget = function() {
				$scope.budget.type_modification = "Modification du budget";
				$http.put('/serveurpcet/index.php/pcaet/actions/budget/'+$routeParams.id, $scope.budget).
				success(function(data, status, headers, config) {
					$scope.chargerBudget();
			    });
			};
			
			// requete get pour remplir le champ phases à
			// l'ouverture de la page
			$scope.chargerPhases = function() {
				$http.get('/serveurpcet/index.php/pcaet/actions/phases/'+$routeParams.id).success(function(data, status, headers) {
			  		$scope.phases = data;
			  		for (var i = 0; i < $scope.phases.length; i++) {
			  			$scope.phases[i].date_debut_prevue = new Date($scope.phases[i].date_debut_prevue);
						$scope.phases[i].date_fin_prevue = new Date($scope.phases[i].date_fin_prevue);
						$scope.phases[i].date_debut_reelle = new Date($scope.phases[i].date_debut_reelle);
						$scope.phases[i].date_fin_reelle = new Date($scope.phases[i].date_fin_reelle);
			  		}
				});
			};
			
			$scope.chargerPhases();
			
			// requete get pour remplir le champ budget à
			// l'ouverture de la page
			$scope.chargerBudget = function() {
				$http.get('/serveurpcet/index.php/pcaet/actions/budget/'+$routeParams.id).success(function(data, status, headers) {
		  		$scope.budget = data;
				});
			};
			$scope.chargerBudget();
			// requete get pour remplir le champ indicateurs à
			// l'ouverture de la page
			$scope.chargerIndicateurs = function() {
				$http.get('/serveurpcet/index.php/pcaet/actions/indicateurs/'+$routeParams.id).success(function(data, status, headers) {
					$scope.indicateurs =[];
		  		for(var i=0;i<data.length;i++)
		  			{
		  			for(var j=0;j<data[i].length;j++)
		  			$scope.indicateurs.push(data[i][j]);
		  			}
				});
			};
			$scope.chargerIndicateurs();
			// requete get pour remplir les enjeux des indicateurs
			$scope.chargerObjectifs_enjeu = function() {
				$http.get('/serveurpcet/index.php/pcaet/actions/objectifs/'+$routeParams.id).success(function(data, status, headers) {
					$scope.objectifs_enjeu = data;
				});
			};
			$scope.chargerObjectifs_enjeu();

							
							/*
							 * Comptes-rendus
							 */
              $scope.cractions = [];
              
              $scope.newCRAction = {
                  description_cr:'',
                  est_actualite:'false',
                  action_id:$routeParams.id
              };
              
              // requete get pour remplir le tableau des comptes-rendus
              $scope.refreshCompteRendu= function() {
              $http.get('/serveurpcet/index.php/pcaet/actions/cractions/'+$routeParams.id).success(function(data, status, headers) {
                $scope.cractions = data;
              })};
              $scope.refreshCompteRendu();
              
              $scope.afficherAjouterCompteRendu = function() {
                $('#idModalAjouterCR').modal('show');
              }
              
              $scope.fermerAjouterCompteRendu = function() {
                $scope.newCRAction.description_cr = '';
                $('#idModalAjouterCR').modal('hide');
              }
              
              $scope.ajouterCompteRendu = function() { 
                $http.post('/serveurpcet/index.php/pcaet/actions/cractions/', $scope.newCRAction);
                $('#idModalAjouterCR').modal('hide');
                $scope.refreshCompteRendu();
              }
                            // requete get pour lier les documents
              $scope.showDocumentsAction = function() { 
              $http.get('/serveurpcet/index.php/action/documents/'+$routeParams.id).success(function(data, status, headers) {
                $scope.documentsAction = data;
              })};
              $scope.showDocumentsAction();
              
          	$scope.delierDocumentAction = function(document	){
        		$http({
        			method: 'PUT', 
        			url: '/serveurpcet/index.php/document/delier/'+$scope.action_id+'/'+document.id
        		}).success(function(data, status, headers, config) {
        			$scope.showDocumentsAction();
				  	});
        		
          	
          	
          	};
        	

						} ]).controller('ModalSuppressionPhase',
								[ '$scope', '$modalInstance','data', function($scope, $modalInstance, data) {
									$scope.data=data;
									$scope.textHeaderSupp = "Suppression d'une phase";
									$scope.textBodySupp ="Etes-vous sur de vouloir supprimer la phase : " + data.nom_phase  + " ?";
												$scope.bool = true;
									$scope.ok = function() {
										$modalInstance.close({b:$scope.bool,data:$scope.data});
									};
									$scope.cancel = function() {
										$modalInstance.dismiss('cancel');
									};
									
						} ]).controller('ModalSuppressionCrAction',
								[ '$scope', '$modalInstance','data', function($scope, $modalInstance, data) {
									$scope.data=data;
									$scope.textHeaderSupp = "Suppression d'un compte rendu";
									$scope.textBodySupp ="Etes-vous sur de vouloir supprimer le Compte rendu : ?";
												$scope.bool = true;
									$scope.ok = function() {
										$modalInstance.close({b:$scope.bool,data:$scope.data});
									};
									$scope.cancel = function() {
										$modalInstance.dismiss('cancel');
									};

						} ]).controller('ModalSuppressionIndicateur',
								[ '$scope', '$modalInstance','data', function($scope, $modalInstance, data) {
									$scope.data=data;
									$scope.textHeaderSupp = "Suppression d'un indicateur";
									$scope.textBodySupp ="Etes-vous sur de vouloir supprimer l'indicateur : " + data.nom_indicateur  + " ?";
												$scope.bool = true;
									$scope.ok = function() {
										$modalInstance.close({b:$scope.bool,data:$scope.data});
									};
									$scope.cancel = function() {
										$modalInstance.dismiss('cancel');
									};
									

						} ]).controller('ModalModifierBudget',
								[ '$scope', '$modalInstance','data', function($scope, $modalInstance, data) {
									$scope.data=data;
									$scope.textHeaderSupp = "Modification du budget";
									$scope.textBodySupp ="Etes-vous sur de vouloir modifier le budget dédié à cette action ?";
												$scope.bool = true;
									$scope.ok = function() {
										$modalInstance.close({b:$scope.bool,data:$scope.data});
										$modalInstance.iscancel=false;
									};
									$scope.cancel = function() {
										$modalInstance.dismiss('cancel');
									};
									

						} ]).run(function(editableOptions) {
							  editableOptions.theme = 'bs3'; // bootstrap3
																// theme. Can be
																// also 'bs2',
																// 'default'
						});;
