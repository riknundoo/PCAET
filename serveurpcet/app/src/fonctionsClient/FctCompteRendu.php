<?php
use RedBean_Facade as R;

class FctCompteRendu {
	
	public static function deleteCrCompteRendu($idCr, $idUtilisateur){
		$user = Utilisateur::recupererUtilisateur($idUtilisateur);
		$crACtion = CrAction::recupererCrAction($idCr);
		$action = $crACtion->action;
		if(!Action::aDroitDeModification($action,$user)){
			return null;
		}
		
		if(Utilisateur::estChefDeProjet($user->login_utilisateur) || $crACtion->utilisateur->id == $idUtilisateur){
			Log::creerLog(date('Y-m-d H:i:s'), "Suppression commentaire", $user->login_utilisateur, $crACtion->action->code_action, "Suppression commentaire");
			CrAction::supprimerCr($idCr);
			return true;
		}
		return null;
	}

	public static function ajouterCompteRendu($idAction, $idUtilisateur, $descriptionCrAction, $estActualite){
		$action = Action::getActionById($idAction);
		$utilisateur = Utilisateur::recupererUtilisateur($idUtilisateur);
		if(!Action::aDroitDeModification($action,$utilisateur)){
			return null;
		}
		
		if($action == null)
			return null;
		
		if($utilisateur == null)
			return null;
		
		$crAction = CrAction::creerCrAction($descriptionCrAction, $estActualite);
		Log::creerLog(date('Y-m-d H:i:s'), "Ajout d'un compte rendu action", $utilisateur->login_utilisateur, $action->code_action, "Ajout du compte rendu ".$descriptionCrAction);
		
		if($crAction == null)
			return null;
		
		Action::addCrAction($action, $crAction);
		Utilisateur::addCrAction($utilisateur, $crAction);
		
		return $crAction;
	}
	
	public static function listerComptesRendusParAction($idAction){
		$action = Action::getActionById($idAction);
			
		if($action == null)
			return null;
				
		$crActions = $action->ownCraction;
		
		$listeCrActions = array();
		
		foreach($crActions as $crAction){
			$listeCrAction=array();
			$listeCrAction['id']=$crAction->id;
			$listeCrAction['date_cr_action']=$crAction->date_cr_action;
			$listeCrAction['description_cr_action']=$crAction->description_cr_action;
			if($crAction->utilisateur!=null){
				$listeCrAction['nom_utilisateur']=$crAction->utilisateur->nom_utilisateur;
				$listeCrAction['prenom_utilisateur']=$crAction->utilisateur->prenom_utilisateur;
			}
			else{
				$listeCrAction['nom_utilisateur']='';
				$listeCrAction['prenom_utilisateur']='';
			}
			$listeCrActions[]=$listeCrAction;
		}

		return $listeCrActions;
	}
	
	
}
?>