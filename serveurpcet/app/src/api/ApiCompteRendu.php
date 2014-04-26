<?php 

use RedBean_Facade as R;

class ApiCompteRendu {
	
	public static function addHttpRequest($app) {
		

		// Supprimer un indicateur
		$app->delete('/pcaet/actions/cractions/:idCr', function ($idCr) use ($app) {
			
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			$rep = FctCompteRendu::deleteCrCompteRendu($idCr, $_SESSION['id']);
			if($rep == null){
				echo json_encode(array('success' => 'ko'));
				return;
			}
			
			echo json_encode(array('success' => 'ok'));
			return;
		});
		
		// Lister les comptes rendus d'une action
		$app->get('/pcaet/actions/cractions/:action_id', function ($idAction) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			$crActions = FctCompteRendu::listerComptesRendusParAction($idAction);

			echo json_encode($crActions);
			return;
		});

			
		$app->post('/pcaet/actions/cractions/', function () use ($app)  {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
				
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			//récupération des paramètres
			$tableauParametres = json_decode($app->request->getBody(), true);
			
			$decriptionCrAction = $tableauParametres['description_cr'] ;
			$estActualite = $tableauParametres['est_actualite'] ;
			$idAction = $tableauParametres['action_id'] ;
			
			//transformation en booléen de la valeur estActualité
			if($estActualite=="true"){
				$estActualite = true;
			}
			else{
				$estActualite = false;
			}
				
			$crAction = FctCompteRendu::ajouterCompteRendu($idAction, $_SESSION['id'], $decriptionCrAction, $estActualite);
			
			if ($crAction == null){
				echo json_encode(array('success' => 'ko'));
				return;
			}
		
			echo json_encode(array('success' => 'ok'));
			return;
		});
		
		
		return $app;
	}
}
?>
