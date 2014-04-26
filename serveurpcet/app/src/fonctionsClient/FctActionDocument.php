<?php
// TODO: finir la mise à jour des trucs sur le drive
use RedBean_Facade as R;
class FctActionDocument {
	
	public static function documentsDAction($idAction) {
		$action = Action::getActionById($idAction);
		$documents = array();
		foreach($action->sharedDocument as $document){
			$documents[]=FctDocument::formeDocumentArray($document);
		}
		return $documents;
	}

}


?>
