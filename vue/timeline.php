
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
       

	   <div id="timeline" style="height: 25%;width : 100%;"></div>

<?php

///////////// RAJOUTER UNE COMBOBOX POUR RECHERCHER UNE OCCURRENCE ///////////////

$a=0;
foreach($data as $time) {
	$annee = $time['annee'];
	$nomparent = $time['lib_occurrence'];
	$mois = $time['mois']-1;
	$jour = $time['jour'];
	$tableau[$a] = array("occ"=>$nomparent,"annee"=>$annee,"mois"=>$mois,"jour"=>$jour);
	$a++;
}

?>
	   
<script>


google.charts.load('current', {'packages':['timeline']});
      google.charts.setOnLoadCallback(drawChart);
	
      function drawChart() {
		  
        var container = document.getElementById('timeline');
        var chart = new google.visualization.Timeline(container);
        var dataTable = new google.visualization.DataTable();

		var tableau_test = new Object();
		var tableau_test = <?php echo json_encode($tableau, JSON_PRETTY_PRINT) ?>;
		
		dataTable.addColumn({ type: 'string', id: 'occurence' });
		dataTable.addColumn({ type: 'date', id: 'Start' });
        dataTable.addColumn({ type: 'date', id: 'End' }); 
		
		for (var key in tableau_test) 
		{ // On stocke l'identifiant dans « id » pour parcourir l'objet

		data = [[tableau_test[key].occ, new Date(tableau_test[key].annee, tableau_test[key].mois, tableau_test[key].jour), new Date(tableau_test[key].annee, tableau_test[key].mois, tableau_test[key].jour) ]];
        dataTable.addRows(data);
}

		var options = {
		backgroundColor : '#000000',
		timeline: { rowLabelStyle: {fontSize: 12, color: '#F5A320' } },
		hAxis : { textStyle : {fontSize: 7 , color: '#F5A320'}}
		};
		
        chart.draw(dataTable, options);
      }
</script>