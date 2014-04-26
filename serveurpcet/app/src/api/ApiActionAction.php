<?php 

use RedBean_Facade as R;

class ApiACtionAction {
	
	public static function addHttpRequest($app) {
		
		$app->put('/action/lier/:idActionPere/:idActionFils', function ($idActionPere,$idActionFils) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			FctActionAction::lierActionPereFils($idActionPere, $idActionFils, $_SESSION['login']);
			$rep = array('success' => 'ok');
			echo json_encode($rep);
			return;
		});
		
		$app->put('/action/delier/:idActionPere/:idActionFils', function ($idActionPere,$idActionFils) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
				
			FctActionAction::delierActionPereFils($idActionPere, $idActionFils,$_SESSION['login']);
			$rep = array('success' => 'ok');
			echo json_encode($rep);
			return;
		});
			
		
		return $app;
	}
}
?>
