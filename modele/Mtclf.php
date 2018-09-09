<?php

class Mtclf {

	  // Renvoie les mot-clefs par ID de session
	public static function getBySession($idsession) {
	$connexionInstance = Connexion::getInstance();
    $sql = 'SELECT `IDmtclf`, `lib_mtclf` FROM `mtclf` INNER JOIN session_mtclf ON IDmtclf = CE_IDmtclf WHERE `CE_IDsession`=? ORDER BY lib_mtclf';
	$liste_mtclf = $connexionInstance->requeter($sql, array($idsession));
		return $liste_mtclf;
    }
	
	//compter le nombre de mtclf par session
	public static function compterMtclf($idsession) {
	$connexionInstance = Connexion::getInstance();
	$sql = "SELECT COUNT(`CE_IDsession`) as NBmtclf FROM `session_mtclf` WHERE `CE_IDsession` =?";
	$NbMtclf_session = $connexionInstance->requeter($sql,array($idsession));
	return $NbMtclf_session;
	}
	
	//pour rajouter un mot-clé à une session
	public static function ajoutMtclf($libmtclf, $idsession) {
		//On controle que l'association session/mot-clés n'existe pas déjà
			$idmt = self::getID($libmtclf); // On récupère l'ID du mot clef
		
			if(!empty($idmt)) {$control = self::controlDbmtclf($idsession,intval($idmt[0][0]));}  // recherche de l'association IDsession IDmtclf dans session_mtclf
		
			if((!empty($idmt)) && (intval($control[0][0])>0)) { // s'il n'est pas nul (donc doublon du mot clef)
				return;
			}
			else
			{
				if(!empty($idmt)) { // si le mot-clef existe mais pas dans la session, on actualise session_mot-clef
					$connexionInstance = Connexion::getInstance();
					$sql = 'INSERT INTO `session_mtclf`(CE_IDsession,CE_IDmtclf) VALUES (?,?)';
					$ajoutmtclf = $connexionInstance->requeter($sql,array(intval($idsession),intval($idmt[0][0])));
				}
				else
				{
					$connexionInstance = Connexion::getInstance();
					$sql = 'INSERT INTO `mtclf`(lib_mtclf) VALUES (?)'; // sinon création dans les deux tabkles session_mtclf et mtclf
					$ajoutmtclf = $connexionInstance->requeter($sql,array($libmtclf));
					
					
					$idm_tclf = self::lastIDmtclf();
	
					$sql = 'INSERT INTO `session_mtclf`(CE_IDsession,CE_IDmtclf) VALUES (?,?)';
					$ajoutmtclf = $connexionInstance->requeter($sql,array(intval($idsession),intval($idm_tclf[0][0])));
				}
			}
	}
	
	// Renvoie les mot-clefs par ID de mtclf
	public static function getID($libmtclf) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT `IDmtclf`FROM `mtclf` WHERE `lib_mtclf` =?';
		$idmtclf = $connexionInstance->requeter($sql, array($libmtclf));
		return $idmtclf;  // Accès aux lignes
      //throw new Exception("Aucun mot clef n'est affecté à cette thématique (id:'$idsession')");
    }
	
	//controle des doublons dans la table session_mtclf
	public static function controlDbmtclf($idsession,$idmt) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT COUNT(*) as nb FROM session_mtclf WHERE CE_IDsession =? AND CE_IDmtclf =?';
		$Nbsession_mtclf = $connexionInstance->requeter($sql,array($idsession,$idmt));
		return $Nbsession_mtclf;
	}
	
	//récupération du dernier IDmtclf
	public static function lastIDmtclf() {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT MAX(IDmtclf) FROM mtclf LIMIT 1';
		$lastmtclf = $connexionInstance->requeter($sql);
		return $lastmtclf;
	}
	
	//Suppression d'un mot clé d'une session
	public static function suppressionMtclf($idmtclf,$idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'DELETE FROM `session_mtclf` WHERE CE_IDmtclf=? AND CE_IDsession=?';
		$suppressionmtclf = $connexionInstance->requeter($sql, array($idmtclf,$idsession));
	}
}

?>