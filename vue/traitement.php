<h2>Thématique de la veille : <?php echo $donnees['session'][0]['lib_session']; ?> </h2>

<hr>
<?php include('menu_maj.php'); ?>

<fieldset>
<label><span class="label_orange">Nouvelle référence</span></label>

<?php
foreach ($reference as $ref):
$url = $ref['url_maj'];
$IDtemp = $ref['IDtemporaire'];
?>
<button><a href="<?php echo $url; ?>" target="_blank">Visiter</a></button>
<hr>
<span class="label_orange">Titre : </span><?php echo $ref['lib_maj']."  "; 
$datemaj = date_create('', timezone_open('Europe/Paris'));
$datemajx = date_format($datemaj, 'd-m-Y');?>
<span class="label_orange">Date : </span><?php echo $datemajx."  "; ?><span class="label_orange">Mot-Clef : </span><?php echo $ref['mtclf_maj']."  ";?><br>
<span class="label_orange">Desc : </span><span style="font-size : small;"><?php echo $ref['description_maj']."  ";?></span><hr>
 <?php
 endforeach; ?>

<!-- table des propositions des occurences -->

<div id="table_occurence">
<label><span class="label_orange">Occurrences : </span></label>
<form method='POST' name='form_temp_occurrence'>
<table name='occurence'>
	<tr>
		<th>termes</th><th>à conserver</th>
	</tr>
	<tr>
<?php foreach($occurrence as $key => $result) {
	 echo "<tr>";
	 echo "<td>$result</td>";
	 echo "<td><input type='checkbox' name='tab[$key]' value='$result'></td>";
	 echo "</tr>";
 }
echo "</table>"; ?>
</div>
</div>

<div class="menu_ex"><input type="hidden" name="IDtemporaire" value="<?php echo $IDtemp; ?>"><input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>"><input type="submit" name="valid_new_ref" value="validation"></div></form>

<!-- <div style="float : left; margin : 1% 0% 0% 1%;"><iframe sandbox src="https://www.google.fr" width="400" height="400"></iframe></div> -->


</fieldset>