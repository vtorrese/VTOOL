<?php
class Utilisateur
{
	private $id = null;
	private $login;
	private $mdp;
	private $statut;
	
	public function getId(){return $this->id;}
	public function setId($id){$this->id = $id;}
	
	public function getLogin(){return $this->login;}
	public function setLogin($login){$this->login = $login;}
	
	public function getMdp(){return $this->mdp;}
	public function setMdp($mdp){$this->mdp = $mdp;}

	public function getStatut(){return $this->statut;}
	public function setStatut($statut){$this->statut = $statut;}
	
	public static function getAllListe(){} // a voir
	
	
	public static function verifierLogin($login) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT COUNT(*) as CPT FROM utilisateur WHERE login=?';
		$controle_dblon = $connexionInstance->requeter($sql, array($login));
		return $controle_dblon;
	}
	
	
	public static function getById($id){
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT login, statut FROM utilisateur WHERE IDutilisateur =?';
		$recupere_tout = $connexionInstance->requeter($sql, array($id));
		return $recupere_tout;
	}
	
	public static function getIdByLogin($login) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT IDutilisateur FROM utilisateur WHERE login =?';
		$recupere_ID = $connexionInstance->requeter($sql, array($login));
		return $recupere_ID[0]['IDutilisateur'];
	}
	
	public static function getByLoginMdp($login, $mdp) {
		$connexionInstance = Connexion::getInstance();
		$sql = 'SELECT mdpx FROM utilisateur WHERE login =?';
		$ouvrir_session = $connexionInstance->requeter($sql, array($login));
		
		if(count($ouvrir_session)>0) {
			if($mdp==($ouvrir_session[0]['mdpx'])) {
				$cadenas = true;
			}
			else
			{
				$cadenas = false;
			}
		}
		else
		{
			$cadenas = false;
		}
		return $cadenas;
	}
	
	public static function insererUtilisateur($login, $mdp) {
		$connexionInstance = Connexion::getInstance();
		$statut = 2;
		$sql = 'INSERT INTO utilisateur (login,mdpx,statut) VALUES (?,?,?)';
		$creer_utilisateur = $connexionInstance->requeter($sql, array($login,$mdp,$statut));
		return;
	}
    
	
	
}

?>