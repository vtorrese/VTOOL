
<h2>Thématique de la veille : <?php echo $donnees['session'][0]['lib_session']; ?> </h2>

<hr>

<?php include('menu_session.php'); ?>


<fieldset id="tdbsession">
<h4><b>Tableau de bord</b></h4>
<?php echo "<span class='label_orange'>".$donnees['nbref'][0]['NBref']." référence(s) active(s)</span><br>"; 
echo "<p>".$donnees['nbmtclf'][0]['NBmtclf']." mot-clefs<br>";
echo $donnees['nblien'][0]['NBlien']." flux RSS<br>";
echo "1 moteur de recherche (Google)</p>";
$creation = substr($donnees['datex']['0']['creation'],8,2)."/".substr($donnees['datex']['0']['creation'],5,2)."/".substr($donnees['datex']['0']['creation'],0,4);
$fin = substr($donnees['datex']['0']['fin'],8,2)."/".substr($donnees['datex']['0']['fin'],5,2)."/".substr($donnees['datex']['0']['fin'],0,4);
$nbmaj = $donnees['maj'][0]['NBmaj'];
$datedernmaj = substr($donnees['maj'][0]['datemaj'],8,2)."/".substr($donnees['maj'][0]['datemaj'],5,2)."/".substr($donnees['maj'][0]['datemaj'],0,4)." ".substr($donnees['maj'][0]['datemaj'],11);
if (($donnees['nbref'][0]['NBref']>0)&& ($nbmaj>0)){$refparmaj = substr($donnees['nbref'][0]['NBref']/$nbmaj,0,3);} else {$refparmaj = 0;}
?>

<span class="label_orange">Création : </span><?php echo $creation; ?><br>
<span class="label_orange">Dern. ref: </span><?php echo $fin; ?><br>
<span class="label_orange">Nb MAJ: </span><?php echo $nbmaj; ?><br>
<span class="label_orange">Dern. maj: </span><?php echo $datedernmaj; ?><br>
<span class="label_orange">Nb ref moy./maj: </span><?php echo $refparmaj; ?><br>

</fieldset>


<fieldset>
<h4>Modalités et critères de recherche associés à la thématique :</h4>
<div id="table_mtclf">
<form method="POST" name="ajout_mtclf" action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=ajout_mtclf">
<input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>">
<table>
	<tr>
		<th><span class="label_orange">Mot-Clef</span></th><th><input type="text" name="nveau_mtclf" Placeholder="Nouveau..."><input type="submit" name="ajoutmtclf" value="+"></th>
	</tr>
<?php 

foreach ($donnees['mtclf'] as $mc):

    ?>
<tr><td><?= $mc['lib_mtclf'] ?></td><td style="text-align : right"><form method="POST" name="<?= $mc['lib_mtclf'] ?>" action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=ajout_mtclf"><input type="hidden" name="idmtclf" value="<?= $mc['IDmtclf'] ?>"><input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>"><input type="submit" name="supprimermtclf" value="-"></td></tr></form>

<?php endforeach; ?>
</table>
</form>
</div>

<div id="table_lien" >
<form method="POST" name="ajout_lien"  action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=ajout_lien">
<input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>">
<table>

	<tr>
		<th><span class="label_orange">Flux RSS</span></th><th><input type="text" name="nveau_lien" Placeholder="Flux rss..."><input type="submit" name="ajoutlien" value="+"></th>
	</tr>
<?php 

foreach ($donnees['lien'] as $ln):

    ?>
<tr><td><?= $ln['url_lien'] ?></td><td style="text-align : right"><form method="POST" name="<?= $ln['url_lien'] ?>" action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=ajout_lien"><input type="hidden" name="idlien" value="<?= $ln['IDlien'] ?>"><input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>"><input type="submit" name="supprimerlien" value="-"></td></tr></form>

<?php endforeach; ?>
</table>
</form>
</div>

<!-- <div id="table_moteur">
<form method="POST" name="ajout_moteur">
<input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>">
<table>

	<tr>
		<th><span class="label_orange">Moteur</span></th>
		<th><select name="combomoteur">
				<?php foreach ($allmoteur as $mt):?>
					<option value="<?= $mt['IDmoteur'] ?>"><?= $mt['lib_moteur'] ?></option>
				<?php endforeach; ?>
			</select>
		<input type="submit" name="ajoutmoteur" value="+"></th>
	</tr>
<?php  

foreach ($moteur as $mt):

    ?>
<tr><td><?= $mt['lib_moteur'] ?></td><td style="text-align : right"><form method="POST" name="<?= $mt['url_moteur'] ?>"><input type="hidden" name="idmoteur" value="<?= $mt['IDmoteur'] ?>"><input type="hidden" name="IDsession" value="<?php echo $session['IDsession']; ?>"><input type="submit" name="supprimermoteur" value="-"></td></tr></form>

<?php endforeach; ?>
</table>
</form>
</div> -->
</fieldset>
