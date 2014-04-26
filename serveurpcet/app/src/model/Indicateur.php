<?php
use RedBean_Facade as R;

class Indicateur {
	
	public static $nameTable = "indicateur";
	
	public static function creerIndicateurAction($nomIndicateur,$valeurActuelle,$valeurObjectif,$descriptionIndicateur){
		$indicateurAction = R::dispense(Indicateur::$nameTable);
		$indicateurAction->nom_indicateur= $nomIndicateur;
		$indicateurAction->valeur_actuelle = $valeurActuelle;
		$indicateurAction->valeur_objectif = $valeurObjectif;
		$indicateurAction->description_indicateur = $descriptionIndicateur;
		$idIndicateurAction = R::store($indicateurAction);
		return $indicateurAction;
	}
	
	public static function listerIndicateurs() {
		$indicateurs = R::findall(Indicateur::$nameTable, "ORDER BY nom_indicateur ASC");
		return $indicateurs;
	}
	
	public static function modifierIndicateur($tabParametres){
		$indicateur = Indicateur::recupererIndicateur($tabParametres['id']);
		//$phase = Phase::recupererPhase($tabParametres['id']);
		if($indicateur == null)
			return null;
		//decaspulation du tableau des nouvelles valeurs
		$nouveauNom = $tabParametres['nom_indicateur'];
		$nouvelleValeurActuelle = $tabParametres['valeur_actuelle'];
		$nouvelleValeurObjectif = $tabParametres['valeur_objectif'];
		$nouvelleDescription = $tabParametres['description_indicateur'];
		
	
		$indicateur->nom_indicateur = $nouveauNom;
		$indicateur->valeur_actuelle = $nouvelleValeurActuelle;
		$indicateur->valeur_objectif = $nouvelleValeurObjectif;
		$indicateur->description_indicateur = $nouvelleDescription;
		
		R::store($indicateur);
		return $indicateur;
	}




	public static function recupererIndicateur($idIndicateur) {
		$indicateur = R::findOne(Indicateur::$nameTable, " id = ? ", array($idIndicateur));
		if($indicateur == null)
			return null;
		return $indicateur;
	}
	
	public static function recupererIndicateurByNom($nameIndicateur) {
		$indicateur = R::findOne(Indicateur::$nameTable, "  = ? ", array($nameIndicateur));
		if($indicateur == null)
			return null;
		return $indicateur;
	}
	
	public static function chercherIndicateurs($nomIndicateur) {
		$nom = "%".$nomIndicateur."%";
		$indicateur = R::find(Indicateur::$nameTable, " nom_indicateur LIKE ? ", array($nom));
		if($indicateur == null)
			return null;
		return $indicateur;
	}
	
	public static function renommerIndicateur($idIndicateur, $nouveauNom){
		$indicateur = Indicateur::recupererIndicateur($idIndicateur);
		if($indicateur == null)
			return null;
		$indicateur->nom_indicateur = $nouveauNom;
		R::store($indicateur);
		return $indicateur;
	}
	
	public static function supprimerIndicateur($idIndicateur){
		$indicateur = Indicateur::recupererIndicateur($idIndicateur);
		if($indicateur == null)
			return null;
		R::trash($indicateur);
		return "OK";
	}
}
?>