<script src="../web/librairies/js/Chart.js"></script>

<?php
	$nbref = "";
	$datx = "";
	$inverse = array_reverse($data['recupmajgp']);

	foreach($inverse as $item) {
	$nb = $item['nbref'];
	$date = DateTime::createFromFormat('Y-m-d', $item['annee']."-".$item['mois']."-".$item['jour']); 
	$newFormat = $date->format('d-m-Y');
	unset($date);
		$datx .= ",'$newFormat'";
		$nbref .= ",$nb";
	}
	$nbref = substr($nbref,1);
	$datx = substr($datx,1);


?>
	<div style="width: 33%; height: auto;display: inline-block;float : right;border : 1px solid white;">
	<p style="text-align : center"><span class="label_orange">Evolution du nb de références</span></p>
    <canvas id="historique"></canvas>
	</div>
<script>

var ctx = document.getElementById("historique").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: [<?php echo $datx; ?>],
    datasets: [{
      backgroundColor: [
        "#FA9507"
      ],fill: false,
      data: [<?php echo $nbref; ?>]
    }]
  },
      options: {
        legend: {
            display: false,
            labels: {
                display: false
            }
        },
		title: {
            display: false,
            text: 'NB de références'
        },
		scales:{
  xAxes:[{
	 color : "#FA9507",
    gridLines:{
      color:"rgba(255,255,255,0.5)",
      zeroLineColor:"rgba(255,255,255,0.5)"
    }
  }],
  yAxes:[{
    display:true
  }],
}
}
	});
</script>