<?php
//Controleur principal -> redirige vers la page autorisée
class MainController extends BaseControleur
{
    public function dispatch()
    {
 
		if($this->getSessionParam('estAutenthifie') === null || $this->getSessionParam('estAutenthifie') === false)
        { // Non connecté
            if($this->getGetParam('page') === null)
            { // Page d'accueil
                $authentificationController = new AuthentificationController();
                $authentificationController->login();
            }
			else if($this->getGetParam('page') === 'traitementLogin')
            { // bouton pour se connecter
				if($_POST['btn_gene']=='Se connecter') {
					$authentificationController = new AuthentificationController();
					$authentificationController->traitementLogin();
				}
				else if($_POST['btn_gene']=='S\'inscrire')
				{ // bouton pour s'inscrire
					$authentificationController = new AuthentificationController();
					$authentificationController->enregistrementLogin();
				}
			}
			
		}
		else
        { // Connecté
			if($this->getGetParam('page') === null)
            {
				 if(intval($this->getSessionParam('statut')) === 1)
                {  //L'utilisateur est administrateur
					$accueilControleur = new AccueilControleur();
					$id = $this->getSessionParam('id');
					$accueilControleur->accueil($id);
                }
				
				if(intval($this->getSessionParam('statut')) === 2)
                {  //L'utilisateur est utilisateur
					$accueilControleur = new AccueilControleur();
					$id = $this->getSessionParam('id');
					$accueilControleur->accueil($id);
                }
				
			}
			
			if ($this->getGetParam('page') === 'deconnection')
				{
					$authentificationController = new AuthentificationController();
					$authentificationController->logout();
				}
			else if($this->getGetParam('page') === 'gestionsession') // bouton pour gérer les sessions
            { 
				if($_POST['btn_gene']=='Choisir') { // choisir une session
					$idsession = $_POST['combosession'];
					if($idsession>0) {
						$sessionController = new SessionController();
						$sessionController->ouvrir_session($idsession);
					}
					else
					{
						$this->redirect('index.php?erreur=4');
					}
				}
				else if($_POST['btn_gene']=='Supprimer') // Supprimer une session
				{ 
					$idsession = $_POST['combosession'];
					$sessionController = new SessionController();
					$sessionController->supprimer_session($idsession);
					$id = $this->getSessionParam('id');
					$accueilControleur = new AccueilControleur();
					$accueilControleur->accueil($id);
				}
				else if($_POST['btn_gene']=='Ajouter') // Ajouter une session
				{ 
					$nvsession = $_POST['nveau_session'];
					$id = $this->getSessionParam('id');
					$sessionController = new SessionController();
					$sessionController->ajouter_session($nvsession,$id);
					$accueilControleur = new AccueilControleur();
					$accueilControleur->accueil($id);
				}
			}
			else if($this->getGetParam('page') === 'editsession') // bouton pour gérer les sessions
            { 
				if(isset($_POST['btn_gene'])) {
				
					if($_POST['btn_gene']=='retour accueil') { // revenir à l'accueil
						$id = $this->getSessionParam('id');
						$accueilControleur = new AccueilControleur();
						$accueilControleur->accueil($id);
					}
					else if($_POST['btn_gene']=='mettre à jour') // Mettre à jour une session
					{ 
						$idsession = $_POST['IDsession'];
						$sessionController = new SessionController();
						$sessionController->maj_session($idsession);
						
					}
					else if($_POST['btn_gene']=='consulter') // Consulter une session
					{ 
						$idsession = $_POST['IDsession'];
						$idreference = 0;
						$referenceControleur = new ReferenceControleur();
						if(!isset($control)) {$control= null;}
						if(!isset($chemin)) {$chemin= null;}
						if(!isset($export)) {$export= null;}
						$referenceControleur->affiche_session($idsession,$idreference,$control,$chemin,$export);
					}
					else if($_POST['btn_gene']=='rechercher') // Rechercher dans une session
					{ 
						$idsession = $_POST['IDsession'];
						$referenceControleur = new ReferenceControleur();
						$referenceControleur->recherche_session($idsession);
					}
					else if($_POST['btn_gene']=='tutoriel') // Accéder à l'aide
					{ 
						$idsession = $_POST['IDsession'];
						$referenceControleur = new ReferenceControleur();
						$referenceControleur->aide_session($idsession);
					}
					else if($_POST['btn_gene']=='résultats') // Publir les résultats de la session
					{ 
						$idsession = $_POST['IDsession'];
						$nbref = Reference::compterReference($idsession);
						if(intval($nbref[0][0])>0) {
							$ecran=0;
							$resultatControleur = new ResultatControleur();
							$resultatControleur->affiche_session($idsession,$ecran,$nbref);
						}
						else
						{
							$this->redirect('index.php?info=2');
						}
						
					}
				
				}
						//Insertion d'une nouvelle entrée dans la table reference
				else if(isset($_POST ['valid_new_ref'])) {
					$IDtemporaire = intval($_POST['IDtemporaire']);
					$idsession = $_POST['IDsession'];
					if(isset($_POST['tab'])) {$tabocc = $_POST['tab'];} else {$tabocc = null;}
					$referenceControleur = new ReferenceControleur();
					$referenceControleur->insereReference($IDtemporaire, $idsession, $tabocc);
					$sessionController = new SessionController();
					$sessionController->ouvrir_session($idsession);
				}
				
						//Validation des nouvelles entrées
				else if(isset($_POST['valid_maj'])) {
					$tabtemporaire = $_POST['compteur'];
					$tabtemp = "";
					for($i=1;$i<$tabtemporaire+1;$i++) {
						if(isset($_POST[$i])) {
							$label = "idtemp_".$i;
							$tabtemp .=",".$_POST[$label];
						}
					}
	
					//vérification de la sélection d'une nouvelle entrées
					if(!empty($tabtemp)) {
						$sessionController = new SessionController();
						$sessionController->selectiontemporaire($tabtemp);
					//////////////////////////////////////////Lancement du traitement et des occurences
						$idsession = $_POST['IDsession'];
						$referenceControleur = new ReferenceControleur();
						$referenceControleur->traitement($idsession);
					}
					else
					{
						$sessionController = new SessionController();
						$sessionController->videtemporaire($idsession);
						$fichier = "session";
						$idsession = $_POST['IDsession'];
						$sessionController->actualisehistorique($idsession); ////// historique nécessaire ou pas (cas de figure ou on ne retient pas les nouvelles entrées)
						$sessionController = new SessionController();
						$sessionController->ouvrir_session($idsession);
					}
				}
	
			}
			
			else if($this->getGetParam('page') === 'consultsession') //menu de l'édition session
            { 
				if($_POST['btn_gene']=='retour accueil') { // revenir à l'accueil
					$id = $this->getSessionParam('id');
					$accueilControleur = new AccueilControleur();
					$accueilControleur->accueil($id);
				}
				else if($_POST['btn_gene']=='retour thématique') // revenir au menu de la session
				{ 
					$idsession = $_POST['IDsession'];
					$sessionController = new SessionController();
					$sessionController->ouvrir_session($idsession);
				}
				else if($_POST['btn_gene']=='rechercher') {  // panneau de recherche
					$idsession = $_POST['IDsession'];
					$referenceControleur = new ReferenceControleur();
					$referenceControleur->recherche_session($idsession);
				}
				else if($_POST['btn_gene']=='résultats') // Publir les résultats de la session
					{ 
						$idsession = $_POST['IDsession'];
						$nbref = Reference::compterReference($idsession);
						if(intval($nbref[0][0])>0) {
							$ecran=0;
							$resultatControleur = new ResultatControleur();
							$resultatControleur->affiche_session($idsession,$ecran,$nbref);
						}
						else
						{
							$this->redirect('index.php?info=2');
						}
						
				}
			}
			
			else if($this->getGetParam('page') === 'consultresult') // bouton pour gérer les résultats
            {
				
				if(isset($_POST['btn_gene'])) {
					if($_POST['btn_gene']=='retour accueil') { // revenir à l'accueil
						$id = $this->getSessionParam('id');
						$accueilControleur = new AccueilControleur();
						$accueilControleur->accueil($id);
					}
					else if($_POST['btn_gene']=='retour thématique') // revenir au menu de la thématique
					{ 
						$idsession = $_POST['IDsession'];
						$sessionController = new SessionController();
						$sessionController->ouvrir_session($idsession);
					}
					else if($_POST['btn_gene']=='Analyse de la veille') // aller au menu analyse
					{ 
						$idsession = $_POST['IDsession'];
						$resultatControleur = new ResultatControleur();
						$resultatControleur->analyse_veille($idsession);
					}
					else if($_POST['btn_gene']=='Table des Occurrences') // aller à la table des occurrences
					{ 
						$idsession = $_POST['IDsession'];
						$resultatControleur = new ResultatControleur();
						$resultatControleur->construiretable_occurence($idsession);
					}
					else if($_POST['btn_gene']=='Timeline') // aller à la timeline
					{ 
						$idsession = $_POST['IDsession'];
						$resultatControleur = new ResultatControleur();
						$resultatControleur->affiche_timeline($idsession);
					}
					else if($_POST['btn_gene']=='Arborescence') // aller à l'arborescence
					{ 
						$idsession = $_POST['IDsession'];
						$resultatControleur = new ResultatControleur();
						$resultatControleur->affiche_arborescence($idsession);
					}
				}
				//pour voir une sélection de références de la table classement
				else if (isset($_POST['sel_tb'])) {
					$idsession = $_POST['IDsession'];
					$ref = $_POST['sel_ref'];
					$referenceControleur = new ReferenceControleur();
					$referenceControleur->reference_selection($idsession,$ref);
				}
							
			}
					
			else if($this->getGetParam('page') === 'ajout_mtclf') // bouton pour gérer les sessions
            {
				//var_dump($_POST);
				if(isset($_POST['ajoutmtclf'])) {
					if(!empty($_POST['nveau_mtclf'])) {
						// insérer le nouveau mot-clef
						$idsession = $_POST['IDsession'];
						$libmtclf = $_POST['nveau_mtclf'];
						$sessionController = new SessionController();
						$sessionController->ajouter_mtclf($libmtclf, $idsession);
						$sessionController->ouvrir_session($idsession);
					}
					else
					{
						$this->redirect('index.php?erreur=5');
					}
				}
				// supprimer le mot-clef
				if(isset($_POST['supprimermtclf'])) {
					$idsession = $_POST['IDsession'];
					$idmtclf = $_POST['idmtclf'];
					$sessionController = new SessionController();
					$sessionController->supprimer_mtclf($idmtclf, $idsession);
					$sessionController->ouvrir_session($idsession);
				}
			}
			
			//le post lien rss
			else if($this->getGetParam('page') === 'ajout_lien') // bouton pour gérer les sessions
            {
				if(isset($_POST['ajoutlien'])) {
					if(!empty($_POST['nveau_lien'])) {
					$idsession = $_POST['IDsession'];
					$liblien = $_POST['nveau_lien'];
					$sessionController = new SessionController();
					$sessionController->ajouter_lien($liblien, $idsession);
					$sessionController->ouvrir_session($idsession);
					}
					else
					{
						$this->redirect('index.php?erreur=6');
					}
				}
				// supprimer le lien
				if(isset($_POST['supprimerlien'])) {
					$idsession = $_POST['IDsession'];
					$idlien = $_POST['idlien'];
					$sessionController = new SessionController();
					$sessionController->supprimer_lien($idlien, $idsession);
					$sessionController->ouvrir_session($idsession);
				}
				
			}
			
			//consulter une référence
			else if($this->getGetParam('page') === 'consulter_reference') // bouton pour gérer les sessions
            {
				$idsession = $_POST['IDsession'];
				$idreference = $_POST['IDreference'];
				$tc = unserialize( base64_decode( $_POST['selection'] ) );
				$referenceControleur = new ReferenceControleur();
				if(!isset($chemin)) {$chemin= null;}
				if(!isset($export)) {$export= null;}
				$referenceControleur->affiche_session($idsession,$idreference,$tc,$chemin,$export);
			}
			
			
			//éditer une référence ///////////////////////////////////
			else if(($this->getGetParam('page') === 'edition_reference')||($this->getGetParam('page') === 'edition_ttreference')) // bouton pour gérer les sessions
            {
				//Changement d'un mot-clef d'une référence
				if(isset($_POST['change_mtclf'])) {
					$idreference = $_POST['IDreference'];
					$idmtclf = $_POST['combo_mtclf'];
					$idsession = $_POST['IDsession'];
					$tc = unserialize( base64_decode( $_POST['selection'] ) );
					$referenceControleur = new ReferenceControleur();
					$referenceControleur->changer_mtclf($idreference,$idmtclf,$idsession);
					if(!isset($chemin)) {$chemin= null;}
					if(!isset($export)) {$export= null;}
					$referenceControleur->affiche_session($idsession,$idreference,$tc,$chemin,$export);
				}
				
				//Changement de titre d'une référence
				if(isset($_POST['valid_nouveau_titre'])) {
					$idreference = $_POST['IDreference'];
					$nvtitre = $_POST['nouveau_titre'];
					$idsession = $_POST['IDsession'];
					if($nvtitre!='') {
						$referenceControleur = new ReferenceControleur();
						$referenceControleur->modifier_titre($idsession,$idreference,$nvtitre);
						$tc = unserialize( base64_decode( $_POST['selection'] ) );
						if(!isset($chemin)) {$chemin= null;}
						if(!isset($export)) {$export= null;}
						$referenceControleur->affiche_session($idsession,$idreference,$tc,$chemin,$export);
					}
				}
				
				//Changement de description d'une référence
				if(isset($_POST['valid_nouveau_description'])) {
					$idreference = $_POST['IDreference'];
					$nvdesc = $_POST['nouveau_description'];
					$idsession = $_POST['IDsession'];
					$referenceControleur = new ReferenceControleur();
					$referenceControleur->modifier_description($idsession,$idreference,$nvdesc);
					$tc = unserialize( base64_decode( $_POST['selection'] ) );
					if(!isset($chemin)) {$chemin= null;}
					if(!isset($export)) {$export= null;}
					$referenceControleur->affiche_session($idsession,$idreference,$tc,$chemin,$export);
					}
					
				//Ajout d'une nouvelle occurence
				if(isset($_POST['valid_nouveau_occurence'])) {
					$idreference = $_POST['IDreference'];
					$idsession = $_POST['IDsession'];
					$nvocc = ucwords($_POST['nouveau_occurence']);
					if($nvocc=='') {
						$cbocc = $_POST['combo_occurence'];
						if ($cbocc!='') {
							$referenceControleur = new ReferenceControleur();
							$referenceControleur->ajouter_occurence($idsession,$idreference,$cbocc);
							$tc = unserialize( base64_decode( $_POST['selection'] ) );
							if(!isset($chemin)) {$chemin= null;}
							if(!isset($export)) {$export= null;}
							$referenceControleur->affiche_session($idsession,$idreference,$tc,$chemin,$export);
						}
						else
						{
							$this->redirect('index.php?erreur=7');
						}
					}
					else
					{
						$referenceControleur = new ReferenceControleur();
						$referenceControleur->ajouter_occurence($idsession,$idreference,$nvocc);
						$tc = unserialize( base64_decode( $_POST['selection'] ) );
						if(!isset($chemin)) {$chemin= null;}
						if(!isset($export)) {$export= null;}
						$referenceControleur->affiche_session($idsession,$idreference,$tc,$chemin,$export);
					}
				}
				
				//Suppression d'une occurence pour une référence
				if(isset($_POST['supprimer_occurence'])) {
					$idoccurence = $_POST['IDocc'];
					$idreference = $_POST['IDreference'];
					$idsession = $_POST['IDsession'];
					$referenceControleur = new ReferenceControleur();
					$referenceControleur->supprimer_occurence($idsession, $idreference, $idoccurence);
					$tc = unserialize( base64_decode( $_POST['selection'] ) );
					if(!isset($chemin)) {$chemin= null;}
					if(!isset($export)) {$export= null;}
					$referenceControleur->affiche_session($idsession,$idreference,$tc,$chemin,$export);
				}
				
				//Supprimer une référence
				if(isset($_POST['supprime_reference'])) {
					$idreference = $_POST['IDreference'];
					$idsession = $_POST['IDsession'];
					$referenceControleur = new ReferenceControleur();
					$referenceControleur->supprime_reference($idsession,$idreference);
					$tc = unserialize( base64_decode( $_POST['selection'] ) );
					$testid=0; // pour gérer le rafraichissement de la page en enlevant la référence supprimée
					foreach($tc as $key) {
						if($key["IDreference"]==$idreference) {unset($tc[$testid]);}
						$testid++;
					}
					$idreference =0;
					if(!isset($chemin)) {$chemin= null;}
					if(!isset($export)) {$export= null;}
					$referenceControleur->affiche_session($idsession,$idreference,$tc,$chemin,$export);
				}
				
				//Désactiver une référence
				if(isset($_POST['desactive_reference'])) {
					$idreference = $_POST['IDreference'];
					$idsession = $_POST['IDsession'];
					$referenceControleur = new ReferenceControleur();
					$referenceControleur->activation_reference($idsession,$idreference);
					$tc = unserialize( base64_decode( $_POST['selection'] ) );
					$testid=0; // pour gérer le rafraichissement de la page en enlevant la référence désactivée
					foreach($tc as $key) {
						if($key["IDreference"]==$idreference) {unset($tc[$testid]);}
						$testid++;
					}
					$idreference =0;
					if(!isset($chemin)) {$chemin= null;}
					if(!isset($export)) {$export= null;}
					$referenceControleur->affiche_session($idsession,$idreference,$tc,$chemin,$export);
				}
				
				// gérer les exports uniques
				//format texte
				if(isset($_POST['exp_txt'])) {
					if($_POST['export']) {$export = $_POST['export'];} else {$export = null;}
					if(isset($_POST['IDreference'])) {$idreference = $_POST['IDreference'];} else {$idreference = null;}
					if($_POST['selection']) {$tc = unserialize( base64_decode( $_POST['selection'] ) );} else {$tc=null;}
					$idsession = $_POST['IDsession'];
					if($export!=null) {
						$referenceControleur = new ReferenceControleur();
						$referenceControleur->exporterTxt($export,$idsession,$idreference,$tc);
					}
					else
					{
						$this->redirect('index.php?erreur=8');
					}
			
				}
		
				//format pdf
				if(isset($_POST['exp_pdf'])) {
					if($_POST['export']) {$export = $_POST['export'];} else {$export = null;}
					if(isset($_POST['IDreference'])) {$idreference = $_POST['IDreference'];} else {$idreference = null;}
					if($_POST['selection']) {$tc = unserialize( base64_decode( $_POST['selection'] ) );} else {$tc=null;}
					$idsession = $_POST['IDsession'];
					if($export!=null) {
						$referenceControleur = new ReferenceControleur();
						$referenceControleur->exporterPdf($export,$idsession,$idreference,$tc);
					}
					else
					{
						$this->redirect('index.php?erreur=8');
					}
			
				}
	
				//format csv
				if(isset($_POST['exp_csv'])) {
					if($_POST['export']) {$export = $_POST['export'];} else {$export = null;}
					if(isset($_POST['IDreference'])) {$idreference = $_POST['IDreference'];} else {$idreference = null;}
					if($_POST['selection']) {$tc = unserialize( base64_decode( $_POST['selection'] ) );} else {$tc=null;}
					$idsession = $_POST['IDsession'];
					if($export!=null) {
						$referenceControleur = new ReferenceControleur();
						$referenceControleur->exporterCsv($export,$idsession,$idreference,$tc);
					}
					else
					{
						$this->redirect('index.php?erreur=8');
					}
				}
			}
			
			else if($this->getGetParam('page') === 'menu_recherchesession') // Pour la page recherche
            { 
				//var_dump($_POST);
				if($_POST['btn_gene']=='retour accueil') { // revenir à l'accueil
					$id = $this->getSessionParam('id');
					$accueilControleur = new AccueilControleur();
					$accueilControleur->accueil($id);
				}
				
				if($_POST['btn_gene']=='retour thématique') // revenir au menu de la session
				{ 
					$idsession = $_POST['IDsession'];
					$sessionController = new SessionController();
					$sessionController->ouvrir_session($idsession);
				}
			}
			else if($this->getGetParam('page') === 'recherchesession') // Pour la page recherche
            {	
				//Valider la recherche des références désactivées
				if(isset($_POST['valid_desactive'])) {
					$idsession = $_POST['IDsession'];
					$referenceControleur = new ReferenceControleur();
					$referenceControleur->liste_desactive($idsession);
				}
				
				//Valider la recherche des références sans occurrences
				if(isset($_POST['valid_ss_occ'])) {
					$idsession = $_POST['IDsession'];
					$referenceControleur = new ReferenceControleur();
					$referenceControleur->liste_ss_occ($idsession);
				}
				
				//Recherche par le menu texte et date + combo
				if(isset($_POST['valid_recherche'])) {
					$id = $_POST['IDsession'];
			 
					 //recherche dans le titre et la description
					 if(isset($_POST['titre'])) {$mot = $_POST['titre'];} else {$mot=null;}
					 
					 //recherche par date
						if((isset($_POST['datedeb']))&&(!empty($_POST['datedeb']))) {$newdebut = $_POST['datedeb'];} else {$newdebut=null;}
						if((isset($_POST['datefin']))&&(!empty($_POST['datefin']))) {$newfin = $_POST['datefin'];} else {$newfin=null;}
						if($newfin<$newdebut) {$temp=$newdebut;$newdebut=$newfin;$newfin=$temp;}
				
					//recherche par mot-clef parent
			
						if((isset($_POST['parent']))&&($_POST['parent']!=null)) {
							$liste_parent = "";
							for($u=0;$u<count($_POST['parent']);$u++) {
								if($_POST['parent'][$u]!=null) {
								$liste_parent .= ", ".$_POST['parent'][$u];
								}
							}
							$parents = "(".substr($liste_parent,2).")";
						}
						else {$parents=null;}
			
					// recherche par occurence
						if((isset($_POST['occurrence']))&&($_POST['occurrence']!=null)) {
							$liste_occ = "";
							for($u=0;$u<count($_POST['occurrence']);$u++) {
								if($_POST['occurrence'][$u]!=null) {
								$liste_occ .= ", '".$_POST['occurrence'][$u]."'";
								}
							}
							$occs = "(".substr($liste_occ,2).")";
						}
						else {$occs=null;}

				//envoi final de la recherche pour construction de la requete
					$referenceControleur = new ReferenceControleur();
					$referenceControleur->prepare_recherche($id,$mot,$newdebut,$newfin,$parents,$occs);
				}
				
			}
	
		}
	}
	
}

?>