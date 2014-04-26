<?php

use RedBean_Facade as R;

class ApiDocument {
	public static function addHttpRequest($app) {
		
		$app->post('/document/ajout', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			$document = Document::creerDocument($_FILES['file']['name']);
			$path = 'document/';
			$uploadfile = $path . $document->id;
			
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
			}
			Document::ajoutPath($document,$uploadfile);
			
			if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadfile)){
				Document::ajoutPath($document,$uploadfile);
				$rep = array('success' => 'ok','file'=>$uploadfile);
				echo json_encode($rep);
				return;
			
			}
			else{
				Document::supprimerDocument($document->id);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
		});
		
			$app->delete('/document/delete/:id', function ($id) use ($app) {
				$app->response->header('Content-Type', 'application/json;charset=utf-8');
				if(!isset($_SESSION['login']) || $_SESSION['login']==''){
					$app->response->status(401);
					$rep = array('success' => 'ko');
					echo json_encode($rep);
					return;
				}
				json_encode(array("success"=>Document::supprimerDocument($id)));
			});
			
		
		$app->get('/document/list', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}

			$docs=array();
			$documents = Document::getAllDocument();
			foreach($documents as $document){
				$doc=array();
				$doc['id']=$document->id;
				$doc['nom_document']=$document->nom_document;
				$docs[]=$doc;
			}
			echo json_encode($docs);
		});
		
		$app->get('/document/actionnonlie/:id', function ($id) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$rep = FctDocument::getAllActionNonLie($_SESSION['login'], $id);
			echo json_encode($rep);
			return;
		});
		
		$app->get('/document/actionlie/:id', function ($id) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$rep = FctDocument::getAllActionLie($_SESSION['login'], $id);
			echo json_encode($rep);
			return;
		});
		
		$app->put('/document/lier/:idAction/:idDocument', function ($idAction,$idDocument) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			// TODO - vérification droit (chef action et responsable)
			$rep = FctDocument::lieActionDocument($_SESSION['login'], $idAction,$idDocument);
			echo json_encode($rep);
			return;
		});
		
		$app->put('/document/delier/:idAction/:idDocument', function ($idAction,$idDocument) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			// TODO - vérification droit (chef action et responsable)
			$rep = FctDocument::delieActionDocument($_SESSION['login'], $idAction,$idDocument);
			echo json_encode($rep);
			return;
		});
		
		$app->get('/document/:id', function ($id) use ($app) {
			
			$doc = Document::recupererDocument($id);
			
			
			if($doc!=null){
				$file = $doc->path;

				if(file_exists($file)){
										
					$res = $app->response();
					$res['Content-Description'] = 'File Transfer';
					$res['Content-Type'] = 'application/octet-stream';
					$res['Content-Disposition'] ='attachment; filename=' . $doc->nom_document;
					$res['Content-Transfer-Encoding'] = 'binary';
					$res['Expires'] = '0';
					$res['Cache-Control'] = 'must-revalidate';
					$res['Pragma'] = 'public';
					$res['Content-Length'] = filesize($file);
					readfile($file);
				}
			}
			return;
		});
		
		
		return $app;		
	}
	
	
	
	
}
?>
