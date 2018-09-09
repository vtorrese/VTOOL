<?php

class Lien {

	//Renvoie tous les flux rss
	public static function getAll() {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT `IDlien`, `url_lien` FROM `lien`';
		$liste_All_lien = $connexionInstance->requeter($sql);
		return $liste_All_lien;
		}


	  // Renvoie les liens par ID de session
	public static function getBySession($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT `IDlien`, `url_lien` FROM `lien` INNER JOIN session_lien ON IDlien = CE_IDlien WHERE `CE_IDsession`=? ORDER BY url_lien';
		$liste_lien = $connexionInstance->requeter($sql, array($idsession));
		return $liste_lien;
		}
	
	//Compte le nb de lien par ID de session
	public static function compterLien($idsession) {
	$connexionInstance = Connexion::getInstance();
	$sql = "SELECT COUNT(`CE_IDsession`) as NBlien FROM `session_lien` WHERE `CE_IDsession` =?";
	$Nblien_session = $connexionInstance->requeter($sql,array($idsession));
	return $Nblien_session;
	}
	
	//pour rajouter un lien RSS à une session
	public static function ajoutLien($liblien, $idsession) {
		//On controle que l'association session/lien n'existe pas déjà
		$idln = self::getID($liblien); // On récupère l'ID du mot clef
		
		if(!empty($idln)) {$control = self::controlDblien($idsession,intval($idln[0][0]));}  // recherche de l'association IDsession IDlien dans session_lien
		
		if((!empty($idln)) && (intval($control[0][0])>0)) { // s'il n'est pas nul (donc doublon du mot clef)
				return;
			}
			else
			{
				if(!empty($idln)) { // si le lien existe mais pas dans la session, on actualise session_lien
					$connexionInstance = Connexion::getInstance();
					$sql = 'INSERT INTO `session_lien`(CE_IDsession,CE_IDlien) VALUES (?,?)';
					$ajoutlien = $connexionInstance->requeter($sql,array(intval($idsession),intval($idln[0][0])));
				}
				else
				{
					$connexionInstance = Connexion::getInstance();
					$sql = 'INSERT INTO `lien`(url_lien) VALUES (?)'; // sinon création dans les deux tabkles session_lien et lien
					$ajoutlien = $connexionInstance->requeter($sql,array($liblien));
					
					
					$idlnx = self::lastIDLien();
	
					$sql = 'INSERT INTO `session_lien`(CE_IDsession,CE_IDlien) VALUES (?,?)';
					$ajoutlien = $connexionInstance->requeter($sql,array(intval($idsession),intval($idlnx[0][0])));
				}
				
			}
	}
	
	//Supprimer un lien
	public static function suppressionLien($idlien,$idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'DELETE FROM `session_lien` WHERE CE_IDlien=? AND CE_IDsession =?';
		$suppressionlien = $connexionInstance->requeter($sql, array($idlien,$idsession));
	}
	
	
	// Renvoie les liens par ID de lien
	public static function getID($urln) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT `IDlien`FROM `lien` WHERE `url_lien` =?';
		$idlien = $connexionInstance->requeter($sql, array($urln));
		return $idlien;
    }
	
	//récupération du dernier IDlien
	public static function  lastIDlien() {
			$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT MAX(IDlien) FROM lien LIMIT 1';
		$lastlien = $connexionInstance->requeter($sql);
		return $lastlien;
	}
	
	//controle des doublons dans la table session_lien
	public static function controlDblien($idsession,$idln) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT COUNT(*) as nb FROM session_lien WHERE CE_IDsession =? AND CE_IDlien =?';
		$Nbsession_lien = $connexionInstance->requeter($sql,array($idsession,$idln));
		return $Nbsession_lien;
	}
}

?>