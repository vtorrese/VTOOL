<?php


class ResultatControleur extends BaseControleur
{
	
	public function affiche_session($idsession,$ecran,$nbref) {
			$session = Session::getSession($idsession);
			$fichier = "resultat";
	
			//Répartition des résultats selon écran à afficher
			switch ($ecran) {
				case 0:
					$data = ""; // faire quelque chose à l'écran d'accueil des résultats
					break;
				case 1:
					$time_start = microtime(true); // Pour estimer la durée de construction de l'arborescence
					$data = Reference::construirearborescence($idsession);
					$time_end = microtime(true); // comptabilisation de la duréee de construction de l'arborescence
					$time = $time_end - $time_start;
					break;
				case 2:
					$time_start = microtime(true); // Pour estimer la durée de construction de la timeline
					$data = Reference::construiretimeline($idsession);
					$time_end = microtime(true); // comptabilisation de la duréee de construction de la timeline
					$time = $time_end - $time_start;
					break;
				case 3:
					$time_start = microtime(true); // Pour estimer la durée de construction des tables
					$data = Reference::construiretable_occurence($idsession);
					$time_end = microtime(true); // comptabilisation de la duréee de construction des tables
					$time = $time_end - $time_start;
					break;
				case 4:
					$data = Reference::construireanalyse($idsession);
					break;
				}
			
			if(isset($time)) {$time=$time;} else {$time=null;}
			self::affiche_contenu(array('session' => $session, 'fichier' => $fichier, 'nbref' => $nbref, 'ecran' =>$ecran, 'data' => $data, 'timer' => $time));
		}
		
	//Affichage du contenu de la page
	private function affiche_contenu($donnees) {
		$fichier = $donnees['fichier'].".php";
		include $this->pathVue.'header.php';
		extract($donnees);
		// Démarrage de la temporisation de sortie
		ob_start();
		include $this->pathVue.$fichier.".php";
		include $this->pathVue.'footer.php';
		}

	//Affichage de l'analyse
	public function analyse_veille($idsession) {
		$ecran=4;
		$nbref = Reference::compterReference($idsession);
		self::affiche_session($idsession,$ecran,$nbref);
		}
		
	//Affichage de la table des occurences
	public function affiche_table_occurence($idsession) {
		$ecran=3;
		$nbref = Reference::compterReference($idsession);
		self::affiche_session($idsession,$ecran,$nbref);
		}	
		
	//Affichage de la timeline
	public function affiche_timeline($idsession) {
		$ecran=2;
		$nbref = Reference::compterReference($idsession);
		self::affiche_session($idsession,$ecran,$nbref);
		}
		
	//Affichage de l'arborescence
	public function affiche_arborescence($idsession) {
		$ecran=1;
		$nbref = Reference::compterReference($idsession);
		self::affiche_session($idsession,$ecran,$nbref);
		}
	
	// Construction de la table des ref/occ
	public function construiretable_occurence($idsession) {
		$table_occ = Reference::construiretable_occurence($idsession);
		$ecran=3;
		$nbref = Reference::compterReference($idsession);
		self::affiche_session($idsession,$ecran,$nbref);
		}
}

?>