<?php 

use RedBean_Facade as R;

class ApiActionMotClef {
	
	public static function addHttpRequest($app) {

		$app->post('/action/motclef/', function () use($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);	
				return;
			}
			$tableauParametres = json_decode($app->request->getBody(), true);
			FctMotClef::ajoutNouveauMotClefOuLie($tableauParametres['id'], $tableauParametres['motclef'],$_SESSION['login']);
			echo json_encode(array('success' => 'ok'));
			return;

		});
		
		$app->put('/action/motclef/lier/:idAction/:idMotClef', function ($idAction,$idMotClef) use($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			FctMotClef::lierActionMotClef($idAction, $idMotClef, $_SESSION['login']);
			echo json_encode(array('success' => 'ok'));
			return;
		});
		
		$app->put('/action/motclef/delier/:idAction/:idMotClef', function ($idAction,$idMotClef) use($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ok');
				echo json_encode($rep);
				return;
			}
			FctMotClef::delierActionMotClef($idAction, $idMotClef, $_SESSION['login']);
			echo json_encode(array('success' => 'ok'));
			return;
		});
		
		$app->get('/action/motclef/lier/:idAction', function ($idAction) use($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$rep = FctMotClef::motClefLier($idAction);
			echo json_encode($rep);
			return;
		});
		
		$app->get('/action/motclef/delier/:idAction', function ($idAction) use($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$rep = FctMotClef::motClefPasLier($idAction);
			echo json_encode($rep);
			return;
		});
		
		return $app;		
	}
}
?>
