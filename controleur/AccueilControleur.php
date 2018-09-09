<?php

class AccueilControleur extends BaseControleur
{
	public function accueil($id)
	{
		$sessions = Session::getSessions($id);
		$fichier = "accueil";
		self::affiche_contenu(array('session' => $sessions, 'fichier' => $fichier));
	}
	
	public function affiche_contenu($donnees)
	{ //Page accueil		
		$fichier = $donnees['fichier'];
		include $this->pathVue.'header.php';
		extract($donnees);
		// Démarrage de la temporisation de sortie
		ob_start();
		include $this->pathVue.$fichier.".php";
		include $this->pathVue.'footer.php';

	}
}

?>