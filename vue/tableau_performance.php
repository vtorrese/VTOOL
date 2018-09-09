<div id="tableauperformance">
<div>
	<table>
		<tr>
			<th><span class="label_orange">Date de la MAJ</span></th><th><span class="label_orange">Performance de la MAJ</span></th>
		</tr>
<?php 
$cpt=1;
$nbrefx[0] = 0;
$tab = [];
$inverse = array_reverse($data['recupmajgp']);
foreach($inverse as $maj) {
	$nbrefx[$cpt] = $maj['nbref'];
	$date = $maj['jour']."/".$maj['mois']."/".$maj['annee'];
	$nbnew = $nbrefx[$cpt]-$nbrefx[$cpt-1];
	$perf = substr($nbnew/($maj['nblien']*$maj['nbmtclf'])*100,0,4);
	$tab[$cpt] = ['date' =>$date, 'perf' => $perf];
	$cpt++;
	}

foreach(array_reverse($tab) as $itm) {
	echo "<tr>";
	echo "<td>".$itm['date']."</td><td>".$itm['perf']." %</td>";
	echo "</tr>";
}

?>
</table>
</div>
</div>