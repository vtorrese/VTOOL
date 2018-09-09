<h2>Thématique de la veille : <?php echo $donnees['session'][0]['lib_session']." (".$donnees['nbref'][0]['NBref']." ref. tot.)"; ?> </h2>

<hr>
<?php include('menu_resultat.php'); ?>

<?php if($ecran!=0) { ?>
	
	<fieldset>
	
	<?php if($ecran==1) { ?>
	<!-- Affichage de l'arborescence -->
	<h4>Arborescence de la veille <span style="font-size:small;">(Durée de création = <?php echo substr($donnees['timer'],0,6); ?> sec)</span> : </h4>

	<p><?php include("arborescence.php");} ?></p>
	
	<?php if($ecran==2) { ?>
	<!-- Affichage de la timeline -->
	<h4>Timeline de la veille <span style="font-size:small;">(Durée de création = <?php echo substr($donnees['timer'],0,6); ?> sec)</span> : </h4>
	<?php 
	include("timeline.php");
	} ?>
	
	<?php if($ecran==3) { ?>
	<!-- Affichage de la table des occurences -->
	<h4>Table des occurrences <span style="font-size:small;">(Durée de création = <?php echo substr($donnees['timer'],0,6); ?> sec)</span> : </h4>
	<?php 
	include("tableau_occurrence.php");
	} ?>
	
	<?php if($ecran==4) { ?>
	<!-- Affichage de l'analyse de la veille -->
	<h4>Analyse de la structure de la veille</h4>
	
	<!-- Affichage du graphique historique des références -->
	<?php 
	if($data['cptermaj'][0]['NBmaj']>2) { // s'il y a plus de deux maj
	include("graphique_histo.php");
	}
	
		// On transforme les 2 dates en timestamp
	$date1 = strtotime($data['dater'][0]['creation']);
	$date2 = strtotime($data['dater'][0]['fin']);
 
	// On récupère la différence de timestamp entre les 2 précédents
	$nbJoursTimestamp = $date2 - $date1;
	 
	// ** Pour convertir le timestamp (exprimé en secondes) en jours **
	// 60 secondes * 60 minutes et que 1 jour = 24 heures donc :
	$nbJours = intval($nbJoursTimestamp/86400); // 86 400 = 60*60*24
 
	//Calcul du taux d'actualisation de la veille
	if($nbJours<$data['cptermaj'][0]['NBmaj']) {
		$nbmaj = (($data['cptermaj'][0]['NBmaj'])/$nbJours);
		$nbj = "";
		$conseilmaj = "Votre rythme est trop élevé. Espacez vos mises à jour ";
		}
		else
		{
		$nbmaj = 1;
		$nbj = ($nbJours/($data['cptermaj'][0]['NBmaj']));
			if($nbj>30) {
				$conseilmaj = "Votre rythme est trop faible. Effectuez vos mises à jour au moins une fois par mois";
			}
			else
			{
				$conseilmaj = "Votre rythme est correct.";
			}
		}
	
	//Calcul des flux rss sous utilisés
	$cpt = 0;
	$cpteur=0;
	foreach($data['txlien'] as $lien) {
		$cpteur++;

		$nbtheoriquelien = substr($data['compter'][0]['NBref']/$lien['NB'],0,4);
		if(intval($lien['NB'])<intval($nbtheoriquelien)) {
			$cpt++;
			$conseilien = " flux RSS ne récoltent pas assez de références.";
		}
	}
	$txlien = ($cpt/$cpteur)*100;
	
	
	if(isset($conseilien)) {$conseilien = $txlien. " % de vos ".$conseilien;}
	
	
	//Calcul des mots-clés sous utilisés
	$cpt = 0;
	foreach($data['txmtclf'] as $mtc) {
		if($mtc['FREQUENCE']!=null) {
			$nbtheoriquemtclf = substr($data['compter'][0]['NBref']/$mtc['FREQUENCE'],0,4);
			if($mtc['FREQUENCE']<$nbtheoriquemtclf) {
				$conseilmtc = " mots-clés ne récoltent pas assez de références.";
				}
				else
				{
				$cpt++;
				}
			}
	}
	$txmtc = substr((1-($cpt/$data['nbmtclf'][0]['NBmtclf']))*100,0,4);
	if(isset($conseilmtc)) {$conseilmtc = $txmtc. " % de vos ".$conseilmtc;}
	
	
	?>
	<p>
	Votre veille contient <span class="label_orange"><?php echo $data['compter'][0]['NBref']; ?></span> références actives (et <?php echo ($data['toutcompter'][0]['NBreftot'])-($data['compter'][0]['NBref']); ?> inactives), collectées avec <span class="label_orange"><?php echo $data['cptermaj'][0]['NBmaj']; ?></span> mises à jour (soit env. <?php echo substr(($data['compter'][0]['NBref'])/($data['cptermaj'][0]['NBmaj']),0,4); ?> réf/maj). <br>Vous avez effectué <span class="label_orange"><?php echo substr($nbmaj,0,4); ?></span> maj/ <span class="label_orange"><?php echo substr($nbj,0,4); ?></span> jours (sur une période de <?php echo $nbJours; ?> jrs).
	</p>
	
	<p>
	Actuellement, la mise à jour s'effectue sur un panel de <span class="label_orange"><?php echo $data['nblien'][0]['NBlien']; ?></span> flux RSS, <span class="label_orange">1</span> moteur de recherche, avec un filtrage sur <span class="label_orange"><?php echo $data['nbmtclf'][0]['NBmtclf']; ?></span> mots-clefs.
	</p>
	

	<!-- Affichage des camenberts flux rss et mot clé -->
	<?php 
	if(($data['nblien'][0]['NBlien']>0) && ($data['nbmtclf'][0]['NBmtclf']>0)) { // s'il y a des flux rss et des mot-clés
	include("camembert_session.php");
	}	?>
	
	<div id="progression">
	<hr>
	
	<h4>Conseils d'optimisation de la veille</h4>
	
	<p>Fréquence de vos mises à jour : <span class="label_orange"><?php echo $conseilmaj; ?></span></p>
	<p>Efficience de vos flux RSS : <span class="label_orange"><?php echo $conseilien; ?></span></p>
	<p>Efficience de vos mots-clés : <span class="label_orange"><?php echo $conseilmtc; ?></span></p>
<?php 

	/*$performance = substr((($data['compter']['NBref']/$data['nblien']['NBlien'])/$data['nbmtclf']['NBmtclf'])*100,0,4);*/
	$performance = substr(($data['compter'][0]['NBref']/(($data['nblien'][0]['NBlien']*$data['nbmtclf'][0]['NBmtclf'])*$data['cptermaj'][0]['NBmaj']))*100,0,4);
	if($performance<100) {
	if($data['nblien'][0]['NBlien']>$data['nbmtclf'][0]['NBmtclf']) {
		$conseilstructure = "Modifiez votre panel de flux RSS";
	}
	else
	{
		$conseilstructure = "Modifiez votre panel de mots-clefs";
	}
	}
	else
	{
		$conseilstructure = "Configuration efficiente";
	}
 ?>
	<p>Performance actuelle de votre configuration Flux RSS/Mots-clés : <span class="label_orange"><?php echo $performance; ?> %</span>
	</p>
	
	<?php include("tableau_performance.php"); ?>
	
	<p>Proposition : <span class="label_orange"><?php echo $conseilstructure; ?></span>
	</p>
	</div>
	
	
	<?php }	?>

	
	
	</fieldset>


<?php } ?>