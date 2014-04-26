<?php 

use RedBean_Facade as R;

class ApiAction {
	
	public static function addHttpRequest($app) {

		$app->put('/pcaet/actions/', function () use($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);	
				return;
			}
			//Recuperation de l'ensemble des informations de l'action
			$tableauParametres = json_decode($app->request->getBody(), true);
			//Verification des donnees modifiees pour modifier les champs concernes
			$tabParamVerifies = FctAction::verifModifAction($tableauParametres);
			$idUtilisateur = $_SESSION['id'];
			//Modification effective des champs concernes
			$action = FctAction::modifierAction($idUtilisateur,$tabParamVerifies);
			if ($action == null){
				echo json_encode(array('success' => 'ko'));
				return;
			}
			echo json_encode(array('success' => 'ok'));
			return;
		});

		$app->get('/pcaet/actions',function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$tableau = FctAction::listerActions();
			echo json_encode($tableau);
			return;
		});

		$app->get('/pcaet/actions/:id',function($id) use($app) {
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$tableau = FctAction::listerActionsResponsable($id);
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			echo json_encode($tableau);
			return;
		});

		$app->get('/pcaet/actions/phases/:idaction', function ($idAction) use ($app){
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$phases = FctAction::listerPhasesAction($idAction);
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			echo json_encode($phases);
			return;
		});
		
		$app->get('/pcaet/actions/indicateurs/:idaction', function ($idAction) use ($app){
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$indicateurs = FctIndicateur::ListerIndicateurAction($idAction);
			echo json_encode($indicateurs);
			return;
		});
		
		$app->get('/pcaet/actions/objectifs/:idaction', function ($idAction) use ($app){
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$objectifs = FctObjectifEnjeu::listerObjectifsEnjeuxIdAction($idAction);
			echo json_encode($objectifs);
			return;
		});
			
		$app->get('/pcaet/action/:id',function ($id) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$tableau = FctAction::infoActionComplete($id);
			$tableau = FctAction::ajoutAttributEdit($_SESSION['id'],$id,$tableau);
			echo json_encode($tableau);
			return;
		});
		
		$app->get('/pcaet/actions/etats', function () use ($app){
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$etats = FctAction::listerEtatActions();
			echo json_encode($etats);
			return;
		});
		
		$app->get('/pcaet/actions/budget/:id', function ($id) use ($app){
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			$action = Action::getActionById($id);
			$actionTab=array();
			$actionTab['total']=$action->budget_total;
			$actionTab['consomme']=$action->budget_consomme;
			echo json_encode($actionTab);
			return;
		});
		
		$app->put('/pcaet/actions/budget/:id', function ($id) use ($app){
			
			$injson = json_decode($app->request->getBody());
			
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
// 			$utilisateur = Utilisateur::recupererUtilisateur($_SESSION['id']);

			
			$action = FctAction::modifierBudget($id, $injson->total, $injson->consomme, $_SESSION['login']);
			if ($action != null){
				echo json_encode(array('success' => 'ok'));
				return;
			}
			echo json_encode(array('success' => 'ko'));
			return;
		});
		
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
