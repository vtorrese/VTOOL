<?php

require_once '../web/librairies/simple_html/simple_html_dom.php';
require '../web/librairies/OpenGraph/OpenGraph.php';

class Session {

  // Renvoie la liste des sessions d'un utilisateur
  public static function getSessions($id) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT IDsession, lib_session FROM `session_utilisateur` JOIN session ON IDsession = CE_IDsession WHERE `CE_IDutilisateur` = ?';
		$liste_session = $connexionInstance->requeter($sql, array($id));
		return $liste_session;
  }
  
  // Renvoie une session en particulier
  public static function getSession($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT * FROM `session` WHERE IDsession=?';
		$detail_session = $connexionInstance->requeter($sql, array($idsession));
		return $detail_session;
		}
		
	
		//Récupérer par ordre croissant les liens RSS utilisées dans toutes les références de la session
	public static function compterRatioLien($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = "SELECT `url_reference` as nom, COUNT(IDreference) as NB FROM `reference`  WHERE `CE_IDsession`=? AND validation_reference =1 GROUP BY SUBSTR(`url_reference`, 12,10) ORDER BY NB DESC";
		$compteratioLien = $connexionInstance->requeter($sql,array($idsession));
		return $compteratioLien;
	}
	
		//Comptabilise le nbre de mise à jour
	public static function comptemaj($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = "SELECT MAX(`date_historique`) as datemaj,COUNT(*) as NBmaj FROM `historique` WHERE `CE_IDsession`=?";
		$comptermaj = $connexionInstance->requeter($sql,array($idsession));
		return $comptermaj;
	}
	
	public static function suppressionSession($idsession) {
	
	//suppression dans la table occurence
	$connexionInstance = Connexion::getInstance();
	$sql = 'DELETE FROM `occurence` WHERE `CE_IDreference` IN (SELECT IDreference from reference WHERE CE_IDsession=?)';
    $suppressionoccurrence = $connexionInstance->requeter($sql, array($idsession));
	
	//suppression de la ligne session
	$connexionInstance = Connexion::getInstance();
	$sql = 'DELETE FROM `session` WHERE IDsession=?';
    $suppressionsession = $connexionInstance->requeter($sql, array($idsession));
	
	//suppression de la ligne session_utilisateur
	$connexionInstance = Connexion::getInstance();
	$sql = 'DELETE FROM `session_utilisateur` WHERE CE_IDsession=?';
    $suppressionsession = $connexionInstance->requeter($sql, array($idsession));

	}
	
	public static function ajoutSession($nvsession,$id) {
		
		//ajout dans la table session
		$connexionInstance = Connexion::getInstance();
		$sql = 'INSERT INTO `session`(lib_session) VALUES (?)';
		$ajoutsession = $connexionInstance->requeter($sql,array($nvsession));
		
		//Récupération du dernier IDsession entré
		$lastid = self::lastIDsession();
			
		//ajout dans la table session_utilisateur
		$connexionInstance = Connexion::getInstance();
		$sql = 'INSERT INTO `session_utilisateur`(CE_IDsession, CE_IDutilisateur) VALUES (?,?)';
		$ajoutsession = $connexionInstance->requeter($sql,array(intval($lastid[0]['LAST']),intval($id)));
	}
	
	public static function lastIDsession() {
	//récupération du dernier IDsession
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT MAX(IDsession) as LAST FROM session LIMIT 1';
		$lastsession = $connexionInstance->requeter($sql);
		return $lastsession;
	}
	
	//Récupérer la fréquence d'un mots-clés utilisées dans une référence
	public static function compterRatioMtclfByReference($idsession, $idreference) {
		$connexionInstance = Connexion::getInstance();
		$sql = "SELECT lib_mtclf, count(lib_mtclf) as freqmtclf FROM `reference` JOIN mtclf ON CE_IDmtclf = IDmtclf WHERE CE_IDsession =? AND validation_reference=1 AND IDmtclf= (SELECT CE_IDmtclf from reference WHERE IDreference=?) GROUP BY lib_mtclf ORDER BY freqmtclf DESC";
		$compteratioMtclfRef = $connexionInstance->requeter($sql, array($idsession, $idreference));
		return $compteratioMtclfRef;
	}
	
	//Récupérer par ordre croissant les mots-clés utilisées dans toutes les références de la session
	public static function compterRatioMtclf($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = "SELECT IDmtclf, lib_mtclf as nom, (SELECT count(lib_mtclf) as freqmtclf FROM `reference` LEFT JOIN mtclf ON CE_IDmtclf = IDmtclf WHERE CE_IDsession =? AND validation_reference =1 AND lib_mtclf = nom GROUP BY lib_mtclf) AS FREQUENCE
		FROM session_mtclf 
		JOIN mtclf ON CE_IDmtclf = IDmtclf 
		WHERE CE_IDsession =?
		GROUP BY lib_mtclf
		ORDER BY FREQUENCE DESC";
		$compteratioMtclf = $connexionInstance->requeter($sql,array($idsession,$idsession));
		return $compteratioMtclf;
	}
	
		///MISE A JOUR DE LA BASE ///////////////////////////
	public static function updateSession($idsession,$lien,$mtclf) {
		
		$cptfil=0;
		$cpt=0;
		foreach($lien as $ln) { //on parcourt chaque flux
			$rss = simplexml_load_file($ln['url_lien']);
			
			
			foreach ($rss->channel->item as $item){
				$titre = ((string)$item->title);
				$url = ($item ->link);
				$cpt++;
				$datetime = date_create($item->pubDate);
				$date = date_format($datetime, 'Y-m-d');
				$description = ((string)$item ->description);
				
				if(strlen($description)>399) {$description = "";} // pour éviter erreur 1406 sur data toolong
				
				//filtrage en fonction du mot clef
				foreach($mtclf as $mt) {
					$mot = $mt['lib_mtclf'];
					if ((preg_match("/\b$mot\b/i", $titre)) || (preg_match("/\b$mot\b/i", $description))) {
					$result_flux[$cptfil]['titre'] = $titre;
					$result_flux[$cptfil]['url'] = $url;
					$result_flux[$cptfil]['date'] = $date;
					$result_flux[$cptfil]['motmatch'] = $mot;
					$result_flux[$cptfil]['description'] = $description;
					$cptfil++;
				}
				}
				
				
			}
		}
	
		//Puis par les moteurs de recherche TODO --------------------------
		//$this->moteur = new Moteur();
		//$moteur = $this->moteur->getBySession($idsession);
		foreach($mtclf as $mt) {
			$html = new simple_html_dom();
			$theme = $mt;
			$mot = $mt['lib_mtclf'];
			$mot = str_replace(" ","+",$mot);
			$mapage= "https://www.google.fr/search?hl=fr&gl=fr&tbm=nws&authuser=0&q=$mot&num=20";
			$doc = @file_get_contents($mapage);
			$html->load_file($mapage);
			$tib = array();
			$tab = explode('<a href="/url?q=',$doc,-1);

			for ($element=1;$element<count($tab);$element++) {
					if(!empty(substr($tab[$element],0,stripos($tab[$element], '.html')))) {	
					$tib[$element] = (substr($tab[$element],0,stripos($tab[$element], '.html')));
					}
				}
			$middletab = array_unique($tib);
			$lastab = array();
			foreach($middletab as $element) {
				array_push($lastab,$element.".html");
				}
	
			foreach($lastab as $ele) {
			$url = $ele;
			if (filter_var($url, FILTER_VALIDATE_URL)) {
			$graph = OpenGraph::fetch($url);
			if($graph) {
				
			foreach($graph as $key => $value) {
					if("$key"=='title') {$titre = $value;}
					if("$key"=='url') {$urlx = $value;}
					if("$key"=='description') {$description = $value;}
			}
					$cptfil++;
					$result_flux[$cptfil]['titre'] = $titre;
					$result_flux[$cptfil]['url'] = $urlx;
					$result_flux[$cptfil]['date'] = date("Y-m-d h:i:sa");;
					$result_flux[$cptfil]['motmatch'] = str_replace("+", " ",$mot);
					if(strlen($description)>399) {$description = "";} // pour éviter erreur 1406 sur data toolong
					$result_flux[$cptfil]['description'] = $description;
			}
			}
			}
		}
		

		if(isset($result_flux)) {
			//S'il y a des résultats on créé une table temporaire
			self::temporaire($result_flux,$idsession);
		}	
	}
	
	//création d'une table temporaire contenant les résultats
	public static function temporaire($result_flux,$idsession) {
		$verif = self::verifier_temporaire();
		if($verif) {self::purgetemporaire($idsession);}
		/*$sql = "CREATE TABLE `vtool`.`temporaire` ( `IDtemporaire` INT NOT NULL AUTO_INCREMENT , `url_maj` VARCHAR(250) NOT NULL, `lib_maj` VARCHAR(150) NOT NULL , `date_maj` DATETIME NOT NULL , `mtclf_maj` VARCHAR(50) NOT NULL , `description_maj` VARCHAR(500) NOT NULL , PRIMARY KEY (`IDtemporaire`)) ENGINE = InnoDB;";
		$temporaire = $this->executerRequete($sql);*/
		self::ajoutTemporaire($result_flux,$idsession);
	}
	
	//Vérification que la table existe
	public static function verifier_temporaire() {
		$connexionInstance = Connexion::getInstance();
		$sql="SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'temporaire'";
		$veriftemporaire = $connexionInstance->requeter($sql);
		return $veriftemporaire;
	}
	
	//Suppression de la table temporaire
	public static function purgetemporaire($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = "DELETE FROM `temporaire` WHERE CE_IDsession =?";
		$temporaire = $connexionInstance->requeter($sql,array($idsession));
	}
	
	//insertion dans la table temporaire
	public static function ajoutTemporaire($result_flux,$idsession) {
		foreach($result_flux as $result) {
			// C'est ici qu'il faut faire un premier tri avec la base des références
			if(!empty($result['url'])) {
			$verif = self::verifier_doublon($result['url'],$idsession);
			foreach($verif as $v){
				if(intval($v['NBref'])==0) {
					$connexionInstance = Connexion::getInstance();
					$sql = 'INSERT INTO `temporaire`(lib_maj,url_maj, date_maj, mtclf_maj,description_maj,CE_IDsession) VALUES (?,?,?,?,?,?)';
				$ajouttemporaire = $connexionInstance->requeter($sql,array($result['titre'],$result['url'],$result['date'],$result['motmatch'],$result['description'],$idsession));
					}	
				}
			}
		}
	}
	
	//Pour vérifier les dblons d'url
	public static function verifier_doublon($url,$idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT COUNT(*) as NBref FROM reference WHERE url_reference =? AND CE_IDsession =?';
		$verification = $connexionInstance->requeter($sql,array($url,$idsession));
		return $verification;
	}
	
	//recupération de la table temporaire pour affichage
	public static function GetTemporaire($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT * FROM `temporaire` WHERE CE_IDsession =? ORDER BY date_maj DESC';
		$temporaire = $connexionInstance->requeter($sql,array($idsession));
		return $temporaire;
	}
	
	//comptabilisation des nouvelles entrées
	public static function GetNbTemporaire($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT count(*) as nb FROM `temporaire` WHERE CE_IDsession =? LIMIT 1';
		$nbtemporaire = $connexionInstance->requeter($sql,array($idsession));
		return $nbtemporaire;
	}
	
	//Suppression partielle de temporaire
	public static function selectemporaire($tabtemp) {
		$connexionInstance = Connexion::getInstance();
		$sql = "DELETE FROM temporaire WHERE IDtemporaire NOT IN ($tabtemp)";
		$selectemporaire = $connexionInstance->requeter($sql);
	}
	
	//Actualise l'historique
	public static function actualisehisto($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = "INSERT INTO `historique`(`date_historique`, `nblien`, `nbmtcf`, `nbref`, `CE_IDsession`) VALUES (NOW(),(SELECT COUNT(`IDsession_lien`) as nblien FROM `session_lien` WHERE `CE_IDsession`=?),(SELECT COUNT(IDsession_mtclf) as nbmtclf FROM `session_mtclf` WHERE `CE_IDsession`=?),(SELECT COUNT(`IDreference`) as nbref FROM `reference` WHERE `CE_IDsession`=? AND `validation_reference`=1),?)";
		$historique = $connexionInstance->requeter($sql, array($idsession,$idsession,$idsession,$idsession));
	}
	
}

?>