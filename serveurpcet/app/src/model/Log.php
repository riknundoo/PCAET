<?php
use RedBean_Facade as R;

class Log {
	
	public static $nameTable = "log";
	
	public static function creerLog($dateModification,$typeModification,$loginUtilisateur,$codeAction,$descriptionModification){
		$log = R::dispense(Log::$nameTable);
		$log->date_modification = $dateModification;
		$log->type_modification = $typeModification;
		$log->description_modification = $descriptionModification;
		$log->login_utilisateur = $loginUtilisateur;
		$log->code_action = $codeAction;
		$idLog = R::store($log);
		return $log;
	}
	
	
	public static function getLogsParCodeAction($action){
		$logs = R::find(Log::$nameTable, ' code_action = ?  ', array($action->code_action));
		return $logs;
	}
	
	public static function getLogsParUtilisateur($utilisateur){
		$logs = R::find(Log::$nameTable, ' login_utilisateur = ?  ', array($utilisateur->login_utilisateur));
		return $logs;
	}
	
	public static function getLogs(){
		$logs = R::find(Log::$nameTable);
		return $logs;
	}
}
?>
