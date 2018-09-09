<?php
class AuthentificationController extends BaseControleur
{
    public function login()
    { //Affiche la vue authentification

		include $this->pathVue.'header.php';
        include $this->pathVue.'formulaireLogin.php';
        include $this->pathVue.'footer.php';
    }
	
	

    public function traitementLogin()
    { //Vérification du login et du mot de passe
        $bdd = Connexion::getInstance();
		$mdp = $this->getPostParam('motdepasse');
		$login = $this->getPostParam('login');

		if (!empty($login) && !empty($mdp))
		{ //champs login et mot de passe correspondants	
			//$sel='lootv5'; //A mettre en place plus tard (concaténation dans sha1)
			$mdp_crypte = sha1($mdp);
			$vérification_login_mdp = Utilisateur::getByLoginMdp($login,$mdp_crypte);
			
			if ($vérification_login_mdp == true)
			{ //login et mdp OK
				$id = Utilisateur::getIdByLogin($login);
				$utilisateur = Utilisateur::getById($id);
				$statut = $utilisateur[0]['statut'];
				$nom = $utilisateur[0]['login'];
				$this->setSessionParam('estAutenthifie', 'true');
				$this->setSessionParam('statut', intval($statut));
				$this->setSessionParam('id', intval($id));
				$this->setSessionParam('login', $nom);
				$this->redirect('index.php'); //redirection vers la page autorisée
			}
			else
			{ //login/mdp INCONNU ou FAUX
				$this->redirect('index.php?erreur=1');
			}
		}
		else
		{ //erreur : remplir tout les champs
			$this->redirect('index.php?erreur=2');
		}
    }
	
	public function logout()
    { //Déconnexion
        $this->setSessionParam('estAutenthifie', 'false');
		session_destroy();
		$this->redirect('index.php');
    }
	
	public function enregistrementLogin()
	{ // enregistrement d'un nouvel utilisateur
		$mdp = $this->getPostParam('motdepasse');
		$login = $this->getPostParam('login');
		
		//vérification doublon login
		$doublon = Utilisateur::verifierLogin($login);
		if(intval($doublon[0]['CPT'])>0) {
			$this->setSessionParam('estAutenthifie', 'false');
			$this->redirect('index.php?erreur=3');
		}
		else
		{
			$mdp_crypte = sha1($mdp);
			$nouvel_utilisateur = Utilisateur::insererUtilisateur($login,$mdp_crypte);
			$id = Utilisateur::getIdByLogin($login);
				$utilisateur = Utilisateur::getById($id);
				$statut = $utilisateur[0]['statut'];
				$nom = $utilisateur[0]['login'];
				$this->setSessionParam('estAutenthifie', 'true');
				$this->setSessionParam('statut', intval($statut));
				$this->setSessionParam('id', intval($id));
				$this->setSessionParam('login', $nom);
				$this->redirect('index.php'); //redirection vers la page autorisée
		}
	}
	
	
}
?>