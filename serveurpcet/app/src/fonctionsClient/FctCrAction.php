<?php
use RedBean_Facade as R;

class FctCrAction {
	public static function formeCrActionArray($crAction){
		$crActionTab = array();
		$crActionTab['id']=$crAction->id;
		$crActionTab['date_cr_action']=$crAction->date_cr_action;
		$crActionTab['description_cr_action']=$crAction->description_cr_action;
		$crActionTab['estActualite']=$crAction->estActualite;		
		return $crActionTab;
	}
}
?>