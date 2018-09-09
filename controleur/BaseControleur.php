<?php
//gestion des variables $_ et des chemins
//gestion des injections sql
//gestion des erreurs

class BaseControleur
{
    protected $erreurMessage;
	protected $infoMessage;
    protected $pathVue;

    public function __construct()
    {
		
		$this->pathVue = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vue'.DIRECTORY_SEPARATOR;
	
	
		//Liste des information
		if($this->getGetParam('info') == 1)
        {
            $this->infoMessage = 'Bienvenue dans VTool, connectez-vous';
        }
		else if($this->getGetParam('info') == 2)
        {
            $this->infoMessage = 'Vous n\'avez pas encore assez de références pour afficher des résultats !';
        }
		
		//Liste des erreurs
        if($this->getGetParam('erreur') == 1)
        {
            $this->erreurMessage = 'Identifiants incorrects';
        }
		else if ($this->getGetParam('erreur') == 2)
		{
			$this->erreurMessage = 'Veuillez remplir tous les champs';
		}
		else if ($this->getGetParam('erreur') == 3)
		{
			$this->erreurMessage = 'Ce login est déjà utilisé ! ';
		}
		else if ($this->getGetParam('erreur') == 4)
		{
			$this->erreurMessage = 'Sélectionnez une thématique ! ';
		}
		else if ($this->getGetParam('erreur') == 5)
		{
			$this->erreurMessage = 'Mot-clef vide ! ';
		}
		else if ($this->getGetParam('erreur') == 6)
		{
			$this->erreurMessage = 'Flux RSS vide ! ';
		}
		else if ($this->getGetParam('erreur') == 7)
		{
			$this->erreurMessage = 'Aucune occurrence valide ! ';
		}
		else if ($this->getGetParam('erreur') == 8)
		{
			$this->erreurMessage = 'Export impossible, aucune donnée sélectionnée !! ';
		}
		else if ($this->getGetParam('erreur') == 9)
		{
			$this->erreurMessage = 'Mise à jour impossible, sélectionnez au moins deux mots-clefs et deux flux rss !! ';
		}
		
	}
	
	public function redirect($lien)	//redirection
    {
        header('location: '.$this->pathWeb($lien));
    }

    public function pathWeb($name)	//permet d'avoir le chemin du script
    {
        return dirname($this->getServerParam('REQUEST_URI')).'/'.$name;
    }

	
	//htmlspecialchars permet d'éviter les injections sql
	//=>enleve html qui peut être contenu dans la variable
    public function getSessionParam($name)
    {
        if (isset($_SESSION[$name])) {
            return htmlspecialchars($_SESSION[$name]);
        }
        else
        {
            return null;
        }
    }

    public function setSessionParam($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function getGetParam($name)
    {
        if (isset($_GET[$name])) {
            return htmlspecialchars($_GET[$name]);
        }
        else
        {
            return null;
        }
    }

    public function getPostParam($name)
    {
        if (isset($_POST[$name])) {
            //return htmlspecialchars($_POST[$name]);
			return $_POST[$name];
        }
        else
        {
            return null;
        }
    }

    public function getServerParam($name)
    {
        if (isset($_SERVER[$name])) {
            return htmlspecialchars($_SERVER[$name]);
        }
        else
        {
            return null;
        }
    }
}