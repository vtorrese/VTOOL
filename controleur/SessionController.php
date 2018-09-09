<?php

class SessionController extends BaseControleur
{
	
	public function ouvrir_session($idsession) {
		$session = Session::getSession($idsession);
		$mtclf = Mtclf::getBySession($idsession);
		$lien = Lien::getBySession($idsession);
		$nbref = Reference::compterReference($idsession);
		$nbmtclf = Mtclf::compterMtclf($idsession);
		$nbflux = Lien::compterLien($idsession);
		$datex = Reference::daterReference($idsession);
		$mtclfBySession = Session::compterRatioMtclf($idsession);
		$lienBySession = Session::compterRatioLien($idsession);
		$majBySession = Session::comptemaj($idsession);
		$fichier  = "session";
		self::affiche_contenu(array('session' => $session, 'mtclf' => $mtclf, 'lien' => $lien, 'fichier' => $fichier,'nbref' => $nbref, 'nbmtclf' => $nbmtclf, 'nblien' => $nbflux, 'datex' => $datex, 'freqmtclf' => $mtclfBySession, 'freqlien' => $lienBySession, 'maj' => $majBySession));
	}
	
	public function affiche_contenu($donnees)
	{ //Page acceuil		
		$fichier = $donnees['fichier'].".php";
		include $this->pathVue.'header.php';
		extract($donnees);
		// Démarrage de la temporisation de sortie
		ob_start();
		include $this->pathVue.$fichier.".php";
		include $this->pathVue.'footer.php';

	}
	
	//Pour supprimer une session
	public function supprimer_session($idsession) {
		$session = Session::suppressionSession($idsession);
	}
	
	//Pour ajouter une session
	public function ajouter_session($nvsession,$id) {
		if(!empty($nvsession)) {
			$session = Session::ajoutSession($nvsession,$id);
		}
	}
	
	//ajout d'un mot-clef
	public function ajouter_mtclf($libmtclf, $idsession) {
			$mtclf_session = Mtclf::ajoutMtclf($libmtclf, $idsession);
		}
		
	 	//Supprime un mot-clef
    public function supprimer_mtclf($idmtclf, $idsession) {
			$supmtclf_session = Mtclf::suppressionMtclf($idmtclf, $idsession);
		}
		
	//ajout d'un flux rss
	public function ajouter_lien($liblien, $idsession) {
			$lien_session = Lien::ajoutLien($liblien, $idsession);
		}
		
	//Supprime un lien
    public function supprimer_lien($idlien, $idsession) {
			$suplien_session = Lien::suppressionLien($idlien, $idsession);
		}
		
	//mise à jour de la session
	public function maj_session($idsession) {
	
			$lien = Lien::getBySession($idsession);
			$mtclf = Mtclf::getBySession($idsession);
			if(count($lien)>1) { //vérifier qu'il y a bien de quoi faire la maj_session
				$videtemporaire = Session::purgetemporaire($idsession);
				$maj = Session::updateSession($idsession,$lien,$mtclf);
				$maj_tab = Session::GetTemporaire($idsession);
				$compteur_maj = Session::GetNbTemporaire($idsession);
				self::schema_new($idsession, "maj",$maj_tab,$compteur_maj[0]);
			}
			else
			{
				$this->redirect('index.php?erreur=9');
			}
			
		
		}

	private function schema_new($idsession, $fichier, $entrees, $nbentrees) {
		$session = Session::getSession($idsession);
		self::affiche_contenu(array('session' => $session, 'fichier' => $fichier, 'entrees' => $entrees, 'nbentrees' => $nbentrees));
		}

	//Suppression partielle de la table temporaire
	public function selectiontemporaire($tabtemp) {
		$select = Session::selectemporaire(substr($tabtemp,1));
	}
	
	//Suppresion de la table temporaire
	public function videtemporaire($idsession) {
		$supp = Session::purgetemporaire($idsession);
		
	}
	
	//Actualise l'historique
	public function actualisehistorique($idsession) {
		$actualise = Session::actualisehisto($idsession);
	}
	
		
}
	

?>