<?php

require_once '../web/librairies/simple_html/simple_html_dom.php';

class Reference {
	
	//Pour comptabiliser le nb de références actives par session
	public static function compterReference($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT COUNT(IDreference) as NBref FROM `reference` WHERE CE_IDsession=? AND validation_reference = 1';
		$actives = $connexionInstance->requeter($sql, array($idsession));
		return $actives;
		}
		
	//Pour comptabiliser le nombre total de références d'une session actives ou non
	public static function compterTouteReference($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT COUNT(IDreference) as NBreftot FROM `reference` WHERE CE_IDsession=?';
		$toutes = $connexionInstance->requeter($sql, array($idsession));
		return $toutes;
		}
		
	//Pour dater une session
	public static function daterReference($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT MIN(date_reference) as creation, MAX(date_reference) as fin FROM `reference` WHERE `CE_IDsession`=? AND validation_reference=1';
		$dater_session = $connexionInstance->requeter($sql, array($idsession));
		return $dater_session;
		}
		
	//Pour classer les occurrences par fréquence pour une session donnée
	public static function classerOccurenceBySession($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT lib_occurrence, count(*) as frequence FROM `occurence` WHERE CE_IDreference IN (SELECT IDreference from reference WHERE CE_IDsession=? AND validation_reference=1) GROUP BY lib_occurrence ORDER BY frequence DESC';
		$classement_occurrence = $connexionInstance->requeter($sql, array($idsession));
		return $classement_occurrence;
		}
		
	//Pour n'afficher que les références inactives
	public static function afficheReferenceDesactive($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT IDreference, date_reference, lib_reference, lib_mtclf FROM `reference` JOIN mtclf ON CE_IDmtclf = IDmtclf WHERE CE_IDsession=? AND validation_reference = 0 ORDER BY date_reference DESC';
		$affiche_desact = $connexionInstance->requeter($sql, array($idsession));
		return $affiche_desact;
		}
		
	//Pour récupérer les occurences pour une référence donnée avec leur fréquence relative (vis-à-vis de la fréquence de toute la session)
	public static function recupererOccurenceClasseByReference($idsession,$idreference) {
		$connexionInstance = Connexion::getInstance();
		$sql="SELECT IDoccurence, lib_occurrence as nom, (SELECT count(lib_occurrence) FROM `occurence` WHERE CE_IDreference IN (SELECT IDreference from reference WHERE CE_IDsession=? AND validation_reference=1) AND lib_occurrence=NOM) as FREQUENCE, (SELECT COUNT(distinct(lib_occurrence)) AS frequence FROM `occurence` WHERE CE_IDreference IN(SELECT IDreference FROM reference WHERE CE_IDsession = ? AND validation_reference=1)) as total FROM `occurence` WHERE CE_IDreference =? GROUP BY IDoccurence, lib_occurrence ORDER BY FREQUENCE DESC, nom";
		$recupere_occurrence_classe = $connexionInstance->requeter($sql, array($idsession,$idsession,$idreference));
		return $recupere_occurrence_classe;
		}	
	
	//Pour afficher les références sans occurrences
	public static function afficheReferenceSsOcc($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT IDreference, date_reference, lib_reference, lib_mtclf FROM reference JOIN mtclf ON CE_IDmtclf = IDmtclf WHERE IDreference NOT IN (SELECT DISTINCT(CE_IDreference) from occurence JOIN reference ON CE_IDreference = IDreference) AND CE_IDsession = ? AND validation_reference = 1';
		$referencessocc = $connexionInstance->requeter($sql, array($idsession));
		return $referencessocc;
		}
		
	//Pour afficher toute les références
	public static function afficheAllReference($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT IDreference, date_reference, lib_reference, lib_mtclf FROM `reference` JOIN mtclf ON CE_IDmtclf = IDmtclf WHERE CE_IDsession=? AND validation_reference = 1 ORDER BY date_reference DESC';
		$allreference = $connexionInstance->requeter($sql, array($idsession));
		return $allreference;
		}
		
	//Pour afficher le détail d'une référence (écran consultation)
	public static function afficheDetailReference($idreference) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT IDreference, date_reference, lib_reference, lib_mtclf, url_reference, description_reference, validation_reference FROM `reference` JOIN mtclf ON CE_IDmtclf = IDmtclf WHERE IDreference=? LIMIT 1';
		$detailreference = $connexionInstance->requeter($sql, array($idreference));
		return $detailreference;
		}
		
	//Pour modifier le mot clé d'une référence
	public static function changemtclf($idreference,$idmtclf) {
		$connexionInstance = Connexion::getInstance();
		$sql = "UPDATE `reference` SET `CE_IDmtclf`=? WHERE IDreference=?";
		$modifiereference = $connexionInstance->requeter($sql, array($idmtclf,$idreference));
		}
	
	//Pour modifier titre d'une référence
	public static function changetitre($idreference,$nvtitre) {
		$connexionInstance = Connexion::getInstance();
		$sql = "UPDATE `reference` SET `lib_reference`=? WHERE IDreference=?";
		$modifiereference = $connexionInstance->requeter($sql, array($nvtitre,$idreference));
		}
	
	//Pour modifier description d'une référence
	public static function changedescription($idreference,$nvdesc) {
		$connexionInstance = Connexion::getInstance();
		$sql = "UPDATE `reference` SET `description_reference`=? WHERE IDreference=?";
		$modifiereference = $connexionInstance->requeter($sql, array($nvdesc,$idreference));
		}
	
	//Pour ajouter une occurence
	public static function ajoutoccurence($idreference,$cbocc) {
		$control = self::controleRefocc($idreference,$cbocc);
		if(intval($control[0]['controle'])<1) {
			$connexionInstance = Connexion::getInstance();
			$sql = 'INSERT INTO `occurence`(`lib_occurrence`, `CE_IDreference`) VALUES (?,?)';
			$ajoutoccurrence = $connexionInstance->requeter($sql, array($cbocc,$idreference));
			}
		}
		
	//Pour controler un couple occurence/reference
	public static function controleRefocc($idreference,$nvocc) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT count(*) as controle FROM `occurence` WHERE CE_IDreference =? AND lib_occurrence=?';
		$controle_occurrence = $connexionInstance->requeter($sql, array($idreference,$nvocc));
		return $controle_occurrence;
		}
		
	//Pour supprimer une occurence
	public static function supprimeOccurence($idoccurence) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'DELETE FROM `occurence` WHERE `IDoccurence` =?';
		$suppressionoccurrence = $connexionInstance->requeter($sql, array($idoccurence));
		}
		
	//Pour supprimer une référence
	public static function suppressionreference($idreference) {
		
		//suppression dans la table occurence
		$connexionInstance = Connexion::getInstance();
		$sql = 'DELETE FROM `occurence` WHERE `CE_IDreference` =?';
		$suppressionoccurrence = $connexionInstance->requeter($sql, array($idreference));
		
		//suppression de la ligne reference
		$connexionInstance = Connexion::getInstance();
		$sql = 'DELETE FROM `reference` WHERE IDreference=?';
		$suppressionref = $connexionInstance->requeter($sql, array($idreference));
		}
		
	//Pour activer/désactiver une référence
	public static function activationreference($idreference) {
		$connexionInstance = Connexion::getInstance();
		$sql = "UPDATE reference SET validation_reference = CASE WHEN validation_reference=1 THEN 0 WHEN validation_reference=0 THEN 1 ELSE 0 END WHERE IDreference=?";
		$activeref = $connexionInstance->requeter($sql, array($idreference));
		}
		
	//Pour récupérer les occurences pour une référence donnée
	public static function recupererOccurenceByReference($idreference) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT IDoccurence, lib_occurrence FROM `occurence` WHERE CE_IDreference =? ORDER BY lib_occurrence';
		$recupere_occurrence = $connexionInstance->requeter($sql, array($idreference));
		return $recupere_occurrence;
		}
		
	//Pour afficher une sélection de références après recherche
	public static function afficheRechercheReference($idsession,$mot,$newdebut,$newfin,$parents,$occs) {
		
		//titre ou description
		if($mot){$mot = str_replace("'","''",$mot);$req_comp = "AND lib_reference LIKE '%".$mot."%' OR description_reference LIKE '%".$mot."%'";} else {$req_comp = "";}
		
		//date
		if($newdebut!=null) {$req_comp = $req_comp." AND date_reference >= '".$newdebut."'";}
		if($newfin!=null) {$req_comp = $req_comp." AND date_reference <= '".$newfin."'";}
		
		//mots-clefs
		if($parents!=null) {$req_comp = $req_comp." AND CE_IDmtclf IN ".$parents;}
		
		//occurrences
		if($occs!=null) {$req_comp = $req_comp." AND lib_occurrence IN ".$occs;}
		
		$connexionInstance = Connexion::getInstance();
		$sql = "SELECT IDreference, date_reference, lib_reference, lib_mtclf FROM `reference` JOIN mtclf ON CE_IDmtclf = IDmtclf JOIN occurence ON IDreference = CE_IDreference WHERE CE_IDsession=? AND validation_reference = 1 ".$req_comp." GROUP BY IDreference, date_reference, lib_reference, lib_mtclf ORDER BY date_reference DESC";
		
		 $allreference = $connexionInstance->requeter($sql, array($idsession));
		
		return $allreference;
		}
		
	//recupération de la ligne temporaire
	public static function getTemporaire($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT * FROM temporaire WHERE CE_IDsession =? LIMIT 1';
		$reference = $connexionInstance->requeter($sql, array($idsession));
		return $reference;
		}
		
	//Moteur des occurences //////////////////////////////////////////////////////
	public static function getOccurrence($reference) {
		//On récupère l'url de la nouvelle référence
		foreach($reference as $ref) {
			$url = $ref['url_maj'];
		}
		
		//analyse de la page avec simple_html_dom parser
		if (filter_var($url, FILTER_VALIDATE_URL)) {
		$html = file_get_html($url);
		$tableau = array();
		$motrouve = array();
		
		//On découpe la page html à partir de la balise "<p>"
		
		$links = $html->find('p');
		foreach($links as $e) {
		// suite du traitement : on crée un premier tableau donnant le nombre de mot par paragraphe (min : 20 mots)
		$tableau = str_word_count($e, 1, 'àáãç1234567890éèùôû-');
			if (count($tableau)>20) {
	
		//on crée un deuxième tableau évacuant les mots de moins de 4 lettres ou plus de 26 lettres
				foreach ($tableau as $item) {
					if ((strlen($item)>4) && (strlen($item)<27)) {
					array_push($motrouve,$item);
					}
				}
	
			}
		
		}
		}
		//On crée un troisième tableau permettant de mesurer les occurences de chaque mot trouve
		$newtab = array_count_values($motrouve);
		
		//on trie ce tableau par nombre max d'occurences
		arsort($newtab);
		$lastab = array();
		foreach($newtab as $key => $tt) {
	
		//On recrée un quatrieme tableau ne conservant que les occurences supérieurs à 3
			if ($tt>3) {
				array_push($lastab,str_replace("'","",$key));
			}
		}
		
		//On finit par enlever les mots usuels ou suspects et créer le dernier tableau
		$usuel = ['agrave','required','value','input','label','submit','eacute','target','blank','article','drivers','onclick','title','zdnet','class','trackEvent','textarea','comment-post-text',	'your-email','celle','cette','selon','ainsi','egrave','maxlength','subscribe','htpps','techtarget','Techtarget'];
		$finaltab = array_diff($lastab,$usuel);
	
		return $finaltab;
		}
		
	//Insérer une nouvelle référence
	public static function insereReference($IDtemporaire, $idsession, $tabocc) {
		
	
		//On récupère le lib mtclf dans la table temporaire
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT mtclf_maj FROM `temporaire` WHERE IDtemporaire=?';
		$libmtclf = $connexionInstance->requeter($sql, array($IDtemporaire));
		//$libmtclf->fetch();
		foreach($libmtclf as $mtclf) {$lib = $mtclf['mtclf_maj'];}
		
		//On récupère l'ID_mtclf
		$IDmtclf = Mtclf::getID($lib);
		foreach($IDmtclf as $idmtclf) {$idmt = $idmtclf;}

		//On commence par récupérer la ligne temporaire et l'insérer dans référence
		$connexionInstance = Connexion::getInstance();
		$sql = "INSERT INTO reference (date_reference,lib_reference,description_reference,url_reference,CE_IDsession,CE_IDmtclf,validation_reference)
				SELECT date_maj,lib_maj,description_maj,url_maj,?,?,1
				FROM temporaire
				WHERE IDtemporaire =?";
		$reference = $connexionInstance->requeter($sql, array($idsession,intval($idmt[0]),$IDtemporaire));
		
		//Il faut ensuite mettre à jour la table occurences 
			if($tabocc!=null) { //s'il y a une ou plusieurs occurences à  insérer
			//On récupère le dernier ID entré dans la table reference
			$IDreference = self::lastIDreference();
			foreach($tabocc as $libelle) {
			$new_occ = ucwords($libelle);
			$connexionInstance = Connexion::getInstance();
			$sql = "INSERT INTO occurence (lib_occurrence,CE_IDreference) VALUES (?,?)";
			$occurence = $connexionInstance->requeter($sql, array($new_occ,intval($IDreference[0][0])));
			}
			}
		//Il faut ensuite supprimer la référence dans la table temporaire
		self::supprimeTemporaire($IDtemporaire);
		}
		
	//récupération du dernier IDreference
	public static function lastIDreference() {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT MAX(IDreference) FROM reference LIMIT 1';
		$lastreference = $connexionInstance->requeter($sql);
		return $lastreference;
		} 

	//suppression de la ligne temporaire
	public static function supprimeTemporaire($IDtemporaire) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'DELETE FROM `temporaire` WHERE IDtemporaire=?';
		$suppressiontemporaire = $connexionInstance->requeter($sql, array($IDtemporaire));
		}	

	//Affichage de l'analyse de la veille
	public static function construireanalyse($idsession) {
		$data = [
		'dater' => self::daterReference($idsession), 
		'compter' => self::compterReference($idsession), 
		'toutcompter' => self::compterTouteReference($idsession),
		'cptermaj' => self::comptemaj($idsession),
		'recupmajgp' => self::recup_historique_groupe($idsession),
		'nbmtclf' => Mtclf::compterMtclf($idsession),
		'nblien' => Lien::compterLien($idsession),
		'txmtclf' => Session::compterRatioMtclf($idsession),
		'txlien' => Session::compterRatioLien($idsession)
		];
		return $data;
		}	
		
	//Comptabilise le nbre de mise à jour par session
	public static function comptemaj($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = "SELECT MAX(`date_historique`) as datemaj,COUNT(*) as NBmaj FROM `historique` WHERE `CE_IDsession`=?";
		$comptermaj = $connexionInstance->requeter($sql,array($idsession));
		return $comptermaj;
		}
		
	//Récupère le nbre de reference de l'historique par session et par jour
	public static function recup_historique_groupe($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = "SELECT YEAR(date_historique) as annee, Month(date_historique) as mois, day(date_historique) as jour, max(nbref) as nbref, max( nblien) as nblien, max(nbmtcf) as nbmtclf FROM `historique` WHERE `CE_IDsession`=? GROUP BY annee, mois, jour ORDER BY annee DESC, mois DESC, jour DESC LIMIT 30";
		$recupmajgp = $connexionInstance->requeter($sql,array($idsession));
		return $recupmajgp;
		}
		
	//Affichage de la table des occurrences
	public static function construiretable_occurence($idsession) {
		
		$data = [
		'recup_toute_occ' => self::horizontale_classer($idsession),
		'recupere_occ' => self::classerOccurenceBySession($idsession)
		];
		return $data;
		}
		
	//classement horizontal parent classé
	public static function horizontale_classer($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = "SELECT IDreference, lib_mtclf as parent,  (SELECT lib_occurrence as nom FROM `occurence` WHERE CE_IDreference =IDreference GROUP BY IDoccurence, lib_occurrence ORDER BY (SELECT count(lib_occurrence) FROM `occurence` WHERE CE_IDreference IN (SELECT IDreference from reference WHERE CE_IDsession=? AND validation_reference=1) AND lib_occurrence=NOM) DESC, nom LIMIT 1) as enfant, (SELECT lib_occurrence as nom FROM `occurence` WHERE CE_IDreference =IDreference GROUP BY IDoccurence, lib_occurrence ORDER BY (SELECT count(lib_occurrence) FROM `occurence` WHERE CE_IDreference IN (SELECT IDreference from reference WHERE CE_IDsession=? AND validation_reference=1) AND lib_occurrence=NOM) DESC, nom LIMIT 1,1) as petitenfant FROM `reference` JOIN mtclf ON `CE_IDmtclf` = IDmtclf WHERE `CE_IDsession`=? AND `validation_reference`=1 ORDER BY parent,enfant,petitenfant";
		$recupere_horiz = $connexionInstance->requeter($sql, array($idsession,$idsession,$idsession));
		return $recupere_horiz;
		}
	
	//classement horizontal parent
	public static function horizontale_parent($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql = "SELECT parent, count(parent) as cptparent FROM (SELECT IDreference, lib_mtclf as parent, (SELECT lib_occurrence as nom FROM `occurence` WHERE CE_IDreference =IDreference GROUP BY IDoccurence, lib_occurrence ORDER BY (SELECT count(lib_occurrence) FROM `occurence` WHERE CE_IDreference IN (SELECT IDreference from reference WHERE CE_IDsession=? AND validation_reference=1) AND lib_occurrence=NOM) DESC, nom LIMIT 1) as enfant, (SELECT lib_occurrence as nom FROM `occurence` WHERE CE_IDreference =IDreference GROUP BY IDoccurence, lib_occurrence ORDER BY (SELECT count(lib_occurrence) FROM `occurence` WHERE CE_IDreference IN (SELECT IDreference from reference WHERE CE_IDsession=? AND validation_reference=1) AND lib_occurrence=NOM) DESC, nom LIMIT 1,1) as petitenfant FROM `reference` JOIN mtclf ON `CE_IDmtclf` = IDmtclf WHERE `CE_IDsession`=? AND `validation_reference`=1 ORDER BY parent) as horizontal GROUP BY parent ORDER BY count(parent) DESC";
		$recupere_parent = $connexionInstance->requeter($sql, array($idsession,$idsession,$idsession));
		return $recupere_parent;
	}
	
	//classement horizontal enfant
	public static function horizontale_enfant($idsession, $parent) {
		$connexionInstance = Connexion::getInstance();
		$sql = "SELECT enfant, count(enfant) as cptenfant FROM (SELECT IDreference, lib_mtclf as parent, (SELECT lib_occurrence as nom FROM `occurence` WHERE CE_IDreference =IDreference GROUP BY IDoccurence, lib_occurrence ORDER BY (SELECT count(lib_occurrence) FROM `occurence` WHERE CE_IDreference IN (SELECT IDreference from reference WHERE CE_IDsession=? AND validation_reference=1) AND lib_occurrence=NOM) DESC, nom LIMIT 1) as enfant, (SELECT lib_occurrence as nom FROM `occurence` WHERE CE_IDreference =IDreference GROUP BY IDoccurence, lib_occurrence ORDER BY (SELECT count(lib_occurrence) FROM `occurence` WHERE CE_IDreference IN (SELECT IDreference from reference WHERE CE_IDsession=? AND validation_reference=1) AND lib_occurrence=NOM) DESC, nom LIMIT 1,1) as petitenfant FROM `reference` JOIN mtclf ON `CE_IDmtclf` = IDmtclf WHERE `CE_IDsession`=? AND `validation_reference`=1 ORDER BY parent) as horizontal WHERE parent = ? AND enfant IS NOT NULL GROUP BY enfant ORDER BY count(enfant) DESC";
		$recupere_enfant = $connexionInstance->requeter($sql, array($idsession,$idsession,$idsession,$parent));
		return $recupere_enfant;
		}
		
	//classement horizontal ptt-enfant
	public static function horizontale_ptenfant($idsession, $parent,$enfant) {
		$connexionInstance = Connexion::getInstance();
		$sql = "SELECT petitenfant, count(petitenfant) as ptenfant FROM (SELECT IDreference, lib_mtclf as parent, (SELECT lib_occurrence as nom FROM `occurence` WHERE CE_IDreference =IDreference GROUP BY IDoccurence, lib_occurrence ORDER BY (SELECT count(lib_occurrence) FROM `occurence` WHERE CE_IDreference IN (SELECT IDreference from reference WHERE CE_IDsession=? AND validation_reference=1) AND lib_occurrence=NOM) DESC, nom LIMIT 1) as enfant, (SELECT lib_occurrence as nom FROM `occurence` WHERE CE_IDreference =IDreference GROUP BY IDoccurence, lib_occurrence ORDER BY (SELECT count(lib_occurrence) FROM `occurence` WHERE CE_IDreference IN (SELECT IDreference from reference WHERE CE_IDsession=? AND validation_reference=1) AND lib_occurrence=NOM) DESC, nom LIMIT 1,1) as petitenfant FROM `reference` JOIN mtclf ON `CE_IDmtclf` = IDmtclf WHERE `CE_IDsession`=? AND `validation_reference`=1 ORDER BY parent) as horizontal WHERE parent =? AND enfant=? GROUP BY petitenfant ORDER BY count(petitenfant) DESC";
		$recupere_ptenfant = $connexionInstance->requeter($sql, array($idsession,$idsession,$idsession,$parent,$enfant));
		return $recupere_ptenfant;
		}
		
	//Pour afficher sélection de la table des occurrences
	public static function afficheSelectionRef($idsession,$ref) {
		$connexionInstance = Connexion::getInstance();
		$sql = "SELECT IDreference, date_reference, lib_reference, lib_mtclf FROM reference JOIN mtclf ON CE_IDmtclf = IDmtclf WHERE IDreference IN (".$ref.") AND CE_IDsession = ? AND validation_reference = 1";
		 $allreference = $connexionInstance->requeter($sql, array($idsession));
		 return $allreference; 
		}
		
	//Affichage de la timeline
	public static function construiretimeline($idsession) {
		$connexionInstance = Connexion::getInstance();
		$sql="SELECT `CE_IDreference`, lib_occurrence, YEAR(date_reference) as annee, MONTH(date_reference) as mois, DAY(date_reference) as jour FROM reference JOIN occurence ON IDreference = CE_IDreference WHERE CE_IDsession =? AND validation_reference = 1 ORDER BY lib_occurrence";
		$recupere_timeline = $connexionInstance->requeter($sql, array($idsession));
		return $recupere_timeline;
		}
		
	//Affichage de l'arborescence
	public static function construirearborescence($idsession) {
		
		//On commence par récupérer les lignes parents, enfants, petit-enfant
		$parent = self::horizontale_parent($idsession);
		$parentx = "";
		$childrenx = "";
		$childrenxx = "";
		
		$Nomsession = Session::getSession($idsession);
		$Nomsession = $Nomsession[0]['lib_session'];// On recupere le nom de la session
		
		$NBrefx = self::compterReference($idsession);
		
		$tabparent = [];
		foreach($parent as $par) {
			
			$donn = $par['parent']." (".$par['cptparent'].")";
			array_push($tabparent,$donn); // On créé le tableau parent
			
			}
		
		
		foreach($tabparent as $tabpar) {
			$mot = explode("(",$tabpar);
			$nomparent = trim($mot[0]);
			$enfant = self::horizontale_enfant($idsession,$nomparent);
			
			$tabenfant = [];
			foreach($enfant as $enf) {
				
				
				
				if(($enf['cptenfant']!=0) || ($enf['enfant']!=0)) {
				$donn = $enf['enfant']." (".$enf['cptenfant'].")";
				array_push($tabenfant,$donn); // On créé le tableau enfant
					
				}
				
				
				foreach($tabenfant as $tbenf) {
					$mot = explode("(",$tbenf);
					$nomenfant = trim($mot[0]);
					$ptenfant = self::horizontale_ptenfant($idsession,$nomparent,$nomenfant);
					$childrenxx = "";
					foreach($ptenfant as $ptenf) { // On affiche le tableau ptt enfant
						if($ptenf[0]!=null) {
						$childrenxx .= "{'name': '".$ptenf[0]." (".$ptenf[1].")', 'parent': 'adef'},";
						}
					}
					
					
				}
				
				$childrenx .= "{'name': '$tbenf', 'parent': '$nomparent','children': [".$childrenxx."]},";
				$childrenxx = "";
	
			}	
				$parentx .= "{'name': '$tabpar', 'parent': '".$Nomsession."','children': [".$childrenx."]},";
				$childrenx = "";

		}
		
		$test = "[{'name': '".$Nomsession." (".intval($NBrefx[0]).")', 'parent': 'null','children': [".$parentx."]}]";
		return $test;
		}	
		
}

?>