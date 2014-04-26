<?php
use RedBean_Facade as R;

class MotClef {
	
	public static $nameTable = "motclef";
	
	public static function creerMotClef($nomMotClef){
		$motClef = R::dispense(MotClef::$nameTable);
		$motClef->nom_mot_clef = $nomMotClef;
		R::store($motClef);
		return $motClef;
	}
	
	public static function lierMotClefAction($motClef, $action) {
		$motClef->sharedAction[] = $action;
		R::store($motClef);
		return $motClef;
	}
	
	public static function getMotsClefsNonLiesAction($action){
		$motsclefs = R::findAll(MotClef::$nameTable);
	
		$motsclefsNonLie=array();
	
		foreach($motsclefs as $motclef){
			$lie=false;
			foreach($motclef->sharedAction as $motclefAction){
				if($action->id==$motclefAction->id){
					$lie=true;
				}
			}
			if(!$lie){
				$motsclefsNonLie[]=$motclef;
			}
		}
		return $motsclefsNonLie;
	}
	
	public static function listerMotsClefs() {
		$motsClefs = R::findAll(MotClef::$nameTable, "ORDER BY nom_mot_clef ASC");
		return $motsClefs;
	}
	
	public static function recupererMotClef($idMotClef) {
		$motClef = R::findOne(MotClef::$nameTable, " id = ? ", array($idMotClef));
		if($motClef == null)
			return null;
		return $motClef;
	}
	
	public static function chercherMotClef($nomMotClef) {
		$nom = "%".$nomMotClef."%";
		$motsClefs = R::find(MotClef::$nameTable, " nom_mot_clef LIKE ? ", array($nom));
		if($motsClefs == null)
			return null;
		return $motsClefs;
	}
	
	public static function chercherOneMotClef($nomMotClef) {
		$motsClefs = R::findOne(MotClef::$nameTable, " nom_mot_clef = ? ", array($nomMotClef));
		if($motsClefs == null)
			return null;
		return $motsClefs;
	}
	
	public static function renommerMotClef($idMotClef, $nouveauNom) {
		$motClef = R::findOne(MotClef::$nameTable, " id = ? ", array($idMotClef));
		if($motClef == null)
			return null;
		$motClef->nom_mot_clef = $nouveauNom;
		R::store($motClef);
		return $motClef;
	}
	
	public static function supprimerMotClef($idMotClef) {
		$motClef = R::findOne(MotClef::$nameTable, " id = ? ", array($idMotClef));
		if($motClef == null)
			return null;
		R::trash($motClef);
		return "OK";
	}
	
	public static function getMotClefNonLie($action){
		$motclefs = R::findAll(MotClef::$nameTable);
	
		$motclefNonLie=array();
	
		foreach($motclefs as $motclef){
			$lie=false;
			foreach($motclef->sharedAction as $actionIn){
				if($actionIn->id==$action->id){
					$lie=true;
				}
			}
			if(!$lie){
				$motclefNonLie[]=$motclef;
			}
		}
		return $motclefNonLie;
	}
}

