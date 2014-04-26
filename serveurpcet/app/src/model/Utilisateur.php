<?php
use RedBean_Facade as R;

class Utilisateur {
	
	public static $nameTable = "utilisateur";
	
	public static function creerUtilisateur($login,$nom,$prenom,$passwordMd5,$role,$organisation,$email,$telInterne,$telStandard){
// 		if($role != "chef de projet" && $role!="responsable action" && $role!="visiteur"){
// 			return null;
// 		}
		$utilisateur = R::dispense(Utilisateur::$nameTable);
		$utilisateur->login_utilisateur= $login;
		$utilisateur->prenom_utilisateur = $prenom;
		$utilisateur->nom_utilisateur = $nom;
		$utilisateur->mot_de_passe=$passwordMd5;
		$utilisateur->role_utilisateur=$role;
		$utilisateur->organisation = $organisation;
		$utilisateur->email = $email;
		$utilisateur->tel_interne=$telInterne;
		$utilisateur->tel_standard=$telStandard;
		$id = R::store($utilisateur);
		return $utilisateur;
	}
	
	public static function authentification($login,$passwordMd5){
		$utilisateur = R::findOne(Utilisateur::$nameTable, ' login_utilisateur = ?  ', array($login));
		if($utilisateur!=null && $utilisateur->mot_de_passe === $passwordMd5){
			return $utilisateur;
		}
		return null;
	}
	
	public static function getAllUtilisateur(){
		return R::findAll(Utilisateur::$nameTable);
	}
	
	public static function getUtilisateur($login){
		return R::findOne(Utilisateur::$nameTable, ' login_utilisateur = ?  ', array($login));
	}
	
	public static function recupererUtilisateur($idUtilisateur){
		$utilisateur = R::findOne(Utilisateur::$nameTable, " id = ? ", array($idUtilisateur));
		return $utilisateur;
	}
	
	public static function estChefDeProjet($login){
		$utilisateur=R::findOne(Utilisateur::$nameTable, ' login_utilisateur = ?  ', array($login));
		if($utilisateur->role_utilisateur==1){
			return true;
		}
		return false;
	}
	
	public static function estChefDeProjetParUtilisateur($utilisateur){
		if($utilisateur->role_utilisateur==1){
			return true;
		}
		return false;
	}
	
	public static function estChefDeProjetOuResponsable($login){
		$utilisateur=R::findOne(Utilisateur::$nameTable, ' login_utilisateur = ?  ', array($login));
		if($utilisateur->role_utilisateur==1 || $utilisateur->role_utilisateur==2){
			return true;
		}
		return false;
	}
	
	public static function addCrAction($utilisateur,$crAction){
		$utilisateur->ownCraction[]=$crAction;
		R::store($utilisateur);
		return;
	}
	
	public static function deleteUtilisateur($utilisateur){
		R::trash($utilisateur);
		return;
	}
	
}
?>
