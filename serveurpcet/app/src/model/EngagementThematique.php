<?php
use RedBean_Facade as R;

class EngagementThematique {
	
	public static $nameTable = "engagementthematique";
	
	public static function creerEngagementThematique($codeEngagementThematique,$nomEngagementThematique){
		$engagement_thema = R::dispense(EngagementThematique::$nameTable);
		$engagement_thema->code_engagement_thematique = $codeEngagementThematique;
		$engagement_thema->nom_engagement_thematique = $nomEngagementThematique;
		
		$idEngageThema = R::store($engagement_thema);
		return $engagement_thema;
	}
	
	public static function addObjectifStrategique($engagement_thema,$objectifStrategique){
		$engagement_thema->ownObjectifstrategique[]=$objectifStrategique;
		R::store($engagement_thema);
		return;
	}
	
	public static function listerEngagementsThematique() {
		$engagements_themas = R::findAll(EngagementThematique::$nameTable, "ORDER BY code_engagement_thematique ASC");
	
		return $engagements_themas;
	}
	
	public static function recupererEngagementThematique($idEngagementThematique) {
		$engagement_thema = R::findOne(EngagementThematique::$nameTable, " id = ? ", array($idEngagementThematique));
	
		if($engagement_thema == null)
			return null;
	
		return $engagement_thema;
	}
	
	public static function chercherEngagementsThematique($nomEngagementThematique) {
		$nomEngagementThema = "%" . $nomEngagementThematique . "%";
		$engagements_themas = R::find(EngagementThematique::$nameTable, " nom_engagement_thematique like ? ", array($nomEngagementThema));
	
		return $engagements_themas;
	}
	
	public static function renommerEngagementThematique($idEngagementThematique, $nouveauNom){
		$engagement_thema = EngagementThematique::recupererEngagementThematique($idEngagementThematique);
	
		if($engagement_thema == null)
			return null;
	
		$engagement_thema->nom_engagement_thematique = $nouveauNom;
		R::store($engagement_thema);
	
		return $engagement_thema;
	}
	
	public static function supprimerEngagementThematique($idEngagementThematique){
		$engagement_thema = EngagementThematique::recupererEngagementThematique($idEngagementThematique);
	
		if($engagement_thema == null)
			return null;
	
		R::trash($engagement_thema);
		return "OK";
	}
	
}

?>
