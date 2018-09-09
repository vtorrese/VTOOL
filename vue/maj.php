

<h2>Thématique de la veille : <?php echo $donnees['session'][0]['lib_session']; ?> </h2>
<hr>
<?php include('menu_maj.php'); ?>
<?php
//sous titre : date et heure de la mise à jour
$date = date_create('', timezone_open('Europe/Paris'));
$date = date_format($date, 'd-m-Y H:i');
echo "<h3>Mise à jour du $date -- ".$donnees['nbentrees']['nb']." nouvelle(s) ref.</h3>";
?>

<!-- Table des résultats de la mise à jour -->
<?php if(isset($entrees)) { //On n'affiche rien s'il n'y a pas de résultats ?>
<fieldset>
<div id="table_resultat">

<table>
<label><span class="label_orange">Nouvelles références (1/<?php echo $donnees['nbentrees']['nb']; ?>)</span></label>
	<tr>
		<th>Titre</th><th>Date</th><th>Description</th><th>Mot-clef</th><th>Validation</th>
	</tr>

<?php

$cpt=0;
foreach ($entrees as $ent):
	$cpt++;
	echo "<tr>";
	echo "<td style='width : 200px;'><a href='".$ent['url_maj']."' target='_blank'>".$ent['lib_maj']."</a></td>";
	$datemaj = date_create('', timezone_open('Europe/Paris'));
	$datemajx = date_format($datemaj, 'd-m-Y');
	echo "<td style='width : 80px;'>$datemajx</td>";
	echo "<td style='width : 550px;font-size : small;'>".$ent['description_maj']."</td>";
	echo "<td style='width : 80px;'>".$ent['mtclf_maj']."</td>";
	echo "<td><form method='POST' name='".$ent['url_maj']."'><input type='hidden' name='idtemporaire' value='".$ent['IDtemporaire']."'><input type='checkbox' name='$cpt'><input type='hidden' name='idtemp.$cpt' value='".$ent['IDtemporaire']."'><input type='hidden' name='compteur' value='$cpt'></td>";
	echo "</tr>";
?>

<?php endforeach;?>

</table>
</div>
<div class="menu_ex"><input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>"><input type="submit" name="valid_maj" value="validation"></div></form>
</fieldset>
<?php } ?>