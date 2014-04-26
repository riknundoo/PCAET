<?php
use RedBean_Facade as R;

class ObjectifStrategique {
	public static $nameTable = "objectifstrategique";

	public static function creerObjectifStrategique($codeObjectifStrategique,$nomObjectifStrategique){
		$obj_strat = R::dispense(ObjectifStrategique::$nameTable);
		$obj_strat->code_objectif_strategique = $codeObjectifStrategique;
		$obj_strat->nom_objectif_strategique = $nomObjectifStrategique;
		$idObjStrat = R::store($obj_strat);
		return $obj_strat;
	}

	
	public static function addAction($objectifstrategique,$action){
		$objectifstrategique->ownAction[]=$action;
		R::store($objectifstrategique);
		return;
	}
	

	public static function listerObjectifStrategique() {
		$objs_strats = R::findAll(ObjectifStrategique::$nameTable, "ORDER BY code_objectif_strategique ASC");
	
		return $objs_strats;
	}
	
	public static function recupererObjectifStrategique($idObjectifStrategique) {
		$obj_strat = R::findOne(ObjectifStrategique::$nameTable, " id = ? ", array($idObjectifStrategique));
	
		if($obj_strat == null)
			return null;
	
		return $obj_strat;
	}
	
	public static function chercherObjectifStrategique($nomObjectifStrategique) {
		$nomObjThema = "%" . $nomObjectifStrategique . "%";
		$objs_strats = R::find(ObjectifStrategique::$nameTable, " nom_objectif_strategique like ? ", array($nomObjThema));
	
		return $objs_strats;
	}
	
	public static function renommerObjectifStrategique($idObjectifStrategique, $nouveauNom){
		$obj_strat = ObjectifStrategique::recupererObjectifStrategique($idObjectifStrategique);
	
		if($obj_strat == null)
			return null;
	
		$obj_strat->nom_objectif_strategique = $nouveauNom;
		R::store($obj_strat);
	
		return $obj_strat;
	}
	
	public static function supprimerObjectifStrategique($idObjectifStrategique){
		$obj_strat = ObjectifStrategique::recupererObjectifStrategique($idObjectifStrategique);
	
		if($obj_strat == null)
			return null;
	
		R::trash($obj_strat);
		return "OK";
	}
	
}
?>