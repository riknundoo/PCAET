<?php
use RedBean_Facade as R;

class FctObjectifStrategique {
	
	public static function formeObjectifStrategiqueArray($objectifStrategique){
		$objectifTab = array();
		$objectifTab['id']=$objectifStrategique->id;
		$objectifTab['code_objectif_strategique']=$objectifStrategique->code_objectif_strategique;
		$objectifTab['nom_objectif_strategique']=$objectifStrategique->nom_objectif_strategique;
		
		if($objectifStrategique->engagementthematique){
			$objectifTab['engagementthematique']=FctEngagementThematique::formeEngagementThematiqueArray($objectifStrategique->engagementthematique);
		}
		return $objectifTab;
	}
	
}
?>