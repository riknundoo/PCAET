<?php
use RedBean_Facade as R;

class CrAction {
	
	public static $nameTable = "craction";
	
	public static function creerCrAction($DescriptionCRAction,$estActualite){
		return CrAction::creerCrActionP(date('Y-m-d H:i:s'), $DescriptionCRAction, $estActualite);
	}
	
	public static function creerCrActionP($dateCRAction,$DescriptionCRAction,$estActualite){
		$crAction = R::dispense(CrAction::$nameTable);
		$crAction->date_cr_action = $dateCRAction;
		$crAction->description_cr_action = $DescriptionCRAction;
		$crAction->est_actualite = $estActualite;
		$idCRAction = R::store($crAction);
		return $crAction;
	}
	
	public static function recupererCrAction($idCr) {
		$crAction = R::findOne(CrAction::$nameTable, " id = ? ", array($idCr));
		if($crAction == null)
			return null;
		return $crAction;
	}
	
	public static function supprimerCr($idCr){
		$crAction = CrAction::recupererCrAction($idCr);
		if($crAction == null)
			return null;
		R::trash($crAction);
		return "OK";
	}

}
?>