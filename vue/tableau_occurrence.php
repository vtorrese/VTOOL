<script src="../web/librairies/js/Chart.js"></script>

<!-- Graphiques des occurences -->

<?php
$label = "";
$datx = "";
$cpteur = count($data['recupere_occ']);
$cpteur2 = 0;
foreach($data['recupere_occ'] as $occ) {
	
	if($occ['frequence']>1) {  // Ici paramétrage du graphique des occurrences réglé sur +1ref
	$label .= ",'".$occ['lib_occurrence']."'";
	$datx .= ",".$occ['frequence'];
	$cpteur2++;
	}
}
$label = substr($label,1);
$datx = substr($datx,1);
?>

<div class="chart-container">
<p>La session contient <span class="label_orange"><?php echo $cpteur; ?></span> occurrences#2 et #3 (dont <?php echo $cpteur2; ?> comprenant plus de 2 références).</p>

<!--<canvas id="graphique_occ" style="position: relative; height:20vh; width:60vw;"></canvas>-->

    <canvas id="graphique_occ"></canvas>
</div>



<p>Classement par occurrences hiérarchisées</p>
<?php 


for($a=1;$a<count($data['recup_toute_occ']);$a++) {
	
	$b=$a-1;
	$controle = $data['recup_toute_occ'][$a]['parent'].$data['recup_toute_occ'][$a]['enfant'].$data['recup_toute_occ'][$a]['petitenfant'];
	$controle2 = $data['recup_toute_occ'][$b]['parent'].$data['recup_toute_occ'][$b]['enfant'].$data['recup_toute_occ'][$b]['petitenfant']; 
	$idref = $data['recup_toute_occ'][$a]['IDreference'];
	if($controle===$controle2) {$test="oui";} else {$test="non";}
	if($test=="non") {$refe = $idref;} else {$refe.= ",".$data['recup_toute_occ'][$a]['IDreference'];}
	$idg[$controle] = array('parent' => $data['recup_toute_occ'][$a]['parent'],'enfant' => $data['recup_toute_occ'][$a]['enfant'],'petitenfant' => $data['recup_toute_occ'][$a]['petitenfant'], 'reference' => $refe);
}	

?>
<div id="table_occ">
<div>
<table>
<tr>
	<th>Occurrence#1 (Parent)</th>
	<th>Occurrence#2 (Enfant)</th>
	<th>Occurrence#3 (Petit-enfant)</th>
	<th>Nb</th>
	<th></th>

</tr>

<?php

foreach($idg as $itm) {
	$ref = $itm['reference'];
	$compteur = (intval(mb_substr_count($ref, ","))+1);
	echo "<tr><td>".$itm['parent']."</td><td>".$itm['enfant']."</td><td>".$itm['petitenfant']."</td><td>".$compteur."</td>"; ?>
		<form method="POST" name="<?php echo $ref; ?>" action="<?php echo $this->getServerParam('PHP_SELF') ?>?page=consultresult">
		<input type="hidden" name="IDsession" value="<?php echo $donnees['session'][0]['IDsession']; ?>">
		<td><input type="submit" name="sel_tb" value="voir">
		<input type = "hidden" name = "sel_ref" value = "<?php echo $ref; ?>">
		
		</td>
		
		</form>
		<?php echo "</tr>";
	}
 ?>
 
</table>
</div>
</div>


<script>
// Bar chart
Chart.defaults.global.defaultFontColor = '#F5A320';
new Chart(document.getElementById("graphique_occ"), {
    type: 'bar',
	
    data: {
      labels: [<?php echo $label; ?>],
	  
      datasets: [
        {
		backgroundColor: "#030303",
		borderColor: "#F5A320",
		borderWidth: 1,
		label: 'références',
        data: [<?php echo $datx; ?>]
        }
      ]
    },
    options: {
      legend: { display: false },
	 
      title: {
        display: true,
        text: 'Classement des occurrences de la session selon leur fréquence (>1 ref.)'
      },
	  scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>