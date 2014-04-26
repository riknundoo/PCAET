<?php
use RedBean_Facade as R;

class Document {
	
	public static $nameTable = "document";
	
	public static function creerDocument($nomDocument) {
		$document = R::dispense(Document::$nameTable);
		$document->nom_document = $nomDocument;
		R::store($document);
		return $document;
	}
	
	public static function ajoutPath($document,$path) {
		$document->path = $path;
		R::store($document);
		return $document;
	}
	
	public static function listerDocuments() {
		$documents = R::findall(Document::$nameTable, "ORDER BY nom_document ASC");
		return $documents;
	}
	
	public static function recupererDocument($idDocument) {
		$document = R::findOne(Document::$nameTable, " id = ? ", array($idDocument));
		if($document == null)
			return null;
		return $document;
	}
	
	public static function recupererDocumentByNom($nameDocument) {
		$document = R::findOne(Document::$nameTable, " nom_document = ? ", array($nameDocument));
		if($document == null)
			return null;
		return $document;
	}
	
	public static function chercherDocuments($nomDocument) {
		$nom = "%".$nomDocument."%";
		$document = R::find(Document::$nameTable, " nom_document LIKE ? ", array($nom));
		if($document == null)
			return null;
		return $document;
	}
	
	public static function renommerDocument($idDocument, $nouveauNom){
		$document = Document::recupererDocument($idDocument);
		if($document == null)
			return null;
		$document->nom_document = $nouveauNom;
		R::store($document);
		return $document; 
	}
	
	public static function supprimerDocument($idDocument){
		$document = Document::recupererDocument($idDocument);
		if($document == null)
			return null;
		$deletefile = $document->path;
		if(file_exists($deletefile)){
			unlink($deletefile);
		}
		R::trash($document);
		return "ok";
	}
	
	public static function getAllDocument(){
		return R::findAll(Document::$nameTable);
	}
}
?>
