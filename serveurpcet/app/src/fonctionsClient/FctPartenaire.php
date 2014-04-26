<?php
use RedBean_Facade as R;

class FctPartenaire {
		
	public static function formePartenaireArray($partenaire) {
		$partenaireTab = array();
		$partenaireTab['id']=$partenaire->id;
		$partenaireTab['nom_partenaire']=$partenaire->nom_partenaire;
		return $partenaireTab;
	}
	
	public static function ajouterUnPartenaire($idAction,$nomPartenaire,$loginUtilisateur) {
		$action = Action::getActionById($idAction);
		$partenaine = Partenaire::creerPartenaire($nomPartenaire);
		Action::addPartenaire($action, $partenaine);
		$dateModif = date('Y-m-d H:i:s');
		$typeModif = "Liaison d'un partenaire à l'action";
		$description = "Le partenaire \"".$nomPartenaire."\" a été lié à l'action.";
		Log::creerLog($dateModif, $typeModif, $loginUtilisateur, $action->code_action, $description);
		return;
	}
}
?>
