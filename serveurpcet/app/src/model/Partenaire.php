<?php
use RedBean_Facade as R;
 
class Partenaire {
	
	public static $nameTable = "partenaire";
	
	public static function creerPartenaire($nomPartenaire){
		$partenaire = R::dispense(Partenaire::$nameTable);
		$partenaire->nom_partenaire = $nomPartenaire;
		$idPartenaire = R::store($partenaire);
		return $partenaire;
	}
	
	public static function listerPartenaires() {
		$partenaires = R::findall(Partenaire::$nameTable, "ORDER BY nom_partenaire ASC");
	
		return $partenaires;
	}
	
	public static function recupererPartenaire($idPartenaire) {
		$partenaire = R::findOne(Partenaire::$nameTable, " id = ? ", array($idPartenaire));
	
		if($partenaire == null)
			return null;
	
		return $partenaire;
	}
	
	public static function chercherPartenaires($nomPartenaire) {
		$nom = "%".$nomPartenaire."%";
		$partenaires = R::find(Partenaire::$nameTable, " nom_partenaire like ? ", array($nom));
	
		return $partenaires;
	}
	
	public static function renommerPartenaire($idPartenaire, $nouveauNom){
		$partenaire = Partenaire::recupererPartenaire($idPartenaire);
	
		if($partenaire == null)
			return null;
	
		$partenaire->nom_partenaire = $nouveauNom;
		R::store($partenaire);
	
		return $partenaire;
	}
	
	public static function supprimerPartenaire($idPartenaire){
		$partenaire = Partenaire::recupererPartenaire($idPartenaire);
	
		if($partenaire == null)
			return null;
	
		R::trash($partenaire);
		return "OK";
	}
	
	public static function ajouterAction($partenaire,$action){
		$partenaire->sharedAction[]=$action;
		R::store($partenaire);
		return;
	}
	
	}
?>
		