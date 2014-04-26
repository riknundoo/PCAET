<?php 

use RedBean_Facade as R;

class ApiLog {
	
	public static function addHttpRequest($app) {
		
		// Lister tous les logs
		$app->get('/pcaet/notifications/', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
	
			//fonction réservée au chef de projet
			if(!Utilisateur::estChefDeProjet($_SESSION['login'])){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			$logs = FctLog::listerLogs();
				
			echo json_encode($logs);
			return;		
		});

		// Lister tous les logs d'une action
		$app->get('/pcaet/actions/historique/:id', function ($id) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$action = Action::getActionById($id);
			$modifications = FctLog::listerLogsParAction($action->id);
			echo json_encode($modifications);
			return;
		});
	
	return $app;
	}
}
?>
