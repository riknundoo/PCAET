<?php
use RedBean_Facade as R;

class FctDocument {
	
	public static function formeDocumentArray($document){
		$documentTab = array();
		$documentTab['id']=$document->id;
		$documentTab['nom_document']=$document->nom_document;
		$documentTab['path']=$document->path;
		return $documentTab;
	}
	
	public static function getAllActionNonLie($login,$idDocument) {
		
		$document = Document::recupererDocument($idDocument);
		$actions = Action::getDocumentNonLieDocument($document);
		
		$actionsRep=array();
		foreach($actions as $action){
			$actionRep=array();
			$actionRep['id']=$action->id;
			$actionRep['code_action']=$action->code_action;
			$actionRep['nom_action']=$action->nom_action;
			$actionsRep[]=$actionRep;
		}
		return $actionsRep;
	}  
	
	public static function getAllActionLie($login,$idDocument) {
		$document = Document::recupererDocument($idDocument);
		$actions = $document->sharedAction;
		$actionsRep=array();
		foreach($actions as $action){
			$actionRep=array();
			$actionRep['id']=$action->id;
			$actionRep['code_action']=$action->code_action;
			$actionRep['nom_action']=$action->nom_action;
			$actionsRep[]=$actionRep;
		}
		return $actionsRep;
	}
	
	public static function lieActionDocument($login,$idAction,$idDocument) {
		$document = Document::recupererDocument($idDocument);
		$action = Action::getActionById($idAction);
		Action::addDocument($action, $document);
		$dateModif = date('Y-m-d H:i:s');
		$typeModif = "Liaison d'un document à l'action";
		$description = "Le document \"".$document->nom_document."\" a été lié à l'action.";
		Log::creerLog($dateModif, $typeModif, $login, $action->code_action, $description);
		return array('success' => 'ok');
	}
	
	public static function delieActionDocument($login,$idAction,$idDocument) {
		$document = Document::recupererDocument($idDocument);
		$action = Action::getActionById($idAction);
		Action::deleteDocument($action, $document);
		$dateModif = date('Y-m-d H:i:s');
		$typeModif = "Déliaison d'un document de l'action";
		$description = "Le document \"".$document->nom_document."\" a été délié de l'action.";
		Log::creerLog($dateModif, $typeModif, $login, $action->code_action, $description);
		return array('success' => 'ok');
	}

	
	public static function supprimerDocument($login,$idDocument) {
		Document::supprimerDocument($idDocument);
		return array('success' => 'ok');
	}	
	
}
?>