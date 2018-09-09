<?php
require '../web/librairies/fpdf/fpdf.php';

class ReferenceControleur extends BaseControleur
{
	public function affiche_session($idsession,$idreference,$control,$chemin,$export) {
		$session = Session::getSession($idsession);
		$fichier = "reference";
		$allreference = Reference::afficheAllReference($idsession);
		$nbref = Reference::compterReference($idsession);
		if($idreference>0) {$detailreference= Reference::afficheDetailReference($idreference);} else {$detailreference=null;}
		$occurence = Reference::recupererOccurenceClasseByReference($idsession,$idreference);
		$alloccurence = Reference::classerOccurenceBySession($idsession);
		$ratioparent = Session::compterRatioMtclfByReference($idsession, $idreference);
		$mtclfBySession = Session::compterRatioMtclf($idsession);
		self::affiche_contenu(array('session' => $session, 'fichier' => $fichier, 'references' => $allreference, 'nbref' => $nbref, 'detailref' => $detailreference, 'occref' => $occurence, 'ratioparent' => $ratioparent, 'combomtclf' => $mtclfBySession, 'comboccurence' => $alloccurence, 'controle' => $control, 'chemin' => $chemin, 'export' => $export));
	}
	
	//Affichage du contenu de la page
	private function affiche_contenu($donnees) {
		$fichier = $donnees['fichier'];
		include $this->pathVue.'header.php';
		extract($donnees);
		// Démarrage de la temporisation de sortie
		ob_start();
		include $this->pathVue.$fichier.".php";
		include $this->pathVue.'footer.php';
		exit;
	}
	
	//Modifier le mot clé d'une références
	public function changer_mtclf($idreference,$idmtclf,$idsession) {
		$changemtclf = Reference::changemtclf($idreference,$idmtclf);
	}
	
	//Modifier le titre d'une références
	public function modifier_titre($idsession,$idreference,$nvtitre) {
		$changetitre = Reference::changetitre($idreference,$nvtitre);
	}
	
	//Modifier la description d'une références
	public function modifier_description($idsession,$idreference,$nvdesc) {
		$changedesc = Reference::changedescription($idreference,$nvdesc);
	}
	
	//Ajouter une occurence à une référence
	public function ajouter_occurence($idsession,$idreference,$cbocc) {
		$ajoutocc = Reference::ajoutoccurence($idreference,$cbocc);
	}
	
	//Supprimer une occurence
	public function supprimer_occurence($idsession, $idreference, $idoccurence) {
		$supocc = Reference::supprimeOccurence($idoccurence);
	}
	
	//Supprimer une référence
	public function supprime_reference($idsession,$idreference) {
		$suppression = Reference::suppressionreference($idreference);
	}
	
	//Désactiver/activer une référence
	public function activation_reference($idsession,$idreference) {
		$activation = Reference::activationreference($idreference);
	}
	
	//Rechercher dans les références
	public function recherche_session($idsession) {
		$session = Session::getSession($idsession);
		$fichier = "recherche";
		$mtclf = Mtclf::getBySession($idsession);
		$occurence = Reference::classerOccurenceBySession($idsession);
		$nbref = Reference::compterReference($idsession);
		self::affiche_contenu(array('session' => $session, 'fichier' => $fichier, 'motclef' => $mtclf, 'occurrence' => $occurence,'nbref' => $nbref));
	}
	
	
	///////////////Export en Txt
	public function exporterTxt($export,$idsession,$idreference,$tc) {
		$nom_session = Session::getSession($idsession);
		$nom_fichier = "fichier".$idsession.".txt";
		$date = date("d-m-Y");
		$titre_txt = 'VTool export'. "\r\n" . 'Thématique : '.$nom_session[0]['lib_session'].', le : '.$date."\r\r\n";
		switch ($export) {
				case "unique": //Premier cas  fiche export détail
					$reference = "Référence n° ".$idreference."\r\n";
					$contenu = $titre_txt.$reference; // contenu de l'id unique à sélectionner dans $tc
					foreach($tc as $itm) {
						if($itm['IDreference']==$idreference) {
							$description = Reference::afficheDetailReference($idreference);
							$date_parution = $description[0]['date_reference'];
							$datepar = date_create($date_parution, timezone_open('Europe/Paris'));
							$dateparx = date_format($datepar, 'd-m-Y');
							$occ = Reference::recupererOccurenceByReference($idreference);
							$motclef = "";
							foreach($occ as $liste_occ) {$motclef.= ", ".$liste_occ['lib_occurrence'];}
							$motclef = substr($motclef,1);
							$contenu = $contenu."Titre : ".$itm['lib_reference']."\r\n"."Résumé : ".$description[0]['description_reference']."\r\n"."Parution : ".$dateparx."\r\n"."url : ".$description[0]['url_reference']."\r\n"."Mots-clefs : ".$description[0]['lib_mtclf'].",".$motclef;
						}
					}
					$h = fopen($nom_fichier, "w");
					fwrite($h, $contenu);
					fclose($h);
					$chemin_export = $nom_fichier;
					break;
				case "tous": //Deuxième cas  fiche export multiple
					$contenu = $titre_txt; // contenu des exports dans $tc
					foreach($tc as $itm) {
						$idreference = $itm['IDreference'];
						$description = Reference::afficheDetailReference($idreference);
						$date_parution = $description[0]['date_reference'];
						$datepar = date_create($date_parution, timezone_open('Europe/Paris'));
						$dateparx = date_format($datepar, 'd-m-Y');
						$occ = Reference::recupererOccurenceByReference($idreference);
						$motclef = "";
						$separation = "-----------------------";
						foreach($occ as $liste_occ) {
							$motclef.= ", ".$liste_occ['lib_occurrence'];}
							$motclef = substr($motclef,1);
						$contenu = $contenu."Titre : ".$itm['lib_reference']."\r\n"."Résumé : ".$description[0]['description_reference']."\r\n"."Parution : ".$dateparx."\r\n"."url : ".$description[0]['url_reference']."\r\n"."Mots-clefs : ".$description[0]['lib_mtclf'].",".$motclef."\r\n\n".$separation."\r\n";
					}
					$h = fopen($nom_fichier, "w");
					fwrite($h, $contenu);
					fclose($h);
					$chemin_export = $nom_fichier;
					break;
	}
	if($chemin_export) {$chemin = $chemin_export;} else {$chemin=null;}
	self::affiche_session($idsession,$idreference,$tc,$chemin,$export);
	}
	
	///////////////Export en PDF
	public function exporterPdf($export,$idsession,$idreference,$tc) {
		$nom_session = Session::getSession($idsession);
		$nom_fichier = "fichier".$idsession.".pdf";
		if (file_exists($nom_fichier)) {
			unlink($nom_fichier);
			}
			$date = date("d-m-Y");
			$titre_txt = 'VTool export'. "\r\n" . 'Thématique : '.$nom_session[0]['lib_session'].', le : '.$date."\r\r\n";
			switch ($export) {
				case "unique": //Premier cas  fiche export détail
					$reference = "Référence n° ".$idreference."\r\n";
					$contenu = $titre_txt.$reference; // contenu de l'id unique à sélectionner dans $tc
					foreach($tc as $itm) {
						if($itm['IDreference']==$idreference) {
							$description = Reference::afficheDetailReference($idreference);
							$date_parution = $description[0]['date_reference'];
							$datepar = date_create($date_parution, timezone_open('Europe/Paris'));
							$dateparx = date_format($datepar, 'd-m-Y');
							$occ = Reference::recupererOccurenceByReference($idreference);
							$motclef = "";
							foreach($occ as $liste_occ) {$motclef.= ", ".$liste_occ['lib_occurrence'];}
							$motclef = substr($motclef,1);
							$contenu = $contenu."Titre : ".$itm['lib_reference']."\r\n"."Résumé : ".$description[0]['description_reference']."\r\n"."Parution : ".$dateparx."\r\n"."url : ".$description[0]['url_reference']."\r\n"."Mots-clefs : ".$description[0]['lib_mtclf'].",".$motclef;
						}
					}

							$PDF = new FPDF();
							$PDF->AddPage();
							$PDF->SetFont("Arial","B",12);
							$PDF->Write(10,iconv("UTF-8", "CP1250//TRANSLIT",$contenu));
							$PDF->Output($nom_fichier, "F");
					$chemin_export = $nom_fichier;
					break;
				case "tous": //Deuxième cas  fiche export multiple
					$contenu = $titre_txt; // contenu des exports dans $tc
					foreach($tc as $itm) {
						$idreference = $itm['IDreference'];
						$description = Reference::afficheDetailReference($idreference);
						$date_parution = $description[0]['date_reference'];
						$datepar = date_create($date_parution, timezone_open('Europe/Paris'));
						$dateparx = date_format($datepar, 'd-m-Y');
						$occ = Reference::recupererOccurenceByReference($idreference);
						$motclef = "";
						$separation = "-----------------------";
						foreach($occ as $liste_occ) {$motclef.= ", ".$liste_occ['lib_occurrence'];}
							$motclef = substr($motclef,1);
						$contenu = $contenu."Titre : ".$itm['lib_reference']."\r\n"."Résumé : ".$description[0]['description_reference']."\r\n"."Parution : ".$dateparx."\r\n"."url : ".$description[0]['url_reference']."\r\n"."Mots-clefs : ".$description[0]['lib_mtclf'].",".$motclef."\r\n\n".$separation."\r\n";
					}
						$PDF = new FPDF();
						$PDF->AddPage();
						$PDF->SetFont("Arial","B",12);
						$PDF->Write(10,iconv("UTF-8", "CP1250//TRANSLIT",$contenu));
						$PDF->Output($nom_fichier, "F");
					$chemin_export = $nom_fichier;
					$chemin_export = $nom_fichier;
					break;
	}
	if($chemin_export) {$chemin = $chemin_export;} else {$chemin=null;}
	self::affiche_session($idsession,$idreference,$tc,$chemin,$export);
	}		
	
	///////////////Export en Csv
	public function exporterCsv($export,$idsession,$idreference,$tc) {
		$nom_session = Session::getSession($idsession);
		$nom_fichier = "fichier".$idsession.".csv";
			if (file_exists($nom_fichier)) {
			unlink($nom_fichier);
			}
			$delimiteur = ',';
			$fichier_csv = fopen($nom_fichier, 'w+');
			fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF));
			
		$date = date("d-m-Y");
		$titre_txt = 'VTool export'. "\r\n" . 'Thématique : '.$nom_session[0]['lib_session'].', le : '.$date."\r\r\n";
		switch ($export) {
				case "unique": //Premier cas  fiche export détail
					$reference = "Référence n° ".$idreference."\r\n";
					$contenu = $reference; // contenu de l'id unique à sélectionner dans $tc
					foreach($tc as $itm) {
						if($itm['IDreference']==$idreference) {
							$description = Reference::afficheDetailReference($idreference);
							$date_parution = $description[0]['date_reference'];
							$datepar = date_create($date_parution, timezone_open('Europe/Paris'));
							$dateparx = date_format($datepar, 'd-m-Y');
							$occ = Reference::recupererOccurenceByReference($idreference);
							$motclef = "";
							foreach($occ as $liste_occ) {$motclef.= "/".$liste_occ['lib_occurrence'];}
							$motclef = substr($motclef,1);
							$ligne = array($itm['lib_reference'],$dateparx,$description[0]['description_reference'],$description[0]['url_reference'],$motclef);
							fputcsv($fichier_csv, $ligne, $delimiteur);
						}
					}
					fclose($fichier_csv);
					if (file_exists($nom_fichier)) {
						$file = file_get_contents($nom_fichier);
						$header = $titre_txt.$contenu."\r\n";
						file_put_contents($nom_fichier,$header.$file);
					}
					$chemin_export = $nom_fichier;
					break;
				case "tous": //Deuxième cas  fiche export multiple
					foreach($tc as $itm) {
						$idreference = $itm['IDreference'];
						$description = Reference::afficheDetailReference($idreference);
							$date_parution = $description[0]['date_reference'];
							$datepar = date_create($date_parution, timezone_open('Europe/Paris'));
							$dateparx = date_format($datepar, 'd-m-Y');
							$occ = Reference::recupererOccurenceByReference($idreference);
							$motclef = "";
							foreach($occ as $liste_occ) {$motclef.= "/".$liste_occ['lib_occurrence'];}
							$motclef = substr($motclef,1);
							$ligne = array($itm['lib_reference'],$dateparx,$description[0]['description_reference'],$description[0]['url_reference'],$motclef);
							fputcsv($fichier_csv, $ligne, $delimiteur);
					}
					fclose($fichier_csv);
					if (file_exists($nom_fichier)) {
						$file = file_get_contents($nom_fichier);
						$header = $titre_txt."\r\n";
						file_put_contents($nom_fichier,$header.$file);
					}
					$chemin_export = $nom_fichier;
					break;
	}
	if($chemin_export) {$chemin = $chemin_export;} else {$chemin=null;}
	self::affiche_session($idsession,$idreference,$tc,$chemin,$export);
	}
	
	//////////////////RECHERCHE DANS LES Références
	
	//Prépare la recherche des références désactivées
	public function liste_desactive($idsession) {
		$session = Session::getSession($idsession);
		$fichier = "reference";
		$allreference = Reference::afficheReferenceDesactive($idsession);
		$nbref = Reference::compterReference($idsession);
		$detailreference=null;
		$idreference=0;
		$occurence = Reference::recupererOccurenceClasseByReference($idsession,$idreference);
		$alloccurence = Reference::classerOccurenceBySession($idsession);
		$ratioparent = Session::compterRatioMtclfByReference($idsession, $idreference);
		$mtclfBySession = Session::compterRatioMtclf($idsession);
		self::affiche_contenu(array('session' => $session, 'fichier' => $fichier, 'references' => $allreference, 'nbref' => $nbref, 'detailref' => $detailreference, 'occref' => $occurence, 'ratioparent' => $ratioparent, 'combomtclf' => $mtclfBySession, 'comboccurence' => $alloccurence));
	}
	
	//Prépare la recherche des références sans occurrences
	public function liste_ss_occ($idsession) {
		$session = Session::getSession($idsession);
		$fichier = "reference";
		$allreference = Reference::afficheReferenceSsOcc($idsession);
		$nbref = Reference::compterReference($idsession);
		$detailreference=null;
		$idreference=0;
		$occurence = Reference::recupererOccurenceClasseByReference($idsession,$idreference);
		$alloccurence = Reference::classerOccurenceBySession($idsession);
		$ratioparent = Session::compterRatioMtclfByReference($idsession, $idreference);
		$mtclfBySession = Session::compterRatioMtclf($idsession);
		self::affiche_contenu(array('session' => $session, 'fichier' => $fichier, 'references' => $allreference, 'nbref' => $nbref, 'detailref' => $detailreference, 'occref' => $occurence, 'ratioparent' => $ratioparent, 'combomtclf' => $mtclfBySession, 'comboccurence' => $alloccurence));
	}
	
	//Prepare la recherche_session
	public function prepare_recherche($idsession,$mot,$newdebut,$newfin, $parents, $occs) {
		$session = Session::getSession($idsession);
		$fichier = "reference";
		$allreference = Reference::afficheRechercheReference($idsession,$mot,$newdebut,$newfin,$parents, $occs); // à reprendre avec une sélection de références ok
		$nbref = Reference::compterReference($idsession);
		$detailreference=null;
		$idreference = 0;
		$occurence = Reference::recupererOccurenceClasseByReference($idsession,$idreference);
		$alloccurence = Reference::classerOccurenceBySession($idsession);
		$ratioparent = Session::compterRatioMtclfByReference($idsession, $idreference);
		$mtclfBySession = Session::compterRatioMtclf($idsession);
		self::affiche_contenu(array('session' => $session, 'fichier' => $fichier, 'references' => $allreference, 'nbref' => $nbref, 'detailref' => $detailreference, 'occref' => $occurence, 'ratioparent' => $ratioparent, 'combomtclf' => $mtclfBySession, 'comboccurence' => $alloccurence));
	}
	
	public function traitement($idsession) {
		$control = Session::GetNbTemporaire($idsession);
		if(intval($control[0]['nb'])<1) {
				///// fin de traitement retour vers menu session
						$fichier = "session";
						$historique = Session::actualisehisto($idsession);
						////////////////////////////////////////////////////////////////////////ICI
			}
			else
			{
				/// poursuite du traitement
				self::schema_traitement($idsession, "traitement");
			}
		}
		
	public function schema_traitement($idsession, $fichier) {
		//On récupère la table temporaire ligne par ligne
		$reference = Reference::getTemporaire($idsession);
		$occurrence = Reference::getOccurrence($reference);
		$reference = Reference::getTemporaire($idsession); // je sais pas pourquoi je perds $reference donc obligé de l'instancier une deuxième fois ????
		
		$session = Session::getSession($idsession);
		self::affiche_contenu(array('session' => $session, 'fichier' => $fichier, 'reference' => $reference, 'occurrence' => $occurrence));
		}
		
	//Insérer une nouvelle reference
	public function insereReference($IDtemporaire, $idsession, $tabocc) {
		$reference = Reference::insereReference($IDtemporaire, $idsession, $tabocc);
		self::traitement($idsession);
	}
	
	//Prépare la recherche des références sélectionnée dans la table des occurrences
	public function reference_selection($idsession,$ref) {
		
		$session = Session::getSession($idsession);
		$fichier = "reference";
		$allreference = Reference::afficheSelectionRef($idsession,$ref);
		$nbref = Reference::compterReference($idsession);
		$detailreference=null;
		$idreference = 0;
		$occurence = Reference::recupererOccurenceClasseByReference($idsession,$idreference);
		$alloccurence = Reference::classerOccurenceBySession($idsession);
		$ratioparent = Session::compterRatioMtclfByReference($idsession, $idreference);
		$mtclfBySession = Session::compterRatioMtclf($idsession);
		self::affiche_contenu(array('session' => $session, 'fichier' => $fichier, 'references' => $allreference, 'nbref' => $nbref, 'detailref' => $detailreference, 'occref' => $occurence, 'ratioparent' => $ratioparent, 'combomtclf' => $mtclfBySession, 'comboccurence' => $alloccurence));
	}
	
	//affiche le tutoriel
	public function aide_session($idsession) {
		$session = Session::getSession($idsession);
		$lien = Lien::getBySession(2); // 2 pour cibler sur ma veille machine learning !!!!
		$fichier = "tutoriel";
		self::affiche_contenu(array('session' => $session, 'fichier' => $fichier, 'lien' => $lien));
		}
	
}

?>