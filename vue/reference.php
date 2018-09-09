

<h2>Thématique de la veille : <?php echo $donnees['session'][0]['lib_session']." (".$donnees['nbref'][0]['NBref']." ref. tot.)"; ?> </h2>

<hr>
<?php include('menu_reference.php'); ?>

	<!-- table complete des références -->
<fieldset style="width : auto;display : inline-block;float : left;">


<h4 id="compteur">Références contenues dans la veille : </h4>



<div id="table_consultation">
	<div id="collectif_consultation">
		<table>
			<tr>
				<th style="width : 100px"><span class="label_orange">Date</span></th>
				<th style="width : 200px"><span class="label_orange">Titre</span></th>
				<th style="width : 150px"><span class="label_orange">Mot-Clef</span></th>
				<th style="width : 50px"></th>
			</tr>
			
			<?php 
				
				$cpt_result = 0;
				if(isset($donnees['controle'])) {
					$allreference = $donnees['controle'];
				}
				else
				{
					$allreference =$donnees['references'];
				}
				
				foreach($allreference as $ref):
				$cpt_result++;
				$date = $ref['date_reference'];
				$datemaj = date_create($date, timezone_open('Europe/Paris'));
				$datemajx = date_format($datemaj, 'd-m-Y');
				?>
					<tr>
						<td style="font-size : small;"><?= $datemajx ?></td>
						<td style="width : 250px"><?= $ref['lib_reference'] ?></td>
						<td><?= $ref['lib_mtclf'] ?></td>
						<td style="vertical-align: center;background : transparent;padding : 2% 1% 0% 1%;"><form method="POST" name="consulter_reference" action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=consulter_reference"><input type="hidden" name="IDreference" value="<?php echo $ref['IDreference']; ?>"><input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>"><input type = "hidden" name = "selection" value = "<?php echo base64_encode( serialize( $allreference ) ); ?>"><button type="submit" name="detail_reference"><img src="../web/images/voir.png"  alt="voir"></button></form></td>
					</tr>
			<?php endforeach; ?>
		</table>

	</div>

<form method="POST" name="edition_ttreference" action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=edition_ttreference">
<input type = "hidden" name = "selection" value = "<?php echo base64_encode( serialize( $allreference ) ); ?>">
<input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>">
<p class="menu_ex">
<input type = "hidden" name = "export" value = "tous">
<input type='button' value='exporter' style ='width : 17%;height : 3%;color : #F5A320;background: #717475;' onClick='ttshow()'>
<div id="progression"></div>
<div id="ttexport">
<hr>
<input type="submit" name="exp_txt" value="Format Txt" style ='width : auto;height : 2.8%;color : #F5A320;background: #717475;'>
<input type="submit" name="exp_pdf" value="Format Pdf" style ='width : auto;height : 2.8%;color : #F5A320;background: #717475;'>
<input type="submit" name="exp_csv" value="Format Csv" style ='width : auto;height : 2.8%;color : #F5A320;background: #717475;'>
</div>
</p>

<?php if((isset($donnees['chemin']))&&($export=="tous")) {echo "<button id='telec'><a href='".$donnees['chemin']."' download='".$donnees['chemin']."'>Télécharger</a></button>";} //bouton pour le téléchargement de l'export multiple le cas échéant ?>
</form>
</div>

</fieldset>



<form method="POST" name="edition_reference" action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=edition_reference">
<input type = "hidden" name = "selection" value = "<?php echo base64_encode( serialize( $allreference ) ); ?>">
<input type="hidden" name="IDreference" value="<?php echo $donnees['detailref'][0]['IDreference']; ?>">
<input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>">
	<!-- table détail des références -->
<fieldset>
<?php if(!empty($donnees['detailref'][0]['IDreference'])) { ?><h4>Référence n°<?php echo $donnees['detailref'][0]['IDreference']; ?> : <button><a href="<?php echo $donnees['detailref'][0]['url_reference'] ?>" target="_blank" >Voir</a></button></h4><?php } ?>

<?php if ($donnees['detailref'][0]['IDreference']!=null) {
	$date = $donnees['detailref'][0]['date_reference'];
	$datemaj = date_create($date, timezone_open('Europe/Paris'));
	$datemajx = date_format($datemaj, 'd-m-Y');
	?>
<h5><span class="label_orange">Titre : </span><?php echo $donnees['detailref'][0]['lib_reference']."<br>"; ?>
<span class="label_orange">Date : </span><?php echo $datemajx; ?></h5>

<h6 style="width : 90%;text-align : justify;"><span class="label_orange">Description : </span><?php echo $donnees['detailref'][0]['description_reference']; ?></h6>

<hr>




<?php
	$ratiomtclf = substr((intval($donnees['ratioparent'][0]['freqmtclf'])/intval($donnees['nbref'][0]['NBref']))*100,0,5);
	$ratioref = (intval($donnees['ratioparent'][0]['freqmtclf'])/intval($donnees['nbref'][0]['NBref']));
?>



<table id="table_occ_reference">
	<tr>
		<th>Rang</th><th>Terme/Occurrence</th><th>Ratio</th><th>Action</th>
	</tr>
	
	<!-- PARENT -->
	<tr>
		<td ><span class="label_orange">parent</span></td><td><?php echo $donnees['detailref'][0]['lib_mtclf']; ?></td><td><?php echo $ratiomtclf." %"; ?></td>
		<td><select name="combo_mtclf">
				<?php foreach ($donnees['combomtclf'] as $mt):?>
					<option value="<?= $mt['IDmtclf'] ?>"><?= $mt['nom'] ?> (<?= $mt['FREQUENCE'] ?> ref.)</option>
				<?php endforeach; ?>
			</select>
			<input type="submit" name="change_mtclf" value="Modifier">
			</td>
	</tr>
	
	<!-- ENFANTs et autres -->
	<?php 
	$step = 1;
		foreach ($donnees['occref'] as $occ) {
			if($step==1) {$rang = "enfant";$ratioref = $ratioref + ($occ['FREQUENCE']/$occ['total']);} else {if ($step==2){$rang = "petit-enfant";$ratioref = $ratioref + ($occ['FREQUENCE']/$occ['total']);} else {$rang = "autres";}}
			$step++;  
			$ratio = substr((($occ['FREQUENCE']/$occ['total'])*100),0,5);
			?>
			<tr>
				<td ><span class="label_orange"><?php echo $rang; ?></span></td><td><?php echo $occ['nom']; ?></td><td><?php echo $ratio." %"; ?></td>
				<td style="text-align : left; vertical-align: center"><form METHOD="POST" name="IDoccurence[<?php echo $step; ?>]" action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=edition_reference"><input type="hidden" name="IDreference" value="<?php echo $donnees['detailref'][0]['IDreference']; ?>"><input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>"><input type="hidden" name="IDocc" value="<?php echo $occ['IDoccurence']; ?>"><input type = "hidden" name = "selection" value = "<?php echo base64_encode( serialize( $allreference ) ); ?>"><input type="submit" name="supprimer_occurence" value="-"></form></td>
				
				
			</tr>
		<?php		
		}?>
			<tr>
				<td colspan="2">Ratio moyen</td><td><span class="label_orange"><?php if($step==1) {$step=1;} else {if($step>2) {$step=3;}} echo substr(($ratioref/$step)*100,0,5)." %"; ?></span></td><td></td>
			<tr>
			</tr>
				<td colspan="2">Ratio pondéré</td><td><span style="color:red;"><?php echo substr(($ratioref/3)*100,0,5)." %"; ?></span></td><td></td>
			</tr>
</table>

<!-- Changement du titre de la reference -->
<p><input type="text" name="nouveau_titre" Placeholder="Nouveau titre..." maxlength="248">
<input type="submit" name="valid_nouveau_titre" value="Modifier"></p>

<!-- Changement de la description de la reference -->
<p><textarea name="nouveau_description"  Placeholder="Nouvelle description..." maxlength="398" rows="4" cols="50"></textarea>
<input type="submit" name="valid_nouveau_description" value="Modifier"></p>

<!-- Changement des occurences -->
<p><input type="text" name="nouveau_occurence"  Placeholder="Nouvelle occurrence..." maxlength="30">
<select name="combo_occurence">
<option value=""></option>
				<?php foreach ($donnees['comboccurence'] as $occ):?>
					<option value="<?= $occ['lib_occurrence'] ?>"><?= $occ['lib_occurrence'] ?> (<?= $occ['frequence'] ?> ref.)</option>
				<?php endforeach; ?>
</select>
<input type="submit" name="valid_nouveau_occurence" value="Ajouter"></p>
</p>
 <hr>
<?php if($donnees['detailref'][0]['validation_reference']==0) {$libel="Activer";} else {$libel="Désactiver";} // gestion du libellé du bouton actif/inactif ?> 
<p class="menu_ex">
<input type = "hidden" name = "export" value = "unique">
<input type='button' value='exporter' style ='width : 17%;height : 3%;color : #F5A320;background: #717475;' onClick='show()'>
<input type="submit" name="desactive_reference" value="<?php echo $libel; ?>">
<input type="submit" name="supprime_reference" value="Supprimer">
<div id="progression"></div>
<div id="export">
<hr>
<input type="submit" name="exp_txt" value="Format Txt" style ='width : 12%;height : 2.8%;color : #F5A320;background: #717475;'>
<input type="submit" name="exp_pdf" value="Format Pdf" style ='width : 12%;height : 2.8%;color : #F5A320;background: #717475;'>
<input type="submit" name="exp_csv" value="Format Csv" style ='width : 12%;height : 2.8%;color : #F5A320;background: #717475;'>
</div>
</p>
<?php if((isset($donnees['chemin']))&&($export=="unique")) {echo "<button id='telec'><a href='".$donnees['chemin']."' download='".$donnees['chemin']."'>Télécharger</a></button>";} //bouton pour le téléchargement de l'export unique le cas échéant ?>



</form>
<?php } ?>
</fieldset>

<script type="text/javascript">
var test = <?php echo $cpt_result; ?>;
document.getElementById("compteur").innerHTML = test+" résultats";

function show(){

if (document.getElementById("export").style.display == "block")	{	document.getElementById("export").style.display= 'none';document.getElementById("telec").style.display= 'none';	}
	else {	document.getElementById("export").style.display= 'block';	}
		
}

function ttshow(){

if (document.getElementById("ttexport").style.display == "block")	{	document.getElementById("ttexport").style.display= 'none';document.getElementById("telec").style.display= 'none';	}
	else {	document.getElementById("ttexport").style.display= 'block';	}
		
}


</script>