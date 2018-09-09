<?php //var_dump($donnees); ?>

<h2>Thématique de la veille : <?php echo $donnees['session'][0]['lib_session']." (".$donnees['nbref'][0]['NBref']." ref. tot.)"; ?> </h2>

<hr>
<?php include('menu_maj.php'); ?>


<!-- formulaire de recherche -->
<fieldset>
<h4>Rechercher dans la veille : </h4>
<form method="POST" name="form_recherche" action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=recherchesession">
<input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>">

<input type='submit' value='ref. désactivées' name="valid_desactive" style ='margin : 1% 1% 0% 0%;width : auto;height : auto;color : #F5A320;background: #717475;'>
<input type='submit' value='ref. ss occur.' name="valid_ss_occ" style ='margin : 1% 1% 0% 0%;width : auto;height : auto;color : #F5A320;background: #717475;'>
<hr>

<p><span class="label_orange">Titre, mots du titre, de la description : </span><input type="text" name="titre"></p>

<p><span class="label_orange">Date : du (le) </span><input type="date" name="datedeb"><span class="label_orange"> au </span><input type="date" name="datefin"></p>

<p><input type='button' value='parents' style ='margin : 0% 1% 0% 0%;width : 90px;height : auto;color : #F5A320;background: #717475;' onClick='show(this)'></p>

<div id="parents" name="parents">
<input id="allp" type="checkbox" onclick="cochpar('parent')" /><span style="font-size : small;"> cocher / decocher tout</span>

<ul class='simple'>


<?php

//les checkboxs des mots-clefs pour choix multiples

foreach($donnees['motclef'] as $item) {
	$codeparent = "parent[]";
	echo "<li><input type='checkbox' class='parent' name='".$codeparent."' value='".$item['IDmtclf']."' checked>".$item['lib_mtclf']."</li>";

}
?>
</ul>
</div>

<div id="progression"></div>


<p><input type='button' value='occurrences' style ='margin : 0% 1% 0% 0%;width : 90px;;height : auto;color : #F5A320;background: #717475;' onClick='show(this)'></p>

<div id='occurrences' name='occurrences'>
<input id="allo" type="checkbox" onclick="cochocc('occ')" /><span style="font-size : small;"> cocher / decocher tout</span>
<ul class='simple'>

<?php

//les checkboxs des occurences pour choix multiples
foreach($donnees['occurrence'] as $itemo) {
	$codeocc = "occurrence[]";
	echo "<li><input type='checkbox' class='occ' name='".$codeocc."' value='".$itemo['lib_occurrence']."' checked>".$itemo['lib_occurrence']."</li>";	
}
?>
</ul>
</div>
<div id="progression"></div>


<p style="float : right"><input type="submit" name="valid_recherche" value="Chercher"></p>
</form>
</fieldset>



<script>
function show($champ){
		
var champ = ($champ.value);
if (document.getElementById(champ).style.display == "block")	{	document.getElementById(champ).style.display= 'none';	}
	else {	document.getElementById(champ).style.display= 'block';	}
		
}

function cochpar(element){
 
    tableau_checkbox = document.getElementsByClassName(element);

 
    if (document.getElementById('allp').checked){
        for (var i = 0; i < tableau_checkbox.length; i++){
            tableau_checkbox[i].checked = true;
        }
    }
    else {
        for (var i = 0; i < tableau_checkbox.length; i++){
            tableau_checkbox[i].checked = false;
        }
    }
 
}

function cochocc(element){
 
    tableau_checkbox = document.getElementsByClassName(element);

 
    if (document.getElementById('allo').checked){
        for (var i = 0; i < tableau_checkbox.length; i++){
            tableau_checkbox[i].checked = true;
        }
    }
    else {
        for (var i = 0; i < tableau_checkbox.length; i++){
            tableau_checkbox[i].checked = false;
        }
    }
 
}

</script>